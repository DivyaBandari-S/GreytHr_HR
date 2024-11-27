<?php

namespace App\Livewire;

use App\Helpers\FlashMessageHelper;
use App\Mail\LeaveApprovalNotification;
use App\Mail\LeaveReminderMail;
use App\Models\EmployeeDetails;
use App\Models\LeaveRequest;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class YearEndProcess extends Component
{
    public $employeeIds = [];
    public $pendingLeaveRequests;
    public $employeeDetailsList;
    public $managerDetails;


    public function mount()
    {
        try {
            // Get the logged-in employee's ID
            $loggedInEmpID = auth()->guard('hr')->user()->emp_id;

            // Fetch the company ID for the logged-in employee
            $companyID = EmployeeDetails::where('emp_id', $loggedInEmpID)
                ->pluck('company_id')
                ->first();

            // Decode the company IDs if necessary
            $companyIdsArray = is_array($companyID) ? $companyID : json_decode($companyID, true);

            // Query employees based on company IDs and status
            $this->employeeIds = EmployeeDetails::where(function ($query) use ($companyIdsArray) {
                foreach ($companyIdsArray as $companyId) {
                    $query->orWhere('company_id', 'like', "%\"$companyId\"%");
                }
            })
                ->whereIn('employee_status', ['active', 'on-probation'])
                ->pluck('emp_id')
                ->toArray();
        } catch (\Illuminate\Database\QueryException $e) {
            // Handle database exceptions
            Log::error("Database query error in mount: " . $e->getMessage());
            FlashMessageHelper::flashError('There was a database error while loading the employee list.');
        } catch (\Exception $e) {
            // Handle general exceptions
            Log::error("Unexpected error in mount: " . $e->getMessage());
            FlashMessageHelper::flashError('An unexpected error occurred while loading the employee list.');
        }
    }

    public function getPendingLeaveRequests()
    {
        try {
            // Ensure $this->employeeIds is not empty
            if (empty($this->employeeIds)) {
                throw new \Exception('Employee IDs are not available.');
            }

            // Calculate the date exactly 3 working days ago
            $dateThreshold = $this->subtractWorkingDays(3);

            // Query leave requests where the difference between created_at and current date is exactly 3 working days
            $this->pendingLeaveRequests = LeaveRequest::whereIn('emp_id', $this->employeeIds)
                ->whereDate('created_at', '<=', $dateThreshold) // Fetch requests on or before 3 working days ago
                ->where(function ($query) {
                    $query->where('leave_status', 5)
                        ->orWhere(function ($query) {
                            $query->where('leave_status', 2)
                                ->where('cancel_status', 7);
                        });
                })
                ->orderBy('created_at', 'desc')
                ->get();

            // Process pending leave requests
            foreach ($this->pendingLeaveRequests as $request) {
                // Retrieve employee details
                $employeeDetails = EmployeeDetails::where('emp_id', '=', $request->emp_id)->first();
                if ($employeeDetails) {
                    // Retrieve manager details if manager_id is not null
                    $managerId = $employeeDetails->manager_id;
                    $this->managerDetails = $managerId
                        ? EmployeeDetails::where('emp_id', '=', $managerId)->first()
                        : null;
                } else {
                    $this->managerDetails = null; // Handle case when employee details are not found
                }
            }
        } catch (\Exception $e) {
            // Log and flash error
            Log::error("Error in getPendingLeaveRequests: " . $e->getMessage());
            FlashMessageHelper::flashError('An error occurred while fetching pending leave requests.');
            return collect(); // Return an empty collection on error
        }
    }

    private function subtractWorkingDays($days)
    {
        $date = Carbon::now(); // Current date
        while ($days > 0) {
            $date->subDay(); // Subtract one day
            // Skip weekends (Saturday and Sunday)
            if (!$date->isWeekend()) {
                $days--;
            }
        }
        return $date->toDateString(); // Return the date in 'Y-m-d' format
    }

    // Modify to handle multiple IDs
    public $selectedLeaveRequestIds = [];
    public $selectAll = false;

    public function toggleSelectAll()
    {
        if ($this->selectAll) {
            // Select all leave requests
            $this->selectedLeaveRequestIds = $this->pendingLeaveRequests->pluck('id')->toArray();
        } else {
            // Deselect all leave requests
            $this->selectedLeaveRequestIds = [];
        }
    }

    // Watch for changes in selectedLeaveRequestIds and update selectAll accordingly
    public function updatedSelectedLeaveRequestIds()
    {
        // If all leave requests are selected, check the "Select All" checkbox
        $this->selectAll = count($this->selectedLeaveRequestIds) === $this->pendingLeaveRequests->count();
    }


    // Function to select multiple leave request IDs
    public function getSelectedEmp($id)
    {
        if (!in_array($id, $this->selectedLeaveRequestIds)) {
            $this->selectedLeaveRequestIds[] = $id;
        } else {
            // Remove if already selected (toggle selection)
            $this->selectedLeaveRequestIds = array_diff($this->selectedLeaveRequestIds, [$id]);
        }
    }

    // Approve multiple leave requests
    public function approveLeave()
    {
        if (empty($this->selectedLeaveRequestIds)) {
            FlashMessageHelper::flashWarning('No leave requests selected for approval.');
            return;
        }

        foreach ($this->selectedLeaveRequestIds as $requestId) {
            $leaveRequest = LeaveRequest::find($requestId);

            if (!$leaveRequest) {
                FlashMessageHelper::flashError("Leave request with ID {$requestId} not found.");
                continue;
            }

            try {
                // Check if already approved
                if ($leaveRequest->leave_status === 2) {
                    FlashMessageHelper::flashWarning("Leave application for ID {$requestId} is already approved.");
                    continue;
                }

                $employeeId = auth()->user()->emp_id; // Assuming you have the logged-in employee ID
                $leaveRequest->leave_status = 2; // Approved
                $leaveRequest->updated_at = now();
                $leaveRequest->action_by = $employeeId;
                $leaveRequest->save();

                // Send notifications and emails (reuse existing logic)
                $this->sendApprovalNotifications($leaveRequest);
                $this->selectAll = false;
            } catch (\Exception $e) {
                Log::error("Error in approving leave request for ID {$requestId}: " . $e->getMessage());
                FlashMessageHelper::flashError("An error occurred while approving leave ID {$requestId}. Please try again later.");
            }
        }
        FlashMessageHelper::flashSuccess("Leave application approved successfully.");
        // Clear selected IDs after processing
        $this->selectedLeaveRequestIds = [];
    }

    private function sendApprovalNotifications($leaveRequest)
    {
        $sendEmailToEmp = EmployeeDetails::where('emp_id', $leaveRequest->emp_id)->pluck('email')->first();
        $applyingToDetails = json_decode($leaveRequest->applying_to, true);
        $ccToDetails = json_decode($leaveRequest->cc_to, true);
        $ccEmails = array_map(fn($cc) => $cc['email'], $ccToDetails);
        $ccEmails = array_slice($ccEmails, 0, 5); // Limit to 5

        Notification::create([
            'emp_id' => auth()->user()->emp_id,
            'notification_type' => 'leaveApprove',
            'leave_type' => $leaveRequest->leave_type,
            'assignee' => $leaveRequest->emp_id,
        ]);

        if (!empty($sendEmailToEmp)) {
            Mail::to($sendEmailToEmp)
                ->send(new LeaveApprovalNotification($leaveRequest, $applyingToDetails, $ccToDetails, true));
        }

        if (!empty($ccEmails)) {
            Mail::to($ccEmails)
                ->send(new LeaveApprovalNotification($leaveRequest, $applyingToDetails, $ccToDetails, false));
        }
    }

    public function rejectLeave()
    {
        if (empty($this->selectedLeaveRequestIds)) {
            FlashMessageHelper::flashWarning('No leave requests selected for approval.');
            return;
        }

        foreach ($this->selectedLeaveRequestIds as $requestId) {
            $leaveRequest = LeaveRequest::find($requestId);

            if (!$leaveRequest) {
                FlashMessageHelper::flashError("Leave request with ID {$requestId} not found.");
                continue;
            }

            try {
                // Check if already approved
                if ($leaveRequest->leave_status === 3) {
                    FlashMessageHelper::flashWarning("Leave application for ID {$requestId} is already rejected.");
                    continue;
                }

                $employeeId = auth()->user()->emp_id; // Assuming you have the logged-in employee ID
                $leaveRequest->leave_status = 3; // rejected
                $leaveRequest->updated_at = now();
                $leaveRequest->action_by = $employeeId;
                $leaveRequest->save();

                // Send notifications and emails (reuse existing logic)
                $this->sendApprovalNotifications($leaveRequest);
                $this->selectAll = false;
            } catch (\Exception $e) {
                Log::error("Error in rejecting leave request for ID {$requestId}: " . $e->getMessage());
                FlashMessageHelper::flashError("An error occurred while rejecting leave ID {$requestId}. Please try again later.");
            }
        }
        FlashMessageHelper::flashSuccess("Leave application rejected successfully.");
        // Clear selected IDs after processing
        $this->selectedLeaveRequestIds = [];
    }

    public function sendReminder()
    {
        if (empty($this->selectedLeaveRequestIds)) {
            FlashMessageHelper::flashError('No leave requests selected.');
            return;
        }

        try {
            foreach ($this->selectedLeaveRequestIds as $leaveRequestId) {
                $leaveRequest = LeaveRequest::find($leaveRequestId);

                if (!$leaveRequest) {
                    FlashMessageHelper::flashError("Leave request with ID {$leaveRequestId} not found.");
                    continue; // Skip to the next request if not found
                }

                $applyingToEmails = json_decode($leaveRequest->applying_to, true); // Decode the JSON field
                if (!is_array($applyingToEmails) || empty($applyingToEmails)) {
                    FlashMessageHelper::flashError("No valid recipient found for leave request {$leaveRequestId}.");
                    continue;
                }

                foreach ($applyingToEmails as $recipient) {
                    if (isset($recipient['email']) && filter_var($recipient['email'], FILTER_VALIDATE_EMAIL)) {
                        Mail::to($recipient['email'])->send(new LeaveReminderMail($leaveRequest));
                    }
                }
            }
            FlashMessageHelper::flashSuccess('Reminder emails sent successfully.');
            $this->selectAll = false;
            $this->showReminderModal = false;
            $this->selectedLeaveRequestIds = [];
        } catch (\Exception $e) {
            FlashMessageHelper::flashError('Failed to send reminder emails.');
        }
    }

    public $showReminderModal = false;
    public function toggleReminderModal(){
        $this->showReminderModal = !$this->showReminderModal;
    }
    public function render()
    {
        $this->getPendingLeaveRequests();
        return view('livewire.year-end-process', [
            'employeeIds' => $this->employeeIds,
            'pendingLeaveRequests' => $this->pendingLeaveRequests,
            'employeeDetailsList' => $this->employeeDetailsList,
            'managerDetails' => $this->managerDetails,

        ]);
    }
}

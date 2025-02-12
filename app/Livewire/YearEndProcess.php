<?php

namespace App\Livewire;

use App\Helpers\FlashMessageHelper;
use App\Helpers\LeaveHelper;
use App\Mail\LeaveApprovalNotification;
use App\Mail\LeaveReminderMail;
use App\Models\EmployeeDetails;
use App\Models\EmployeeLeaveBalances;
use App\Models\LeavePolicySetting;
use App\Models\LeaveRequest;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Support\Collection;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class YearEndProcess extends Component
{
    public $employeeIds = [];
    public $pendingLeaveRequests;
    public $employeeDetailsList;
    public $managerDetails;
    public $employeeReqDetails;
    public $showSettingsForYearEndProcess = false;
    public $leaveTypeList;
    public $selectedLeaveId = null;
    public $activeTab = 'nav-home';
    public $leaveData;
    public $searchQuery = '';
    public $startDate;

    public $selectedLeaveRequestIds = [];
    public $selectAll = false;
    public $endDate;
    public $yearRange;
    public $leavePolicyData = [];
    public $selectedYear;
    public $selectedLeaveTypes = [];
    public $selectedYearRange;
    public $leaveDetailsWithBalance = [];
    public $showYearEndProcessModal = false;
    public $selectedLeaveNames = [];
    public $filteredLeaveData = [];
    public function mount()
    {

        try {
            $this->selectedYear = now()->year;
            $currentYear = Carbon::now()->year;

            $this->selectedYearRange = 'Jan ' . $currentYear . ' - Dec ' . $currentYear;
            $this->yearRange = range(2000, now()->year + 5);

            if (request()->has('tab')) {
                $this->activeTab = request()->get('tab');
            }

            // Get the logged-in employee's ID
            $loggedInEmpID = auth()->guard('hr')->user()->emp_id;

            $this->leaveTypeList = LeavePolicySetting::select('leave_name', 'id')->get();
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

            $this->updateDateRange();
            $this->getLeaveTypes();
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

    //get leavepolicy data
    public function getLeaveTypes()
    {
        $this->leavePolicyData = LeavePolicySetting::all()->toArray();
    }

    // Update date range based on selected year
    public function updateDateRange()
    {
        try {
            if ($this->selectedYear) {
                // Update the start and end date based on the selected year
                $this->startDate = Carbon::createFromDate($this->selectedYear, 1, 1)->format('M Y');
                $this->endDate = Carbon::createFromDate($this->selectedYear, 12, 31)->format('M Y');
                // Update period options when the year range is updated
                $this->getAllEmpLeaveReq();
                // $this->getAvailedLeaves();
                $this->getLeaveTypeToLapse();
                $this->getEmployeeLeaveDetailsWithBalance();
            }
        } catch (\InvalidArgumentException $e) {
            // Handle invalid argument exceptions (e.g., invalid year format)
            Log::error("Invalid argument in updateDateRange: " . $e->getMessage());
            FlashMessageHelper::flashError('Invalid year selected. Please check your input.');
        } catch (\Exception $e) {
            // Handle any other unexpected errors
            Log::error("Unexpected error in updateDateRange: " . $e->getMessage());
            FlashMessageHelper::flashError('An unexpected error occurred while updating the date range.');
        }
    }
    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
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
                $this->employeeReqDetails = EmployeeDetails::where('emp_id', '=', $request->emp_id)->get();
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
        try {
            $date = Carbon::now(); // Current date

            while ($days > 0) {
                $date->subDay(); // Subtract one day
                // Skip weekends (Saturday and Sunday)
                if (!$date->isWeekend()) {
                    $days--;
                }
            }

            return $date->toDateString(); // Return the date in 'Y-m-d' format
        } catch (\Exception $e) {
            // Catch any exception that occurs and log the error or show a message
            FlashMessageHelper::flashError("An error occurred while subtracting working days: " . $e->getMessage());
            return null; // Optionally return null or a fallback value if the operation fails
        }
    }


    // Modify to handle multiple IDs
    public function toggleSelectAll()
    {
        try {
            if ($this->selectAll) {
                // Select all leave requests
                $this->selectedLeaveRequestIds = $this->pendingLeaveRequests->pluck('id')->toArray();
            } else {
                // Deselect all leave requests
                $this->selectedLeaveRequestIds = [];
            }
        } catch (\Exception $e) {
            // Catch any exception that occurs and log the error or show a message
            FlashMessageHelper::flashError("An error occurred while toggling select all: " . $e->getMessage());
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
        try {
            // Check if the $id is not already in the selectedLeaveRequestIds array
            if (!in_array($id, $this->selectedLeaveRequestIds)) {
                $this->selectedLeaveRequestIds[] = $id; // Add the ID to the array
            } else {
                // Remove the ID if it's already in the array (toggle selection)
                $this->selectedLeaveRequestIds = array_diff($this->selectedLeaveRequestIds, [$id]);
            }
        } catch (\Exception $e) {
            // Catch any exception that occurs and log the error or show a message
            FlashMessageHelper::flashError("An error occurred while selecting the employee: " . $e->getMessage());
        }
    }


    // Approve multiple leave requests
    public function approveLeave()
    {
        try {
            if (empty($this->selectedLeaveRequestIds)) {
                FlashMessageHelper::flashWarning('No leave requests selected for approval.');
                return;
            }

            foreach ($this->selectedLeaveRequestIds as $requestId) {
                try {
                    // Find the leave request by ID
                    $leaveRequest = LeaveRequest::find($requestId);

                    if (!$leaveRequest) {
                        FlashMessageHelper::flashError("Leave request with ID {$requestId} not found.");
                        continue;
                    }

                    // Check if already approved
                    if ($leaveRequest->leave_status === 2) {
                        FlashMessageHelper::flashWarning("Leave application for ID {$requestId} is already approved.");
                        continue;
                    }

                    // Set the leave request status to approved
                    $employeeId = auth()->user()->emp_id; // Get logged-in employee ID
                    $leaveRequest->leave_status = 2; // Approved
                    $leaveRequest->updated_at = now();
                    $leaveRequest->action_by = $employeeId;
                    $leaveRequest->save();

                    // Send notifications and emails
                    $this->sendApprovalNotifications($leaveRequest);
                    $this->selectAll = false; // Reset the "select all" flag
                } catch (\Exception $e) {
                    // Log the specific error for this leave request
                    Log::error("Error in approving leave request for ID {$requestId}: " . $e->getMessage());
                    FlashMessageHelper::flashError("An error occurred while approving leave ID {$requestId}. Please try again later.");
                }
            }

            FlashMessageHelper::flashSuccess("Leave applications approved successfully.");

            // Clear selected IDs after processing
            $this->selectedLeaveRequestIds = [];
        } catch (\Exception $e) {
            // Catch any error that occurs during the entire approval process
            Log::error("Error in the overall leave approval process: " . $e->getMessage());
            FlashMessageHelper::flashError("An error occurred while processing the leave approvals. Please try again later.");
        }
    }

    private function sendApprovalNotifications($leaveRequest)
    {
        try {
            // Fetch employee email
            $sendEmailToEmp = EmployeeDetails::where('emp_id', $leaveRequest->emp_id)->pluck('email')->first();
            // Decode 'applying_to' and 'cc_to' JSON data
            $applyingToDetails = json_decode($leaveRequest->applying_to, true);
            $ccToDetails = json_decode($leaveRequest->cc_to, true);

            // Prepare CC emails, limiting to 5 emails
            $ccEmails = array_map(fn($cc) => $cc['email'], $ccToDetails);
            $ccEmails = array_slice($ccEmails, 0, 5); // Limit to 5

            // Create notification entry in the database
            Notification::create([
                'emp_id' => auth()->user()->emp_id,
                'notification_type' => 'leaveApprove',
                'leave_type' => $leaveRequest->leave_type,
                'assignee' => $leaveRequest->emp_id,
            ]);

            // Send email to employee if email exists
            if (!empty($sendEmailToEmp)) {
                Mail::to($sendEmailToEmp)
                    ->send(new LeaveApprovalNotification($leaveRequest, $applyingToDetails, $ccToDetails, true));
            }

            // Send email to CC recipients if CC emails exist
            if (!empty($ccEmails)) {
                Mail::to($ccEmails)
                    ->send(new LeaveApprovalNotification($leaveRequest, $applyingToDetails, $ccToDetails, false));
            }
        } catch (\Exception $e) {
            // Log the error for debugging and provide user feedback
            Log::error("Error in sending approval notifications for leave request ID {$leaveRequest->id}: " . $e->getMessage());
            FlashMessageHelper::flashError("An error occurred while sending leave approval notifications. Please try again later.");
        }
    }


    //reject pedning leave application
    public function rejectLeave()
    {
        try {
            if (empty($this->selectedLeaveRequestIds)) {
                FlashMessageHelper::flashWarning('No leave requests selected for rejection.');
                return;
            }

            foreach ($this->selectedLeaveRequestIds as $requestId) {
                try {
                    // Find the leave request by ID
                    $leaveRequest = LeaveRequest::find($requestId);

                    if (!$leaveRequest) {
                        FlashMessageHelper::flashError("Leave request with ID {$requestId} not found.");
                        continue;
                    }

                    // Check if already rejected
                    if ($leaveRequest->leave_status === 3) {
                        FlashMessageHelper::flashWarning("Leave application for ID {$requestId} is already rejected.");
                        continue;
                    }

                    // Set the leave request status to rejected
                    $employeeId = auth()->user()->emp_id; // Get logged-in employee ID
                    $leaveRequest->leave_status = 3; // Rejected
                    $leaveRequest->updated_at = now();
                    $leaveRequest->action_by = $employeeId;
                    $leaveRequest->save();

                    // Send notifications and emails (reuse existing logic)
                    $this->sendApprovalNotifications($leaveRequest);
                    $this->selectAll = false; // Reset the "select all" flag
                } catch (\Exception $e) {
                    // Log the specific error for this leave request
                    Log::error("Error in rejecting leave request for ID {$requestId}: " . $e->getMessage());
                    FlashMessageHelper::flashError("An error occurred while rejecting leave ID {$requestId}. Please try again later.");
                }
            }

            FlashMessageHelper::flashSuccess("Leave application rejected successfully.");

            // Clear selected IDs after processing
            $this->selectedLeaveRequestIds = [];
        } catch (\Exception $e) {
            // Catch any error that occurs during the entire rejection process
            Log::error("Error in the overall leave rejection process: " . $e->getMessage());
            FlashMessageHelper::flashError("An error occurred while processing the leave rejections. Please try again later.");
        }
    }


    //send reminder mail for a manager to approve or reject pending leave
    public function sendReminder()
    {
        if (empty($this->selectedLeaveRequestIds)) {
            FlashMessageHelper::flashError('No leave requests selected.');
            return;
        }

        try {
            foreach ($this->selectedLeaveRequestIds as $leaveRequestId) {
                try {
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
                } catch (\Exception $e) {
                    // Log and handle error for individual leave request
                    Log::error("Error sending reminder for leave request {$leaveRequestId}: " . $e->getMessage());
                    FlashMessageHelper::flashError("An error occurred while sending reminder for leave request {$leaveRequestId}.");
                }
            }

            FlashMessageHelper::flashSuccess('Reminder emails sent successfully.');
            $this->selectAll = false;
            $this->showReminderModal = false;
            $this->selectedLeaveRequestIds = [];
        } catch (\Exception $e) {
            // Log and handle error for the entire process
            Log::error("Error in the sendReminder process: " . $e->getMessage());
            FlashMessageHelper::flashError('Failed to send reminder emails. Please try again later.');
        }
    }


    public $showReminderModal = false;
    public function toggleReminderModal()
    {
        $this->showReminderModal = !$this->showReminderModal;
    }
    public function openSettingsContainer($id)
    {
        $this->selectedLeaveId = $id;
        $this->showSettingsForYearEndProcess = true;
    }
    public function getAvailedLeaves()
    {
        // Initialize an array to hold leave balances
        $leaveAvailedReq = [];

        // Chunk the employee IDs to avoid overloading the database
        $chunks = array_chunk($this->employeeIds, 50); // You can adjust the chunk size

        // Iterate over each chunk of employees
        foreach ($chunks as $chunk) {
            try {
                // Fetch leave balances for the chunk of employee IDs
                $leaveBalances = YearEndProcess::getLeaveBalances($chunk, $this->selectedYear);

                // Merge the results for the current chunk with the overall leave data
                $leaveAvailedReq = array_merge($leaveAvailedReq, $leaveBalances);
            } catch (\Exception $e) {
                // Log error and continue with the next chunk
                Log::error("Error fetching leave balances for employees in chunk: " . $e->getMessage());

                // Optionally, store a default error message or null value for those employees in the chunk
                foreach ($chunk as $employeeId) {
                    $leaveAvailedReq[$employeeId] = 'Error fetching data';
                }
            }
        }

        // Return the leave balances array
        return $leaveAvailedReq;
    }



    public $empLeaveAvailedCount = [];
    //manual process for lasped leaves
    public function getAllEmpLeaveReq()
    {
        try {
            // Get the logged-in employee's ID
            $loggedInEmpID = auth()->guard('hr')->user()->emp_id;

            // Fetch the company ID for the logged-in employee
            $companyID = EmployeeDetails::where('emp_id', $loggedInEmpID)
                ->pluck('company_id')
                ->first();

            // Check if company ID is an array or a string and decode it if necessary
            $companyIdsArray = is_array($companyID) ? $companyID : json_decode($companyID, true);

            // Query employees based on company IDs and status
            $this->employeeIds = EmployeeDetails::where(function ($query) use ($companyIdsArray) {
                foreach ($companyIdsArray as $companyId) {
                    $query->orWhere('company_id', 'like', "%\"$companyId\"%");
                }
            })
                ->whereIn('employee_status', ['active', 'on-probation'])
                ->when(!empty($this->searchQuery), function ($query) {
                    // Apply the search query (assuming you're searching by employee name)
                    $query->where(function ($query) {
                        $query->where('emp_id', 'like', "%{$this->searchQuery}%");
                    });
                })
                ->pluck('emp_id')
                ->toArray();

            // Now fetch the leave balances for these employees from the employee_leavebalance table
            $leaveBalances = EmployeeLeaveBalances::whereIn('emp_id', $this->employeeIds)
                ->where('is_lapsed', false)
                ->whereNull('lapsed_date')
                ->where('granted_for_year', (string) $this->selectedYear) // Use this if granted_for_year is an integer
                ->get(['emp_id', 'leave_policy_id']);

            // Map leave balances with employee details (this assumes you need to return the balance for each employee)
            $leaveData = $leaveBalances->map(function ($leaveBalance) {
                // Decode the leave_policy_id (assuming it's stored as a JSON string)
                $leavePolicies = json_decode($leaveBalance->leave_policy_id, true);

                // Initialize an array to store the leave details for this employee
                $leaveDetails = [];

                // Iterate over each leave policy and fetch the leave_name and grant_days
                foreach ($leavePolicies as $policy) {
                    $leaveDetails[] = [
                        'leave_name' => $policy['leave_name'] ?? null,
                        'grant_days' => $policy['grant_days'] ?? 0,
                    ];
                }

                // Return the data with employee ID and leave details
                return [
                    'emp_id' => $leaveBalance->emp_id,
                    'leave_details' => $leaveDetails,
                ];
            });

            // If you need to return the leave data for the front-end
            return $leaveData;
        } catch (\Exception $e) {
            // Log the error message and provide feedback
            Log::error("Error fetching leave requests for employees: " . $e->getMessage());

            // Optionally, flash an error message to the user
            FlashMessageHelper::flashError('An error occurred while fetching employee leave data. Please try again later.');

            // Return an empty array or null to indicate failure
            return [];
        }
    }

    public $leaveType = 'Sick Leave';
    //employee details with balance granted leave balance
    public function getEmployeeLeaveDetailsWithBalance()
    {
        try {
            // Fetch availed leaves
            $availedLeaves = $this->getAvailedLeaves();
            // Fetch allowed leave policies
            $allowedLeaves = $this->getAllEmpLeaveReq();

            // Ensure $allowedLeaves is a Laravel Collection
            $allowedLeaves = collect($allowedLeaves);

            // Now you can use the groupBy method on the collection
            $groupedLeaves = $allowedLeaves->groupBy('emp_id');


            // Initialize an array to hold the final data
            $leaveDetailsWithBalance = [];

            // Iterate through each employee's leave data
            foreach ($groupedLeaves as $empId => $leaveGroup) {

                $leaveDetails = $leaveGroup->flatMap(function ($leave) {
                    return $leave['leave_details'];
                });

                // Ensure there are leave details for this employee
                if (!empty($leaveDetails)) {
                    // Use map to modify each leave item in the collection
                    $leaveDetails = $leaveDetails->map(function ($leave) use ($empId, $availedLeaves) {
                        // Generate the key for the leave type balance from the leave name
                        $leaveNameKey = lcfirst(str_replace(' ', '', $leave['leave_name'])) . 'Balance';

                        // Check if balance data exists for the leave type in $availedLeaves
                        if (isset($availedLeaves[$empId][$leaveNameKey])) {
                            // Calculate the remaining balance for the leave type
                            if ($availedLeaves[$empId][$leaveNameKey] >= 0) {
                                $remainingBalance =  $availedLeaves[$empId][$leaveNameKey];
                            } elseif ($availedLeaves[$empId][$leaveNameKey] >  $leave['grant_days']) {
                                $remainingBalance = $leave['grant_days'] - $availedLeaves[$empId][$leaveNameKey];
                            } else {
                                $remainingBalance = $leave['grant_days'] - $availedLeaves[$empId][$leaveNameKey];
                            }
                            $leave['remaining_balance'] = $remainingBalance; // Ensure balance doesn't go negative
                        } else {
                            // If no balance data found, set remaining balance to 0
                            $leave['remaining_balance'] = 0;
                        }

                        // Return the updated leave
                        return $leave;
                    });

                    // If a leave type is selected, filter by that type
                    if ($this->leaveType !== 'All') {
                        $leaveDetails = $leaveDetails->filter(function ($leave) {
                            return stripos($leave['leave_name'], $this->leaveType) !== false;
                        });
                    }

                    // Add the updated leave details with balance for this employee
                    $leaveDetailsWithBalance[$empId] = [
                        'emp_id' => $empId,
                        'leave_details' => $leaveDetails,
                    ];
                }
            }

            // Sort the array by employee ID
            ksort($leaveDetailsWithBalance);

            // Return the result
            return $leaveDetailsWithBalance;
        } catch (\Exception $e) {
            // Log the error message and provide feedback
            Log::error("Error fetching employee leave details with balance: " . $e->getMessage());

            // Optionally, flash an error message to the user
            FlashMessageHelper::flashError('An error occurred while fetching employee leave details. Please try again later.');

            // Return an empty array or null to indicate failure
            return [];
        }
    }



    //we can getting leave baalcne from this method for each leave type
    public static function getLeaveBalances(array $employeeIds, $selectedYear)
    {
        try {
            // Fetch employee details for the batch of employee IDs
            $employeeDetails = EmployeeDetails::whereIn('emp_id', $employeeIds)->get();

            // If no employees found, return an empty array
            if ($employeeDetails->isEmpty()) {
                return [];
            }

            // Prepare an array to hold the leave balances for each employee
            $leaveBalances = [];

            // Loop through each employee to fetch their leave balances
            foreach ($employeeDetails as $employee) {
                // Get leave balances per year for each employee
                $sickLeavePerYear = EmployeeLeaveBalances::getLeaveBalancePerYear($employee->emp_id, 'Sick Leave', $selectedYear);
                $casualLeavePerYear = EmployeeLeaveBalances::getLeaveBalancePerYear($employee->emp_id, 'Casual Leave', $selectedYear);
                $casualProbationLeavePerYear = EmployeeLeaveBalances::getLeaveBalancePerYear($employee->emp_id, 'Casual Leave Probation', $selectedYear);
                $marriageLeaves = EmployeeLeaveBalances::getLeaveBalancePerYear($employee->emp_id, 'Marriage Leave', $selectedYear);
                $maternityLeaves = EmployeeLeaveBalances::getLeaveBalancePerYear($employee->emp_id, 'Maternity Leave', $selectedYear);
                $paternityLeaves = EmployeeLeaveBalances::getLeaveBalancePerYear($employee->emp_id, 'Paternity Leave', $selectedYear);

                // Get the logged-in employee's approved leave days for the selected year
                $approvedLeaveDays = LeaveHelper::getApprovedLeaveDays($employee->emp_id, $selectedYear);

                // Calculate the remaining balances for each leave type
                $sickLeaveBalance = $sickLeavePerYear - $approvedLeaveDays['totalSickDays'];
                $casualLeaveBalance = $casualLeavePerYear - $approvedLeaveDays['totalCasualDays'];
                $lossOfPayBalance = $approvedLeaveDays['totalLossOfPayDays'];
                $casualProbationLeaveBalance = $casualProbationLeavePerYear - $approvedLeaveDays['totalCasualLeaveProbationDays'];
                $marriageLeaveBalance = $marriageLeaves - $approvedLeaveDays['totalMarriageDays'];
                $maternityLeaveBalance = $maternityLeaves - $approvedLeaveDays['totalMaternityDays'];
                $paternityLeaveBalance = $paternityLeaves - $approvedLeaveDays['totalPaternityDays'];

                // Store the leave balances for the employee
                $leaveBalances[$employee->emp_id] = [
                    'sickLeaveBalance' => $sickLeaveBalance,
                    'casualLeaveBalance' => $casualLeaveBalance,
                    'lossOfPayBalance' => $lossOfPayBalance,
                    'casualProbationLeaveBalance' => $casualProbationLeaveBalance,
                    'marriageLeaveBalance' => $marriageLeaveBalance,
                    'maternityLeaveBalance' => $maternityLeaveBalance,
                    'paternityLeaveBalance' => $paternityLeaveBalance,
                ];
            }
            // Return the leave balances for the batch of employees
            return $leaveBalances;
        } catch (\Illuminate\Database\QueryException $e) {
            // Handle database-related errors (e.g., query issues)
            Log::error('Database query exception in getLeaveBalances: ' . $e->getMessage());
            FlashMessageHelper::flashError('Database connection error occurred. Please try again later.');
            return [];
        } catch (\Exception $e) {
            // Catch any other exceptions
            Log::error('General exception in getLeaveBalances: ' . $e->getMessage());
            FlashMessageHelper::flashError('Failed to retrieve leave balances. Please try again later.');
            return [];
        }
    }



    public function triggerYearEndProcess()
    {
        $this->showYearEndProcessModal = !$this->showYearEndProcessModal;
    }
    public function updateSelectedLeaveTypes($leaveId)
    {
        // Ensure that selectedLeaveTypes is not empty
        if (empty($this->selectedLeaveTypes)) {
            FlashMessageHelper::flashWarning("Please select at least one leave type.");
            return;
        }
        try {
            // Check if the leaveId is already in the array
            if (in_array($leaveId, $this->selectedLeaveTypes)) {
                // Remove the leaveId from the array (uncheck the checkbox)
                $this->selectedLeaveTypes = array_diff($this->selectedLeaveTypes, [$leaveId]);
            } else {
                // Add the leaveId to the array (check the checkbox)
                $this->selectedLeaveTypes[] = $leaveId;
            }

            // Fetch the leave names corresponding to the selected leave IDs
            $this->fetchLeaveNames();

            // Fetch the corresponding EmployeeLeaveBalances for selected leave types
            $this->getLeaveTypeToLapse();
        } catch (\Exception $e) {
            // Catch any exception that occurs and log the error or show a message
            FlashMessageHelper::flashError("An error occurred while updating the selected leave types: " . $e->getMessage());
        }
    }

    public function fetchLeaveNames()
    {
        try {
            // Fetch the leave names from LeavePolicySettings based on the selected leave types
            $leaveNames = LeavePolicySetting::whereIn('id', $this->selectedLeaveTypes)
                ->pluck('leave_name', 'id');

            // Store the leave names for later comparison
            $this->selectedLeaveNames = array_values($leaveNames->toArray());
        } catch (\Exception $e) {
            // Catch any exception that occurs and log the error or show a message
            FlashMessageHelper::flashError("An error occurred while fetching leave names: " . $e->getMessage());
        }
    }

    public function getLeaveTypeToLapse()
    {

        try {
            // Compare with EmployeeLeaveBalances for the selected leave types
            $this->filteredLeaveData = EmployeeLeaveBalances::where(function ($query) {
                // Loop through selected leave types and add whereJsonContains for each
                foreach ($this->selectedLeaveNames as $leaveType) {
                    // Use whereJsonContains to search for the `leave_name` inside the JSON array
                    $query->orWhereJsonContains('leave_policy_id', [
                        'leave_name' => $leaveType
                    ]);
                }
            })
                ->where('granted_for_year', $this->selectedYear)
                ->where('is_lapsed', false)
                ->get();

            // If no filteredLeaveData is found
            if (empty($this->filteredLeaveData)) {
                FlashMessageHelper::flashError("No matching records found.");
                return;
            }

            // Optionally, you can display or store the data for confirmation
        } catch (\Exception $e) {
            // Catch any exception that occurs and log the error or show a message
            FlashMessageHelper::flashError("An error occurred while fetching leave data: " . $e->getMessage());
        }
    }


    //year end process method
    public function changeToLapsed()
    {
        // Ensure there is filtered data to update
        if (empty($this->filteredLeaveData)) {
            FlashMessageHelper::flashWarning("No leave data selected to update.");
            return;
        }

        // Check if any of the filtered leave data is already lapsed
        $alreadyLapsed = false;
        foreach ($this->filteredLeaveData as $data) {
            if ($data->is_lapsed && !empty($data->lapsed_date)) {
                $alreadyLapsed = true;
                break;  // If any leave data is already lapsed, break the loop
            }
        }

        // If any leave data is already lapsed, show a warning message
        if ($alreadyLapsed) {
            FlashMessageHelper::flashWarning("Some leave data for the selected year is already marked as lapsed for " . $this->selectedYear . ".");
            return; // Stop further processing
        }

        // Collect employee IDs for batch processing
        $employeeIds = array_column($this->filteredLeaveData, 'emp_id');

        // Set the batch size (e.g., process 100 employees per batch)
        $batchSize = 100;
        $chunks = array_chunk($employeeIds, $batchSize);

        // To track employees we have already created a new record for
        $processedEmployees = [];

        // To hold leave balance data for the chunks
        $leaveAvailedReq = [];

        try {
            foreach ($chunks as $chunk) {
                try {
                    // Fetch leave balances for the chunk of employee IDs
                    $leaveBalances = YearEndProcess::getLeaveBalances($chunk, $this->selectedYear);

                    // Merge the results for the current chunk with the overall leave data
                    $leaveAvailedReq = array_merge($leaveAvailedReq, $leaveBalances);
                } catch (\Exception $e) {
                    // Log error and continue with the next chunk
                    Log::error("Error fetching leave balances for employees in chunk: " . $e->getMessage());

                    // Optionally, store a default error message or null value for those employees in the chunk
                    foreach ($chunk as $employeeId) {
                        $leaveAvailedReq[$employeeId] = 'Error fetching data';
                    }
                }
            }

            // Now loop through the filtered leave data and process it
            foreach ($this->filteredLeaveData as $data) {
                // Get leave balance for the employee from the merged leave balances
                $employeeLeaveBalance = $leaveAvailedReq[$data->emp_id] ?? null;

                // If no leave balance data is found, skip to next iteration
                if (!$employeeLeaveBalance || $employeeLeaveBalance === 'Error fetching data') {
                    continue;
                }

                // Decode the leave_policy_id (JSON string) into an array
                $leavePolicy = json_decode($data->leave_policy_id, true);
                // Check if the leave policy contains "Sick Leave"
                $isSickLeave = false;
                foreach ($leavePolicy as $leave) {
                    if ($leave['leave_name'] == 'Sick Leave') {
                        $isSickLeave = true;
                        break;
                    }
                }

                // If it's a sick leave, update status to 'opening balance' and skip the 'is_lapsed' update
                if ($isSickLeave) {
                    // Check sick leave balance
                    $sickLeaveBalance = $employeeLeaveBalance['sickLeaveBalance'] ?? 0;
                    $data->lapsed_date = now();
                    $data->save();

                    // Check if we've already processed this employee
                    if (!in_array($data->emp_id, $processedEmployees)) {
                        // Replicate the existing record to create a new record with the same attributes
                        $newRecord = $data->replicate();
                        // Set the new status to 'opening balance'
                        $newRecord->status = 'opening balance';
                        $leavePolicyNew = json_decode($newRecord->leave_policy_id, true);
                        // Update the 'grant_days' with the sick leave balance
                        foreach ($leavePolicyNew as &$policy) {
                            if ($policy['leave_name'] == 'Sick Leave') {
                                $policy['grant_days'] = $sickLeaveBalance;
                                break; // Exit the loop once Sick Leave is found and updated
                            }
                        }

                        // Set 'is_lapsed' to false since this is a new record
                        $newRecord->is_lapsed = false;
                        $newRecord->lapsed_date = null;
                        // Save the new record to the database
                        $newRecord->save();

                        // Mark this employee as processed
                        $processedEmployees[] = $data->emp_id;
                    }
                } else {
                    // For other leave types, update the status to 'lapsed'
                    $data->update([
                        'is_lapsed' => true,
                        'lapsed_date' => now(),
                    ]);
                }
            }

            // Provide feedback to the user
            FlashMessageHelper::flashSuccess('Leave types successfully changed to lapsed.');

            // Optionally hide any modals or reset states
            $this->showYearEndProcessModal = false;
        } catch (\Exception $e) {
            // Catch any exception that occurs and log the error or show a message
            FlashMessageHelper::flashError("An error occurred while updating leave data: " . $e->getMessage());
        }
    }


    public $totalImages;
    public $totalPages;
    public $perPage = 10;
    public $paginatedImages;
    public $currentPage = 1;
    public function setPage($page)
    {
        // Ensure the page number is within the valid range
        $this->currentPage = max(1, min($page, ceil($this->totalImages / $this->perPage)));

        // Get the paginated image paths and store them in the component property
        $this->paginatedImages = $this->getPaginatedImages();
    }


    public function getPaginatedImages()
    {
        // Ensure imagePaths is not null and is an array
        $leaveDetailsWithBalance = $this->leaveDetailsWithBalance ?? [];

        // Use array_slice to paginate the image paths array
        return array_slice($leaveDetailsWithBalance, ($this->currentPage - 1) * $this->perPage, $this->perPage);
    }
    public function render()
    {
        $this->getPendingLeaveRequests();
        // $this->leaveData = $this->getAllEmpLeaveReq();
        $this->leaveDetailsWithBalance = $this->getEmployeeLeaveDetailsWithBalance();
        $this->totalImages = count($this->leaveDetailsWithBalance);
        $this->totalPages = ceil($this->totalImages / $this->perPage);
        $this->paginatedImages = array_slice($this->leaveDetailsWithBalance, ($this->currentPage - 1) * $this->perPage, $this->perPage);

        return view('livewire.year-end-process', [
            'employeeIds' => $this->employeeIds,
            'pendingLeaveRequests' => $this->pendingLeaveRequests,
            'employeeDetailsList' => $this->employeeDetailsList,
            'managerDetails' => $this->managerDetails,
            'employeeReqDetails' => $this->employeeReqDetails,
            'leaveTypeList' => $this->leaveTypeList,
            'leaveData' => $this->leaveData,
            'leaveDetailsWithBalance' => $this->leaveDetailsWithBalance,
            'paginatedImages' => $this->paginatedImages,
            'currentPage' => $this->currentPage,
            'totalImages' => $this->totalImages,
            'totalPages' => $this->totalPages,
        ]);
    }
}

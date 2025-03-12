<?php

namespace App\Livewire;

use Livewire\Component;
use App\Helpers\FlashMessageHelper;
use App\Mail\LeaveApplicationNotification;
use App\Models\EmployeeDetails;
use App\Models\EmployeeLeaveBalances;
use App\Models\HolidayCalendar;
use App\Models\Hr;
use App\Models\LeaveRequest;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class LeaveApplyOnBehalf extends Component
{
    use WithFileUploads;
    public $leave_type;
    public $employeeId;
    public $currentYear;
    public $leaveType;
    public $searchQuery = '';
    public $emp_id;
    public $casualLeavePerYear;
    public $casualProbationLeavePerYear;
    public $marriageLeaves;
    public $maternityLeaves;
    public $paternityLeaves;
    public $sickLeavePerYear;
    public $lossOfPayPerYear;
    public $from_date;
    public $from_session = 'Session 1';
    public $to_session = 'Session 2';
    public $to_date;
    public $applying_to;
    public $contact_details;
    public $reason;
    public $selectedPeople = [];
    public  $showinfoMessage = true;
    public  $showinfoButton = false;
    public $showNumberOfDays = false;
    public $differenceInMonths;
    public $show_reporting = false;
    public $showApplyingTo = true;
    public $leaveBalances = [];
    public $selectedYear;
    public $createdLeaveRequest;
    public $calculatedNumberOfDays = 0;
    public $employeeDetails = [];
    public $showPopupMessage = false;
    public $numberOfDays;
    public $showApplyingToContainer = false;
    public $loginEmpManagerProfile;
    public $loginEmpManager;
    public $cc_to;
    public $showCcRecipents = false;
    public $loginEmpManagerId;
    public $employee;
    public $managerFullName = [];
    public $ccRecipients;
    public $selectedEmployee = [];
    public $selectedManager = [];

    public $searchTerm = '';
    public $filter = '';
    public $errorMessage = '';
    public $fromDate;
    public  $file_paths;
    public $fromSession;
    public $toSession;
    public $toDate;
    public $filePath;
    public $selectedCcTo = [];
    public $selectedCCEmployees = [];
    public $showerrorMessage = false;
    public $showCasualLeaveProbation, $showCasualLeaveProbationYear;
    public $field;
    public $managerDetails, $fullName;
    public $empManagerDetails;
    public $showEmployeeSearch;
    public $seleceted_emp_id;
    public  $selectedManagerDetails;
    public $hasReachedLimit = false;
    public $hrAppliedLeaveRequests;

    protected $rules = [
        'leave_type' => 'required',
        'from_date' => 'required|date',
        'from_session' => 'required',
        'to_date' => 'required|date',
        'to_session' => 'required',
        'contact_details' => 'required',
        'reason' => 'required',
        'file_paths.*' => 'nullable|file|mimes:xls,csv,xlsx,pdf,jpeg,png,jpg,gif,zip|max:1024',
    ];

    protected $messages = [
        'leave_type.required' => 'Leave type is required',
        'from_date.required' => 'From date is required',
        'from_session.required' => 'Session is required',
        'to_date.required' => 'To date is required',
        'to_session.required' => 'Session is required',
        'contact_details.required' => 'Contact details are required',
        'reason.required' => 'Reason is required',
        'file_paths.*.max' => 'Your file is larger than 1 MB. Please select a file of up to 1 MB only.',
        'file_paths.*.mimes' => 'Please upload a file of type: xls, csv, xlsx, pdf, jpeg, png, jpg, gif.',
    ];
    public function validateField($propertyName)
    {
        $this->validateOnly($propertyName);
    }
    public function mount()
    {
        try {
            $this->getHrAppliedLeaveList();
            $this->selectedPeople = [];
            $this->searchTerm = '';
            $this->searchQuery = '';
            $this->selectedYear = Carbon::now()->format('Y');
        } catch (\Exception $e) {
            // Optionally, notify the user using FlashMessageHelper
            FlashMessageHelper::flashError('An error occurred while loading the data. Please try again.');
            return false;
        }
    }

    //get fetching the leave requests data which are applied by HR on behalf of an employee
    public function getHrAppliedLeaveList()
    {
        try {
            // Fetch leave requests with employee details
            $this->hrAppliedLeaveRequests = LeaveRequest::with('employee')
                ->whereNotNull('applied_by')
                ->orderBy('created_at')
                ->get();
    
            // Process each leave request
            foreach ($this->hrAppliedLeaveRequests as $leaveRequest) {
                $createdAt = Carbon::parse($leaveRequest->created_at);
    
                // Check if the leave request is within this week using startOfWeek and endOfWeek
                $startOfWeek = Carbon::now()->startOfWeek();
                $endOfWeek = Carbon::now()->endOfWeek();
    
                if ($createdAt->between($startOfWeek, $endOfWeek)) {
                    $leaveRequest->formatted_created_at = $createdAt->format('l h:i A'); // e.g., "Wednesday 4:00 PM"
                } else {
                    $leaveRequest->formatted_created_at = $createdAt->format('Y-m-d'); // e.g., "2025-03-06"
                }
            }
    
        } catch (\Exception $e) {
            // Log the exception message
            Log::error('Error while fetching or formatting leave requests: ' . $e->getMessage());

            // Optionally, you could return a message or perform another action if something goes wrong
            FlashMessageHelper::flashError('Something went wrong while processing leave requests.');
        }
    }

    //get manager details
    public function getManagerDetailsForEmp()
    {
        try {
            $employeeId = $this->seleceted_emp_id;
            $this->employee = EmployeeDetails::where('emp_id', $employeeId)->first();

            if ($this->employee) {
                $managerId = $this->employee->manager_id;
                // Fetch the logged-in employee's manager details
                $managerDetails = EmployeeDetails::where('emp_id', $managerId)->first();

                if ($managerDetails) {
                    // Format the manager's full name
                    $fullName = ucfirst(strtolower($managerDetails->first_name)) . ' ' . ucfirst(strtolower($managerDetails->last_name));
                    $this->loginEmpManager = $fullName;
                    $this->selectedManagerDetails = $managerDetails;
                } else {
                    // Handle case where no manager is found
                    if (is_null($managerId)) {
                        // Get the company_id from the logged-in employee's details
                        $companyId = $this->employee->company_id;

                        // Fetch emp_ids from the HR table
                        $hrEmpIds = Hr::pluck('emp_id');

                        // Fetch employee details for these HR emp_ids
                        $hrManagers = EmployeeDetails::whereIn('emp_id', $hrEmpIds)
                            ->whereJsonContains('company_id', $companyId) // Ensure company_id matches
                            ->get();

                        if ($hrManagers->isNotEmpty()) {
                            // Select the first manager
                            $firstManager = $hrManagers->first();
                            $fullName = ucfirst(strtolower($firstManager->first_name)) . ' ' . ucfirst(strtolower($firstManager->last_name));
                            $this->loginEmpManager = $fullName;
                            $this->selectedManagerDetails = $firstManager;
                        } else {
                            // Handle case where no HR managers are found
                            $this->loginEmpManager = 'No manager found';
                            $this->selectedManagerDetails = null;
                        }
                    }
                }

                // Determine if the dropdown option should be displayed
                $this->showCasualLeaveProbation = $this->employee && !$this->employee->confirmation_date;
                $currentYear = date('Y');
                $this->showCasualLeaveProbationYear = $this->employee &&
                    !empty($this->employee->confirmation_date) &&
                    (date('Y', strtotime($this->employee->confirmation_date)) == $currentYear);
            } else {
                $this->employee = null;
                $this->managerDetails = null;
            }
        } catch (\Exception $e) {
            // Optionally, notify the user using FlashMessageHelper
            FlashMessageHelper::flashError('An error occurred while loading the data. Please try again.');
            return false;
        }
    }


    public function getSelectedEmp($empId)
    {
        $this->showEmployeeSearch = false;
        $this->seleceted_emp_id = $empId;
        $this->getApplyingToDetails();
        $this->getEmpDetailsFor();
    }
    public function closeSearchContainer()
    {
        $this->showEmployeeSearch = !$this->showEmployeeSearch;
        $this->getEmpDetailsFor();
    }
    public function toggleInfo()
    {
        $this->showinfoMessage = !$this->showinfoMessage;
        $this->showinfoButton = !$this->showinfoButton;
    }

    public $searchEmployeeIds;

    //get employee filter based on dropdown
    public function loadEmployeeList()
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
            $query = EmployeeDetails::where(function ($query) use ($companyIdsArray) {
                foreach ($companyIdsArray as $companyId) {
                    $query->orWhere('company_id', 'like', "%\"$companyId\"%");
                }
            });

            // Add the searchTerm filter if it's provided (not null or empty)
            if (!empty($this->searchTerm)) {
                $query->where(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', '%' . $this->searchTerm . '%');
            }

            // Fetch the employee details with image and full name
            $this->searchEmployeeIds = $query->orderBy(DB::raw("CONCAT(first_name, ' ', last_name)"))
                ->get(['emp_id', 'first_name', 'last_name', 'image', DB::raw("CONCAT(first_name, ' ', last_name) as full_name")])
                ->mapWithKeys(function ($employee) {
                    // Map each employee's ID to their full name and image URL
                    return [
                        $employee->emp_id => [
                            'full_name' => $employee->full_name,
                            'image' => $employee->image
                        ]
                    ];
                })
                ->toArray();
        } catch (\Illuminate\Database\QueryException $e) {
            // Catch database-related exceptions
            Log::error("Database query error in loadEmployeeList: " . $e->getMessage());
            FlashMessageHelper::flashError('There was a database error while loading the employee list.');
        } catch (\Exception $e) {
            // Catch any other exceptions
            Log::error("Unexpected error in loadEmployeeList: " . $e->getMessage());
            FlashMessageHelper::flashError('An unexpected error occurred while loading the employee list.');
        }
    }

    public $selecetdEmpDetails;
    public function getEmpDetailsFor()
    {
        $this->selecetdEmpDetails = EmployeeDetails::where('emp_id', $this->seleceted_emp_id)->first();
    }
    //this method used to filter cc recipients from employee details
    public function searchCCRecipients()
    {
        try {
            // Get the logged-in employee's ID
            $employeeId = auth()->guard('hr')->user()->emp_id;

            // Fetch the company IDs for the logged-in employee
            $companyIds = EmployeeDetails::where('emp_id', $employeeId)->value('company_id');

            // Ensure companyIds is an array; decode if it's a JSON string
            $companyIdsArray = is_array($companyIds) ? $companyIds : (is_null($companyIds) ? [] : json_decode($companyIds, true));

            // Initialize an empty collection for recipients
            $this->ccRecipients = collect();

            // Fetch all employees for the relevant company IDs at once
            $employees = EmployeeDetails::whereJsonContains('company_id', $companyIdsArray)
                ->where('emp_id', '!=', $employeeId)
                ->whereIn('employee_status', ['active', 'on-probation'])
                ->when($this->searchTerm, function ($query) {
                    $query->where(function ($subQuery) {
                        $subQuery->where('first_name', 'like', '%' . $this->searchTerm . '%')
                            ->orWhere('last_name', 'like', '%' . $this->searchTerm . '%');
                    });
                })
                ->groupBy('emp_id', 'image', 'gender')
                ->select(
                    'emp_id',
                    'gender',
                    'image',
                    DB::raw('MIN(CONCAT(first_name, " ", last_name)) as full_name')
                )
                ->orderBy('full_name')
                ->get();

            $this->ccRecipients = collect($employees);

            // Optionally, remove duplicates if necessary
            $this->ccRecipients = $this->ccRecipients->unique('emp_id');
        } catch (\Exception $e) {
            // Log the error
            FlashMessageHelper::flashError('An error occurred while searching for CC recipients. Please try again later.');
        }
    }

    public function openCcRecipientsContainer()
    {
        try {
            $this->showCcRecipents = true;
            if ($this->showCcRecipents = true) {
                $this->searchCCRecipients();
            }
        } catch (\Exception $e) {
            FlashMessageHelper::flashError('An error occurred. Please try again later.');
        }
    }


    //this method will help to close the cc to container
    public function closeCcRecipientsContainer()
    {
        try {
            $this->showCcRecipents = !$this->showCcRecipents;
        } catch (\Exception $e) {
            FlashMessageHelper::flashError('An error occurred. Please try again later.');
        }
    }

    public $selectedEmployeeId;

    private function isWeekend($date)
    {
        // Convert date string to a Carbon instance
        $carbonDate = Carbon::parse($date);
        // Check if the day of the week is Saturday or Sunday
        return $carbonDate->isWeekend();
    }
    public function toggleSelection($empId)
    {
        try {
            if (isset($this->selectedPeople[$empId])) {
                // Deselect employee and reset limit flag
                unset($this->selectedPeople[$empId]);
                $this->hasReachedLimit = false;
            } else {
                // Check if the selection limit is reached
                if (count($this->selectedPeople) < 5) {
                    // Select employee if within limit
                    $this->selectedPeople[$empId] = true;
                } else {
                    // Show warning only once if limit reached
                    if (!$this->hasReachedLimit) {
                        FlashMessageHelper::flashWarning("You reached maximum limit of CC To");
                        $this->hasReachedLimit = true;
                    }
                }
            }
            // Always update recipients list and fetch employee details
            $this->searchCCRecipients();
            $this->fetchEmployeeDetails();
        } catch (\Exception $e) {
            // Notify the user if an error occurs
            FlashMessageHelper::flashError('An error occurred while processing your selection. Please try again.');
            return false;
        }
    }


    public function fetchEmployeeDetails()
    {
        // Reset the list of selected employees
        $this->selectedCCEmployees = [];

        // Fetch employee IDs from selected people
        $employeeIds = array_keys($this->selectedPeople);

        // Ensure there are employee IDs to fetch
        if (empty($employeeIds)) {
            return; // No selected employees to fetch
        }

        try {
            // Fetch details for selected employees in one query
            $employees = EmployeeDetails::whereIn('emp_id', $employeeIds)->get();

            // Map employees to selectedCCEmployees
            $this->selectedCCEmployees = $employees->map(function ($employee) {
                // Calculate initials
                $initials = strtoupper(substr($employee->first_name, 0, 1) . substr($employee->last_name, 0, 1));

                // Return the transformed employee data
                return [
                    'emp_id' => $employee->emp_id,
                    'first_name' => $employee->first_name,
                    'last_name' => $employee->last_name,
                    'initials' => $initials,
                ];
            })->toArray(); // Convert the collection back to an array

        } catch (\Exception $e) {
            FlashMessageHelper::flashError('An error occurred while fetching employee details. Please try again later.');
        }
    }


    public function removeFromCcTo($empId)
    {
        try {
            // Remove the employee from selectedCcTo array
            $this->selectedCcTo = array_values(array_filter($this->selectedCcTo, function ($recipient) use ($empId) {
                return $recipient['emp_id'] != $empId;
            }));

            // Update cc_to field with selectedCcTo (comma-separated string of emp_ids)
            $this->cc_to = implode(',', array_column($this->selectedCcTo, 'emp_id'));

            // Toggle selection state in selectedPeople
            unset($this->selectedPeople[$empId]);

            // Fetch updated employee details
            $this->fetchEmployeeDetails();
            $this->searchCCRecipients();
        } catch (\Exception $e) {
            // Notify the user
            FlashMessageHelper::flashError('An error occurred while removing CC recipients.');
            return false;
        }
    }

    public $showCCEmployees = false;
    public function openModal()
    {
        $this->showCCEmployees = !$this->showCCEmployees;
    }
    public function leaveApply()
    {
        // Validate input fields before further processing
        $this->validate();
        // Call handleFieldUpdate for relevant fields
        if (
            !$this->handleFieldUpdate('from_date') ||
            !$this->handleFieldUpdate('to_date') ||
            !$this->handleFieldUpdate('leave_type')
        ) {
            return; // Stop execution if there is an error
        } else {
            $this->handleFieldUpdate($this->field); // Handle the field update for the main field
        }


        try {
            // Prepare CC and Manager details
            $ccToDetails = [];
            foreach ($this->selectedCCEmployees as $selectedEmployee) {
                $selectedEmployeeId = $selectedEmployee['emp_id']; // Get the emp_id

                // Check if the emp_id is not already in $ccToDetails
                if (!in_array($selectedEmployeeId, array_column($ccToDetails, 'emp_id'))) {
                    $employeeDetails = EmployeeDetails::where('emp_id', $selectedEmployeeId)->first();

                    if ($employeeDetails) {
                        $ccToDetails[] = [
                            'emp_id' => $selectedEmployeeId,
                            'full_name' => $employeeDetails->first_name . ' ' . $employeeDetails->last_name,
                            'email' => $employeeDetails->email
                        ];
                    }
                }
            }

            // Manager details
            $applyingToDetails = [];
            if ($this->selectedManagerDetails) {
                $employeeDetails = EmployeeDetails::where('emp_id', $this->selectedManagerDetails->emp_id)->first();
                if ($employeeDetails) {
                    $applyingToDetails[] = [
                        'manager_id' => $employeeDetails->emp_id,
                        'report_to' => $employeeDetails->first_name . ' ' . $employeeDetails->last_name,
                        'email' => $employeeDetails->email
                    ];
                }
            } else {
                $employeeDetails = EmployeeDetails::where('emp_id', auth()->guard('hr')->user()->hr_emp_id)->first();
                $defaultManager = $employeeDetails->manager_id;
                $defaultManagerEmail = EmployeeDetails::where('emp_id', $defaultManager)->first();
                $applyingToDetails[] = [
                    'manager_id' => $defaultManager,
                    'report_to' => $this->loginEmpManager,
                    'email' => $defaultManagerEmail->email
                ];
            }

            $filePaths = $this->file_paths ?? [];
            // Validate file uploads
            $validator = Validator::make($filePaths, [
                'file_paths.*' => 'required|file|mimes:xls,csv,xlsx,pdf,jpeg,png,jpg,gif|max:1024',
            ], [
                'file_paths.*.required' => 'You must upload at least one file.',
                'file_paths.*.max' => 'Your file is larger than 1 MB. Please select a file of up to 1 MB only.',
                'file_paths.*.mimes' => 'Invalid file type. Only xls, csv, xlsx, pdf, jpeg, png, jpg, gif are allowed.',
            ]);


            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            // Store files
            $fileDataArray = [];
            if ($filePaths) {
                foreach ($filePaths as $file) {
                    $fileContent = file_get_contents($file->getRealPath());
                    $mimeType = $file->getMimeType();
                    $base64File = base64_encode($fileContent);
                    $fileDataArray[] = [
                        'data' => $base64File,
                        'mime_type' => $mimeType,
                        'original_name' => $file->getClientOriginalName(),
                    ];
                }
            }


            // Create the leave request
            $this->createdLeaveRequest = LeaveRequest::create([
                'applied_by' => auth()->guard('hr')->user()->hr_emp_id,
                'emp_id' => $this->seleceted_emp_id,
                'leave_type' => $this->leave_type,
                'category_type' => 'Leave',
                'from_date' => $this->from_date,
                'from_session' => $this->from_session,
                'to_session' => $this->to_session,
                'to_date' => $this->to_date,
                'file_paths' => json_encode($fileDataArray),
                'applying_to' => json_encode($applyingToDetails),
                'cc_to' => json_encode($ccToDetails),
                'contact_details' => $this->contact_details,
                'reason' => $this->reason,
            ]);
            FlashMessageHelper::flashSuccess("Leave application submitted successfully!");
            // Notify
            Notification::create([
                'emp_id' =>  $this->seleceted_emp_id,
                'notification_type' => 'leave',
                'leave_type' => $this->leave_type,
                // 'leave_reason' => $this->reason,
                'applying_to' => json_encode($applyingToDetails),
                'cc_to' => json_encode($ccToDetails),
            ]);

            // Send email notification
            $managerEmail = $applyingToDetails[0]['email'] ?? null; // Use null coalescing to avoid undefined index
            $ccEmails = array_map(fn($cc) => $cc['email'], $ccToDetails);

            // Limit CC emails to a maximum of 5 recipients
            $ccEmails = array_slice($ccEmails, 0, 5);

            // Check if the manager email or CC emails are not empty
            if (!empty($managerEmail) || !empty($ccEmails)) {
                // Send the email if we have at least one recipient
                Mail::to($managerEmail)
                    ->cc($ccEmails)
                    ->send(new LeaveApplicationNotification($this->createdLeaveRequest, $applyingToDetails, $ccToDetails));
            }
            $this->resetFields();
        } catch (\Exception $e) {
            Log::error('dfghjk' . $e->getmessage());
            FlashMessageHelper::flashError("Failed to submit leave application. Please try again later.");
        }
    }
    public function updatedFromDate()
    {
        $this->validateDateField('from_date');
    }

    public function updatedToDate()
    {
        $this->validateDateField('to_date');
    }

    private function validateDateField($field)
    {
        $maxDate = now()->endOfYear()->toDateString();

        if ($this->$field > $maxDate) {
            $this->$field = $maxDate;
            FlashMessageHelper::flashWarning(ucfirst(str_replace('_', ' ', $field)) . ' cannot be beyond the current year.');
        }
    }
    public $errorMessageValidation;
    public $propertyName;
    public function handleFieldUpdate($field)
    {
        $this->validateOnly($field);
        try {

            $employeeId = $this->seleceted_emp_id;
            $checkJoinDate = EmployeeDetails::where('emp_id', $employeeId)->first();

            // Clear any previous error messages
            $this->errorMessageValidation = null;
            $currentYear = Carbon::now()->year;
            $fromYear = Carbon::parse($this->from_date)->year;
            $toYear = Carbon::parse($this->to_date)->year;

            //check validation for employeeid
            if ($employeeId == null) {
                $this->errorMessageValidation = FlashMessageHelper::flashWarning('Please select an employee');
                return false;
            }

            // Validate that the selected year is current year or last year
            if ($this->from_date && $this->to_date) {
                if ($fromYear != $currentYear) {
                    $this->errorMessageValidation = FlashMessageHelper::flashWarning('Leave date has to be on or after the current leave year ' . $currentYear . '!');
                    return false;
                }
            }
            // Check for insufficient leave balancev
            // Extract the year from the from_date for leave balance check
            $fromDateYear = Carbon::parse($this->from_date)->year;
            if ($this->leave_type) {
                $leaveBalance = $this->getLeaveBalance($employeeId);
                if ($leaveBalance <= 0 && $this->checkLeaveBalance($this->calculatedNumberOfDays, $this->leaveBalances, $this->leave_type, $fromDateYear)) {
                    return false;
                }
            }
            // Check for date parsing errors
            try {
                $fromDate = Carbon::parse($this->from_date)->format('Y-m-d');
                $toDate = Carbon::parse($this->to_date)->format('Y-m-d');
                $hireDate = Carbon::parse($checkJoinDate->hire_date)->format('Y-m-d');
            } catch (\Exception $e) {
                $this->errorMessageValidation = FlashMessageHelper::flashError('Invalid date format.');
                return false; // Stop further processing
            }

            // 1. Check if the selected dates are on weekends
            if (!$this->isWeekday($this->from_date) || !$this->isWeekday($this->to_date)) {
                $this->errorMessageValidation = FlashMessageHelper::flashError('Looks like it\'s already your non-working day. Please pick different date(s) to apply.');
                return false;
            }

            // 3. Validate leave balance
            if ($this->leave_type != 'Loss Of Pay') {
                $totalNumberOfDays = $this->getTotalLeaveDays($employeeId);
                $leaveBalance = $this->getLeaveBalance($employeeId);
                if ($totalNumberOfDays > $leaveBalance) {
                    Log::debug('Total number of leave days exceed balance', ['totalNumberOfDays' => $totalNumberOfDays, 'leaveBalance' => $leaveBalance]);
                    $this->errorMessageValidation = FlashMessageHelper::flashError('It looks like you have already used all your leave balance.');
                    return false;
                }
            }

            // 4. Check for holidays
            if ($this->checkForHolidays()) {
                $this->errorMessageValidation = FlashMessageHelper::flashError('The selected leave dates overlap with existing holidays. Please pick different dates.');
                return false;
            }

            // 5. Validate from date for joining date
            if (!empty($this->from_date)) {
                $fromDate = Carbon::parse($this->from_date)->format('Y-m-d');
                $hireDate = Carbon::parse($checkJoinDate->hire_date)->format('Y-m-d');
                if ($fromDate < $hireDate) {
                    $this->errorMessageValidation = FlashMessageHelper::flashError('Entered From date and To dates are less than your Join date.');
                    return; // Stop further validation if error occurs
                }
            }

            // 6. Special validation for Casual Leave
            if ($this->leave_type === 'Casual Leave' && $this->checkCasualLeaveLimit($employeeId)) {
                $this->errorMessageValidation = FlashMessageHelper::flashError('You can only apply for a maximum of 2 days of Casual Leave for the month.');
                return false; // Stop further validation if error occurs
            }

            // 7. Validate date range
            if ($this->from_date === $this->to_date && $this->from_session > $this->to_session) {
                $this->errorMessageValidation = FlashMessageHelper::flashError('To session must be greater than or equal to from session.');
                return false; // Stop further validation if error occurs
            }

            // 8. Validate date range
            if ($this->to_date) {
                if ($this->from_date > $this->to_date) {
                    $this->errorMessageValidation = FlashMessageHelper::flashError('To date must be greater than or equal to from date.');
                    return false; // Stop further validation if error occurs
                }

                // 2. Check for overlapping leave requests
                if ($this->checkOverlappingLeave($employeeId)) {
                    $this->errorMessageValidation = FlashMessageHelper::flashError('The selected leave dates overlap with an existing leave application.');
                    return false;
                }
            }

            // New validation for file uploads
            if ($this->file_paths) {
                if ($this->checkFileSize()) {
                    // Check if the file extension is 'zip'
                    foreach ($this->file_paths as $file) {
                        if (in_array($file->getClientOriginalExtension(), ['zip', 'rar'])) {
                            FlashMessageHelper::flashError('The uploaded file type is not allowed. Please upload a valid xls,csv,xlsx,pdf,jpeg,png,jpg,gif.');
                            return false;
                        }
                    }
                } else {
                    $this->errorMessageValidation = FlashMessageHelper::flashError('The file size must not exceed 1 MB. Please upload a smaller file.');
                    return false;
                }
            }


            // All validations passed, now calculate the number of days
            if ($this->to_date) {
                $this->showNumberOfDays = true;
                if (in_array($field, ['from_date', 'to_date', 'from_session', 'to_session', 'leave_type'])) {
                    $this->calculateNumberOfDays($this->from_date, $this->from_session, $this->to_date, $this->to_session, $this->leave_type);
                }
            } else {
                $this->showNumberOfDays = false;
            }
            return true;
        } catch (ValidationException $exception) {
            $this->addError($field, 'This field is required and cannot be empty.');
            return false;
        } catch (\Exception $e) {
            FlashMessageHelper::flashError('An error occurred while handling field update. Please try again later.');
            return false;
        }
    }


    //checkfilesize
    protected function checkFileSize()
    {
        try {
            foreach ($this->file_paths as $file) {
                if ($file->getSize() <= 1024 * 1024) {
                    return true;
                }
            }
        } catch (\Exception $e) {
            FlashMessageHelper::flashError('An error occurred while uploading the file, please try again later.');
            return false; // Handle any error during file size check
        }

        return false; // All files are within size limit
    }


    // check leave overlap
    protected function checkOverlappingLeave($employeeId)
    {
        try {
            // Parse and format the entered dates
            $fromDate = Carbon::createFromFormat('Y-m-d', $this->from_date)->toDateString(); // 'Y-m-d'
            $toDate = Carbon::createFromFormat('Y-m-d', $this->to_date)->toDateString();     // 'Y-m-d'
            $fromSession = $this->from_session;  // Get the from_session value
            $toSession = $this->to_session;      // Get the to_session value

            $currentYearNow = date('Y');
            // Retrieve leave requests for the employee
            $leaveRequests = LeaveRequest::where('emp_id', $employeeId)
                ->where('category_type', 'Leave')
                ->whereIn('leave_status', [2, 5])
                ->whereIn('cancel_status', [5, 7])
                ->whereBetween('from_date', ["{$currentYearNow}-01-01", "{$currentYearNow}-12-31"])  // Ensure from_date is in the current year
                ->whereBetween('to_date', ["{$currentYearNow}-01-01", "{$currentYearNow}-12-31"])    // Ensure to_date is in the current year
                ->get();

            // Iterate over each leave request to format the dates and check for overlaps
            foreach ($leaveRequests as $leaveRequest) {
                // Parse existing leave request's dates
                $existingFromDate = Carbon::parse($leaveRequest->from_date)->toDateString();
                $existingToDate = Carbon::parse($leaveRequest->to_date)->toDateString();
                $existingFromSession = $leaveRequest->from_session; // Existing from session
                $existingToSession = $leaveRequest->to_session;     // Existing to session

                // Check for date overlap
                $dateOverlap = ($fromDate <= $existingToDate && $toDate >= $existingFromDate);

                // Check for session overlap
                $sessionOverlap = ($fromSession <= $existingToSession && $toSession >= $existingFromSession);

                // If both date and session overlap, return true (overlap found)
                if ($dateOverlap && $sessionOverlap) {
                    return true; // Overlap found
                }
            }

            // No overlaps found
            return false;
        } catch (\Exception $e) {
            FlashMessageHelper::flashError("Failed to check leave request.");
            return false; // Return false on failure
        }
    }

    //calculate number of days for a leave request
    protected function getTotalLeaveDays($employeeId)
    {
        try {
            // Retrieve total number of days for approved and pending leave requests
            $checkLeaveBalance = LeaveRequest::where('emp_id', $employeeId)
                ->where('leave_type', $this->leave_type)
                ->whereNotIn('leave_type', ['Loss Of Pay'])
                ->where('category_type', 'Leave')
                ->whereIn('leave_status', [2, 5])
                ->whereIn('cancel_status', [5, 3, 6])
                ->whereYear('from_date', '=', date('Y'))  // Check if 'from_date' is within the current year
                ->whereYear('to_date', '=', date('Y'))    // Check if 'to_date' is within the current year
                ->get();

            $totalNumberOfDays = 0; // Initialize the counter for total days

            if (!$checkLeaveBalance->isEmpty()) {
                foreach ($checkLeaveBalance as $leaveRequest) {
                    $numberBalanceOfDays = $this->calculateNumberOfDays(
                        $leaveRequest->from_date,
                        $leaveRequest->from_session,
                        $leaveRequest->to_date,
                        $leaveRequest->to_session,
                        $leaveRequest->leave_type
                    );
                    $totalNumberOfDays += $numberBalanceOfDays;
                }
            }

            // Calculate the days for the new leave request, if provided
            if ($this->leave_type !== 'Loss of Pay' && $this->from_date && $this->from_session && $this->to_date && $this->to_session) {
                $totalEnteredDays = $this->calculateNumberOfDays(
                    $this->from_date,
                    $this->from_session,
                    $this->to_date,
                    $this->to_session,
                    $this->leave_type
                );
                $totalNumberOfDays += $totalEnteredDays;
            }

            return $totalNumberOfDays;
        } catch (\Exception $e) {
            FlashMessageHelper::flashError('An error occurred while calculating leave days. Please try again.');
            return false; // Default return value in case of an error
        }
    }

    //get leave balance for selected leave type
    protected function getLeaveBalance($employeeId)
    {
        try {
            // Extract the year from the 'from_date' (use the year from the 'from_date' instead of the current year)
            $fromDateYear = Carbon::parse($this->from_date)->year;
            // Retrieve leave balances for the year from 'from_date'
            $toggleLapsedData = EmployeeLeaveBalances::where('emp_id', $employeeId)
                ->whereNull('lapsed_date')
                ->where('granted_for_year', $fromDateYear)
                ->where('period', 'like', "%$fromDateYear%")
                ->first();

            if ($toggleLapsedData && $toggleLapsedData->laspsed_date != null) {
                // If lapsed, set the balance directly to 0 for all leave types
                $leaveBalances = [
                    'Sick Leave' => 0,
                    'Casual Leave' => 0,
                    'Casual Leave Probation' => 0,
                    'Marriage Leave' => 0,
                    'Maternity Leave' => 0,
                    'Paternity Leave' => 0
                ];
            } else {
                // Get the leave balance per year based on the 'from_date' year
                $leaveBalances = [
                    'Sick Leave' => EmployeeLeaveBalances::getLeaveBalancePerYear($employeeId, 'Sick Leave', $fromDateYear),
                    'Casual Leave' => EmployeeLeaveBalances::getLeaveBalancePerYear($employeeId, 'Casual Leave', $fromDateYear),
                    'Casual Leave Probation' => EmployeeLeaveBalances::getLeaveBalancePerYear($employeeId, 'Casual Leave Probation', $fromDateYear),
                    'Marriage Leave' => EmployeeLeaveBalances::getLeaveBalancePerYear($employeeId, 'Marriage Leave', $fromDateYear),
                    'Maternity Leave' => EmployeeLeaveBalances::getLeaveBalancePerYear($employeeId, 'Maternity Leave', $fromDateYear),
                    'Paternity Leave' => EmployeeLeaveBalances::getLeaveBalancePerYear($employeeId, 'Paternity Leave', $fromDateYear),
                ];
            }
            // Return the balance for the specific leave type
            return (float)($leaveBalances[$this->leave_type] ?? 0);
        } catch (\Exception $e) {
            FlashMessageHelper::flashError('An error occurred while retrieving leave balance. Please try again.');
            return false;
        }
    }


    //check for exsiting holidays
    protected function checkForHolidays()
    {
        try {
            // Check if both from_date and to_date exactly match holidays
            return HolidayCalendar::whereIn('date', [$this->from_date, $this->to_date])
                ->whereNotNull('festivals')
                ->where('festivals', '!=', '')
                ->exists();
        } catch (\Exception $e) {
            // Handle errors
            FlashMessageHelper::flashError('An error occurred while checking for holidays. Please try again.');
            return false;
        }
    }


    //check for casual leave limit for the month
    protected function checkCasualLeaveLimit($employeeId)
    {
        try {
            // Get all casual leave requests for the current month
            $currentMonth = now()->month;
            $currentYear = now()->year;
            $leaveRequests = LeaveRequest::where('emp_id', $employeeId)
                ->whereIn('leave_type', ['Casual Leave', 'Maternity Leave'])
                ->where('category_type', 'Leave')
                ->whereYear('from_date', $currentYear)
                ->whereMonth('from_date', $currentMonth)
                ->whereIn('leave_status', [2, 5])
                ->whereIn('cancel_status', [3, 6, 5])
                ->get();

            $totalLeaveDays = 0;

            foreach ($leaveRequests as $leaveRequest) {
                $numberOfDays = $this->calculateNumberOfDays(
                    $leaveRequest->from_date,
                    $leaveRequest->from_session,
                    $leaveRequest->to_date,
                    $leaveRequest->to_session,
                    $leaveRequest->leave_type
                );
                $totalLeaveDays += $numberOfDays;
            }

            // Calculate days for the new leave request if it's Casual Leave
            if ($this->from_date && $this->to_date) {
                $newLeaveDays = $this->calculateNumberOfDays(
                    $this->from_date,
                    $this->from_session,
                    $this->to_date,
                    $this->to_session,
                    $this->leave_type
                );
                $totalLeaveDays += $newLeaveDays;
            }

            // Check if total leave days exceed 2
            return $totalLeaveDays > 2;
        } catch (\Exception $e) {
            FlashMessageHelper::flashError('An error occurred while checking the casual leave limit. Please try again.');
            return false; // Default return value in case of an error
        }
    }

    private function validateDateFormat($date)
    {
        return Carbon::hasFormat($date, 'Y-m-d');
    }

    //calculate leave balance dynamically
    public function checkLeaveBalance($calculatedNumberOfDays, $leaveBalances, $leave_type, $fromDateYear)
    {
        $leaveBalanceKey = '';

        // Determine the leave balance key based on the leave type
        switch ($leave_type) {
            case 'Sick Leave':
                $leaveBalanceKey = 'sickLeaveBalance';
                break;
            case 'Casual Leave':
                $leaveBalanceKey = 'casualLeaveBalance';
                break;
            case 'Casual Leave Probation':
                $leaveBalanceKey = 'casualProbationLeaveBalance';
                break;
            case 'Maternity Leave':
                $leaveBalanceKey = 'maternityLeaveBalance';
                break;
            case 'Paternity Leave':
                $leaveBalanceKey = 'paternityLeaveBalance';
                break;
            case 'Marriage Leave':
                $leaveBalanceKey = 'marriageLeaveBalance';
                break;
        }

        // Ensure the balance key is set and the year matches
        if ($leaveBalanceKey) {
            // Get the leave balance for the year extracted from from_date
            $leaveBalanceForYear = $leaveBalances[$fromDateYear][$leaveBalanceKey] ?? 0;

            // Check if there is enough leave balance for the specified year
            if ($calculatedNumberOfDays >= $leaveBalanceForYear) {
                FlashMessageHelper::flashError('Insufficient leave balance for ' . $leave_type . ' for the year ' . $fromDateYear);
                return true; // Indicates insufficient balance
            }
        }

        return false; // Indicates sufficient balance
    }




    // Add the isWeekday function
    private function isWeekday($date)
    {
        $dayOfWeek = date('N', strtotime($date)); // 1 (for Monday) through 7 (for Sunday)
        return $dayOfWeek >= 1 && $dayOfWeek <= 5; // Return true if it's a weekday
    }

    public $empId;
    public function applyingTo()
    {
        try {
            $this->showApplyingToContainer = !$this->showApplyingToContainer;
            $this->show_reporting = true;
            $this->showApplyingTo = false;
        } catch (\Exception $e) {
            // Log the error
            FlashMessageHelper::flashError('An error occurred, please try again later.');
            return false;
        }
    }

    public $showSessionDropdown = true; // Default value

    public function updatedLeaveType($value)
    {
        if ($value === 'Maternity Leave' || $value === 'Paternity Leave') {
            $this->showSessionDropdown = false;
        } else {
            $this->showSessionDropdown = true;
        }
    }
    public function selectLeave()
    {
        try {
            $this->getManagerDetailsForEmp();
            $this->show_reporting = $this->leave_type !== 'default';
            $this->showApplyingTo = false;
            $this->selectedYear = Carbon::now()->format('Y');
            // Check if 'from_date' and 'to_date' are set
            if ($this->from_date && $this->to_date) {
                $fromYear = Carbon::parse($this->from_date)->format('Y');
                $toYear = Carbon::parse($this->to_date)->format('Y');
                // If both from and to dates belong to the same year, use that year
                // Otherwise, use the current year or the year from 'from_date' as fallback
                $this->selectedYear = ($fromYear === $toYear) ? $fromYear : Carbon::now()->format('Y');
            } else {
                // Fallback to the current year if no dates are set
                $this->selectedYear = Carbon::now()->format('Y');
            }
            $employeeId = $this->seleceted_emp_id;
            // Retrieve all leave balances
            $allLeaveBalances = LeaveBalances::getLeaveBalances($employeeId, $this->selectedYear);
            // Filter leave balances based on the selected leave type
            switch ($this->leave_type) {
                case 'Casual Leave Probation':
                    $this->leaveBalances = [
                        'casualProbationLeaveBalance' => $allLeaveBalances['casualProbationLeaveBalance'] ?? '0'
                    ];
                    break;
                case 'Casual Leave':
                    $this->leaveBalances = [
                        'casualLeaveBalance' => $allLeaveBalances['casualLeaveBalance'] ?? '0'
                    ];
                    break;
                case 'Loss of Pay':
                    $this->leaveBalances = [
                        'lossOfPayBalance' => $allLeaveBalances['lossOfPayBalance'] ?? '0'
                    ];
                    break;
                case 'Sick Leave':
                    $this->leaveBalances = [
                        'sickLeaveBalance' => $allLeaveBalances['sickLeaveBalance'] ?? '0'
                    ];
                    break;
                case 'Maternity Leave':
                    $this->leaveBalances = [
                        'maternityLeaveBalance' => $allLeaveBalances['maternityLeaveBalance'] ?? '0'
                    ];
                    break;
                case 'Paternity Leave':
                    $this->leaveBalances = [
                        'paternityLeaveBalance' => $allLeaveBalances['paternityLeaveBalance'] ?? '0'
                    ];
                    break;
                case 'Marriage Leave':
                    $this->leaveBalances = [
                        'marriageLeaveBalance' => $allLeaveBalances['marriageLeaveBalance'] ?? '0'
                    ];
                    break;
                default:
                    $this->leaveBalances = [];
                    break;
            }

            $this->showNumberOfDays = true;
        } catch (\Exception $e) {
            // Flash an error message to the user
            FlashMessageHelper::flashError('An error occurred while selecting leave and leave balance. Please try again later.');
            // Redirect the user back
            return redirect()->back();
        }
    }

    //it will calculate number of days for leave application
    public function calculateNumberOfDays($fromDate, $fromSession, $toDate, $toSession, $leaveType)
    {
        try {
            $startDate = Carbon::parse($fromDate);
            $endDate = Carbon::parse($toDate);

            // Fetch holidays between the fromDate and toDate
            $holidays = HolidayCalendar::whereBetween('date', [$startDate, $endDate])->get();

            // Check if the start or end date is a weekend for non-Marriage leave
            if (!in_array($leaveType, ['Marriage Leave', 'Sick Leave', 'Maternity Leave', 'Paternity Leave']) && ($startDate->isWeekend() || $endDate->isWeekend())) {
                return 0;
            }

            // Check if the start and end sessions are different on the same day
            if (
                $startDate->isSameDay($endDate) &&
                $this->getSessionNumber($fromSession) === $this->getSessionNumber($toSession)
            ) {
                // Inner condition to check if both start and end dates are weekdays (for non-Marriage leave)
                if (!in_array($leaveType, ['Marriage Leave', 'Sick Leave', 'Maternity Leave', 'Paternity Leave']) && !$startDate->isWeekend() && !$endDate->isWeekend() && !$this->isHoliday($startDate, $holidays) && !$this->isHoliday($endDate, $holidays)) {
                    return 0.5;
                } else {
                    return 0.5;
                }
            }

            if (
                $startDate->isSameDay($endDate) &&
                $this->getSessionNumber($fromSession) !== $this->getSessionNumber($toSession)
            ) {
                // Inner condition to check if both start and end dates are weekdays (for non-Marriage leave)
                if (!in_array($leaveType, ['Marriage Leave', 'Sick Leave', 'Maternity Leave', 'Paternity Leave']) && !$startDate->isWeekend() && !$endDate->isWeekend() && !$this->isHoliday($startDate, $holidays) && !$this->isHoliday($endDate, $holidays)) {
                    return 1;
                } else {
                    return 1;
                }
            }

            $totalDays = 0;

            while ($startDate->lte($endDate)) {
                // For non-Marriage leave type, skip holidays and weekends, otherwise include weekdays
                if (!in_array($leaveType, ['Marriage Leave', 'Sick Leave', 'Maternity Leave', 'Paternity Leave'])) {
                    if (!$this->isHoliday($startDate, $holidays) && $startDate->isWeekday()) {
                        $totalDays += 1;
                    }
                } else {
                    // For Marriage leave type, count all weekdays without excluding weekends or holidays
                    if (!$this->isHoliday($startDate, $holidays)) {
                        $totalDays += 1;
                    }
                }

                // Move to the next day
                $startDate->addDay();
            }

            // Deduct weekends based on the session numbers
            if ($this->getSessionNumber($fromSession) > 1) {
                $totalDays -= $this->getSessionNumber($fromSession) - 1; // Deduct days for the starting session
            }
            if ($this->getSessionNumber($toSession) < 2) {
                $totalDays -= 2 - $this->getSessionNumber($toSession); // Deduct days for the ending session
            }

            // Adjust for half days
            if ($this->getSessionNumber($fromSession) === $this->getSessionNumber($toSession)) {
                if ($this->getSessionNumber($fromSession) !== 1) {
                    $totalDays += 0.5; // Add half a day
                } else {
                    $totalDays += 0.5;
                }
            } elseif ($this->getSessionNumber($fromSession) !== $this->getSessionNumber($toSession)) {
                if ($this->getSessionNumber($fromSession) !== 1) {
                    $totalDays += 1; // Add half a day
                }
            } else {
                $totalDays += ($this->getSessionNumber($toSession) - $this->getSessionNumber($fromSession) + 1) * 0.5;
            }

            return $totalDays;
        } catch (\Exception $e) {
            FlashMessageHelper::flashError('An error occurred while calculating the number of days.');
            return false;
        }
    }
    // Helper method to check if a date is a holiday
    private function isHoliday($date, $holidays)
    {
        // Check if the date exists in the holiday collection
        return $holidays->contains('date', $date->toDateString());
    }

    private function getSessionNumber($session)
    {
        return (int) str_replace('Session ', '', $session);
    }
    //selected applying to manager details
    public function toggleManager($empId)
    {
        if ($empId) {
            $this->fetchManagerDetails($empId);
            $this->showApplyingToContainer = false;
        } else {
            $employeeId = $this->seleceted_emp_id;
            $applying_to = EmployeeDetails::where('emp_id', $employeeId)->first();
            if ($applying_to) {
                $managerId = $applying_to->manager_id;
                // Fetch the logged-in employee's manager details
                $managerDetails = EmployeeDetails::where('emp_id', $managerId)->first();
                if ($managerDetails) {
                    $fullName = ucfirst(strtolower($managerDetails->first_name)) . ' ' . ucfirst(strtolower($managerDetails->last_name));
                    $this->loginEmpManager = $fullName;
                    $this->empManagerDetails = $managerDetails;
                }
            }
            $this->selectedManager = [$empId];
            $this->showApplyingToContainer = false;
        }
    }


    // Method to fetch manager details
    private function fetchManagerDetails($empId)
    {
        $employeeDetails = EmployeeDetails::where('emp_id', $empId)->first();
        if ($employeeDetails) {
            $fullName = ucfirst(strtolower($employeeDetails->first_name)) . ' ' . ucfirst(strtolower($employeeDetails->last_name));
            $this->loginEmpManager = $fullName;
            $this->selectedManagerDetails = $employeeDetails;
        } else {
            $this->resetManagerDetails();
        }
    }

    private function resetManagerDetails()
    {
        $this->empManagerDetails = null;
    }

    public function resetFields()
    {
        $this->reason = null;
        $this->contact_details = null;
        $this->reset('from_date', 'to_date');
        $this->resetErrorBag();
        $this->leave_type = null;
        $this->to_date = null;
        $this->from_date = null;
        $this->from_session = 'Session 1';
        $this->to_session = 'Session 2';
        $this->cc_to = null;
        $this->showNumberOfDays = false;
        $this->showApplyingToContainer = false;
        $this->show_reporting = false;
        $this->showApplyingTo = true;
        $this->selectedCCEmployees = [];
        $this->file_paths = null;
        $this->selectedPeople = [];
        $this->selectedManager = [];
    }

    public $hrEmployeeId;

    public $managers;
    public $employeeGender;
    //get manager and Hr details
    public function getApplyingToDetails()
    {
        $employeeId = $this->seleceted_emp_id;
        $this->loginEmpManager = null;
        $this->selectedManager = $this->selectedManager ?? [];
        $managers = collect();

        $this->employeeGender = null;

        try {
            // Fetch details for the current employee
            $applying_to = EmployeeDetails::where('emp_id', $employeeId)->first();

            if ($applying_to) {
                $managerId = $applying_to->manager_id;

                if ($managerId) {
                    // Fetch the logged-in employee's manager details
                    $managerDetails = EmployeeDetails::where('emp_id', $managerId)->first();
                } else {
                    $managerDetails = null;
                }

                if ($managerDetails) {
                    $fullName = ucfirst(strtolower($managerDetails->first_name)) . ' ' . ucfirst(strtolower($managerDetails->last_name));
                    $this->loginEmpManager = $fullName;
                    $this->empManagerDetails = $managerDetails;

                    // Add the logged-in manager to the collection
                    $managers->push([
                        'full_name' => $fullName,
                        'emp_id' => $managerDetails->emp_id,
                        'gender' => $managerDetails->gender,
                        'image' => $managerDetails->image,
                    ]);
                } else {
                    $managers = [];
                }

                // Fetch the gender of the logged-in employee
                $this->employeeGender = $applying_to->gender;
                // Fetch employees with job roles CTO, Chairman, and HR
                $jobRoles = ['CTO', 'Chairman'];
                $filteredManagers = EmployeeDetails::whereIn('job_role', $jobRoles)
                    ->when($this->searchQuery, function ($query) {
                        return $query->whereRaw('CONCAT(first_name, " ", last_name) LIKE ?', ["%{$this->searchQuery}%"]);
                    })
                    ->get(['first_name', 'last_name', 'emp_id', 'gender', 'image']);

                // Add the filtered managers to the collection
                $managers = $managers->merge(
                    $filteredManagers->map(function ($manager) {
                        $fullName = ucfirst(strtolower($manager->first_name)) . ' ' . ucfirst(strtolower($manager->last_name));
                        return [
                            'full_name' => $fullName,
                            'emp_id' => $manager->emp_id,
                            'gender' => $manager->gender,
                            'image' => $manager->image,
                        ];
                    })
                );

                // Get the company_id from the logged-in employee's details
                $companyIds = $applying_to->company_id;

                // Convert the company IDs to an array if it's in JSON format
                $companyIdsArray = is_array($companyIds) ? $companyIds : json_decode($companyIds, true);

                // Fetch emp_ids from the HR table
                $hrEmpIds = Hr::pluck('emp_id');
                // Now, fetch employee details for these HR emp_ids
                $hrManagers = EmployeeDetails::whereIn('emp_id', $hrEmpIds)
                    ->where(function ($query) use ($companyIdsArray) {
                        foreach ($companyIdsArray as $companyId) {
                            $query->orWhere('company_id', 'like', "%\"$companyId\"%");
                        }
                    })
                    ->get(['first_name', 'last_name', 'emp_id', 'gender', 'image']);

                // Add HR managers to the collection
                $hrManagers->each(function ($hrManager) use ($managers) {
                    $fullName = ucfirst(strtolower($hrManager->first_name)) . ' ' . ucfirst(strtolower($hrManager->last_name));
                    $managers->push([
                        'full_name' => $fullName,
                        'emp_id' => $hrManager->emp_id,
                        'gender' => $hrManager->gender,
                        'image' => $hrManager->image,
                    ]);
                });
                // Keep only unique emp_ids
                $managers = $managers->unique('emp_id')->values(); // Ensure we reset the keys
                $this->managers = $managers;
            } else {
                FlashMessageHelper::flashError('Please select an employee to apply leave');
            }
        } catch (\Exception $e) {
            FlashMessageHelper::flashError("Error fetching employee or manager details. Please try again.");
        }
    }
    public function render()
    {
        $this->selectedYear = Carbon::now()->format('Y');
        return view('livewire.leave-apply-on-behalf', [
            'employeeGender' => $this->employeeGender,
            'calculatedNumberOfDays' => $this->calculatedNumberOfDays,
            'empManagerDetails' => $this->empManagerDetails,
            'selectedManagerDetails' => $this->selectedManagerDetails,
            'loginEmpManager' => $this->loginEmpManager,
            'managers' => $this->managers,
            'ccRecipients' => $this->ccRecipients,
            'showCasualLeaveProbation' => $this->showCasualLeaveProbation,
            'seleceted_emp_id' => $this->seleceted_emp_id,
            'selecetdEmpDetails' => $this->selecetdEmpDetails,
            'searchEmployeeIds' => $this->searchEmployeeIds,
            'hrAppliedLeaveRequests' => $this->hrAppliedLeaveRequests
        ]);
    }

    // Add a method to update the search query
    public function getFilteredManagers()
    {
        $this->getApplyingToDetails();
    }
    public function handleEnterKey()
    {
        $this->searchCCRecipients();
    }
}

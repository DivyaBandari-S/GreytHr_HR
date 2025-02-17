<?php

namespace App\Livewire;

use App\Helpers\FlashMessageHelper;
use App\Models\EmployeeLeaveBalances;
use App\Models\LeavePolicySetting;
use App\Models\EmployeeDetails;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EmpLeaveGranterDetails extends Component
{
    public $showActiveGrantLeave = false;
    public $showConfirmDeletionBox = false;
    public $batchIdToDelete = null;
    public $selectedPolicyIds = [];
    public $startDate;
    public $endDate;
    public $selectedYear;
    public $selectedYearRange;
    public $showLeaveBalanceSummary = true;
    public $periodicity = 'Monthly';
    public $period = '';
    public $months;
    public $quarters;
    public $halfYear;
    public $years;
    public $from_date;
    public $to_date;
    public $leavePolicyIds = [];
    public $leavePolicies;
    public $selectedEmployeeIds = [];
    public $employeeIds = [];  // To store the list of employee IDs for the dropdown
    public $startYear;
    public $selectAll = null;
    public $selectedEmployees = [];
    public $endYear;
    public $yearRange;
    public $showEmployeeList = false;
    public $employeeLeaveBalance;
    public $empId;
    public $batchId;
    public $leaveTypes = []; // Array of leave types (leave names and policies)
    public $selectedLeaveTypes = []; // Array of selected leave types for deletion
    public $showModal = false; // Flag to show/hide the modal
    public $deletionType = null;
    public $idToDelete = null;
    public $showEditModal = false;
    public $empIdToDelete;
    public $empIdToUpdate;
    public $leavePolicyData = [];
    public $groupedData;
    public $searchTerm = '';
    public $showEmployeeSearch = false;
    public $showEmployeeSelectionList = false;
    public $showToSelect = true;
    public $leaveType;
    public $employmentType;
    public $grantType;
    protected $rules = [
        'leavePolicyIds' => 'required|array',
        'leavePolicyIds.*' => 'exists:leave_policy,id', // Ensure each ID exists in the leave_policy table
    ];
    public function mount()
    {
        try {
            // Set the selected year to the currentD year
            $this->selectedYear = now()->year;
            $this->leavePolicies = LeavePolicySetting::all();
            // Get current year using Carbon and set the selected year range
            $currentYear = Carbon::now()->year;
            $this->selectedYearRange = 'Jan ' . $currentYear . ' - Dec ' . $currentYear;

            // Update the date range based on the selected year
            $this->updateDateRange();

            // Update period options based on the selected periodicity
            $this->updatePeriodOptions();

            // Initialize months for the selected year
            $this->initializeMonths();

            // Set default periodicity and period
            $this->setDefaultPeriodicityAndPeriod();


            // Load employee list based on selected company or criteria
            $this->loadEmployeeList();
            $this->selectNewlyJoinEpList();
            // Filter leave balances
            $this->filterLeaveBalances();

            // Set the year range for the dropdown (2000 to current year + 5)
            $this->yearRange = range(2000, now()->year + 5);
        } catch (\Carbon\Exceptions\InvalidFormatException $e) {
            // Handle errors related to Carbon date manipulation
            Log::error("Invalid date format in mount method: " . $e->getMessage());
            FlashMessageHelper::flashError('There was an issue with the date format. Please check your data and try again.');
        } catch (\InvalidArgumentException $e) {
            // Handle invalid argument exceptions
            Log::error("Invalid argument in mount method: " . $e->getMessage());
            FlashMessageHelper::flashError('Invalid input. Please check your data and try again.');
        } catch (\Illuminate\Database\QueryException $e) {
            // Handle database query exceptions
            Log::error("Database query error in mount method: " . $e->getMessage());
            FlashMessageHelper::flashError('An error occurred while fetching data from the database. Please try again later.');
        } catch (\Exception $e) {
            // Handle any other unexpected exceptions
            Log::error("Unexpected error in mount method: " . $e->getMessage());
            FlashMessageHelper::flashError('An unexpected error occurred. Please try again later.');
        }
    }



    public function filterLeaveBalances()
    {
        try {
            // Reset the grouped data to start fresh
            $this->groupedData = collect();

            // Initialize startDate and endDate for filtering based on selected periodicity
            $startDate = null;
            $endDate = null;

            // Determine the filtering range based on the selected periodicity (Monthly, Quarterly, Half-Yearly, Yearly)
            if ($this->periodicity === 'Monthly') {
                // Get the selected month for the chosen year (e.g., "January 2024")
                $month = Carbon::parse($this->period)->month;
                $startDate = Carbon::create($this->selectedYear, $month, 1);  // First day of the selected month
                $endDate = $startDate->copy()->endOfMonth();  // Last day of the selected month

            } elseif ($this->periodicity === 'Quarterly') {
                // Determine the quarter range based on the selected quarter
                $quarterStartMonth = ($this->quarter - 1) * 3 + 1; // Start month for the selected quarter
                $quarterEndMonth = $quarterStartMonth + 2; // End month for the selected quarter

                // Set start and end dates for the selected quarter
                $startDate = Carbon::create($this->selectedYear, $quarterStartMonth, 1); // Start of quarter
                $endDate = Carbon::create($this->selectedYear, $quarterEndMonth, 1)->endOfMonth(); // End of quarter

            } elseif ($this->periodicity === 'Half yearly') {
                // Determine if it's the first or second half of the year
                if ($this->halfYear === 'Jan - Jun') {
                    $startDate = Carbon::create($this->selectedYear, 1, 1); // Start of first half
                    $endDate = Carbon::create($this->selectedYear, 6, 30)->endOfDay(); // End of first half
                } else {
                    $startDate = Carbon::create($this->selectedYear, 7, 1); // Start of second half
                    $endDate = Carbon::create($this->selectedYear, 12, 31)->endOfDay(); // End of second half
                }
            } elseif ($this->periodicity === 'Yearly') {
                // For yearly, the start and end date will span the entire year
                $startDate = Carbon::create($this->selectedYear, 1, 1); // Start of the year
                $endDate = Carbon::create($this->selectedYear, 12, 31)->endOfDay(); // End of the year
            }

            // Assuming $selectedYear holds the value of the selected year from the frontend
            $selectedYear = (string)$this->selectedYear;

            // Check if selected year is not empty and is a valid year
            // Assuming $selectedYear, $leaveType, and $employmentType are set from the user's input
            $leaveType = $this->leaveType;
            $employmentType = $this->employmentType;
            $grantType = $this->grantType;

            if ($selectedYear) {
                // Build the query for the selected year with optional filters
                $query = DB::table('employee_leave_balances')
                    ->join('employee_details', 'employee_leave_balances.emp_id', '=', 'employee_details.emp_id')
                    ->whereNull('employee_leave_balances.deleted_at')
                    ->where('employee_leave_balances.granted_for_year', $selectedYear);

                if ($leaveType && $leaveType !== 'All') {
                    // Since leave_policy_id is stored as JSON, use whereJsonContains to filter based on leave_name
                    $query->whereJsonContains('employee_leave_balances.leave_policy_id', ['leave_name' => $this->leaveType]);
                }
                // Apply employment type filter if selected
                if ($employmentType && $employmentType !== 'All') {
                    $query->where('employee_details.employee_type', $employmentType);
                }
                // Apply grant type type filter if selected
                if ($grantType && $grantType !== 'All') {
                    $query->where('employee_leave_balances.periodicity', $grantType);
                }
                // Execute the query
                $this->employeeLeaveBalance = $query->select('employee_leave_balances.*', 'employee_details.*')->get();
            } else {
                // If no year is selected, fetch data for the current year
                $query = DB::table('employee_leave_balances')
                    ->join('employee_details', 'employee_leave_balances.emp_id', '=', 'employee_details.emp_id')
                    ->whereNull('employee_leave_balances.deleted_at')
                    ->where('employee_leave_balances.granted_for_year', (string)Carbon::now()->year); // Use current year if no year is selected

                // Apply leave type filter if selected
                if ($leaveType && $leaveType !== 'All') {
                    // Since leave_policy_id is stored as JSON, use whereJsonContains to filter based on leave_name
                    $query->whereJsonContains('employee_leave_balances.leave_policy_id', ['leave_name' => $this->leaveType]);
                }

                // Apply employment type filter if selected
                if ($employmentType && $employmentType !== 'All') {
                    $query->where('employee_details.employee_type', $employmentType);
                }
                if ($grantType && $grantType !== 'All') {
                    $query->where('employee_leave_balances.periodicity', $grantType);
                }

                // Execute the query
                $this->employeeLeaveBalance = $query->select('employee_leave_balances.*', 'employee_details.*')->get();
            }

            // Group the records by batch_id, or other suitable field based on the context
            $this->groupedData = $this->employeeLeaveBalance->groupBy('batch_id');
        } catch (\Illuminate\Database\QueryException $e) {
            // Handle any database-related exceptions
            Log::error("Database query error in filterLeaveBalances: " . $e->getMessage());
            FlashMessageHelper::flashError('There was a database error while filtering leave balances.');
        } catch (\Exception $e) {
            // Handle general unexpected errors
            Log::error("Unexpected error in filterLeaveBalances: " . $e->getMessage());
            FlashMessageHelper::flashError('An unexpected error occurred while filtering leave balances.');
        }
    }

    public function toggleEmployeeList()
    {
        $this->showEmployeeList = !$this->showEmployeeList; // Toggle the visibility
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
                $this->updatePeriodOptions();
                $this->filterLeaveBalances();
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

    // Initialize months of the current year
    public function initializeMonths()
    {
        try {
            // Initialize months collection with the current year
            $this->months = collect(['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'])
                ->map(function ($month) {
                    return $month . ' ' . $this->selectedYear; // Append selected year to each month
                });
        } catch (\InvalidArgumentException $e) {
            // Handle invalid argument exceptions (e.g., if $this->selectedYear is invalid)
            Log::error("Invalid argument in initializeMonths: " . $e->getMessage());
            FlashMessageHelper::flashError('Invalid argument encountered while initializing months.');
        } catch (\Exception $e) {
            // Handle any other unexpected errors
            Log::error("Unexpected error in initializeMonths: " . $e->getMessage());
            FlashMessageHelper::flashError('An unexpected error occurred while initializing months.');
        }
    }


    // Set default periodicity and period based on the current date
    public function setDefaultPeriodicityAndPeriod()
    {
        try {
            $currentDate = Carbon::now();

            // Set default periodicity
            $this->periodicity = 'Monthly';

            // Set default period based on periodicity
            if ($this->periodicity === 'Monthly') {
                $this->period = $currentDate->format('F Y'); // Current month (e.g., "October 2024")
            } elseif ($this->periodicity === 'Quarterly') {
                $this->period = $this->getCurrentQuarter($currentDate); // Current quarter (e.g., "Q3 2024")
            } elseif ($this->periodicity === 'Half yearly') {
                $this->period = $this->getCurrentHalfYear($currentDate); // Current half-year (e.g., "Jan-Jun 2024" or "Jul-Dec 2024")
            } elseif ($this->periodicity === 'Yearly') {
                $this->period = $currentDate->year; // Current year
            }
        } catch (\InvalidArgumentException $e) {
            // Handle any invalid argument exceptions (e.g., invalid date input)
            Log::error("Invalid argument in setDefaultPeriodicityAndPeriod: " . $e->getMessage());
            FlashMessageHelper::flashError('Invalid argument passed while setting default periodicity or period.');
        } catch (\Exception $e) {
            // Catch other unexpected exceptions
            Log::error("Unexpected error in setDefaultPeriodicityAndPeriod: " . $e->getMessage());
            FlashMessageHelper::flashError('An unexpected error occurred while setting default periodicity or period.');
        }
    }


    // Determine the current quarter based on the current month
    private function getCurrentQuarter($date)
    {
        try {
            // Ensure the input date is a valid Carbon instance
            if (!$date instanceof \Carbon\Carbon) {
                throw new \InvalidArgumentException('The provided date is not a valid Carbon instance.');
            }

            $month = $date->month;
            $year = $date->year;

            // Determine the quarter based on the month
            if ($month <= 3) {
                return 'Q1 ' . $year . ' (Jan - Mar)';
            } elseif ($month <= 6) {
                return 'Q2 ' . $year . ' (Apr - Jun)';
            } elseif ($month <= 9) {
                return 'Q3 ' . $year . ' (Jul - Sep)';
            } else {
                return 'Q4 ' . $year . ' (Oct - Dec)';
            }
        } catch (\InvalidArgumentException $e) {
            // Handle invalid argument exception, e.g., invalid date
            Log::error("Invalid argument in getCurrentQuarter: " . $e->getMessage());
            FlashMessageHelper::flashError('Invalid date provided for determining the quarter.');
        } catch (\Exception $e) {
            // Catch general exceptions (e.g., unexpected errors)
            Log::error("Unexpected error in getCurrentQuarter: " . $e->getMessage());
            FlashMessageHelper::flashError('An unexpected error occurred while determining the quarter.');
        }
    }



    // Determine the current half-year based on the current month
    private function getCurrentHalfYear($date)
    {
        try {
            // Ensure the input date is a valid Carbon instance
            if (!$date instanceof \Carbon\Carbon) {
                // If not, try to create a Carbon instance from the date
                $date = \Carbon\Carbon::parse($date);
            }

            // Get the month from the Carbon instance
            $month = $date->month;

            // Determine which half of the year it is
            if ($month <= 6) {
                return 'Jan - Jun ' . $date->year; // First half of the year
            } else {
                return 'Jul - Dec ' . $date->year; // Second half of the year
            }
        } catch (\InvalidArgumentException $e) {
            // Handle invalid argument exception, e.g., invalid date
            Log::error("Invalid argument in getCurrentHalfYear: " . $e->getMessage());
            FlashMessageHelper::flashError('Invalid date provided for determining the half-year period.');
        } catch (\Exception $e) {
            // Catch general exceptions (e.g., unexpected errors)
            Log::error("Unexpected error in getCurrentHalfYear: " . $e->getMessage());
            FlashMessageHelper::flashError('An unexpected error occurred while determining the half-year period.');
        }
    }



    // Update the period options based on the selected periodicity
    public function updatePeriodOptions()
    {
        try {
            // Reset the period to empty when changing periodicity or year range
            $this->period = '';

            // Initialize months, quarters, half-years, or years based on the selected periodicity
            if ($this->periodicity === 'Monthly') {
                // Initialize months for the selected year
                $this->months = collect();
                foreach (['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'] as $month) {
                    $this->months->push($month . ' ' . $this->selectedYear);
                }

                // Set default month based on the current date and selected year
                $this->period = Carbon::now()->year == $this->selectedYear
                    ? Carbon::now()->format('F Y') // Default to current month for selected year
                    : 'January ' . $this->selectedYear; // Default to January for other years

            } elseif ($this->periodicity === 'Quarterly') {
                // Initialize quarters for the selected year
                $this->quarters = collect();
                for ($quarter = 1; $quarter <= 4; $quarter++) {
                    // Format the quarter with month ranges and push to the collection
                    $this->quarters->push($this->getQuarterPeriod("Q$quarter {$this->selectedYear}"));
                }

                // Set default quarter based on the current date and selected year
                $this->period = $this->selectedYear == Carbon::now()->year
                    ? $this->getCurrentQuarter(Carbon::now())  // Use full quarter format
                    : $this->getQuarterPeriod("Q1 {$this->selectedYear}"); // Default to Q1 for other years

            } elseif ($this->periodicity === 'Half yearly') {
                // Initialize half-year periods for the selected year
                $this->halfYear = collect();
                $this->halfYear->push("Jan - Jun " . $this->selectedYear);
                $this->halfYear->push("Jul - Dec " . $this->selectedYear);

                // Set default half-year period based on the current date and selected year
                $this->period = $this->selectedYear == Carbon::now()->year
                    ? $this->getCurrentHalfYear(Carbon::now()) // Default to current half-year for selected year
                    : 'Jan - Jun ' . $this->selectedYear; // Default to first half for other years

            } elseif ($this->periodicity === 'Yearly') {
                // Initialize yearly options for the selected year
                $this->years = collect();
                $this->years->push($this->selectedYear); // Only the selected year

                // Set default to the selected year
                $this->period = $this->selectedYear; // Default to the selected year
            }
        } catch (\Illuminate\Database\QueryException $e) {
            // Catch database-related exceptions (e.g., failure to query or retrieve data)
            Log::error("Database query error in updatePeriodOptions: " . $e->getMessage());
            FlashMessageHelper::flashError('There was a database error while updating period options.');
        } catch (\Exception $e) {
            // Catch general exceptions (e.g., unexpected errors)
            Log::error("Unexpected error in updatePeriodOptions: " . $e->getMessage());
            FlashMessageHelper::flashError('An unexpected error occurred while updating period options.');
        }
    }


    public function updatedStartYear()
    {
        $this->updatePeriodOptions();  // Recalculate available periods and default
    }

    // Load employee list based on logged-in employee's company ID
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
            })
                ->whereIn('employee_status', ['active', 'on-probation']);

            // Add the searchTerm filter if it's provided
            if (!empty($this->searchTerm)) {
                $query->where(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', '%' . $this->searchTerm . '%');
            }

            // Order by the full name in alphabetical order (ascending)
            $this->employeeIds = $query->orderBy(DB::raw("CONCAT(first_name, ' ', last_name)"))
                ->where('employee_status', 'active')
                ->pluck(DB::raw("CONCAT(first_name, ' ', last_name) as full_name"), 'emp_id')
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

    public $newlyJoinEmployeeIds;
    public $selectAllNewJoin;

    public function selectNewlyJoinEpList()
    {
        $this->newlyJoinEmployeeIds = EmployeeDetails::whereNull('confirmation_date')
            ->where('employee_status', 'on-probation')->pluck('emp_id')->toArray();
    }

    public function openEmployeeList()
    {
        $this->showEmployeeSelectionList = true;
        $this->showToSelect = false;
    }

    public function toggleSearchEmployee()
    {
        // Toggle the visibility of the employee search container
        $this->showEmployeeSearch = true;
        $this->loadEmployeeList();
    }
    public function closeContainer()
    {
        $this->showEmployeeSearch = false;
        $this->searchTerm = '';
    }
    // Handle "Select All" functionality
    public function toggleSelectAll()
    {
        if ($this->selectAll) {
            // Select all employees
            $this->selectedEmployees = array_keys($this->employeeIds); // Assuming $this->employeeIds holds employee data
        } elseif ($this->selectAllNewJoin) {
            // Deselect all employees
            $this->selectedEmployees = $this->newlyJoinEmployeeIds;
        } else {
            $this->selectedEmployees = [];
        }
    }

    public function updatedSelectedEmployees($value)
    {
        // Check if all employees are selected to update the "select all" checkbox state
        $this->selectAll = count($this->selectedEmployees) === count($this->employeeIds);
    }


    // Method to store the leave balance for selected leave policies
    public function storeLeaveBalance()
    {
        // Trim whitespace from selected data
        $this->selectedPolicyIds = array_map('trim', $this->selectedPolicyIds);
        $this->selectedEmployeeIds = array_map('trim', $this->selectedEmployees);
        // Validate if selected policies and employees exist
        if (empty($this->selectedPolicyIds) || empty($this->selectedEmployeeIds)) {
            FlashMessageHelper::flashError('Please select at least one leave policy and employee.');
            return;
        }

        try {
            // Step 2: Prepare the leave policies data to be stored in JSON format
            $leavePoliciesData = [];

            foreach ($this->selectedPolicyIds as $policyId) {
                $policy = LeavePolicySetting::find($policyId);
                if ($policy) {
                    // Add policy data to the array (e.g., policy id and grant days)
                    $leavePoliciesData[] = [
                        'leave_policy_id' => $policy->leave_code,
                        'leave_name' => $policy->leave_name,
                        'grant_days' => $policy->grant_days,
                    ];
                }
            }

            // Step 3: Combine selected employees and 'all' option and ensure no duplicates
            $employeeIdsToProcess = [];

            // Merge and deduplicate individual selected employee IDs
            $employeeIdsToProcess = array_merge($employeeIdsToProcess, array_diff($this->selectedEmployeeIds, ['all']));

            // Remove duplicates from the array to avoid processing the same employee twice
            $employeeIdsToProcess = array_unique($employeeIdsToProcess);
            // Log the employees to be processed for debugging purposes
            Log::info('Employees to be processed: ' . implode(', ', $employeeIdsToProcess));

            // Step 4: Loop through each selected policy and create leave balance with a unique batch ID for each policy
            foreach ($leavePoliciesData as $policyData) {
                // Generate a unique batch ID for the policy
                $newBatchIdForPolicy = EmployeeLeaveBalances::max('batch_id') + 1;

                // Step 5: Loop through employees and create leave balance for each employee and policy
                foreach ($employeeIdsToProcess as $empId) {
                    $period = $this->getPeriodBasedOnPeriodicity();
                    // Fetch from_date from Livewire property (assuming this is set)
                    $fromDate = $this->from_date;

                    // Call checkLeaveNames to update grant_days_allow and showFromTodates flag
                    $this->checkLeaveNames();

                    // Automatically calculate to_date when from_date is set
                    $this->calculateToDate();
                    // Create leave balance for the employee and policy with the same batch ID for this policy
                    $this->createLeaveBalance($empId, [$policyData], $period, $newBatchIdForPolicy, $fromDate);
                }
            }

            $this->selectAll = null;
            $this->resetLeaveGrant();
            // Step 7: Clear selected data
            $this->selectedPolicyIds = [];
            $this->selectedEmployeeIds = [];
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error("Database Query Exception: " . $e->getMessage());
            FlashMessageHelper::flashError('A database error occurred. Please try again later.');
        } catch (\Exception $e) {
            Log::error("General Exception: " . $e->getMessage());
            FlashMessageHelper::flashError('An unexpected error occurred. Please try again later.');
        }
    }

    // Helper function to create leave balance for an employee
    private function createLeaveBalance($empIds, $leavePoliciesData, $period, $batchId, $fromDate)
    {
        try {

            // Ensure $empIds is an array
            if (is_string($empIds)) {
                $empIds = explode(',', $empIds); // Convert comma-separated string to array
            }

            // Array to keep track of which errors were displayed for employee-policy pairs
            $processedErrors = [];

            foreach ($empIds as $empId) {
                // Retrieve all leave balance records for the employee, period, and periodicity
                $existingRecords = EmployeeLeaveBalances::where('emp_id', $empId)
                    ->where('period', $period)
                    ->where('periodicity', $this->periodicity)
                    ->get();

                // Iterate through each leave policy for the employee
                foreach ($leavePoliciesData as $policyData) {
                    $leavePolicyId = $policyData['leave_policy_id'];
                    $leaveName = $policyData['leave_name'];
                    $grantDays = $policyData['grant_days'];
                    $currentYear = Carbon::now()->year;

                    // Case: Check for duplicates in existing records
                    $isDuplicate = false;
                    foreach ($existingRecords as $existingRecord) {
                        // Check for matching leave policy for the employee
                        $existingLeavePolicies = json_decode($existingRecord->leave_policy_id, true);

                        foreach ($existingLeavePolicies as $existingPolicy) {
                            // Check if the leave name is the same
                            if (isset($existingPolicy['leave_name']) && $existingPolicy['leave_name'] === $leaveName) {
                                // Check if the `grant_for_year` is the same (should not be the same)
                                if ($existingRecord->granted_for_year == $currentYear) {
                                    // Create a unique error key for this employee-policy combination
                                    $errorKey = "{$empId}-{$leaveName}-{$currentYear}-year";

                                    // Check if this error has already been shown
                                    if (!isset($processedErrors[$errorKey])) {
                                        Log::info("Leave balance for employee {$empId} with leave name {$leaveName} has already been granted for this year.");
                                        FlashMessageHelper::flashError("Leave balance for employee {$empId} with leave name {$leaveName} has already been granted for this year.");
                                        $processedErrors[$errorKey] = true;
                                    }
                                    $isDuplicate = true;
                                    break 2; // Exit both loops if a duplicate is found
                                }

                                // Check if `grant_days` is the same (should not be the same)
                                if ($existingRecord->grant_days == $grantDays) {
                                    // Create a unique error key for grant days
                                    $errorKey = "{$empId}-{$leaveName}-{$grantDays}-days";

                                    // Check if this error has already been shown
                                    if (!isset($processedErrors[$errorKey])) {
                                        Log::info("Leave balance for employee {$empId} with leave name {$leaveName} has the same grant days.");
                                        FlashMessageHelper::flashError("Leave balance for employee {$empId} with leave name {$leaveName} has the same grant days.");
                                        $processedErrors[$errorKey] = true;
                                    }
                                    $isDuplicate = true;
                                    break 2; // Exit both loops if a duplicate is found
                                }
                            }
                        }
                    }

                    // If no duplicates, create or update the leave balance for this policy
                    if (!$isDuplicate) {
                        Log::info('From Date: ' . $this->from_date);
                        Log::info('To Date: ' . $this->to_date);

                        // If no duplicate, create or update the leave balance for this policy
                        EmployeeLeaveBalances::updateOrCreate(
                            [
                                'emp_id' => $empId,
                                'batch_id' => $batchId,
                                'period' => $period,
                                'from_date' => $this->from_date, // Ensure this is coming from Livewire property
                                'to_date' => $this->to_date,
                                'periodicity' => $this->periodicity,
                                'leave_policy_id' => $leavePolicyId, // Policy should be unique per employee
                            ],
                            [
                                'leave_policy_id' => json_encode([$policyData]), // Store leave policy data
                                'status' => 'Granted', // Status is granted when leave is allocated
                                'granted_for_year' => $currentYear, // Store the current year for granted leave
                                'grant_days' => $grantDays, // Ensure the correct grant days are stored
                            ]
                        );
                        Log::info("Leave balance granted for employee {$empId} with leave name {$leaveName}.");
                    }
                }
            }
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error("Database Query Exception while creating or updating leave balance: " . $e->getMessage());
            FlashMessageHelper::flashError('A database error occurred while creating or updating leave balances.');
        } catch (\Exception $e) {
            Log::error("General Exception while creating or updating leave balance: " . $e->getMessage());
            FlashMessageHelper::flashError('An unexpected error occurred while processing leave balances.');
        }
    }




    //reset the fields
    public function resetLeaveGrant()
    {
        $this->periodicity = null;
        $this->period = null;
    }
    private function getPeriodBasedOnPeriodicity()
    {
        if ($this->periodicity == 'Monthly') {
            return $this->period; // Month is selected by the user
        } elseif ($this->periodicity == 'Quarterly') {
            return $this->getQuarterPeriod($this->period); // Calculate quarter period
        } elseif ($this->periodicity == 'Half yearly') {
            return $this->getHalfYearPeriod($this->period); // Calculate half-year period
        } elseif ($this->periodicity == 'Yearly') {
            return $this->period; // Year (e.g., 2024)
        }
    }



    // Helper function to handle quarterly period formatting
    private function getQuarterPeriod($period)
    {
        try {
            // First, try to match the format "Qn YYYY"
            preg_match('/Q(\d) (\d{4})/', $period, $matches);

            if (count($matches) >= 3) {
                // If we got the expected result from preg_match
                $quarter = $matches[1];
                $year = $matches[2];
            } else {
                // If no match for "Qn YYYY", try matching the format "YYYY (Month Range)"
                preg_match('/(\d{4}) \((Jan - Mar|Apr - Jun|Jul - Sep|Oct - Dec)\)/', $period, $matches);

                if (count($matches) < 3) {
                    throw new \InvalidArgumentException('Invalid period format: ' . $period);
                }

                // Extract year and month range
                $year = $matches[1];
                $monthRange = $matches[2];

                // Map month range to quarter
                switch ($monthRange) {
                    case 'Jan - Mar':
                        $quarter = 1;
                        break;
                    case 'Apr - Jun':
                        $quarter = 2;
                        break;
                    case 'Jul - Sep':
                        $quarter = 3;
                        break;
                    case 'Oct - Dec':
                        $quarter = 4;
                        break;
                    default:
                        throw new \InvalidArgumentException('Invalid month range: ' . $monthRange);
                }
            }

            // Return the formatted quarter string with month ranges
            switch ($quarter) {
                case 1:
                    return "$year (Jan - Mar)";
                case 2:
                    return "$year (Apr - Jun)";
                case 3:
                    return "$year (Jul - Sep)";
                case 4:
                    return "$year (Oct - Dec)";
                default:
                    throw new \InvalidArgumentException('Invalid quarter value: ' . $quarter);
            }
        } catch (\InvalidArgumentException $e) {
            // Catch invalid argument errors
            Log::error("Invalid period format or quarter value: " . $e->getMessage());
            FlashMessageHelper::flashError('Invalid period format or quarter value. Please check the input.');
            return $period; // Return the original period as fallback
        } catch (\Exception $e) {
            // Catch any other exceptions
            Log::error("Unexpected error: " . $e->getMessage());
            FlashMessageHelper::flashError('An unexpected error occurred while processing the quarter period.');
            return $period; // Return the original period as fallback
        }
    }

    // Helper function to handle half-year period formatting
    private function getHalfYearPeriod($period)
    {
        try {
            // First, try to match the "H1 YYYY" or "H2 YYYY" format
            preg_match('/(H1|H2) (\d{4})/', $period, $matches);

            if (count($matches) >= 3) {
                // If we got the "H1 YYYY" or "H2 YYYY" format
                $halfYear = $matches[1]; // H1 or H2
                $year = $matches[2]; // Year (e.g., 2024)

                // Return the formatted half-year string with month ranges
                switch ($halfYear) {
                    case 'H1':
                        return "$year (January - June)";
                    case 'H2':
                        return "$year (July - December)";
                    default:
                        throw new \InvalidArgumentException('Invalid half-year value: ' . $halfYear);
                }
            }

            // If no match for "H1 YYYY" or "H2 YYYY", try matching the "Jan - Jun" or "Jul - Dec" format
            preg_match('/(Jan - Jun|Jul - Dec) (\d{4})/', $period, $matches);

            if (count($matches) >= 3) {
                // If we matched the month range format
                $monthRange = $matches[1]; // "Jan - Jun" or "Jul - Dec"
                $year = $matches[2]; // Year (e.g., 2024)

                // Map month range to half-year
                switch ($monthRange) {
                    case 'Jan - Jun':
                        return "$year (January - June)";
                    case 'Jul - Dec':
                        return "$year (July - December)";
                    default:
                        throw new \InvalidArgumentException('Invalid month range: ' . $monthRange);
                }
            }

            // If no valid format is matched, throw an InvalidArgumentException
            throw new \InvalidArgumentException('Invalid half-year period: ' . $period);
        } catch (\InvalidArgumentException $e) {
            // Catch invalid half-year period errors
            Log::error("Invalid half-year period: " . $e->getMessage());
            FlashMessageHelper::flashError('Invalid half-year period. Please check the input.');
            return $period; // Return the original period as fallback
        } catch (\Exception $e) {
            // Catch any other exceptions
            Log::error("Unexpected error: " . $e->getMessage());
            FlashMessageHelper::flashError('An unexpected error occurred while processing the half-year period.');
            return $period; // Return the original period as fallback
        }
    }

    public function showGrantLeaveTab()
    {
        $this->showActiveGrantLeave = true;
        $this->showLeaveBalanceSummary = false;
    }

    // Triggered when clicking the delete icon
    public function deleteLeaveBalanceBatch($batchId)
    {
        // Set the batchId to delete and show the confirmation modal
        $this->deletionType = 'batch';
        $this->batchIdToDelete = $batchId;
        $this->showConfirmDeletionBox = true;
    }

    public function deleteLeaveBalanceEmp($id)
    {
        // Set the batchId to delete and show the confirmation modal
        $this->deletionType = 'employee_balance';
        $this->empIdToDelete = $id;
        $this->showConfirmDeletionBox = true;
    }
    public function getLeaveBalanceEmp($id)
    {
        $this->showEditModal = true;
        $this->empIdToUpdate = $id;
        $balData = EmployeeLeaveBalances::where('id', $this->empIdToUpdate)->first();

        // Decode the JSON field to array
        $this->leavePolicyData = json_decode($balData->leave_policy_id, true);
    }

    public function editLeaveBal()
    {
        // Fetch the record to update
        $balData = EmployeeLeaveBalances::where('id', $this->empIdToUpdate)->first();

        // Here, we update the leave_policy_id field with the updated array (encoded as JSON)
        $balData->leave_policy_id = json_encode($this->leavePolicyData);
        // Save the updated record
        $balData->save();
        FlashMessageHelper::flashSuccess('Leave balance updated!');
    }

    public function deleteAnEmpBal()
    {
        try {
            $empBalance = EmployeeLeaveBalances::where('id', $this->empIdToDelete)->get();
            if ($empBalance->isEmpty()) {
                FlashMessageHelper::flashError('No entries found for batch_id 1!');
            } else {
                // Loop through each batch and check if deleted_at is not null before deleting
                foreach ($empBalance as $batch) {
                    if ($batch) {
                        // Soft delete the batch
                        $batch->delete();
                    } else {
                        FlashMessageHelper::flashError('An error occurred while deleting.');
                    }
                }
            }
            FlashMessageHelper::flashSuccess('Employee Leave balance has been deleted successfully!');
            $this->showConfirmDeletionBox = false;
        } catch (\Exception $e) {
            FlashMessageHelper::flashError('An error occurred while deleting the balance: ' . $e->getMessage());
        }
    }
    public function confirmDeletion()
    {
        try {
            // Attempt to find all entries with batch_id 1
            $batches = EmployeeLeaveBalances::where('batch_id', $this->batchIdToDelete)->get();
            // Check if any batches were found
            if ($batches->isEmpty()) {
                FlashMessageHelper::flashError('No entries found for batch_id 1!');
            } else {
                // Loop through each batch and check if deleted_at is not null before deleting
                foreach ($batches as $batch) {
                    if ($batch) {
                        // Soft delete the batch
                        $batch->delete();
                    } else {
                        FlashMessageHelper::flashError('Batch with id ' . $batch->id . ' has not been marked for deletion.');
                    }
                }
            }
            FlashMessageHelper::flashSuccess('Leave balance batches for batch_id 1 deleted successfully!');
            $this->showConfirmDeletionBox = false;
        } catch (\Exception $e) {
            FlashMessageHelper::flashError('An error occurred while deleting the batches: ' . $e->getMessage());
        }

        // Hide the confirmation modal after the deletion or failure
        $this->showConfirmDeletionBox = false;
    }

    // When the user cancels the deletion
    public function cancelDeletion()
    {
        // Hide the confirmation modal without doing anything
        $this->showConfirmDeletionBox = false;
        $this->showEditModal = false;
    }
    public $showFromTodates = false;
    public $grant_days_allow = 0;
    // Method that runs when checkbox changes
    public function checkLeaveNames()
    {
        // Reset the showFromTodates to false before checking
        $this->showFromTodates = false;
        // Iterate over selected policy IDs
        foreach ($this->selectedPolicyIds as $policyId) {
            // Find the policy by ID in LeavePolicySetting
            $policy = LeavePolicySetting::find($policyId);

            if ($policy) {
                // Check if the leave name is "Marriage Leave", "Paternity Leave", or "Maternity Leave"
                if (in_array($policy->leave_name, ['Marriage Leave', 'Paternity Leave', 'Maternity Leave'])) {
                    // Set the flag to true if it's one of the specified leave types
                    $this->showFromTodates = true;

                    // Store the grant days for the leave
                    $this->grant_days_allow = $policy->grant_days;

                    break;
                }
            }
        }
    }

    public function calculateToDate()
    {
        // Automatically set the to_date when from_date is selected
        if ($this->showFromTodates && $this->grant_days_allow > 0 && $this->from_date) {
            $fromDate = \Carbon\Carbon::parse($this->from_date);
            // Add (grant_days_allow - 1) to the from_date to calculate the to_date
            $this->to_date = $fromDate->addDays($this->grant_days_allow - 1)->toDateString();
        }
    }


    public function render()
    {

        return view('livewire.emp-leave-granter-details', [
            'employeeLeaveBalance' => $this->employeeLeaveBalance,
            'groupedData' => $this->groupedData,
            'leavePolicyData' => $this->leavePolicyData,
            'employeeIds' => $this->employeeIds
        ]);
    }
}

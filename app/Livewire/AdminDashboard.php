<?php

namespace App\Livewire;

use Livewire\Component;
use App\Helpers\FlashMessageHelper;
use App\Helpers\LeaveHelper;
use App\Models\AdminFavoriteModule;
use App\Models\EmpDepartment;
use App\Models\EmployeeDetails;
use App\Models\EmpResignations;
use App\Models\Hr;
use App\Models\LeaveRequest;
use App\Models\SwipeRecord;
use Illuminate\Support\Facades\DB;
use App\Models\Task;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AdminDashboard extends Component
{
    public $show = false;
    public $signInTime;
    public $totalEmployeeCount;
    public $totalNewEmployeeCount;
    public $totalActiveEmpList;
    public $labels;
    public $data;
    public $departmentCount;
    public $maleCount = 0;
    public $femaleCount = 0;
    public $employeeCountsByLocation;
    public $loginEmployee;
    public $hrRequests;
    public $hrRequestsCount;
    public $activeEmployeesCount;
    public $activeEmployees;
    public $newEmployees;
    public $newEmployeedeparts;
    public $activeTab = 'active';
    public $mappedLeaveData = [];
    public $searchContent = '';
    public $overviewItems = [];
    public $overviewContentList = [];

    public $selectedAction = null;
    public $dataEmp;
    public $earlyOrOnTimeEmployees;
    public $topLeaveTakers;
    public $groupedByEmpId;
    public $lateEmployees;
    public $swipes;
    public $dateRange = 'thisMonth';
    public $tasksData = [];
    public $deptCounts;
    public $backgroundColors;
    public $colors;
    public $selectedOption = 'This Year';
    public $otherCount;
    public $departmentChartData;
    public $notAvailableCount;
    public $selectedDepartments = '';
    public $allDepartments;
    public $currentYear;
    public $currentMonth;
    public $lastMonth;
    public $totalEmployees;
    public $empStatus;
    public $employeeTypes;
    public $serviceAgeLabels = [];
    public $serviceAgeCounts = [];
    public $present;
    public $late;
    public $absent;
    public $total = 0;
    public $serviceData;
    public $selecetedTime;
    public function updateOption($option)
    {
        $this->selectedOption = $option;
        $activeTab = $this->activeTab;
        $this->loadChartData();
        return redirect()->route('admin-dashboard', [
            'activeTab' => $activeTab,
            'selectedOption' => $this->selectedOption, // Optionally pass the selected option
        ]);
    }


    public function loadChartData()
    {
        $startDate = null;
        $endDate = null;

        // Determine the date range based on the selected option
        switch ($this->selectedOption) {
            case 'This Week':
                $startDate = Carbon::now()->startOfWeek();
                $endDate = Carbon::now()->endOfWeek();
                break;
            case 'This Month':
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                break;
            case 'Last Month':
                $startDate = Carbon::now()->subMonth()->startOfMonth();
                $endDate = Carbon::now()->subMonth()->endOfMonth();
                break;
            case 'This Year':
                $startDate = Carbon::now()->startOfYear();
                $endDate = Carbon::now()->endOfYear();
                break;
        }
        $totalTasks = Task::whereBetween('created_at', [$startDate, $endDate])->count();
        $closed = Task::where('status', 11)->whereBetween('created_at', [$startDate, $endDate])->count();
        $opened = Task::where('status', 10)->whereBetween('created_at', [$startDate, $endDate])->count();
        $overdue = Task::where('status', 10)
            ->whereDate('due_date', '<', now())
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        $this->tasksData = [
            'labels' => ['Opened', 'Closed', 'Overdue'],
            'series' => $totalTasks > 0 ? [
                round(($opened / $totalTasks) * 100, 2),
                round(($closed / $totalTasks) * 100, 2),
                round(($overdue / $totalTasks) * 100, 2),
            ] : [0, 0, 0],
            'totalTasks' => $totalTasks
        ];
    }
    public function setAction($action)
    {
        $this->selectedAction = $action;
        // Perform the redirect to the route with the action
        return redirect()->route('employee.data.update', ['action' => $action]);
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function mount()
    {
        try {
            $this->selectedDepartments;
            $this->signInTime = today()->format('Y-m-d');
            $this->selecetedTime = today()->format('Y-m-d');
            $this->getContainerData();
            $this->loginHRDetails();
            $this->setActiveTab($this->activeTab);
            $employeeId = auth()->guard('hr')->user()->emp_id;

            // $this->loginEmployee = Hr::where('emp_id', $employeeId)->select('emp_id', 'employee_name')->first();
            $companyId = EmployeeDetails::where('emp_id', $employeeId)->value('company_id');
            //Hr Requests
            $deptId = EmpDepartment::pluck('dept_id');
            $this->dataEmp = EmployeeDetails::with('empDepartment')
                ->whereIn('dept_id', $deptId)
                ->whereJsonContains('company_id', $companyId)
                ->inRandomOrder()
                ->get()->take(5);
            $this->getTopLeaveTakers();
            $this->getSignInOutData();
            $this->getHrRequests($companyId);
            $this->getEmployeesCount($companyId);
            // Count total employees
            // $this->totalEmployeeCount = EmployeeDetails::where('company_id', $companyId)->count();
            // Count of employees for the department year
            $this->totalActiveEmpList = EmployeeDetails::whereJsonContains('company_id', $companyId)
                ->where('status', 1)
                ->get();

            $this->totalEmployees = $this->totalActiveEmpList->count();
            if ($this->totalActiveEmpList && $this->totalActiveEmpList->count()) {
                $empStatus = $this->totalActiveEmpList->groupBy('employee_type');

                $totalEmployees = $this->totalActiveEmpList->count();

                $employeeTypes = [];
                foreach ($empStatus as $type => $group) {
                    $count = $group->count();
                    $percentage = round(($count / $totalEmployees) * 100, 2);
                    $this->employeeTypes[] = [
                        'type' => $type,
                        'count' => $count,
                        'percentage' => $percentage,
                    ];
                }
            } else {
                $this->employeeTypes = []; // fallback empty array to prevent error
            }
            //get all departments
            $this->allDepartments = EmpDepartment::all();
            // Get total employees grouped by location
            $this->employeeCountsByLocation = EmployeeDetails::select('job_location', DB::raw('count(*) as count'))
                ->whereJsonContains('company_id', $companyId)
                ->groupBy('job_location')
                ->get();

            // Get gender distribution for the company
            $genderDistribution = EmployeeDetails::select('gender', DB::raw('count(*) as count'))
                ->whereJsonContains('company_id', $companyId)
                ->where('status', 1)
                ->groupBy('gender')
                ->get();
            $this->labels = $genderDistribution->pluck('gender');
            $this->data = $genderDistribution->pluck('count');
            $colors = [
                'MALE' => 'rgb(255, 99, 132)',
                'FEMALE' => 'rgb(54, 162, 235)',
                'OTHER' => 'rgb(255, 205, 86)',
                '' => 'rgb(201, 203, 207)'
            ];

            // Loop through the gender distribution data to calculate male and female counts
            foreach ($genderDistribution as $distribution) {
                if ($distribution->gender === 'MALE') {
                    $maleCount = $distribution->count;
                } elseif ($distribution->gender === 'FEMALE') {
                    $femaleCount = $distribution->count;
                } elseif ($distribution->gender === 'OTHER') {
                    $otherCount = $distribution->count;
                } elseif (empty($distribution->gender) || is_null($distribution->gender)) {
                    $notAvailableCount = $distribution->count;
                }
            }
            $this->maleCount = $maleCount ?? 0;
            $this->femaleCount = $femaleCount ?? 0;
            $this->otherCount = $otherCount ?? 0;
            $this->notAvailableCount = $notAvailableCount ?? 0;
            $this->backgroundColors = $genderDistribution->pluck('gender')->map(function ($label) use ($colors) {
                return $colors[$label] ?? 'rgba(201, 203, 207, 0.5)';
            });

            $this->serviceData = EmployeeDetails::where('status', 1)
                ->select('service_age')
                ->get()
                ->pluck('service_age');

            $this->filterDepartmentChart();
            $this->getAttendanceOverView();
            $this->getServiceAgeDistribution();
        } catch (\Exception $e) {
            if ($e instanceof \Illuminate\Database\QueryException) {
                // Handle database query exceptions
                Log::error("Database error registering employee: " . $e->getMessage());
                FlashMessageHelper::flashError('Database connection error occurred. Please try again later.');
            } elseif (strpos($e->getMessage(), 'Call to a member function store() on null') !== false) {
                // Display a user-friendly error message for null image
                FlashMessageHelper::flashError('Please upload an image.');
            } elseif ($e instanceof \Illuminate\Http\Client\RequestException) {
                // Handle network request exceptions
                Log::error("Network error registering employee: " . $e->getMessage());
                FlashMessageHelper::flashError('Network error occurred. Please try again later.');
            } elseif ($e instanceof \PDOException) {
                // Handle database connection errors
                Log::error("Database connection error registering employee: " . $e->getMessage());
                FlashMessageHelper::flashError('Database connection error. Please try again later.');
            } else {
                // Handle other generic exceptions
                Log::error("Error registering employee: " . $e->getMessage());
                FlashMessageHelper::flashError('Failed to register employee. Please try again later.');
            }
            // Redirect the user back to the registration page or any other appropriate action
            return redirect()->back();
        }
    }


    //service age distributionuse Illuminate\Database\QueryException;
    public function getServiceAgeDistribution()
    {
        try {
            $distribution = EmployeeDetails::where('status', 1)
                ->selectRaw('service_age, COUNT(*) as count')
                ->groupBy('service_age')
                ->orderBy('service_age')
                ->get();

            $this->serviceAgeLabels = $distribution->pluck('service_age')->toArray();
            $this->serviceAgeCounts = $distribution->pluck('count')->toArray();
        } catch (QueryException $e) {
            // Handle database query errors
            FlashMessageHelper::flashError('Database query error while fetching service age.' );
            $this->serviceAgeLabels = [];
            $this->serviceAgeCounts = [];
        } catch (Exception $e) {
            // Handle all other errors
            FlashMessageHelper::flashError('General error while fetching service age.' );
            $this->serviceAgeLabels = [];
            $this->serviceAgeCounts = [];
        }
    }



    //chart for department wise
    public function filterDepartmentChart()
    {
        $filteredList = $this->totalActiveEmpList;

        if (!empty($this->selectedDepartments)) {
            $filteredList = $filteredList->filter(function ($emp) {
                return $emp['dept_id'] == $this->selectedDepartments;
            });
        }

        $this->deptCounts = $filteredList
            ->groupBy('dept_id')
            ->map(fn($group) => $group->count())
            ->sortDesc();
        $departmentNames = EmpDepartment::whereIn('dept_id', $this->deptCounts->keys()->filter()->all())
            ->pluck('department', 'dept_id');

        $this->departmentChartData = $this->deptCounts->mapWithKeys(function ($count, $deptId) use ($departmentNames) {
            $deptName = $departmentNames[$deptId] ?? 'Not Assigned';
            return [$deptName => $count];
        });

        // If you want to emit to JS

        $this->dispatch('admin-dashboard', data: $this->departmentChartData);
    }


    //get top 5 leavew takrs data
    public function getTopLeaveTakers()
    {
        try {
            $currentYear = date('Y');
            $currentMonth = date('m');
            $lastMonth = date('m', strtotime('last month'));
            $employeeId = auth()->guard('hr')->user()->emp_id;
            $companyId = EmployeeDetails::where('emp_id', $employeeId)->value('company_id');
            // Fetch employee ids with status 1 and matching company_id
            $emp_ids = EmployeeDetails::where('status', 1)
                ->whereJsonContains('company_id', $companyId)
                ->pluck('emp_id');

            // Initialize an empty array for the matching emp_ids
            $data = [];

            // Iterate through emp_ids to fetch matching leave takers
            foreach ($emp_ids as $emp_id) {
                // Determine the period to fetch leave days for based on the selected range
                if ($this->dateRange == 'thisMonth') {
                    $approvedLeaves = LeaveHelper::getApprovedLeaveDaysForFilter($emp_id, $currentMonth, $this->dateRange); // This month
                } elseif ($this->dateRange == 'lastMonth') {
                    $approvedLeaves = LeaveHelper::getApprovedLeaveDaysForFilter($emp_id, $lastMonth, $this->dateRange); // Last month
                } else {
                    $approvedLeaves = LeaveHelper::getApprovedLeaveDaysForFilter($emp_id, $currentYear, $this->dateRange); // This year
                }
                $data[$emp_id] = $approvedLeaves;
            }
            // Initialize an array to store the top 5 highest leave days for each type
            $topLeaveDays = [];
            $top5LeaveDays = [];
            // Iterate through each employee's leave data
            foreach ($data as $emp_id => $leaveData) {
                // Sort the leave data for each employee and get the top 5
                $totalLeave = array_sum($leaveData);
                // Only add to the array if the total leave days is greater than 0
                if ($totalLeave > 0) {
                    $topLeaveDays[$emp_id] = $totalLeave;
                }
            }
            // Check if the array is populated
            if (isset($topLeaveDays)) {
                // Sort the total leave days in descending order to get the top 5 employees with the most leave days
                arsort($topLeaveDays);
                // Get the top 5 employees with the highest total leave days
                $top5LeaveDays = array_slice($topLeaveDays, 0, 5);
            } else {
                $top5LeaveDays[] = [];
            }
            // Check if top4LeaveDays is set and not empty
            if (isset($top5LeaveDays) && !empty($top5LeaveDays)) {
                // Iterate through the leave data and get the employee details
                foreach ($top5LeaveDays as $key => $value) {
                    // Fetch the employee details based on emp_id
                    $employee = EmployeeDetails::where('emp_id', $key)->first();  // Assuming emp_id is unique
                    if ($employee) {
                        // Map the employee details with the leave days
                        $this->mappedLeaveData[$key] = [
                            'totalLeave' => $value,
                            'first_name' => $employee->first_name,
                            'last_name' => $employee->last_name,
                            'job_role' => $employee->job_role,
                            'image' => $employee->image,
                        ];
                    }
                }
            } else {
                // If top4LeaveDays is not set, map an empty array
                $this->mappedLeaveData = [];
            }
        } catch (\Exception $e) {
            if ($e instanceof \Illuminate\Database\QueryException) {
                // Handle database query exceptions
                Log::error("Database error getting leaves of employee: " . $e->getMessage());
                FlashMessageHelper::flashError('dfghj Database connection error occurred. Please try again later.');
            } elseif (strpos($e->getMessage(), 'Call to a member function store() on null') !== false) {
                // Display a user-friendly error message for null image
                FlashMessageHelper::flashError('An error occured while getting leave data.');
            } elseif ($e instanceof \Illuminate\Http\Client\RequestException) {
                // Handle network request exceptions
                Log::error("Network error registering employee: " . $e->getMessage());
                FlashMessageHelper::flashError('Network error occurred. Please try again later.');
            } elseif ($e instanceof \PDOException) {
                // Handle database connection errors
                Log::error("Database connection error registering employee: " . $e->getMessage());
                FlashMessageHelper::flashError('Database connection error. Please try again later.');
            } else {
                // Handle other generic exceptions
                Log::error("Error registering employee: " . $e->getMessage());
                FlashMessageHelper::flashError('Failed to get leave data. Please try again later.');
            }
            // Redirect the user back to the registration page or any other appropriate action
            return redirect()->back();
        }
    }

    public function getSignInOutData()
    {
        try {
            // Fetch swipe data, including employee details
            $this->swipes = SwipeRecord::with('employee')
                ->whereDate('created_at', $this->signInTime)
                ->orderBy('emp_id') // Ensure order by employee_id
                ->orderBy('created_at', 'asc')  // Order by creation date to get the first swipe for each employee
                ->get()
                ->unique('emp_id');

            // Initialize arrays to store late and early/on-time employees
            $this->lateEmployees = [];
            $this->earlyOrOnTimeEmployees = [];

            // Loop through the swipes to check for late or early/on-time entries
            foreach ($this->swipes as $swipe) {
                // Parse the swipe time using Carbon
                $swipeTime = Carbon::parse($swipe->swipe_time);

                // Define the "late" threshold time (10:00 AM)
                $lateThreshold = $swipeTime->copy()->setTime(10, 0, 0);  // 10:00 AM
                // Check if the employee's swipe time is later than 10:00 AM and if it is an "IN" swipe
                if ($swipe->in_or_out === 'IN' && $swipeTime->gt($lateThreshold)) {
                    // Add the employee to the late employees array
                    $this->lateEmployees[] = $swipe;
                } else {
                    // Add the employee to the early or on-time employees array
                    $this->earlyOrOnTimeEmployees[] = $swipe;
                }
            }
        } catch (\Exception $e) {
            Log::error("Database error getting swipe data: " . $e->getMessage());
            FlashMessageHelper::flashError('An error occurred while getting swipe data. Please try again later.');
        }
    }

    public function loginHRDetails()
    {
        $user = auth()->guard('hr')->user();
        $this->loginEmployee = Hr::with('emp')->where('emp_id', $user->emp_id)->first();
    }
    public function getHrRequests($companyIds)
    {
        // Retrieve HR requests where the company_id contains any of the given company IDs
        $this->hrRequests = EmpResignations::join('employee_details', 'employee_details.emp_id', '=', 'emp_resignations.emp_id')
            ->where('emp_resignations.status', '5')
            ->where(function ($query) use ($companyIds) {
                foreach ($companyIds as $companyId) {
                    $query->orWhereRaw('JSON_CONTAINS(employee_details.company_id, ?)', [json_encode($companyId)]);
                }
            })
            ->get();
        // dd( $this->hrRequests);
        // Count the number of HR requests
        $this->hrRequestsCount = $this->hrRequests->count();
    }
    public function getEmployeesCount($companyIds)
    {
        $thirtyDaysAgo = Carbon::now()->subDays(30);
        // dd( $thirtyDaysAgo);

        $this->activeEmployees = EmployeeDetails::where('status', 1)
            ->where(function ($query) use ($companyIds) {
                foreach ($companyIds as $companyId) {
                    $query->orWhereJsonContains('company_id', $companyId);
                }
            })
            ->get();
        $this->activeEmployeesCount = $this->activeEmployees->count();
        $this->newEmployees = $this->activeEmployees->where('hire_date', '>=', $thirtyDaysAgo)->count();
        $this->newEmployeedeparts = $this->activeEmployees->where('hire_date', '>=', $thirtyDaysAgo)->unique('dept_id')->count();
        // dd( $this->newEmployeedeparts);
    }
    public function updatedSearchContent()
    {
        // Call the method to filter the data
        $this->getContainerData();
    }

    public $showDynamicContent = false;

    public function toggleContent()
    {
        $this->showDynamicContent = !$this->showDynamicContent;
        $this->setCategory('');
    }

    public $categoryFilter = '';
    // Method to set the category filter
    public function setCategory($category)
    {
        $this->categoryFilter = $category;
        $this->getContainerData();
    }

    public function getContainerData()
    {
        try {
            // Fetch all favorite modules with the starred status
            $favoriteModules = AdminFavoriteModule::all();
            $favoriteModulesArray = $favoriteModules->pluck('module_name')->toArray();
            $favoriteCategoriesArray = $favoriteModules->pluck('module_category')->toArray();
            $starredModulesArray = $favoriteModules->where('is_starred', true)->pluck('module_name')->toArray(); // Get starred modules

            // Define the overview content list
            $this->overviewContentList = [
                'employee' => [
                    '/hr/update-employee-details' => 'Update Employee Data',
                    '/hr/add-employee-details/{employee?}' => 'Add Employee Data',
                    '/hr/letter/prepare' => 'Prepare Letter',
                    '/hr/employee-data-update/disable' => 'Disable Portal Access',
                    '/hr/employee-data-update/enable' => 'Enable Portal Access',
                    '/hr/user/employee-separation' => 'Employee Separation',
                    '/hr/employee-data-update/confirm' => 'Confirm Employee',
                    '/hr/employee-data-update/extend' => 'Extend Probation Period',
                    '/8' => 'Exclude From Payroll',
                    '/hr/employee-data-update/delete' => 'Delete Employee',
                    '/hr/user/emp/admin/bulkphoto-upload' => 'Bulk Photo Upload',
                    '/hr/user/analytics-hub' => 'People Analytical Hub',
                    '/hr/user/hr-organisation-chart' => 'Organization Chart',
                    '/26' => 'Assign Manager',
                    '/hr/request' => 'Offboarding Request',
                    '/hr/HelpDesk' => 'HelpDesk',
                    '/hr/hrFeeds' => 'Engage',
                ],

                'payroll' => [
                    '/hr/user/stop-salaries' => 'Stop Salary Processing',
                    '/hr/user/employee-lop-days' => 'Deduct Loss Of Pay(LOP)',
                    '/hr/payslips' => 'Payslips',
                    '/hr/ctcslips' => 'CTC Slips',
                    '/hr/ytdreport' => 'YTD Reports',
                    '/hr/pfytdreport' => 'PF YTD REport',
                    '/hr/itstatement' => 'IT Statement',
                    '/27' => 'Print/Email Payslips',
                    '/29' => 'Print/Email Reimbursement Payslip',
                    '/30' => 'Arrears',
                    '/31' => 'Update Employee PAN Number',
                    '/hr/user/salary-revision' => 'Revise Employee Salary',
                    '/33' => 'Process Payroll',
                    '/34' => 'Release IT Declaration',
                    '/35' => 'Download IT Declaration For TDS',
                    '/36' => 'Create New Payroll Month',
                    '/hr/user/payroll-salary' => 'Update Payroll Data',
                    '/38' => 'Pay Arrears',
                    '/39' => 'Verify Payroll Difference',
                    '/40' => 'Generate Payroll Statement',
                    '/41' => 'Generate Accounts JV',
                    '/42' => 'Release Payslips To Employees',
                    '/hr/user/hold-salaries' => 'Hold Salary Account',
                    '/hr/user/release-salary ' => 'Release Salary Account',
                    '/46' => 'Resettle Employee',
                    '/47' => 'Add TDS Challan',
                    '/48' => 'Bank Transfer',
                    '/49' => 'Track Cash/Cheque Payment',
                    '/50' => 'PF KYC Mapping',
                    '/51' => 'Create and Release FBP Plan',
                    '/52' => 'Salary Staement for a Month',
                    '/53' => 'Employee Wise Payslip Release',
                    '/54' => 'Approve Reimbursement'
                ],
                'leave' => [
                    '/hr/user/holidayList' => 'Add Holidays',
                    '/hr//user/grant-summary' => 'Grant Leave',
                    '/hr/user/leaveYearEndProcess' => 'Year End Process',
                    '/hr/user/leave-approval' => 'Approve Leave',
                    '/hr/user/approval-leave-cancellation' => 'Approve Leave Cancellation',
                    // '/61' => 'Approve RH',
                    '/hr/user/leave-calendar' => 'Leave Calendar',
                    // '/63' => 'Approve Comp Off',
                    '/64' => 'Update Employee Weekdays'
                ],
                'attendance' => [
                    '/hr/user/employee-swipes-for-hr' => 'Verify Employee Swipes',
                    '/hr/user/attendance-muster-hr' => 'Attendance Muster',
                    '/hr/user/hr-manual-override' => 'Manual Override',
                    '/hr/user/shift-roster-hr' => 'Shift Roster',
                    '/hr/user/shift-override' => 'Shift Override',
                    '/hr/user/attendance-exception' => 'Attendance Exception',
                    '/70' => 'Attendance Period Finalization',
                    '/71' => 'Update Sign In IP Address',
                    '/hr/user/who-is-in-chart-hr' => 'Who is in ?',
                    '/hr/user/hr-attendance-overview' => 'View Employee Attendance',
                    '/74' => 'Review Attendance Regularization',
                    '/75' => 'Delete Attendance Manual Override',
                ],
                'other' => [
                    '/a' => 'Uploads Documents in Bulk',
                    '/b' => 'Set Up Workflow Delegate for an Employee',
                    '/c' => 'List of Values',
                    '/d' => 'Employee Position',
                    '/e' => 'Add New Bank Branch',
                    '/f' => 'Update Compnany Details',
                ]
            ];

            // Get the search term from the user input (if any)
            $searchContent = $this->searchContent ?? '';
            $categoryFilter = $this->categoryFilter;

            // Prepare an array with dynamic classes based on the counter
            $overviewItems = [];
            $counter = 0;

            // Loop through each category in overviewContentList
            foreach ($this->overviewContentList as $category => $contentList) {
                // Filter based on selected category
                if ($categoryFilter && $categoryFilter !== 'favorites' && $categoryFilter !== $category) {
                    continue;
                }

                // If filtering for "favorites", only show starred items
                if ($categoryFilter === 'favorites') {
                    // Filter out non-starred items
                    $contentList = array_filter($contentList, function ($content) use ($starredModulesArray) {
                        return in_array($content, $starredModulesArray);
                    });
                }

                // Define default background and icon classes based on the category
                switch ($category) {
                    case 'employee':
                        $bgClass = 'blue-bg'; // Blue background for employee
                        $iconClass = 'fa-regular fa-user'; // User icon for employee
                        break;

                    case 'payroll':
                        $bgClass = 'green-bg'; // Green background for payroll
                        $iconClass = 'fa-solid fa-dollar-sign'; // Dollar sign icon for payroll
                        break;

                    case 'leave':
                        $bgClass = 'yellow-bg'; // Yellow background for leave
                        $iconClass = 'fa-solid fa-calendar-check'; // Calendar icon for leave
                        break;

                    case 'attendance':
                        $bgClass = 'purple-bg'; // Purple background for attendance
                        $iconClass = 'fa-solid fa-calendar'; // Calendar icon for attendance
                        break;

                    case 'other':
                        $bgClass = 'orag-bg'; // Orange background for other
                        $iconClass = 'fa-regular fa-folder'; // Cogs icon for other
                        break;

                    default:
                        $bgClass = 'default-bg'; // Default background for unspecified categories
                        $iconClass = 'fa-solid fa-question'; // Default icon
                        break;
                }

                // Loop through each item within the category
                foreach ($contentList as $route => $content) {
                    // Ensure content is a string
                    if (is_array($content)) {
                        // If content is an array, concatenate or pick one element
                        $content = implode(' ', $content); // Converts array to string
                    }

                    // Filter items based on the search term
                    if (stripos($content, $searchContent) !== false) { // Case-insensitive search
                        // Check if this module is in the favorites
                        $isFavorite = in_array($content, $favoriteModulesArray) && in_array(ucfirst($category), $favoriteCategoriesArray);

                        // Check if the module is starred
                        $isStarred = in_array($content, $starredModulesArray);

                        // Add the module to the overview items array
                        $overviewItems[] = [
                            'route' => $route,
                            'content' => $content,
                            'bgClass' => $bgClass,
                            'iconClass' => $iconClass,
                            'category' => ucfirst($category),  // Store the category name for reference
                            'isFavorite' => $isFavorite,  // Favorite flag from earlier logic
                            'isStarred' => $isStarred,    // Add the starred flag
                        ];
                        $counter++;
                    }
                }
            }

            // Pass this data to the view
            $this->overviewItems = $overviewItems;
        } catch (Exception $e) {
            // Log the error or handle the exception accordingly
            error_log('Error in getContainerData: ' . $e->getMessage());

            // Optionally, set a flag or message to inform the user of the error
            $this->overviewItems = []; // Empty the items in case of error
            FlashMessageHelper::flashError("An error occurred while processing the data. Please try again later.");
        }
    }

    public function getModuleName($content, $category)
    {
        try {
            // Get the logged-in user's HR and employee IDs
            $logginHrId = Auth::guard('hr')->user()->hr_emp_id;
            $logginHrEmpId = Auth::guard('hr')->user()->emp_id;

            // Check if the favorite module with the same content and category exists
            $favoriteModule = AdminFavoriteModule::where('emp_id', $logginHrEmpId)
                ->where('hr_emp_id', $logginHrId)
                ->where('module_name', $content)
                ->where('module_category', $category)
                ->first();

            if ($favoriteModule) {
                // If it exists, toggle the is_starred value
                $favoriteModule->is_starred = !$favoriteModule->is_starred;
                $favoriteModule->save();
            } else {
                // If it does not exist, create a new record with is_starred set to true
                AdminFavoriteModule::create(
                    [
                        'emp_id' => $logginHrEmpId,
                        'hr_emp_id' => $logginHrId,
                        'module_name' => $content,
                        'module_category' => $category,
                        'is_starred' => true
                    ]
                );
            }
            // Provide feedback to the user
            FlashMessageHelper::flashSuccess($favoriteModule ? 'Favorite status updated.' : 'Added to my favorite list.');
            $this->getContainerData();
        } catch (Exception $e) {
            // Log the error and provide feedback
            Log::error('Error in getModuleName: ' . $e->getMessage());
            FlashMessageHelper::flashError("An error occurred while updating the favorite module. Please try again later.");
        }
    }

    public function open()
    {
        $this->show = true;
    }

    public function getEmpCountByDept()
    {
        try {
        } catch (Exception $e) {
            // Log the error and provide feedback
            Log::error('Error in chart dept: ' . $e->getMessage());
            FlashMessageHelper::flashError("An error occurred while updating the chart. Please try again later.");
        }
    }

    public function getAttendanceOverView()
    {
        $this->present = 0;
        $this->late = 0;
        $this->absent = 0;

        $employeeIds = $this->totalActiveEmpList->pluck('emp_id');
        $swipes = SwipeRecord::whereDate('created_at', $this->selecetedTime)->get();

        foreach ($employeeIds as $empId) {
            $swipe = $swipes->firstWhere('emp_id', $empId);

            if ($swipe) {
                $signinTimes = Carbon::parse($swipe->swipe_time); // Adjust field name as needed

                if ($signinTimes->lte(Carbon::parse('10:00:00'))) {
                    $this->present++;
                } else {
                    $this->late++;
                }
            } else {
                $this->absent++;
            }
        }

        $this->total = $this->present + $this->late + $this->absent;
    }

    public function render()
    {
        $this->loadChartData();
        return view('livewire.admin-dashboard', [
            'loginEmployee' => $this->loginEmployee,
            'topLeaveTakers' => $this->topLeaveTakers,
            'mappedLeaveData' => $this->mappedLeaveData,
            'lateEmployees' => $this->lateEmployees,
            'swipes' => $this->earlyOrOnTimeEmployees,
            'backgroundColors' => $this->backgroundColors,
            'allDepartments' => $this->allDepartments,
            'total' => $this->total,
            'present' => $this->present,
            'late' => $this->late,
            'absent' => $this->absent,
            'serviceAgeLabels' => $this->serviceAgeLabels,
            'serviceAgeCounts' => $this->serviceAgeCounts
        ]);
    }
}

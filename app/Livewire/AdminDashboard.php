<?php

namespace App\Livewire;

use App\Helpers\FlashMessageHelper;
use App\Models\AdminFavoriteModule;
use App\Models\EmpDepartment;
use App\Models\EmployeeDetails;
use App\Models\EmpResignations;
use App\Models\Hr;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AdminDashboard extends Component
{
    public $show = false;
    public $totalEmployeeCount;
    public $totalNewEmployeeCount;
    public $totalNewEmployees;
    public $labels;
    public $data;
    public $departmentCount;
    public $colors;
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
    public $activeTab = 'summary';

    public $searchContent = '';
    public $overviewItems = [];
    public $overviewContentList = [];

    public $selectedAction = null;
    public $dataEmp;

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

            $this->getHrRequests($companyId);
            $this->getEmployeesCount($companyId);
            // Count total employees
            // $this->totalEmployeeCount = EmployeeDetails::where('company_id', $companyId)->count();

            // Get total employees grouped by location
            $this->employeeCountsByLocation = EmployeeDetails::select('job_location', DB::raw('count(*) as count'))
                ->where('company_id', $companyId)
                ->groupBy('job_location')
                ->get();

            // Count new employees for the current year
            $this->totalNewEmployees = EmployeeDetails::where('company_id', $companyId)
                ->where('status', 1)
                ->whereYear('hire_date', Carbon::now()->year)
                ->get();
            $this->totalNewEmployeeCount = $this->totalNewEmployees->count();
            $departmentNames = [];
            // Check if $newEmployees is not empty
            if ($this->totalNewEmployees->isNotEmpty()) {
                foreach ($this->totalNewEmployees as $employee) {
                    $departmentNames[] = $employee->department;
                }
                $uniqueDepartments = array_unique($departmentNames);

                $this->departmentCount = count($uniqueDepartments);
            } else {
                $this->departmentCount = 0;
            }

            // Get gender distribution for the company
            $genderDistribution = EmployeeDetails::select('gender', DB::raw('count(*) as count'))
                ->where('company_id', $companyId)
                ->groupBy('gender')
                ->get();

            $this->labels = $genderDistribution->pluck('gender');
            $this->data = $genderDistribution->pluck('count');
            $this->colors = [
                'Male' => 'rgb(255, 99, 132)',
                'Female' => 'rgb(54, 162, 235)',
                'Not Active' => 'rgb(255, 205, 86)'
            ];

            // Loop through the gender distribution data to calculate male and female counts
            foreach ($genderDistribution as $distribution) {
                if ($distribution->gender === 'Male') {
                    $maleCount = $distribution->count;
                } elseif ($distribution->gender === 'Female') {
                    $femaleCount = $distribution->count;
                }
            }

            $this->maleCount = $maleCount ?? 0;
            $this->femaleCount = $femaleCount ?? 0;
        } catch (\Exception $e) {
            if ($e instanceof \Illuminate\Database\QueryException) {
                // Handle database query exceptions
                Log::error("Database error registering employee: " . $e->getMessage());
                session()->flash('emp_error', 'Database connection error occurred. Please try again later.');
            } elseif (strpos($e->getMessage(), 'Call to a member function store() on null') !== false) {
                // Display a user-friendly error message for null image
                session()->flash('emp_error', 'Please upload an image.');
            } elseif ($e instanceof \Illuminate\Http\Client\RequestException) {
                // Handle network request exceptions
                Log::error("Network error registering employee: " . $e->getMessage());
                session()->flash('emp_error', 'Network error occurred. Please try again later.');
            } elseif ($e instanceof \PDOException) {
                // Handle database connection errors
                Log::error("Database connection error registering employee: " . $e->getMessage());
                session()->flash('emp_error', 'Database connection error. Please try again later.');
            } else {
                // Handle other generic exceptions
                Log::error("Error registering employee: " . $e->getMessage());
                session()->flash('emp_error', 'Failed to register employee. Please try again later.');
            }
            // Redirect the user back to the registration page or any other appropriate action
            return redirect()->back();
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
                    '/update-employee-details' => 'Update Employee Data',
                    '/add-employee-details/{employee?}' => 'Add Employee Data',
                    '/' => 'Prepare Letter',
                    '/import' => 'Import Data From Excel',
                    '/1' => 'Disable Portal Access',
                    '/2' => 'Enable Portal Access',
                    '/3' => 'Regenerate Employee Password',
                    '/4' => 'Employee Separation',
                    '/5' => 'Confirm Employee',
                    '/6' => 'Extend Probation Period',
                    '/7' => 'Change Employee Number',
                    '/8' => 'Exclude From Payroll',
                    '/9' => 'Delete Employee',
                    '/10' => 'Upload Employee Document',
                    '/11' => 'Add Bulletin Board',
                    '/12' => 'Mass Employee Email',
                    '/14' => 'Employee onboarding',
                    '/15' => 'Invite Employees(Email Employee Password)',
                    '/16' => 'Employee Filter',
                    '/17' => 'Bulk Photo Upload',
                    '/18' => 'PF/ESI Details',
                    '/19' => 'Add Nomination Details',
                    '/21' => 'Bulk Data Upload',
                    '/22' => 'Add Employee',
                    '/23' => 'Upload Forms/Policies',
                    '/24' => 'People Analytical Hub',
                    '/25' => 'Organization Chart',
                    '/26' => 'Assign Manager',
                ],
                'payroll' => [
                    '/hr/user/payroll/update' => 'Stop Salary Processing',
                    '/user/payroll/process' => 'Deduct Loss Of Pay(LOP)',
                    '/27' => 'Print/Email Payslips',
                    '/28' => 'Settle Resigned Employee',
                    '/29' => 'Print/Email Reimbursement Payslip',
                    '/30' => 'Arrears',
                    '/31' => 'Update Employee PAN Number',
                    '/32' => 'Revise Employee Salary',
                    '/33' => 'Process Payroll',
                    '/34' => 'Release IT Declaration',
                    '/35' => 'Download IT Declaration For TDS',
                    '/36' => 'Create New Payroll Month',
                    '/37' => 'Update Payroll Data',
                    '/38' => 'Pay Arrears',
                    '/39' => 'Verify Payroll Difference',
                    '/40' => 'Generate Payroll Statement',
                    '/41' => 'Generate Accounts JV',
                    '/42' => 'Release Payslips To Employees',
                    '/43' => 'Clean Up Payroll',
                    '/44' => 'Hold Salary Account',
                    '/45' => 'Release Salary Account',
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
                    '/user/leave/approve' => 'Add Holidays',
                    '/55' => 'Post Leave Transction',
                    '/56' => 'Grant Leave',
                    '/57' => 'Year End Process',
                    '/58' => 'Download Leave Card',
                    '/59' => 'Approve Leave',
                    '/60' => 'Approve Leave Cancellation',
                    '/61' => 'Approve RH',
                    '/62' => 'Leave Calendar',
                    '/63' => 'Approve Comp Off',
                    '/64' => 'Update Employee Weekdays'
                ],
                'attendance' => [
                    '/hr/user/holidayList' => 'Verify Employee Swipes',
                    '/65' => 'Attendance Muster',
                    '/66' => 'Manual Override',
                    '/67' => 'Shift Roster',
                    '/68' => 'Shift Override',
                    '/69' => 'Attendance Exception',
                    '/70' => 'Attendance Period Finalization',
                    '/71' => 'Update Sign In IP Address',
                    '/72' => 'Who is in ?',
                    '/73' => 'View Employee Attendance',
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


    public function render()
    {
        return view('livewire.admin-dashboard', [
            'departmentCount' => $this->departmentCount,
            'loginEmployee' => $this->loginEmployee
        ]);
    }
}

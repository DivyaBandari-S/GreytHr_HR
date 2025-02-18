<?php

namespace App\Livewire;

use App\Helpers\FlashMessageHelper;
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


    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }


    public function mount()
    {

        try {
            $this->getContainerData();
            $this->setActiveTab($this->activeTab);
            $employeeId = auth()->guard('hr')->user()->emp_id;
            // dd( $employeeId);
            // $this->loginEmployee = Hr::where('emp_id', $employeeId)->select('emp_id', 'employee_name')->first();
            $companyId = EmployeeDetails::where('emp_id', $employeeId)->value('company_id');
            //Hr Requests

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

    public function getContainerData()
    {
        try {
            // Define the overview content list
            $this->overviewContentList = [
                'employee' => [
                    '/update-employee-details' => 'Update Employee Data',
                    '/add-employee-details/{employee?}' => 'Add Employee Data',
                ],
                'payroll' => [
                    '/hr/user/payroll/update' => 'Update Payroll Data',
                    '/user/payroll/process' => 'Process Payroll',
                ],
                'leave' => [
                    '/user/leave/approve' => 'Approve Leave',
                ],
                'attendance' => [
                    '/hr/user/holidayList' => 'Add Holidays',
                ],
                'other' => [
                    '/a' => 'Update Employee Data',
                    '/b' => 'Add Employee Data',
                    '/c' => 'Add Holidays',
                    '/d' => 'Update Payroll Data',
                    '/e' => 'Process Payroll',
                    '/f' => 'Approve Leave',
                ]
            ];
    
            // Get the search term from the user input (if any)
            $searchContent = $this->searchContent ?? '';
    
            // Prepare an array with dynamic classes based on the counter
            $overviewItems = [];
            $counter = 0;
    
            // Loop through each category in overviewContentList
            foreach ($this->overviewContentList as $category => $contentList) {
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
                        $overviewItems[] = [
                            'route' => $route,
                            'content' => $content,
                            'bgClass' => $bgClass,
                            'iconClass' => $iconClass,
                            'category' => ucfirst($category)  // Store the category name for reference
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



    public function open()
    {
        $this->show = true;
    }
    public function render()
    {
        return view('livewire.admin-dashboard', [
            'departmentCount' => $this->departmentCount,
        ]);
    }
}

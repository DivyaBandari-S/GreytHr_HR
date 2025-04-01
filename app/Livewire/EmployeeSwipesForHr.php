<?php

namespace App\Livewire;

use App\Models\EmpDepartment;
use App\Models\EmployeeDetails;
use App\Models\SwipeRecord;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jenssegers\Agent\Agent as AgentAgent;
use Jenssegers\Agent\Facades\Agent;
use Livewire\Component;
use Spatie\SimpleExcel\SimpleExcelWriter;

class EmployeeSwipesForHr extends Component
{
    public $employees;
    public $startDate;

    public $todaySwipeRecords;
    public $doorSwipeTime;
    public $accessCardDetails;
    public $endDate;









    public $selectedWebEmployeeId;
    public $deviceId;

    public $selectedDepartment;
    public $selectedEmployeeId;
    
    public $webSwipeDirection;
    public $swipeTime;
    public $employeeId;
    public $isPending=0;

    public $isOpen=false;
    public $webDeviceId;
    public $webDeviceName;
    public $isApply=1;

    public $selectedSwipeStatus;
    public $selectedLocation;
    public $isupdateFilter=0;

  
    public $signInDevice;
    public $doorSignIn;
    
    public $selectedDesignation;
    public $selectedCategory;
    public $selectedShift;
    public $employeeShiftDetails;
    public $selectedSwipeTime;

    public $defaultApply=1;
    public $search = '';
    public $searching = 0;
    public $selectedSwipeLogTime = [];
    public $swipeLogTime = null;
    public $status;

    

    public function mount()
    {
        try {
            $today = now()->startOfDay();
            $authUser = Auth::user();
            $userId = $authUser->emp_id;

            $this->startDate=now()->toDateString();
            $managedEmployees = EmployeeDetails::where('manager_id', $userId)
                ->where('employee_status', 'active')
                ->join('company_shifts', function ($join) {
                    $join->on('employee_details.shift_type', '=', 'company_shifts.shift_name')
                        ->whereRaw('JSON_CONTAINS(employee_details.company_id, JSON_QUOTE(company_shifts.company_id))');
                })
                ->select(
                    'employee_details.*',
                    'company_shifts.shift_start_time',
                    'company_shifts.shift_end_time'
                )
                ->get();

            // Check if the user swiped today
            $userSwipesToday = SwipeRecord::where('emp_id', $authUser->emp_id)
                ->where('created_at', '>=', $today)
                ->where('created_at', '<', $today->copy()->endOfDay())
                ->exists();

            $agent = new AgentAgent();
            $this->status = $userSwipesToday ? ($agent->isMobile() ? 'Mobile' : ($agent->isDesktop() ? 'Desktop' : '-')) : '-';
        } catch (\Exception $e) {
            Log::error('Error in mount method: ' . $e->getMessage());
            $this->status = 'Error';
        }
    }



    public function updateselectedCategory()
    {
        $this->selectedCategory=$this->selectedCategory;
    }

    public function resetSidebar()
    {
        if($this->isApply==1&&$this->defaultApply==1)
        {
            $this->selectedSwipeStatus='All';
            $this->selectedDepartment='All';
            $this->selectedDesignation='All';
            $this->selectedLocation='Hyderabad';
            $this->todaySwipeRecords=null;
            
        }
        elseif($this->isPending==1&&$this->defaultApply==0)
        {
            $this->selectedSwipeStatus='door';
            $this->selectedDepartment='All';
            $this->selectedDesignation='All';
            $this->selectedLocation='Hyderabad';
            $this->todaySwipeRecords=null;
        }
    }
    public function updateselectedDepartment()
    {
        $this->selectedDepartment=$this->selectedDepartment;
   
    }
    public function toggleSidebar()
    {
        $this->isOpen = !$this->isOpen; // Toggle sidebar visibility
    }

    public function applyFilter()
    {
        $this->isupdateFilter = 1;
        $this->isOpen = false;
    }
    public function updateselectedDesignation()
    {
        $this->selectedDesignation=$this->selectedDesignation;
        
       
    }
    public function updateselectedLocation()
    {
        $this->selectedLocation=$this->selectedLocation;
    }
    public function closeSidebar()
    {
        $this->isOpen = false; // Ensure sidebar closes when called
    }
    public function viewDoorSwipeButton()
    {
        Log::info('Welcome to viewDoorSwipeButton method');
        $this->isApply = 1;
        $this->isPending = 0;
        $this->defaultApply = 1;
    
        $this->swipeTime = null;
        $this->webSwipeDirection=null;
        $this->webDeviceName=null;
        $this->webDeviceId=null;
       
        $today = now()->toDateString();
        $authUser = Auth::user();
        $userId = $authUser->emp_id;


    if($this->isupdateFilter==1)
    {

        $departmentId = null;
        if ($this->selectedDepartment === 'information_technology') {
            $departmentId = EmpDepartment::where('department', 'Information Technology')->value('dept_id');
        }
        elseif ($this->selectedDepartment === 'business_development') {
            $departmentId = EmpDepartment::where('department', 'Business Development')->value('dept_id');
        }
        elseif ($this->selectedDepartment === 'operations') {
            $departmentId = EmpDepartment::where('department', 'Operations')->value('dept_id');
        }
        elseif ($this->selectedDepartment === 'innovation') {
            $departmentId = EmpDepartment::where('department', 'Innovation')->value('dept_id');
        }
        elseif ($this->selectedDepartment === 'infrastructure') {
            $departmentId = EmpDepartment::where('department', 'Infrastructure')->value('dept_id');
        }
        elseif ($this->selectedDepartment === 'human_resources') {
            $departmentId = EmpDepartment::where('department', 'Human Resource')->value('dept_id');
        }
        $managedEmployees = EmployeeDetails::join('company_shifts', function ($join) {
            $join->on('employee_details.shift_type', '=', 'company_shifts.shift_name')
                ->whereRaw('JSON_CONTAINS(employee_details.company_id, JSON_QUOTE(company_shifts.company_id))');
        })
        
        ->when($this->selectedDesignation, function ($query) {
            if ($this->selectedDesignation == 'software_engineer') {
                return $query->whereIn('employee_details.job_role', ['Software Engineer I', 'Software Engineer II']);
            } elseif ($this->selectedDesignation == 'senior_software_engineer'){
                return $query->where('employee_details.job_role', 'Software Engineer III');
            }
            elseif ($this->selectedDesignation == 'team_lead'){
                return $query->where('employee_details.job_role', 'LIKE','%Team Lead%');
            }
            elseif ($this->selectedDesignation == 'sales_head'){
                return $query->where('employee_details.job_role', 'LIKE','%Sales Head%');
            }
        })
        ->when($this->selectedLocation, function ($query) {
            if ($this->selectedLocation === 'Remote') {
                return $query->where('employee_details.job_mode', $this->selectedLocation);
            } else {
                return $query->where('employee_details.job_location', $this->selectedLocation)
                             ->where('employee_details.job_mode', '!=', 'Remote');
            }
        })
        ->when($departmentId, function ($query) use ($departmentId) {
            return $query->where('employee_details.dept_id', $departmentId);
        })
        ->select(
            'employee_details.first_name',
            'employee_details.emp_id',
            'employee_details.last_name',
            'company_shifts.shift_start_time',
            'company_shifts.shift_end_time'
        )
        ->get();
       
    }
      
    else
    {

        $managedEmployees = EmployeeDetails::join('company_shifts', function ($join) {
            $join->on('employee_details.shift_type', '=', 'company_shifts.shift_name')
                ->whereRaw('JSON_CONTAINS(employee_details.company_id, JSON_QUOTE(company_shifts.company_id))');
        })
        ->select(
            'employee_details.first_name',
            'employee_details.emp_id',
            'employee_details.last_name',
            'company_shifts.shift_start_time',
            'company_shifts.shift_end_time'
        )
        ->get();
    
    }  
            
        
            
        

        $this->employees = $this->processSwipeLogs($managedEmployees, $this->startDate);

        Log::info('isApply: ' . $this->isApply);
        Log::info('isPending: ' . $this->isPending);
        Log::info('defaultApply: ' . $this->defaultApply);

        // Debugging: Log the output of processWebSignInLogs
        Log::info('Employees: ', ['employees' => $this->employees]);
        

        
        
       

    }

    public function updateselectedSwipeStatus()
    {
       $this->selectedSwipeStatus= $this->selectedSwipeStatus;  
       

    }
    public function processWebSignInLogs()
    {

        $today = $this->startDate;
        $authUser = Auth::user();
        $userId = $authUser->emp_id;
        $webSignInData = [];
        // Check if the user is a manager
        $isManager = EmployeeDetails::where('manager_id', $userId)->exists();

          
        if($this->isupdateFilter==1)
        {
    
            $departmentId = null;
            if ($this->selectedDepartment === 'information_technology') {
                $departmentId = EmpDepartment::where('department', 'Information Technology')->value('dept_id');
            }
            elseif ($this->selectedDepartment === 'business_development') {
                $departmentId = EmpDepartment::where('department', 'Business Development')->value('dept_id');
            }
            elseif ($this->selectedDepartment === 'operations') {
                $departmentId = EmpDepartment::where('department', 'Operations')->value('dept_id');
            }
            elseif ($this->selectedDepartment === 'innovation') {
                $departmentId = EmpDepartment::where('department', 'Innovation')->value('dept_id');
            }
            elseif ($this->selectedDepartment === 'infrastructure') {
                $departmentId = EmpDepartment::where('department', 'Infrastructure')->value('dept_id');
            }
            elseif ($this->selectedDepartment === 'human_resources') {
                $departmentId = EmpDepartment::where('department', 'Human Resource')->value('dept_id');
            }
            $managedEmployees = EmployeeDetails::join('company_shifts', function ($join) {
                $join->on('employee_details.shift_type', '=', 'company_shifts.shift_name')
                    ->whereRaw('JSON_CONTAINS(employee_details.company_id, JSON_QUOTE(company_shifts.company_id))');
            })
            ->join('swipe_records', 'employee_details.emp_id', '=', 'swipe_records.emp_id') // Join with swipe_records
            ->when($this->selectedDesignation, function ($query) {
                return match ($this->selectedDesignation) {
                    'software_engineer' => $query->whereIn('employee_details.job_role', ['Software Engineer I', 'Software Engineer II']),
                    'senior_software_engineer' => $query->where('employee_details.job_role', 'Software Engineer III'),
                    'team_lead' => $query->where('employee_details.job_role', 'LIKE', '%Team Lead%'),
                    'sales_head' => $query->where('employee_details.job_role', 'LIKE', '%Sales Head%'),
                    default => $query,
                };
            })
            ->when($this->selectedLocation, function ($query) {
                return $this->selectedLocation === 'Remote'
                    ? $query->where('employee_details.job_mode', 'Remote')
                    : $query->where('employee_details.job_location', $this->selectedLocation)
                            ->where('employee_details.job_mode', '!=', 'Remote');
            })
            ->when($departmentId, function ($query) use ($departmentId) {
                return $query->where('employee_details.dept_id', $departmentId);
            })
            ->select(
                'employee_details.first_name',
                'employee_details.emp_id',
                'employee_details.last_name',
                'company_shifts.shift_start_time',
                'company_shifts.shift_end_time',
                
            )
        
           // Ensure unique employees if multiple swipe records exist
            ->get();
        
            if($this->selectedSwipeStatus=='mobile_sign_in'&&$this->isupdateFilter==1)
            {

                $this->todaySwipeRecords = SwipeRecord::whereDate('created_at', $today)
                        ->whereIn('emp_id', $managedEmployees->pluck('emp_id'))
                        ->where('sign_in_device','Mobile')
                        ->get();
            }
            elseif($this->selectedSwipeStatus=='web_sign_in'&&$this->isupdateFilter==1)
            {

                $this->todaySwipeRecords = SwipeRecord::whereDate('created_at', $today)
                        ->whereIn('emp_id', $managedEmployees->pluck('emp_id'))
                        ->where('sign_in_device','Laptop/Desktop')
                        ->get();
            }
            elseif($this->selectedSwipeStatus=='All'&&$this->isupdateFilter==1)
            {

                $this->todaySwipeRecords = SwipeRecord::whereDate('created_at', $today)
                        ->whereIn('emp_id', $managedEmployees->pluck('emp_id'))
                        ->get();
            }
                
           Log::info('Web Sign In Logs are:',$managedEmployees->toArray());

        }
          
        else
        {
    
            $managedEmployees = EmployeeDetails::join('company_shifts', function ($join) {
                $join->on('employee_details.shift_type', '=', 'company_shifts.shift_name')
                    ->whereRaw('JSON_CONTAINS(employee_details.company_id, JSON_QUOTE(company_shifts.company_id))');
            })
            ->select(
                'employee_details.first_name',
                'employee_details.emp_id',
                'employee_details.last_name',
                'company_shifts.shift_start_time',
                'company_shifts.shift_end_time'
            )
            ->get();
        
            $this->todaySwipeRecords = SwipeRecord::whereDate('created_at', $today)
                    ->whereIn('emp_id', $managedEmployees->pluck('emp_id'))
                    ->get();
        }  
        
            

                // Fetch today's swipe records
                

            
                // Prepare Web Sign-in Data
                foreach ($managedEmployees as $employee) {
                    $normalizedEmployeeId = $employee->emp_id;
                    $employeeSwipeLog = $this->todaySwipeRecords->where('emp_id', $normalizedEmployeeId);

                    if ($employeeSwipeLog->isNotEmpty()) {
                        $webSignInData[] = [
                            'employee' => $employee,
                            'swipe_log' => $employeeSwipeLog,
                        ];
                    }
                }

                // **Apply Search Filter**
                if (!empty(trim($this->search))) {
                    $webSignInData = array_filter($webSignInData, function ($data) {
                        return stripos($data['employee']->first_name, $this->search) !== false ||
                                stripos($data['employee']->last_name, $this->search) !== false ||
                                stripos($data['employee']->emp_id, $this->search) !== false;
                    });
                }

                return array_values($webSignInData);


    }

    public function viewWebsignInButton()
    {
        Log::info('Welcome to viewWebsignInButton method');
        $this->isApply = 0;
        $this->isPending = 1;
        $this->defaultApply = 0;
        $this->accessCardDetails=null;
        $this->deviceId=null;
        $this->employees = $this->processWebSignInLogs();
        Log::info('isApply: ' . $this->isApply);
        Log::info('isPending: ' . $this->isPending);
        Log::info('defaultApply: ' . $this->defaultApply);

        // Debugging: Log the output of processWebSignInLogs
        Log::info('Employees: ', ['employees' => $this->employees]);

        
    }

    public function handleEmployeeSelection()
    {
        $parts = explode('-', $this->selectedEmployeeId);
       
        $this->doorSwipeTime=$parts[3];
        $this->selectedEmployeeId = $parts[0].'-'.$parts[1];
        $this->doorSignIn=$parts[4];
        $currentDate = Carbon::today();
        $month = $currentDate->format('n');
        $year = $currentDate->format('Y');
        $normalizedId=str_replace('-', '', $this->selectedEmployeeId);
        // Construct the table name for SQL Server
        $tableName = 'DeviceLogs_' . $month . '_' . $year;
        $this->accessCardDetails = DB::connection('sqlsrv')
                    ->table(table: $tableName)
                    ->where('UserId', $normalizedId)
                  
                    ->value('UserId');
        $this->deviceId=  DB::connection('sqlsrv')
        ->table(table: $tableName)
        ->where('UserId', $normalizedId)
      
        ->value('DeviceId');           
       
    }
    public function updateDate()
    {

        $this->startDate = $this->startDate;
    }

    public function handleEmployeeWebSelection()
    {
      
        // $this->selectedWebEmployeeId=$this->selectedWebEmployeeId;
        $parts = explode('-', $this->selectedWebEmployeeId);
      
       
        $this->selectedWebEmployeeId = $parts[0].'-'.$parts[1];
        $this->swipeTime = $parts[3];
        $this->webSwipeDirection=$parts[4];
        $this->signInDevice=$parts[5];
        $this->webDeviceName=SwipeRecord::where('emp_id',$this->selectedWebEmployeeId)->where('in_or_out',$this->webSwipeDirection)->where('swipe_time',$this->swipeTime)->whereDate('created_at',$this->startDate)->value('device_name');
        $this->webDeviceId=SwipeRecord::where('emp_id',$this->selectedWebEmployeeId)->where('in_or_out',$this->webSwipeDirection)->where('swipe_time',$this->swipeTime)->whereDate('created_at',$this->startDate)->value('device_id');
       
        // Construct the table name for SQL Server
      
        
        // $this->webDeviceId=  SwipeRecord::where('')         
        
    }
    public function downloadFileforSwipes()
    {
        try {
            $today = now()->toDateString();
            $authUser = Auth::user();
            $userId = $authUser->emp_id;

            $managedEmployees = EmployeeDetails::where('employee_status', 'active')
                ->get();

            $swipeData = [];


            if ($this->isApply == 1 && $this->isPending == 0 && $this->defaultApply == 1) {
                $title = 'For Door Swipe Data';
                $headerColumns = ['Employee ID', 'Employee Name', 'Swipe Date', 'Swipe Time', 'Direction'];
                $employeesInExcel = $this->processSwipeLogs($managedEmployees, $this->startDate);
                foreach ($employeesInExcel as $employee) {
                    foreach($employee['swipe_log'] as $log)
                    {
                            $swipeData[] = [
                                            $employee['employee']->emp_id,
                                            ucwords(strtolower($employee['employee']->first_name)).' '.ucwords(strtolower($employee['employee']->last_name)),
                                            Carbon::parse($log->logDate)->format('jS F Y') ,
                                            Carbon::parse($log->logDate)->format('H:i:s'),
                                            strtoupper($log->Direction),
                                        ];
                    }
                    
                    
                }
                
            } elseif ($this->isApply == 0 && $this->isPending == 1 && $this->defaultApply == 0) {
                $title = 'Web Sign In Data';
                $headerColumns = ['Employee ID', 'Employee Name', 'Swipe Date', 'Swipe Time', 'Web Sign In Data'];
                $employeesInExcel = $this->processWebSignInLogs();
                foreach ($employeesInExcel as $employee) {
                    foreach($employee['swipe_log'] as $log)
                    {
                            $swipeData[] = [
                                            $employee['employee']->emp_id,
                                            ucwords(strtolower($employee['employee']->first_name)).' '.ucwords(strtolower($employee['employee']->last_name)),
                                            Carbon::parse($log->created_at)->format('jS F Y') ,
                                            Carbon::parse($log->swipe_time)->format('H:i:s'),
                                            strtoupper($log->in_or_out),
                                        ];
                    }
                    
                    
                }
                
            } 
            

           
            $filePath = storage_path('app/todays_present_employees.xlsx');

            SimpleExcelWriter::create($filePath)
                ->addRow([$title])
                ->addRow($headerColumns)
                ->addRows($swipeData)
                ->close();

            return response()->download($filePath, 'todays_present_employees.xlsx');
        } catch (\Exception $e) {
            Log::error('Error downloading file for swipes: ' . $e->getMessage());
            session()->flash('error', 'An error occurred while downloading the file for swipes. Please try again.');
            return redirect()->back();
        }
    }

    public function updatedSelectedShift($value)
    {
        // This method will be called whenever the selected radio button changes
        // $value will contain the value of the selected shift ('GS', 'AS', or 'ES')
        Log::info('Selected Shift: ' . $value);

        // You can handle the selected value here

        $this->selectedShift = $value;
        // $this->formattedSelectedShift='General Shift';


    }
    public function searchEmployee()
    {
        $this->searching = 1;
    }


    public function processSwipeLogs($managedEmployees, $today)
    {
        $filteredData  = [];
        $today = $this->startDate;
        $normalizedIds = $managedEmployees->pluck('emp_id')->map(function ($id) {
            return str_replace('-', '', $id);
        });
        $currentDate = Carbon::today();
        $month = $currentDate->format('n');
        $year = $currentDate->format('Y');
        $tableName = 'DeviceLogs_' . $month . '_' . $year;
        
        try {
          
              
                // ✅ Local: Use Laravel SQLSRV connection
                
                if (DB::connection('sqlsrv')->getSchemaBuilder()->hasTable($tableName)) {
                    $externalSwipeLogs = DB::connection('sqlsrv')
                        ->table($tableName)
                        ->select('UserId', 'logDate',DB::raw("CONVERT(VARCHAR(8), logDate, 108) AS logTime"), 'Direction')
                        ->whereIn('UserId', $normalizedIds)
                        ->whereRaw("CONVERT(DATE, logDate) = ?", $today)
                        ->orderBy('logTime')
                        ->get();
                    

                } else {
                    $externalSwipeLogs = collect();
                }
           
        } catch (\Exception $e) {
            // ✅ Log the error for debugging
            Log::error("Swipe Logs Error: " . $e->getMessage());
            $externalSwipeLogs = collect();
        }

        foreach ($managedEmployees as $employee) {
            $normalizedEmployeeId = str_replace('-', '', $employee->emp_id);
            $employeeSwipeLog = $externalSwipeLogs->where('UserId', $normalizedEmployeeId);

            if ($employeeSwipeLog->isNotEmpty()) {
                $filteredData[] = [
                    'employee' => $employee,
                    'swipe_log' => $employeeSwipeLog,
                ];
            }
        }

        // ✅ Search Filtering
        if (!empty(trim($this->search))) {
            $filteredData = array_filter($filteredData, function ($data) {
                return stripos($data['employee']->first_name, $this->search) !== false ||
                    stripos($data['employee']->last_name, $this->search) !== false ||
                    stripos($data['employee']->emp_id, $this->search) !== false;
            });
        }
      
        return array_values($filteredData);
    }



    public function render()
    {
        if($this->isApply==1&&$this->isPending==0&&$this->defaultApply==1)
        {

            $this->viewDoorSwipeButton();
            $this->webSwipeDirection=null;
            $this->swipeTime=null;
            

            
        }
        elseif($this->isApply==0&&$this->isPending==1&&$this->defaultApply==0)
        {
            $this->viewWebsignInButton();
            $this->doorSignIn=null;
            $this->doorSwipeTime=null;
            $this->accessCardDetails=null;
            $this->deviceId=null;
        }

        return view('livewire.employee-swipes-for-hr', [
            'SignedInEmployees' => $this->employees,

        ]);
    }
}

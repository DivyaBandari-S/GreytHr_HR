<?php

namespace App\Livewire;

use App\Models\EmployeeDetails;
use App\Models\Hr;
use App\Models\LeaveRequest;
use App\Models\RegularisationDates;
use App\Models\SwipeRecord;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Spatie\SimpleExcel\SimpleExcelWriter;

class HrAttendanceOverviewNew extends Component
{
    public $totalemployees;
    
    public $chartDate;

    public $openApprovePopupModal=false;

    public $openRejectPopupModal=false;
    public $openAccordionForActive=null;
    public $absentemployeescount;

    public $selectedYear;
    public $lateemployeescount;

    public $mobileEmployeeCount;

    public $laptopEmployeeCount;
    public  $earlyemployeescount;
    public $showHelp=false;
    public $totalemployeesinexcel;

    public $openAccordionsForAbsentees = [];
    public $previousYear;
    public $regularisations;

    public $nextYear;
    public $currentYear;

    public $isdateSelected=0;
    public $monthNumber;
    public $currentMonth;
    public $monthNumbers;

    public $today;

    public $yesterday;

    public $selectedDateForDropdown;
    public $dayBeforeYesterday;
    public function mount()
    {
        $currentDate = now()->toDateString();
        $currentDate = Carbon::today();
        $today = $currentDate->toDateString(); // Get today's date in 'Y-m-d' format
        $month = $currentDate->format('n');
        $year = $currentDate->format('Y');
        $this->monthNumber=Carbon::now()->format('M');
        $this->currentYear = Carbon::now()->year;
        $this->previousYear = $this->currentYear - 1;
        $this->nextYear = $this->currentYear + 1;
        $this->today = Carbon::today()->format('Y-m-d');
                                    $this->yesterday = Carbon::yesterday()->format('Y-m-d');
                                    $this->dayBeforeYesterday = Carbon::yesterday()->subDay()->format('Y-m-d');
        $this->monthNumbers = [
            1 => 'Jan',
            2 => 'Feb',
            3 => 'Mar',
            4 => 'Apr',
            5 => 'May',
            6 => 'Jun',
            7 => 'Jul',
            8 => 'Aug',
            9 => 'Sep',
            10 => 'Oct',
            11 => 'Nov',
            12 => 'Dec',
        ];
        $this->yesterday = Carbon::yesterday()->format('Y-m-d');
        $this->dayBeforeYesterday = Carbon::yesterday()->subDay()->format('Y-m-d');
        $this->selectedYear=now()->year;
        $this->selectedDateForDropdown=$today;
        // Construct the table name for SQL Server
      
    }

   public function updateSelectedDateForDropdown()
   {
     $this->isdateSelected=1;  
     $this->selectedDateForDropdown=$this->selectedDateForDropdown;
   
   }

    public function updateSelectedYear()
    {
        $this->selectedYear=$this->selectedYear;
    }
    public function updatemonthNumber()
    {
        $this->monthNumber=$this->monthNumber;
    
    }
    public function hideHelp()
    {
   
         $this->showHelp=true;
    }

    public function showhelp()
    {
        $this->showHelp=false;

    }
    public function downloadexcelForNotAssigned()
    {
        $userId = Auth::id();
       
        $companyName = Hr::
    where('hr_emp_id', $userId)
    ->get();
         
      
        $this->totalemployeesinexcel = EmployeeDetails::select('emp_id', 'first_name', 'last_name')->get();
    
        $data[] = ['Not Assigned Employees'];
        $data[] = ['Employee ID', 'First Name', 'Last Name'];
        foreach ($this->totalemployeesinexcel as $employee) {
            $data[] = [$employee->emp_id, $employee->first_name, $employee->last_name];
        }
    
        $filePath = storage_path('app/employees.xlsx');
    
        SimpleExcelWriter::create($filePath)->addRows($data);
    
        return response()->download($filePath, 'notassignedemployees.xlsx');
    }
    public function downloadexcelForAttendanceType()
    {
        $userId = Auth::id();
       
        $companyName = Hr::
        where('hr_emp_id', $userId)
        ->get();
        $employees = EmployeeDetails::join('swipe_records', 'employee_details.emp_id', '=', 'swipe_records.emp_id')
        ->select('employee_details.emp_id', 'employee_details.first_name', 'employee_details.last_name', 'employee_details.job_role','employee_details.job_location','swipe_records.sign_in_device', 'swipe_records.in_or_out', 'swipe_records.swipe_time')
        ->where('swipe_records.in_or_out', 'IN')
        ->whereDate('swipe_records.created_at', now()->toDateString()) // Filter for today's date
        ->orderBy('employee_details.first_name')
        ->get();
    
    // Now $employees contains the first swipe record for each employee where in_or_out is 'IN'
    
     
        $data[] = ['Employees By Attendance Type'];
        $data[] = ['Employee No', 'Employee Name', 'Sign Date & Time','Designation','Location','Attendance Type'];
        foreach ($employees as $employee) {
           
            if ($employee->sign_in_device === 'Mobile') {
                $deviceLabel = 'Mobile Sign In';
            } elseif ($employee->sign_in_device === 'Desktop/Laptop' ) {
                $deviceLabel = 'Web Sign In';
            } else {
                $deviceLabel = 'Unknown Device';
            }
            $data[] = [$employee->emp_id, ucwords(strtolower($employee->first_name)).' '.ucwords(strtolower($employee->last_name)),$employee->swipe_time,$employee->job_role,$employee->job_location,$deviceLabel];
        }
        $filePath = storage_path('app/employees.xlsx');
        SimpleExcelWriter::create($filePath)->addRows($data);
    
        return response()->download($filePath, 'employeesattendanceType.xlsx');
    }
    public function toggleActiveAccordion($id)
    {
        
        if ($this->openAccordionForActive === $id) {
            $this->openAccordionForActive = null; // Close if already open
        } else {
            $this->openAccordionForActive = $id; // Set to open
        }
    }
  
    public function render()
    {
       
        // $companyName = $yourModels->com->company_name;
        $hremployeeId = auth()->guard('hr')->user()->hr_emp_id;
        $employeeId=Hr::where('hr_emp_id',$hremployeeId)->value('emp_id');
        $companyIdJson = EmployeeDetails::where('emp_id', $employeeId)->value('company_id');
        if (is_array($companyIdJson)) {
            $companyIds = $companyIdJson; // It's already an array
        } elseif (is_string($companyIdJson)) {
            $companyIds = json_decode($companyIdJson, true); // Decode the JSON string
        } else {
            $companyIds = []; // Default to an empty array if it's neither
        }
        $empIds = EmployeeDetails::where(function($query) use ($companyIds) {
            foreach ($companyIds as $companyId) {
                // Use JSON_CONTAINS to check if company_id field contains the companyId
                $query->orWhereRaw('JSON_CONTAINS(company_id, \'' . json_encode($companyId) . '\')');
            }
        })->pluck('emp_id');
       
        $this->regularisations = RegularisationDates::where('is_withdraw', 0) // Only records with is_withdraw set to 0
        ->where('status', 5)
        ->whereIn('emp_id', $empIds) // Filter by emp_id
        ->selectRaw('*, JSON_LENGTH(regularisation_entries) AS regularisation_entries_count')
        ->whereRaw('JSON_LENGTH(regularisation_entries) > 0')
        ->whereRaw('DATEDIFF(CURRENT_DATE, updated_at) > 3')  // Check if the difference from today is greater than 3 days
        ->with('employee')
        ->get();
        
        $companyId =auth()->guard('hr')->user()->emp_id;
        $employeeCompanyId=EmployeeDetails::where('emp_id',$companyId)->value('company_id');
       
        $currentDate1=now()->toDateString();
        if($this->isdateSelected==1)
        {
            $currentDate=$this->selectedDateForDropdown;
        }
        else
        {
            $currentDate = Carbon::now()->format('Y-m-d');    
        }
        
        $employees = EmployeeDetails::select('emp_id', 'first_name', 'last_name','shift_type')->get();

        $approvedLeaveRequests = LeaveRequest::join('employee_details', 'leave_applications.emp_id', '=', 'employee_details.emp_id')
        ->where('leave_applications.leave_status', 2)
        ->whereIn('leave_applications.emp_id', $employees->pluck('emp_id'))
        ->whereDate('from_date', '<=', $currentDate)
        ->whereDate('to_date', '>=', $currentDate)
        ->get(['leave_applications.*', 'employee_details.first_name', 'employee_details.last_name'])
        ->map(function ($leaveRequest) {
            $fromDate = Carbon::parse($leaveRequest->from_date);
            $toDate = Carbon::parse($leaveRequest->to_date);
            $leaveRequest->number_of_days = $fromDate->diffInDays($toDate) + 1;
            return $leaveRequest;
        });
        if($this->isdateSelected==1)
        {
            Log::info('Executing query for isdateSelected == 1');

            $this->absentemployeescount =  EmployeeDetails::leftJoin('emp_personal_infos', 'employee_details.emp_id', '=', 'emp_personal_infos.emp_id')
        ->leftJoin('company_shifts', function ($join) {
            // Join with company_shifts on company_id and shift_type matching shift_name
            $join->on('company_shifts.company_id', '=', DB::raw("JSON_UNQUOTE(JSON_EXTRACT(employee_details.company_id, '$[0]'))"))
                ->on('company_shifts.shift_name', '=', 'employee_details.shift_type');
        })
        ->select(
            'employee_details.*',
           
            'company_shifts.shift_start_time',
            'company_shifts.shift_end_time',
            'company_shifts.shift_name'
        )
        ->whereNotIn('employee_details.emp_id', function ($query) use ($currentDate) {
            $query->select('emp_id')
                ->from('swipe_records')
                ->whereDate('created_at', $currentDate);
        })
        ->whereNotIn('employee_details.emp_id', $approvedLeaveRequests->pluck('emp_id'))
        ->where('employee_details.employee_status', 'active')
        ->orderBy('employee_details.first_name')
        ->distinct('employee_details.emp_id')
        ->count();
        Log::info('Absent employees count for selected date:', ['count' => $this->absentemployeescount]);
        }

        else
        {
            Log::info('Executing query for isdateSelected != 1');

            $this->absentemployeescount =  EmployeeDetails::leftJoin('emp_personal_infos', 'employee_details.emp_id', '=', 'emp_personal_infos.emp_id')
        ->leftJoin('company_shifts', function ($join) {
            // Join with company_shifts on company_id and shift_type matching shift_name
            $join->on('company_shifts.company_id', '=', DB::raw("JSON_UNQUOTE(JSON_EXTRACT(employee_details.company_id, '$[0]'))"))
                ->on('company_shifts.shift_name', '=', 'employee_details.shift_type');
        })
        ->select(
            'employee_details.*',
           
            'company_shifts.shift_start_time',
            'company_shifts.shift_end_time',
            'company_shifts.shift_name'
        )
        ->whereNotIn('employee_details.emp_id', function ($query) use ($currentDate) {
            $query->select('emp_id')
                ->from('swipe_records')
                ->whereDate('created_at', $currentDate);
        })
        ->whereNotIn('employee_details.emp_id', $approvedLeaveRequests->pluck('emp_id'))
        ->where('employee_details.employee_status', 'active')
        ->orderBy('employee_details.first_name')
        ->distinct('employee_details.emp_id')
        ->count();
        Log::info('Absent employees count for non-selected date:', ['count' => $this->absentemployeescount]);
        }

        Log::info('Final absent employees count:', ['absentemployeescount' => $this->absentemployeescount]);

        
    
           
        
        $this->lateemployeescount = SwipeRecord::whereIn('swipe_records.id', function ($query) use ($employees, $approvedLeaveRequests, $currentDate) {
            $query->selectRaw('MIN(swipe_records.id)')
                ->from('swipe_records')
                ->whereNotIn('swipe_records.emp_id', $approvedLeaveRequests->pluck('emp_id'))
                ->whereIn('swipe_records.emp_id', $employees->pluck('emp_id'))
                ->whereDate('swipe_records.created_at', $currentDate)
                ->groupBy('swipe_records.emp_id');
        })
        ->join('employee_details', 'swipe_records.emp_id', '=', 'employee_details.emp_id')
        ->leftJoin('emp_personal_infos', 'swipe_records.emp_id', '=', 'emp_personal_infos.emp_id')  // Join with emp_personal_infos
        ->leftJoin('company_shifts', function ($join) {
            // Join with company_shifts using JSON_UNQUOTE to extract company_id from employee_details
            $join->on('company_shifts.company_id', '=', DB::raw("JSON_UNQUOTE(JSON_EXTRACT(employee_details.company_id, '$[0]'))"))
                 ->on('company_shifts.shift_name', '=', 'employee_details.shift_type');
        })
        ->select(
            'swipe_records.*', 
            'employee_details.first_name', 
            'employee_details.last_name',
            'company_shifts.shift_start_time',  // Use shift times from company_shifts
            'company_shifts.shift_end_time',    // Use shift times from company_shifts
          
        )
        ->where(function ($query) {
            $query->whereRaw("swipe_records.swipe_time > company_shifts.shift_start_time");
        })
        ->where('employee_details.employee_status', 'active')
        ->distinct('swipe_records.emp_id')
        ->count();
        $this->earlyemployeescount = SwipeRecord::whereIn('swipe_records.id', function ($query) use ($employees, $approvedLeaveRequests, $currentDate) {
            $query->selectRaw('MIN(swipe_records.id)')
                ->from('swipe_records')
                ->whereIn('swipe_records.emp_id', $employees->pluck('emp_id'))
                ->whereNotIn('swipe_records.emp_id', $approvedLeaveRequests->pluck('emp_id'))
                ->whereDate('swipe_records.created_at', $currentDate)
                ->groupBy('swipe_records.emp_id');
        })
        ->join('employee_details', 'swipe_records.emp_id', '=', 'employee_details.emp_id')
        ->leftJoin('emp_personal_infos', 'swipe_records.emp_id', '=', 'emp_personal_infos.emp_id') // Joining emp_personal_infos
        ->leftJoin('company_shifts', function ($join) {
            // Join with company_shifts using JSON_UNQUOTE to extract company_id from employee_details
            $join->on('company_shifts.company_id', '=', DB::raw("JSON_UNQUOTE(JSON_EXTRACT(employee_details.company_id, '$[0]'))"))
                 ->on('company_shifts.shift_name', '=', 'employee_details.shift_type');
        })
        ->select(
            'swipe_records.*', 
            'employee_details.first_name', 
            'employee_details.last_name', 
            'emp_personal_infos.mobile_number', // Selecting fields from emp_personal_infos
            'company_shifts.shift_start_time'  // Select shift start time from company_shifts
        )
        ->where(function ($query) {
            // Comparing swipe_time with shift_start_time from company_shifts
            $query->whereRaw("swipe_records.swipe_time <= company_shifts.shift_start_time");
        })
        ->where('employee_details.employee_status', 'active')
        ->orderBy('swipe_records.swipe_time')
        ->distinct('swipe_records.emp_id')
        ->count();

        
        $this->totalemployees = EmployeeDetails::select('emp_id', 'first_name', 'last_name')->orderBy('first_name')->get();
        foreach($this->totalemployees as $employee)
        {
            $normalizedEmployeeId = str_replace('-', '', $employee->emp_id);
            
        }
        $employees = EmployeeDetails::join('swipe_records', 'employee_details.emp_id', '=', 'swipe_records.emp_id')
    ->select('employee_details.emp_id', 'employee_details.first_name', 'employee_details.last_name',  'swipe_records.in_or_out', 'swipe_records.swipe_time','swipe_records.sign_in_device')
    ->where('swipe_records.in_or_out', 'IN')
    ->whereDate('swipe_records.created_at', $currentDate) // Filter for today's date
    ->orderBy('employee_details.first_name')
    ->get();
   

$this->mobileEmployeeCount = 0;
$this->laptopEmployeeCount = 0;

foreach ($employees as $employee) {
    if ($employee->sign_in_device === 'Mobile') {
        $this->mobileEmployeeCount++;
    } elseif ($employee->sign_in_device === 'Desktop/Laptop') {
        $this->laptopEmployeeCount++;
    }
}
        return view('livewire.hr-attendance-overview-new',['Employees'=>$employees]);
    }
}

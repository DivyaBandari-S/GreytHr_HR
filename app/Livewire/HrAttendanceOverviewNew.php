<?php

namespace App\Livewire;

use App\Exports\LateArrivalsExport;
use App\Helpers\FlashMessageHelper;
use App\Mail\RegularisationApprovalMail;
use App\Mail\RegularisationRejectionMail;
use App\Models\EmployeeDetails;
use App\Models\HolidayCalendar;
use App\Models\Hr;
use App\Models\LeaveRequest;
use App\Models\RegularisationDates;
use App\Models\SwipeRecord;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\SimpleExcel\SimpleExcelWriter;

class HrAttendanceOverviewNew extends Component
{
    public $totalemployees;
    
    public $chartDate;

    public $openApprovePopupModal=false;

    public $employeeEmailForApproval;

    public $openRejectPopupModal=false;
    public $openAccordionForActive=[];
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

    public $remarks;
    public $nextYear;
    public $currentYear;

    
    public $employeeEmailForRejection;
   
    public $openshiftselectorforcheck=false;
    public $isdateSelected=0;
    public $monthNumber;
    public $currentMonth;
    public $monthNumbers;

    public $user;
    public $today;

    public $absentDays;
    public $yesterday;

    public $selectedDateForDropdown;
    public $dayBeforeYesterday;

    public $holidaysInMonth;
    public $currentMonthForSummary;

    public $currentMonthForSummaryInFormat;
    public $currentYearForSummary;
    public function mount()
    {
        $this->generateWorkHoursData();
        $currentDate = now()->toDateString();
        $currentDate = Carbon::today();
        $today = $currentDate->toDateString(); // Get today's date in 'Y-m-d' format
        $month = $currentDate->format('n');
        $year = $currentDate->format('Y');
        $loggedInEmpID = auth()->guard('hr')->user()->emp_id;
        
        $this->user = EmployeeDetails::where('emp_id', $loggedInEmpID)->first();
 
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
        $this->currentMonthForSummaryInFormat=Carbon::now()->format('F');
        $this->currentMonthForSummary=Carbon::now()->month;
        $this->currentYearForSummary=Carbon::now()->year;
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
        ->leftJoin('company_shifts', function ($join) {
            $join->on('company_shifts.company_id', '=', DB::raw("JSON_UNQUOTE(JSON_EXTRACT(employee_details.company_id, '$[0]'))"))
                 ->on('company_shifts.shift_name', '=', 'employee_details.shift_type');
        })
        ->whereRaw("TIME(swipe_records.swipe_time) > company_shifts.shift_start_time")
        ->where('employee_details.employee_status', 'active')
        ->count(DB::raw('DISTINCT swipe_records.emp_id'));
    
        $subQuery = SwipeRecord::selectRaw('MIN(swipe_records.id) as min_id')
    ->whereIn('swipe_records.emp_id', $employees->pluck('emp_id'))
    ->whereNotIn('swipe_records.emp_id', $approvedLeaveRequests->pluck('emp_id'))
    ->whereDate('swipe_records.created_at', $currentDate)
    ->groupBy('swipe_records.emp_id');

$this->earlyemployeescount = DB::table('swipe_records')
    ->joinSub($subQuery, 'min_swipes', function ($join) {
        $join->on('swipe_records.id', '=', 'min_swipes.min_id');
    })
    ->join('employee_details', 'swipe_records.emp_id', '=', 'employee_details.emp_id')
    ->leftJoin('emp_personal_infos', 'employee_details.emp_id', '=', 'emp_personal_infos.emp_id') // join added
    ->leftJoin('company_shifts', function ($join) {
        $join->on('company_shifts.company_id', '=', DB::raw("JSON_UNQUOTE(JSON_EXTRACT(employee_details.company_id, '$[0]'))"))
             ->on('company_shifts.shift_name', '=', 'employee_details.shift_type');
    })
    ->whereRaw("TIME(swipe_records.swipe_time) <= company_shifts.shift_start_time")
    ->where('employee_details.employee_status', 'active')
    ->distinct('swipe_records.emp_id')
    ->count();

        // Construct the table name for SQL Server
        $this->holidaysInMonth = HolidayCalendar::where('year', $this->currentYearForSummary)
                    ->where('month', $this->currentMonthForSummary)
                    ->count();
        $this->absentDays=$this->findAbsentDaysForEmployees($this->currentYearForSummary,$this->currentMonthForSummary);
    }

    public function generateWorkHoursData()
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        
        $daysInMonth = Carbon::now()->daysInMonth;
        $this->days = range(1, $daysInMonth);

        // Initialize work hours array with 0 for each day
        $this->workHours = array_fill(0, $daysInMonth, 0);

        // Assuming you have a model SwipeRecord that records the work hours per day
        $records = SwipeRecord::whereMonth('created_at', $currentMonth)
                              ->whereYear('created_at', $currentYear)
                              ->get();

        foreach ($records as $record) {
            $day = (int)$record->created_at->format('d');
            $this->workHours[$day - 1] += $record->total_hours; // Adjust `total_hours` based on your model's field
        }
    }

    private function findAbsentDaysForEmployees($currentYearForSummary, $currentMonthForSummary)
{
    $totalabsentdays=0;
    Log::info('Welcome to findAbsentDaysForEmployees method');
    
    // Define the start and end of the month
    // $startOfMonth = Carbon::create($currentYearForSummary, $currentMonthForSummary, 1);
    // $endOfMonth = Carbon::now()->format('Y-m-d');
    $startOfMonth = Carbon::create($currentYearForSummary,  $currentMonthForSummary, 1);
    $endOfMonth = Carbon::now()->format('Y-m-d');


    // Retrieve all active employees
    $employees = EmployeeDetails::select('emp_id', 'first_name', 'last_name')->where('employee_status', 'active')->get();

    // Generate all weekdays in the month
    $datesInMonth = [];
    for ($date = $startOfMonth->copy(); $date->lte($endOfMonth); $date->addDay()) {
        if (!$date->isWeekend()) { // Exclude weekends right away
            $datesInMonth[] = $date->format('Y-m-d');
        }
    }

    // Log the generated dates for verification
    Log::info($datesInMonth);
    
    // Initialize an array to hold absent days for each employee
    $employeeAbsentDays = []; 

    // Retrieve all approved leave requests for the employees at once
  

    // Prepare leave dates for each employee
    
    

    // Iterate through each employee
   
     
        // Check for absent days from generated weekdays
        foreach ($datesInMonth as $date) {
            $approvedLeaveRequests = LeaveRequest::join('employee_details', 'leave_applications.emp_id', '=', 'employee_details.emp_id')
        ->leftJoin('emp_personal_infos', 'leave_applications.emp_id', '=', 'emp_personal_infos.emp_id') // Joining with emp_personal_infos
        ->where('leave_applications.leave_status', 2)
        ->whereIn('leave_applications.emp_id', $employees->pluck('emp_id'))
        ->whereDate('from_date', '<=', $date)
        ->whereDate('to_date', '>=', $date)
        ->where('employee_details.employee_status', 'active')
        ->get([
            'leave_applications.*', // To get leave date and leave type
            'employee_details.*',
        ])
        ->map(function ($leaveRequest) {
            // Calculating the number of leave days excluding weekends
            $fromDate = Carbon::parse($leaveRequest->from_date);
            $toDate = Carbon::parse($leaveRequest->to_date);

            // Generate all dates between from_date and to_date, excluding weekends
            $leave_dates = [];
            for ($date = $fromDate->copy(); $date->lte($toDate); $date->addDay()) {
                // Exclude weekends (Saturday=6, Sunday=7)
                if (!$date->isWeekend()) {
                    $leave_dates[] = $date->format('Y-m-d');
                }
            }

            // Set the leave_dates attribute using setAttribute
            $leaveRequest->setAttribute('leave_dates', $leave_dates);

            // Calculate the number of leave days excluding weekends
            $leaveRequest->number_of_days = count($leave_dates);

            return $leaveRequest;
        });
            // Check if a swipe record exists for the employee on this date
            $employees1 = EmployeeDetails::leftJoin('emp_personal_infos', 'employee_details.emp_id', '=', 'emp_personal_infos.emp_id')
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
            ->whereNotIn('employee_details.emp_id', function ($query) use ($date) {
                $query->select('emp_id')
                    ->from('swipe_records')
                    ->whereDate('created_at', $date);
            })
            ->whereNotIn('employee_details.emp_id', $approvedLeaveRequests->pluck('emp_id'))
            ->where('employee_details.employee_status', 'active')
            ->orderBy('employee_details.first_name')
            ->distinct('employee_details.emp_id')
            ->count();
            Log::info('Number of absentees in '.$date.'is'.$employees1 );
            $totalabsentdays+=$employees1; 
            Log::info('TotalAbsentEmployees:'.$totalabsentdays);    
        }
       
    return $totalabsentdays;
    // Log or process $employeeAbsentDays as needed
    // Log::info("In findAbsentDaysForEmployees Method, Absent days for each employee:", $totalabsentdays);
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
    
        return Excel::download(new LateArrivalsExport($data), 'not_assigned_employees.xlsx');
       
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
            } elseif ($employee->sign_in_device === 'Desktop' ) {
                $deviceLabel = 'Web Sign In';
            }
            elseif ($employee->sign_in_device === 'Laptop' ) {
                $deviceLabel = 'Web Sign In';
            }
             else {
                $deviceLabel = 'Unknown Device';
            }
            $data[] = [$employee->emp_id, ucwords(strtolower($employee->first_name)).' '.ucwords(strtolower($employee->last_name)),$employee->swipe_time,$employee->job_role,$employee->job_location,$deviceLabel];
        }
        return Excel::download(new LateArrivalsExport($data), 'employees_attendance_type.xlsx');
       
    }
    public function closeAllAbsentEmployees()
    {
      $this->openshiftselectorforcheck=false;   
    }
    public function toggleActiveAccordion($id)
    {
        if (in_array($id, $this->openAccordionForActive)) {
            // Remove from open accordions if already open
            $this->openAccordionForActive = array_diff($this->openAccordionForActive, [$id]);
        } else {
            // Add to open accordions if not open
            $this->openAccordionForActive[] = $id;
        }
    }
  
    public function openApproveModal()
    {
        $this->openApprovePopupModal=true;
    }
    public function approve($id)
    {
        
        // dd();
        $currentDateTime = Carbon::now();
        $item = RegularisationDates::find($id);
       
        $employeeId=$item->emp_id;
       
        $item->status=2;
        if(!empty($this->remarks))
        {
            $item->approver_remarks=$this->remarks;
            

        }
        
        $item->approved_date = $currentDateTime;
        
        $item->approved_by=$this->user->first_name . ' ' . $this->user->last_name;
        
        
        $item->save();
        $regularisationEntries = json_decode($item['regularisation_entries'], true);
        $count_of_regularisations=count($regularisationEntries);
        
        
        if (!empty($regularisationEntries) && is_array($regularisationEntries)) {
            
            for($i=0;$i<$count_of_regularisations;$i++) {
               
                $swiperecord=new SwipeRecord();
                $swiperecord->emp_id=$employeeId;
                $date = $regularisationEntries[$i]['date'];
                $from=$regularisationEntries[$i]['from'];
                $to=$regularisationEntries[$i]['to'];
                $reason=$regularisationEntries[$i]['reason'];
               
               
                
                if (empty($from)) {
                    
                    
                    $swiperecord->in_or_out='IN';
                    $swiperecord->swipe_time= '10:00';
                    $swiperecord->created_at=$date;
                    $swiperecord->is_regularized=true;
                    
                } else {
                    $swiperecord->in_or_out='IN';
                    $swiperecord->swipe_time= $from;
                    $swiperecord->created_at=$date;
                    $swiperecord->is_regularized=true;
                }
                $swiperecord->save();
                $swiperecord1=new SwipeRecord();
                $swiperecord1->emp_id=$employeeId;
                
                if (empty($to) ){
                    
                    
                    $swiperecord1->in_or_out='OUT';
                    $swiperecord1->swipe_time= '19:00';
                    $swiperecord1->created_at=$date;
                    $swiperecord1->is_regularized=true;
                    
                } else {
                    $swiperecord1->in_or_out='OUT';
                    $swiperecord1->swipe_time= $to;
                    $swiperecord1->created_at=$date;
                    $swiperecord1->is_regularized=true;
                }
                $swiperecord1->save();
                // Exit the loop after the first entry since the example has one entry
               
            }
        }
       
        $this->remarks='';
        $this->closeApproveModal();
        FlashMessageHelper::flashSuccess('Regularisation Request approved successfully');
        $this->sendApprovalMail($id);
        // $this->sendApprovalMail($id);
    }
    public function closeApproveModal()
    {

        $this->openApprovePopupModal=false;
        $this->remarks='';
    
    }
    public function openRejectModal()
    {
       $this->openRejectPopupModal=true;
    }
    public function closeRejectModal()
    {
        $this->openRejectPopupModal=false;
        $this->remarks='';
    }
    public function reject($id)
    {
        $currentDateTime = Carbon::now();
        $item = RegularisationDates::find($id);
        if(!empty($this->remarks))
        {
            $item->approver_remarks=$this->remarks;
        }
     
        $item->status=3;
        $item->rejected_date = $currentDateTime; 
        $item->rejected_by=$this->user->first_name . ' ' . $this->user->last_name;
        $item->save();

       
        $this->remarks='';
        $this->closeRejectModal();
        FlashMessageHelper::flashError('Regularisation Request rejected successfully');
        $this->sendRejectionMail($id);
    }
    public function sendRejectionMail($id)
    {
        $item = RegularisationDates::find($id); // Ensure you have the correct ID to fetch data
 
        $regularisationEntriesforRejection = json_decode($item->regularisation_entries, true); // Decode the JSON entries
   
    $employee = EmployeeDetails::where('emp_id', $item->emp_id)->first();
    // Prepare the HTML table
    
    $this->employeeEmailForRejection=$employee->email;
    $details = [
     
        'regularisationRequests'=>$regularisationEntriesforRejection,
        'sender_id'=>$employee->emp_id,
        'sender_remarks'=>$item->employee_remarks,
        'receiver_remarks'=>$item->approver_remarks,
       
    ];
 
 
    // Send email to manager
      Mail::to($this->employeeEmailForRejection)->send(new RegularisationRejectionMail($details));
    }
    public function sendApprovalMail($id)
    {
        $item = RegularisationDates::find($id); // Ensure you have the correct ID to fetch data
 
        $regularisationEntriesforApproval = json_decode($item->regularisation_entries, true); // Decode the JSON entries
   
    $employee = EmployeeDetails::where('emp_id', $item->emp_id)->first();
    // Prepare the HTML table
    
    $this->employeeEmailForApproval=$employee->email;
    $details = [
     
        'regularisationRequests'=>$regularisationEntriesforApproval,
        'sender_id'=>$employee->emp_id,
        'sender_remarks'=>$item->employee_remarks,
        'receiver_remarks'=>$item->approver_remarks,
       
    ];
 
 
    // Send email to manager
      Mail::to($this->employeeEmailForApproval)->send(new RegularisationApprovalMail($details));
    }
    private function calculateWeekdaysDiff(Carbon $startDate, Carbon $endDate)
{
    $weekdays = 0;
    while ($startDate->lte($endDate)) {
        $checkholidayflag=$this->isHolidayOnDate($startDate);
        if (!$startDate->isWeekend()&&$checkholidayflag==1) { // Exclude weekends
            $weekdays++;
        }
        $startDate->addDay();
    }
    return $weekdays;
}

private function isHolidayOnDate($date)
{
   $holidayflag=0;
   $holidayexists=HolidayCalendar::where('date',$date)->exists();
   if($holidayexists)
   {
      $holidayflag=1;
   }
   return $holidayflag;
}
private function calculateWorkdaysDiff(Carbon $startDate, Carbon $endDate)
{
    $workdays = 0;
    while ($startDate->lte($endDate)) {
        // Count only if the day is neither a weekend nor a holiday
        if (!$startDate->isWeekend() && !$this->isHolidayOnDate($startDate)) {
            $workdays++;
        }
        $startDate->addDay();
    }
    return $workdays;
}
public function openSelector()
{
    $this->openshiftselectorforcheck = true;
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
    } elseif ($employee->sign_in_device === 'Desktop') {
        $this->laptopEmployeeCount++;
    }
    elseif ($employee->sign_in_device === 'Laptop') {
        $this->laptopEmployeeCount++;
    }
}
        return view('livewire.hr-attendance-overview-new',['Employees'=>$employees]);
    }
}

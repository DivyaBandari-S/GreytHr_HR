<?php

namespace App\Livewire;

use App\Exports\EmployeesExport;
use App\Models\EmployeeDetails;
use App\Models\HolidayCalendar;
use App\Models\Hr;
use App\Models\LeaveRequest;
use App\Models\SwipeRecord;
use Carbon\Carbon;
use DateTime;
use Livewire\Component;
use Maatwebsite\Excel\Excel;
use Spatie\SimpleExcel\SimpleExcelWriter;

class AttendanceMusterHr extends Component
{
    public $currentYear;

    public $holiday;
    public $year1;
    public $selectedYear;
    public $nextYear;
    public $previousYear;

    public $year;
    public $months;
    public $selectedMonth;
    public $currentMonth;

    public $selectedOption = 'all'; 
    public $selectedEmployeeType;
    public $distinctDatesMap;
    public $todaysdate;
    public $employees;

    public $showHelp=true;
    public $displayText;
    public $loggedInEmpId;

    public $empIds;
    public function mount()
    {
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
            // If it's already an array, no need to decode
          
        $this->empIds = EmployeeDetails::where(function($query) use ($companyIds) {
            foreach ($companyIds as $companyId) {
                // Use JSON_CONTAINS to check if company_id field contains the companyId
                $query->orWhereRaw('JSON_CONTAINS(company_id, \'' . json_encode($companyId) . '\')');
            }
        })->pluck('emp_id');
      
        $this->selectedEmployeeType = 'all'; // Default to 'Employee: All'
        $this->displayText = 'Employee: All';
        $this->todaysdate=Carbon::now()->format('Y-m-d');
        $this->employees = EmployeeDetails::where('manager_id', $this->loggedInEmpId)->get();
        $this->currentYear = Carbon::now()->year;
        $this->selectedYear=$this->currentYear;
        $this->currentMonth = Carbon::now()->month;
        $this->previousYear = $this->currentYear - 1;
        $this->nextYear = $this->currentYear + 1;
        $this->selectedMonth=$this->currentMonth;
        $this->months = collect(range(1, 12))->map(function ($month) {
            return [
                'value' => $month,
                'name' => Carbon::createFromDate(null, $month, 1)->format('F'),
            ];
        });
    }

    public function hideHelp()
    {
       $this->showHelp=!$this->showHelp;
    }
    public function downloadExcel()
    {
        
        return \Maatwebsite\Excel\Facades\Excel::download(new EmployeesExport($this->selectedMonth, $this->selectedYear,$this->selectedOption), 'attendance_muster_report.xlsx');

    }
    public function updateSelectedYear()
    {
        // You can trigger any logic here when the year changes
        
        $this->selectedYear=$this->selectedYear;
        $this->selectedMonth=Carbon::now()->month;
       
       
    }
    public function updateselectedMonth()
    {
        $this->selectedMonth=$this->selectedMonth;
    }
    // Default option

    public function updateSelected($option)
    {
        $this->selectedOption = $option;
    }
    public function render()
    {
        
        $this->holiday = HolidayCalendar::where('month',Carbon::createFromDate(null, $this->selectedMonth, 1)->format('F'))
        ->where('year', $this->selectedYear)
        ->pluck('date');
        
        $this->distinctDatesMap = SwipeRecord::whereIn('emp_id', $this->empIds)
        ->whereMonth('created_at', $this->selectedMonth) // December
        ->whereYear('created_at', $this->selectedYear) // December
        ->selectRaw('DISTINCT emp_id, DATE(created_at) as distinct_date ')
        ->get()
        ->groupBy('emp_id')
        ->map(function ($dates) {
            return $dates->pluck('distinct_date')->toArray();
        })
        ->toArray();
        $approvedLeaveRequests1 = LeaveRequest::join('employee_details', 'leave_applications.emp_id', '=', 'employee_details.emp_id')
            ->where('leave_applications.leave_status', 2)
            ->whereIn('leave_applications.emp_id', $this->empIds)
            ->whereDate('from_date', '>=', $this->selectedYear . '-' . $this->selectedMonth . '-01') // Dynamically set year and month
            ->whereDate('to_date', '<=', $this->selectedYear . '-' . $this->selectedMonth . '-31') // Dynamically set year and month
            ->get(['leave_applications.*', 'employee_details.emp_id', 'employee_details.first_name', 'employee_details.last_name'])
            ->mapWithKeys(function ($leaveRequest) {
                $fromDate = \Carbon\Carbon::parse($leaveRequest->from_date);
                $toDate = \Carbon\Carbon::parse($leaveRequest->to_date);
                $number_of_days = $fromDate->diffInDays($toDate) + 1;
                $dates = [];
                for ($i = 0; $i < $number_of_days; $i++) {
                    $dates[] = $fromDate->copy()->addDays($i)->toDateString();
                }
                return [
                    $leaveRequest->emp_id => [
                        'emp_id' => $leaveRequest->emp_id,
                        'dates' => $dates,
                    ],
                ];
            }); 
            $leaveDates = [];
            foreach ($approvedLeaveRequests1 as $emp_id => $leaveRequest) {
                foreach ($leaveRequest['dates'] as $date) {
                    $leaveDates[$emp_id][] = $date;
                }
            }
            if($this->selectedOption=='current')
            {
                $this->employees = EmployeeDetails::whereIn('emp_id', $this->empIds)->where('employee_status','active')->get();                
            }
            elseif($this->selectedOption=='past')
            {
                $this->employees = EmployeeDetails::whereIn('emp_id', $this->empIds)->where('employee_status','resigned')->orWhere('employee_status','terminated')->get(); 
            }
            elseif($this->selectedOption=='intern')
            {
                $this->employees = EmployeeDetails::whereIn('emp_id', $this->empIds)->where('job_role','intern')->get(); 
            }
            else
            {
                $this->employees = EmployeeDetails::whereIn('emp_id', $this->empIds)->get(); 
            }
        return view('livewire.attendance-muster-hr',['DistinctDatesMap'=>$this->distinctDatesMap,'ApprovedLeaveRequests1'=>$approvedLeaveRequests1]);
    }
}

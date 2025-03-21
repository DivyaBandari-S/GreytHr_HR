<?php

namespace App\Livewire;

use App\Exports\EmployeeShiftExport;
use App\Models\EmployeeDetails;
use App\Models\HolidayCalendar;
use App\Models\Hr;
use App\Models\LeaveRequest;
use Carbon\Carbon;
use Livewire\Component;

class ShiftRosterHr extends Component
{
    public $selectedMonth;

    public $currentYear;

    public $selectedOption = 'All'; 
    public $approvedLeaveRequests;
    public $daysInMonth;
    public $months;
    public $currentMonth;

    public $count_of_holiday;
    public $holiday;
    public $employees;

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
       
        $this->empIds = EmployeeDetails::where(function($query) use ($companyIds) {
            foreach ($companyIds as $companyId) {
                // Use JSON_CONTAINS to check if company_id field contains the companyId
                $query->orWhereRaw('JSON_CONTAINS(company_id, \'' . json_encode($companyId) . '\')');
            }
        })->pluck('emp_id');
        $this->employees = EmployeeDetails::whereIn('emp_id', $this->empIds)->get();
        $this->currentYear=Carbon::now()->year;
        $this->currentMonth = Carbon::now()->month;
        $this->selectedMonth=$this->currentMonth;
        $employeeIds = $this->employees->pluck('emp_id');
        $this->approvedLeaveRequests = LeaveRequest::join('employee_details', 'leave_applications.emp_id', '=', 'employee_details.emp_id')
        ->where('leave_applications.leave_status', 2)
        ->whereIn('leave_applications.emp_id', $employeeIds)
        ->whereDate('from_date', '>=', $this->currentYear . '-' . $this->selectedMonth . '-01') // Dynamically set year and month
        ->whereDate('to_date', '<=', $this->currentYear . '-' . $this->selectedMonth . '-31') // Dynamically set year and month
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
        foreach ($this->approvedLeaveRequests as $emp_id => $leaveRequest) {
            foreach ($leaveRequest['dates'] as $date) {
                $leaveDates[$emp_id][] = $date;
            }
        }
     
        
        
        $this->months = collect(range(1, 12))->map(function ($month) {
            return [
                'value' => $month,
                'name' => Carbon::createFromDate(null, $month, 1)->format('F'),
            ];
        });
    }
    public function downloadExcelForShiftRoster()
    {
        return \Maatwebsite\Excel\Facades\Excel::download(new EmployeeShiftExport($this->selectedMonth, $this->currentYear,$this->selectedOption), 'shift_roster_report.xlsx');
    }
    public function updateselectedMonth()
    {
        $this->selectedMonth=$this->selectedMonth;
    }
    public function updateSelected($option)
    {
        $this->selectedOption = $option;
    }
    public function refresh(): void
    {
        $this->selectedOption='All';
        $this->selectedMonth=Carbon::now()->month;
    }
    public function render()
    {
        $monthName=Carbon::createFromDate(null, $this->selectedMonth, 1)->format('F');
        $this->holiday = HolidayCalendar::where('month',$monthName)
        ->where('year', $this->currentYear)
        ->get('date');
        $this->count_of_holiday=count($this->holiday);
        if($this->selectedOption=='Current')
            {
                $this->employees = EmployeeDetails::whereIn('emp_id', $this->empIds)
                ->where('employee_status', 'active') // Filter employees with active status
                ->join('company_shifts', function ($join) {
                    $join->on('employee_details.shift_type', '=', 'company_shifts.shift_name') // Join on shift_type and shift_name
                         ->whereRaw('JSON_CONTAINS(employee_details.company_id, JSON_QUOTE(company_shifts.company_id))'); // Match company_id
                })
                ->select(
                    'employee_details.*', // All fields from employee_details
                    'company_shifts.shift_start_time', // Shift start time
                    'company_shifts.shift_end_time', // Shift end time
                    'company_shifts.shift_name'
                )
                ->get();
                           
            }
            elseif($this->selectedOption=='Past')
            {
                $this->employees = EmployeeDetails::whereIn('emp_id', $this->empIds)
                                    ->where(function ($query) {
                                        $query->where('employee_status', 'resigned')
                                            ->orWhere('employee_status', 'terminated');
                                    })
                                    ->join('company_shifts', function ($join) {
                                        $join->on('employee_details.shift_type', '=', 'company_shifts.shift_name')
                                            ->whereRaw('JSON_CONTAINS(employee_details.company_id, JSON_QUOTE(company_shifts.company_id))');
                                    })
                                    ->select(
                                        'employee_details.*', // All columns from employee_details
                                        'company_shifts.shift_start_time', // Shift start time
                                        'company_shifts.shift_end_time', // Shift end time
                                        'company_shifts.shift_name'
                                    )
                                    ->get();

                
            }
            elseif($this->selectedOption=='Intern')
            {
                $this->employees = EmployeeDetails::whereIn('emp_id', $this->empIds)
                                    ->where('job_role', 'intern') // Filter employees with job_role = 'intern'
                                    ->join('company_shifts', function ($join) {
                                        $join->on('employee_details.shift_type', '=', 'company_shifts.shift_name')
                                            ->whereRaw('JSON_CONTAINS(employee_details.company_id, JSON_QUOTE(company_shifts.company_id))');
                                    })
                                    ->select(
                                        'employee_details.*', // All columns from employee_details
                                        'company_shifts.shift_start_time', // Shift start time from company_shifts
                                        'company_shifts.shift_end_time', // Shift end time from company_shifts
                                        'company_shifts.shift_name'
                                    )
                                    ->get();
            }
            else
            {
                $this->employees = EmployeeDetails::whereIn('emp_id', $this->empIds)
                                    ->join('company_shifts', function ($join) {
                                        $join->on('employee_details.shift_type', '=', 'company_shifts.shift_name')
                                            ->whereRaw('JSON_CONTAINS(employee_details.company_id, JSON_QUOTE(company_shifts.company_id))');
                                    })
                                    ->select(
                                        'employee_details.*', // Select all columns from employee_details
                                        'company_shifts.shift_start_time', // Include shift start time
                                        'company_shifts.shift_end_time', // Include shift end time
                                        'company_shifts.shift_name',

                                    )
                                    ->get();

            }
        return view('livewire.shift-roster-hr');
    }
}

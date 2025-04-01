<?php

namespace App\Livewire;

use App\Exports\AbsentEmployeesExport;
use App\Exports\LateArrivalsExport;
use App\Models\EmployeeDetails;
use App\Models\LeaveRequest;
use App\Models\SwipeRecord;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\SimpleExcel\SimpleExcelWriter;

class WhoIsInChartHr extends Component
{
    use WithPagination;
    public $leaveRequests;
    public $swipe_records;

    public $dayShiftEmployeesCount;

    public $afternoonShiftEmployeesCount;

    public $eveningShiftEmployeesCount;
    
    public $openAccordionsForAbsentees = [];

    public $openAccordionForLateComers=[];
    public $approvedLeaveRequests;
    public $currentDate;
    public $notFound;
    public $notFound2;
    public $notFound3;
    public $isdatepickerclicked = 0;

    public $employeesOnLeaveCount;
    public $absentEmployeesCount;

    public $openAccordionsForEmployeesOnLeave=[];
    public $toggleButton=false;
    public $isToggled = false;

    public $selectedShift;
    public $openshiftselector = false;
    public $from_date;

    public $employees4;

    public $openAccordionForAbsent=null;

    public $openAccordionForLate=null;

    public $openAccordionForEarly=null;

    public $openAccordionForEarlyComers=[];

    public $openAccordionForLeave=null;
    public $shiftsforAttendance;
    public $search = '';
    public $results = [];
    public function mount()
    {
        $this->currentDate = Carbon::now()->format('Y-m-d');
        // $this->shiftsforAttendance = EmployeeDetails::select('shift_type', 'shift_start_time', 'shift_end_time')
        //                 ->distinct()
        //                 ->get();
        $this->from_date=$this->currentDate;  



    }
    public function opentoggleButton()
    {
        $this->toggleButton = !$this->toggleButton;   
    }
    public function toggleAccordionForAbsent($index)
    {
        if (in_array($index, $this->openAccordionsForAbsentees)) {
            // Remove from open accordions if already open
            $this->openAccordionsForAbsentees = array_diff($this->openAccordionsForAbsentees, [$index]);
        } else {
            // Add to open accordions if not open
            $this->openAccordionsForAbsentees[] = $index;
        }
       
    }
    public function toggleAccordionForLate($index)
    {
        // Toggle the open state for the clicked accordion
        if (in_array($index, $this->openAccordionForLateComers)) {
            // Remove from open accordions if already open
            $this->openAccordionForLateComers = array_diff($this->openAccordionForLateComers, [$index]);
        } else {
            // Add to open accordions if not open
            $this->openAccordionForLateComers[] = $index;
        }
    }
    public function toggleAccordionForEarly($index)
    {
        if (in_array($index, $this->openAccordionForEarlyComers)) {
            // Remove from open accordions if already open
            $this->openAccordionForEarlyComers = array_diff($this->openAccordionForEarlyComers, [$index]);
        } else {
            // Add to open accordions if not open
            $this->openAccordionForEarlyComers[] = $index;
        }
    }
    public function toggleAccordionForLeave($index)
    {
        if (in_array($index, $this->openAccordionsForEmployeesOnLeave)) {
            // Remove from open accordions if already open
            $this->openAccordionsForEmployeesOnLeave = array_diff($this->openAccordionsForEmployeesOnLeave, [$index]);
        } else {
            // Add to open accordions if not open
            $this->openAccordionsForEmployeesOnLeave[] = $index;
        }

    }
    //This function will help us to get the details of late arrival employees(who arrived after 10:00am) in excel sheet
    public function downloadExcelForLateArrivals()
    {
        try {
           
            $employees = EmployeeDetails::select('emp_id', 'first_name', 'last_name','shift_type')->get();
            if ($this->isdatepickerclicked == 0) {
                $currentDate = now()->toDateString();
            } else {
                $currentDate = $this->from_date;
            }

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

            
            
                $swipes = SwipeRecord::whereIn('swipe_records.id', function ($query) use ($employees, $approvedLeaveRequests, $currentDate) {
                    $query->selectRaw('MIN(swipe_records.id)')
                        ->from('swipe_records')
                        ->whereIn('swipe_records.emp_id', $employees->pluck('emp_id'))
                        ->whereNotIn('swipe_records.emp_id', $approvedLeaveRequests->pluck('emp_id'))
                        ->whereDate('swipe_records.created_at', $currentDate)
                        ->groupBy('swipe_records.emp_id');
                })
                ->join('employee_details', 'swipe_records.emp_id', '=', 'employee_details.emp_id')
                ->leftJoin('emp_personal_infos', 'swipe_records.emp_id', '=', 'emp_personal_infos.emp_id')
                ->leftJoin('company_shifts', function ($join) {
                    // Join with company_shifts using JSON_UNQUOTE to extract company_id from employee_details
                    $join->on('company_shifts.company_id', '=', DB::raw("JSON_UNQUOTE(JSON_EXTRACT(employee_details.company_id, '$[0]'))"))
                         ->on('company_shifts.shift_name', '=', 'employee_details.shift_type');
                })
                ->select(
                    'swipe_records.*',
                    'employee_details.*',
                    
                    'company_shifts.shift_start_time',
                    'company_shifts.shift_end_time',
                    'company_shifts.shift_name'
                )
                ->where('employee_details.employee_status', 'active')
                ->orderByDesc('swipe_records.swipe_time')
                ->distinct('swipe_records.emp_id')
                ->get();
        
           
            $data = [
                ['List of Late Arrival Employees on ' . Carbon::parse($currentDate)->format('jS F, Y')],
                ['Employee ID', 'Name', 'Sign In Time', 'Late By(HH:MM)'],
            ];

            foreach ($swipes as $employee) {
                $swipeTime = Carbon::parse($employee->swipe_time);
                $shiftStartTime = $employee->shift_start_time;
                $swipeTime1 = Carbon::parse($employee['created_at'])->format('H:i:s');
                $lateArrivalTime = $swipeTime->diff(Carbon::parse($shiftStartTime))->format('%H:%I');
                $isLateBy10AM = $swipeTime->format('H:i') >= $shiftStartTime;
                if($isLateBy10AM)
                {
                    $data[] = [$employee['emp_id'], ucwords(strtolower($employee['first_name'])) . ucwords(strtolower($employee['last_name'])), Carbon::parse($employee['swipe_time'])->format('H:i:s'), $lateArrivalTime];
                }
            }

            return Excel::download(new LateArrivalsExport($data), 'late_employees.xlsx');
        } catch (\Exception $e) {
            Log::error('Error generating Excel report for late arrivals: ' . $e->getMessage());
            session()->flash('error', 'An error occurred while generating the Excel report. Please try again.');
            return redirect()->back();
        }
    }
    //This function will help us to get the details of early arrival employees(who arrived before 10:00am) in excel sheet
    public function downloadExcelForEarlyArrivals()
    {
        try {
            
            $employees = EmployeeDetails::select('emp_id', 'first_name', 'last_name')->get();
            if ($this->isdatepickerclicked == 0) {
                $currentDate = now()->toDateString();
            } else {
                $currentDate = $this->from_date;
            }
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


            
                
                $swipes = SwipeRecord::whereIn('swipe_records.id', function ($query) use ($employees, $approvedLeaveRequests, $currentDate) {
                    $query->selectRaw('MIN(swipe_records.id)')
                        ->from('swipe_records')
                        ->whereIn('swipe_records.emp_id', $employees->pluck('emp_id'))
                        ->whereNotIn('swipe_records.emp_id', $approvedLeaveRequests->pluck('emp_id'))
                        ->whereDate('swipe_records.created_at', $currentDate)
                        ->groupBy('swipe_records.emp_id');
                })
                ->join('employee_details', 'swipe_records.emp_id', '=', 'employee_details.emp_id')
                ->leftJoin('emp_personal_infos', 'swipe_records.emp_id', '=', 'emp_personal_infos.emp_id')
                ->leftJoin('company_shifts', function ($join) {
                    // Join with company_shifts using JSON_UNQUOTE to extract company_id from employee_details
                    $join->on('company_shifts.company_id', '=', DB::raw("JSON_UNQUOTE(JSON_EXTRACT(employee_details.company_id, '$[0]'))"))
                         ->on('company_shifts.shift_name', '=', 'employee_details.shift_type');
                })
                ->select(
                    'swipe_records.*',
                    'employee_details.*',
                    
                    'company_shifts.shift_start_time',
                    'company_shifts.shift_end_time',
                    'company_shifts.shift_name'
                )
                ->where('employee_details.employee_status', 'active')
                ->orderByDesc('swipe_records.swipe_time')
                ->distinct('swipe_records.emp_id')
                ->get();
            $data = [
                ['List of On Time Employees on ' . Carbon::parse($currentDate)->format('jS F, Y')],
                ['Employee ID', 'Name', 'Sign In Time', 'Early By(HH:MM)'],

            ];
            foreach ($swipes as $employee) {
                $swipeTime = Carbon::parse($employee->swipe_time);
                $shiftStartTime = (new DateTime($employee->shift_start_time))->format('H:i');

                $swipeTime1 = Carbon::parse($employee['CalculateAbsenteescreated_at'])->format('H:i:s');
                $earlyArrivalTime = $swipeTime->diff(Carbon::parse($shiftStartTime))->format('%H:%I');
                $isEarlyBy10AM = $swipeTime->format('H:i') <=$shiftStartTime;
                if($isEarlyBy10AM)
                {
                    $data[] = [$employee['emp_id'], ucwords(strtolower($employee['first_name'])).' '. ucwords(strtolower($employee['last_name'])), $employee['swipe_time'], $earlyArrivalTime];
                }
            }
            $filePath = storage_path('app/employees_on_time.xlsx');
            SimpleExcelWriter::create($filePath)->addRows($data);
            return response()->download($filePath, 'employees_on_time.xlsx');
        } catch (\Exception $e) {
            Log::error('Error generating Excel report for early/on-time arrivals: ' . $e->getMessage());
            session()->flash('error', 'An error occurred while generating the Excel report. Please try again.');
            return redirect()->back();
        }
    }
    //This function will help us to get the details of employees who are on leave in excel sheet
    public function downloadExcelForLeave()
    {
        try {
            
            $employees = EmployeeDetails::select('emp_id', 'first_name', 'last_name')->get();
            if ($this->isdatepickerclicked == 0) {
                $currentDate = now()->toDateString();
            } else {
                $currentDate = $this->from_date;
            }
            
            $approvedLeaveRequests = LeaveRequest::join('employee_details', 'leave_applications.emp_id', '=', 'employee_details.emp_id')
                            ->join('status_types', 'leave_applications.leave_status', '=', 'status_types.status_code') // Join with status_types
                            ->where('leave_applications.leave_status', 2)
                            ->whereIn('leave_applications.emp_id', $employees->pluck('emp_id'))
                            ->whereDate('from_date', '<=', $currentDate)
                            ->whereDate('to_date', '>=', $currentDate)
                            ->where('employee_details.employee_status', 'active')
                            ->get([
                            'leave_applications.*',
                            'employee_details.first_name', 
                            'employee_details.last_name', 
                            'status_types.status_name as leave_status' // Select status_name as leave_status
                            ])
                            ->map(function ($leaveRequest) {
                            $fromDate = Carbon::parse($leaveRequest->from_date);
                            $toDate = Carbon::parse($leaveRequest->to_date);

                            // Calculate the number of days excluding weekends
                            $numberOfDays = 0;
                            while ($fromDate->lte($toDate)) {
                            // Check if the day is not a weekend
                            if (!$fromDate->isWeekend()) {
                                $numberOfDays++;
                            }
                            $fromDate->addDay();
                            }

                            $leaveRequest->number_of_days = $numberOfDays;
                            return $leaveRequest;
                            });
            $data = [
                ['List of On Leave Employees on ' . Carbon::parse($currentDate)->format('jS F, Y')],
                ['Employee ID', 'Name', 'Leave Type', 'Leave Days'],

            ];
            foreach ($approvedLeaveRequests as $employee) {
                $data[] = [$employee['emp_id'], ucwords(strtolower($employee['first_name'])) . ' ' . ucwords(strtolower($employee['last_name'])), $employee['leave_type'], $employee['number_of_days']];
            }

            $filePath = storage_path('app/employees_on_leave.xlsx');

            SimpleExcelWriter::create($filePath)->addRows($data);

            return response()->download($filePath, 'employees_on_leave.xlsx');
        } catch (\Exception $e) {
            Log::error('Error generating Excel report for leave: ' . $e->getMessage());
            session()->flash('error', 'An error occurred while generating the Excel report. Please try again.');
            return redirect()->back();
        }
    }
    public function checkShiftForEmployees()
{
   
    $this->selectedShift=$this->selectedShift;
   
    $this->openshiftselector=false;
    
}
    //This function will help us to get the details of employees who are absent in excel sheet
 

        public function downloadExcelForAbsent()
        {
            try {
                $employees = EmployeeDetails::select('emp_id', 'first_name', 'last_name')->get();

                if ($this->isdatepickerclicked == 0) {
                    $currentDate = now()->toDateString();
                } else {
                    $currentDate = $this->from_date;
                }

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
                    ->whereNotIn('employee_details.emp_id', function ($query) use ($currentDate) {
                        $query->select('emp_id')
                            ->from('swipe_records')
                            ->whereDate('created_at', $currentDate);
                    })
                    ->whereNotIn('employee_details.emp_id', $approvedLeaveRequests->pluck('emp_id'))
                    ->where('employee_details.employee_status', 'active')
                    ->orderBy('employee_details.first_name')
                    ->distinct('employee_details.emp_id')
                    ->get()->toArray();

                $data = [
                    ['List of Absent Employees on ' . Carbon::parse($currentDate)->format('jS F, Y')],
                    ['Employee ID', 'Name', 'Shift_Start_Time'],
                ];

                foreach ($employees1 as $employee) {
                    $data[] = [
                        $employee['emp_id'],
                        ucwords(strtolower($employee['first_name'])) . ' ' . ucwords(strtolower($employee['last_name'])),
                        $employee['shift_start_time']
                    ];
                }

                // Create a new export class
                Excel::store(new AbsentEmployeesExport($data), 'absent_employees.xlsx', 'local');

                $filePath = storage_path('app/absent_employees.xlsx');
                return response()->download($filePath, 'absent_employees.xlsx');
            } catch (\Exception $e) {
                Log::error('Error generating Excel report for absent: ' . $e->getMessage());
                session()->flash('error', 'An error occurred while generating the Excel report. Please try again.');
                return redirect()->back();
            }
        }

    //This function will help us to search about any particular employees
    public function searchFilters()
    {
        try {
           
            $this->results = EmployeeDetails::where(function ($query) {
                $query->where(function ($query) {
                    $query->where('first_name', 'like', '%' . $this->search . '%')
                          ->orWhere('last_name', 'like', '%' . $this->search . '%')
                          ->orWhere('emp_id', 'like', '%' . $this->search . '%');
                });
            })->get();
            
        } catch (\Exception $e) {
            Log::error('Error performing search: ' . $e->getMessage());
            session()->flash('error', 'An error occurred while performing the search. Please try again.');
        }
    }
    //This function will help us to get the details of the status of all the employees based on the particular date
    public function updateDate()
    {
        try {
            $this->isdatepickerclicked = 1;
            $this->currentDate = $this->from_date;
            $this->openAccordionForEarlyComers=[];
            $this->openAccordionsForEmployeesOnLeave=[];
            $this->openAccordionForLateComers=[];
            $this->openAccordionsForAbsentees=[];
        } catch (\Exception $e) {
            Log::error('Error updating date: ' . $e->getMessage());
            session()->flash('error', 'An error occurred while updating the date. Please try again.');
        }
    }
    public function checkshift()
{
    $this->selectedShift=$this->selectedShift;
   
    $this->openshiftselector=false;
}
    //After seraching about any particular employee it will remove the data from the search bar
    public function clearSearch()
    {
        try {
            $this->search = '';
            $this->results = [];
        } catch (\Exception $e) {
            Log::error('Error clearing search: ' . $e->getMessage());
            session()->flash('error', 'An error occurred while clearing the search. Please try again.');
        }
    }
    public function openSelector()
    {
        $this->openshiftselector = true;
    }


    public function toggle()
    {
        $this->isToggled = !$this->isToggled;
    }
    public function closeShiftSelector()
    {
        $this->openshiftselector = false;
    }
    

    public function render()
    {
       
        $employees = EmployeeDetails::select('emp_id', 'first_name', 'last_name')->where('employee_status','active')->get();
        $employees2 = EmployeeDetails::where('employee_status','active')->
                    count(); // Count the results

        if ($this->isdatepickerclicked == 0) {
            $currentDate = now()->toDateString();
        } else {
            $currentDate = $this->from_date;
        }
        $approvedLeaveRequests = LeaveRequest::join('employee_details', 'leave_applications.emp_id', '=', 'employee_details.emp_id')
    ->leftJoin('emp_personal_infos', 'leave_applications.emp_id', '=', 'emp_personal_infos.emp_id') // Joining with emp_personal_infos
    ->leftJoin('company_shifts', function ($join) {
        $join->on('company_shifts.company_id', '=', DB::raw("JSON_UNQUOTE(JSON_EXTRACT(employee_details.company_id, '$[0]'))"))
        ->on('company_shifts.shift_name', '=', 'employee_details.shift_type'); // Matching shift_type with shift_name
    })
    ->where('leave_applications.leave_status', 2)
    ->whereIn('leave_applications.emp_id', $employees->pluck('emp_id'))
    ->whereDate('from_date', '<=', $currentDate)
    ->whereDate('to_date', '>=', $currentDate)
    ->where('employee_details.employee_status', 'active')
    ->get([
        'leave_applications.*', // To get leave date and leave type
        'employee_details.*', // Explicitly select employee details you need
        'company_shifts.shift_start_time', // Fetching shift_start_time from company_shifts
        'company_shifts.shift_end_time',   // Fetching shift_end_time from company_shifts
        'company_shifts.shift_name',       // Fetching shift_name from company_shifts
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
      






    $approvedLeaveRequests1 = LeaveRequest::join('employee_details', 'leave_applications.emp_id', '=', 'employee_details.emp_id')
    ->leftJoin('emp_personal_infos', 'leave_applications.emp_id', '=', 'emp_personal_infos.emp_id')  // Join with emp_personal_infos
    ->where('leave_applications.leave_status', 2)
    ->whereIn('leave_applications.emp_id', $employees->pluck('emp_id'))
    ->whereDate('leave_applications.from_date', '<=', $currentDate)
    ->whereDate('leave_applications.to_date', '>=', $currentDate)
    ->select(
        'leave_applications.*', 
        'employee_details.*' // Include fields from emp_personal_infos
    )
    ->count();

            // dd($approvedLeaveRequests1);

            // $approvedLeaveRequests1List = LeaveRequest::join('employee_details', 'leave_applications.emp_id', '=', 'employee_details.emp_id')
            // ->where('leave_applications.status', 'approved')
            // ->whereIn('leave_applications.emp_id', $employees->pluck('emp_id'))
            // ->whereDate('from_date', '<=', '2024-09-04')
            // ->whereDate('to_date', '>=', '2024-09-04')
            // ->get();
            // dd($approvedLeaveRequests1List);

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
    ->whereNotIn('employee_details.emp_id', function ($query) use ($currentDate) {
        $query->select('emp_id')
            ->from('swipe_records')
            ->whereDate('created_at', $currentDate);
    })
    ->whereNotIn('employee_details.emp_id', $approvedLeaveRequests->pluck('emp_id'))
    ->where('employee_details.employee_status', 'active')
    ->orderBy('employee_details.first_name')
    ->distinct('employee_details.emp_id')
    ->get();

       
            
    $swipes = SwipeRecord::whereIn('swipe_records.id', function ($query) use ($employees, $approvedLeaveRequests, $currentDate) {
        $query->selectRaw('MIN(swipe_records.id)')
            ->from('swipe_records')
            ->whereIn('swipe_records.emp_id', $employees->pluck('emp_id'))
            ->whereNotIn('swipe_records.emp_id', $approvedLeaveRequests->pluck('emp_id'))
            ->whereDate('swipe_records.created_at', $currentDate)
            ->groupBy('swipe_records.emp_id');
    })
    ->join('employee_details', 'swipe_records.emp_id', '=', 'employee_details.emp_id')
    ->leftJoin('emp_personal_infos', 'swipe_records.emp_id', '=', 'emp_personal_infos.emp_id')
    ->leftJoin('company_shifts', function ($join) {
        // Join with company_shifts using JSON_UNQUOTE to extract company_id from employee_details
        $join->on('company_shifts.company_id', '=', DB::raw("JSON_UNQUOTE(JSON_EXTRACT(employee_details.company_id, '$[0]'))"))
             ->on('company_shifts.shift_name', '=', 'employee_details.shift_type');
    })
    ->select(
        'swipe_records.*',
        'employee_details.*',
        
        'company_shifts.shift_start_time',
        'company_shifts.shift_end_time',
        'company_shifts.shift_name'
    )
    ->where('employee_details.employee_status', 'active')
    ->orderByDesc('swipe_records.swipe_time')
    ->distinct('swipe_records.emp_id')
    ->get();

            
       
    $lateSwipesCount = SwipeRecord::whereIn('swipe_records.id', function ($query) use ($employees, $approvedLeaveRequests, $currentDate) {
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
        $query->whereRaw("TIME(swipe_records.swipe_time) > company_shifts.shift_start_time");
    })
    ->where('employee_details.employee_status', 'active')
    ->distinct('swipe_records.emp_id')
    ->count();

           
            // $lateSwipesList = SwipeRecord::whereIn('id', function ($query) use ($employees, $approvedLeaveRequests, $currentDate) {
            //     $query->selectRaw('MIN(id)')
            //         ->from('swipe_records')
            //         ->whereNotIn('emp_id', $approvedLeaveRequests->pluck('emp_id'))
            //         ->whereIn('emp_id', $employees->pluck('emp_id'))
            //         ->whereDate('created_at', '2024-09-09')
            //         ->groupBy('emp_id');
            // })
            //     ->join('employee_details', 'swipe_records.emp_id', '=', 'employee_details.emp_id')
            //     ->select('swipe_records.*', 'employee_details.first_name', 'employee_details.last_name','employee_details.shift_start_time','employee_details.shift_end_time')
            //     ->where(function ($query) {
            //         $query->whereRaw("swipe_records.swipe_time > employee_details.shift_start_time");
            //    })
            //     ->get();
            //     dd($lateSwipesList);
            $earlySwipesCount = SwipeRecord::whereIn('swipe_records.id', function ($query) use ($employees, $approvedLeaveRequests, $currentDate) {
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
                $query->whereRaw("TIME(swipe_records.swipe_time) <= company_shifts.shift_start_time");
            })
            ->where('employee_details.employee_status', 'active')
            ->orderBy('swipe_records.swipe_time')
            ->distinct('swipe_records.emp_id')
            ->count();

    
           
        $swipes2 = SwipeRecord::whereIn('id', function ($query) use ($employees, $currentDate) {
            $query->selectRaw('MIN(id)')
                ->from('swipe_records')
                ->whereIn('emp_id', $employees->pluck('emp_id'))
                ->whereDate('created_at', $currentDate)
                ->groupBy('emp_id');
        })
            ->join('employee_details', 'swipe_records.emp_id', '=', 'employee_details.emp_id')
            ->select('swipe_records.*', 'employee_details.first_name', 'employee_details.last_name')
            ->get();
        $this->dayShiftEmployeesCount=EmployeeDetails::where('shift_type','GS')->count();
        $this->afternoonShiftEmployeesCount=EmployeeDetails::where('shift_type','AS')->count();
        $this->eveningShiftEmployeesCount=EmployeeDetails::where('shift_type','ES')->count();
        $swipes_count = $swipes2->count();
        $employeesCount = $employees1->count();

        $calculateAbsent = ($employeesCount / $employees2) * 100;
        $calculateApprovedLeaves = ($approvedLeaveRequests1 / $employees2) * 100;
        $nameFilter = $this->search;
        $swipes = $swipes->filter(function ($swipe) use ($nameFilter) {
            return stripos($swipe->first_name, $nameFilter) !== false ||
                stripos($swipe->last_name, $nameFilter) !== false ||
                stripos($swipe->emp_id, $nameFilter) !== false ||
                stripos($swipe->swipe_time, $nameFilter) !== false;
        });
        $employees1 = $employees1->filter(function ($swipe) use ($nameFilter) {
            return stripos($swipe->first_name, $nameFilter) !== false ||
                stripos($swipe->last_name, $nameFilter) !== false ||
                stripos($swipe->emp_id, $nameFilter) !== false ||
                stripos($swipe->swipe_time, $nameFilter) !== false;
        });
        $approvedLeaveRequests = $approvedLeaveRequests->filter(function ($swipe) use ($nameFilter) {
            return stripos($swipe->first_name, $nameFilter) !== false ||
                stripos($swipe->last_name, $nameFilter) !== false ||
                stripos($swipe->emp_id, $nameFilter) !== false ||
                stripos($swipe->swipe_time, $nameFilter) !== false;
        });
        $this->employeesOnLeaveCount=$approvedLeaveRequests->count();
        $this->absentEmployeesCount=$employees1->count();
        $this->notFound = $swipes->isEmpty();
        $this->notFound = $employees1->isEmpty();
        $this->notFound3 = $approvedLeaveRequests->isEmpty();
        return view('livewire.who-is-in-chart-hr',['Swipes' => $swipes, 'ApprovedLeaveRequests' => $approvedLeaveRequests, 'ApprovedLeaveRequestsCount' => $approvedLeaveRequests1, 'Employees1' => $employees1, 'employeesCount1' => $employeesCount, 'Employess2' => $employees2, 'CalculateAbsentees' => $calculateAbsent, 'CalculateApprovedLeaves' => $calculateApprovedLeaves, 'TotalEmployees' => $employees2, 'currentdate' => $this->currentDate, 'Swipes1' => $swipes_count, 'EarlySwipesCount' => $earlySwipesCount, 'LateSwipesCount' => $lateSwipesCount]);
    }
}

<?php

namespace App\Livewire;

use App\Helpers\FlashMessageHelper;
use App\Models\EmployeeDetails;
use App\Models\HolidayCalendar;
use App\Models\LeaveRequest;
use App\Models\SwipeRecord;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Torann\GeoIP\Facades\GeoIP;

class HrAttendanceTable extends Component
{
    public $distinctDates;

    public $todaysDate;
    public $viewDetailsInswiperecord,$standardWorkingMinutes;

    public $viewDetailsOutswiperecord;
    public $moveCaretLeftSession1 = false;

    public $viewDetailsswiperecord;
    public $moveCaretLeftSession2 = false;
    public $holiday;

    public $swiperecord;

    public $date;

    public $showAlertDialog = false;

    public  string $year;

    public  string $start;

    public $employeeDetails;
    public string $end;

    public $totalshortfallHoursWorked;

    public $totalexcessHoursWorked;

    public $totalexcessMinutesWorked;
    public $totalshortfallMinutesWorked;
    public $totalHoursWorked;

    public $totalWorkedMinutes;
    public $totalMinutesWorked;
    public $startDate;

    public $checkdate=0;
    public $employeeIdForTable;
    public $endDate;
    public $legend=true;
    public $showSR = false;

    public $country='-';

    public $dateforpopup;
    public $fromDate;

    public $selectedEmployeeId;
    public $toDate;
    public $city='-';

    public $employee_shift_type;
    
    public $employeeHireDate;
    public $postal_code='-';

    
    public $employeeShiftDetails;
    protected $listeners = [
        'updateDates'
    ];

    protected $rules = [
        'fromDate' => 'required|date',
        'toDate' => 'required|date|after_or_equal:fromDate', // Ensuring toDate is after or equal to fromDate
    ];
    public function mount($startDateForInsights, $toDate,$selectedEmployeeId)
    {
        // First initialize
        $this->year = Carbon::now()->format('Y');
        $this->selectedEmployeeId=$selectedEmployeeId;
        // $this->start = Carbon::now()->year($this->year)->firstOfMonth()->format('Y-m-d');
         $this->start=$startDateForInsights;
        // $this->end = Carbon::now()->year($this->year)->lastOfMonth()->format('Y-m-d');
        $this->end=$toDate;
        $this->fromDate=$this->start;
        $this->toDate=$this->end;
        $this->employeeDetails=EmployeeDetails::where('emp_id',$this->selectedEmployeeId)->first();
        $this->employeeHireDate=$this->employeeDetails->hire_date;
        $ip = request()->ip();
        $location = GeoIP::getLocation($ip);
        $lat = $location['lat'];
        $lon = $location['lon'];
        $this->country = $location['country'];
        $this->city = $location['city'];
        $this->postal_code = $location['postal_code'];
        $this->employeeShiftDetails = DB::table('employee_details')
    ->join('company_shifts', function($join) {
        $join->on(DB::raw("JSON_UNQUOTE(JSON_EXTRACT(employee_details.company_id, '$[0]'))"), '=', 'company_shifts.company_id')
             ->on('employee_details.shift_type', '=', 'company_shifts.shift_name');
    })
    ->where('employee_details.emp_id', $this->selectedEmployeeId)
    ->select('company_shifts.shift_start_time','company_shifts.shift_end_time','company_shifts.shift_name', 'employee_details.*')
    ->first();
    }
    public function updatedStart($value)
    {
        $this->emit('updatedStart', $value);
    }

    public function updatedEnd($value)
    {
        $this->emit('updatedEnd', $value);
    }
    
  
    public function updatefromDate()
    {
        $this->fromDate=$this->fromDate;
        $this->checkUpdateDate();
    }
   
    public function updateDates($startDateForInsights, $toDate)
    {
        $this->start = $startDateForInsights;
        $this->end = $toDate;
    }
    
    public function updatetoDate()
    {
        $this->toDate=$this->toDate;
        $this->checkUpdateDate();
    }
    public function checkUpdateDate()
    {
        if($this->fromDate>$this->toDate)
        {
            $this->checkdate=1;
            FlashMessageHelper::flashError('Start Date should be lesser than End Date');
           
        }
    }
    private function isEmployeeFullDayLeaveOnDate($date, $employeeId)
{
    try {
        $employeeId = $this->selectedEmployeeId;
      
        $sessionCheck = LeaveRequest::where('emp_id', $employeeId)
                ->where('leave_applications.leave_status', 2)
                ->where('from_session', 'Session 1')
                ->where('to_session', 'Session 1')
                ->exists();

        if ($sessionCheck) {
            // Case when both sessions are 'Session 1'
            $leaveRecord = LeaveRequest::where('emp_id', $employeeId)
                ->where('leave_applications.leave_status', 2)
                ->where('from_session', 'Session 1')
                ->where('to_session', 'Session 1')
                ->where(function ($query) use ($date) {
                    $query->whereDate('from_date', '<=', $date)
                        ->whereDate('to_date', '>', $date);
                })
                ->join('status_types', 'status_types.status_code', '=', 'leave_applications.leave_status')
                ->exists();
            $leaveRecordType= LeaveRequest::where('emp_id', $employeeId)
            ->where('leave_applications.leave_status', 2)
            ->where('from_session', 'Session 1')
            ->where('to_session', 'Session 1')
            ->where(function ($query) use ($date) {
                $query->whereDate('from_date', '<=', $date)
                    ->whereDate('to_date', '>', $date);
            })
            ->join('status_types', 'status_types.status_code', '=', 'leave_applications.leave_status')
            ->value('leave_type');   
        } else {
            // Case when sessions are not both 'Session 1'
            $leaveRecord = LeaveRequest::where('emp_id', $employeeId)
                ->where('leave_applications.leave_status', 2)
                ->where('from_session', 'Session 2')
                ->where('to_session', 'Session 2')
                ->where(function ($query) use ($date) {
                    $query->whereDate('from_date', '<=', $date)
                        ->whereDate('to_date', '>', $date);
                })
                ->join('status_types', 'status_types.status_code', '=', 'leave_applications.leave_status')
                ->exists();
            $leaveRecordType=LeaveRequest::where('emp_id', $employeeId)
            ->where('leave_applications.leave_status', 2)
            ->where('from_session', 'Session 2')
            ->where('to_session', 'Session 2')
            ->where(function ($query) use ($date) {
                $query->whereDate('from_date', '<=', $date)
                    ->whereDate('to_date', '>', $date);
            })
            ->join('status_types', 'status_types.status_code', '=', 'leave_applications.leave_status')
            ->value('leave_type');    
        }
        return [
          
            'leaveRecordType' => $leaveRecordType,
            'fullsessionCheck' => (($leaveRecord) ) ? true : false,
            

        ];
    } catch (\Exception $e) {
        Log::error('Error in isEmployeeHalfDayLeaveOnDate method: ' . $e->getMessage());
        FlashMessageHelper::flashError('An error occurred while checking employee leave. Please try again later.');
        [
          
            'leaveRecordType' => null,
            'fullsessionCheck' =>  false,
            

        ];
    }
}
    private function isEmployeeHalfDayLeaveOnDate($date, $employeeId)
    {
        try {
            $employeeId = $this->selectedEmployeeId;
            $sessionArray=[];
            $leaveRecord=null;
            $isBeforeToDate = $this->isEmployeeFullDayLeaveOnDate($date, $employeeId);
            Log::info('Checking half-day leave for employee.', [
                'employee_id' => $employeeId,
                'date' => $date,
            ]);
    
            $session1Check = LeaveRequest::where('emp_id', $employeeId)
                ->where('leave_applications.leave_status', 2)
                ->where('from_session', 'Session 1')
                ->where('to_session', 'Session 1')
                ->whereDate('from_date', '<=', $date)
                            ->whereDate('to_date', '>=', $date)
                ->exists();
                $session1CheckleaveType = LeaveRequest::where('emp_id', $employeeId)
                ->where('leave_applications.leave_status', 2)
                ->where('from_session', 'Session 1')
                ->where('to_session', 'Session 1')
                ->whereDate('from_date', '<=', $date)
                            ->whereDate('to_date', '>=', $date)
                ->value('leave_type');    
                $session2Check = LeaveRequest::where('emp_id', $employeeId)
                ->where('leave_applications.leave_status', 2)
                ->where('from_session', 'Session 2')
                ->where('to_session', 'Session 2')
                ->whereDate('from_date', '<=', $date)
                            ->whereDate('to_date', '>=', $date)
                ->exists();    
                $session2CheckleaveType = LeaveRequest::where('emp_id', $employeeId)
                ->where('leave_applications.leave_status', 2)
                ->where('from_session', 'Session 2')
                ->where('to_session', 'Session 2')
                ->whereDate('from_date', '<=', $date)
                            ->whereDate('to_date', '>=',  $date)
                ->value('leave_type');  
                
    
            if ($session1Check) {
                Log::info('Session Check Result:', [
                    'employee_id' => $employeeId,
                    'sessionCheck' => 'Session 1'
                ]);
            } elseif ($session2Check) {
                Log::info('Session Check Result:', [
                    'employee_id' => $employeeId,
                    'sessionCheck' => 'Session 2'
                ]);
            } else {
                Log::info('No session found for the employee:', [
                    'employee_id' => $employeeId
                ]);
            }
            
            if ($session1Check) {
                // Case when both sessions are 'Session 1'
                $leaveRecord = LeaveRequest::where('emp_id', $employeeId)
                    ->where('leave_applications.leave_status', 2)
                    ->where('from_session', 'Session 1')
                    ->where('to_session', 'Session 1')
                    ->where(function ($query) use ($date) {
                        $query->whereDate('from_date', '<=', $date)
                            ->whereDate('to_date', '>=', $date);
                    })
                    ->join('status_types', 'status_types.status_code', '=', 'leave_applications.leave_status')
                    ->exists();
                    $sessionArray[] = 'Session 1'; 
                Log::info('Leave Record for Session 1:', [
                    'employee_id' => $employeeId,
                    'date' => $date,
                    'leaveRecord' => $leaveRecord
                ]);
            }
            
            if($session2Check) {
                // Case when sessions are not both 'Session 1'
                $leaveRecord = LeaveRequest::where('emp_id', $employeeId)
                    ->where('leave_applications.leave_status', 2)
                    ->where('from_session', 'Session 2')
                    ->where('to_session', 'Session 2')
                    ->where(function ($query) use ($date) {
                        $query->whereDate('from_date', '<=', $date)
                            ->whereDate('to_date', '>=', $date);
                    })
                    ->join('status_types', 'status_types.status_code', '=', 'leave_applications.leave_status')
                    ->exists();
                    $sessionArray[] = 'Session 2'; 
                Log::info('Leave Record for Session 2:', [
                    'employee_id' => $employeeId,
                    'date' => $date,
                    'leaveRecord' => $leaveRecord,
                   'sessionCheck' => ($session1Check xor $session2Check xor !$isBeforeToDate) ? true : false,
                    'sessionCheckleaveType' => (($session1CheckleaveType) ? $session1CheckleaveType : $session2CheckleaveType),
                    'doubleSessionCheck'=>($session1Check && $session2Check) ? true : false,
                    'session1leaveType'=>($session1Check && $session2Check) ? $session1CheckleaveType : null,
                    'session2leaveType'=>($session1Check && $session2Check) ? $session2CheckleaveType : null,
                ]);
            }
    
         
            
            return [
                'session' => $sessionArray,
              
              
                'employee_id' => $employeeId,
                    'date' => $date,
                    'leaveRecord' => $leaveRecord,
                   'sessionCheck' => ($session1Check xor $session2Check xor !$isBeforeToDate) ? true : false,
                    'sessionCheckleaveType' => (($session1CheckleaveType) ? $session1CheckleaveType : $session2CheckleaveType),
                    'doubleSessionCheck'=>($session1Check && $session2Check) ? true : false,
                    'session1leaveType'=>($session1Check && $session2Check) ? $session1CheckleaveType : null,
                    'session2leaveType'=>($session1Check && $session2Check) ? $session2CheckleaveType : null,
    
            ];
        } catch (\Exception $e) {
            Log::error('Error in isEmployeeHalfDayLeaveOnDate method:', [
                'error_message' => $e->getMessage(),
                'employee_id' => $employeeId,
                'date' => $date
            ]);
    
            FlashMessageHelper::flashError('An error occurred while checking employee leave. Please try again later.');
    
            return [
                'session' => null,
                'leaveRecord' => false,
            ];
        }
    }
    private function isEmployeeLeaveOnDate($date, $employeeId)
    {
        try {
            $employeeId = $this->selectedEmployeeId;


            return LeaveRequest::where('emp_id', $employeeId)
            ->where('leave_applications.leave_status', 2)
            ->where('leave_applications.from_session', 'Session 1')
            ->where('leave_applications.to_session', 'Session 2')
            
            ->where(function ($query) use ($date) {
                $query->whereDate('from_date', '<=', $date)
                    ->whereDate('to_date', '>=', $date);
            })
            ->join('status_types', 'status_types.status_code', '=', 'leave_applications.leave_status') // Join with status_types
            ->exists();
        } catch (\Exception $e) {
            Log::error('Error in isEmployeeLeaveOnDate method: ' . $e->getMessage());
            session()->flash('error', 'An error occurred while checking employee leave. Please try again later.');
            return false; // Return false to handle the error gracefully
        }
    }
    private function detectEmployeeLeaveType($date, $employeeId)
    {
        try {
            $employeeId = $this->selectedEmployeeId;


            if($this->isEmployeeLeaveOnDate($date,$employeeId))
            {

                return LeaveRequest::where('emp_id', $employeeId)
                    ->where('leave_applications.leave_status', 2)
                    ->where(function ($query) use ($date) {
                        $query->whereDate('from_date', '<=', $date)
                            ->whereDate('to_date', '>=', $date);
                    })
                    ->join('status_types', 'status_types.status_code', '=', 'leave_applications.leave_status') // Join with status_types
                    ->value('leave_applications.leave_type');
            }
            elseif($this->isEmployeeHalfDayLeaveOnDate($date,$employeeId)['sessionCheck']==true)
            {

                return LeaveRequest::where('emp_id', $employeeId)
                    ->where('leave_applications.leave_status', 2)
                    ->where(function ($query) use ($date) {
                        $query->whereDate('from_date', '<=', $date)
                            ->whereDate('to_date', '>=', $date);
                    })
                    ->join('status_types', 'status_types.status_code', '=', 'leave_applications.leave_status') // Join with status_types
                    ->value('leave_applications.leave_type');
            }
        } catch (\Exception $e) {
            Log::error('Error in isEmployeeLeaveOnDate method: ' . $e->getMessage());
            session()->flash('error', 'An error occurred while checking employee leave. Please try again later.');
            return false; // Return false to handle the error gracefully
        }
    }
    public function openlegend()
    {
        $this->legend=!$this->legend;
    }
    

    public function changeYear()
    {
        $this->start = Carbon::parse($this->start)->year($this->year)->format('Y-m-d');
        $this->end = Carbon::parse($this->end)->year($this->year)->format('Y-m-d');
    }
    public function toggleCaretDirectionForSession1()
    {
        $this->moveCaretLeftSession1 = !$this->moveCaretLeftSession1;
    }

    public function viewDetails($i)
    {
        $this->showAlertDialog = true;
        $this->dateforpopup = $i;
        $this->viewDetailsswiperecord = SwipeRecord::where('emp_id', $this->selectedEmployeeId)->whereDate('created_at', $this->dateforpopup)->get();
        $this->viewDetailsInswiperecord = SwipeRecord::where('emp_id', $this->selectedEmployeeId)->where('in_or_out', 'IN')->whereDate('created_at', $this->dateforpopup)->first();
       
        $this->viewDetailsOutswiperecord = SwipeRecord::where('emp_id', $this->selectedEmployeeId)->where('in_or_out', 'OUT')->whereDate('created_at', $this->dateforpopup)->first();
        
    }
    public function close()
    {
        $this->viewDetailsInswiperecord = null;
        $this->viewDetailsOutswiperecord = null;
        $this->showAlertDialog = false;
    }
    public function toggleCaretDirectionForSession2()
    {
        $this->moveCaretLeftSession2 = !$this->moveCaretLeftSession2;
    }
    private function isEmployeePresentOnDate($date)
    {
        try {
            $employeeId = $this->selectedEmployeeId;
            $intime=SwipeRecord::where('emp_id', $employeeId)->where('in_or_out','IN')->whereDate('created_at', $date)->exists();    


            return $intime;
        } catch (\Exception $e) {
            Log::error('Error in isEmployeePresentOnDate method: ' . $e->getMessage());
            FlashMessageHelper::flashError('An error occurred while checking employee presence. Please try again later.');
            return false; // Return false to handle the error gracefully
        }
    }
    private function isEmployeeRegularisedOnDate($date)
    {
        try {
            $employeeId = $this->selectedEmployeeId;
            return SwipeRecord::where('emp_id', $employeeId)->whereDate('created_at', $date)->where('is_regularized', 1)->exists();
        } catch (\Exception $e) {
            Log::error('Error in isEmployeePresentOnDate method: ' . $e->getMessage());
            FlashMessageHelper::flashError('An error occurred while checking employee presence. Please try again later.');
            return false; // Return false to handle the error gracefully
        }
    }
    public function render()
    {
        $this->todaysDate = date('Y-m-d');
        $employeeId = $this->selectedEmployeeId;
        $this->employeeIdForTable = $this->selectedEmployeeId;
        $this->swiperecord = SwipeRecord::where('emp_id', $employeeId)->where('is_regularized', 1)->get();
        $currentMonth = date('F');
        $currentYear = date('Y');
        $this->holiday = HolidayCalendar::pluck('date')
            ->toArray();
        
        $swipeRecords = SwipeRecord::where('emp_id', $this->selectedEmployeeId)->get();
        $groupedDates = $swipeRecords->groupBy(function ($record) {
            return Carbon::parse($record->created_at)->format('Y-m-d');
        });
       
        $this->distinctDates = $groupedDates->mapWithKeys(function ($records, $key) {
            $inRecord = $records->where('in_or_out', 'IN')->first();
            $outRecord = $records->where('in_or_out', 'OUT')->last();

            return [
                $key => [
                    'in' => "IN",
                    'first_in_time' => optional($inRecord)->swipe_time,
                    'last_out_time' => optional($outRecord)->swipe_time,
                    'out' => "OUT",
                ]
            ];
        });
        return view('livewire.hr-attendance-table');
    }
}

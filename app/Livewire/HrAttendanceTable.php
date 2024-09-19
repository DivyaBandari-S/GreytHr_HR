<?php

namespace App\Livewire;

use App\Models\EmployeeDetails;
use App\Models\HolidayCalendar;
use App\Models\LeaveRequest;
use App\Models\SwipeRecord;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

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

    public $employeeIdForTable;
    public $endDate;
    public $legend=true;
    public $showSR = false;

    public $country='-';

    public $dateforpopup;
    public $fromDate;

    public $toDate;
    public $city='-';

    public $postal_code='-';

    public $selectedEmployeeId;
    protected $listeners = [
        'update',
    ];


    public function mount($selectedEmployeeId)
    {
        // First initialize
        $this->selectedEmployeeId=$selectedEmployeeId;
        $this->year = Carbon::now()->format('Y');
        $this->start = Carbon::now()->year($this->year)->firstOfMonth()->format('Y-m-d');

        $this->end = Carbon::now()->year($this->year)->lastOfMonth()->format('Y-m-d');
        $this->fromDate=$this->start;
        $this->toDate=$this->end;
        $this->employeeDetails=EmployeeDetails::where('emp_id',$selectedEmployeeId)->first();
        $ip = request()->ip();
        // $location = GeoIP::getLocation($ip);
        // $lat = $location['lat'];
        // $lon = $location['lon'];
        // $this->country = $location['country'];
        // $this->city = $location['city'];
        // $this->postal_code = $location['postal_code'];
    }
  
    public function updatefromDate()
    {
        $this->fromDate=$this->fromDate;
    }
    public function updatetoDate()
    {
        $this->toDate=$this->toDate;
    }
    private function isEmployeeLeaveOnDate($date, $employeeId)
    {
        try {
            $employeeId = $this->selectedEmployeeId;


            return LeaveRequest::where('emp_id', $employeeId)
                ->where('status', 'approved')
                ->where(function ($query) use ($date) {
                    $query->whereDate('from_date', '<=', $date)
                        ->whereDate('to_date', '>=', $date);
                })
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


            return LeaveRequest::where('emp_id', $employeeId)
                ->where('status', 'approved')
                ->where(function ($query) use ($date) {
                    $query->whereDate('from_date', '<=', $date)
                        ->whereDate('to_date', '>=', $date);
                })
                ->value('leave_type');
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
    public function update($start, $end) 
    {
        $this->year = Carbon::parse($start)->format('Y');
        $this->start = $start;
        $this->end = $end;
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

    public function render()
    {
        $this->todaysDate = date('Y-m-d');
        $employeeId = $this->selectedEmployeeId;
        $this->employeeIdForTable = $this->selectedEmployeeId;
        $this->swiperecord = SwipeRecord::where('emp_id', $this->selectedEmployeeId)->where('is_regularised', 1)->get();
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

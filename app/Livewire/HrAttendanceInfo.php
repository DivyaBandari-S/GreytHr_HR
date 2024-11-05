<?php

namespace App\Livewire;

use App\Models\EmployeeDetails;
use App\Models\HolidayCalendar;
use App\Models\LeaveRequest;
use App\Models\RegularisationDates;
use App\Models\SwipeRecord;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Torann\GeoIP\Facades\GeoIP;

class HrAttendanceInfo extends Component
{
    public $searchEmployee=0;

    public $calendar;
    public $searchTerm = '';
    public $employees;

    public $selectedEmployeeId='';

    public $employeeBox=1;

    public $selectedEmployeeFirstName;

    public $year;

    public $month;
    public $selectedEmployeeLastName;

   
    public $currentDate2;
    public $hours;

    public $country;

    public $city;

    public $postal_code;
    public $totalWorkingPercentage;
    public $minutesFormatted;

    public $avgWorkHoursFromJuly = 0;
    public $last_out_time;

    public $percentageDifference;
    public $currentDate;
    public $date1;

    public $avgSignInTime;


    public $view_student_first_name;

    public $view_student_last_name;
    public $averageWorkHours;

    public $percentageOfWorkHrs;
    public $percentageOfWorkHours;
    public $CurrentDate;
    public $avgSignOutTime;

    public $swipe_records_count;
    public $clickedDate;
    public $currentWeekday;


    public $totalDays;
    
    public $selectedDate;
    public $shortFallHrs;
    public $work_hrs_in_shift_time;
    public $swipe_record;
    public $holiday;
    public $leaveApplies;
    public $first_in_time;
       public $currentDate2record;
    
    public $actualHours = [];
    public $firstSwipeTime;
    public $secondSwipeTime;
    public $swiperecords;
    public $currentDate1;

    public $showCalendar = true;
    public $date2;
    public $modalTitle = '';

    public $countofAbsent;
    public $view_student_swipe_time;
    public $view_student_in_or_out;
    public $swipeRecordId;

    public $swiperecordsfortoggleButton;
    public $swiperecord;
    public $from_date;
    public $to_date;
    public $status;
    public $dynamicDate;
    public $view_student_emp_id;
    public $view_employee_swipe_time;
    public $currentDate2recordexists;


    public $defaultfaCalendar = 1;
    public $dateclicked;
    public $view_table_in;

    public $count;
    public $view_table_out;
    public $employeeDetails;
    public $changeDate = 0;
    public $student;
    public $selectedRecordId = null;

    
    public $regularised_by;

    public $regularised_date;

    public $regularised_reason;

    public $regularised_date_to_check;

    public $avgWorkingHrsForModalTitle;
    public $legend = true;
    public $isNextMonth = 0;
    public $record;

    public $dateToCheck;

    public $Swiperecords;
    public $employeeId;



    public $employeeIdForRegularisation;

   
    public $totalDurationFormatted;

    public $avgDurationFormatted;
    public $öpenattendanceperiod = false;

    public $averageFormattedTime = '00:00';
    public $totalDurationFormatted1;
    public $errorMessage;
    public $showRegularisationDialog = false;
    public $distinctDates;
    public $isPresent;
    public $table;
    public $previousMonth;
    public $session1 = 0;
    public $session2 = 0;
    public $session1ArrowDirection = 'right';
    public $session2ArrowDirection = 'right';

    public $averageHoursWorked;

    public $totalcount = 0;

    public $employeeType = 'all';
    public $averageMinutesWorked;
    public $avgSwipeInTime = null;
    public $avgSwipeOutTime = null;
    public $totalmodalDays;

    public $avgWorkHoursPreviousMonth;
    public $averageworkhours;
    public $averageWorkHrsForCurrentMonth = null;
    public $averageFormattedTimeForCurrentMonth;
    public $holidayCountForInsightsPeriod;
    public $weekendDays = 0;
    public $daysWithRecords = 0;

    public $selectedOption = 'all'; 
    public $percentageinworkhrsforattendance;
    public $leaveTaken = 0;
    public $totalHoursWorked = 0;

    public $toggleButton = false;
    public $totalMinutesWorked = 0;
    public $avgWorkHours = 0;
    public $avgLateIn = 0;
    public $avgEarlyOut = 0;

    public $k, $k1;
    public $showMessage = false;

    public $employee;
    //This function will help us to toggle the arrow present in session fields

    public function mount()
    {
        
        $this->employee = EmployeeDetails::where('emp_id', $this->selectedEmployeeId)->select('emp_id', 'first_name', 'last_name', 'shift_type')->first();
        
        $this->selectedEmployeeId=$this->selectedEmployeeId;
        Log::info("Employee-ID:".$this->selectedEmployeeId);
            
          

    }

    
    
    

    
    //This function will help us to toggle the arrow present in session fields
    
    public  $averageWorkingHours, $percentageOfHoursWorked, $yearA, $monthA;

    


    

    //This function will help us to calculate the number of public holidays in a particular month
    protected function getPublicHolidaysForMonth($year, $month)
    {
        try {
            return HolidayCalendar::whereYear('date', $year)
                ->whereMonth('date', $month)
                ->get();
        } catch (\Exception $e) {
            Log::error('Error in getPublicHolidaysForMonth method: ' . $e->getMessage());
            session()->flash('error', 'An error occurred while fetching public holidays. Please try again later.');
            return collect(); // Return an empty collection to handle the error gracefully
        }
    }

    
    
    
    //This function will help us to check if the employee is on leave for this particular date or not
       
    //This function will help us to check the leave type of employee
   
   
    //This function will help us to create the calendar
   //This function will help us to check the details related to the particular date in the calendar
   
    //This function will help us to check whether the employee is absent 'A' or present 'P'
    public function updatesearchTerm()
    {
        $this->searchTerm=$this->searchTerm;
    }
 
    public function updateEmployeeType()
    {
        // Handle the change in employee type
        $this->employeeType = $this->employeeType;
        
       
        
    }
    public function getEmployeesByType()
    {
        $emptype=$this->employeeType;
        $query = EmployeeDetails::query();
        // Example logic to fetch employees based on the selected type
        switch ($emptype) {
            case 'current':
                $query->where('employee_status', 'active');
                break;
            case 'past':
                $query->where('employee_status', 'terminated')->orWhere('employee_status','resigned');
                break;
            case 'interns':
                $query->where('job_role', 'intern');
                break;
            default:
                // If "all" is selected, no additional filtering for status
                break;
        }
        if (!empty($this->searchTerm)) {
            $query->where(function ($query) {
                $query->where('first_name', 'like', '%' . $this->searchTerm . '%')
                      ->orWhere('last_name', 'like', '%' . $this->searchTerm . '%')
                      ->orWhere('emp_id', 'like', '%' . $this->searchTerm . '%');
            });
        }
    
        // Get the filtered employees
        return $query->get();
    
    }
    // This method runs when the selected employee type changes
   

   
    
    // private function calculateAvgWorkingHrs($employeeId)
    // {
    //     $currentDate = Carbon::now()->startOfMonth();
    //     $endDate = Carbon::now()->endOfMonth();
    //     $this->averageFormattedTime = '00:00';
    //     $standardWorkingMinutesPerDay = 9 * 60;
    //     $totalMinutesWorked = 0;  // Initialize total minutes worked
    //     $daysWithRecords = 0;
    //     while ($currentDate->lt($endDate)) {
    //         $SwipeInRecord = SwipeRecord::where('emp_id', $employeeId)->whereDate('created_at', $currentDate)->where('in_or_out', 'IN')->first();
    //         $SwipeOutRecord = SwipeRecord::where('emp_id', $employeeId)->whereDate('created_at', $currentDate)->where('in_or_out', 'OUT')->first();
    //         if ($SwipeInRecord && $SwipeOutRecord) {
    //             // Get the swipe times
    //             $swipeInTime = Carbon::parse($SwipeInRecord->swipe_time);
    //             $swipeOutTime = Carbon::parse($SwipeOutRecord->swipe_time);

    //             $timeDifferenceInMinutes = $swipeOutTime->diffInMinutes($swipeInTime);
    //             $workingHoursPercentage = ($timeDifferenceInMinutes / $standardWorkingMinutesPerDay) * 100;
    //             // Add the time difference to the total minutes worked
    //             $totalMinutesWorked += $timeDifferenceInMinutes;

    //             // Increment the count of days with records
    //             $daysWithRecords++;
    //             // echo " (" . round($workingHoursPercentage, 2) . "% of standard working hours)";
    //         }
    //         $currentDate->addDay();
    //     }
    //     if ($daysWithRecords > 0) {
    //         $averageMinutes = $totalMinutesWorked / $daysWithRecords;
    //         $averageHours = floor($averageMinutes / 60);
    //         $averageRemainingMinutes = $averageMinutes % 60;

    //         $this->averageFormattedTimeForCurrentMonth = sprintf('%02d:%02d', $averageHours, $averageRemainingMinutes);

    //         // Return or use the average formatted time

    //     }
    //     // $this->averageFormattedTime=$this->calculateAvgWorkHours()-$this->calculateAvgWorkHoursForPreviousMonth();
    //     $totalPossibleWorkingMinutes = $daysWithRecords * $standardWorkingMinutesPerDay;

    //     // Calculate the percentage of total minutes worked
    //     if ($totalPossibleWorkingMinutes > 0) {
    //         $this->totalWorkingPercentage = ($totalMinutesWorked / $totalPossibleWorkingMinutes) * 100;
    //     } else {
    //         $this->totalWorkingPercentage = 0;
    //     }
    // }
    
   
    public $show = false;
    public $show1 = false;
   
    public $showSR = false;
   
    public function searchforEmployee()
    {
          $this->searchEmployee=1;
       
    }
  
    public function updateselectedEmployee($empId)
    {
    
        $this->selectedEmployeeId=$empId;
        $this->selectedEmployeeFirstName= EmployeeDetails::where('emp_id',$empId)->value('first_name');
        $this->selectedEmployeeLastName= EmployeeDetails::where('emp_id',$empId)->value('last_name');
        $this->searchEmployee=0;
    }
    public function closeEmployeeBox()
    {
        $this->searchEmployee=0;
       
       
    }
    public function clearSelectedEmployee()
    {
        $this->selectedEmployeeId='';
        $this->selectedEmployeeFirstName='';
        $this->selectedEmployeeLastName='';
        $this->searchTerm='';
    }
   
    public function render()
    {
        $this->employees = $this->getEmployeesByType();
      
     
      
        $employeeId = $this->selectedEmployeeId;
       
       

       
       
        return view('livewire.hr-attendance-info',[
            
        ]);
    }
}

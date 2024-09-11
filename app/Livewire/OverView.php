<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\EmployeeDetails;
use Carbon\Carbon;
use App\Models\SwipeRecord;


class OverView extends Component
{
    public $employeeCounts = [];
    public $months = [];
    public $employees;
    public $Employees;
    public $newJoiners;
    public $employeesWithAnniversaries;
    public $employeesbirthdays;
    public $inactiveEmployees;
    public $data = [];

    public function mount()
    {
        // Fetch employees who joined last month
        $this->newJoiners = EmployeeDetails::whereBetween('hire_date', [
            Carbon::now()->startOfMonth()->subMonth()->format('Y-m-d'),
            Carbon::now()->subMonth()->endOfMonth()->format('Y-m-d')
        ])->get();

        // Fetch employees with anniversaries this month
        $currentDate = Carbon::now();
        $this->employeesWithAnniversaries = EmployeeDetails::whereMonth('hire_date', '=', $currentDate->month)
        ->where('employee_status', 'active')
        ->get()
        ->map(function ($employee) use ($currentDate) {
            // Get the hire date
            $hireDate = Carbon::createFromFormat('Y-m-d', $employee->hire_date);

            // Calculate the difference in years
            $anniversaryYears = $currentDate->diffInYears($hireDate);
            //dd($anniversaryYears);
             // If the hire date is in the future, it's not an anniversary yet
            if ($currentDate->lt($hireDate->addYears($anniversaryYears))) {
                $anniversaryYears--;
            }

            // Set the anniversary years for the employee
            $employee->anniversary_years = $anniversaryYears;

            return $employee;
        });
        //dd( $this->employeesWithAnniversaries);
        $currentWeekStart = Carbon::now()->startOfWeek();
        $currentWeekEnd = Carbon::now()->endOfWeek();
        
        $this->employeesbirthdays = EmployeeDetails::whereMonth('date_of_birth', Carbon::now()->month)
        ->whereDay('date_of_birth', '>=', $currentWeekStart->day)
        ->whereDay('date_of_birth', '<=', $currentWeekEnd->day)
        ->where('employee_status', 'active')
        ->get();
        //dd($this->employeesbirthdays );
        $this->inactiveEmployees = EmployeeDetails::where('employee_status', '=', 'resigned')
        ->get();
        //dd($inactiveEmployees);
        $this->fetchSwipeChartData();
    }


    public function fetchSwipeChartData()
    {
        $lastMonthStart = now()->subMonth()->startOfMonth();
        $lastMonthEnd = now()->subMonth()->endOfMonth();
        $currentMonthStart = now()->startOfMonth();
        $currentMonthEnd = now()->endOfMonth();

        // Fetch data for last month
        $lastMonthData = SwipeRecord::where('in_or_out', 'IN')
            ->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])
            ->get();

        // Fetch data for current month
        $currentMonthData = SwipeRecord::where('in_or_out', 'IN')
            ->whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])
            ->get();

        // Initialize counts arrays
        $lastMonthCounts = array_fill(1, $lastMonthEnd->day, 0);
        $currentMonthCounts = array_fill(1, $currentMonthEnd->day, 0);

        // Prepare data for last month
        foreach ($lastMonthData as $record) {
            $day = $record->created_at->day;
            $lastMonthCounts[$day]++;
        }

        // Prepare data for current month
        foreach ($currentMonthData as $record) {
            $day = $record->created_at->day;
            $currentMonthCounts[$day]++;
        }

        // Combine data for last and current month
        $this->data = [
            'lastMonth' => $lastMonthCounts,
            'currentMonth' => $currentMonthCounts,
        ];
    }
    
    public function render()
    {
        // Initialize arrays to store monthly counts and months
        $this->employeeCounts = [];
        $this->months = [];

        // Get the current month and year
        $currentDate = Carbon::now();
        $currentYear = $currentDate->year;
        $currentMonth = $currentDate->month;

        // Calculate the start date for tracking employees
        $startDate = Carbon::createFromDate($currentYear - 1, $currentMonth, 1)->startOfMonth();

        // Loop through each month
        while ($startDate->lessThanOrEqualTo($currentDate)) {
            // Calculate the end date for the current month
            $endDate = $startDate->copy()->endOfMonth();

            // Count active employees who were hired before or during the current month
            $count = EmployeeDetails::where('employee_status', 'active')
                ->where(function ($query) use ($startDate, $endDate) {
                    $query->where('created_at', '<=', $endDate)
                        ->orWhere('hire_date', '<=', $endDate);
                })
                ->count();

            // Add the count to the array
            $this->employeeCounts[] = $count;
            // Add the month to the array
            $this->months[] = $startDate->format('M Y');

            // Move to the next month
            $startDate->addMonth();
        }

        //dd($this->employeeCounts,$this->months);

        return view('livewire.over-view',['newJoiners'=>$this->newJoiners,'employeesWithAnniversaries'=>$this->employeesWithAnniversaries,
        'employeesBirthdays' =>$this->employeesbirthdays,'inactiveEmployees'=>$this->inactiveEmployees,
        'employeeCounts'=>$this->employeeCounts,'months' => $this->months,'data' => $this->data]);
    }
}






 

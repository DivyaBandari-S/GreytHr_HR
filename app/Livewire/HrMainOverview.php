<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\EmployeeDetails;
use Carbon\Carbon;
use App\Models\SwipeRecord;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Jenssegers\Agent\Agent;

class HrMainOverview extends Component
{
    public $employeeCounts = [];
    public $months = [];
    public $employees;
    public $Employees;
    public $newJoiners;
    public $employeesWithAnniversaries;
    public $employeesbirthdays;
    public $percentageChangeText;
    public $inactiveEmployees;
    public $confirmationDue;
    public $data = [];
    public $showHelp = false;
    public $startOfWeek;
    public $endOfWeek;
    public $mobileUsersCount;
    public $allEmpCount;
    public $hrRequestCount;
    public $hrRequestSolvedCount;
    public $hrRequestCountCurrentMonth;
    public $hrRequestCountPreviousMonth;


    public function mount()
    {
        // Fetch employees who joined last month
        $employeeId = auth()->guard('hr')->user()->emp_id;

        // Fetch the company_ids for the logged-in employee
        $companyIds = EmployeeDetails::where('emp_id', $employeeId)->value('company_id');

        // Check if companyIds is an array; decode if it's a JSON string
        $companyIdsArray = is_array($companyIds) ? $companyIds : json_decode($companyIds, true);
        $this->newJoiners = EmployeeDetails::where(function($query) use ($companyIdsArray) {
            foreach ($companyIdsArray as $companyId) {
                $query->orWhereJsonContains('company_id', $companyId);
            }
        })
        ->whereBetween('hire_date', [
            Carbon::now()->startOfMonth()->subMonth()->format('Y-m-d'),
            Carbon::now()->subMonth()->endOfMonth()->format('Y-m-d')
        ])
        
        ->whereIn('employee_status', ['active', 'on-probation'])
            ->get();


        // Fetch employees with anniversaries this month
        $currentDate = Carbon::now();
        $startOfWeekFormatted = $currentDate->format('Y-m-d');
        $endOfWeekFormatted = $currentDate->copy()->addDays(6)->format('Y-m-d');


        // Define the date range for the next month
        $today = Carbon::now();
        $startOfNextMonth = $today->copy()->addMonth()->startOfMonth();
        $endOfNextMonth = $today->copy()->addMonth()->endOfMonth();
        $this->confirmationDue = EmployeeDetails::where(function($query) use ($companyIdsArray) {
            foreach ($companyIdsArray as $companyId) {
                $query->orWhereJsonContains('company_id', $companyId);
            }
        })
        ->whereIn('employee_status', ['active', 'on-probation'])
            ->select(
                'employee_details.*',
                DB::raw('DATE_ADD(hire_date, INTERVAL probation_period DAY) AS probation_end_date')
            )
            ->havingRaw('probation_end_date BETWEEN ? AND ?', [$startOfNextMonth->toDateString(), $endOfNextMonth->toDateString()])
            ->get();
        $this->employeesWithAnniversaries = EmployeeDetails::where(function($query) use ($companyIdsArray) {
            foreach ($companyIdsArray as $companyId) {
                $query->orWhereJsonContains('company_id', $companyId);
            }
        })
        ->whereRaw('MONTH(hire_date) BETWEEN ? AND ?', [Carbon::parse($startOfWeekFormatted)->month, Carbon::parse($endOfWeekFormatted)->month])
            ->whereRaw('DAY(hire_date) BETWEEN ? AND ?', [Carbon::parse($startOfWeekFormatted)->day, Carbon::parse($endOfWeekFormatted)->day])
            ->whereIn('employee_status', ['active', 'on-probation'])
            ->get()
            ->map(function ($employee) use ($currentDate) {
                // Get the hire date
                $hireDate = Carbon::createFromFormat('Y-m-d', $employee->hire_date);

                // Calculate the difference in years
                $anniversaryYears = $currentDate->diffInYears($hireDate);

                // If the hire date is in the future, it's not an anniversary yet
                if ($currentDate->lt($hireDate->addYears($anniversaryYears))) {
                    $anniversaryYears--;
                }

                // Set the anniversary years for the employee
                $employee->anniversary_years = $anniversaryYears;

                return $employee;
            });

        // Dump and die (for debugging)

        // Calculate the start and end of the current week


        // Query to get employees with birthdays in the current week
        $this->employeesbirthdays = EmployeeDetails::join('emp_personal_infos', 'employee_details.emp_id', '=', 'emp_personal_infos.emp_id')
        ->where(function($query) use ($companyIdsArray) {
            foreach ($companyIdsArray as $companyId) {
                $query->orWhereJsonContains('company_id', $companyId);
            }
        })
            ->whereRaw('MONTH(emp_personal_infos.date_of_birth) BETWEEN ? AND ?', [Carbon::parse($startOfWeekFormatted)->month, Carbon::parse($endOfWeekFormatted)->month])
            ->whereRaw('DAY(emp_personal_infos.date_of_birth) BETWEEN ? AND ?', [Carbon::parse($startOfWeekFormatted)->day, Carbon::parse($endOfWeekFormatted)->day])
            ->whereIn('employee_details.employee_status', ['active','on-probation'])
            ->select('employee_details.*', 'emp_personal_infos.date_of_birth')
            ->get();

        // Fetch and log inactive employees
        $startOfPreviousMonth = Carbon::now()->subMonth()->startOfMonth()->format('Y-m-d');
        $endOfPreviousMonth = Carbon::now()->subMonth()->endOfMonth()->format('Y-m-d');
        $this->inactiveEmployees = EmployeeDetails::where(function($query) use ($companyIdsArray) {
            foreach ($companyIdsArray as $companyId) {
                $query->orWhereJsonContains('company_id', $companyId);
            }
        })
        ->whereIn('employee_status', ['active', 'on-probation'])
        ->select(
            'employee_details.*',
            DB::raw('DATE_ADD(resignation_date, INTERVAL notice_period DAY) AS effective_end_date') // Calculate effective end date
        )
        ->havingRaw('effective_end_date BETWEEN ? AND ?', [$startOfPreviousMonth, $endOfPreviousMonth]) // Filter based on effective end date
        ->get();

        // Fetch swipe chart data if applicable
        $this->fetchSwipeChartData();
        $this->allEmpCount = EmployeeDetails::where(function($query) use ($companyIdsArray) {
            foreach ($companyIdsArray as $companyId) {
                $query->orWhereJsonContains('company_id', $companyId);
            }
        })
        ->whereIn('employee_status', ['active', 'on-probation']) ->count();
        $this->calculateMobileUsers();
    }
    public function calculateMobileUsers()
    {
        $agent = new Agent();
        $employeeId = auth()->guard('hr')->user()->emp_id;

        // Fetch the company_ids for the logged-in employee
        $companyIds = EmployeeDetails::where('emp_id', $employeeId)->value('company_id');

        // Check if companyIds is an array; decode if it's a JSON string
        $companyIdsArray = is_array($companyIds) ? $companyIds : json_decode($companyIds, true);
        // Example: Assuming you have a way to fetch users
        $users = EmployeeDetails::where(function($query) use ($companyIdsArray) {
            foreach ($companyIdsArray as $companyId) {
                $query->orWhereJsonContains('company_id', $companyId);
            }
        })
        ->whereIn('employee_status', ['active', 'on-probation']) ->get(); // Fetch all users or use a query
        $mobileUsersCount = 0;

        foreach ($users as $user) {
            if ($agent->isMobile()) {
                $mobileUsersCount++;
            }
        }

        $this->mobileUsersCount = $mobileUsersCount;
    }
    public function hideHelp()
    {

        $this->showHelp = true;
    }
    public function showhelp()
    {
        $this->showHelp = false;
    }


    public function fetchSwipeChartData()
    {
        $lastMonthStart = now()->subMonth()->startOfMonth();
        $lastMonthEnd = now()->subMonth()->endOfMonth();
        $currentMonthStart = now()->startOfMonth();
        $currentMonthEnd = now()->endOfMonth();

        // Fetch data for last month
        // $lastMonthData = SwipeRecord::where('in_or_out', 'IN')
        //     ->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])
        //     ->get();
        $lastMonthData = SwipeRecord::select(
            'emp_id',
            DB::raw('DATE(created_at) as swipe_date'),
            DB::raw('MIN(CASE WHEN in_or_out = "IN" THEN swipe_time END) as first_signin'),
            DB::raw('MAX(CASE WHEN in_or_out = "OUT" THEN swipe_time END) as last_signout')
        )
            ->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])
            ->groupBy('emp_id', DB::raw('DATE(created_at)'))
            ->havingRaw('first_signin IS NOT NULL AND last_signout IS NOT NULL')
            ->get();



        // Fetch data for current month
        $currentMonthData = SwipeRecord::select(
            'emp_id',
            DB::raw('DATE(created_at) as swipe_date'),
            DB::raw('MIN(CASE WHEN in_or_out = "IN" THEN swipe_time END) as first_signin'),
            DB::raw('MAX(CASE WHEN in_or_out = "OUT" THEN swipe_time END) as last_signout')
        )
            ->whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])
            ->groupBy('emp_id', DB::raw('DATE(created_at)'))
            ->havingRaw('first_signin IS NOT NULL AND last_signout IS NOT NULL')
            ->get();


        // Initialize counts arrays
        $lastMonthCounts = array_fill(1, $lastMonthEnd->day, 0);
        $currentMonthCounts = array_fill(1, $currentMonthEnd->day, 0);

        foreach ($lastMonthData as $record) {
            $day = \Carbon\Carbon::parse($record->swipe_date)->day;
            if (!isset($lastMonthCounts[$day])) {
                $lastMonthCounts[$day] = 0;
            }
            $lastMonthCounts[$day]++;
        }

        foreach ($currentMonthData as $record) {
            // Extract the day from swipe_date
            $day = \Carbon\Carbon::parse($record->swipe_date)->day;

            // Initialize the count for this day if not already set
            if (!isset($currentMonthCounts[$day])) {
                $currentMonthCounts[$day] = 0;
            }

            // Increment the count for this day
            $currentMonthCounts[$day]++;
        }
        $daysOfInterest = [5, 10, 15, 20, 25, 30];

        // Extract counts for the days of interest
        function calculateAverageCount($counts, $daysOfInterest)
        {
            $filteredCounts = [];
            foreach ($daysOfInterest as $day) {
                if (isset($counts[$day])) {
                    $filteredCounts[] = $counts[$day];
                }
            }

            // Calculate the average count
            return !empty($filteredCounts) ? array_sum($filteredCounts) / count($filteredCounts) : 0;
        }

        // Calculate averages for current and last month
        $currentMonthAverage = calculateAverageCount($currentMonthCounts, $daysOfInterest);
        $lastMonthAverage = calculateAverageCount($lastMonthCounts, $daysOfInterest);

        function calculatePercentageChange($current, $previous)
        {
            if ($previous == 0) {
                return ($current > 0) ? 100 : 0; // Handle the case where previous month average is zero
            }

            return (($current - $previous) / $previous) * 100;
        }

        // Calculate percentage change
        $percentageChange = calculatePercentageChange($currentMonthAverage, $lastMonthAverage);

        // Format the percentage change for display
        $percentageChangeFormatted = number_format($percentageChange, 2);
        // $percentageChangeSign = ($percentageChange >= 0) ? '+' : '-';
        $percentageChangeText = "{$percentageChangeFormatted}%";

        // Output the average count


        // Filter the days to include only 5, 10, 15, 20, 25, 30



        // Define specific days of interest
        // $specificDays = [5, 10, 15, 20, 25, 30];

        // // Initialize counts arrays for specific days
        // $lastMonthCounts = array_fill_keys($specificDays, 0);
        // $currentMonthCounts = array_fill_keys($specificDays, 0);

        // // Process last month data
        // foreach ($lastMonthData as $record) {
        //     $day = \Carbon\Carbon::parse($record->swipe_date)->day;


        //     // Count only if the day is in the specific days list
        //     if (in_array($day, $specificDays)) {

        //         $lastMonthCounts[$day]++;

        //     }
        // }

        // // Process current month data
        // foreach ($currentMonthData as $record) {
        //     $day = \Carbon\Carbon::parse($record->swipe_date)->day;

        //     // Count only if the day is in the specific days list
        //     if (in_array($day, $specificDays)) {
        //         $currentMonthCounts[$day]++;
        //     }
        // }

        // Combine data for last and current month
        $this->data = [
            'lastMonth' => $lastMonthCounts,
            'currentMonth' => $currentMonthCounts,
            'percentageChangeText' => $percentageChangeText
        ];
    }

    public function render()
    {
        $employeeId = auth()->guard('hr')->user()->emp_id;
       


        // Fetch the company_ids for the logged-in employee
        $companyIds = EmployeeDetails::where('emp_id', $employeeId)->value('company_id');

        // Check if companyIds is an array; decode if it's a JSON string
        $companyIdsArray = is_array($companyIds) ? $companyIds : json_decode($companyIds, true);
        // Initialize arrays to store monthly counts and months
        $this->employeeCounts = [];
        $this->months = [];

        // Get the current month and year
        $currentDate = Carbon::now();
        $currentYear = $currentDate->year;
        $currentMonth = $currentDate->month;

        $categories = [
            'Employee Information',
            'Hardware Maintenance',
            'Incident Report',
            'Privilege Access Request',
            'Security Access Request',
            'Technical Support'
        ];
        $endDate = Carbon::now()->endOfMonth(); // End of the current month
        $startDate = Carbon::now()->subMonths(2)->startOfMonth(); // Start of the month 3 months ago
        $currentMonthStart = Carbon::now()->startOfMonth(); // Start of the current month
        $currentMonthEnd = Carbon::now()->endOfMonth(); // End of the current month

        $previousMonthStart = Carbon::now()->subMonth()->startOfMonth(); // Start of the previous month
        $previousMonthEnd = Carbon::now()->subMonth()->endOfMonth();

        // Fetch the records within the date range and category list
        // $this->hrRequestCount = DB::table('help_desks')
        //     ->whereIn('category', $categories)
        //     ->whereBetween('created_at', [$startDate, $endDate])
        //     ->count();

        // Count for the current month
        // $this->hrRequestCountCurrentMonth = DB::table('help_desks')
        //     ->whereIn('category', $categories)
        //     ->whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])
        //     ->count();

        // Count for the previous month
        // $this->hrRequestCountPreviousMonth = DB::table('help_desks')
        //     ->whereIn('category', $categories)
        //     ->whereBetween('created_at', [$previousMonthStart, $previousMonthEnd])
        //     ->count();
        //     $percentageChange = $this->calculatePercentageChange(
        //         $this->hrRequestCountCurrentMonth,
        //         $this->hrRequestCountPreviousMonth
        //     );

            // Format percentage change
        //     $percentageChangeFormatted = number_format(abs($percentageChange), 2); // Absolute value for formatting

        //     $percentageChangeText = "{$percentageChangeFormatted}%";

        // $this->hrRequestSolvedCount = DB::table('help_desks')
        //     ->whereIn('category', $categories)
        //     ->whereBetween('created_at', [$startDate, $endDate])
        //     ->where('status', 'Completed')
        //     ->count();



        // Calculate the start date for tracking employees
        $startDate = Carbon::createFromDate($currentYear - 1, $currentMonth + 1, 1)->startOfMonth();
    


        // Loop through each month
        while ($startDate->lessThanOrEqualTo($currentDate)) {
            // Calculate the end date for the current month
            $endDate = $startDate->copy()->endOfMonth();
           


            // Count active employees who were hired before or during the current month
            $count = EmployeeDetails::where(function($query) use ($companyIdsArray) {
                foreach ($companyIdsArray as $companyId) {
                    $query->orWhereJsonContains('company_id', $companyId);
                }
            })
            ->whereIn('employee_status', ['active', 'on-probation']) 
                ->where(function ($query) use ($startDate, $endDate) {
                    $query->orWhere('hire_date', '<=', $endDate);
                })
                ->count();


            // Add the count to the array
            $this->employeeCounts[] = $count;
            // Add the month to the array
            $this->months[] = $startDate->format('M Y');

            // Move to the next month
            $startDate->addMonth();
        }
        $maxEmployeeCount = max($this->employeeCounts);
        $roundedMaxEmployeeCount = ceil($maxEmployeeCount / 41) * 41;

        //dd($this->employeeCounts,$this->months);

        return view('livewire.hr-main-overview', [
            'newJoiners' => $this->newJoiners,
            'employeesWithAnniversaries' => $this->employeesWithAnniversaries,
            'employeesBirthdays' => $this->employeesbirthdays,
            'inactiveEmployees' => $this->inactiveEmployees,
            'confirmationDue' => $this->confirmationDue,
            'employeeCounts' => $this->employeeCounts,
            // 'hrRequestCount' => $this->hrRequestCount,
            // 'hrRequestSolvedCount' => $this->hrRequestSolvedCount,
            'months' => $this->months,
            'mobileUsersCount' => $this->mobileUsersCount,
            'data' => $this->data,
            'percentageChangeText' =>$this->percentageChangeText,
            'maxEmployeeCount' => $roundedMaxEmployeeCount
        ]);
    }
    private function calculatePercentageChange($current, $previous)
    {
        if ($previous == 0) {
            return ($current > 0) ? 100 : 0; 
        }
    
        return (($current - $previous) / $previous) * 100;
    }
}

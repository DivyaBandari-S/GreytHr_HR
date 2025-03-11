<?php

namespace App\Livewire;

use App\Helpers\FlashMessageHelper;
use App\Helpers\LeaveHelper;
use App\Models\EmployeeDetails;
use App\Models\EmployeeLeaveBalances;
use App\Models\EmployeeLopDays;
use App\Models\EmpSalaryRevision;
use App\Models\EmpSeparationDetails;
use App\Models\LeaveRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class FinalSettlementStepper extends Component
{
    public $isShowHelp = true;
    public $step = 1;
    public $selectedEmployeeType = 'separated_employee';
    public $separatedEmployees = [];
    public $selectedEmployee = null;
    public $activeButton = 'General';
    public $employeeType = 'active';
    public $showContainer = false;
    public $showSearch = true;
    public $search = null;
    public $employees;
    public $separationDetails;
    public $resignation_submitted_on;
    public $leaving_date;
    public $leaving_reason;
    public $settlement_date;
    public $notice_required = false;
    public $notice_period = 0;
    public $served_days = 0;
    public $workingDaysData;
    public $leavesData;
    public $assetDetails;
    public $lastPaidDate;
    public $remarks;




    public function rules()
    {
        return match ($this->step) {
            2 => [
                'resignation_submitted_on' => 'required|date',
                'leaving_date' => 'required|date|after_or_equal:resignation_submitted_on',
                'leaving_reason' => 'required',
                'settlement_date' => 'required|string',
            ],
            3 => [
                'notice_period' => $this->notice_required ? 'required|numeric|min:1' : 'nullable|numeric',
                'served_days' => 'required|numeric',
            ],
            default => [
                'selectedEmployeeType' => 'nullable',
            ],
        };
    }


    public array $messages =
    [
        'resignation_submitted_on.required' => 'Resignation submission date is required.',
        'resignation_submitted_on.date' => 'Please enter a valid date for resignation submission.',

        'leaving_date.required' => 'Leaving date is required.',
        'leaving_date.date' => 'Please enter a valid date for leaving.',

        'leaving_reason.required' => 'Please select the reason for leaving.',

        'settlement_date.required' => 'The settlement date is required.',
        'settlement_date.string' => 'Please enter a valid date for settlement.',

        'notice_period.required' => 'Notice period is required when applicable.',
        'notice_period.numeric' => 'Notice period must be a valid number.',

        'served_days.required' => 'Number of served days is required.',
        'served_days.numeric' => 'Served days must be a valid number.',

    ];

    public function updated($propertyName)
    {
        // dd();
        // dd($propertyName);
        $this->validateOnly($propertyName);
    }



    public function toogleHelp()
    {
        $this->isShowHelp = !$this->isShowHelp;
    }
    public function changeRadio($type)
    {
        $this->selectedEmployee = null;
        $this->selectedEmployeeType = $type;

        if ($this->selectedEmployeeType == 'separated_employee') {
            $this->getSeperatedEmployees();
        }
    }

    public function getSeperatedEmployees()
    {
        $this->separatedEmployees = EmpSeparationDetails::join('employee_details', 'employee_details.emp_id', 'emp_separation_details.emp_id')
            ->where('settled_date', null)
            ->select('employee_details.emp_id', 'employee_details.first_name', 'employee_details.last_name')
            ->get()
            ->toArray();
    }


    public function hideContainer()
    {
        $this->showSearch = true;
        $this->showContainer = false;
    }
    public function filterEmployeeType()
    {
        $this->loadEmployees(); // Reload employees when type changes
    }
    public function loadEmployees()
    {

        if ($this->employeeType === 'active') {
            $this->employees = EmployeeDetails::whereIn('employee_status', ['active', 'on-probation'])->get();
        } else {
            $this->employees = EmployeeDetails::whereIn('employee_status', ['terminated', 'resigned'])->get();
        }
    }

    public function searchFilter()
    {
        if ($this->search !== '') {
            $this->showContainer = true; // Show the container when the search is triggered

            // Search with the existing term
            $this->employees = EmployeeDetails::where(function ($query) {
                $query->where('first_name', 'like', '%' . $this->search . '%')
                    ->orWhere('last_name', 'like', '%' . $this->search . '%')
                    ->orWhere('emp_id', 'like', '%' . $this->search . '%'); // Ensure `like` for partial match
            })
                ->where(function ($query) {
                    if ($this->employeeType === 'active') {
                        $query->whereIn('employee_status', ['active', 'on-probation']);
                    } else {
                        $query->whereIn('employee_status', ['terminated', 'resigned']);
                    }
                })
                ->get();

            // If no results found, the container should still be shown to display the message

        } else {
            // If search term is empty, hide the container and reload the employees
            $this->showContainer = false; // Hide the container
            $this->loadEmployees(); // Reload current employees
        }
    }

    public function selectEmployee($employeeId)
    {
        // $this->reset();
        // Check if the employee is already selected
        $this->selectedEmployee = $employeeId; // Change here

        if (is_null($this->selectedEmployee)) {
            $this->showSearch = true;
            $this->search = null;
            $this->selectedEmployee = null;
        } else {
            $this->showSearch = false;
            $this->showContainer = false;
        }
    }

    public function getSeparationDetails()
    {

        $this->separationDetails = EmpSeparationDetails::where('emp_id', $this->selectedEmployee)
            ->where('settled_date', null)
            ->first();

        // dd( $this->separationDetails);

        if ($this->separationDetails) {
            $this->resignation_submitted_on = $this->separationDetails->resignation_submitted_on;
            $this->leaving_reason = $this->separationDetails->separation_mode;

            if ($this->separationDetails->leaving_date) {
                $this->leaving_date = $this->separationDetails->leaving_date;
            } else {
                $this->leaving_date = $this->separationDetails->tentative_date;
            }
            if ($this->leaving_date) {

                $this->getSettlementDate($this->leaving_date);
            }

            $this->notice_required = $this->separationDetails->notice_required == 1 ? true : false;

            if ($this->separationDetails->notice_period) {
                $this->notice_period = $this->separationDetails->notice_period;
            }
            if ($this->separationDetails->notice_period && $this->separationDetails->short_fall_notice_period != null) {
                // dd($this->separationDetails->short_fall_notice_period);
                $this->served_days = $this->separationDetails->notice_period - $this->separationDetails->short_fall_notice_period;
            }
        }
    }

    // public function updatedLeavingDate($value)
    // {
    //     // dd();
    //     // $this->getSettlementDate($value);

    // }

    public function getSettlementDate($leavingDate)
    {
        // dd();
        $this->settlement_date = Carbon::parse($leavingDate)->endofmonth()->toDateString();
    }

    function calculatePaidDays($startDate, $endDate)
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        $data = [];
        $totalPaidDays = 0; // Variable to store the total paid days
        $currentDate = $start->copy()->startOfMonth(); // Start from the first day of the joining month

        while ($currentDate->lte($end)) {
            $monthYear = $currentDate->format('M Y');
            $lop_month = $currentDate->format('Y-m');
            // $daysInMonth = $currentDate->daysInMonth;
            $daysInMonth = 30;

            // Fetch loss of pay (LOP) days
            $lop_Days = EmployeeLopDays::where('emp_id', $this->selectedEmployee)
                ->where('payout_month', $lop_month)
                ->value('lop_days') ?? 0;

            if ($currentDate->isSameMonth($start) && $currentDate->isSameMonth($end)) {
                // Case: Start and End are in the same month
                $paidDays = $end->day - ($start->day - 1) - $lop_Days;
            } elseif ($currentDate->isSameMonth($start)) {
                // First month: Work starts mid-month
                $paidDays = $daysInMonth - ($start->day - 1) - $lop_Days;
            } elseif ($currentDate->isSameMonth($end)) {
                // Last month: Work ends mid-month
                $paidDays = $end->day - $lop_Days;
            } else {
                // Full month worked
                $paidDays = $daysInMonth - $lop_Days;
            }

            // Ensure paid days are not negative
            $paidDays = max(0, $paidDays);

            // Add to total paid days
            $totalPaidDays += $paidDays;

            // Store data
            $data[] = [
                'month' => $monthYear,
                'total_days' => $daysInMonth,
                'paid_days' => $paidDays
            ];

            // Move to next month
            $currentDate->addMonth();
        }

        // Return both the array of data and the total paid days
        return [
            'monthly_data' => $data,
            'total_paid_days' => $totalPaidDays
        ];
    }
    public function getTotalWorkDays()
    {
        // Recalculate total paid days
        $this->workingDaysData['total_paid_days'] = array_sum(array_column($this->workingDaysData['monthly_data'], 'paid_days'));
    }

    public function getTotalEncashDays()
    {
        // Only update leavesData.total_encash
        $this->leavesData['total_encash'] = array_sum(array_column($this->leavesData['leave_data'], 'encash'));
    }

    public function getEmployeesLEavesData($employee, $year)
    {
        $leaveTypes = [
            'Casual Leave' => 'totalCasualDays',
            'Casual Leave Probation' => 'totalCasualLeaveProbationDays',
            'Loss Of Pay' => 'totalLossOfPayDays',
            'Sick Leave' => 'totalSickDays',
            'Maternity Leave' => 'totalMaternityDays',
            'Marriage Leave' => 'totalMarriageDays',
            'Paternity Leave' => 'totalPaternityDays'
        ];

        $leaveData = [];
        $totalEncash = 0; // Variable to store the total encash

        // Fetch leave balances and leaves availed once to avoid redundant calls
        $allLeavesAvailed = LeaveHelper::getApprovedLeaveDays($employee, $year);

        foreach ($leaveTypes as $leave => $key) {
            $leavesGranted = EmployeeLeaveBalances::getLeaveBalancePerYear($employee, $leave, $year);
            $pendingLeaves = $leavesGranted - ($allLeavesAvailed[$key] ?? 0);
            $encash = 0; // Assuming encashment is initially 0, update logic if needed

            $leaveData[] = [
                'leave_type' => $leave,
                'pending_leaves' => max(0, $pendingLeaves), // Ensuring no negative values
                'encash' => $encash
            ];

            $totalEncash += $encash;
        }

        return [
            'leave_data' => $leaveData,
            'total_encash' => $totalEncash
        ];
    }




    public function nextStep()
    {
        $next_month_start = null;
        $join_date = null;
        $next_month_start = null;


        if ($this->step < 7) { // Adjust according to the number of steps

            // dd($this->selectedEmployee);
            if ($this->selectedEmployee == null) {
                FlashMessageHelper::flashError('Please Select The Employee');
                return;
            }

            if ($this->step == 1) {

                $this->lastPaidDate = null;
                $this->resignation_submitted_on = null;
                $this->leaving_reason = '';
                $this->leaving_date = null;
                $this->notice_required = false;
                $this->notice_period = 0;
                $this->served_days = 0;
                $this->getSeparationDetails();
            } elseif ($this->step == 2) {
                $this->validate();
            } elseif ($this->step == 3) {
                $join_date = EmployeeDetails::where('emp_id', $this->selectedEmployee)
                    ->value('hire_date');
                $this->lastPaidDate = EmpSalaryRevision::join('emp_salaries', 'emp_salaries.sal_id', '=', 'salary_revisions.id')
                    ->where('salary_revisions.emp_id', $this->selectedEmployee)
                    ->latest('emp_salaries.month_of_sal')
                    ->value('emp_salaries.month_of_sal'); // Use value() to directly get the column

                $next_month_start = $this->lastPaidDate
                    ? Carbon::parse($this->lastPaidDate)->addMonth()->startOfMonth()->format('Y-m-d')
                    : Carbon::parse($join_date)->format('Y-m-d');
                $this->workingDaysData = $this->calculatePaidDays($next_month_start, $this->leaving_date);
                // dd($this->workingDaysData);
            } elseif ($this->step == 4) {
                $year = Carbon::parse($this->leaving_date)->format('Y');
                $this->leavesData = $this->getEmployeesLEavesData($this->selectedEmployee, $year);
                // dd( $this->leavesData);
            } elseif ($this->step == 5) {

                $this->assetDetails = DB::table('assign_asset_emps')
                    ->leftjoin('employee_details', 'employee_details.emp_id', 'assign_asset_emps.emp_id')
                    ->leftJoin('asset_types_tables', 'assign_asset_emps.asset_type', 'asset_types_tables.id')
                    ->select('assign_asset_emps.*', 'asset_types_tables.asset_names', 'employee_details.first_name', 'employee_details.last_name')
                    ->where('assign_asset_emps.emp_id', $this->selectedEmployee)->get();
                // dd($this->assetDetails);

            }

            $this->step++;
        }
    }

    public function previousStep()
    {
        if ($this->step > 1) {
            $this->step--;
        }
    }
    public function mount()
    {

        $this->getSeperatedEmployees();
    }

    public function render()
    {
        $selectedEmployeesDetails = $this->selectedEmployee ?
            EmployeeDetails::where('emp_id', $this->selectedEmployee)->get() : [];

        return view('livewire.final-settlement-stepper', [
            'selectedEmployeesDetails' => $selectedEmployeesDetails,
        ]);
        // return view('livewire.final-settlement-stepper');
    }
}

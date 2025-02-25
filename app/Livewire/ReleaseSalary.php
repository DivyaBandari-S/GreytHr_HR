<?php

namespace App\Livewire;

use App\Helpers\FlashMessageHelper;
use App\Models\EmpBankDetail;
use App\Models\EmployeeDetails;
use App\Models\EmpSalary;
use App\Models\EmpSalaryRevision;
use App\Models\HoldSalaries;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class ReleaseSalary extends Component
{
    public $isShowHelp = true;
    public $isPageOne = true;
    public $showContainer = false;
    public $isConfirm = false;
    public $deleteId;
    public $employees = [];
    public $selectedEmployee = null;
    public $showSearch = true;
    public $search = '';
    public $searchtable = '';

    public $employeeType = 'active';
    public $empDetails;
    public $payout_month;
    public $remarks = '';
    public $holdedPayoutEmployees;
    public $allHoldedPayoutEmployees;
    public $isAlreadyHolded = false;
    public $selectedHoldReason = '';
    public $selectedReleaseReason = '';
    public $deleteEmpDetails;
    public $selectedHoldMonth;
    public $releaseSalary;
    public $isReleasedFilter =null;
    public $uniqueEmployees;
    public $uniquePayoutMonths;


    public $holdReasons = [
        'None' => 'None',
        'Full & Final Settlement' => 'Full & Final Settlement',
        'Others' => 'Others',
    ];

    protected function rules()
    {
        return [

            'selectedEmployee' => 'required',
            'selectedHoldMonth' => 'required',
            'releaseSalary' => 'required',
            'selectedReleaseReason' => 'required',
            'remarks' => ' nullable|min:20',
        ];
    }
    protected function messages()
    {
        return [
            'selectedEmployee.required' => ' Please select Employee.',
            'selectedHoldMonth.required' => ' Please select Hold Month.',
            'selectedReleaseReason.required' => ' Please select Release Reason.',
            'remarks.min' => 'Remarks should be greater than 20 characters.',
        ];
    }



    public function toogleHelp()
    {
        $this->isShowHelp = !$this->isShowHelp;
    }

    public function addHoldSalaryProcessing()
    {
        $this->isPageOne = ! $this->isPageOne;
        $this->selectedReleaseReason='';
        $this->remarks='';

        if ($this->isPageOne) {
            $this->getTableData();
        } else {
            $this->getUniqueEmployees();
        }
    }
    public function getUniqueEmployees()
    {
        $this->uniqueEmployees = $this->allHoldedPayoutEmployees
            ->filter(fn($employee) => $employee->is_released == 0)
            ->map(fn($employee) => [
                'emp_id' => $employee->emp_id,
                'first_name' => $employee->first_name,
                'last_name' => $employee->last_name
            ])
            ->unique('emp_id') // Ensure uniqueness based on emp_id
            ->values() // Reset array keys
            ->toArray();
            if($this->uniqueEmployees){
                $this->selectedEmployee = $this->uniqueEmployees[0]['emp_id'];
                $this->getPayoutMonths(null);
            }

    }

    public function getPayoutMonths($month)
    {

        $this->uniquePayoutMonths = $this->allHoldedPayoutEmployees
            ->filter(fn($employee) => $employee->emp_id == $this->selectedEmployee)
            ->filter(fn($employee) => $employee->is_released == 0)
            ->map(fn($employee) => [
                'emp_id' => $employee->emp_id,
                'payout_month' => $employee->payout_month,
                'hold_reason' => $employee->hold_reason,
                'salary' => $employee->payout,
            ])
            ->values() // Reset array keys
            ->toArray();
        // dd( $this->uniquePayoutMonths );
        // dd($this->uniquePayoutMonths);

            $this->selectedHoldMonth = $this->uniquePayoutMonths[0]['payout_month'];

        // dd( $this->selectedHoldMonth);
        $this->selectedHoldReason = $this->uniquePayoutMonths[0]['hold_reason'];
        $this->releaseSalary = $this->uniquePayoutMonths[0]['salary'];
        // dd($this->selectedHoldMonth ,  $this->selectedHoldReason,$this->releaseSalary);

    }
    public function getSalary()
    {
        $this->releaseSalary = $this->getPayoutDetails($this->selectedEmployee, $this->selectedHoldMonth);
    }

    public function releaseEmployeeSalary($emp_id, $payout_month, $hold_reason, $payout)
    {
        $this->selectedEmployee = $emp_id;
        $this->getPayoutMonths(null);
        $this->selectedHoldReason = $hold_reason;
        $this->releaseSalary = $payout;
        $this->selectedHoldMonth = $payout_month;
        $this->isPageOne = false;
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
        // Check if the employee is already selected
        $this->selectedEmployee = $employeeId; // Change here

        Log::info('Selected Employee: ', [$this->selectedEmployee]);
        if (is_null($this->selectedEmployee)) {
            $this->showSearch = true;
            $this->isAlreadyHolded = false;
            $this->search = '';
            $this->remarks = '';
            $this->selectedHoldReason = '';
            // $this->showContainer = true;
            // $this->showSearch = false;
            $this->selectedEmployee = null;
        } else {
            $this->showSearch = false;
            $this->showContainer = false;
            $this->getEmpDetails();
            $this->checkIsAlreadyHolded();
        }
    }

    public function checkIsAlreadyHolded()
    {
        $this->isAlreadyHolded = false;
        $stoppedPayoutEmployee = HoldSalaries::where('emp_id', $this->selectedEmployee)
            ->where('payout_month', $this->payout_month)->first();
        // dd( $stoppedPayoutEmployee);
        if ($stoppedPayoutEmployee) {
            $this->isAlreadyHolded = true;
        }
    }

    public function hideContainer()
    {
        $this->showSearch = true;
        $this->showContainer = false;
    }
    public function getEmpDetails()
    {
        $this->empDetails = DB::table('employee_details')
            ->join('emp_departments', 'emp_departments.dept_id', '=', 'employee_details.dept_id')
            ->where('employee_details.emp_id', $this->selectedEmployee)
            ->select('emp_departments.department', 'employee_details.job_role', 'employee_details.hire_date', 'employee_details.emp_id', 'employee_details.job_location', 'employee_details.first_name', 'employee_details.last_name')
            ->first();
        // dd( $this->empDetails);
    }

    public function mount()
    {
        $this->payout_month = $this->getPayoutMonth(null);
        $this->getTableData();
        // dd( $this->holdedPayoutEmployees);
    }
    public function getTableData()
    {
        // Define the base query
        $query = DB::table('hold_salaries')
            ->join('employee_details', 'employee_details.emp_id', '=', 'hold_salaries.emp_id')
            ->where('hold_salaries.status', 1)
            ->select('hold_salaries.*', 'employee_details.first_name', 'employee_details.last_name');

        // Store all records (without filters)
        $this->allHoldedPayoutEmployees = $query->get()->map(function ($employee) {
            $employee->payout = $this->getPayoutDetails($employee->emp_id, $employee->payout_month);
            return $employee;
        });


        // Store filtered records (with search & release filter)
        $this->holdedPayoutEmployees = (clone $query)
            ->when(isset($this->isReleasedFilter), function ($query) {

                $query->where('hold_salaries.is_released', $this->isReleasedFilter);
            })
            ->when($this->searchtable, function ($query) {
                $search = $this->searchtable;
                $query->where(function ($q) use ($search) {
                    $q->where('employee_details.first_name', 'LIKE', "%{$search}%")
                        ->orWhere('employee_details.last_name', 'LIKE', "%{$search}%")
                        ->orWhere('hold_salaries.payout_month', 'LIKE', "%{$search}%")
                        ->orWhere('hold_salaries.emp_id', 'LIKE', "%{$search}%")
                        ->orWhere('hold_salaries.hold_reason', 'LIKE', "%{$search}%");
                });
            })
            ->get()
            ->map(function ($employee) {
                $employee->payout = $this->getPayoutDetails($employee->emp_id, $employee->payout_month);
                return $employee;
            });
            if( $this->allHoldedPayoutEmployees){
                $this->getUniqueEmployees();
            }

    }


    public function getPayoutDetails($emp_id, $payoutMonth)
    {
        $salaryRevision = EmpSalaryRevision::where('emp_id', $emp_id)
            ->where('status', 1)
            ->where('payout_month', '<=', $payoutMonth)
            ->orderBy('payout_month', 'desc') // Get the latest payout month
            ->first();
        // dump(  $salaryRevision);

        return $salaryRevision ? floor($salaryRevision->revised_ctc / 12) : 0;
    }

    function getPayoutMonth($date = null)
    {
        $date = $date ? Carbon::parse($date) : Carbon::now(); // Default to current date

        // If today is on or after the 26th, payout is for the next month
        if ($date->day >= 26) {
            return $date->addMonth()->format('M Y'); // Next month
        }
        return $date->format('M Y'); // Current month
    }


    public function updatedRemarks()
    {
        $this->validate();
    }


    public function hideModel()
    {
        $this->isConfirm = false;
    }

    public function processSalaryRelease()
    {
        // dd( $this->selectedEmployee,$this->releaseSalary,$this->selectedReleaseReason,$this->selectedHoldReason);
        $this->validate();
        $this->isConfirm = true;
        // dd( $this->isConfirm);
    }

    public function confirmSalaryRevision()
    {
        try {

            $bankDetails = EmpBankDetail::where('emp_id', $this->selectedEmployee)
                ->where('status', 1)
                ->first();
            if ($bankDetails) {
                $bank_id = $bankDetails->id;
            } else {
                FlashMessageHelper::flashError('Bank Details not available for Selected Employee');
                return;
            }
            // dd($this->selectedHoldMonth);
            $payoutMonth = Carbon::parse($this->selectedHoldMonth)->format('Y-m');
            // $payoutMonth = Carbon::parse(2024-01)->format('Y-m');
            // dd( $payoutMonth);
            $salaryRevision = EmpSalaryRevision::where('emp_id', $this->selectedEmployee)
                ->where('status', 1)
                ->where('payout_month', '<=', $payoutMonth)
                ->orderBy('payout_month', 'desc') // Order by latest payout_month
                ->first(); // Get the latest record

            if ($salaryRevision) {
                $sal_id = $salaryRevision->id;
                $salary = floor($salaryRevision->revised_ctc / 12);
            } else {
                FlashMessageHelper::flashError("No salary revision found for the selected employee in the chosen month.");
                return;
            }
            $currentMonth = Carbon::now()->format('Y-m');
            $currentDay = Carbon::now()->day;

            if ($payoutMonth == $currentMonth && $currentDay < 25) {
                FlashMessageHelper::flashError("Salary release is not allowed before the 25th of the current month.");
                return;
            }

            $isSalaryCredited = EmpSalaryRevision::where('emp_id', $this->selectedEmployee)
                ->join('emp_salaries', 'emp_salaries.sal_id', 'salary_revisions.id')
                ->where('emp_salaries.month_of_sal', $payoutMonth)
                ->first();

            // dd($isSalaryCredited);
            if ($isSalaryCredited) {
                FlashMessageHelper::flashError("Salary Already Released For The Selected Month ");
                return;
            }

            $employeeHold=HoldSalaries::where('emp_id',$this->selectedEmployee)
            ->where('payout_month',$payoutMonth)
            ->first();
            $employeeHold->release_reason= $this->selectedReleaseReason;
            $employeeHold->release_month=$currentMonth;
            $employeeHold->release_remarks= $this->remarks;
            $employeeHold->is_released=1;
            $employeeHold->save();

            EmpSalary::create([
                'sal_id'=> $sal_id,
                'bank_id'=> $bank_id,
                'salary'=> $salary,
                'effective_date'=> Carbon::now(),
                'remarks'=>'',
                'month_of_sal' =>$payoutMonth,
            ]);
            FlashMessageHelper::flashSuccess("Salary Released Successfully For The Selected Month.");
            $this->getTableData();
            $this->isConfirm = false;
            $this->isPageOne=true;
        } catch (\Exception $e) { // Catch general exceptions

        }
    }

    public function render()
    {
        // dd($this->isConfirm);
        $selectedEmployeesDetails = $this->selectedEmployee ?
            EmployeeDetails::where('emp_id', $this->selectedEmployee)->get() : [];

        return view('livewire.release-salary', [
            'selectedEmployeesDetails' => $selectedEmployeesDetails,
        ]);
    }
}

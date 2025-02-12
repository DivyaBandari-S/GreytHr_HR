<?php

namespace App\Livewire;

use App\Helpers\FlashMessageHelper;
use App\Models\EmployeeDetails;
use App\Models\EmpSalaryRevision;
use App\Models\HoldSalaries as ModelsHoldSalaries;
use App\Models\StopSalaries;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class HoldSalaries extends Component

{
    public $isShowHelp = true;
    public $isPageOne = true;
    public $showContainer = false;
    public $isDelete = false;
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
    public $isAlreadyHolded = false;
    public $selectedHoldReason = '';
    public $deleteEmpDetails;

    public $holdReasons = [
        'None' => 'None',
        'Bank Account No. Not Available' => 'Bank Account No. Not Available',
        'On Notice Period' => 'On Notice Period',
        'Others' => 'Others',
    ];

    protected function rules()
    {
        return [

            'selectedHoldReason' => 'required',
            'remarks' => 'min:20',
        ];
    }
    protected function messages()
    {
        return [
            'selectedHoldReason.required' => ' Please select Hold Reason.',
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
        $this->isAlreadyHolded = false;
        $this->search = '';
        $this->selectedEmployee = null;
        $this->showSearch = true;
        if ($this->isPageOne) {
            $this->getTableData();
        }
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
        $stoppedPayoutEmployee = ModelsHoldSalaries::where('emp_id', $this->selectedEmployee)
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
    public function  getTableData()
    {

        $this->holdedPayoutEmployees = DB::table('hold_salaries')
            ->join('employee_details', 'employee_details.emp_id', '=', 'hold_salaries.emp_id')
            ->where('hold_salaries.status',1)
            ->select('hold_salaries.*', 'employee_details.first_name', 'employee_details.last_name')
            ->when($this->searchtable, function ($query) {
                $search = $this->searchtable;
                $query->where('employee_details.first_name', 'LIKE', "%{$search}%")
                    ->orWhere('employee_details.last_name', 'LIKE', "%{$search}%")
                    ->orWhere('hold_salaries.payout_month', 'LIKE', "%{$search}%")
                    ->orWhere('hold_salaries.emp_id', 'LIKE', "%{$search}%")
                    ->orWhere('hold_salaries.hold_reason', 'LIKE', "%{$search}%");
            })
            ->get()
            ->map(function ($employee) {
                // Add payout calculation for each employee
                $employee->payout = $this->getPayoutDetails($employee->emp_id);
                return $employee;
            });
    }

    public function getPayoutDetails($emp_id)
    {
        $selectedEmployeesPeers = EmpSalaryRevision::where('emp_id', $emp_id)
            ->latest('created_at')
            ->first();

        return $selectedEmployeesPeers ? floor($selectedEmployeesPeers->revised_ctc / 12) : 0;
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



    public function deleteHoldedEmployee($id)
    {
        $this->deleteId = $id;
        // dd( $this->deleteId);
        $stoppedEmployee = ModelsHoldSalaries::findorfail($this->deleteId);
        $this->deleteEmpDetails = EmployeeDetails::where('emp_id', $stoppedEmployee->emp_id)
            ->select('employee_details.emp_id', 'employee_details.first_name', 'employee_details.last_name')
            ->first();
            // dd(  $this->deleteEmpDetails);
            $this->isDelete=true;

    }
    public function confirmdeleteHoldedEmployee()
    {
        $stoppedEmployee = ModelsHoldSalaries::findorfail($this->deleteId);
        $stoppedEmployee->delete();
        FlashMessageHelper::flashSuccess(
            ucwords(strtolower($this->deleteEmpDetails->first_name)) . ' ' .
                ucwords(strtolower($this->deleteEmpDetails->last_name)) .
                ' (' . $this->deleteEmpDetails->emp_id . ') is successfully deleted from hold salary process list for payroll:' . ' ' . $this->payout_month
        );
        $this->getTableData();

        $this->isDelete=false;
    }
    public function hideModel(){
        $this->isDelete=false;
    }

    public function saveHoldProcessingSalary()
    {
        //   dd($this->selectedHoldReason);
        $this->validate();
        try {
            ModelsHoldSalaries::create([
                'emp_id' => $this->selectedEmployee,
                'payout_month' => $this->payout_month,
                'hold_reason' => $this->selectedHoldReason,
                'remarks' => $this->remarks
            ]);

            FlashMessageHelper::flashSuccess(
                'Salary payout put on hold for the Employee:' .
                    ucwords(strtolower($this->empDetails->first_name)) . ' ' .
                    ucwords(strtolower($this->empDetails->last_name)) .
                    ' (' . $this->empDetails->emp_id . ') for the Payroll:' . ' ' . $this->payout_month
            );

            $this->isPageOne = false;
            $this->getTableData();
            $this->selectedEmployee = null;
            $this->remarks = '';
            $this->selectedHoldReason = '';
            $this->showSearch = true;
            $this->search = '';

        } catch (null) {
        }
    }

    public function render()
    {
        $selectedEmployeesDetails = $this->selectedEmployee ?
            EmployeeDetails::where('emp_id', $this->selectedEmployee)->get() : [];

        return view('livewire.hold-salaries', [
            'selectedEmployeesDetails' => $selectedEmployeesDetails,
        ]);
    }
}

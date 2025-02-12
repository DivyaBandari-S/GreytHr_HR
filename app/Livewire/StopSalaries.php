<?php

namespace App\Livewire;

use App\Helpers\FlashMessageHelper;
use App\Models\EmployeeDetails;
use App\Models\StopSalaries as ModelsStopSalaries;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class StopSalaries extends Component
{
    public $isShowHelp = true;
    public $isPageOne = true;
    public $showContainer = false;
    public $employees = [];
    public $selectedEmployee = null;
    public $showSearch = true;
    public $search = '';
    public $employeeType = 'active';
    public $empDetails;
    public $payout_month;
    public $reason = '';
    public $stoppedPayoutEmployees;
    public $isAlreadyStopped = false;


    protected function rules()
    {
        return [
            'reason' => 'required|min:20',
        ];
    }
    protected function messages()
    {
        return [
            'reason.required' => 'Reason is Required.',
            'reason.min' => 'Reason should be greater than 20 characters.',
        ];
    }



    public function toogleHelp()
    {
        $this->isShowHelp = !$this->isShowHelp;
    }

    public function addStopSalaryProcessing()
    {
        $this->isPageOne = ! $this->isPageOne;
        $this->isAlreadyStopped = false;
        $this->search = '';
        $this->selectedEmployee = null;
        $this->showSearch = true;
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
            $this->isAlreadyStopped = false;
            $this->search = '';
            // $this->showContainer = true;
            // $this->showSearch = false;
            $this->selectedEmployee = null;
        } else {
            $this->showSearch = false;
            $this->showContainer = false;
            $this->getEmpDetails();
            $this->checkIsAlreadyStopped();
        }
    }

    public function checkIsAlreadyStopped()
    {
        $this->isAlreadyStopped = false;
        $stoppedPayoutEmployee = ModelsStopSalaries::where('emp_id', $this->selectedEmployee)
            ->where('payout_month', $this->payout_month)->first();
        // dd( $stoppedPayoutEmployee);
        if ($stoppedPayoutEmployee) {
            $this->isAlreadyStopped = true;
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
        // dd( $this->stoppedPayoutEmployees);
    }
    public function  getTableData()
    {

        $this->stoppedPayoutEmployees =  DB::table('stop_salaries')
            ->join('employee_details', 'employee_details.emp_id', '=', 'stop_salaries.emp_id')
            ->select('stop_salaries.*', 'employee_details.first_name', 'employee_details.last_name')
            ->get();
        // dd($this->stoppedPayoutEmployees);
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


    public function updatedReason()
    {

        $this->validate();
    }

    public function saveStopProcessingSalary()
    {
        $this->validate();

        try {
            ModelsStopSalaries::create([
                'emp_id' => $this->selectedEmployee,
                'payout_month' => $this->payout_month,
                'reason' => $this->reason
            ]);

            FlashMessageHelper::flashSuccess(
                ucwords(strtolower($this->empDetails->first_name)) . ' ' .
                    ucwords(strtolower($this->empDetails->last_name)) .
                    ' (' . $this->empDetails->emp_id . ') is excluded from payroll:' . ' ' . $this->payout_month
            );

            $this->isPageOne = true;
            $this->getTableData();
            $this->selectedEmployee = null;
            $this->reason = '';
            $this->showSearch = true;
            $this->search = '';
        } catch (null) {
        }
    }

    public function render()
    {
        $selectedEmployeesDetails = $this->selectedEmployee ?
            EmployeeDetails::where('emp_id', $this->selectedEmployee)->get() : [];

        return view('livewire.stop-salaries', [
            'selectedEmployeesDetails' => $selectedEmployeesDetails,
        ]);
    }
}

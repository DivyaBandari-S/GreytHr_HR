<?php

namespace App\Livewire;

use App\Helpers\FlashMessageHelper;
use App\Models\EmployeeDetails;
use App\Models\EmployeeLopDays as ModelsEmployeeLopDays;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class EmployeeLopDays extends Component
{
    public $isShowHelp = true;
    public $isPageOne = true;
    public $showContainer = false;
    public $searchtable = '';
    public $isAlreadyLopAdded = false;
    public $selectedEmployee = null;
    public $showSearch = true;
    public $isDelete = false;
    public $search = '';
    public $employees = [];
    public $empDetails;
    public $employeeType = 'active';
    public $remarks;
    public $lopDays;
    public $payout_month;
    public $lopEmployees;



    protected function rules()
    {
        return [

            'lopDays' => 'required|numeric|min:0.5|max:30',
            'remarks' => 'nullable|min:20',
        ];
    }
    protected function messages()
    {
        return [
            'lopDays.required' => ' Please Enter LOP Days.',
            'lopDays.min' => ' LOP Days must be greater than 0. ',
            'lopDays.max' => ' LOP Days must be less than 30.',
            'remarks.min' => 'Remarks should be greater than 20 characters.',
        ];
    }


    public function toogleHelp()
    {
        $this->isShowHelp = !$this->isShowHelp;
    }
    public function addLopDays()
    {
        $this->isPageOne = ! $this->isPageOne;
        $this->isAlreadyLopAdded = false;
        $this->search = '';
        $this->selectedEmployee = null;
        $this->showSearch = true;
        $this->lopDays = null;
        $this->remarks = null;
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
            $this->isAlreadyLopAdded = false;
            $this->search = '';
            // $this->showContainer = true;
            // $this->showSearch = false;
            $this->selectedEmployee = null;
        } else {
            $this->showSearch = false;
            $this->showContainer = false;
            $this->getEmpDetails();
            $this->checkIsLopAdded();
        }
    }
    public function checkIsLopAdded()
    {
        $this->isAlreadyLopAdded = false;
        $lopAddedEmployee = ModelsEmployeeLopDays::where('emp_id', $this->selectedEmployee)
            ->where('payout_month', $this->payout_month)
            ->first();
        if ($lopAddedEmployee) {
            $this->isAlreadyLopAdded = true;
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

    public function saveLopDays()
    {
        $this->validate();
        try {
            ModelsEmployeeLopDays::create([
                'emp_id' => $this->selectedEmployee,
                'lop_days' => $this->lopDays,
                'payout_month' => $this->payout_month,
                'remarks' => $this->remarks,
            ]);

            FlashMessageHelper::flashSuccess('LOP Days Added Successfully For [' . $this->selectedEmployee . '].');
            $this->addLopDays();
        } catch (Exception $e) {
            dd($e->getMessage());
            FlashMessageHelper::flashError('OOPS Something Went Wrong.');
        }
    }
    public function mount()
    {

        $this->payout_month = $this->getPayoutMonth(null);
        // dd( $this->payout_month);
        $this->getTableData();
    }
    public function  getTableData()
    {

        $this->lopEmployees = DB::table('employee_lop_days')
            ->join('employee_details', 'employee_details.emp_id', '=', 'employee_lop_days.emp_id')
            // ->where('hold_salaries.status',1)
            ->select('employee_lop_days.*', 'employee_details.first_name', 'employee_details.last_name', 'employee_details.hire_date')
            ->when($this->searchtable, function ($query) {
                $search = $this->searchtable;
                $query->where('employee_details.first_name', 'LIKE', "%{$search}%")
                    ->orWhere('employee_details.last_name', 'LIKE', "%{$search}%")
                    ->orWhere('employee_lop_days.payout_month', 'LIKE', "%{$search}%")
                    ->orWhere('employee_lop_days.emp_id', 'LIKE', "%{$search}%")
                    ->orWhere('employee_lop_days.remarks', 'LIKE', "%{$search}%");
            })
            ->get();
    }
    public function deleteLopDays($id)
    {

        $record = ModelsEmployeeLopDays::findorfail($id);
        //  dd($record->payout_month);
        if ($record->payout_month == $this->payout_month && Carbon::now()->day <= 25) {
            // dd();
            $record->delete();
            $this->getTableData();
            return FlashMessageHelper::flashSuccess('Employee LOP Days Deleted Successfully!.');
        }
        return FlashMessageHelper::flashError('Previous Payout Month LOP Days Cannot Be Deleted.');
    }

    function getPayoutMonth($date = null)
    {
        $date = $date ? Carbon::parse($date) : Carbon::now(); // Default to current date
        // If today is on or after the 26th, payout is for the next month
        if ($date->day >= 26) {

            return $date->addMonth()->format('Y-m'); // Next month in YYYY-MM format
        }
        return $date->format('Y-m'); // Current month in YYYY-MM format
    }

    public function render()
    {
        $selectedEmployeesDetails = $this->selectedEmployee ?
            EmployeeDetails::where('emp_id', $this->selectedEmployee)->get() : [];
        return view('livewire.employee-lop-days', [
            'selectedEmployeesDetails' => $selectedEmployeesDetails,
        ]);
    }
}

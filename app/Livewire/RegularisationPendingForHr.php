<?php

namespace App\Livewire;

use App\Models\EmployeeDetails;
use App\Models\RegularisationDates;
use Livewire\Component;

class RegularisationPendingForHr extends Component
{
    public $id;

    public $emp_id;

    public $empName;

    public $regularisationrequest;

    public $ManagerId;
    public $employeeDetails;

    public $ManagerName;

    public $regularisationEntries;

    public $totalEntries;
    public function mount($id,$emp_id)
    {
        $this->id=$id;
        $this->emp_id=$emp_id;
        $this->empName = EmployeeDetails::where('emp_id', $this->emp_id)->first();
        $this->regularisationrequest = RegularisationDates::with('employee')->find($id);
        $subordinateEmpId=$this->regularisationrequest->emp_id;
        $this->employeeDetails = EmployeeDetails::where('emp_id', $subordinateEmpId)->first();
        $this->ManagerId=$this->regularisationrequest->employee->manager_id;
        $this->ManagerName=EmployeeDetails::select('first_name','last_name')->where('emp_id',$this->ManagerId)->first();
        $this->regularisationEntries = json_decode($this->regularisationrequest->regularisation_entries, true);
        $this->totalEntries = count($this->regularisationEntries);
    }
    public function render()
    {

        return view('livewire.regularisation-pending-for-hr');
    }
}

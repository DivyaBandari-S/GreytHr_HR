<?php

namespace App\Livewire;

use App\Models\EmployeeDetails;
use App\Models\LockConfiguration;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class CreateLockConfiguration extends Component
{
   
    public $categoryType;

    public $empName;
    public $lockBy = 'employeeFilter';
    public $toDate;
    public $fromDate;


    public $effectiveDate;

    public function mount()
    {
        $loggedInEmpID = auth()->guard('hr')->user()->emp_id;
        $this->empName=EmployeeDetails::where('emp_id',$loggedInEmpID) ->select('first_name', 'last_name')
        ->first();
        
    }
    public function updatecategoryType()
    {
         $this->categoryType=$this->categoryType;
    }
    public function updateLockBy()
    {
        $this->lockBy=$this->lockBy;
    }
    public function testPage()
    {
        dd($this->lockBy);
    }
    public function updatefromDate()
    {
        $this->fromDate=$this->fromDate;
    }
    public function updatetoDate()
    {
        $this->toDate=$this->toDate;
    }

    public function save()
    {
        dd('hii');
    }

    public function updateeffectiveDate()
    {
        $this->effectiveDate=$this->effectiveDate;
    }
    public function render()
    {
        return view('livewire.create-lock-configuration');
    }
}

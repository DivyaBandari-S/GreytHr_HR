<?php

namespace App\Livewire;

use App\Models\EmployeeDetails;
use App\Models\LockConfiguration;
use Livewire\Component;

class CreateNewLockConfigurationPage extends Component
{
    public $categoryType;

    public $attendanceCycle;
    public $employeeFilter='all_employees';
    public $fromDate;

    public $toDate;

    public $effectiveDate;

    public $empName;
    public $lockBy='employeeFilter';
    public function mount()
    {
        
            $loggedInEmpID = auth()->guard('hr')->user()->emp_id;
            $employee = EmployeeDetails::where('emp_id', $loggedInEmpID)
                ->select('first_name', 'last_name')
                ->first();

            if ($employee) {
                $this->empName = $employee->first_name . ' ' . $employee->last_name;
            } else {
                $this->empName = 'Unknown'; // Default value if employee not found
            }
     
    }
    public function updated($propertyName)
{
    $rules = [
        'categoryType' => 'required',
        'fromDate' => 'required|date',
        'toDate' => 'required|date|after:fromDate',
        'effectiveDate' => [
            'required',
            'date',
            function ($attribute, $value, $fail) {
                if ($value <= $this->fromDate || $value <= $this->toDate) {
                    $fail('Effective Date must be greater than both From Date and To Date.');
                }
            },
        ],
    ];

    // **Apply validation if lockBy is 'attendanceCycle'**
    if ($this->lockBy === 'attendanceCycle') {
        $rules['attendanceCycle'] = 'required';
    }

    $this->validateOnly($propertyName, $rules);
}

    public function save()
    {
        $rules = [
            'categoryType' => 'required',
            'fromDate' => 'required|date',
            'toDate' => 'required|date|after:fromDate',
            'effectiveDate' => [
                'required',
                'date',
                function ($attribute, $value, $fail) {
                    if ($value <= $this->fromDate || $value <= $this->toDate) {
                        $fail('Effective Date must be greater than both From Date and To Date.');
                    }
                },
            ],
        ];
        
        // **Apply validation if lockBy is 'attendanceCycle'**
        if ($this->lockBy === 'attendanceCycle') {
            $rules['attendanceCycle'] = 'required';
        }
        
        // Apply validation
        $this->validate($rules);
        $criteriaName = $this->lockBy === 'employeeFilter' ? $this->employeeFilter : $this->attendanceCycle ;
        LockConfiguration::create([
            
            'from_date' => $this->fromDate,
            'to_date' => $this->toDate,
            'category' => $this->categoryType,
            'effective_date'=>$this->effectiveDate,
            'lock_criteria'=>$this->lockBy,
            'criteria_name'=>$criteriaName,
            'updated_by'=> $this->empName ,
            'updated_lock_at'=>now(), 
        ]);
        return redirect()->route('attendance-lock-configuration');
    }
    public function updateemployeeFilter()
    {
        $this->employeeFilter=$this->employeeFilter;
    }

    public function updateattendanceCycle()
    {
        $this->attendanceCycle=$this->attendanceCycle;
    }
    public function updatecategoryType()
    {
         $this->categoryType=$this->categoryType;
    }
    public function updateLockBy()
    {
        $this->lockBy=$this->lockBy;
    }
    public function updatefromDate()
    {
        $this->fromDate=$this->fromDate;
    }
    public function updatetoDate()
    {
        $this->toDate=$this->toDate;
    }

    public function updateeffectiveDate()
    {
        $this->effectiveDate=$this->effectiveDate;
    }
    public function cancel()
    {
        
        $this->fromDate='';
        $this->toDate='';
        $this->effectiveDate='';
        $this->lockBy='employeeFilter';
        $this->attendanceCycle='';
        $this->categoryType='';
        return redirect()->route('attendance-lock-configuration');
    }
    public function render()
    {
        return view('livewire.create-new-lock-configuration-page');
    }
}

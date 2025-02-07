<?php

namespace App\Livewire;

use App\Helpers\FlashMessageHelper;
use App\Models\EmployeeDetails;
use App\Models\EmployeeWeekDay;
use Livewire\Component;

class CreateEmployeeWeekDayChart extends Component
{
    public $searchEmployee=0;

    public $mondaystatus='default';
    public $sundaystatus='default';
    public $selectedEmployeeFirstName;

    public $fridaystatus='default';

    public $employeeId;

    public $employeeFirstName;

    public $employeeLastName;

    public $employeeFromDate;

    public $employeeToDate;

    public $employee_sunday;

    public $employee_monday;

    public $employee_tuesday;

    public $employee_wednesday;

    public $employee_thursday;

    public $employee_friday;

    public $employee_saturday;

    public $saturdaystatus='default';
    public $tuesdaystatus='default';

    public $thursdaystatus='default';
    public $wednesdaystatus='default';
    public $selectedEmployeeLastName;
    public $selectedEmployeeId;

    public $from_date;

    public $to_date;
    public $employees;
    public $searchTerm = '';

    
    protected $rules = [
        'from_date' => 'required|date',
        'to_date' => 'required|date|after_or_equal:from_date',
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }
    public function searchforEmployee()
    {
          $this->searchEmployee=1;
       
    }

    public function updatesundaystatus()
    {
        $this->sundaystatus=$this->sundaystatus;
    }

    public function updatetuesdaystatus()
    {
        $this->tuesdaystatus=$this->tuesdaystatus;
    }
    public function updatemondaystatus()
    {
        $this->mondaystatus=$this->mondaystatus;
    }

    public function updateSaturdaystatus()
    {
        $this->saturdaystatus=$this->saturdaystatus;
    }
    public function updatewednesdaystatus()
    {
        $this->wednesdaystatus=$this->wednesdaystatus;
    }

    public function updatethursdaystatus()
    {
        $this->thursdaystatus=$this->thursdaystatus;
    }

    public function updatefridaystatus()
    {
        $this->fridaystatus=$this->fridaystatus;
    }
    public function updatesearchTerm()
    {
        $this->searchTerm=$this->searchTerm;
    }

    public function updatefromDate()
    {
        $this->from_date=$this->from_date;
    }
    public function updatetoDate()
    {
        $this->to_date=$this->to_date;
    }
    public function closeEmployeeBox()
    {
        $this->searchEmployee=0;
       
       
    }
    public function updateselectedEmployee($empId)
    {
    
        $this->selectedEmployeeId=$empId;
      
        $this->selectedEmployeeFirstName= EmployeeDetails::where('emp_id',$empId)->value('first_name');
        $this->selectedEmployeeLastName= EmployeeDetails::where('emp_id',$empId)->value('last_name');
        $this->searchEmployee=0;
    }
    public function getEmployeesByType()
    {
       
        $query = EmployeeDetails::query();
        // Example logic to fetch employees based on the selected type
        $query->where('employee_status', 'active');
        if (!empty($this->searchTerm)) {
            $query->where(function ($query) {
                $query->where('first_name', 'like', '%' . $this->searchTerm . '%')
                      ->orWhere('last_name', 'like', '%' . $this->searchTerm . '%')
                      ->orWhere('emp_id', 'like', '%' . $this->searchTerm . '%');
            });
        }
        
        // Get the filtered employees
        return $query->get();
    
    }
    public function clearSelectedEmployee()
    {
        $this->selectedEmployeeId='';
        $this->selectedEmployeeFirstName='';
        $this->selectedEmployeeLastName='';
        $this->searchTerm='';
    }

    public function submitweekdayshift()
    {
        $this->validate();
        if(empty($this->selectedEmployeeId))
        {
            FlashMessageHelper::flashError("Please select an employee");
            return;
        }

        EmployeeWeekDay::create([
            'emp_id' => $this->selectedEmployeeId,
            'from_date' => $this->from_date,
            'to_date' => $this->to_date,
            'sunday' => $this->sundaystatus,
            'monday'=>$this->mondaystatus,
            'tuesday'=>$this->tuesdaystatus,
            'wednesday'=>$this->wednesdaystatus,
            'thursday'=>$this->thursdaystatus,
            'friday'=>$this->fridaystatus,
            'saturday'=>$this->saturdaystatus,
            
        ]);
        return redirect()->route('employee-weekday-chart');
    }
    public function closeweekdayshift()
    {
        $this->selectedEmployeeId='';
        $this->from_date='';
        $this->to_date='';
        $this->mondaystatus='default';
        $this->tuesdaystatus='default';
        $this->wednesdaystatus='default';
        $this->thursdaystatus='default';
        $this->fridaystatus='default';
        $this->saturdaystatus='default';
        return redirect()->route('employee-weekday-chart');
    }
    public function render()
    {
        $this->employees = $this->getEmployeesByType();
        return view('livewire.create-employee-week-day-chart');
    }
}

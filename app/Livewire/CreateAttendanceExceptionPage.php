<?php

namespace App\Livewire;

use App\Models\AttendanceException;
use App\Models\EmployeeDetails;
use App\Models\HolidayCalendar;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use PDOException;

class CreateAttendanceExceptionPage extends Component
{
    public $employees;

    public $db;
    public $to_date;
    public $from_date;

    public $reason;
    public $status;
    public $searchTerm = '';
    public $searchEmployee=0;

    public $selectedEmployeeFirstName;

    public $selectedEmployeeLastName;
    public $selectedEmployeeId='';
    public function closeAttendanceException()
    {
        return redirect()->route('attendance-exception');   
    }

    public function updateselectedEmployee($empId)
    {
    
        $this->selectedEmployeeId=$empId;
      
        $this->selectedEmployeeFirstName= EmployeeDetails::where('emp_id',$empId)->value('first_name');
        $this->selectedEmployeeLastName= EmployeeDetails::where('emp_id',$empId)->value('last_name');
        $this->searchEmployee=0;
    }

    public function updatefromDate()
    {
        $this->from_date=$this->from_date;
    }

    public function updateStatus()
    {
        $this->status=$this->status;
    }

    public function updateReason()
    {
        $this->reason=$this->reason;
    }
    public function updatetoDate()
    {
        $this->to_date=$this->to_date;
    }
    public function clearSelectedEmployee()
    {
        $this->selectedEmployeeId='';
        $this->selectedEmployeeFirstName='';
        $this->selectedEmployeeLastName='';
        $this->searchTerm='';
    }
    public function searchforEmployee()
    {
          $this->searchEmployee=1;
       
    }
    public function submitAttendanceException()
    {
        $this->validate([
            
            'from_date' => 'required|date|before_or_equal:to_date',
            'to_date' => 'required|date|after_or_equal:from_date',
            'status' => 'required|string',
            'reason' => 'required|string|max:255',
            'selectedEmployeeId' => 'required',  // Ensuring selectedEmployeeId is not empty
        ], [
            'from_date.required' => 'The From Date field is required.',
            'from_date.date' => 'The From Date must be a valid date.',
            'from_date.before_or_equal' => 'The From Date must be before or equal to the To Date.',
            'to_date.required' => 'The To Date field is required.',
            'to_date.date' => 'The To Date must be a valid date.',
            'to_date.after_or_equal' => 'The To Date must be after or equal to the From Date.',
            'status.required' => 'The status field is required.',
            'reason.required' => 'Please provide a reason.',
            'reason.max' => 'The reason may not exceed 255 characters.',
            'selectedEmployeeId.required' => 'Employee details are required.', 
        ]);
       
        AttendanceException::create([
            'emp_id' => $this->selectedEmployeeId,
            'from_date' => $this->from_date,
            'to_date' => $this->to_date,
            'status' => $this->status,
            'reason'=>$this->reason,
            
        ]);
        if($this->status=='Holiday')
        {
                    
                        // Assuming you have a database connection already set up
                  // Assuming the necessary variables are set
                
        $empId = $this->selectedEmployeeId;  // Employee ID
              // To date

       
              HolidayCalendar::create([
                'emp_id' => $empId,
                'day' => 'Monday',
                'date' => 2024-12-11,
                'month' => 'December',
                'year' => 2024,
                'festivals' => 'hr modified',
            ]);

       
    
        }
        return redirect()->route('attendance-exception');
    
    }
    public function editAttendanceException($id)
{
    return redirect()->route('edit-attendance-exception-page', ['id' => $id]);
}

    public function closeEmployeeBox()
    {
        $this->searchEmployee=0;
       
       
    }
    public function updatesearchTerm()
    {
        $this->searchTerm=$this->searchTerm;
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
    public function render()
    {
        $this->employees = $this->getEmployeesByType();
        return view('livewire.create-attendance-exception-page');
    }
}

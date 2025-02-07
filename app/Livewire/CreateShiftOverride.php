<?php

namespace App\Livewire;

use App\Helpers\FlashMessageHelper;
use App\Models\EmployeeDetails;
use App\Models\ShiftOverride;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CreateShiftOverride extends Component
{
    public $searchEmployee=0;

    public $validationErrors = [];
    public $reason;

    public $to_date;
    public $shift;
    public $from_date;
    public $searchTerm = '';
    public $employees;

    public $index=0;
    public $selectedEmployeeId='';

    public $selectedEmployeeFirstName;

    public $selectedEmployeeLastName;
    public function searchforEmployee()
    {
          $this->searchEmployee=1;
       
    }
    public function updatefromDate()
    {
        $this->from_date=$this->from_date;
    }

    public function closeEmployeeBox()
    {
        $this->searchEmployee=0;
       
       
    }
    public function updateShift()
    {
        $this->shift=$this->shift;
    }

    public function updateReason()
    {
        $this->reason=$this->reason;
    }
    public function updatetoDate()
    {
        $this->to_date=$this->to_date;
    }
    public function updated($propertyName)
    {
        // Remove validation message as soon as user enters value
        $this->resetErrorBag($propertyName);
    }
    public function submitAttendanceException()
    {
        if (empty($this->selectedEmployeeId)) {
            FlashMessageHelper::flashError('Please select the employee.');
            return;
        }

        $rules = [
            'from_date' => 'required|date|before_or_equal:to_date',
            'to_date' => 'required|date|after_or_equal:from_date',
            'shift' => 'required|string',
            'reason' => 'required|string|max:255',
            'selectedEmployeeId' => 'required',
        ];
    
        $messages = [
            'from_date' => 'From Date is required',
            'to_date' => 'To Date is required',
            'shift.required' => 'The shift field is required.',
            'reason.required' => 'Please provide a reason.',
            'reason.max' => 'The reason may not exceed 255 characters.',
        ];

        // Only validate `from_date` if it's entered
        if (!empty($this->from_date)) {
            $rules['from_date'] = 'required|date|before_or_equal:to_date';
            $messages['from_date.required'] = 'The From Date field is required.';
            $messages['from_date.date'] = 'The From Date must be a valid date.';
            $messages['from_date.before_or_equal'] = 'The From Date must be before or equal to the To Date.';
        }

        // Only validate `to_date` if it's entered
        if (!empty($this->to_date)) {
            $rules['to_date'] = 'required|date|after_or_equal:from_date';
            $messages['to_date.required'] = 'The To Date field is required.';
            $messages['to_date.date'] = 'The To Date must be a valid date.';
            $messages['to_date.after_or_equal'] = 'The To Date must be after or equal to the From Date.';
        }
    
        $this->validate($rules, $messages);
        ShiftOverride::create([
            'emp_id' => $this->selectedEmployeeId,
            'from_date' => $this->from_date,
            'to_date' => $this->to_date,
            'shift_type' => $this->shift,
            'reason'=>$this->reason,
            
        ]);
        $existingJson = json_decode($employee->shift_entries_from_hr ?? '{}', true);

        // Add the new entry to the JSON object
        $newIndex = count($existingJson); // Use count of existing entries as the new index
        $existingJson[$newIndex] = [
            'from_date' => $this->from_date,
            'to_date' => $this->to_date,
            'shift_type' => $this->shift,
        ];
        DB::table('employee_details')
        ->where('emp_id', $this->selectedEmployeeId)
        ->update(['shift_entries_from_hr' => json_encode($existingJson)]);
        FlashMessageHelper::flashSuccess("Shift Override is saved successfully for the employee(s) over a period from " . Carbon::parse($this->from_date)->format('jS F Y') . " to " . Carbon::parse($this->to_date)->format('jS F Y') . " for the shift " . $this->shift);

        return redirect()->route('shift-override');
    
    }
    public function clearSelectedEmployee()
    {
        $this->selectedEmployeeId='';
        $this->selectedEmployeeFirstName='';
        $this->selectedEmployeeLastName='';
        $this->searchTerm='';
    }
    public function closeAttendanceException()
    {
        return redirect()->route('shift-override');
    }
    public function updateselectedEmployee($empId)
    {
    
        $this->selectedEmployeeId=$empId;
      
        $this->selectedEmployeeFirstName= EmployeeDetails::where('emp_id',$empId)->value('first_name');
        $this->selectedEmployeeLastName= EmployeeDetails::where('emp_id',$empId)->value('last_name');
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
        return view('livewire.create-shift-override');
    }
}

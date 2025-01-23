<?php

namespace App\Livewire;

use App\Helpers\FlashMessageHelper;
use App\Models\AttendanceException;
use App\Models\EmployeeDetails;
use Carbon\Carbon;
use Livewire\Component;

class EditAttendanceExceptionPage extends Component
{
    public $exceptionDetails;

    public $employeeId;

    public $from_date;

    public $to_date;

    public $status;

    public $reason;
    public $employeeName;
    public function mount($id)
    {
        $this->exceptionDetails = AttendanceException::findOrFail($id);
        $this->employeeId=$this->exceptionDetails->emp_id;
        $employee = EmployeeDetails::where('emp_id', $this->employeeId)
        ->select('first_name', 'last_name')
        ->first();
        $this->employeeName=ucwords(strtolower($employee->first_name)).' '.ucwords(strtolower($employee->last_name));
        $this->from_date = $this->exceptionDetails->from_date;
        $this->to_date = $this->exceptionDetails->to_date;
        $this->status = $this->exceptionDetails->status;
        $this->reason = $this->exceptionDetails->reason;


    }
    public function updateAttendanceException()
    {
        $this->validate([
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:fromDate',
            'status' => 'required|string|max:255',
            'reason' => 'required|string|max:500',
        ]);

        // Update the record
        $this->exceptionDetails->update([
            'from_date' => $this->from_date,
            'to_date' => $this->to_date,
            'status' => $this->status,
            'reason' => $this->reason,
        ]);
         $formattedFromDate=Carbon::parse($this->from_date)->format('jS F Y');
         $formattedToDate=Carbon::parse($this->to_date)->format('jS F Y');
        // Notify user
        FlashMessageHelper::flashSuccess("Attendance Exception is saved successfully for the employee {$this->employeeName}  ({$this->employeeId}) over a period from {$formattedFromDate} to {$formattedToDate}");

        // Redirect or stay on the same page
        return redirect()->route('attendance-exception'); // Adjust the route name as needed
    }
    public function closeAttendanceException()
    {
        return redirect()->route('attendance-exception');   
    }
    public function render()
    {
        return view('livewire.edit-attendance-exception-page');
    }
}

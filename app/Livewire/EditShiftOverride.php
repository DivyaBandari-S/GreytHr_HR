<?php

namespace App\Livewire;

use App\Helpers\FlashMessageHelper;
use App\Models\EmployeeDetails;
use App\Models\ShiftOverride;
use Carbon\Carbon;
use Livewire\Component;

class EditShiftOverride extends Component
{
    public $id;

    public $employeeId;

    public $shiftoverride;
    public $employeeName;

    public $from_date;

    public $to_date;

    public $shift;

    public $reason;
    public function mount($id)
    {
        $this->id=$id;
        $this->shiftoverride = ShiftOverride::findOrFail($id);
        $this->employeeId=$this->shiftoverride->emp_id;
        $employee = EmployeeDetails::where('emp_id', $this->employeeId)
        ->select('first_name', 'last_name')
        ->first();
        $this->employeeName=$employee->first_name.' '.$employee->last_name;
        $this->from_date = $this->shiftoverride->from_date;
        $this->to_date = $this->shiftoverride->to_date;
        $this->shift = $this->shiftoverride->shift_type;
        $this->reason = $this->shiftoverride->reason;
    }
    public function updateAttendanceException()
    {
        $this->validate([
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:fromDate',
            'shift' => 'required|string|max:255',
            'reason' => 'required|string|max:500',
        ]);

        // Update the record
        $this->shiftoverride->update([
            'from_date' => $this->from_date,
            'to_date' => $this->to_date,
            'shift_type' => $this->shift,
            'reason' => $this->reason,
        ]);
         $formattedFromDate=Carbon::parse($this->from_date)->format('jS F Y');
         $formattedToDate=Carbon::parse($this->to_date)->format('jS F Y');
        // Notify user
        FlashMessageHelper::flashSuccess("Attendance Exception is updated successfully for the employee {$this->employeeName}  ({$this->employeeId}) over a period from {$formattedFromDate} to {$formattedToDate}");

        // Redirect or stay on the same page
        return redirect()->route('shift-override'); // Adjust the route name as needed
    }
    public function render()
    {
        return view('livewire.edit-shift-override');
    }
}

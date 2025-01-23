<?php

namespace App\Livewire;

use App\Helpers\FlashMessageHelper;
use App\Models\AttendanceException;
use Carbon\Carbon;
use Livewire\Component;

class AttendanceExceptionForDisplay extends Component
{
    public $attendanceExceptions;

    public $idfordeletingrecord;
    public $deleteModal=false;
    public function addException()
    {
        return redirect()->route('create-attendance-exception');
    }

    public function closeAttendanceExceptionModal()
    {
        $this->deleteModal=false;
    }
    public function openDeleteModal($id)
    {
       $this->deleteModal=true;
       $this->idfordeletingrecord=$id;
      
    }
    public function deleteAttendanceException($id)
    {
        
        $attendance_exception=AttendanceException::find($id);
        $attendance_exception_from_date=Carbon::parse($attendance_exception->from_date)->format('jS F Y');
        $attendance_exception_to_date=Carbon::parse($attendance_exception->to_date)->format('jS F Y');
        FlashMessageHelper::flashSuccess("
Attendance Exception is deleted successfully for the employee {$attendance_exception->employee->first_name} {$attendance_exception->employee->last_name} ({$attendance_exception->emp_id}) over a period from {$attendance_exception_from_date} to {$attendance_exception_to_date}");
        $attendance_exception->delete();
        $this->deleteModal=false;
    }
    public function editAttendanceException($id)
    {
        return redirect()->route('edit-attendance-exception-page', ['id' => $id]);   
    }
    public function render()
    {
        $this->attendanceExceptions = AttendanceException::with('employee')->orderByDesc('id')->get();
        
       
        return view('livewire.attendance-exception-for-display');
    }
}

<?php

namespace App\Livewire;

use App\Helpers\FlashMessageHelper;
use App\Models\AttendanceException;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class AttendanceExceptionForDisplay extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
     

    public $currentPage=1;

    public $limit = 5;
    public $idfordeletingrecord;
    public $deleteModal=false;
    public function addException()
    {
        return redirect()->route('create-attendance-exception');
    }

    public function setPage($page)
    {
        $this->currentPage = $page;
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
        FlashMessageHelper::flashSuccess("Attendance Exception is deleted successfully for the employee {$attendance_exception->employee->first_name} {$attendance_exception->employee->last_name} ({$attendance_exception->emp_id}) over a period from {$attendance_exception_from_date} to {$attendance_exception_to_date}");
        $attendance_exception->delete();
        $this->deleteModal=false;
    }

    public function previousPage()
    {
        if ($this->currentPage > 1) {
            $this->currentPage--;
        }
    }

    // Move to the next page if possible
    public function nextPage()
    {
        // Calculate total count and total pages
        $totalCount = AttendanceException::count();
        $totalPages = (int) ceil($totalCount / $this->limit);

        if ($this->currentPage < $totalPages) {
            $this->currentPage++;
        }
    }
    public function editAttendanceException($id)
    {
        return redirect()->route('edit-attendance-exception-page', ['id' => $id]);   
    }
    public function render()
    {
        $offset = ($this->currentPage - 1) * $this->limit;

        $totalCount = AttendanceException::count();
        // $attendanceExceptions = AttendanceException::with('employee')->orderByDesc('id')->paginate(5);
        $attendanceExceptions = AttendanceException::with('employee')->orderByDesc('id')
        ->skip($offset)
        ->take($this->limit)
        ->get();
        $totalPages = (int) ceil($totalCount / $this->limit);
        return view('livewire.attendance-exception-for-display',[
            'attendanceExceptions' => $attendanceExceptions, // Only pass the data (records) part
            'totalCount'=>$totalCount,
            'totalPages'=>$totalPages,
            'pagination' => $attendanceExceptions // Pass the whole paginator for pagination links
        ]);
    }
}

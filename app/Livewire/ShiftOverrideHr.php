<?php

namespace App\Livewire;

use App\Helpers\FlashMessageHelper;
use App\Models\ShiftOverride;
use Carbon\Carbon;
use Livewire\Component;

class ShiftOverrideHr extends Component
{
    public $shiftoverride;

    public $deleteModal=false;

    public $idfordeletingrecord;
  
    public function createShiftOverride()
    {
        return redirect()->route('create-shift-override');
    }
  
    public function openDeleteModal($id)
    {
       $this->deleteModal=true;
       $this->idfordeletingrecord=$id;
      
    }
    public function closeShiftOverrideModal()
    {
        $this->deleteModal=true;
        $this->idfordeletingrecord=null;
    }
    public function deleteShiftOverride($id)
    {
        
        $shift_override=ShiftOverride::find($id);
        $shift_override_from_date=Carbon::parse($shift_override->from_date)->format('jS F Y');
        $shift_override_to_date=Carbon::parse($shift_override->to_date)->format('jS F Y');
        FlashMessageHelper::flashSuccess("Shift Override is deleted successfully for the employee " . ucwords(strtolower($shift_override->employee->first_name)) . " " . ucwords(strtolower($shift_override->employee->last_name)) . " ({$shift_override->emp_id}) over a period from {$shift_override_from_date} to {$shift_override_to_date}");
        $shift_override->delete();
        $this->deleteModal=false;
    }
    public function editShiftOverride($id)
    {
        return redirect()->route('edit-shift-override', ['id' => $id]);
    }
    public function render()
    {
        $this->shiftoverride = ShiftOverride::with('employee')->orderByDesc('id')->get();
        return view('livewire.shift-override-hr');
    }
}

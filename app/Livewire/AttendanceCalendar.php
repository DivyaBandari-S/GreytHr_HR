<?php

namespace App\Livewire;

use Livewire\Component;

class AttendanceCalendar extends Component
{
    public $selectedEmployeeId='hjkm';
    public function mount($SelectedEmployeeId)
    {
       $this->selectedEmployeeId=$SelectedEmployeeId;
       
          
    }
    public function render()
    {
        return view('livewire.attendance-calendar');
    }
}

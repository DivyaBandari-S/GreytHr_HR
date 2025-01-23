<?php

namespace App\Livewire;

use Carbon\Carbon;
use Livewire\Component;

class EmployeeWeekDayChart extends Component
{
    public $currentYear;

    public $selectedOption='All';
    public $previousYear;

    public $nextYear;
    
    public function mount()
    {
        $this->currentYear = Carbon::now()->year;
        $this->previousYear = $this->currentYear - 1;
        $this->nextYear = $this->currentYear + 1;
    }
    public function updateSelected($option)
    {
        $this->selectedOption = $option;
    }
    public function render()
    {
        return view('livewire.employee-week-day-chart');
    }
}

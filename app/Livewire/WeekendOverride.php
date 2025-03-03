<?php

namespace App\Livewire;

use Carbon\Carbon;
use Livewire\Component;

class WeekendOverride extends Component
{
    public $months;

    public $currentYear;

    public $selectedYear;
    public function mount()
    {
        $this->currentYear = Carbon::now()->year;
        $this->selectedYear=$this->currentYear;
        $this->months = collect(range(1, 12))->map(function ($month) {
            return [
                'value' => $month,
                'name' => Carbon::createFromDate(null, $month, 1)->format('F'),
            ];
        });
    }
    public function render()
    {
        return view('livewire.weekend-override');
    }
}

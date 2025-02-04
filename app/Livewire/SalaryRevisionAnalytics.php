<?php

namespace App\Livewire;

use Livewire\Component;

class SalaryRevisionAnalytics extends Component
{
    public $isShowHelp = true;


    public $minMonths = 1;
    public $maxMonths = 30;
    public $selectedRange = [1, 15]; // Default selected range
    public $activeTab = 'pending';
    protected $listeners = ['updateRange'];


    public function changeTab($tab)
    {
        $this->activeTab = $tab;
    }

    // Method that handles the range update
    public function updateRange($range)
    {
        // Update the selected range
        $this->selectedRange = [$range[0], $range[1]];
       

        // Additional logic here if needed
    }

    public function toogleHelp()
    {
        // dd();
        $this->isShowHelp = !$this->isShowHelp;
    }
    public function render()
    {
        return view('livewire.salary-revision-analytics');
    }
}

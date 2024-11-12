<?php

namespace App\Livewire;

use Livewire\Component;

class HrHolidayList extends Component
{
    public $showActiveGrantLeave = false;
    public $showLeaveBalanceSummary = true;
    public $holidays = [];
    public $filteredHolidays = [];
    public $filters = [];
    public $rowCount = 0;

    public function mount()
    {
        // Load holidays data (this would typically come from a model)
        $this->holidays = []; // Fetch from the database
        $this->filteredHolidays = $this->holidays; // Initialize with all holidays
    }
    public function filterHolidays($filter)
    {
        // Implement your filtering logic
        // For example, filter by occasion
        if ($filter) {
            $this->filteredHolidays = array_filter($this->holidays, function ($holiday) use ($filter) {
                return strpos($holiday['occasion'], $filter) !== false;
            });
        } else {
            $this->filteredHolidays = $this->holidays; // Reset if no filter
        }
    }

    public function showGrantLeaveTab(){
        $this->showActiveGrantLeave = true;
        $this->showLeaveBalanceSummary = false;
    }
    public function render()
    {
        return view('livewire.hr-holiday-list');
    }
}

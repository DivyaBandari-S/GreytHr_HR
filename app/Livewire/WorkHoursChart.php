<?php

namespace App\Livewire;

use App\Models\SwipeRecord;
use Carbon\Carbon;
use Livewire\Component;

class WorkHoursChart extends Component
{
    public $days = [];
    public $workHours = [];

    public function mount()
    {
        $this->generateWorkHoursData();
    }

    public function generateWorkHoursData()
{
    // Set up the days of the month (e.g., September with 30 days)
    $this->days = range(1, 30);

    // Hardcoded work hours data for each day in September
    $this->workHours = [
        500, 480, 450, 430, 420, 10, 8, 5, 2, 1,
        0, 0, 0, 0, 0, 0, 0, 0, 0, 0,
        0, 0, 0, 0, 0, 0, 0, 0, 0, 0
    ];

    // Ensure that the array matches the number of days
    if (count($this->workHours) !== count($this->days)) {
        throw new \Exception("Work hours array must have the same number of elements as the days array.");
    }
}

    public function generateWorkHoursData1()
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        
        $daysInMonth = Carbon::now()->daysInMonth;
        $this->days = range(1, $daysInMonth);

        // Initialize work hours array with 0 for each day
        $this->workHours = array_fill(0, $daysInMonth, 0);

        // Assuming you have a model SwipeRecord that records the work hours per day
        $records = SwipeRecord::whereMonth('created_at', $currentMonth)
                              ->whereYear('created_at', $currentYear)
                              ->get();

        foreach ($records as $record) {
            $day = (int)$record->created_at->format('d');
            $this->workHours[$day - 1] += $record->total_hours; // Adjust `total_hours` based on your model's field
        }
    }

    public function render()
    {
        return view('livewire.work-hours-chart');
    }
}

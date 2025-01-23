<?php

namespace App\Livewire;

use App\Helpers\FlashMessageHelper;
use App\Models\HolidayCalendar;
use App\Models\SwipeRecord;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class ShiftRotationCalendar extends Component
{
    public $calendar=[];

    public $year;

    public $date;
    public $month;
    public function mount()
    {
        $this->year = now()->year;
        $this->month = now()->month;
        $this->generateCalendar();
    }
    public function beforeMonth()
    {
        try {
            
          $this->date = Carbon::create($this->year, $this->month, 1)->subMonth();
            
            $this->year = $this->date->year;
            $this->month = $this->date->month;
          
            $this->generateCalendar();
          
        } catch (\Exception $e) {
            Log::error('Error in nextMonth method: ' . $e->getMessage());
            // Handle the error as needed, such as displaying a message to the user
        }
    }
    public function nextMonth()
{
    try {
        
      $this->date = Carbon::create($this->year, $this->month, 1)->addMonth();
        
        $this->year = $this->date->year;
        $this->month = $this->date->month;
       
        $this->generateCalendar();
      
    } catch (\Exception $e) {
        Log::error('Error in nextMonth method: ' . $e->getMessage());
        // Handle the error as needed, such as displaying a message to the user
    }
}
    public function generateCalendar()
    {
       
          
            

            try {
                $firstDay = Carbon::create($this->year, $this->month, 1);
                $daysInMonth = $firstDay->daysInMonth;
                $today = now();
                $calendar = [];
                $dayCount = 1;
                $firstDayOfWeek = $firstDay->dayOfWeek;
                $lastDayOfPreviousMonth = $firstDay->copy()->subDay();
        
                for ($i = 0; $i < ceil(($firstDayOfWeek + $daysInMonth) / 7); $i++) {
                    $week = [];
                    for ($j = 0; $j < 7; $j++) {
                        if ($i === 0 && $j < $firstDay->dayOfWeek) {
                            $previousMonthDays = $lastDayOfPreviousMonth->copy()->subDays($firstDay->dayOfWeek - $j - 1);
                            $week[] = [
                                'day' => $previousMonthDays->day,
                                'date' => $previousMonthDays->toDateString(),
                                'isToday' => false,
                                'isRegularised' => false,
                                'isCurrentDate' => false,
                                'isCurrentMonth' => false,
                                'isNextMonth'=>false,
                                'isPreviousMonth' => true,
                                'isAfterToday' => $previousMonthDays->isAfter($today),
                            ];
                        } elseif ($dayCount <= $daysInMonth) {
                            $date = Carbon::create($this->year, $this->month, $dayCount);
                            $isToday = $date->isSameDay($today);
                           
                            $week[] = [
                                'day' => $dayCount,
                                'date' => $date->toDateString(),
                                'isToday' => $isToday,
                                'isCurrentDate' => $isToday,
                                'isCurrentMonth' => true,
                                'isPreviousMonth' => false,
                                 'isNextMonth'=>false,
                                'isRegularised' => false,
                                'isAfterToday' => $date->isAfter($today),
                            ];
                            $dayCount++;
                        } else {
                            $nextMonth = $this->month % 12 + 1;
                            $nextYear = $this->year + ($this->month == 12 ? 1 : 0);
                            $nextMonthDays = Carbon::create($nextYear, $nextMonth, $dayCount - $daysInMonth);
                            $week[] = [
                                'day' => $nextMonthDays->day,
                                'date' => $nextMonthDays->toDateString(),
                                'isToday' => false,
                                'isCurrentDate' => false,
                                'isCurrentMonth' => false,
                                'isNextMonth' => true,
                                'isRegularised' => false,
                                'isAfterToday' => $nextMonthDays->isAfter($today),
                            ];
                            $dayCount++;
                        }
                    }
                    $calendar[] = $week;
                }
                $this->calendar = $calendar;
            } catch (\Exception $e) {
                Log::error('Error in generateCalendar method: ' . $e->getMessage());
                // Handle the error as needed, such as displaying a message to the user
            }
       
    }
    public function render()
    {
        return view('livewire.shift-rotation-calendar');
    }
}

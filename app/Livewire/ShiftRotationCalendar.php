<?php

namespace App\Livewire;

use App\Helpers\FlashMessageHelper;
use App\Models\HolidayCalendar;
use App\Models\ShiftRotation;
use App\Models\SwipeRecord;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class ShiftRotationCalendar extends Component
{
    public $calendar=[];

    public $year;

    public $isDropdownVisible = false; // Dropdown visibility state
    public $selectedDate;

    public $dayType='Regular';
    public $date;
    public $month;

    public $selectedShiftRotationPlan;
    public $openDatePopUp=false;
    public $shiftRotationPlan = '';
    public function mount()
    {
        $this->year = now()->year;
        $this->month = now()->month;
        $this->generateCalendar();
    }
    public function selectDate($date)
    {
       $this->openDatePopUp=true;
       $this->selectedDate=$date;
       $this->dayType = 'Regular';
       $shiftRotation = ShiftRotation::where('shift_rotation_date', $this->selectedDate)->where('shift_type',$this->selectedShiftRotationPlan)->first();
      
       if ($shiftRotation) {
           $this->selectedShiftRotationPlan = $shiftRotation->shift_type;
           $this->dayType = $shiftRotation->day_type;
       } 

    }
    public function updateSelectedDayType($value)
    {
       $this->dayType=$value;
       
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
    

    public function toggleDropdown()
    {
        $this->isDropdownVisible = !$this->isDropdownVisible;
    }
    public function updateSelectedShiftType($value)
    {
        $this->selectedShiftRotationPlan=$value;
        Log::info("Selected Shift rotation plan selected: " . $value);
    }
    public function updatedShiftRotationPlan($value)
    {
        // This method is triggered when the dropdown value changes
        $this->shiftRotationPlan=$value;
        Log::info("Shift rotation plan selected: " . $value);
    }
    public function savePopup()
    {
        $existingShiftRotation = ShiftRotation::where('shift_rotation_date', $this->selectedDate)->first();
        if ($existingShiftRotation) {
            // Update the existing record
            Log::info('Updating ShiftRotation dates to database.');
            $existingShiftRotation->update([
                'day_type' => $this->dayType,
            ]);
            FlashMessageHelper::flashSuccess('Shift Rotation Calendar updated successfully.');
        } else {
            Log::info('Saving ShiftRotation dates to database.');
            ShiftRotation::create([
                'shift_rotation_date' => $this->selectedDate,
                'shift_type' => $this->selectedShiftRotationPlan,
                'day_type' => $this->dayType,
            ]);
            FlashMessageHelper::flashSuccess('Shift Rotation Calendar saved successfully.');
        }
       
       
        
        Log::info('Shift Rotation Calendar saved successfully.');
    }
    public function updateSelected($option)
    {
       $this->shiftRotationPlan=$option;
       $this->dayType = 'Regular';
    }
    public function render()
    {
        $this->selectedShiftRotationPlan=$this->shiftRotationPlan;
        return view('livewire.shift-rotation-calendar');
    }
}

<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Holiday;
use Illuminate\Support\Facades\Log;
use App\Models\HolidayMasterList; 
use App\Helpers\FlashMessageHelper;

class HrHolidayList extends Component
{
    public $showActiveGrantLeave = false;
    public $showLeaveBalanceSummary = true;
    public $holidaysList;
    public $holidays;
    public $filteredHolidays = [];
    public $selectedHolidays = []; 
    public $selectedHolidays1 = []; 
    public $filters = [];
    public $rowCount = 0;
    public $addButton= true;
    public $showButtons = false;
    public $isModalOpen = false;
    public function openModal()
    {
        $this->isModalOpen = true;
        $this->holidaysList = HolidayMasterList::all();
    }

    // Close the modal
    public function close()
    {
       
        
        $this->isModalOpen = false;
        $this->selectedHolidays1 = [];
    }


    public function addSelectedHolidays()
    {
        // Log the number of selected holidays
        Log::info('Selected holidays count: ' . count($this->selectedHolidays1));
    
        // Track if any holiday was added successfully
        $addedHolidayCount = 0;
        $existingHolidayCount = 0;
    
        if (count($this->selectedHolidays1) > 0) {
            foreach ($this->selectedHolidays1 as $holidayId) {
                // Log the current holiday ID being processed
                Log::info('Processing holiday ID: ' . $holidayId);
    
                // Find the holiday by ID from the HolidayMasterList
                $holiday = HolidayMasterList::find($holidayId);
    
                if ($holiday) {
                    // Check if the holiday is already in the 'holidays' table
                    $existingHoliday = Holiday::where('occasion', $holiday->occasion)
                        ->where('date', $holiday->date)
                        ->first();
    
                    if ($existingHoliday) {
                        // Log that the holiday already exists
                        Log::info('Holiday already exists in the holidays table:', [
                            'occasion' => $holiday->occasion,
                            'date' => $holiday->date,
                        ]);
                        // Increment the existing holiday counter
                        $existingHolidayCount++;
    
                        // Optionally, you can skip adding this holiday to the table
                        continue; // Skip this holiday and move to the next one
                    }
    
                    // Log the details of the holiday being added
                    Log::info('Adding holiday to the holidays table:', [
                        'occasion' => $holiday->occasion,
                        'date' => $holiday->date,
                        'day' => $holiday->day,
                    ]);
    
                    // Add the holiday to the holidays table
                    Holiday::create([
                        'occasion' => $holiday->occasion,
                        'date' => $holiday->date,
                        'day' => $holiday->day,
                        'status' => 5, 
                    ]);
    
                    // Increment the added holiday counter
                    $addedHolidayCount++;
                } else {
                    // Log if no holiday was found for the given ID
                    Log::warning('Holiday not found for ID: ' . $holidayId);
                }
            }
    
            // Reset selected holidays after adding them
            $this->selectedHolidays1 = [];
            $this->isModalOpen = false;
    
            // Log success message if at least one holiday was added
            if ($addedHolidayCount > 0) {
                Log::info('Selected holidays have been added successfully.');
                FlashMessageHelper::flashSuccess("Successfully added {$addedHolidayCount} holidays.");
            }
    
            // Show error message if any holidays already existed in the table
            if ($existingHolidayCount > 0) {
                FlashMessageHelper::flashError("{$existingHolidayCount} holidays already exist in the table.");
            }
    
            // If no holidays were added, show a generic error message
            if ($addedHolidayCount == 0 && $existingHolidayCount == 0) {
                FlashMessageHelper::flashError('No holidays were processed.');
            }
            $this->holidays = Holiday::all();
    
        } else {
            // Log error message when no holidays are selected
            Log::error('No holidays selected for adding.');
            FlashMessageHelper::flashError('No holidays selected!');
        }
    }
    
    
    public function saveHolidays()
    {
        Log::info('Save Holidays called.');
       
        // Variable to track already saved holidays for flash messages
        $alreadySavedHolidays = [];
    
        // Check if there are selected holidays
        if (!empty($this->selectedHolidays)) {
            foreach ($this->selectedHolidays as $holidayId) {
                $holiday = Holiday::find($holidayId);
                
                if ($holiday) {
                    // Check if the holiday status is already set to 2 (selected)
                    if ($holiday->status == 2) {
                        // Add holiday to the list of already saved holidays
                        $alreadySavedHolidays[] = $holiday->name;
                        
                        // Optionally deselect the checkbox (remove from selectedHolidays)
                        $this->selectedHolidays = array_filter($this->selectedHolidays, function($id) use ($holidayId) {
                            return $id !== $holidayId;
                        });
                        continue; // Skip updating this holiday
                    }
                    
                    // Update holiday status to 2 if it's not already set
                    $holiday->status = 2;
                    $holiday->save();
                }
            }
    
            // If any holidays were already saved, show a flash message once for all
            if (!empty($alreadySavedHolidays)) {
                FlashMessageHelper::flashInfo('The following holidays have already been saved');
            }
    
            // If holidays were updated, show success message
            if (!empty($this->selectedHolidays)) {
                Log::info('Selected Holidays:', $this->selectedHolidays);
                FlashMessageHelper::flashSuccess('Selected holidays saved successfully!');
            }
        
        } else {
            FlashMessageHelper::flashError('No holidays selected!');
        }
    }
    
    
    

    // Delete selected holidays
    public function deleteSelectedHolidays()
    {
        if (count($this->selectedHolidays) > 0) {
            Holiday::whereIn('id', $this->selectedHolidays)->delete(); // Delete selected holidays
            $this->holidays = Holiday::all(); // Refresh the list of holidays
            FlashMessageHelper::flashSuccess('Selected holidays deleted successfully!');
            $this->selectedHolidays = [];
        } else {
            FlashMessageHelper::flashError('No holidays selected to delete!');
        }
    }


    public function mount()
    {
        $this->holidays = Holiday::all();
        // $this->showButtons = $this->holidays->isNotEmpty(); 
        $this->selectedHolidays = [];

    }
  

    public function showGrantLeaveTab(){
        $this->showActiveGrantLeave = true;
        $this->showLeaveBalanceSummary = false;
    }
    public function toggleSelectAll()
{
    if (count($this->selectedHolidays) === count($this->holidays)) {
        // If all holidays are selected, deselect them
        $this->selectedHolidays = [];
    } else {
        // Otherwise, select all holidays
        $this->selectedHolidays = $this->holidays->pluck('id')->toArray();
    }
}
public function toggleSelectAll1()
{
    if (count($this->selectedHolidays1) === count($this->holidaysList)) {
        // If all holidays are selected, deselect them
        $this->selectedHolidays1 = [];
    } else {
        // Otherwise, select all holidays
        $this->selectedHolidays1 = $this->holidaysList->pluck('id')->toArray();
    }
}

    public function render()
    {
        return view('livewire.hr-holiday-list');
    }
}

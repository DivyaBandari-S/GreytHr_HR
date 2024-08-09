<?php

namespace App\Livewire;

use Livewire\Component;

class AnalyticsHubViewAll extends Component
{
    public $activeTab = 'all';
    public $isOpenEventList = true;
    public $isOpenMySheets = false;
    public $isOpenEmployeeList = false;

    public function toggleAccordion($accordion)
    {
        if ($accordion === 'eventList') {
            $this->isOpenEventList = !$this->isOpenEventList;
        } elseif ($accordion === 'mySheets') {
            $this->isOpenMySheets = !$this->isOpenMySheets;
        } elseif ($accordion === 'employeeList') {
            $this->isOpenEmployeeList = !$this->isOpenEmployeeList;
        }
    }
   
    public function goBack()
    {
        return redirect()->route('analytichub'); 
    }
   
    public function render()
    {
        return view('livewire.analytics-hub-view-all');
    }
}

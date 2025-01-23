<?php

namespace App\Livewire;

use Livewire\Component;

class SwipeManagementForHr extends Component
{
    public $accordionForHealthStatus=false;

    public $accordionForRequestHistory=false;
    public $accordionForSyncAttendance=false;

    public $accordionForPendingRequest=false;
    public function openAccordionForHealthStatus()
    {
        $this->accordionForHealthStatus=true;
    }

    public function openAccordionForPendingRequest()
    {
        $this->accordionForPendingRequest=true;
    }
    public function openAccordionForSyncAttendance()
    {
        $this->accordionForSyncAttendance=true;
    }

    public function openAccordionForRequestHistory()
    {
        $this->accordionForRequestHistory=true;
    }
    public function render()
    {
        return view('livewire.swipe-management-for-hr');
    }
}

<?php

namespace App\Livewire;

use Livewire\Component;

class EmpLeaveGranterDetails extends Component
{
    public $showActiveGrantLeave = false;
    public $showLeaveBalanceSummary = true;
    public function showGrantLeaveTab(){
        $this->showActiveGrantLeave = true;
        $this->showLeaveBalanceSummary = false;
    }
    public function render()
    {
        return view('livewire.emp-leave-granter-details');
    }
}

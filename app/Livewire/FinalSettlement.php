<?php

namespace App\Livewire;

use Livewire\Component;

class FinalSettlement extends Component
{
    public $isShowHelp = true;


    public function toogleHelp()
    {
        $this->isShowHelp = !$this->isShowHelp;
    }

    public function settleEmployee()
    {
        return redirect('/hr/user/final-settlement-stepper');
    }
    public function render()
    {
        return view('livewire.final-settlement');
    }
}

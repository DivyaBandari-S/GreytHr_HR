<?php

namespace App\Livewire;

use Livewire\Component;

class PayrollArrears extends Component
{
    public $currentStep = 1;
    public $showPayArrearsSection  = false;
    public $showOverViewSection = true;

    public function toggleSection()
    {
        $this->showPayArrearsSection  = !$this->showPayArrearsSection;
        $this->showOverViewSection = !$this->showOverViewSection;
    }
    public function nextStep()
    {

        if ($this->currentStep < 3) {
            $this->currentStep++;
        }
    }

    public function gotoBack()
    {
        if ($this->currentStep) {
            $this->currentStep--;
        }
    }
    public function render()
    {
        return view('livewire.payroll-arrears');
    }
}

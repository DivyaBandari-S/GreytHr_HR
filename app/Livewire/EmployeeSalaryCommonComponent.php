<?php

namespace App\Livewire;

use Livewire\Component;

class EmployeeSalaryCommonComponent extends Component
{
    public $isShowHelp = true;
    public function toogleHelp()
    {
        $this->isShowHelp = !$this->isShowHelp;
    }

    public function render()
    {
        return view('livewire.employee-salary-common-component');
    }
}

<?php

namespace App\Livewire;

use App\Models\EmployeeDetails;
use Livewire\Component;

class ResettlementProcessPage extends Component
{
    public $showSettledEmployees = false;
    public $selectedEmpId;
    public $selecetdEmpDetails;
    public function toggleSettledEmps(){
        $this->showSettledEmployees = !$this->showSettledEmployees;
    }

    public function getSelectedEmp($empId){
        $this->selectedEmpId = $empId;
        $this->selecetdEmpDetails = EmployeeDetails::where('emp_id', $this->selectedEmpId)->first();
        $this->showSettledEmployees =false;
    }
    public function render()
    {
        return view('livewire.resettlement-process-page',
        [
            'selecetdEmpDetails' =>$this->selecetdEmpDetails,
            'selectedEmpId' => $this->selectedEmpId
        ]
    );
    }
}

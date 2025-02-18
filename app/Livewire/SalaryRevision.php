<?php

namespace App\Livewire;

use App\Exports\EmployeePeersExport;
use App\Helpers\FlashMessageHelper;
use App\Models\EmployeeDetails;
use App\Models\EmpSalaryRevision;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class SalaryRevision extends Component
{

    public $isShowHelp = true;
    public function toogleHelp()
    {
        $this->isShowHelp = !$this->isShowHelp;
    }

    public function render()
    {
        return view('livewire.salary-revision');
    }

}

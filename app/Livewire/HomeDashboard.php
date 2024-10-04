<?php

namespace App\Livewire;

use App\Models\EmployeeDetails;
use App\Models\Hr;
use App\Models\RegularisationDates;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class HomeDashboard extends Component
{
   

    public function render()
    {
       
  
        return view('livewire.home-dashboard');
    }
}

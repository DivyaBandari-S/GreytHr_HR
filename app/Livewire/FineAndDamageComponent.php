<?php

namespace App\Livewire;

use App\Helpers\FlashMessageHelper;
use App\Helpers\FormatHelper;
use App\Models\EmployeeDetails;
use App\Models\FineAndDamage;
use Carbon\Carbon;
use Livewire\Component;

class FineAndDamageComponent extends Component
{
    public $finepage=1;

    public $damagepage=0;
    public function openFinePage()
    {
        $this->finepage=1;
        $this->damagepage=0;
    }

    public function openDamagePage()
    {
        $this->finepage=0;
        $this->damagepage=1;
    }

       public function render()
    {
        
       
        
        return view('livewire.fine-and-damage-component');
    }
}

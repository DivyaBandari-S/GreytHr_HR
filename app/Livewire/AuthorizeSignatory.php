<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\AuthorizedSignatory;

class AuthorizeSignatory extends Component
{
    public function render()
    {
        $signatories = AuthorizedSignatory::all(); // Fetch data from DB
        return view('livewire.authorize-signatory', compact('signatories'));
    }

}

<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\AuthorizedSignatory;
use App\Helpers\FlashMessageHelper;

class AuthorizeSignatory extends Component
{
    public function render()
    {
        $signatories = AuthorizedSignatory::all(); 
        return view('livewire.authorize-signatory', compact('signatories'));
    }
    public function delete($id)
    {
        $signatory = AuthorizedSignatory::find($id);
        if ($signatory) {
            $signatory->delete();
            FlashMessageHelper::flashSuccess('Signatory deleted successfully.');
        }
    }

}

<?php

namespace App\Livewire;

use App\Models\ClientDetails;
use Livewire\Component;
use App\Helpers\FlashMessageHelper;

class ClientSList extends Component
{
   
    public function render()
    {
        $clients = ClientDetails::all(); 
        return view('livewire.client-s-list', compact('clients'));
    }
    public function delete($id)
    {
        $clients = ClientDetails::find($id);

        if ($clients) {
            $clients->status= 1;
            $clients->save();
            FlashMessageHelper::flashSuccess('Client deleted successfully.');
        }
    }
}

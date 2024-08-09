<?php

namespace App\Livewire;

use Livewire\Component;

class AnalyticsHub extends Component
{
    public $selectedCard = 'Basic Information';

    public function selectCard($card)
    {
        $this->selectedCard = $card;
    }
    public function analyticsHubList()
    {
        return redirect()->route('viewAll'); 
    }
    public function render()
    {
        return view('livewire.analytics-hub');
    }
}

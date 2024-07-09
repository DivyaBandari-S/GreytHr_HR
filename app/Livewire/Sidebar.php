<?php

namespace App\Livewire;

use Livewire\Component;

class Sidebar extends Component
{
    public $activeIcon = '';
    public $showDetails = false;
    public $showSubmenu = [];

    public function toggleSubmenu($rowId)
    {
        if (isset($this->showSubmenu[$rowId])) {
            $this->showSubmenu[$rowId] = !$this->showSubmenu[$rowId];
        } else {
            $this->showSubmenu[$rowId] = true;
        }
    }
    public function mount()
    {
        $this->activeIcon = request()->segment(1);
    }


    public function setActiveIcon($icon)
    {
        $this->activeIcon = $icon;
        $this->showDetails = false;
    }
    public function toggleDetails()
    {
        $this->showDetails = !$this->showDetails;
    }
    public function render()
    {
        return view('livewire.sidebar');
    }
}

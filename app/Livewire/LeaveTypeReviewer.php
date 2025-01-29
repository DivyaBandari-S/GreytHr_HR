<?php

namespace App\Livewire;

use Livewire\Component;

class LeaveTypeReviewer extends Component
{
    public $activeTab = 'nav-reviewers';

    public function mount()
    {
        if (request()->has('tab')) {
            $this->activeTab = request()->get('tab');
        }
    }
    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }
    public function render()
    {
        return view('livewire.leave-type-reviewer');
    }
}

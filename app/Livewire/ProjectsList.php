<?php

namespace App\Livewire;

use App\Models\ClientProjects;
use Livewire\Component;
use App\Helpers\FlashMessageHelper;

class ProjectsList extends Component
{
    public function render()
    {
        $projects = ClientProjects::all(); 
        return view('livewire.projects-list', compact('projects'));
    }
    public function delete($id)
    {
        $clients = ClientProjects::find($id);
        if ($clients) {
            $clients->delete();
            FlashMessageHelper::flashSuccess('Client deleted successfully.');
        }
    }
}

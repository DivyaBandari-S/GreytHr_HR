<?php

namespace App\Livewire;

use Livewire\Component;

class GenerateLetters extends Component
{
    public $showHelp = false;
    public function hideHelp()
    {
        $this->showHelp = true;
    }
    public function showhelp()
    {
        $this->showHelp = false;
    }
    public function showPrepareLetter(){
        return redirect()->route('letter.prepare');
    }
    public function render()
    {
        return view('livewire.generate-letters');
    }
   
}

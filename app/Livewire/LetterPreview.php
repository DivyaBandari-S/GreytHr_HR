<?php

namespace App\Livewire;

use Livewire\Component;

class LetterPreview extends Component
{
    public $previewLetter; // Declare public property
    public $currentEmployeeIndex = 0;

    public function mount($previewLetter)
    {
        // Initialize the previewLetter property
        $this->previewLetter = $previewLetter;
    }
    public function nextEmployee()
    {
        if ($this->currentEmployeeIndex < count($this->previewLetter['employees']) - 1) {
            $this->currentEmployeeIndex++;
        }
    }

    public function previousEmployee()
    {
        if ($this->currentEmployeeIndex > 0) {
            $this->currentEmployeeIndex--;
        }
    }

    public function render()
    {
        // If 'employees' is a string, decode it; otherwise, use it as is.
        if (is_string($this->previewLetter['employees'])) {
            $this->previewLetter['employees'] = json_decode($this->previewLetter['employees'], true);
        }
    
        // Ensure we don't go out of bounds when accessing employee data
        $totalEmployees = count($this->previewLetter['employees']);
        if ($this->currentEmployeeIndex >= $totalEmployees) {
            $this->currentEmployeeIndex = $totalEmployees - 1; // Ensure the index stays within bounds
        }
    
        // Get the current employee based on index
        $currentEmployee = $this->previewLetter['employees'][$this->currentEmployeeIndex] ?? null;
    
        // Return the view with the necessary data
        return view('livewire.letter-preview', [
            'previewLetter' => $this->previewLetter,
            'currentEmployee' => $currentEmployee,
        ]);
    }
    
}

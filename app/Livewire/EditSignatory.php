<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\AuthorizedSignatory;
use Livewire\WithFileUploads;
use App\Helpers\FlashMessageHelper;

class EditSignatory extends Component
{
    use WithFileUploads;

    public $signatory_id, $first_name, $last_name, $designation, $company, $is_active, $signature;

    protected $rules = [
        'first_name' => 'required',
        'last_name' => 'required',
        'designation' => 'required',
        'company' => 'required',
        'is_active' => 'required|boolean',
        'signature' => 'required', // Optional signature upload
    ];
    public function updatedSignature()
{
    // If a new signature is uploaded, you can handle the new image here.
    // This is called automatically when the wire:model of the signature is updated.
    if ($this->signature) {
        $this->validate([
            'signature' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        // Optionally you can process the file (if needed) here to store it
        // For example, saving the uploaded image to the server and then converting it to base64 for preview
        $this->signature = base64_encode(file_get_contents($this->signature->getRealPath())); // If it's a file upload

    }
}

    // Load the signatory details based on the ID
    public function mount($id)
    {
        $signatory = AuthorizedSignatory::findOrFail($id);
        $this->signatory_id = $signatory->id;
        $this->first_name = $signatory->first_name;
        $this->last_name = $signatory->last_name;
        $this->designation = $signatory->designation;
        $this->company = $signatory->company;
        $this->is_active = $signatory->is_active;
        if ($signatory->signature) {
            $this->signature = base64_encode($signatory->signature);
        } else {
            $this->signature = null; 
        }
    }
   
    

    // Method to update the signatory details
    public function update()
    {
        $this->validate();
    
        $signatory = AuthorizedSignatory::find($this->signatory_id);
        if ($this->signature) {
            // Decode the base64 string to binary data
            $signatureBinaryData = base64_decode($this->signature);
        }
    
        if ($signatory) {
            $signatory->update([
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'designation' => $this->designation,
                'company' => $this->company,
                'is_active' => $this->is_active,
                'signature' => isset($signatureBinaryData) ? $signatureBinaryData : $signatory->signature, // Save new or keep old signature
            ]);
    
            // Reload the updated signature data
            if ($signatory->signature) {
                $this->signature = base64_encode($signatory->signature); // Convert the new signature to base64 for display
            }
    

            FlashMessageHelper::flashSuccess('Signatory updated successfully.');
            return redirect()->route('authorize-signatory.page');
        }
    }
    

    public function render()
    {
        return view('livewire.edit-signatory');
    }
}


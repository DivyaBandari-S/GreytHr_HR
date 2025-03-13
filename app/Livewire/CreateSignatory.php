<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\AuthorizedSignatory;
use Livewire\WithFileUploads;
use App\Helpers\FlashMessageHelper;

class CreateSignatory extends Component
{
    use WithFileUploads; // For handling file uploads

    public $first_name, $last_name, $designation, $company, $is_active, $signature;

    protected $rules = [
        'first_name' => 'required',
        'last_name' => 'required',
        'designation' => 'required',
        'company' => 'required',
        'is_active' => 'required|boolean',
        'signature' => 'required|image|max:1024', // Signature is required and should be an image
    ];

    // Customize error messages
    protected $messages = [
        'first_name.required' => 'First Name is required.',
        'last_name.required' => 'Last Name is required.',
        'designation.required' => 'Please specify a Designation.',
        'company.required' => 'Company name is required.',
        'is_active.required' => 'Please specify if the signatory is active.',
        'signature.required' => 'Please upload a valid signature image.',
        'signature.image' => 'The file must be an image.',
        'signature.max' => 'Signature image size must be less than 1MB.',
    ];
    public function validateInputChange($field)
    {
        
        $this->validateOnly($field);
    }

    public function save()
    {
        $this->validate();

        // Convert the uploaded signature image to binary data
        if ($this->signature) {
            $signatureBinaryData = file_get_contents($this->signature->getRealPath());
        }

        // Create the new signatory record
        AuthorizedSignatory::create([
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'designation' => $this->designation,
            'company' => $this->company,
            'is_active' => $this->is_active,
            'signature' => isset($signatureBinaryData) ? $signatureBinaryData : null, // Store the binary data
        ]);

        // session()->flash('message', 'Signatory added successfully.');
        FlashMessageHelper::flashSuccess('Signatory added successfully.');
        return redirect()->route('authorize-signatory.page');
    }

    public function render()
    {
        return view('livewire.create-signatory');
    }
}

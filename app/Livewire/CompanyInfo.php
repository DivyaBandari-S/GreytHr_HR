<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Rinvex\Country\Country;
class CompanyInfo extends Component
{
    use WithFileUploads;

    public $company_name, $company_address, $country, $state, $industry, $time_zone, $currency;
    public $pf_no, $tan_no, $pan_no, $esi_no, $lin_no, $gst_no, $registration_no, $twitter_handle;
    public $company_logo;

    protected $rules = [
        'company_name' => 'required|string|max:255',
        'company_address' => 'nullable|string',
        'country' => 'required|string',
        'industry' => 'required|string',
        'time_zone' => 'required|string',
        'currency' => 'required|string',
        'company_logo' => 'nullable|image|mimes:jpg,png,gif|max:2048',
    ];

    public function save()
    {
        $this->validate();

        // Handle file upload
        if ($this->company_logo) {
            $logoPath = $this->company_logo->store('logos', 'public');
        }

        // Save company settings logic here
        session()->flash('message', 'Company settings saved successfully.');
    }

    public function render()
    {
        return view('livewire.company-info');
    }
}

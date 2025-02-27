<?php

namespace App\Livewire;

use App\Models\Company;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class CompanyInfo extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $company_id, $company_name, $industry, $country, $state, $time_zone, $currency,
        $company_present_address, $company_permanent_address, $pf_no, $tan_no, $pan_no, $esi_no, $lin_no, $gst_no,
        $registration_no, $company_website, $company_logo;

    public $viewMode = false; // To check if the form is in view mode
    public $search = '';
    public $companies = [];
    public $same_as_present = false;
    protected $rules = [
        'company_name' => 'required|string|max:255',
        'company_present_address' => 'nullable|string',
        'country' => 'required|string',
        'industry' => 'required|string',
        'time_zone' => 'required|string',
        'currency' => 'required|string',
        'company_logo' => 'nullable|image|mimes:jpg,png,gif|max:2048',
    ];
    public function updatedSameAsPresent($value)
    {
        if ($value) {
            $this->company_permanent_address = $this->company_present_address;
        } else {
            $this->company_permanent_address = '';
        }
    }
    public function saveCompanyInfo()
    {
        $this->validate();

        if ($this->company_id) {
            // Update existing company
            $company = Company::find($this->company_id);
            $company->update([
                'company_name' => $this->company_name,
                'industry' => $this->industry,
                'country' => $this->country,
                'time_zone' => $this->time_zone,
                'currency' => $this->currency,
                'company_present_address' => $this->company_present_address,
                'pf_no' => $this->pf_no,
                'tan_no' => $this->tan_no,
                'pan_no' => $this->pan_no,
                'esi_no' => $this->esi_no,
                'lin_no' => $this->lin_no,
                'gst_no' => $this->gst_no,
                'registration_no' => $this->registration_no,
                'company_website' => $this->company_website,
            ]);
        } else {
            // Create new company
            Company::create([
                'company_name' => $this->company_name,
                'industry' => $this->industry,
                'country' => $this->country,
                'time_zone' => $this->time_zone,
                'currency' => $this->currency,
                'company_present_address' => $this->company_present_address,
                'pf_no' => $this->pf_no,
                'tan_no' => $this->tan_no,
                'pan_no' => $this->pan_no,
                'esi_no' => $this->esi_no,
                'lin_no' => $this->lin_no,
                'gst_no' => $this->gst_no,
                'registration_no' => $this->registration_no,
                'company_website' => $this->company_website,
            ]);
        }

        session()->flash('message', 'Company saved successfully!');
        $this->resetForm();
    }

    public function editCompanyInfo($id)
    {
        $company = Company::find($id);
        $this->fill($company->toArray());
        $this->company_id = $company->id;
        $this->viewMode = false;
    }

    public function viewCompanyInfo($id)
    {
        $company = Company::find($id);
        $this->fill($company->toArray());
        $this->company_id = $company->id;
        $this->viewMode = true;
    }

    public function deleteCompanyInfo($id)
    {
        $company = Company::find($id);
    }

    public function resetForm()
    {
        $this->reset([
            'company_id',
            'company_name',
            'industry',
            'country',
            'time_zone',
            'currency',
            'company_present_address',
            'pf_no',
            'tan_no',
            'pan_no',
            'esi_no',
            'lin_no',
            'gst_no',
            'registration_no',
            'company_website',
            'company_logo',
            'viewMode'
        ]);
    }

    public function render()
    {
        $savedCompanies = Company::where('company_name', 'like', '%' . $this->search . '%')
            ->paginate(5); // Adjust the number of items per page
        return view('livewire.company-info', compact('savedCompanies'));
    }
}

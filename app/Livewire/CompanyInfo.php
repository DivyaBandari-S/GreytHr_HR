<?php

namespace App\Livewire;

use App\Helpers\FlashMessageHelper;
use App\Models\City;
use App\Models\Company;
use App\Models\CompanyType;
use App\Models\Country;
use App\Models\IndustryType;
use App\Models\State;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class CompanyInfo extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $company_id, $company_name, $company_industry, $country, $state, $time_zone, $currency, $currency_symbol,
        $company_present_address, $company_permanent_address, $pf_no, $tan_no, $pan_no, $esi_no, $lin_no, $gst_no,
        $company_registration_no, $company_website, $company_logo, $company_registration_date, $ceo_name, $contact_email, $contact_phone, $email_domain;
    public bool $is_parent = true;
    public $parent_company_id = null;

    public $viewMode = false; // To check if the form is in view mode
    public $search = '';
    public $industry_search = '';
    public $company_search = '';
    public $country_search = '';
    public $state_search = '';
    public $city_search = '';
    public $companyList = [];
    public $same_as_present = false;
    public $industries;
    public $companyTypes;
    public $company_type;
    public $countries;
    public $states = [];
    public $branch_locations = [];

    public $selectedStates = []; // Holds only IDs
    public $selectedStatesData = []; // Holds IDs & Names
    public $cities = [];
    public $selectedCities = [];
    public $selectedCitiesData = [];
    public $stateDropdownOpen = false;
    public $cityDropdownOpen = false;
    public $errorMessage = '';
    protected function rules()
    {
        return [
            'company_name' => 'required|string|max:255|unique:companies,company_name,' . $this->company_id . ',company_id',
            'contact_email' => 'required|email|max:255|unique:companies,contact_email,' . $this->company_id . ',company_id',
            'contact_phone' => 'required|digits:10|unique:companies,contact_phone,' . $this->company_id . ',company_id',
            'ceo_name' => 'required|string|max:255',
            'is_parent' => 'required|boolean',
            'parent_company_id' => 'nullable|required_if:is_parent,false|exists:companies,company_id',
            'company_present_address' => 'required|string',
            'company_permanent_address' => 'required|string',
            'country' => 'required|string',
            'company_industry' => 'required|string',
            'company_type' => 'required|string',
            'time_zone' => 'required|string',
            'currency' => 'required|string',
            'company_logo' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:1024',
            'company_registration_no' => 'required|string|unique:companies,company_registration_no,' . $this->company_id . ',company_id',
            'gst_no' => 'required|string|unique:companies,gst_no,' . $this->company_id . ',company_id',
            'pf_no' => 'required|string|unique:companies,pf_no,' . $this->company_id . ',company_id',
            'lin_no' => 'required|string|unique:companies,lin_no,' . $this->company_id . ',company_id',
            'pan_no' => 'required|string|unique:companies,pan_no,' . $this->company_id . ',company_id',
            'esi_no' => 'required|string|unique:companies,esi_no,' . $this->company_id . ',company_id',
            'tan_no' => 'required|string|unique:companies,tan_no,' . $this->company_id . ',company_id',
            'company_website' => 'required|url|unique:companies,company_website,' . $this->company_id . ',company_id',
            'company_registration_date' => 'required|date',
            'selectedStates' => 'required|array|min:1|max:5',
            'email_domain' => 'required|string|max:255|regex:/^[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/|unique:companies,email_domain,' . $this->company_id . ',company_id',
            'selectedCities' => 'required|array|min:1|max:5',
        ];
    }


    // public function updatedCompanyRegistrationDate($date)
    // {
    //     // Convert date to Y-m-d format if needed
    //     $this->company_registration_date = $date;
    // }

    public function clearError($field)
    {
        $this->resetErrorBag($field);
    }
    public function updatedCountry($value)
    {
        $this->reset(['states', 'selectedStates', 'selectedStatesData', 'cities', 'selectedCities', 'selectedCitiesData']);

        if (!$value) {
            $this->time_zone = '';
            $this->currency = '';
            return;
        }

        try {
            $selectedCountry = Country::with('states')->find($value);

            if ($selectedCountry) {
                $this->states = $selectedCountry->states->sortBy('name')->values();
                $this->currency = ($selectedCountry->currency ?? '') . ' (' . ($selectedCountry->currency_symbol ?? '') . ')';

                $timeZones = json_decode($selectedCountry->timezones, true);
                $this->time_zone = (!empty($timeZones) && isset($timeZones[0]))
                    ? $timeZones[0]['abbreviation'] . ' (' . $timeZones[0]['gmtOffsetName'] . ')'
                    : '';
            } else {
                $this->time_zone = '';
                $this->currency = '';
            }
        } catch (QueryException $e) {
            $this->time_zone = '';
            $this->currency = '';
        }
    }

    public function selectState($id, $name)
    {
        if (count($this->selectedStates) >= 5) return;

        if (!in_array($id, $this->selectedStates, true)) {
            $this->selectedStates[] = (int) $id;
            $this->selectedStatesData[] = ['id' => (int) $id, 'name' => $name];

            // Optimized city fetching
            $this->cities = City::whereIn('state_id', $this->selectedStates)
                ->orderBy('name', 'asc')
                ->get();
        }

        $this->resetErrorBag('selectedStates');
        $this->stateDropdownOpen = false;
    }

    public function selectCity($id, $name)
    {
        if (count($this->selectedCities) >= 5) return;

        if (!in_array($id, array_column($this->selectedCitiesData, 'id'), true)) {
            $this->selectedCities[] = (int) $id;
            $this->selectedCitiesData[] = ['id' => (int) $id, 'name' => $name];
        }

        $this->resetErrorBag('selectedCities');
        $this->cityDropdownOpen = false;
    }

    public function removeState($id)
    {
        $id = (int) $id;

        // Remove the selected state
        $this->selectedStates = array_values(array_diff($this->selectedStates, [$id]));
        $this->selectedStatesData = array_values(array_filter($this->selectedStatesData, fn($s) => $s['id'] !== $id));

        // Optimized city fetching
        $cities = City::whereIn('state_id', $this->selectedStates)->get();
        $this->cities = $cities;

        // Filter out cities that no longer belong to selected states
        $validCityIds = $cities->pluck('id')->toArray();
        $this->selectedCities = array_values(array_intersect($this->selectedCities, $validCityIds));
        $this->selectedCitiesData = array_values(array_filter($this->selectedCitiesData, fn($c) => in_array($c['id'], $validCityIds, true)));

        $this->resetErrorBag('selectedStates');
        $this->resetErrorBag('selectedCities');
    }

    public function removeCity($id)
    {
        $id = (int) $id;

        $this->selectedCities = array_values(array_diff($this->selectedCities, [$id]));
        $this->selectedCitiesData = array_values(array_filter($this->selectedCitiesData, fn($c) => $c['id'] !== $id));

        $this->resetErrorBag('selectedCities');
    }

    public function toggleStateDropdown()
    {
        $this->stateDropdownOpen = !$this->stateDropdownOpen;
    }

    public function toggleCityDropdown()
    {
        $this->cityDropdownOpen = !$this->cityDropdownOpen;
    }

    public function updatedSameAsPresent($value)
    {
        $this->company_permanent_address = $value ? $this->company_present_address : '';
    }

    // When is_parent is updated, fetch companies
    public function updatedIsParent($value)
    {
        if ($value) { // If parent is selected
            $this->reset(['companyList', 'parent_company_id']);
        } else { // If not a parent, fetch existing companies
            $this->companyList = Company::pluck('company_name', 'company_id')->toArray();
        }
    }



    public function getCountries()
    {
        try {
            $this->countries = Country::all();
        } catch (QueryException $e) {
            $this->countries = collect([]); // Empty collection to prevent errors
        }
    }

    public function saveCompanyInfo()
    {
        $this->validate();

        // Find existing company or set to null
        $company = $this->company_id ? Company::where('company_id', $this->company_id)->first() : null;

        // Generate a new company ID if not provided
        $newCompanyId = $this->company_id ?? $this->generateCompanyId();

        $companyData = [
            'company_id' => $newCompanyId,
            'company_name' => $this->company_name,
            'company_industry' => $this->company_industry,
            'company_type' => $this->company_type,
            'country' => $this->country,
            'time_zone' => $this->time_zone,
            'currency' => $this->currency,
            'company_present_address' => $this->company_present_address,
            'company_permanent_address' => $this->company_permanent_address,
            'pf_no' => $this->pf_no,
            'tan_no' => $this->tan_no,
            'pan_no' => $this->pan_no,
            'esi_no' => $this->esi_no,
            'lin_no' => $this->lin_no,
            'gst_no' => $this->gst_no,
            'company_registration_no' => $this->company_registration_no,
            'company_website' => $this->company_website,
            'state' => json_encode($this->selectedStatesData), // Store states as JSON
            'branch_locations' => json_encode($this->selectedCitiesData), // Store branch locations as JSON
            'company_logo' => $this->company_logo
                ? base64_encode(file_get_contents($this->company_logo->getRealPath()))
                : ($company ? $company->company_logo : null), // Preserve existing logo
            'company_registration_date' => $this->company_registration_date
                ? Carbon::parse($this->company_registration_date)->format('Y-m-d')
                : null, // Store formatted date

            // Newly added fields
            'ceo_name' => $this->ceo_name,
            'contact_email' => $this->contact_email,
            'contact_phone' => $this->contact_phone,
            'is_parent' => $this->is_parent ? 'yes' : 'no', // Convert boolean to ENUM value
            'parent_company_id' => $this->is_parent ? null : $this->parent_company_id, // Null if parent
            'email_domain' => $this->email_domain,
        ];

        // Save or update company details
        Company::updateOrCreate(['company_id' => $newCompanyId], $companyData);

        // Show success message
        FlashMessageHelper::flashSuccess('Company details saved successfully.');

        // Reset the form fields
        $this->resetForm();
    }


    /**
     * Generate a unique 8-digit company ID starting with "99"
     */
    private function generateCompanyId()
    {
        do {
            $companyId = '99' . mt_rand(100000, 999999); // Generates "99XXXXXX" (8 digits)
        } while (Company::where('company_id', $companyId)->exists()); // Ensure uniqueness

        return $companyId;
    }

    public function editCompanyInfo($id)
    {
        $company = Company::find($id);

        if (!$company) {
            return;
        }

        $this->fill($company->toArray());

        $stateIds = is_string($company->state) ? json_decode($company->state, true) : [];
        $cityIds = is_string($company->branch_locations) ? json_decode($company->branch_locations, true) : [];

        // Extract IDs correctly
        $stateIds = is_array($stateIds) ? array_column($stateIds, 'id') : [];
        $cityIds = is_array($cityIds) ? array_column($cityIds, 'id') : [];

        // Retrieve state and city details
        $this->selectedStatesData = State::whereIn('id', $stateIds)->get(['id', 'name'])->toArray();
        $this->selectedCitiesData = City::whereIn('id', $cityIds)->get(['id', 'name'])->toArray();


        $this->company_id = $company->company_id;

        $this->viewMode = false;
    }


    public function viewCompanyInfo($id)
    {
        $company = Company::find($id);

        if (!$company) {
            return;
        }

        // Decode JSON fields safely
        $stateIds = is_string($company->state) ? json_decode($company->state, true) : [];
        $cityIds = is_string($company->branch_locations) ? json_decode($company->branch_locations, true) : [];

        // Ensure state and city IDs are simple arrays
        $stateIds = is_array($stateIds) ? array_map('intval', array_column($stateIds, 'id')) : [];
        $cityIds = is_array($cityIds) ? array_map('intval', array_column($cityIds, 'id')) : [];

        // Retrieve selected states and cities with their names
        $this->selectedStatesData = State::whereIn('id', $stateIds)->get(['id', 'name'])->toArray();
        $this->selectedCitiesData = City::whereIn('id', $cityIds)->get(['id', 'name'])->toArray();
        $this->fill($company->toArray());

        // Assign company ID and set view mode
        $this->company_id = $company->company_id;
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
            'company_industry',
            'country',
            'time_zone',
            'currency',
            'company_present_address',
            'company_permanent_address',
            'pf_no',
            'tan_no',
            'pan_no',
            'esi_no',
            'lin_no',
            'gst_no',
            'company_registration_no',
            'company_website',
            'company_logo',
            'viewMode',
            'company_registration_date',
            'company_type',
            'email_domain',
            'ceo_name',
            'contact_email',
            'contact_phone',
            'is_parent',

        ]);
        // Explicitly reset JSON fields (arrays)
        $this->selectedStatesData = [];
        $this->selectedCitiesData = [];
    }

    public function getIndustries()
    {
        $this->industries = IndustryType::pluck('name', 'id')->toArray();
    }
    public function getCompanyTypes()
    {
        $this->companyTypes = CompanyType::pluck('name', 'id')->toArray();
    }


    public function render()
    {
        $this->getIndustries();
        $this->getCompanyTypes();
        $this->getCountries();
        $savedCompanies = Company::where('company_name', 'like', '%' . $this->search . '%')
            ->where('status', 1)
            ->paginate(5);
        // dd($savedCompanies->pluck('company_logo'));

        // Adjust the number of items per page
        return view('livewire.company-info', compact('savedCompanies'));
    }
}

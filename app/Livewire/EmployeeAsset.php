<?php

namespace App\Livewire;

use App\Models\Asset;
use Livewire\Component;
use App\Models\EmployeeDetails;
use App\Models\HelpDesks;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\WithFileUploads;
class EmployeeAsset extends Component
{
    use WithFileUploads;

    public $searchTerm = '';
    public $editingJobProfile=false;
    public $editingCompanyProfile=false;
    public $Jobmode;
    public $Designation;
    public $selected_equipment;
    public $BloodGroup;
    public $MaritalStatus;
    public $ItRequestaceessDialog = false;
    public $closeItRequestaccess = false;
    public $openItRequestaccess = false;
    public $isNames = false;
    public $record;
    public $subject;
    public $Mobile;
    public $hrempid;
    public $description;
    public $companyname;
    public $priority;
    public $activeTab = 'active';
    public $image;
    public $selectedPerson = null;
    public $cc_to;
    public $peoples;
    public $filteredPeoples;
    public $selectedPeopleNames = [];
    public $selectedPeople = [];
    public $records;
    public $peopleFound = true;
    public $file_path;
    public $justification;
    public $information;
    public $department;
    public $nickName;
  
    public $gender;
    public $name;
    public $showSuccessMessage = false;
   

    public $Email;
 
   
    public $editingProfile = false;
  
    public $employees;
    public $emp_id;
    public $asset_type;
    public $asset_status;
    public $asset_details;
    public $issue_date;
    public $asset_id;
    public $valid_till;
    public $asset_value;
    public $returned_on;
    public $remarks;
   
    public $employeeDetails = [];
    public $employeeIds = [];
    public $showDetails = true;
    public $editingField = false;
  
    
    public function toggleDetails()
    {
        $this->showDetails = !$this->showDetails;
    }


    public function filter()
    {
        $companyId = Auth::user()->company_id;
        $trimmedSearchTerm = trim($this->searchTerm);

        $this->filteredPeoples = EmployeeDetails::where('company_id', $companyId)
            ->where(function ($query) use ($trimmedSearchTerm) {
                $query->where(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', '%' . $trimmedSearchTerm . '%')
                    ->orWhere('emp_id', 'like', '%' . $trimmedSearchTerm . '%');
            })
            ->get();

        $this->peopleFound = count($this->filteredPeoples) > 0;
    }

    public function updatedSelectedPeople()
    {
        $this->cc_to = implode(', ', array_unique($this->selectedPeopleNames));
    }
    public function setEmpId($emp_id)
    {
        $this->emp_id = $emp_id;
    }
  public function mount()
    {
        $hrempid = auth()->guard('hr')->user()->emp_id;
         // Step 1: Retrieve the logged-in user's emp_id
         $loggedInEmpID = auth()->guard('hr')->user()->emp_id;

         // Step 2: Retrieve the company_id associated with the logged-in emp_id
         $companyID = EmployeeDetails::where('emp_id', $loggedInEmpID)
             ->pluck('company_id')
             ->first(); // Assuming company_id is unique for emp_id
 
         // Step 3: Fetch all emp_id values where company_id matches the logged-in user's company_id
         $this->employeeIds = EmployeeDetails::where('company_id', $companyID)
         ->orderBy('first_name')
         ->orderBy('last_name') ->pluck('emp_id')
             ->toArray();
     
             $this->employees = EmployeeDetails::whereIn('emp_id', $this->employeeIds)->get();
    
        $companyId = auth()->guard('hr')->user()->company_id;

      
         
           
    }
    

    

    public function NamesSearch()
    {
        $this->isNames = true;
        $this->selectedPeopleNames = [];
        $this->cc_to = '';
    }

    public function closePeoples()
    {
        $this->isNames = false;
    }

 


    public $selectedPeopleData=[];
    public function selectPerson($emp_id)
    {
        try {
            // Ensure $this->selectedPeople is initialized as an array
            if (!is_array($this->selectedPeople)) {
                $this->selectedPeople = [];
            }
    
            // Find the selected person from the list of employees
            $selectedPerson = $this->employees->where('emp_id', $emp_id)->first();
    
            if ($selectedPerson) {
                // Create the person's name string
                $personName = $selectedPerson->first_name . ' ' . $selectedPerson->last_name . ' #(' . $selectedPerson->emp_id . ')';
    
                if (in_array($emp_id, $this->selectedPeople)) {
                    // Person is already selected, so remove them
                    $this->selectedPeople = array_diff($this->selectedPeople, [$emp_id]);
    
                    // Remove the person's entry from the combined data
                    $this->selectedPeopleData = array_filter(
                        $this->selectedPeopleData,
                        fn($data) => $data['emp_id'] !== $emp_id
                    );
                } else {
                    // Person is not selected, so add them
                    $this->selectedPeople[] = $emp_id;
    
                    // Determine the image URL
                    if ($selectedPerson->image && $selectedPerson->image !== 'null') {
                        $imageUrl = 'data:image/jpeg;base64,' . base64_encode($selectedPerson->image);
                    } else {
                        // Add default image based on gender
                        if ($selectedPerson->gender == "Male") {
                            $imageUrl = asset('images/male-default.png');
                        } elseif ($selectedPerson->gender == "Female") {
                            $imageUrl = asset('images/female-default.jpg');
                        } else {
                            $imageUrl = asset('images/user.jpg');
                        }
                    }
    
                    // Add the person's data to the combined array
                    $this->selectedPeopleData[] = [
                        'name' => $personName,
                        'image' => $imageUrl,
                        'emp_id' => $emp_id
                    ];
                }
    
                // Update the cc_to field with the unique names
                $this->cc_to = implode(', ', array_unique(array_column($this->selectedPeopleData, 'name')));
            }
        } catch (\Exception $e) {
            // Handle the exception
            // Optionally, you can log the error or display a user-friendly message
            $this->dispatchBrowserEvent('error', ['message' => 'An error occurred: ' . $e->getMessage()]);
        }
    }
    
    public function create()
    {
      
        $emp_id = $this->selectedPeople[0] ?? null; // or however you are managing selected people

        // Check if the selected person exists
 
        $selectedPerson = EmployeeDetails::find($emp_id);
       
        $this->validate([
            'asset_type' => 'required',
            'asset_status' => 'required',
            'asset_details' => 'required|string|max:255',
            'issue_date' => 'required',
            'asset_id' => 'required',
            'valid_till' => 'required',
            'asset_value' => 'required',
            'returned_on' => 'required',
            'remarks' => 'required|string|max:255',
        ]);
        
        // Check if the selected person exists
        if ($selectedPerson) {
            // Create the asset record with the correct emp_id
            Asset::create([
                'emp_id' => $emp_id, // Use the provided emp_id
                'asset_type' => $this->asset_type,
                'asset_status' => $this->asset_status,
                'asset_details' => $this->asset_details,
                'issue_date' => $this->issue_date,
                'asset_id' => $this->asset_id,
                'valid_till' => $this->valid_till,
                'asset_value' => $this->asset_value,
                'returned_on' => $this->returned_on,
                'remarks' => $this->remarks,
            ]);
  
            // Debugging statement
           
            
            // Flash a success message
            session()->flash('message', 'Asset record created successfully.');
            
            // Reset form fields
            $this->reset();
        } else {
            // If the selected person doesn't exist, handle the error accordingly
            // For example, you could display an error message or log the error
        }
    }
    
    public function render()
    {
        $hrempid = auth()->guard('hr')->user()->hr_emp_id;
        $companyId = auth()->guard('hr')->user()->company_id;

        $this->peoples = EmployeeDetails::where('company_id', $companyId)->get();
        $peopleData = $this->filteredPeoples ?: $this->peoples;

        $this->record = HelpDesks::all();
        $employeeName = auth()->guard('hr')->user()->employee_name . ' #(' . $hrempid . ')';

        $this->records = HelpDesks::with('emp')
            ->where(function ($query) use ($hrempid, $employeeName) {
                $query->where('emp_id', $hrempid)
                    ->orWhere('cc_to', 'LIKE', "%$employeeName%");
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('livewire.employee-asset', [
            'peopleData' => $peopleData,
            'records' => $this->records
        ]);
    }

}

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
    public $selectedEmployeeId;
    public $searchEmployee;
    public $peopleData=[];

    public $employeess;
    public $selectedEmployeeLastName;

    public $selectedEmployeeFirstName;
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
    public $people;
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
        // Retrieve the logged-in user's emp_id from the 'hr' guard
        if (auth()->guard('hr')->check()) {
            $loggedInEmpID = auth()->guard('hr')->user()->emp_id;
        } else {
            // Debug if user is not authenticated
        
            return;
        }
    
        // Retrieve the company_id associated with the logged-in emp_id
        $employeeDetails = EmployeeDetails::where('emp_id', $loggedInEmpID)->first();
        
        if (!$employeeDetails) {
            // Debug if no employee details are found for this emp_id
      
            return;
        }
    
        $companyID = $employeeDetails->company_id;
    
        if (!$companyID) {
            // Handle the case where companyID is null
        
            $this->employeeIds = [];
            return;
        }
    
        // Fetch all emp_id values where company_id matches the logged-in user's company_id
        $this->employeeIds = EmployeeDetails::whereJsonContains('company_id', $companyID)->pluck('emp_id')->toArray();
    
        if (empty($this->employeeIds)) {
            // Handle the case where no employees are found
         
            return;
        }
    
        // Initialize employees based on search term and company_id
        $employeesQuery = EmployeeDetails::whereJsonContains('company_id', $companyID)
            ->where(function ($query) {
                $query->where('first_name', 'like', '%' . $this->searchTerm . '%')
                      ->orWhere('last_name', 'like', '%' . $this->searchTerm . '%')
                      ->orWhere('emp_id', 'like', '%' . $this->searchTerm . '%');
            })
            ->orderBy('first_name')
            ->orderBy('last_name');
    
        // Fetch the employees
        $employees = $employeesQuery->get();
  
        if ($employees->isEmpty()) {
            // Handle the case where no employees match the search term
         
        }
    
        // Debug output for fetched employees
     
    
        // Set the component's employee data
        $this->employees = $employees;
        $this->employeess = EmployeeDetails::whereJsonContains('company_id', $companyID)
        ->orderBy('hire_date', 'desc') // Order by hire_date descending
      
        ->take(5) // Limit to 5 records
        ->get();
        // Initialize other properties
        $this->peopleData = $this->filteredPeoples ? $this->filteredPeoples : $this->employees;
        $this->selectedPeople = [];
        $this->selectedPeopleNames = [];
    }
    
       public function searchforEmployee()
    {
        if (!empty($this->searchTerm)) {
            // Fetch employees matching the search term
            $this->employees = EmployeeDetails::where(function ($query) {
                $query->where('first_name', 'like', '%' . $this->searchTerm . '%')
                      ->orWhere('last_name', 'like', '%' . $this->searchTerm . '%')
                      ->orWhere('emp_id', 'like', '%' . $this->searchTerm . '%');
            })->get();
    
            // Include previously selected employees not currently displayed in the search
            foreach ($this->selectedPeople as $selectedEmpId) {
                // Check if selected employee is in the current employees
                if (!$this->employees->contains('emp_id', $selectedEmpId)) {
                    $selectedEmployee = EmployeeDetails::where('emp_id', $selectedEmpId)->first();
                    if ($selectedEmployee) {
                        // Ensure it's marked as checked
                        $selectedEmployee->isChecked = true;
                        $this->employees->push($selectedEmployee);
                    }
                }
            }
    
            // Set isChecked for employees in the current search results
            foreach ($this->employees as $employee) {
                $employee->isChecked = in_array($employee->emp_id, $this->selectedPeople);
   
            }
        } else {
            $this->employees = collect(); // Reset employees if no search term
        }
    }

    
    
    

    
    
    

   
    public function closeEmployeeBox()
    {
        $this->searchEmployee;
       
       
    }
    public function clearSelectedEmployee()
    {
        $this->selectedEmployeeId='';
        $this->selectedEmployeeFirstName='';
        $this->selectedEmployeeLastName='';
        $this->searchTerm='';
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
    public function removePerson($empId)
    {
        // Remove the person from the selectedPeople array
        if (($key = array_search($empId, $this->selectedPeople)) !== false) {
            unset($this->selectedPeople[$key]);
        }
        
        // Reindex the array to avoid gaps in the index
        $this->selectedPeople = array_values($this->selectedPeople);
    
        // Update the selectedPeopleData array to remove the person
        $this->selectedPeopleData = collect($this->selectedPeopleData)->filter(function ($person) use ($empId) {
            return $person['emp_id'] !== $empId;
        })->values()->toArray(); // Reindexing the selectedPeopleData
    
        // Optionally, update the employees list or other data if necessary
        $this->employees = $this->employees->filter(function ($employee) use ($empId) {
            return $employee->emp_id !== $empId;
        });
     
    }
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
 

    
    
    

    
    
    
    public function updateselectedEmployee($empId)
    {
        $this->selectedEmployeeId = $empId;
        $this->selectedEmployeeFirstName = EmployeeDetails::where('emp_id', $empId)->value('first_name');
        $this->selectedEmployeeLastName = EmployeeDetails::where('emp_id', $empId)->value('last_name');
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
            'issue_date' => 'required|date',
            'valid_till' => 'nullable|date|after:issue_date',
            'returned_on' => 'nullable|date|after_or_equal:issue_date',
            'asset_value' => 'required|numeric',
            'remarks' => 'nullable|string|max:255',
        ], [
            'asset_type.required' => ' Asset type is required.',
            'asset_status.required' => ' Asset status is required.',
            'asset_details.required' => ' Asset details are required.',
            'asset_details.string' => ' Asset details must be a string.',
            'asset_details.max' => ' Asset details may not be greater than 255 characters.',
            'issue_date.required' => ' Issue date is required.',
            'issue_date.date' => ' Issue date is not a valid date.',
            'valid_till.date' => ' Valid till date is not a valid date.',
            'valid_till.after' => ' Valid till date must be after the issue date.',
            'returned_on.date' => ' Returned on date is not a valid date.',
            'returned_on.after_or_equal' => ' Returned on date must be after or equal to the issue date.',
            'asset_value.required' => ' Asset value is required.',
           
            
            
        ]);
        // Check if the selected person exists
        if ($selectedPerson) {
            try {
                // Dynamically generate a unique asset_id with "ASS-" prefix
                $lastAsset = Asset::latest('created_at')->first(); // Using created_at instead of id
                $nextId = $lastAsset ? ((int)substr($lastAsset->asset_id, 4) + 1) : 1; // Extract numeric part from asset_id
                $generatedAssetId = 'ASS-' . str_pad($nextId, 3, '0', STR_PAD_LEFT); // Generates 'ASS-001', 'ASS-002', etc.
    
                // Create the asset record with the correct emp_id
                Asset::create([
                    'emp_id' => $emp_id,
                    'asset_type' => $this->asset_type,
                    'asset_status' => $this->asset_status,
                    'asset_details' => $this->asset_details,
                    'issue_date' => $this->issue_date,
                    'asset_id' => $generatedAssetId, // Use the generated asset_id
                    'valid_till' => $this->valid_till,
                    'asset_value' => $this->asset_value, // Ensure this value is numeric
                    'returned_on' => $this->returned_on,
                    'remarks' => $this->remarks,
                ]);
    
                // Flash a success message
                session()->flash('message', 'Asset record created successfully.');
    
                // Reset form fields
                return redirect()->to(path: '/hr/employee-asset');
            } catch (\Exception $e) {
                // Log the error or handle it accordingly
                Log::error('Asset creation failed: ' . $e->getMessage());
    
                // Display an error message to the user
                session()->flash('error', 'Failed to create asset record. Please try again.');
            }
        } else {
            // Handle case where selected person doesn't exist
            session()->flash('error', 'Selected person not found.');
        }
    }
    
    
    public function render()
    {
        $loggedInEmpID = auth()->guard('hr')->user()->emp_id;
        $employeeId = auth()->user()->emp_id;
        $companyId = auth()->user()->company_id;
    
        // Retrieve the company_id associated with the logged-in emp_id
        $companyID = EmployeeDetails::where('emp_id', $loggedInEmpID)
            ->pluck('company_id')
            ->first(); // Assuming company_id is unique for emp_id
  
        // If no search term, fetch all employees for the logged-in user's company
        if (empty($this->searchTerm)) {
            $this->employees = EmployeeDetails::whereJsonContains('company_id', $companyID)->get();
        }
    
        // Determine if there are people found
        $peopleFound = $this->employees->count() > 0;
        return view('livewire.employee-asset', [
            'employees' => $this->employees,
            'selectedPeople' => $this->selectedPeople,
            'peopleFound' => $peopleFound,
            'searchTerm' => $this->searchTerm,
        ]);
    }

}

<?php

namespace App\Livewire;

use App\Models\EmployeeDetails;
use App\Models\HelpDesks;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\WithFileUploads;

class PositionHistory extends Component
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
    public $employeeIds;
    public $employeess;
    public $justification;
    public $information;
    public $department;
    public $nickName;
    public $editingPersonalProfile=false;
    public $editingLocationProfile=false;
    public $gender;
    public $name;
    public $showSuccessMessage = false;
   
    public $wishMeOn;
    public $selectedTimeZone;
    public $timeZones;
    public $biography;
    public $faceBook;
    public $twitter;
    public $Email;
    public $Location;
    public $recentHires = [];
    public $linkedIn;
    public $editingProfile = false;
    public $editingTimeZone = false;
    public $editingBiography = false;
    public $editingSocialMedia = false;
    public $employees;

    public $oldPassword;
    public $newPassword;
    public $confirmNewPassword;
    public $passwordChanged = false;
    public $employeeDetails = [];
    
    public $showDetails = true;
    public $editingField = false;
    
        public function editProfile($emp_id)
    {
    
       
        $selectedPerson = EmployeeDetails::find($emp_id);
        $selectedPerson = $this->employeeDetails[$emp_id] ?? null;
        $this->editingProfile= true;
      
        if ($selectedPerson) {
            $this->department = $selectedPerson->department ?? '';
         
          
        }
     
       
    }
  

    public function cancelProfile($emp_id)
    {
        // No need to query for $selectedPerson here
        $selectedPerson = EmployeeDetails::find($emp_id);
        $selectedPerson = $this->employeeDetails[$emp_id] ?? null;
        if ($selectedPerson) {
        $this->editingProfile= false;
        }
       
    }
    
    public function saveProfile($emp_id)
    {
        // No need to query for $selectedPerson here
        $selectedPerson = EmployeeDetails::find($emp_id);
        $selectedPerson = $this->employeeDetails[$emp_id] ?? null;
    
        if ($selectedPerson) {
            $selectedPerson->departmet = $this->department;
    
            $selectedPerson->save();
         
            $this->editingProfile = false;
        }
    }
    public function editJobProfile($emp_id)
    {
       
        $selectedPerson = EmployeeDetails::find($emp_id);
        $selectedPerson = $this->employeeDetails[$emp_id] ?? null;
        $this->editingJobProfile= true;
      
        if ($selectedPerson) {
            $this->Jobmode = $selectedPerson->job_mode ?? '';
         
          
        }
     
       
    }
  

    public function cancelJobProfile($emp_id)
    {
        // No need to query for $selectedPerson here
        $selectedPerson = EmployeeDetails::find($emp_id);
        $selectedPerson = $this->employeeDetails[$emp_id] ?? null;
        if ($selectedPerson) {
        $this->editingJobProfile= false;
        }
       
    }
    
    public function saveJobProfile($emp_id)
    {
        // No need to query for $selectedPerson here
        $selectedPerson = EmployeeDetails::find($emp_id);
        $selectedPerson = $this->employeeDetails[$emp_id] ?? null;
    
        if ($selectedPerson) {
            $selectedPerson->job_mode= $this->Jobmode;
    
            $selectedPerson->save();
         
            $this->editingJobProfile = false;
        }
    }
 // Currently editing profile ID
    
    // Fetch employee details and personal info for editing
    public function editCompanyProfile($emp_id)
    {
        
        $this->currentEditingPersonalCompanyProfileId = $emp_id; // Set current employee for editing
        $employee = EmployeeDetails::find($emp_id);
        $personalInfo = DB::table('emp_personal_infos')->where('emp_id', $emp_id)->first();
        if ($employee && $personalInfo) {
            $this->companyname = $personalInfo->company_name ?? '';  // Load current company name
        } else {
            $this->companyname = ''; // Fallback if no personal info is found
        }
    }
    
    // Save updated company profile
    public function saveCompanyProfile($emp_id)
    {
     
        try {
            // Fetch employee details from EmployeeDetails table
            $employee = EmployeeDetails::find($emp_id);
        
            // Fetch personal info from emp_personal_infos table
            $personalInfo = DB::table('emp_personal_infos')->where('emp_id', $emp_id)->first();
        
            if ($employee) {
       
                $employee->save();
        
                // Update title in emp_personal_infos table
                if ($personalInfo) {
                    DB::table('emp_personal_infos')
                        ->where('emp_id', $emp_id)
                        ->update([
                            'company_name' => $this->companyname,
                            
                        ]);
                }
        
                $this->currentEditingPersonalCompanyProfileId = null; // Exit edit mode after saving
                session()->flash('message', 'Profile updated successfully!');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred while updating the profile. Please try again.');
        }
    }
    
    // Cancel editing company profile
    public function cancelCompanyProfile()
    {
       
        $this->currentEditingPersonalCompanyProfileId = null;  // Exit edit mode without saving
    }
    
    public function editLocationProfile($emp_id)
    {
       
        $selectedPerson = EmployeeDetails::find($emp_id);
        $selectedPerson = $this->employeeDetails[$emp_id] ?? null;
        $this->editingLocationProfile= true;
      
        if ($selectedPerson) {
            $this->Location = $selectedPerson->job_location ?? '';
         
          
        }
     
       
    }
  

    public function cancelLocationProfile($emp_id)
    {
        // No need to query for $selectedPerson here
        $selectedPerson = EmployeeDetails::find($emp_id);
        $selectedPerson = $this->employeeDetails[$emp_id] ?? null;
        if ($selectedPerson) {
        $this->editingLocationProfile= false;
        }
       
    }
    
    public function saveLocationProfile($emp_id)
    {
        // No need to query for $selectedPerson here
        $selectedPerson = EmployeeDetails::find($emp_id);
        $selectedPerson = $this->employeeDetails[$emp_id] ?? null;
    
        if ($selectedPerson) {
            $selectedPerson->job_location = $this->Location;
    
            $selectedPerson->save();
         
            $this->editingLocationProfile = false;
        }
    }
    public function editpersonalProfile($emp_id)
    {
        $selectedPerson = EmployeeDetails::find($emp_id);
        $selectedPerson = $this->employeeDetails[$emp_id] ?? null;
        $this->editingPersonalProfile= true;
      
        if ($selectedPerson) {
          
           $this->Designation = $selectedPerson->job_title ?? '';
         
        }
      
       
    }
    
    public function cancelpersonalProfile($emp_id)
    {
        // No need to query for $selectedPerson here
        $selectedPerson = EmployeeDetails::find($emp_id);
        $selectedPerson = $this->employeeDetails[$emp_id] ?? null;
        if ($selectedPerson) {
        $this->editingPersonalProfile= false;
        }
       
    }

    public function savepersonalProfile($emp_id)
    {
        // No need to query for $selectedPerson here
        $selectedPerson = EmployeeDetails::find($emp_id);
        $selectedPerson = $this->employeeDetails[$emp_id] ?? null;
    
        if ($selectedPerson) {
        
              $selectedPerson->job_title = $this->Designation;
           
            $selectedPerson->save();
         
            $this->editingPersonalProfile = false;
        }
    }
    
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
    
    public function closeMessage()
    {
        $this->showSuccessMessage = false;
    }
    public function updateProfile($emp_id)
    {
        try {
            $this->validate([
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:1024', // 1024 kilobytes = 1 megabyte
            ]);
    
            $employee = EmployeeDetails::where('emp_id', $emp_id)->first();
    
            if ($this->image) {
                $imagePath = file_get_contents($this->image->getRealPath());
                $employee->image = $imagePath;
                $employee->save();
            }
            
            $this->showSuccessMessage = true;
        } catch (\Exception $e) {
            Log::error('Error in updateProfile method: ' . $e->getMessage());
            session()->flash('error', 'An error occurred while updating the profile. Please try again later.');
        }
    }
    

    

    public function NamesSearch()
    {
        $this->isNames = true;
        $this->selectedPeopleNames = [];
        $this->cc_to = '';
    }

    public function toggle()
    {

        $this->isNames = true;


        $this->selectedPeopleNames = [];

        $this->cc_to = '';
    }
    public function closePeoples()
    {
        $this->isNames = false;
    }

 
    
    
    public $currentEditingCompanyProfileId = null; // Track the employee ID for editing profile
    public $currentEditingPersonalCompanyProfileId = null; 


    
    
    
  
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
     
           
    
        $companyId = auth()->guard('hr')->user()->company_id;
    
        $this->employeess = EmployeeDetails::where('company_id', $companyID)
        ->orderBy('hire_date', 'desc') // Order by hire_date descending
      
        ->take(5) // Limit to 5 records
        ->get();
      
         
           
    }
    

    public function render()
{
    $loggedInEmpID = auth()->guard('hr')->user()->emp_id;

    // Retrieve the company_id associated with the logged-in emp_id
    $companyID = EmployeeDetails::where('emp_id', $loggedInEmpID)
        ->pluck('company_id')
        ->first(); // Assuming company_id is unique for emp_id

    // Fetch all employees where company_id matches the logged-in user's company_id
    $this->employeeIds = EmployeeDetails::where('company_id', $companyID)
        ->pluck('emp_id')
        ->toArray();
      
    // Fetch employee details based on IDs
    $this->employees = EmployeeDetails::whereIn('emp_id', $this->employeeIds)->get();
          $this->employeess = EmployeeDetails::where('company_id', $companyID)
        ->orderBy('hire_date', 'desc') // Order by hire_date descending
      
        ->take(5) // Limit to 5 records
        ->get();
    $this->employeeDetails = EmployeeDetails::with(['empBankDetails', 'empParentDetails', 'empPersonalInfo','empSpouseDetails'])
    ->where('emp_id', $this->employeeIds)
    ->first();
  
    // Ensure $peopleData is set
    $peopleData = $this->filteredPeoples ?:  $this->employeeIds;

    return view('livewire.position-history', [
        'employees' => $this->employees,
        'peopleData' => $peopleData,
        'records' => $this->records,
        'selectedPeople' => $this->selectedPeople, // Add selectedPeople to view data
        'cc_to' => $this->cc_to, // Add cc_to to view data
    ]);
}



}
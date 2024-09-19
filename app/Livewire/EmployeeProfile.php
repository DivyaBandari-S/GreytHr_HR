<?php

namespace App\Livewire;

use App\Models\EmployeeDetails;
use App\Models\EmpPersonalInfo;
use App\Models\HelpDesks;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\WithFileUploads;

class EmployeeProfile extends Component
{
    use WithFileUploads;
    public $employeeName;

    public $searchTerm = '';
    public $selectedPeopleImages = [];  
    public $selected_equipment;
    public $BloodGroup;
    public $extension;
    public $personalInfo;
    public $empId;
    public $employeeId;
    public $MaritalStatus;
    public $isNames = false;
    public $record;
    public $subject;
    public $Mobile;
    public $hrempid;
    public $description;
    public $showDetails = true;
    public $priority;
    public $activeTab = 'active';
    public $image;
    public $selectedPerson = null;
    public $emergency_contact;
    public $cc_to;
    public $peoples;
    public $peopleData;
    public $filteredPeoples;
    public $selectedPeopleNames = [];
    public $selectedPeople = [];
    public $records;
    public $peopleFound = true;
    public $file_path;
    public $justification;
    public $title;

    public $information;
    public $nickName;
    public $editingPersonalProfile=false;
    public $gender;
    public $name;
    public $employeess;
    public $showSuccessMessage = false;
   
    public $wishMeOn;
    public $employeeIds = [];
    public $first_name;
    public $last_name;
    public $selectedTimeZone;
    public $timeZones;
    public $biography;
    public $faceBook;
    public $twitter;
    public $Email;
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

    public function closePeoples()
    {
        $this->isNames = false;
    }

 
    
    
    public $currentEditingProfileId = null; // Track the employee ID for editing profile
    public $currentEditingPersonalProfileId = null; 
    public function editProfile($emp_id)
    {
        $this->currentEditingProfileId = $emp_id; // Set current employee for editing
    
        // Fetch employee details including related empPersonalInfo
        $employee = EmployeeDetails::with('empPersonalInfo')->find($emp_id);
  
        if ($employee) {
            // Access personalInfo data using the relationship
            $personalInfo = $employee->empPersonalInfo;
    
            // Set form fields with employee and personal info data
            $this->title = $personalInfo->title ?? '';  // Use relationship data
            $this->nickName = $personalInfo->nick_name ?? '';
            $this->gender = $employee->gender ?? '';
            $this->name = $employee->first_name . ' ' . $employee->last_name ?? '';
            $this->emergency_contact = $employee->emergency_contact ?? '';
            $this->Email = $employee->email ?? '';
            $this->extension = $employee->extension ?? '';
        }
    }
    

    
    public function saveProfile($emp_id)
    {
        try {
            // Fetch employee details along with empPersonalInfo
            $employee = EmployeeDetails::with('empPersonalInfo')->find($emp_id);
    
            if ($employee) {
                // Split name into first_name and last_name if necessary
                $nameParts = explode(' ', $this->name);
                $firstName = $nameParts[0];
                $lastName = isset($nameParts[1]) ? $nameParts[1] : '';
    
                // Check for duplicate email
                if ($this->Email !== '') {
                    $existingEmail = EmployeeDetails::where('email', $this->Email)
                        ->where('emp_id', '!=', $emp_id) // Exclude the current employee
                        ->first();
    
                    if ($existingEmail) {
                        session()->flash('error', 'This email is already taken by another employee.');
                        return;  // Stop further execution
                    }
                }
    
                // Check for duplicate mobile number (emergency_contact)
                if ($this->emergency_contact !== '') {
                    $existingMobile = EmployeeDetails::where('emergency_contact', $this->emergency_contact)
                        ->where('emp_id', '!=', $emp_id) // Exclude the current employee
                        ->first();
    
                    if ($existingMobile) {
                        session()->flash('error', 'This mobile number is already taken by another employee.');
                        return;  // Stop further execution
                    }
                }
    
                // Update EmployeeDetails fields
                $employee->gender = $this->gender;
                $employee->first_name = $firstName;
                $employee->last_name = $lastName;
                $employee->emergency_contact = $this->emergency_contact;
                $employee->email = $this->Email === '' ? null : $this->Email; // Temporarily assign null if empty
                $employee->extension = $this->extension;
                $employee->save();
    
                // Update empPersonalInfo fields if it exists
                $personalInfo = $employee->empPersonalInfo;
                if ($personalInfo) {
                    $personalInfo->title = $this->title;
                    $personalInfo->nick_name = $this->nickName;
                    $personalInfo->save();
                } else {
                    // Optionally, create new personal info if it doesn't exist
                    EmpPersonalInfo::create([
                        'emp_id' => $emp_id,
                        'title' => $this->title,
                        'nick_name' => $this->nickName,
                    ]);
                }
    
                $this->currentEditingProfileId = null; // Exit edit mode after saving
                session()->flash('message', 'Profile updated successfully!');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred while updating the profile. Please try again.');
        }
    }
    
    
    
    
    
    public function cancelProfile()
    {
        $this->currentEditingProfileId = null;// Cancel editing
    }
    public $dob;
    public function editpersonalProfile($emp_id)
    {
        $this->currentEditingPersonalProfileId = $emp_id; // Set current employee for editing
    
        // Fetch employee details from EmployeeDetails table
        $employee = EmployeeDetails::find($emp_id);
        $personalInfo = DB::table('emp_personal_infos')->where('emp_id', $emp_id)->first();
    
        $this->editingPersonalProfile = true;
    
        if ($employee && $personalInfo) {
            $this->dob = $personalInfo->date_of_birth ?? '';
            $this->BloodGroup = $personalInfo->blood_group ?? '';
            $this->MaritalStatus = $personalInfo->marital_status ?? '';
        }
    }
    
    public function savepersonalProfile($emp_id)
    {
        try {
            // Fetch employee details from EmployeeDetails table
            $employee = EmployeeDetails::find($emp_id);
            $personalInfo = DB::table('emp_personal_infos')->where('emp_id', $emp_id)->first();
    
            if ($employee && $personalInfo) {
                // Update employee-related data if necessary (you can add more here)
                $employee->save();
    
                // Update personal info in emp_personal_infos table
                DB::table('emp_personal_infos')
                    ->where('emp_id', $emp_id)
                    ->update([
                        'date_of_birth' => $this->dob,
                        'blood_group' => $this->BloodGroup,
                        'marital_status' => $this->MaritalStatus,
                    ]);
  
                // Clear the editing mode
                $this->currentEditingPersonalProfileId = null;
               
            }
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred while updating the profile. Please try again.');
        }
    }
    
    


    
 
    public function cancelpersonalProfile($emp_id)
    {
        // No need to query for $selectedPerson here
      
        $this->currentEditingPersonalProfileId = null; 
        
       
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

    return view('livewire.employee-profile', [
        'employees' => $this->employees,
        'peopleData' => $peopleData,
        'records' => $this->records,
        'personalInfo' => $this->personalInfo,
        'selectedPeople' => $this->selectedPeople, // Add selectedPeople to view data
        'cc_to' => $this->cc_to, // Add cc_to to view data
    ]);
}


}

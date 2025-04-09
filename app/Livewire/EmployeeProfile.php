<?php

namespace App\Livewire;

use App\Helpers\FlashMessageHelper;
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

    public $searchTerm='';
    public $searchEmployee;
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
    public $peopleData =[];
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
    public $selectedOption = 'all'; 
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


    public $selectedEmployeeId='';

    public $employeeBox=1;

    public $selectedEmployeeFirstName;
    public $selectedEmployeeImage;

    public $year;

    public $month;
    public $selectedEmployeeLastName;


    public function toggleDetails()
    {
        $this->showDetails = !$this->showDetails;
    }

   
    

    
    
    
    public function updatesearchTerm()
    {
        $this->searchTerm= $this->searchTerm;
        $this->searchforEmployee();
       
    }


    public $selectedPeopleData=[];
    public function filter()
    {

        $employeeId = auth()->user()->emp_id;

        $companyId = Auth::user()->company_id;


        $this->peopleData = EmployeeDetails::where('first_name', 'like', '%' . $this->searchTerm . '%')
            ->orWhere('last_name', 'like', '%' . $this->searchTerm . '%')
            ->orWhere('emp_id', 'like', '%' . $this->searchTerm . '%')
            ->get();

        $this->filteredPeoples = $this->searchTerm ? $this->employees : null;

        // Filter records based on category and search term
        $this->records = HelpDesks::with('emp')
            ->whereHas('emp', function ($query) {
                $query->where('first_name', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('last_name', 'like', '%' . $this->searchTerm . '%');
            })

            ->orderBy('created_at', 'desc')
            ->get();
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
            if (!empty($this->selectedPeople) && !in_array($emp_id, $this->selectedPeople)) {
                // Flash an error message to the session
                FlashMessageHelper::flashWarning('You can only select one employee ');
                return; // Stop further execution
            }
    
            if ($selectedPerson) {
                // Check if person is already selected
                if (in_array($emp_id, $this->selectedPeople)) {
                    // Person is already selected, so remove them
    
                    // Remove from selectedPeople array
                    $this->selectedPeople = array_diff($this->selectedPeople, [$emp_id]);
    
                    // Remove the person's entry from the selectedPeopleData array
                    $this->selectedPeopleData = array_filter(
                        $this->selectedPeopleData,
                        fn($data) => $data['emp_id'] !== $emp_id
                    );
                } else {
                    // Person is not selected, so add them
                    $this->selectedPeople[] = $emp_id;
    
                    // Create the person's name string
                    $personName = $selectedPerson->first_name . ' ' . $selectedPerson->last_name . ' #(' . $selectedPerson->emp_id . ')';
    
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
            $this->dispatch('error', ['message' => 'An error occurred: ' . $e->getMessage()]);
        }
    }
    
    
    public function closeMessage()
    {
        $this->showSuccessMessage = false;
    }
    public function updateProfile($emp_id)
    {
        try {
            // Validate if the image is present, and if present, ensure it's an image of the allowed types
            $this->validate([
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:1024', // 1 MB max file size
            ]);
    
            $employee = EmployeeDetails::where('emp_id', $emp_id)->first();
    
            // Check if an image is selected
            if ($this->image) {
                // Get the file content and encode it to Base64
                $imagePath = file_get_contents($this->image->getRealPath());
                $employee->image = base64_encode($imagePath);
    
                // Save the employee details
                $employee->save();
    
                // Show the success message only if a file is chosen and successfully saved
                $this->showSuccessMessage = true;
    
                // Clear the image input after updating
                $this->reset('image');
            } else {
                // Do not show success message if no image is selected
                $this->showSuccessMessage = false;
            }
    
        } catch (\Exception $e) {
            // Log the error and show an error message
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
              
            }
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred while creating new record. Please try again.');
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
        $employee = EmployeeDetails::with('empPersonalInfo')->find($emp_id);
    
        $this->editingPersonalProfile = true;
    
        if ($employee ) {
            $personalInfo = $employee->empPersonalInfo;
            $this->dob = $personalInfo->date_of_birth ?? '';
            $this->BloodGroup = $personalInfo->blood_group ?? '';
            $this->MaritalStatus = $personalInfo->marital_status ?? '';
        }
    }
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
    
        // Clear the selected employee details
        $this->selectedEmployeeId = null;
        $this->selectedEmployeeFirstName = null;
        $this->selectedEmployeeLastName = null;
        $this->selectedEmployeeImage = null;
    
        // Optionally clear the search term
        $this->searchTerm = '';
    
        // This will ensure the correct UI updates (removes selected employee and displays search input)
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
    
    
    public function updateSelected($option)
    {
        $this->selectedOption = $option; 
        
        // Check if the user is logged in with the 'hr' guard
        if (!auth()->guard('hr')->check()) {
            return;
        }
    
        // Get the logged-in employee ID
        $loggedInEmpID = auth()->guard('hr')->user()->emp_id;
    
        // Fetch the first company_id associated with the logged-in employee
        $companyId = EmployeeDetails::where('emp_id', $loggedInEmpID)
            ->pluck('company_id') 
            ->first();
    
        // Handle cases where the company ID is an array or not
        if (is_array($companyId)) {
            $firstCompanyID = $companyId[0]; 
        } else {
            $firstCompanyID = $companyId; 
        }
    
        // Initialize the query for employees based on company_id
        $query = EmployeeDetails::whereJsonContains('company_id', $firstCompanyID);
    
        // Apply the filters based on the selected option
        switch ($this->selectedOption) {
            case 'current':
                $query->where('employee_status', 'active'); // Filter for current employees
                break;
    
            case 'past':
                $query->whereIn('employee_status', ['rejected', 'terminated']); // Filter for past employees
                break;
    
            case 'intern':
                $query->where('job_role', 'intern'); // Filter for interns
                break;
    
           
            default:
                // No additional filtering, fetch all employees
                case 'all':
                    $query=EmployeeDetails::whereJsonContains('company_id', $firstCompanyID);
                break;
        }
    
        // Fetch the employee IDs after filtering
        $this->employeeIds = $query->pluck('emp_id')->toArray(); // Fetch the filtered employee IDs
        $this->employees = $query->get(); // Fetch the employee data for rendering in the view
  
    
    

    
   }
    public function cancelpersonalProfile($emp_id)
    {
        // No need to query for $selectedPerson here
      
        $this->currentEditingPersonalProfileId = null; 
        
       
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

    
   
    public function searchforEmployee() {
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

    public function updateselectedEmployee($empId)
    {
        // If more than one employee is selected, only allow the first employee to be selected
        if (count($this->selectedPeople) > 1) {
            $this->selectedPeople = array_slice($this->selectedPeople, 0, 1); // Keep only the first selected employee
        } else {
            // If employee is not already selected, proceed with selecting
            if (!in_array($empId, $this->selectedPeople)) {
                $this->selectedPeople[] = $empId; // Add employee to the selected list
            } else {
                // If employee is already selected, remove from the list
                $this->selectedPeople = array_filter($this->selectedPeople, fn($id) => $id != $empId);
            }
        }
    
        // Update the selected employee details
        $this->selectedEmployeeId = $empId;
        $this->selectedEmployeeFirstName = EmployeeDetails::where('emp_id', $empId)->value('first_name');
        $this->selectedEmployeeLastName = EmployeeDetails::where('emp_id', $empId)->value('last_name');
        $this->selectedEmployeeImage = EmployeeDetails::where('emp_id', $empId)->value('image');
      
        
    }

    public function selectEmployee($empId)
    {
        
        $this->selectedEmployeeId = $empId;
        $this->selectedEmployeeFirstName = EmployeeDetails::where('emp_id', $empId)->value('first_name');
        $this->selectedEmployeeLastName = EmployeeDetails::where('emp_id', $empId)->value('last_name');
        $this->selectedEmployeeImage = EmployeeDetails::where('emp_id', $empId)->value('image');
        $this->searchTerm='';
    }


public $selectedEmployee = null;

public function removeSelectedEmployee()
{
    $this->selectedEmployeeId = null;
    $this->selectedEmployeeFirstName = null;
    $this->selectedEmployeeLastName = null;
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

    
    
    public function render()
    {
        $loggedInEmpID = auth()->guard('hr')->user()->emp_id;
        $employeeId = auth()->user()->emp_id;
        if (auth()->guard('hr')->check()) {
            $loggedInEmpID = auth()->guard('hr')->user()->emp_id;
            // Debugging to ensure correct emp_id is fetched
       // Check if emp_id is correct
        } else {
            return;
        }
       
   
        
    
        $loggedInEmpID = auth()->guard('hr')->user()->emp_id;

        // Fetch the company_id associated with the employee
        $companyID = EmployeeDetails::where('emp_id', $loggedInEmpID)
            ->pluck('company_id')
            ->first(); // This will return the first company ID for the employee
        
 
        $companyId = EmployeeDetails::where('emp_id', $loggedInEmpID)
        ->pluck('company_id') // This returns the array of company IDs
        ->first();
        if (is_array($companyId)) {
            $firstCompanyID = $companyId[0]; // Get the first element from the array
        } else {
            $firstCompanyID = $companyId; // Handle case where it's not an array
        }
                    


        // Determine if there are people found
        $peopleFound = $this->employees->count() > 0;
    
        return view('livewire.employee-profile', [
            'employees' => $this->employees,
            'selectedPeople' => $this->selectedPeople,
            'peopleFound' => $peopleFound,
            'searchTerm' => $this->searchTerm,
            // Add any other necessary data for your view here
        ]);
    }

}




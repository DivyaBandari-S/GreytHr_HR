<?php

namespace App\Livewire;

use App\Models\EmployeeDetails;
use App\Models\EmpPersonalInfo;
use App\Models\EmpSubDepartments;
use App\Models\HelpDesks;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\WithFileUploads;

class PositionHistory extends Component
{
    use WithFileUploads;
    public $peopleData;
    public $selectedEmployeeImage;
    public $selectedOption = 'all'; 
    public $selectedEmployeeId='';
    public $present_address;

    public $searchTerm = '';
    public $editingJobProfile=false;
    public $editingCompanyProfile=false;
    public $Jobmode;
    public $designation;
    public $selected_equipment;
    public $BloodGroup;
    public $MaritalStatus;
    public $people;
    public $ItRequestaceessDialog = false;
    public $closeItRequestaccess = false;
    public $openItRequestaccess = false;
    public $showDialog=false;
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
    public $selectedEmployeeFirstName;
    public $selectedEmployeeLastName;
    public $searchEmployee;
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
    public $currentEditingProfileId=null;
    public $currentEditingJobProfileId=null;
    public $currentEditingDomainProfileId=null;
    public $Domain=[];
    public function editProfile($emp_id)
    {
        $this->currentEditingJobProfileId = $emp_id; // Set current employee for editing
    
        // Fetch employee details including related empPersonalInfo
        $employee = EmployeeDetails::with('empPersonalInfo')->find($emp_id);
  
        if ($employee) {
      
    
            // Set form fields with employee and personal info data
            $this->Location = $employee->job_location ?? '-';  // Use relationship data
            $this->Jobmode = $employee->job_mode ?? '-';
          
        }
    }
    public function open()
    {
       
        $this->showDialog = true;
    }
    public function close()
    {
        $this->showDialog = false;
       
      
    }

    public function cancelProfile($emp_id)
    {
        // No need to query for $selectedPerson here
        $this->currentEditingJobProfileId = null;
       
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
    
             
                $employee->job_mode = $this->Jobmode ?? '';
                $employee->job_location = $this->Location ?? '-';
           
                // Update EmployeeDetails fields
              
                $employee->save();
    
    
                $this->currentEditingJobProfileId = null; // Exit edit mode after saving
              
            }
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred while creating new record. Please try again.');
        }
    }
    public function editDomainProfile($emp_id)
    {
        $this->currentEditingDomainProfileId = $emp_id; // Set current employee for editing
    
        // Fetch employee details including related empPersonalInfo
        $employee = EmployeeDetails::with('empPersonalInfo')->find($emp_id);
    
        if ($employee) {
            // Set form fields with employee and personal info data
            // Ensure Domain is a string
            $this->Domain = is_array($employee->emp_domain) 
                            ? implode(', ', $employee->emp_domain) 
                            : $employee->emp_domain ?? '-';
        }
    }
    
    public function saveDomainProfile($emp_id)
    {
        try {
            // Fetch employee details along with empPersonalInfo
            $employee = EmployeeDetails::with('empPersonalInfo')->find($emp_id);
    
            if ($employee) {
                // Split name into first_name and last_name if necessary
                $nameParts = explode(' ', $this->name);
                $firstName = $nameParts[0];
                $lastName = isset($nameParts[1]) ? $nameParts[1] : '';
    
                // Convert Domain to string if it's an array
                $employee->emp_domain = is_array($this->Domain) 
                                        ? implode(', ', $this->Domain) 
                                        : $this->Domain ?? '-';
    
                // Update EmployeeDetails fields
                $employee->save();
    
                $this->currentEditingDomainProfileId = null; // Exit edit mode after saving
            }
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred while creating new record. Please try again.');
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

    public $currentEditingPersonalSubProfileId=null;
    public $currentEditingDepPersonalProfileId=null;
    public $currentEditingPersonalProfileId=null;
    public $subDepartment;
    public function editDepartmentProfile($emp_id)
    {
        $this->currentEditingDepPersonalProfileId = $emp_id; // Set current employee for editing
    
        // Fetch employee details including related empPersonalInfo and empSubDepartment
        $employee = EmployeeDetails::with('empDepartment')->find($emp_id);
    
        if ($employee) {
            // Access the subDepartment data using the relationship
            $department = $employee->empDepartment;
    
            // Set the form field with the sub-department's name or ID
            $this->department = $department ? $department->department : '';
    }
}
    // Save updated company profile
    public function saveDepartmentProfile($emp_id)
    {
        try {
            // Fetch employee details along with empSubDepartment
            $employee = EmployeeDetails::with('empSubDepartment')->find($emp_id);
    
            if ($employee) {
                // Check if employee has a sub-department associated
                $department = $employee->empDepartment;
    
                if ($department) {
                    // Update the sub-department with new data
                    $department->department = $this->department ?? ''; // Update with new sub-department name
                    $department->save();
                } else {
                    // If no sub-department exists, create a new one
                    $employee->empSubDepartment()->create([
                        'department' => $this->department ?? '', // Set the new sub-department name
                    ]);
                }
    
              
                // Exit edit mode after saving
                $this->currentEditingDepPersonalProfileId = null;
    
                session()->flash('success', 'Sub-department updated successfully.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred while updating the profile. Please try again.');
        }
    }
    
    
    // Cancel editing company profile
    public function cancelDepartmentProfile()
    {
       
        $this->currentEditingDepPersonalProfileId = null;  // Exit edit mode without saving
    }
    public function editSubDepartmentProfile($emp_id)
    {
        $this->currentEditingPersonalSubProfileId = $emp_id; // Set the current employee for editing
    
        // Fetch employee details including related empSubDepartment
        $employee = EmployeeDetails::with('empSubDepartment')->find($emp_id);
    
        if ($employee) {
            // Access the subDepartment data using the relationship
            $subDepartment = $employee->empSubDepartment;
    
            // Set the form field with the sub-department's name or ID
            $this->subDepartment = $subDepartment ? $subDepartment->sub_department : ''; // Use the sub-department name
        }
    }
    
    public $empSubDepartment;
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
    // Save updated company profile
    public function saveSubDepartmentProfile($emp_id)
    {
        try {
            // Fetch employee details along with empSubDepartment
            $employee = EmployeeDetails::with('empSubDepartment')->find($emp_id);
    
            if ($employee) {
                // Check if employee has a sub-department associated
                $subDepartment = $employee->empSubDepartment;
    
                if ($subDepartment) {
                    // Update the sub-department with new data
                    $subDepartment->sub_department = $this->subDepartment ?? ''; // Update with new sub-department name
                    $subDepartment->save();
                } else {
                    // If no sub-department exists, create a new one
                    $employee->empSubDepartment()->create([
                        'sub_department' => $this->subDepartment ?? '', // Set the new sub-department name
                    ]);
                }
    
                // Update other employee details if needed
                $employee->job_location = $this->Location ?? '';
                $employee->save();
    
                // Exit edit mode after saving
                $this->currentEditingPersonalSubProfileId = null;
    
                session()->flash('success', 'Sub-department updated successfully.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred while updating the profile. Please try again.');
        }
    }

    
    // Cancel editing company profile
    public function cancelSubDepartmentProfile()
    {
       
        $this->currentEditingPersonalSubProfileId = null;  // Exit edit mode without saving
    }
    
    public function editCompanyProfile($emp_id)
    {
        $this->currentEditingPersonalCompanyProfileId = $emp_id; // Set current employee for editing
    
        // Fetch employee details including related empPersonalInfo
        $employee = EmployeeDetails::with('empPersonalInfo')->find($emp_id);
  
        if ($employee) {
            // Access personalInfo data using the relationship
            $personalInfo = $employee->empPersonalInfo;
    
            // Set form fields with employee and personal info data
            $this->companyname = $personalInfo->company_name ?? '';  // Use relationship data
        
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
    public $currentEditingAddressProfileId=null;
    public function editAddressProfile($emp_id)
    {
        $this->currentEditingAddressProfileId = $emp_id; // Set current employee for editing
    
        // Fetch employee details including related empPersonalInfo
        $employee = EmployeeDetails::with('empPersonalInfo')->find($emp_id);
  
        if ($employee) {
            // Access personalInfo data using the relationship
            $personalInfo = $employee->empPersonalInfo;
    
            // Set form fields with employee and personal info data
            $this->present_address = $personalInfo->present_address ?? '';  // Use relationship data
        
        }
    }
    // Save updated company profile
    public function saveAddressProfile($emp_id)
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
                            'present_address' => $this->present_address,
                            
                        ]);
                }
        
                $this->currentEditingAddressProfileId = null; // Exit edit mode after saving
                session()->flash('message', 'Profile updated successfully!');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred while updating the profile. Please try again.');
        }
    }
    
    // Cancel editing company profile
    public function cancelAddressProfile()
    {
       
        $this->currentEditingAddressProfileId = null;  // Exit edit mode without saving
    }
    public $currentEditinglocationProfileId;
    public function editlocationProfile($emp_id)
    {
        $this->currentEditinglocationProfileId = $emp_id; // Set current employee for editing
    
        // Fetch employee details including related empPersonalInfo
        $employee = EmployeeDetails::with('empPersonalInfo')->find($emp_id);
  
        if ($employee) {
            // Access personalInfo data using the relationship
            $personalInfo = $employee->empPersonalInfo;
    
            // Set form fields with employee and personal info data
            $this->Location = $employee->job_location ?? '';  // Use relationship data
         
          
        }
    }
    public function cancellocationProfile($emp_id)
    {
        // No need to query for $selectedPerson here
        $this->currentEditinglocationProfileId = null;
       
    }

    
    public function savelocationProfile($emp_id)
    {
        try {
            // Fetch employee details along with empPersonalInfo
            $employee = EmployeeDetails::with('empPersonalInfo')->find($emp_id);
    
            if ($employee) {
                // Split name into first_name and last_name if necessary
                $nameParts = explode(' ', $this->name);
                $firstName = $nameParts[0];
                $lastName = isset($nameParts[1]) ? $nameParts[1] : '';
    
             
                $employee->job_location = $this->Location ?? '';
                // Update EmployeeDetails fields
              
                $employee->save();
    
                // Update empPersonalInfo fields if it exists
               
    
                $this->currentEditinglocationProfileId = null; // Exit edit mode after saving
              
            }
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred while creating new record. Please try again.');
        }
    }  
    public $currentEditingdesignationProfileId=null;
  

    public function editdesignationProfile($emp_id)
    {
        $this->currentEditingdesignationProfileId = $emp_id; // Set current employee for editing
    
        // Fetch employee details including related empPersonalInfo
        $employee = EmployeeDetails::with('empPersonalInfo')->find($emp_id);
    
        if ($employee) {
            // Access personalInfo data using the relationship
            $personalInfo = $employee->empPersonalInfo;
    
            // Set form fields with employee and personal info data
            $this->designation = $personalInfo->designation ?? '';  // Use relationship data
        }
    }
    
    public function canceldesignationProfile($emp_id)
    {
        // No need to query for $selectedPerson here
        $this->currentEditingdesignationProfileId = null;
       
    }

    
    public function savedesignationProfile($emp_id)
    {
        try {
            // Fetch employee details along with empPersonalInfo
            $employee = EmployeeDetails::with('empPersonalInfo')->find($emp_id);
    
            if ($employee) {
                // Split name into first_name and last_name if necessary
                $nameParts = explode(' ', $this->name);
                $firstName = $nameParts[0];
                $lastName = isset($nameParts[1]) ? $nameParts[1] : '';
    
             
                $personalInfo = $employee->empPersonalInfo;
                if ($personalInfo) {
                    $personalInfo->designation = $this->designation;
       
                    $personalInfo->save();
                // Update EmployeeDetails fields
                }
          
    
                // Update empPersonalInfo fields if it exists
               
    
                $this->currentEditingdesignationProfileId = null; // Exit edit mode after saving
              
            }
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred while creating new record. Please try again.');
        }
    }  
    

    public function toggleDetails()
    {
        $this->showDetails = !$this->showDetails;
    }

    public function updatesearchTerm()
    {
        $this->searchTerm= $this->searchTerm;
       
       
    }
    public function updatedSelectedPeople()
    {
        $this->cc_to = implode(', ', array_unique($this->selectedPeopleNames));
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
    $this->searchTerm='';
    
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
        if (!empty($this->selectedPeople) && !in_array($emp_id, $this->selectedPeople)) {
            // Flash an error message to the session
            session()->flash('warning', 'You can only select one employee ');
            return; // Stop further execution
        }
    

        try {
         
            // Ensure $this->selectedPeople is initialized as an array
            if (!is_array($this->selectedPeople)) {
                $this->selectedPeople = [];
            }
    
         
            // Find the selected person from the list of employees
            $selectedPerson = $this->employees->where('emp_id', $emp_id)->first();
    
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
        // Retrieve the logged-in user's emp_id from the 'hr' guard
        if (auth()->guard('hr')->check()) {
            $loggedInEmpID = auth()->guard('hr')->user()->emp_id;
            // Debugging to ensure correct emp_id is fetched
       // Check if emp_id is correct
        } else {
            return;
        }
        $loggedInEmpID = auth()->guard('hr')->user()->emp_id;
        $companyId = EmployeeDetails::where('emp_id', $loggedInEmpID)
->pluck('company_id') // This returns the array of company IDs
->first();
if (is_array($companyId)) {
    $firstCompanyID = $companyId[0]; // Get the first element from the array
} else {
    $firstCompanyID = $companyId; // Handle case where it's not an array
}


        // Fetch the company_id associated with the employee
        $companyID = EmployeeDetails::where('emp_id', $firstCompanyID)
            ->pluck('company_id')
            ->first(); // This will return the first company ID for the employee

        // Outputs the company_id based on whether it's a parent or not
        
    
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
        $this->employeeIds = EmployeeDetails::whereJsonContains('company_id', $firstCompanyID)->pluck('emp_id')->toArray();
     

   
   
        // Fetch the employee IDs after filtering
       
        
        if (empty($this->employeeIds)) {
            // Handle the case where no employees are found
         
            return;
        }
    
        // Initialize employees based on search term and company_id
        $employeesQuery = EmployeeDetails::whereJsonContains('company_id', $firstCompanyID)
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
      

      
        $this->employeess = EmployeeDetails::whereJsonContains('company_id', $firstCompanyID)
        ->orderBy('hire_date', 'desc') // Order by hire_date descending
      
        ->take(5) // Limit to 5 records
        ->get();
        // Initialize other properties
        $this->peopleData = $this->filteredPeoples ? $this->filteredPeoples : $this->employees;
        $this->selectedPeople = [];
        $this->selectedPeopleNames = [];
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
    
        return view('livewire.position-history', [
            'employees' => $this->employees,
            'selectedPeople' => $this->selectedPeople,
            'peopleFound' => $peopleFound,
            'searchTerm' => $this->searchTerm,
            // Add any other necessary data for your view here
        ]);
    }



}
<?php

namespace App\Livewire;

use App\Models\EmployeeDetails;
use App\Models\HelpDesks;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\WithFileUploads;
use  App\Models\ParentDetail;
class ParentDetails extends Component
{
    use WithFileUploads;

    public $searchTerm = '';
    public $peopleData =[];
    public $selectedEmployeeImage;
  
    public $selectedEmployeeId='';
    public $selectedOption = 'all'; 
    
    public $employeeName;


    public $searchEmployee;
    public $selectedPeopleImages = [];  

    public $selectedEmployeeFirstName;
  
    public $selectedEmployeeLastName;
    public $selectedEmployee;
    public $isNames = false;
    public $record;
    public $subject;
    public $FatherFirstName;
    public $FatherLastName;
    public $FatherDateOfBirth;
    public $MotherFirstName;
    public $MotherLastName;
    public $MotherDateOfBirth;
    public $MotherBloodGroup;
    public $MotherAddress;
   
    public $hrempid;
    public $description;
    public $showDetails = true;
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

    public $editingParentProfile=false;
    public $BankName;

    public $name;
    public $AccountNumber;

    public $Branch;
    public $showSuccessMessage = false;
   
    public $wishMeOn;
    public $employeess;
    public $employeeIds=[];
    public $selectedTimeZone;
    public $timeZones;
    public $biography;
    public $faceBook;
    public $FatherAddress;
    public $Email;
    


    public $recentHires = [];
    public $FatherBloodGroup;
    public $editingBankProfile = false;
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

    public function updatesearchTerm()
    {
        $this->searchTerm= $this->searchTerm;
       
       
    }
    public function updatedSelectedPeople()
    {
        $this->cc_to = implode(', ', array_unique($this->selectedPeopleNames));
    }
    public function selectEmployee($empId)
    {
        
        $this->selectedEmployeeId = $empId;
        $this->selectedEmployeeFirstName = EmployeeDetails::where('emp_id', $empId)->value('first_name');
        $this->selectedEmployeeLastName = EmployeeDetails::where('emp_id', $empId)->value('last_name');
        $this->selectedEmployeeImage = EmployeeDetails::where('emp_id', $empId)->value('image');
        $this->searchTerm='';
    }
    public $selectedPeopleData=[];
    
    public function NamesSearch()
    {
        $this->isNames = true;
        $this->selectedPeopleNames = [];
        $this->cc_to = '';
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
    public function closePeoples()
    {
        $this->isNames = false;
    }
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
            $this->dispatchBrowserEvent('error', ['message' => 'An error occurred: ' . $e->getMessage()]);
        }
    }
   public $currentEditingParentProfile; 
 
   public $fatherName;
   public $FatherDOB;
   public $MotherDOB;
   public $motherName;
  
   public function editParentProfile($emp_id)
   {
       $this->currentEditingParentProfile = $emp_id; // Set current employee for editing
   
       // Fetch employee details along with parent details
       $employee = EmployeeDetails::with('empParentDetails')->find($emp_id);
      
       $this->editingParentProfile = true;
   
       if ($employee && $employee->empParentDetails) {
        $parentInfo = $employee->empParentDetails;
        
        // Set the values from the correct fields
        $this->FatherFirstName = $parentInfo->father_first_name ?? '';
   
        $this->FatherLastName = $parentInfo->father_last_name ?? '';
        $this->FatherDOB = $parentInfo->father_dob ?? '';
        $this->FatherAddress = $parentInfo->father_address ?? '';
        $this->MotherFirstName = $parentInfo->mother_first_name ?? '';
        $this->MotherLastName = $parentInfo->mother_last_name ?? '';
        $this->MotherDOB = $parentInfo->mother_dob ?? '';
        $this->MotherAddress = $parentInfo->mother_address ?? '';

        $this->editingParentProfile = true;
    } else {
        // Handle case when no parent details are found
        session()->flash('error', 'No parent details found for this employee.');
    }


   }
   
   public function saveParentProfile($emp_id)
   {
       try {
           // Fetch employee details along with parent details
           $employee = EmployeeDetails::with('empParentDetails')->find($emp_id);
   
           if ($employee && $employee->empParentDetails) {
               // Update parent details in the `emp_parent_details` table
               $parentInfo = $employee->empParentDetails;
   
               $parentInfo->father_first_name = $this->FatherFirstName;
               $parentInfo->father_last_name = $this->FatherLastName;
               $parentInfo->father_dob = $this->FatherDOB;
               $parentInfo->father_address = $this->FatherAddress;
               $parentInfo->mother_first_name = $this->MotherFirstName;
               $parentInfo->mother_last_name = $this->MotherLastName;
               $parentInfo->mother_dob = $this->MotherDOB;
               $parentInfo->mother_address = $this->MotherAddress;
               // Save the updated parent information
               $parentInfo->save();
   
               // Clear editing state
               $this->currentEditingParentProfile = null;
              
   
               // Flash success message
               session()->flash('success', 'Parent profile updated successfully.');
           } else {
               // Handle the case where no parent details are found
               session()->flash('error', 'No parent details found for this employee.');
           }
       } catch (\Exception $e) {
           // Handle any exceptions that occur during saving
           session()->flash('error', 'An error occurred while updating the parent profile. Please try again.');
       }
   }
   
   
    public function cancelParentProfile()
    {
        // No need to query for $selectedPerson here
      
     
        $this->currentEditingParentProfile = null; 
       
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

        return view('livewire.parent-details', [
            'employees' => $this->employees,
            'selectedPeople' => $this->selectedPeople,
            'peopleFound' => $peopleFound,
            'records' => $this->records
        ]);
    }

}
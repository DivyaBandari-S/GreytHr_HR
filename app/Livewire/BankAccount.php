<?php

namespace App\Livewire;

use App\Models\EmployeeDetails;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;
class BankAccount extends Component
{
    use WithFileUploads;

    public $searchTerm = '';
    public $peopleData =[];
  
    public $selectedEmployeeId='';
    
    public $employeeName;


    public $searchEmployee;
    public $selectedPeopleImages = [];  

    public $selectedEmployeeFirstName;
  
    public $selectedEmployeeLastName;
    public $selectedEmployee;
    public $isNames = false;
    public $record;
    public $subject;
 
   
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
   
    public $BankBranch;
    public $IFSCCode;
    public $BankAccountNumber;
    public $BankAddress;
    public $Branch;
    public $showSuccessMessage = false;
   
    public $wishMeOn;
    public $employeess;
    public $employeeIds=[];
    public $selectedTimeZone;
 
    


    public $recentHires = [];

    public $employees;

    public $employeeDetails = [];

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

    public $selectedPeopleData=[];
    
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
    public function filter()
    {

        $employeeId = auth()->user()->emp_id;

        $companyId = Auth::user()->company_id;


        $this->peopleData = EmployeeDetails::where('first_name', 'like', '%' . $this->searchTerm . '%')
            ->orWhere('last_name', 'like', '%' . $this->searchTerm . '%')
            ->orWhere('emp_id', 'like', '%' . $this->searchTerm . '%')
            ->get();

        $this->filteredPeoples = $this->searchTerm ? $this->employees : null;

  
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
   public $currentEditingBankProfile; 
 
   public $fatherName;
   public $FatherDOB;
   public $MotherDOB;
   public $motherName;
  
   public function editBankProfile($emp_id)
   {
       $this->currentEditingBankProfile = $emp_id; // Set current employee for editing
   
       // Fetch employee details along with parent details
       $employee = EmployeeDetails::with('empBankDetails')->find($emp_id);
       
       $this->editingParentProfile = true;
   
       if ($employee && $employee->empBankDetails) {
           $bankInfo = $employee->empBankDetails;
           
           // Set the values for bank fields
           $this->BankName = $bankInfo->bank_name ?? '';
           $this->BankBranch = $bankInfo->bank_branch ?? '';
           $this->IFSCCode = $bankInfo->ifsc_code ?? '';
           $this->BankAccountNumber = $bankInfo->account_number ?? '';
           $this->BankAddress = $bankInfo->bank_address ?? '';
   
           $this->editingParentProfile = true;
       } else {
           // Handle case when no bank details are found
           session()->flash('error', 'No bank details found for this employee.');
       }
   }
   
   public function saveBankProfile($emp_id)
   {
       try {
           // Fetch employee details along with bank details
           $employee = EmployeeDetails::with('empBankDetails')->find($emp_id);
   
           if ($employee && $employee->empBankDetails) {
               // Update bank details in the `emp_bank_details` table
               $bankInfo = $employee->empBankDetails;
   
               $bankInfo->bank_name = $this->BankName;
               $bankInfo->bank_branch = $this->BankBranch;
               $bankInfo->ifsc_code = $this->IFSCCode;
               $bankInfo->account_number = $this->BankAccountNumber;
               $bankInfo->bank_address = $this->BankAddress;
               // Save the updated bank information
               $bankInfo->save();
   
               // Clear editing state
               $this->currentEditingBankProfile = null;
   
               // Flash success message
               session()->flash('success', 'Bank profile updated successfully.');
           } else {
               // Handle the case where no bank details are found
               session()->flash('error', 'No bank details found for this employee.');
           }
       } catch (\Exception $e) {
           // Handle any exceptions that occur during saving
           session()->flash('error', 'An error occurred while updating the bank profile. Please try again.');
       }
   }
   
   
    public function cancelBankProfile()
    {
        // No need to query for $selectedPerson here
      
     
        $this->currentEditingBankProfile = null; 
       
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
        $this->selectedEmployeeId = $empId;
        $this->selectedEmployeeFirstName = EmployeeDetails::where('emp_id', $empId)->value('first_name');
        $this->selectedEmployeeLastName = EmployeeDetails::where('emp_id', $empId)->value('last_name');
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

        return view('livewire.bank-account', [
            'employees' => $this->employees,
            'selectedPeople' => $this->selectedPeople,
            'peopleFound' => $peopleFound,
            'records' => $this->records
        ]);
    }

}

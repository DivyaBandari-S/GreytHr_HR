<?php

namespace App\Livewire;

use Livewire\Component;
use App\Helpers\FlashMessageHelper;
use App\Models\Asset;

use App\Models\EmployeeDetails;
use App\Models\HelpDesks;
use App\Models\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\PreviousEmployement;

class PreviousEmployeement extends Component
{
    public $searchTerm = '';
    public $employee;
    public $filterData;
    public $selectedEmployeeId;
    public $showDocDialog=false;
    public $searchEmployee;
    public $peopleData=[];
    public $selectedEmployeeImage;
public $hire_date;
    public $employeess;
    public $selectedEmployeeLastName;

    public $selectedEmployeeFirstName;
    public $editingJobProfile=false;
    public $editingCompanyProfile=false;
    public $Jobmode;
    public $Designation;
    public $selected_equipment;

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
    
    public $currentEmpId;
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
    public $requests;
 
   
    public $editingProfile = false;
  
    public $employees;
    public $emp_id;
    public $showDialog=false;
    public $filePath;
   public $selectedOption = 'all'; 
   
    public $employeeDetails = [];
    public $employeeIds = [];
    public $showDetails = true;
    public $editingField = false;
    public $company_name, $designation, $from_date, $to_date, $years_of_experience,$months_of_experience;
    public $nature_of_duties, $leaving_reason, $pf_member_id, $last_drawn_salary;
    protected $rules = [
        'company_name' => 'required|string|max:255',
        'designation' => 'required|string|max:255',
        'from_date' => 'required|date',
        'to_date' => 'required|date|after_or_equal:from_date',
        'nature_of_duties' => 'nullable|string',
        'leaving_reason' => 'nullable|string',
        'pf_member_id' => 'nullable|string|max:50',
        'last_drawn_salary' => 'nullable|numeric|min:0',
    ];
    
    protected $messages = [
        'company_name.required' => 'Company name is required.',
        'designation.required' => 'Designation is required.',
        'from_date.required' => 'Start date is required.',
        'to_date.required' => 'End date is required.',
        'to_date.after_or_equal' => 'End date must be after or equal to start date.',
        'to_date.before' => 'End date must be before joining date.',
        'last_drawn_salary.numeric' => 'Salary must be a number.',
        'last_drawn_salary.min' => 'Salary must be at least 0.',
        'pf_member_id.max' => 'PF Member ID can’t exceed 50 characters.',
    ];

    public function updatesearchTerm()
    {
        $this->searchTerm= $this->searchTerm;
       
       
    }
    public function updatedSelectedPeople()
    {
        $this->cc_to = implode(', ', array_unique($this->selectedPeopleNames));
      
        
    }

    public $selectedPeopleData=[];
    public $activeTab1 = 'tab1';

    public function switchTab($tab)
    {
        $this->activeTab1 = $tab;
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
    public function addDocs()
    {
        $this->showDocDialog = true;
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
    public $combinedRequests=[];
 
    public function selectPerson($emp_id)
    {
        if (!empty($this->selectedPeople) && !in_array($emp_id, $this->selectedPeople)) {
            // Flash an error message to the session
            FlashMessageHelper::flashWarning('You can only select one employee ');
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
                    // After setting currentEmpId
    $this->currentEmpId = $emp_id;
    Log::info('Current emp_id set to: ' . $this->currentEmpId);
            }
        } catch (\Exception $e) {
            // Handle the exception
            // Optionally, you can log the error or display a user-friendly message
            $this->dispatch('error', ['message' => 'An error occurred: ' . $e->getMessage()]);
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

    
   public $showImageDialog = false;
   public $imageUrl;
   public function downloadImage()
   {
       if ($this->imageUrl) {
           // Decode the Base64 data if necessary
           $fileData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $this->imageUrl));

           // Determine MIME type and file extension
           $finfo = finfo_open(FILEINFO_MIME_TYPE);
           $mimeType = finfo_buffer($finfo, $fileData);
           finfo_close($finfo);

           $extension = '';
           switch ($mimeType) {
               case 'image/jpeg':
                   $extension = 'jpg';
                   break;
               case 'image/png':
                   $extension = 'png';
                   break;
               case 'image/gif':
                   $extension = 'gif';
                   break;
               default:
                   return abort(415, 'Unsupported Media Type');
           }

           // Prepare file name and response
           $fileName = 'image-' . time() . '.' . $extension;
           return response()->streamDownload(
               function () use ($fileData) {
                   echo $fileData;
               },
               $fileName,
               [
                   'Content-Type' => $mimeType,
                   'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
               ]
           );
       }
       return abort(404, 'Image not found');
   }
   public function showImage($url)
   {
       $this->imageUrl = $url;
       $this->showImageDialog = true;
   }

   public function closeImageDialog()
   {
       $this->showImageDialog = false;
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
        if (!empty($this->selectedEmployeeId)) {
   
            // Fetch all letter requests for the selected employee
            $this->requests = Previousemployement::whereIn('emp_id', (array)$this->selectedEmployeeId)->get();
        
           
       
        } else {
            $this->requests = collect(); // No selected employee, empty collection
            Log::info('No Employee Selected, Returning Empty Requests');
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
        $this->years_of_experience ;
        $this->months_of_experience ;
  
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
        if (!empty($this->selectedEmployeeId)) {
   
            // Fetch all letter requests for the selected employee
            $this->requests = Asset::whereIn('emp_id', (array)$this->selectedEmployeeId)->get();
        
            // Debugging output
            Log::info('Fetched Letter Requests: ' . $this->requests->toJson());
       
        } else {
            $this->requests = collect(); // No selected employee, empty collection
            Log::info('No Employee Selected, Returning Empty Requests');
        }
        
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
public $showPreviousDialog=false;
public function addPrevious()
{
    $this->resetForm();
    $this->showPreviousDialog = true;
}

public function close()
{
    $this->resetForm();
    $this->showPreviousDialog = false;
}
public function removeSelectedEmployee()
{
    $this->selectedEmployeeId = null;
    $this->selectedEmployeeFirstName = null;
    $this->selectedEmployeeLastName = null;
}

public function resetForm()
{
   
  
}

 




public function updated($propertyName)
{
    
    if ($propertyName == 'from_date' && $propertyName == 'to_date') {
        // Ensure both dates are set before calculating
        if ($this->from_date < $this->to_date) {
            $this->calculateYearsAndMonths();
        } else {
            // Calculate experience if dates are valid
            $this->calculateYearsAndMonths();
        }
        
    }
}

public function calculateYearsAndMonths()
{
    // Create DateTime objects for 'from_date' and 'to_date'
    $fromDate = date_create($this->from_date);
    $toDate = date_create($this->to_date);

    // Calculate the difference between the two dates
    $diff = date_diff($fromDate, $toDate);

    // Calculate years and months separately
    $years = $diff->y;
    $months = $diff->m;

    // Store the calculated values for years and months
    $this->years_of_experience = $years;
    $this->months_of_experience = $months;
}

public function saveExperience()
{
    $emp_id = $this->selectedPeople[0] ?? null;

    // Dynamic validation rule for to_date based on hire_date
    $this->validate([
        'company_name' => 'required|string|max:255',
        'designation' => 'required|string|max:255',
        'from_date' => 'required|date',
        'to_date' => 'required|date|after_or_equal:from_date|before:' . $this->hire_date,
        'nature_of_duties' => 'nullable|string',
        'leaving_reason' => 'nullable|string',
        'pf_member_id' => 'nullable|string|max:50',
        'last_drawn_salary' => 'nullable|numeric|min:0',
    ], $this->messages);

    if (!$emp_id) {
        session()->flash('error', 'Selected employee not found.');
        return;
    }

    $selectedPerson = EmployeeDetails::find($emp_id);

    if (!$selectedPerson) {
        session()->flash('error', 'Employee record not found.');
        return;
    }

    // Experience calculation
    $start = new \DateTime($this->from_date);
    $end = new \DateTime($this->to_date);
    $interval = $start->diff($end);
    $this->years_of_experience = $interval->y;
    $this->months_of_experience = $interval->m;

    // Save record
    PreviousEmployement::create([
        'emp_id' => $emp_id,
        'company_name' => $this->company_name,
        'designation' => $this->designation,
        'from_date' => $this->from_date,
        'to_date' => $this->to_date,
        'years_of_experience' => $this->years_of_experience,
        'months_of_experience' => $this->months_of_experience,
        'nature_of_duties' => $this->nature_of_duties,
        'leaving_reason' => $this->leaving_reason,
        'pf_member_id' => $this->pf_member_id,
        'last_drawn_salary' => $this->last_drawn_salary,
    ]);

    session()->flash('message', 'Experience details saved successfully.');
    $this->resetForm();
    $this->showPreviousDialog = false;
}

// ✅ Reset form method



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
        if (!empty($this->selectedEmployeeId)) {
   
            $this->requests = Previousemployement::whereIn('emp_id', (array)$this->selectedEmployeeId)->get();

       
        } else {
            $this->requests = collect(); // No selected employee, empty collection
           
        }
        $peopleFound = $this->employees->count() > 0;
        return view('livewire.previous-employeement',[
            'employees' => $this->employees,
            'selectedPeople' => $this->selectedPeople,
            'peopleFound' => $peopleFound,
            'searchTerm' => $this->searchTerm,
            'requests'=>$this->requests,
        ]);
    }
}

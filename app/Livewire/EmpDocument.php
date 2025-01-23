<?php

namespace App\Livewire;

use App\Helpers\FlashMessageHelper;
use App\Models\EmployeeDetails;
use App\Models\LetterRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Http\Request;
use App\Models\EmployeeDocument;

class EmpDocument extends Component
{
    use WithFileUploads;
    public $requests;
    public $showConfDialog=false;
     public $filePath;
    public $selectedOption = 'all'; 
    public $searchTerm = '';
    public $filter_option = 'All';
    public $filter_publishtype ='All';
    public $peopleData =[];
    public $empId;
  
    public $selectedEmployeeId='';
    
    public $employeeName;
    public $currentEmpId;


    public $searchEmployee;
    public $documents;
    public $selectedPeopleImages = [];  

    public $selectedEmployeeFirstName;
  
    public $selectedEmployeeLastName;
    public $selectedEmployee;
    public $showDocDialog=false;
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
   public $employeeId;
   public $publishToPortal;
    public $hrempid;
    public $fullName;
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

   
    public $showSuccessMessage = false;
   
  
    public $employeess;
    public $employeeIds=[];

  
    


    public $recentHires = [];

    public $employees;
   public $documentName;
   public $category;
 

    public $employeeDetails = [];
    public $editingField = false;
    protected $rules = [
        'documentName' => 'required|string|max:255',
        'category' => 'required|string',
        'description' => 'nullable|string',
    
    ];
    protected $messages = [
        'documentName' => 'Document name is required',
        'category' => 'Category is required',
        'description' => 'Description is required',
    
    ];
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
    public $activeTab1 = 'tab1'; // Default tab

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
  
  
       if (!empty($this->selectedEmployeeId)) {
      
        // Fetch all letter requests for the selected employee
        $this->requests = LetterRequest::whereIn('emp_id', (array)$this->selectedEmployeeId)->get();
        
        // Debugging output
        Log::info('Fetched Letter Requests: ' . $this->requests->toJson());
   
    } else {
        $this->requests = collect(); // No selected employee, empty collection
        Log::info('No Employee Selected, Returning Empty Requests');
    }
       // Adjust this line based on your actual database column for category
    
       $loggedInEmpID = auth()->guard('hr')->user()->emp_id;
       $companyId = EmployeeDetails::where('emp_id', $loggedInEmpID)
->pluck('company_id') // This returns the array of company IDs
->first();
if (is_array($companyId)) {
   $firstCompanyID = $companyId[0]; // Get the first element from the array
} else {
   $firstCompanyID = $companyId; // Handle case where it's not an array
}

$this->empId = auth()->user()->emp_id;
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
   public function updatedFilterOption()
   {
        // Debug: Ensure this is updating correctly
       $this->loadDocuments();  // Re-load documents when filter changes
   }
   public function loadDocuments()
   {
         // Debug to see the value of filter_option
       
       $query = EmployeeDocument::whereIn('employee_id', (array)$this->selectedEmployeeId)->orderBy('created_at', 'desc');
   
       if ($this->filter_option && $this->filter_option !== 'All') {
           $query->where('category', $this->filter_option);
       }
     
       $this->documents = $query->get(); 
        // Execute the query to get documents
   }
   public function updatedFilterPublishOption()
   {
        // Debug: Ensure this is updating correctly
       $this->publishType();  // Re-load documents when filter changes
   }
   public function publishType()
   {
       // Debugging the filter option
    
       
       $query = EmployeeDocument::where('employee_id', $this->currentEmpId)
           ->orderBy('created_at', 'desc');
   
       // Filter logic
       if ($this->filter_publishtype && $this->filter_publishtype !== 'All') {
           if ($this->filter_publishtype === 'Published') {
               $query->where('publish_to_portal', true);
           } elseif ($this->filter_publishtype === 'Unpublished') {
               $query->where(function($query) {
                   $query->where('publish_to_portal', false)
                         ->orWhereNull('publish_to_portal');
               });
           }
       }
       
       $this->documents = $query->get(); 
   }
   
   public function showModal($empId)
   {
       $this->empId = $empId; // Set empId when showing the modal
       $this->showDocDialog = true; // Show the modal
   }
   public $selectedEmployeeImage;
   public function selectEmployee($empId)
   {
       
       $this->selectedEmployeeId = $empId;
       $this->selectedEmployeeFirstName = EmployeeDetails::where('emp_id', $empId)->value('first_name');
       $this->selectedEmployeeLastName = EmployeeDetails::where('emp_id', $empId)->value('last_name');
       $this->selectedEmployeeImage = EmployeeDetails::where('emp_id', $empId)->value('image');
       $this->searchTerm='';
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
        $this->selectedEmployeeId = null;
        $this->selectedEmployeeFirstName = null;
        $this->selectedEmployeeLastName = null;
        $this->selectedEmployeeImage=null;
    }
    

    
    
    

    
    
    
    public function updateselectedEmployee($empId)
    {
        $this->selectedEmployeeId = $empId;
     
      
        $this->selectedEmployeeFirstName = EmployeeDetails::where('emp_id', $empId)->value('first_name');
        $this->selectedEmployeeLastName = EmployeeDetails::where('emp_id', $empId)->value('last_name');
        if (!empty($this->selectedEmployeeId)) {
            // Fetch all letter requests for the selected employee
            $this->requests = LetterRequest::whereIn('emp_id', (array)$this->selectedEmployeeId)->get();
            
            // Debugging output
            Log::info('Fetched Letter Requests: ' . $this->requests->toJson());
           
        } else {
            $this->requests = collect(); // No selected employee, empty collection
            Log::info('No Employee Selected, Returning Empty Requests');
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
    public function submit()
    {
      
        $this->validate();
      

        $fileContent = null;
        $mime_type = null;
        $file_name = null;

        if ($this->file_path) {
            $fileContent = file_get_contents($this->file_path->getRealPath());

            if ($fileContent === false) {
                Log::error('Failed to read the uploaded file.', [
                    'file_path' => $this->file_path->getRealPath(),
                ]);
                session()->flashError('error', 'Failed to read the uploaded file.');
                return;
            }

            // Check if the file content is too large
            $maxFileSize = 16777215; // 16MB for MEDIUMBLOB
            if (strlen($fileContent) > $maxFileSize) {
                session()->flashError( 'File size exceeds the allowed limit.');
                return;
            }

            $mime_type = $this->file_path->getMimeType();
            $file_name = $this->file_path->getClientOriginalName();
        }
         // This should set the selected employee ID correctly
        foreach ((array)$this->selectedEmployeeId as $emp_id) {
        // Create document record in database
        EmployeeDocument::create([
            'employee_id' => $emp_id,
            'document_name' => $this->documentName,
            'category' => $this->category,
            'description' => $this->description,
            'file_path' => $fileContent, // Store the binary file data
            'file_name' => $file_name,
            'mime_type' => $mime_type,
            'publish_to_portal' => $this->publishToPortal,
        ]);
    }
        // Reset the form
        $this->reset(['file_path', 'documentName', 'description', 'category', 'publishToPortal']);
        $this->showDocDialog = false;
        // Redirect or return response
        session()->flash('success', 'Document uploaded successfully!');
       


    }


    public function removeSelectedEmployee()
    {
        $this->selectedEmployeeId = null;
        $this->selectedEmployeeFirstName = null;
        $this->selectedEmployeeLastName = null;
        $this->selectedEmployeeImage=null;
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
            $this->requests = collect();
          

        // Initialize the requests collection to prevent undefined errors
        $this->requests = LetterRequest::all(); 
      
       
        
        $query = EmployeeDocument::whereIn('employee_id', (array)$this->selectedEmployeeId)->orderBy('created_at', 'desc');
   
       if ($this->filter_option && $this->filter_option !== 'All') {
           $query->where('category', $this->filter_option);
       }
       
           // Debugging the filter option
            // This will show the selected value
   
       
           // Filter logic
           if ($this->filter_publishtype && $this->filter_publishtype !== 'All') {
               if ($this->filter_publishtype === 'Published') {
                   $query->where('publish_to_portal', true);
               } elseif ($this->filter_publishtype === 'Unpublished') {
                   $query->where(function($query) {
                       $query->where('publish_to_portal', false)
                             ->orWhereNull('publish_to_portal');
                   });
               }
           }
           
        
       
       
         
        $this->documents = $query->get();

    
        return view('livewire.emp-document', [
            'employees' => $this->employees,
            'selectedPeople' => $this->selectedPeople,
            'peopleFound' => $peopleFound,
            'records' => $this->records,
            'combinedRequests' => $this->combinedRequests,
            'requests' => $this->requests,
            'documents'=>$this->documents // Pass the requests collection to the view
        ]);
    }
    
}

<?php

namespace App\Livewire;

use App\Helpers\FlashMessageHelper;
use App\Models\EmployeeDetails;
use App\Models\OffboardingRequest;
use App\Models\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Illuminate\Support\Facades\Validator;
use Livewire\WithFileUploads;
use ZipArchive;

class Requests extends Component
{
    use WithFileUploads;
    public $isOpen = false;
    public $job_role;
    public $employeeId;
    public $file_paths = [];
    public $offboardingRequests;

    public $short_description;
    public $rejection_reason;
    public $recordId;

    public $full_name;
    public $selectedCategory = [];

    public $activeCategory = '';
    public $pendingCategory = '';
    public $closedCategory = '';
    public $last_working_day;
   
    public $showViewFileDialog = false;
    public $fileDataArray;
    public $LapRequestaceessDialog=false;
    public $IDRequestaceessDialog=false;
    public $showModal = false;
    public $search = '';
    public $isRotated = false;
    public $requestId;
    public $searchTerm = '';
    public $requestCategories = '';
    public $selectedPerson = null;
    public $peoples;
    public $filteredPeoples;
    public $showserviceViewFileDialog = false;
    public $peopleFound = true;
    public $warningShown = false; 
    public $category;
    public $images=[];
    public $files=[];
    public $ccToArray = [];
    public $request;
    public $subject;
    public $isNames = false;
    public $description;
    public $file_path;
    public $cc_to;
    public $priority;
    public $servicerecords;
    public $records;
    public $image;
    public $mobile;
    public $mail;
    public $selectedPeopleNames = [];
    public $showViewImageDialog=false;
    public $employeeDetails;
    public $showDialog = false;
    public $fileContent, $file_name, $mime_type;

    public $showDialogFinance = false;
    public $record;
    public $peopleData = '';
    public $filterData;
    public $requests;
    public $activeTab = 'active';
    public $selectedPeople = [];
    public $activeSearch = [];
    public $pendingSearch = '';
    public $addselectedPeople = [];
    public $closedSearch = '';
    public $showDetails = true;
    public function toggleDetails()
    {
        $this->showDetails = !$this->showDetails;
    }
    public function mount()
    {
        // Fetch unique requests with their categories
        $requestCategories = Request::select('Request', 'category')->get();

        $employeeId = auth()->user()->emp_id;
        $this->employeeDetails = EmployeeDetails::where('emp_id', $employeeId)->first();
       
        $companyId =   $this->employeeDetails->company_id;
     
        $this->peoples = EmployeeDetails::whereJsonContains('company_id', $companyId)->whereNotIn('employee_status', ['rejected', 'terminated'])->get();

        $this->peopleData = $this->filteredPeoples ? $this->filteredPeoples : $this->peoples;
      

    
        $this->selectedPeople = [];
        $this->selectedPeopleNames = [];
        $this->addselectedPeople = [];
        $employeeName = auth()->user()->first_name . ' #(' . $employeeId . ')';



        if ($this->employeeDetails) {
            // Combine first and last names
            $this->full_name = $this->employeeDetails->first_name . ' ' . $this->employeeDetails->last_name;
        }

        $this->filterData = [];
        $this->peoples = EmployeeDetails::whereJsonContains('company_id', $companyId)->whereNotIn('employee_status', ['rejected', 'terminated'])
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();
        
            $this->requests = OffboardingRequest::all();
      
    }
    public function LapRequest()
    {



        $this->LapRequestaceessDialog = true;
        $this->showModal = true;
        $this->category = 'Service Request';
    }

    public function IDRequest()
    {
       

        $this->IDRequestaceessDialog = true;
        $this->showModal = true;
        $this->category = 'Incident Request';
    }
    public function downloadFilesAsZip($id)
    {
        // Fetch the record
        $record = OffboardingRequest::find($id);
        if (!$record) {
            return response()->json(['error' => 'Record not found'], 404);
        }
    
        $files = $record->getImageUrlsAttribute(); // Assuming this retrieves an array of files
        if (empty($files)) {
            return response()->json(['error' => 'No files available for download'], 404);
        }
    
        // Create a unique name for the ZIP file
        $zipFileName = 'files_' . $id . '_' . time() . '.zip';
        $zipPath = storage_path('app/public/' . $zipFileName);
    
        // Create a new ZIP archive
        $zip = new ZipArchive;
        if ($zip->open($zipPath, ZipArchive::CREATE) === TRUE) {
            foreach ($files as $file) {
                $fileContent = base64_decode($file['data']);
                $originalName = $file['original_name'];
                $zip->addFromString($originalName, $fileContent);
            }
            $zip->close();
        } else {
            return response()->json(['error' => 'Unable to create ZIP file'], 500);
        }
    
        // Return the ZIP file for download
        return response()->download($zipPath)->deleteFileAfterSend(true);
    }
        public $showImageDialog = false;
        public $imageUrl;  
    
        public $currentImageIndex = 0;  // To track which image is being shown
        
        // Show the image modal
        public function getImageUrlsAttribute()
    {
        $fileDataArray = is_string($this->file_paths)
            ? json_decode($this->file_paths, true)
            : $this->file_paths;
    
        return array_filter($fileDataArray, function ($fileData) {
            return isset($fileData['mime_type']) && strpos($fileData['mime_type'], 'image') !== false;
        });
    }
    public function getFileUrlsAttribute()
    {
        $fileDataArray = is_string($this->file_paths)
            ? json_decode($this->file_paths, true)
            : $this->file_paths;
    
        return array_filter($fileDataArray, function ($fileData) {
            // Check if MIME type is not an image
            return isset($fileData['mime_type']) && strpos($fileData['mime_type'], 'image') === false;
        });
    }
    
    
    public function showViewImage($id) 
    {
        $this->recordId = $id;
    
        // Fetch the record
        $record = OffboardingRequest::find($id);
        
        // Get the images (assuming a JSON structure for images)
        $this->images = $record->getImageUrlsAttribute(); 
    
        // Set the current image index
      
    
        // Show the dialog
        $this->showViewImageDialog = true;
    }
    
    
    
    public function loadHelpDeskData()
    {
        if ($this->activeTab === 'active') {
            $this->searchActiveHelpDesk();
        } elseif ($this->activeTab === 'pending') {
            $this->searchPendingHelpDesk();
        } elseif ($this->activeTab === 'closed') {
            $this->searchClosedHelpDesk();
        }
    }
    
    public function updatedActiveTab()
    {
        $this->loadHelpDeskData(); // Reload data when the tab is updated
    }
    // To track the current image index
    public function setActiveImageIndex($index)
    {
        $this->currentImageIndex = $index; // Update current index dynamically
    }     
        
    public function downloadActiveImage()
    {
        if (!isset($this->images[$this->currentImageIndex])) {
            session()->flash('error', 'No active image to download.');
            return;
        }
    
        $activeImage = $this->images[$this->currentImageIndex];
        $imageData = base64_decode($activeImage['data']);
        $mimeType = $activeImage['mime_type'];
        $originalName = $activeImage['original_name'];
    
        return response()->stream(
            function () use ($imageData) {
                echo $imageData;
            },
            200,
            [
                'Content-Type' => $mimeType,
                'Content-Disposition' => 'attachment; filename="' . $originalName . '"',
            ]
        );
    }
    public function downloadAllImages()
    {
        if (empty($this->images)) {
            session()->flash('error', 'No images available to download.');
            return;
        }
    
        // Create a temporary file for the zip archive
        $zipFilePath = storage_path('app/public/carousel_images.zip');
        $zip = new \ZipArchive();
    
        if ($zip->open($zipFilePath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === true) {
            foreach ($this->images as $index => $image) {
                $imageData = base64_decode($image['data']);
                $originalName = $image['original_name'];
    
                // Add each image to the zip file
                $zip->addFromString($originalName, $imageData);
            }
    
            // Close the zip archive
            $zip->close();
    
            // Return the zip file as a download response
            return response()->download($zipFilePath)->deleteFileAfterSend(true);
        } else {
            session()->flash('error', 'Failed to create ZIP file.');
            return;
        }
    }

    public function toggleRotation()
    {

        $this->isRotated = true;


        $this->selectedPeopleNames = [];

        $this->cc_to = '';
    }
    public function toggle()
    {

        $this->isRotated = true;


        $this->selectedPeopleNames = [];

        $this->cc_to = '';
    }

        // Close the image modal
        public function closeViewImage()
        {
            $this->showViewImageDialog = false;
        }
        public function searchHelpDesk($searchTerm)
        {
            $employeeId = auth()->user()->emp_id;
            
            // Start the base query based on status and employee ID or cc_to
            $query = OffboardingRequest::where(function ($query) use ($employeeId) {
                $query->where('emp_id', $employeeId)->orWhere('cc_to', 'like', "%$employeeId%");
            });

            // If there's a search term, apply search filtering
            if ($searchTerm) {
                $query->where(function ($query) use ($searchTerm) {
                    $query->where('emp_id', 'like', '%' . $searchTerm . '%')
                        
                        ->orWhereHas('emp', function ($query) use ($searchTerm) {
                            $query->where('first_name', 'like', '%' . $searchTerm . '%')
                                ->orWhere('last_name', 'like', '%' . $searchTerm . '%');
                        });
                });
            }
        
            // Log the query to check if it's correct
            Log::info("HelpDesk Query: " . $query->toSql());
            Log::info("Bindings: " . implode(', ', $query->getBindings()));
    
            // Get results and update filterData
            $this->filterData = $query->orderBy('created_at', 'desc')->get();
        
            $this->peopleFound = count($this->filterData) > 0;
        }
        
    
    public function closePeoples()
    {
        $this->isNames = false;
    }
    public function NamesSearch()
    {
        $this->isNames = !$this->isNames;
    }
  
    
    public function searchActiveHelpDesk()
    {
        // Pass the activeSearch property (which holds the search term)
        $this->searchHelpDesk($this->activeSearch);
    }
    

    public function closecatalog()
    {
        $this->showModal = false;
        $this->resetErrorBag(); // Reset validation errors if any
        $this->resetValidation(); // Reset validation state
        $this->reset([
            'subject',
            'mail',
            'mobile',
            'description',
      
            'cc_to',
            'category',
            'file_path',
        
            'selectedPeopleNames',
            'image',
            'selectedPeople',
            'selectedPeople',
        ]);
    }
    public function resetDialogs()
    {
        $this->LapRequestaceessDialog = false; 
        $this->IDRequestaceessDialog = false;
    }
    public function filter()
    {
      
        $employeeId = auth()->user()->emp_id;
        $this->employeeDetails = EmployeeDetails::where('emp_id', $employeeId)->first();
       
        $companyId =   $this->employeeDetails->company_id;
     

        // Fetch people data based on company ID and search term
        $this->peopleData =  EmployeeDetails::whereJsonContains('company_id', $companyId)
            ->whereNotIn('employee_status', ['rejected', 'terminated'])
            ->where(function ($query) {
                $query->where('first_name', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('last_name', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('emp_id', 'like', '%' . $this->searchTerm . '%');
            })
            ->get();



        // Apply isChecked only for selected people, uncheck the rest
        $this->peoples->transform(function ($person) {
            // Ensure the comparison is between the same types (convert emp_id to string)
            $person->isChecked = in_array((string)$person->emp_id, $this->selectedPeople);
            return $person;
        });

        // Reset filteredPeoples if search term is present
        $this->filteredPeoples = $this->searchTerm ? $this->peopleData : null;

  
    }
    public function selectPerson($personId)
    {
        try {
            if (count($this->selectedPeopleNames) >= 5 && !in_array($personId, $this->selectedPeople)) {
 
                return;
            }
    
          
            $selectedPerson = $this->peoples->where('emp_id', $personId)->first();

            if ($selectedPerson) {
                if (in_array($personId, $this->selectedPeople)) {
                    $this->selectedPeopleNames[] =  ucwords(strtolower($selectedPerson->first_name)) . ' ' . ucwords(strtolower($selectedPerson->last_name)) . ' #(' . $selectedPerson->emp_id . ')';
                } else {
                    $this->selectedPeopleNames = array_diff($this->selectedPeopleNames, [ucwords(strtolower($selectedPerson->first_name)) . ' ' . ucwords(strtolower($selectedPerson->last_name)) . ' #(' . $selectedPerson->emp_id . ')']);
                }
                $this->cc_to = implode(', ', array_unique($this->selectedPeopleNames));
            }
        } catch (\Exception $e) {
            // Log the exception message or handle it as needed
            Log::error('Error selecting person: ' . $e->getMessage());
            // Optionally, you can set an error message to display to the user
            $this->dispatchBrowserEvent('error', ['message' => 'An error occurred while selecting the person. Please try again.']);
        }
   
    }
    public function updatedSelectedPeople()
    {
        // Check if the number of selected people exceeds 5
        if (count($this->selectedPeople) > 5) {
            if (!$this->warningShown) {
                // Flash a warning message only once
                FlashMessageHelper::flashWarning('You can only select up to 5 people.');
                
                // Set the flag to true, so the warning won't be shown again in this iteration
                $this->warningShown = true;
            }
            
            // Optionally, reset the selected people array or remove the last selection
            $this->selectedPeople = array_slice($this->selectedPeople, 0, 5);
        } else {
            // If the number of selected people is valid, update the cc_to field
            $this->cc_to = implode(', ', array_unique($this->selectedPeopleNames));
            
            // Reset the warning flag when the count is valid
            $this->warningShown = false;
        }
    }
public function addselectPerson($personId)
{
    try {
        $addselectedPerson = $this->peoples->where('emp_id', $personId)->first();

        if ($addselectedPerson) {
            // Toggle the person's selection
            if (in_array($personId, $this->addselectedPeople)) {
                // Remove from selection
                $this->addselectedPeople = array_diff($this->addselectedPeople, [$personId]);
                $this->selectedPeopleNames = array_diff($this->selectedPeopleNames, [
                    ucwords(strtolower($addselectedPerson->first_name)) . ' ' . ucwords(strtolower($addselectedPerson->last_name)) . ' #(' . $addselectedPerson->emp_id . ')',
                ]);
            } else {
                // Add to selection (ensure limit of 1 is respected)
                if (count($this->addselectedPeople) < 1) {
                    $this->addselectedPeople[] = $personId;
                    $this->selectedPeopleNames[] = ucwords(strtolower($addselectedPerson->first_name)) . ' ' . ucwords(strtolower($addselectedPerson->last_name)) . ' #(' . $addselectedPerson->emp_id . ')';
                
                }
            }

            // Update cc_to field
            $this->cc_to = implode(', ', array_unique($this->selectedPeopleNames));
        }
    } catch (\Exception $e) {
        Log::error('Error selecting person: ' . $e->getMessage());
        $this->dispatch('error', ['message' => 'An error occurred while selecting the person. Please try again.']);
    }
}

    public function updatedAddselectedPeople()
    {
        // Ensure $this->addselectedPeople is always an array
        if (!is_array($this->addselectedPeople)) {
            $this->addselectedPeople = [];
        }
    
        // Limit the selection in addselectedPeople to a maximum of 1
        if (count($this->addselectedPeople) > 1) {
            FlashMessageHelper::flashWarning('You can only select up to 1 person.');
            $this->addselectedPeople = array_slice($this->addselectedPeople, 0, 1); // Trim the array
        }
    
        // Clear $selectedPeopleNames and rebuild it based on $addselectedPeople
        $this->selectedPeopleNames = [];
    
        foreach ($this->addselectedPeople as $personId) {
            $person = $this->peoples->where('emp_id', $personId)->first();
            if ($person) {
                $name = ucwords(strtolower($person->first_name)) . ' ' . ucwords(strtolower($person->last_name)) . ' #(' . $person->emp_id . ')';
                if (!in_array($name, $this->selectedPeopleNames)) {
                    $this->selectedPeopleNames[] = $name;
                }
            }
        }
    
        // Update cc_to field
        $this->cc_to = implode(', ', array_unique($this->selectedPeopleNames));
        if (!empty($this->addselectedPeople)) {
            $selectedPersonId = $this->addselectedPeople[0];
            $selectedPerson = EmployeeDetails::where('emp_id', $selectedPersonId)->first();

            if ($selectedPerson) {
                $this->mail = $selectedPerson->email ?? '-';
                $this->mobile = $selectedPerson->emergency_contact ?? '-';
                $this->job_role = $selectedPerson->job_role ?? '-';
            } else {
                $this->mail = null;
                $this->mobile = null;
                $this->job_role=null;
            }
        } else {
            $this->mail = null;
            $this->mobile = null;
            $this->job_role=null;
        }
     
    }
 
 public function Offboarding()
    {
        $messages = [
            'priority.required' => 'Priority is required.',
            'last_working_day.required' => 'Last working day date is required.',
            'last_working_day.after_or_equal' => 'Last working day must be today or a future date.',
        ];
        
        // Validate input fields
        $this->validate([
            'last_working_day' => 'required|date|after_or_equal:' . now()->toDateString(),
            'priority' => 'required|in:High,Medium,Low',
        ], $messages);
        

        // Validate file uploads
        $filePaths = $this->file_paths ?? [];
        $validator = Validator::make(
            ['file_paths' => $filePaths],
            [
                'file_paths' => 'array', 
                'file_paths.*' => 'file|mimes:xls,csv,xlsx,pdf,jpeg,png,jpg,gif', // 1MB max
            ],
            [
                'file_paths.*.mimes' => 'Invalid file type. Only xls, csv, xlsx, pdf, jpeg, png, jpg, and gif are allowed.',
                'file_paths.*.max' => 'Each file must not exceed 1MB in size.',
            ]
        );

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Process files
        $fileDataArray = [];
        foreach ($filePaths as $file) {
            if ($file->isValid()) {
                $fileDataArray[] = [
                    'data' => base64_encode(file_get_contents($file->getRealPath())),
                    'mime_type' => $file->getMimeType(),
                    'original_name' => $file->getClientOriginalName(),
                ];
            }
        }

        try {
            // Fetch logged-in employee details
            $employeeId = auth()->user()->emp_id;
            
            if (!$employeeId) {
                FlashMessageHelper::flashError('Employee ID is missing.');
                return;
            }

            // Create the offboarding request
            OffboardingRequest::create([
                'emp_id' => $employeeId,
                'priority' => $this->priority,
               
                'mobile' => $this->mobile,
                'mail' => $this->mail,
        'file_paths' => !empty($fileDataArray) ? json_encode($fileDataArray) : null, 
                'cc_to' => $this->cc_to ,
                'status_code' => 8, // Pending or any default status code
                'last_working_day' => $this->last_working_day, // Make sure this is added
            ]);
         

            FlashMessageHelper::flashSuccess('Request created successfully.');
            $this->reset();
            return redirect()->to('/request');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->setErrorBag($e->validator->getMessageBag());
        } catch (\Exception $e) {
            Log::error('Error creating request: ' . $e->getMessage(), [
              
                'category' => $this->category ?? null,
                'subject' => $this->subject ?? null,
                'description' => $this->description ?? null,
                'file_paths' => $fileDataArray,
            ]);
            FlashMessageHelper::flashError('An error occurred while creating the request. Please try again.');
        }
    }


    
public function setActiveTab($tab) {
    $this->activeTab = $tab;
}

    public function render()
    {
        $requestCategories = Request::select('Request', 'category')->get();

        $employeeId = auth()->user()->emp_id;
        $this->employeeDetails = EmployeeDetails::where('emp_id', $employeeId)->first();
       
        $companyId =   $this->employeeDetails->company_id;
     
        $this->peoples = EmployeeDetails::whereJsonContains('company_id', $companyId)
        ->whereNotIn('employee_status', ['rejected', 'terminated'])
        ->get();
    // Filter people data if necessary
    $query = OffboardingRequest::with('emp')
    ->where('emp_id', $employeeId);
        $this->requests = OffboardingRequest::all();
        $peopleData = $this->filteredPeoples ? $this->filteredPeoples : $this->peoples;
     
        $employeeName = auth()->user()->first_name . ' #(' . $employeeId . ')';
        $searchData = $this->filterData ?: $this->requests;

        if ($this->employeeDetails) {
            // Combine first and last names
            $this->full_name = $this->employeeDetails->first_name . ' ' . $this->employeeDetails->last_name;
        }
        return view('livewire.requests',[   'searchData' => $searchData?: $this->requests,
          'peopleData' => $peopleData,'requests'=>$this->requests]);
    }
}

<?php
// File Name                       : HelpDesk.php
// Description                     : This file contains the information about various IT requests related to the catalog.
//                                   It includes functionality for adding members to distribution lists and mailboxes, requesting IT accessories,
//                                   new ID cards, MMS accounts, new distribution lists, laptops, new mailboxes, and DevOps access.
// Creator                         : Asapu Sri Kumar Mmanikanta,Ashannagari Archana
// Email                           : archanaashannagari@gmail.com
// Organization                    : PayG.
// Date                            : 2023-09-07
// Framework                       : Laravel (10.10 Version)
// Programming Language            : PHP (8.1 Version)
// Database                        : MySQL
// Models                          : HelpDesk,EmployeeDetails
namespace App\Livewire;

use App\Exports\HelpDeskExport;
use App\Models\EmployeeDetails;
use App\Models\PeopleList;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

use App\Models\HelpDesks;
use App\Mail\HelpDeskotification;
use App\Models\Request;
use Illuminate\Support\Facades\Log;
use Livewire\WithFileUploads;
use App\Helpers\FlashMessageHelper; 
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use ZipArchive;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class HelpDesk extends Component
{
    use WithFileUploads;
    public $isOpen = false;
    public $images = [];
    public $file_paths = [];
 
    public $showDetails = true;

    public $rejection_reason;
    public $selectedCategory = [];
    public $activeCategory = null; // Category for Active tab
public $pendingCategory = null; // Category for Pending tab
public $closedCategory = null; // Category for Closed tab

  
    public $searchTerm = '';
    public $showViewFileDialog = false;
    public $showModal = false;
    public $search = '';
    public $isRotated = false;
    public $requestId;

    public $requestCategories = '';
    public $selectedPerson = null;
    public $peoples;
    public $filteredPeoples;
    public $peopleFound = true;
    public $category;
    public $ccToArray = [];
    public $request;
    public $subject;
    public $showViewImageDialog=false;
    public $description;
    public $file_path;
    public $cc_to;
    public $priority;
    public $records;
    public $image;
    public $mobile;
    public $selectedPeopleNames = [];
    public $employeeDetails;
    public $showDialog = false;
    public $fileContent,$file_name,$mime_type;

    public $showDialogFinance = false;
    public $record;
    public $files = [];
    public $peopleData = '';
    public $filterData;
    public $activeTab = 'active';
    public $selectedPeople = [];
    public $activeSearch = [];
public $pendingSearch = '';
public $closedSearch = '';
    protected $rules = [
        'category' => 'required|string',
        'subject' => 'required|string',
        'description' => 'required|string',
        'file_path' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx,csv,xls,xlsx', // Adjust max size as needed
        'priority' => 'required|in:High,Medium,Low',

    ];

    protected $messages = [
        'category.required' => 'Category is required.',
        'subject.required' => 'Subject is required.',
        'description.required' => 'Description is required.',
        'priority.required' => 'Priority is required.',
        'priority.in' => 'Priority must be one of: High, Medium, Low.',
        'image.image' => 'File must be an image.',
        'image.max' => 'Image size must not exceed 2MB.',
        'file_path.mimes' => 'File must be a document of type: pdf, xls, xlsx, doc, docx, jpg, jpeg, png.',
        'file_path.max' => 'Document size must not exceed 40 MB.',
    ];

    public function validateField($field)
    {
        if (in_array($field, ['description', 'subject','category','priority',])) {
            $this->validateOnly($field, $this->rules);
        } 
    }
    public function toggleDetails()
    {
        $this->showDetails = !$this->showDetails;
    }
    public function open()
    {
        $this->showDialog = true;
    }


    public $selectedCatalog = 'active';
 
    public $selectedNew = 'active';
    public function confirmByAdmin($taskId)
    {
        $task = HelpDesks::find($taskId);
        if ($task) {
            $task->update([
                'status' => 'Open',
                'category' => $task->category ?? 'N/A',
                'mail'   => $task->mail ?? 'N/A',
            ]);
        }
        return redirect()->to('/HelpDesk');
    }
    protected $listeners = ['refreshComponent' => '$refresh'];

    public function pendingForDesks($taskId)
    {
// Fetch the HelpDesk record based on the given task ID
        $task = HelpDesks::find($taskId);
    
        // Check if the task exists
        if ($task) {
            // Log the current status_code for debugging
          
            // Check if the current status_code is already 8
            if ($task->status_code == 8 || $task->status_code == 10) {
                // You can choose whether to allow the status change or not
              
                        // Update the status_code to 5
                $task->status_code = 5;
               // Update other necessary fields if needed
                
                // Save the task
                if ($task->save()) {
                  
                }
            }

       // Redirect back to the helpdesk page
        } else {
           
            session()->flash('error', 'Task not found.');
          
        }
    }

    
    public function mount()
    {
        // Fetch unique requests with their categories
        $requestCategories = Request::select('Request', 'category')->get();
        $employeeId = auth()->user()->emp_id;
        $this->employeeDetails = EmployeeDetails::where('emp_id', $employeeId)->first();
       
        $employeeId = Auth::guard('hr')->user()->emp_id;

        // Fetch emp_id and company_id for the logged-in employee
        $employeeDetails = DB::table('employee_details')
            ->where('emp_id', $employeeId)
            ->select('emp_id', 'company_id') // Select specific columns
            ->first(); // Fetch the result as an object
        
        
        
        // Fetch the company_id for the logged-in employee's parent company
        $companyId = DB::table('employee_details')
        
        ->select('company_id') // Select specific columns
        ->first(); 
       
       
        $this->peoples = EmployeeDetails::whereJsonContains('company_id', $companyId)->whereNotIn('employee_status', ['rejected', 'terminated'])->get();

        $this->peopleData = $this->filteredPeoples ? $this->filteredPeoples : $this->peoples;
        $this->selectedPeople = [];
        $this->selectedPeopleNames = [];
        $employeeName = auth()->user()->first_name . ' #(' . $employeeId . ')';
        $this->records = HelpDesks::with('emp')
            ->where(function ($query) use ($employeeId, $employeeName) {
                $query->where('emp_id', $employeeId)
                    ->orWhere('cc_to', 'LIKE', "%$employeeName%");
            })
            ->orderBy('created_at', 'desc')
            ->get();
         
         
         
     
        $this->peoples = EmployeeDetails::whereJsonContains('company_id', $companyId)->whereNotIn('employee_status', ['rejected', 'terminated'])
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();
   $this->loadHelpDeskData();
        // Group categories by their request
        if ($requestCategories->isNotEmpty()) {
            // Group categories by their request
            $this->requestCategories = $requestCategories->groupBy('Request')->map(function ($group) {
                return $group->unique('category'); // Ensure categories are unique
            });
        } else {
            // Handle the case where there are no requests
            $this->requestCategories = collect(); // Initialize as an empty collection
        }
    }





    public function openFinance()
    {
        $this->showDialogFinance = true;
    }
    public function updatedCategory()
    {
        $this->filter();
        logger($this->category); // Log selected category
        logger($this->records); // Log filtered records
    }
    public $activeDataLoaded=false;
    public $pendingDataLoaded=false;
    public $closedDataLoaded=false;
    public function loadHelpDeskData()
    {
        if ($this->activeTab === 'active' && !$this->activeDataLoaded) {
            $this->searchActiveHelpDesk();
            $this->activeDataLoaded = true; // Track that data has been loaded
        } elseif ($this->activeTab === 'pending' && !$this->pendingDataLoaded) {
            $this->searchPendingHelpDesk();
            $this->pendingDataLoaded = true;
        } elseif ($this->activeTab === 'closed' && !$this->closedDataLoaded) {
            $this->searchClosedHelpDesk();
            $this->closedDataLoaded = true;
        }
    }

    public function loadActiveTabData()
{
    // Fetch data for the 'active' tab only when it's active
    if ($this->activeTab === 'active' && !$this->activeDataLoaded) {
        $this->searchActiveHelpDesk();
        $this->activeDataLoaded = true;
    }
}

    
    public function updatedActiveTab()
    {
        $this->loadHelpDeskData(); // Reload data only when the tab actually changes
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

    public function nextImage()
    {
        $this->currentImageIndex = ($this->currentImageIndex + 1) % count($this->images);
    }

    public function previousImage()
    {
        $this->currentImageIndex = ($this->currentImageIndex - 1 + count($this->images)) % count($this->images);
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
    
 
    public  $forHR; 
    public function searchHelpDesk($status_code, $searchTerm,$selectedCategory)
    {
        $employeeId = auth()->user()->emp_id;
        $requestCategories = Request::select('Request', 'category')->get();
        // Start the base query based on status and employee ID or cc_to
        $query = HelpDesks::where(function ($query) use ($employeeId) {
            $query->where('emp_id', $employeeId)->orWhere('cc_to', 'like', "%$employeeId%");
        });
        if (is_array($status_code)) {
            $query->whereIn('status_code', $status_code);  // Multiple statuses (array)
        } else {
            $query->where('status_code', $status_code);    // Single status (string)
        }// Apply status filter dynamically
    
        // If a category is selected, apply category filtering
        if ($selectedCategory) {
            logger('Selected Category: ' . $selectedCategory);
            $query->whereIn('category', Request::where('Request', $selectedCategory)->pluck('category'));
        }

    
        // If there's a search term, apply search filtering
        if ($searchTerm) {
            $query->where(function ($query) use ($searchTerm) {
                $query->where('emp_id', 'like', '%' . $searchTerm . '%')
                    ->orWhere('category', 'like', '%' . $searchTerm . '%')
                    ->orWhere('subject', 'like', '%' . $searchTerm . '%')
                    ->orWhereHas('emp', function ($query) use ($searchTerm) {
                        $query->where('first_name', 'like', '%' . $searchTerm . '%')
                            ->orWhere('last_name', 'like', '%' . $searchTerm . '%');
                    });
            });
        }
    
        // Get results
        $results = $query->orderBy('created_at', 'desc')->get();
     
        $this->filterData = $results;
        $this->peopleFound = count($this->filterData) > 0;
    }
    
    
    public function searchActiveHelpDesk()
    {
        $this->searchHelpDesk([8,10], $this->activeSearch,$this->activeCategory);
    }
    
    public function searchPendingHelpDesk()
    {
        $this->searchHelpDesk(6, $this->pendingSearch,$this->pendingCategory);
    }
    
    public function searchClosedHelpDesk()
    {
        $this->searchHelpDesk([11,3], $this->closedSearch,$this->closedCategory);
    }
    
    public function showRejectionReason($id)
    {
  
        $record = HelpDesks::findOrFail($id);
    
        if ($record && $record->status_code === 3) {
            $this->rejection_reason = $record->rejection_reason;
       
            $this->isOpen = true;
        } else {
            $this->dispatchBrowserEvent('notification', ['message' => 'Reason not available.']);
        }
    }
    
    public function closeModal()
    {
        $this->isOpen = false;
        $this->rejection_reason = null;
    }
    public function close()
    {
        $this->showDialog = false;
        $this->isRotated = false;
        $this->resetErrorBag(); // Reset validation errors if any
        $this->resetValidation(); // Reset validation state
        $this->reset(['subject', 'description', 'cc_to', 'category', 'file_path', 'priority', 'image', 'selectedPeopleNames', 'selectedPeople']);
    }

    public function closeFinance()
    {
        $this->showDialogFinance = false;
    }



    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }


    protected function addErrorMessages($messages)
    {
        foreach ($messages as $field => $message) {
            $this->addError($field, $message[0]);
        }
    }

    public function openForDesks($taskId)
    {
        // Fetch the HelpDesk record based on the given task ID
        $task = HelpDesks::find($taskId);
    
        // Check if the task exists
        if ($task) {
           
            // Check if the current status_code is already 8
            if ($task->status_code == 8|| $task->status_code == 5) {
                
                // Update the status_code to 5
                $task->status_code = 9;
               // Update other necessary fields if needed
                
                // Save the task
                if ($task->save()) {
                   
                } 
                
            } 
    

       // Redirect back to the helpdesk page
        } else {
          
            session()->flash('error', 'Task not found.');
          
        }
    }
    

    public function closeForDesks($taskId)
    {
        // Fetch the HelpDesk record based on the given task ID
        $task = HelpDesks::find($taskId);
    
        // Check if the task exists
        if ($task) {
            // Log the current status_code for debugging
          
            // Check if the current status_code is already 8
            if ($task->status_code == 9) {
                // You can choose whether to allow the status change or not
              
                        // Update the status_code to 5
                $task->status_code = 8;
               // Update other necessary fields if needed
                
                // Save the task
                if ($task->save()) {
                  
                }
            }
       // Redirect back to the helpdesk page
        }
    }
    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }
    public function Catalog()
    {
        return redirect()->to('/catalog');
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

    public function downloadAllFiles()
    {
        if (empty($this->files)) {
            session()->flash('error', 'No files available to download.');
            return;
        }
    
        // Create a temporary file for the zip archive
        $zipFilePath = storage_path('app/public/files_archive.zip');
        $zip = new ZipArchive();
    
        if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            foreach ($this->files as $file) {
                $fileData = base64_decode($file['data']);
                $originalName = $file['original_name'];
    
                // Add each file to the zip archive
                $zip->addFromString($originalName, $fileData);
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
    


    
    public function showFile($id)
    {
        $record = HelpDesks::findOrFail($id);

        if ($record && $record->file_path !== 'N/A') {
            $mimeType = 'image/jpeg'; // Adjust as necessary

            return response($record->file_path, 200)
                ->header('Content-Type', $mimeType)
                ->header('Content-Disposition', 'inline; filename="image.jpg"'); // Adjust filename and extension as needed
        }

        return abort(404, 'File not found');
    }

    public $recordId;
    public $viewrecord;

    public function showViewFile($id)
    {
        $this->recordId = $id;

        // Fetch the record
        $record = HelpDesks::find($id);

        $this->files = $record->getImageUrlsAttribute(); 

        // Set the current image index
      
    
       
    }
    public function downloadFilesAsZip($id)
{
    // Fetch the record
    $record = HelpDesks::find($id);
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
    $record = HelpDesks::find($id);
    
    // Get the images (assuming a JSON structure for images)
    $this->images = $record->getImageUrlsAttribute(); 

    // Set the current image index
  

    // Show the dialog
    $this->showViewImageDialog = true;
}



  
    
    // Close the image modal
    public function closeViewImage()
    {
        $this->showViewImageDialog = false;
    }
    
    // Navigate to the previous image
 
    



public function closeImageDialog()
{
    // Set the flag to close the image view modal
    $this->showViewImageDialog = false;
}

    public function show()
    {
        $this->showDialog = true;
    }

    public function closeViewFile()
    {
        $this->showViewFileDialog = false;
    }



    public function submitHR()
    {
        try {
            $this->validate($this->rules);
    
            // Initialize file paths as an empty array if not provided
            $filePaths = $this->file_paths ?? [];
    
            // Validate file uploads if files are uploaded
            if (!empty($filePaths) && is_array($filePaths)) {
                $validator = Validator::make(
                    ['file_paths' => $filePaths],
                    [
                        'file_paths' => 'array', // Ensure file_paths is an array
                        'file_paths.*' => 'file|mimes:xls,csv,xlsx,pdf,jpeg,png,jpg,gif|max:1024', // 1MB max
                    ],
                    [
                        'file_paths.*.file' => 'Each file must be a valid file.',
                        'file_paths.*.mimes' => 'Invalid file type. Only xls, csv, xlsx, pdf, jpeg, png, jpg, and gif are allowed.',
                        'file_paths.*.max' => 'Each file must not exceed 1MB in size.',
                    ]
                );
    
                // If validation fails, return an error response
                if ($validator->fails()) {
                    return response()->json($validator->errors(), 422);
                }
            }
    
            // Array to hold processed file data
            $fileDataArray = [];
    
            // Process each file if uploaded
            if (!empty($filePaths) && is_array($filePaths)) {
                foreach ($filePaths as $file) {
                    // Check if the file is valid
                    if ($file->isValid()) {
                        try {
                            // Get file details
                            $mimeType = $file->getMimeType();
                            $originalName = $file->getClientOriginalName();
                            $fileContent = file_get_contents($file->getRealPath());
    
                            // Encode the file content to base64
                            $base64File = base64_encode($fileContent);
    
                            // Add file data to the array
                            $fileDataArray[] = [
                                'data' => $base64File,
                                'mime_type' => $mimeType,
                                'original_name' => $originalName,
                            ];
                        } catch (\Exception $e) {
                            Log::error('Error processing file', [
                                'file_name' => $file->getClientOriginalName(),
                                'error' => $e->getMessage(),
                            ]);
                            return response()->json(['error' => 'An error occurred while processing the file.'], 500);
                        }
                    } else {
                        Log::error('Invalid file uploaded', [
                            'file_name' => $file->getClientOriginalName(),
                        ]);
                        return response()->json(['error' => 'Invalid file uploaded'], 400);
                    }
                }
            }
    
            // If no files are uploaded, the array will be empty and file_paths will be null
            // Fetch employee details
            $employeeId = auth()->guard('emp')->user()->emp_id;
            $this->employeeDetails = EmployeeDetails::where('emp_id', $employeeId)->first();
    
            // Create HelpDesk entry
            HelpDesks::create([
                'emp_id' => $this->employeeDetails->emp_id,
                'category' => $this->category,
                'subject' => $this->subject,
                'description' => $this->description,
                'file_paths' => !empty($fileDataArray) ? json_encode($fileDataArray) : null, // Nullable file paths
                'priority' => $this->priority,
                'mail' => 'N/A',
                'mobile' => 'N/A',
                'distributor_name' => 'N/A',
            ]);
    
            FlashMessageHelper::flashSuccess('Request created successfully.');
            return redirect()->to('/HelpDesk');
    
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->setErrorBag($e->validator->getMessageBag());
        } catch (\Exception $e) {
            Log::error('Error creating request: ' . $e->getMessage(), [
                'category' => $this->category,
                'subject' => $this->subject,
                'description' => $this->description,
            ]);
            FlashMessageHelper::flashError('An error occurred while creating the request. Please try again.');
        }
    }
    
    

    public function downloadFile($id)
    {
        // Fetch the HelpDesk record using the provided ID
        $helpDeskRecord = HelpDesks::findOrFail($id);

        // Check if the file_path has content
        if (!$helpDeskRecord->file_path) {
            return redirect()->back()->with('error', 'File not found.');
        }

        // Prepare the response for the file download
        $fileContent = $helpDeskRecord->file_path; // Retrieve binary content from the database
        $filename = 'document_' . $id; // You may want to generate a more meaningful filename

        return response()->stream(function () use ($fileContent) {
            echo $fileContent; // Output the file content
        }, 200, [
            'Content-Type' => 'application/octet-stream', // Change based on the actual file type
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    protected function resetInputFields()
    {
        $this->category = '';
        $this->subject = '';
        $this->description = '';
        $this->file_path = '';
        $this->cc_to = '';
        $this->priority = '';
        $this->image = '';
    }

    public function closePeoples()
    {
        $this->isRotated = false;
    }

    public $warningShown = false;  // Flag to track if the warning has been shown

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

    public function filter()
    {
        $employeeId = auth()->guard('emp')->user()->emp_id;
        $companyId = Auth::user()->company_id;
    
        // Fetch people data based on company ID and search term
        $this->peopleData = EmployeeDetails::whereJsonContains('company_id', $companyId)->whereNotIn('employee_status', ['rejected', 'terminated'])
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
    
        // Filter records based on category and search term
        $this->records = HelpDesks::with('emp')
            ->whereHas('emp', function ($query) {
                $query->where('first_name', 'like', '%' . $this->searchTerm . '%')
                      ->orWhere('last_name', 'like', '%' . $this->searchTerm . '%');
            })
            ->orderBy('created_at', 'desc')
            ->get();
    }
    
    public $commentText = '';  // To hold the comment text

    public function postComment($recordId)
    {
        $record = HelpDesks::find($recordId);
        $employeeId = auth()->user()->emp_id;
        $this->employeeDetails = EmployeeDetails::where('emp_id', $employeeId)->first();
       
        $employeeId = Auth::guard('hr')->user()->emp_id;

        // Fetch emp_id and company_id for the logged-in employee
        $employeeDetails = DB::table('employee_details')
            ->where('emp_id', $employeeId)
            ->select('emp_id', 'company_id','first_name','last_name') // Select specific columns
            ->first(); // Fetch the result as an object
          // Validate the input to ensure comment is not empty
    $this->validate([
        'commentText' => 'required|string|min:1'
    ], [
        'commentText.required' => 'Comment cannot be empty.',
    ]);
        // Prepare the new comment text
        $newComment = $this->commentText;

        // Decode existing comments from JSON (if not empty)
        $existingComments = $record->active_comment ? json_decode($record->active_comment, true) : [];

        // Append only the comment text (not emp_id)
        $existingComments[] = $newComment;

        // Save back as JSON
        $record->active_comment = json_encode($existingComments);
        $record->save();
        FlashMessageHelper::flashSuccess('Comment Posted successfully.');
            // Clear the comment input field after posting
            $this->commentText = '';

        }
        public function exportToExcel()
        {
            // Fetch employee details using the logged-in employee ID
            $employeeId = auth()->user()->emp_id;
            $employeeId = Auth::guard('hr')->user()->emp_id;
             // Attempt to decode file_oaths
      // Attempt to decode file_oaths
      $this->records = HelpDesks::with('emp')
  
      ->orderBy('created_at', 'desc')
      ->get();

  // Apply filtering based on the selected category
  if ($this->selectedCategory) {
      $this->records->where('request', function ($q) {
          $q->where('category', $this->selectedCategory);
      });
  }
 $requestCategories = Request::select('Request', 'category')->get();
  $this->requestCategories = $requestCategories->groupBy('Request')->map(function ($group) {
      return $group->pluck('category')->toArray(); // Convert categories to array
  });
  
            // Fetch emp_id and company_id for the logged-in employee
            $employeeDetails = DB::table('employee_details')
                ->where('emp_id', $employeeId)
                ->select('emp_id', 'company_id') // Select specific columns
                ->first(); // Fetch the result as an object

            // Fetch HelpDesks records filtered by company_id and HR categories
$this->forHR = HelpDesks::with('emp')
->whereIn('category', $this->requestCategories['HR'])

->orderBy('created_at', 'desc')  // Order by creation date
->get();  // Convert the collection to an array

       
                // Check if data is not empty before exporting
             
                    return Excel::download(new HelpDeskExport($this->forHR), 'helpdesk.xlsx');
                } 
            
        
        
        
        


    public function render()
    {
        $employeeId = auth()->user()->emp_id;
     
        $employeeId = Auth::guard('hr')->user()->emp_id;

        // Fetch emp_id and company_id for the logged-in employee
        $employeeDetails = DB::table('employee_details')
            ->where('emp_id', $employeeId)
            ->select('emp_id', 'company_id') // Select specific columns
            ->first(); // Fetch the result as an object
        
        
        
        // Fetch the company_id for the logged-in employee's parent company
        $companyId = DB::table('employee_details')
        
        ->select('company_id') // Select specific columns
        ->first(); 
     
    
    
        $peopleData = $this->filteredPeoples ? $this->filteredPeoples : $this->peoples;
    
  
    
        $searchData = $this->filterData ?: $this->records;
        $employeeName = auth()->user()->first_name . ' #(' . $employeeId . ')';
    
        // Attempt to decode file_oaths
        $this->records = HelpDesks::with('emp')
            ->where(function ($query) use ($employeeId, $employeeName) {
                $query->where('emp_id', $employeeId)
                    ->orWhere('cc_to', 'LIKE', "%$employeeName%");
            })
            ->orderBy('created_at', 'desc')
            ->get();
    
        // Apply filtering based on the selected category
        if ($this->selectedCategory) {
            $this->records->where('request', function ($q) {
                $q->where('category', $this->selectedCategory);
            });
        }
        $requestCategories = Request::select('Request', 'category')->get();
        $this->requestCategories = $requestCategories->groupBy('Request')->map(function ($group) {
            return $group->pluck('category')->toArray(); // Convert categories to array
        });
        
     
        $this->selectedPeople = [];
        $this->selectedPeopleNames = [];
        $employeeName = auth()->user()->first_name . ' #(' . $employeeId . ')';
        $this->records = HelpDesks::with('emp')
            ->where(function ($query) use ($employeeId, $employeeName) {
                $query->where('emp_id', $employeeId)
                    ->orWhere('cc_to', 'LIKE', "%$employeeName%");
            })
            ->orderBy('created_at', 'desc')
            ->get();
         
         
            if ($employeeDetails) {
                $companyId = $employeeDetails->company_id; // Access company_id from the object
            
                // Now use $companyId in your HelpDesks query
                $this->forHR = HelpDesks::with('emp')
                    ->whereHas('emp', function ($query) use ($companyId) {
                        $query->where('company_id', $companyId);
                    })
                    ->orderBy('created_at', 'desc')
                    ->whereIn('category', $this->requestCategories['HR'])
                    ->get();
            
               
            } else {
                
            }
     
        $this->peoples = EmployeeDetails::whereJsonContains('company_id', $companyId)->whereNotIn('employee_status', ['rejected', 'terminated'])
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();
   $this->loadHelpDeskData();
        // Group categories by their request
        if ($requestCategories->isNotEmpty()) {
            // Group categories by their request
            $this->requestCategories = $requestCategories->groupBy('Request')->map(function ($group) {
                return $group->unique('category'); // Ensure categories are unique
            });
        } else {
            // Handle the case where there are no requests
            $this->requestCategories = collect(); // Initialize as an empty collection
        }
        // Loop through the records and check for file_paths
       
    
        // Filter people data if necessary
        $query = HelpDesks::with('emp')
            ->where('emp_id', $employeeId);
    
        // Apply filtering based on the selected category
        $this->peoples = EmployeeDetails::whereJsonContains('company_id', $companyId)->get();
    
        // Initialize peopleData properly
        $peopleData = $this->filteredPeoples ?: $this->peoples;
    
        // Ensure peopleData is a collection, not null
        $peopleData = $peopleData ?: collect();
    
        return view('livewire.help-desk', [
            'records' => $this->records,
            'searchData' => $this->filterData ?: $this->records,
            'requestCategories' => $this->requestCategories,
            'peopleData' => $peopleData,
            'showViewImageDialog' => $this->showViewImageDialog,
        ]);
    }
}    

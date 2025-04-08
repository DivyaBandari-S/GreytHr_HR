<?php

namespace App\Livewire;

use App\Models\Client;
use App\Mail\TaskAssignedNotification;
use App\Mail\TaskReopenedNotification;
use App\Mail\TaskClosedNotification;
use App\Models\ClientsEmployee;
use App\Models\ClientsWithProjects;
use App\Models\EmployeeDetails;
use App\Models\Task;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use App\Models\TaskComment;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Session;
use \Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Helpers\FlashMessageHelper;
use Illuminate\Support\Facades\Validator;


class Tasks extends Component
{
    use WithFileUploads;
    public $status = false;
    public $searchTerm = '';
    public $searchTermFollower = '';
    public $showDialog = false;
    public $showViewFileDialog = false;
    public $showModal = false;
    public $employeeDetails;
    public $emp_id;
    public $task_name;
    public $assignee;
    public $priority = "Low";
    public $due_date;
    public $tags;
    public $followers;
    public $subject;
    public $description;
    public $file_path;
    public $image;
    public $peoples;
    public $records;
    public $filteredPeoples;
    public $assigneeList;
    public $peopleFound = true;
    public $activeTab = 'open';
    public $employeeIdToComplete;
    public $record;
    public $isLoadingImage = false;
    public $followerPeoples;

    public $followersList = false;
    public $selectedPeopleNames = [];
    public $selectedPeopleNamesForFollowers = [];
    public $newComment = '';
    public $taskId;
    public $commentId;
    public $commentAdded = false;
    public $showAddCommentModal = false;
    public $editCommentId = null;
    public $search = '';
    public $closedSearch = '';
    public $filterData;
    public $showAlert = false;
    public $task;
    public $openAccordions = [];
    public $editingComment = '';

    public function toggleAccordion($recordId)
    {
        if (in_array($recordId, $this->openAccordions)) {
            $this->openAccordions = array_filter($this->openAccordions, function ($id) use ($recordId) {
                return $id !== $recordId;
            });
        } else {
            $this->openAccordions[] = $recordId;
        }
    }
    public function setActiveTab($tab)
    {
        if ($tab === 'open') {
            $this->activeTab = 'open';
            $this->search = '';
            $this->filterPeriod = 'all';
        } elseif ($tab === 'completed') {
            $this->activeTab = 'completed';
            $this->closedSearch = '';
            $this->filterPeriod = 'all';
        }
        $this->loadTasks();
    }
    public function hideAlert()
    {
        $this->showAlert = false;
        session()->forget('showAlert');
    }

    public function loadTasks()
    {
        if ($this->activeTab === 'open') {
            $this->searchActiveTasks();
        } elseif ($this->activeTab === 'completed') {
            $this->searchCompletedTasks();
        }
    }
    public $filterPeriod = 'all';


    public function searchActiveTasks()
    {
        // $employeeId = auth()->guard('hr')->user()->emp_id;
        // $query = Task::where(function ($query) use ($employeeId) {
        //     $query->where('emp_id', $employeeId)
        //         ->orWhereRaw("SUBSTRING_INDEX(SUBSTRING_INDEX(assignee, '(', -1), ')', 1) = ?", [$employeeId]);
        // })
        //     ->where('status', 10);
        $query = Task::where('status', 10);


        // Filter by period
        switch ($this->filterPeriod) {
            case 'this_week':
                $startOfWeek = now()->startOfWeek()->toDateString();
                $endOfWeek = now()->endOfWeek()->toDateString();
                $query->whereBetween('created_at', [$startOfWeek, $endOfWeek]);
                break;
            case 'this_month':
                $startOfMonth = now()->startOfMonth()->toDateString();
                $endOfMonth = now()->endOfMonth()->toDateString();
                $query->whereBetween('created_at', [$startOfMonth, $endOfMonth]);
                break;
            case 'last_month':
                $startOfLastMonth = now()->subMonth()->startOfMonth()->toDateString();
                $endOfLastMonth = now()->subMonth()->endOfMonth()->toDateString();
                $query->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth]);
                break;
            case 'this_year':
                $startOfYear = now()->startOfYear()->toDateString();
                $endOfYear = now()->endOfYear()->toDateString();
                $query->whereBetween('created_at', [$startOfYear, $endOfYear]);
                break;
            case 'all':
                break;
        }

        if ($this->search) {
            $searchTerm = trim($this->search); // Trim any extra whitespace
            $searchTerm = strtolower($searchTerm); // Convert to lowercase for case-insensitivity

            $query->where(function ($query) use ($searchTerm) {
                $query->whereRaw('LOWER(assignee) LIKE ?', ["%{$searchTerm}%"])
                    ->orWhereRaw('LOWER(followers) LIKE ?', ["%{$searchTerm}%"])
                    ->orWhereHas('emp', function ($query) use ($searchTerm) {
                        $query->whereRaw('LOWER(first_name) LIKE ?', ["%{$searchTerm}%"])
                            ->orWhereRaw('LOWER(last_name) LIKE ?', ["%{$searchTerm}%"]);
                    })
                    ->orWhereRaw('LOWER(task_name) LIKE ?', ["%{$searchTerm}%"])
                    ->orWhereRaw('LOWER(emp_id) LIKE ?', ["%{$searchTerm}%"])
                    ->orWhereRaw('LOWER(CONCAT("T-", id)) LIKE ?', ["%{$searchTerm}%"]);
            });
        }


        $this->filterData = $query->orderBy('created_at', 'desc')->get();
        $this->peopleFound = count($this->filterData) > 0;
    }

    public function searchCompletedTasks()
    {
        // $employeeId = auth()->guard('hr')->user()->emp_id;

        // $query = Task::where(function ($query) use ($employeeId) {
        //     $query->where('emp_id', $employeeId)
        //         ->orWhereRaw("SUBSTRING_INDEX(SUBSTRING_INDEX(assignee, '(', -1), ')', 1) = ?", [$employeeId]);
        // })
        //     ->where('status', 11);
        $query = Task::where('status', 11);


        switch ($this->filterPeriod) {
            case 'this_week':
                $startOfWeek = now()->startOfWeek()->toDateString();
                $endOfWeek = now()->endOfWeek()->toDateString();
                $query->whereBetween('created_at', [$startOfWeek, $endOfWeek]);
                break;
            case 'this_month':
                $startOfMonth = now()->startOfMonth()->toDateString();
                $endOfMonth = now()->endOfMonth()->toDateString();
                $query->whereBetween('created_at', [$startOfMonth, $endOfMonth]);
                break;
            case 'last_month':
                $startOfLastMonth = now()->subMonth()->startOfMonth()->toDateString();
                $endOfLastMonth = now()->subMonth()->endOfMonth()->toDateString();
                $query->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth]);
                break;
            case 'this_year':
                $startOfYear = now()->startOfYear()->toDateString();
                $endOfYear = now()->endOfYear()->toDateString();
                $query->whereBetween('created_at', [$startOfYear, $endOfYear]);
                break;
            case 'all':
                break;
        }


        if ($this->closedSearch) {
            $searchTerm = trim($this->closedSearch); // Trim any extra whitespace
            $searchTerm = strtolower($searchTerm); // Convert to lowercase for case-insensitivity

            $query->where(function ($query) use ($searchTerm) {
                $query->whereRaw('LOWER(assignee) LIKE ?', ["%{$searchTerm}%"])
                    ->orWhereRaw('LOWER(followers) LIKE ?', ["%{$searchTerm}%"])
                    ->orWhereHas('emp', function ($query) use ($searchTerm) {
                        $query->whereRaw('LOWER(first_name) LIKE ?', ["%{$searchTerm}%"])
                            ->orWhereRaw('LOWER(last_name) LIKE ?', ["%{$searchTerm}%"]);
                    })
                    ->orWhereRaw('LOWER(task_name) LIKE ?', ["%{$searchTerm}%"])
                    ->orWhereRaw('LOWER(emp_id) LIKE ?', ["%{$searchTerm}%"])
                    ->orWhereRaw('LOWER(CONCAT("T-", id)) LIKE ?', ["%{$searchTerm}%"]);
            });
        }

        $this->filterData = $query->orderBy('created_at', 'desc')->get();
        $this->peopleFound = count($this->filterData) > 0;
    }

    protected $rules = [
        'newComment' => 'required|string|word_count:500|max:3000',
        'description' => 'required|string|min:3',
        'newDueDate' => 'required|date|after_or_equal:today',
        'file_paths.*' => 'nullable|file|mimes:xls,csv,xlsx,pdf,jpeg,png,jpg,gif,zip|max:1024',
    ];
    protected $messages = [
        'newComment.required' => 'Comment is required.',
        'newComment.word_count' => 'Comment must not exceed 500 words.',
        'file_paths.*.max' => 'Your file is larger than 1 MB. Please select a file of up to 1 MB only.',
        'file_paths.*.mimes' => 'Please upload a file of type: xls, csv, xlsx, pdf, jpeg, png, jpg, gif.',
        'description.required' => 'Description is required.',
        'description.min' => 'Description must be at least 3 characters.',
        'newDueDate' => 'DueDate is Required',
        'newDueDate.date' => 'Please enter a valid date for the Due Date.',
        'newDueDate.after_or_equal' => 'The Due Date must be today or later.',

    ];
    public $errorMessageValidation;
    public $showViewImageDialog = false;
    public function closeViewFile()
    {
        $this->showViewFileDialog = false;
    }
    public function showViewFile()
    {
     
      
        $this->showViewFileDialog = true;
    }

    public function showViewImage()
    {
      
        $this->showViewImageDialog = true;
    }
    public function closeViewImage()
    {
        $this->showViewImageDialog = false;
    }
    public function downloadImage($taskId)
    {
       

        $task = $this->records->firstWhere('id', $taskId);

    if (!$task) {
        return response()->json(['message' => 'Task not found'], 404);
    }

    
        $fileDataArray = is_string($task->file_paths)
            ? json_decode($task->file_paths, true)
            : $task->file_paths;
          

        // Filter images
        $images = array_filter(
            $fileDataArray,
            fn($fileData) => strpos($fileData['mime_type'], 'image') !== false,
        );
      
            // If only one image, provide direct download
    if (count($images) === 1) {

        $image = reset($images); // Get the single image
        $base64File = $image['data'];
        $mimeType = $image['mime_type'];
        $originalName = $image['original_name'];
 
        // Decode base64 content
        $fileContent = base64_decode($base64File);
 
        // Return the image directly
        return response()->stream(
            function () use ($fileContent) {
                echo $fileContent;
            },
            200,
            [
                'Content-Type' => $mimeType,
                'Content-Disposition' => 'attachment; filename="' . $originalName . '"',
            ]
        );
    }

        // Create a zip file for the images
        if (count($images) > 1) {
        $zipFileName = 'images.zip';
        $zip = new \ZipArchive();
        $zip->open(storage_path($zipFileName), \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

        foreach ($images as $image) {
            $base64File = $image['data'];
            $mimeType = $image['mime_type'];
            $extension = explode('/', $mimeType)[1];
            $imageName = uniqid() . '.' . $extension;

            $zip->addFromString($imageName, base64_decode($base64File));
        }

        $zip->close();

        // Return the zip file as a download
        return response()->download(storage_path($zipFileName))->deleteFileAfterSend(true);
    }
      // If no images, return an appropriate response
      return response()->json(['message' => 'No images found'], 404);
}
    public function boot()
    {
        // Register custom validation rule for word count
        Validator::extend('word_count', function ($attribute, $value, $parameters, $validator) {
            // Get the maximum word count from parameters (default to 500)
            $maxWords = $parameters[0] ?? 500;
            // Count words in the comment
            $wordCount = str_word_count($value);
            return $wordCount <= $maxWords;
        });
    }

    public function validateField($field)
    {


        $this->validateOnly($field, $this->rules);
    }

    public function forAssignee()
    {
        $this->searchTerm = '';
        $this->assigneeList = true;
    }
    public function closeAssignee()
    {
        $this->assigneeList = false;
    }

    public function forFollowers()
    {
        $this->searchTermFollower = '';
        $this->followersList = true;
    }
    public function closeFollowers()
    {
        $this->followersList = false;
    }

    public $showRecipients = false;
    public $selectedPeople = [];
    public $selectedPeopleName, $selectedPerson, $selectedPersonClients, $selectedPersonClientsWithProjects;
    public function mount()
    {
        $employeeId = auth()->guard('hr')->user()->emp_id;
        $this->selectedPersonClients = collect();
        $this->selectedPersonClientsWithProjects = collect();
        $this->loadTasks();

        if (session()->has('showAlert')) {
            $this->showAlert = session('showAlert');
        }

        // TO reduce notification count by making as read related to  task

        DB::table('notifications')
            ->whereRaw("SUBSTRING_INDEX(SUBSTRING_INDEX(notifications.assignee, '(', -1), ')', 1) = ?", [$employeeId])
            ->whereIn('notification_type', ['task', 'task-Closed', 'task-Reopen'])
            ->delete();
    }
    public function updateFilterDropdown()
    {
        $this->loadTasks();
    }

    public function selectPerson($personId)
    {
        $this->showRecipients = true;
        $this->selectedPerson = $this->peoples->where('emp_id', $personId)->first();
        $this->selectedPersonClients = ClientsEmployee::whereNotNull('emp_id')->where('emp_id', $this->selectedPerson->emp_id)->get();
        $this->selectedPeopleName = ucwords(strtolower(($this->selectedPerson->first_name . ' ' . $this->selectedPerson->last_name))) . ' #(' . $this->selectedPerson->emp_id . ')';
        $this->assignee = $this->selectedPeopleName;
        $this->getFollowers();


        if ($this->selectedPersonClients->isEmpty()) {
            $this->selectedPersonClientsWithProjects = collect();
        }
        $this->assigneeList = false;
    }

    public function showProjects()
    {
        $this->selectedPersonClientsWithProjects = ClientsWithProjects::where('client_id', $this->client_id)->get();

        if ($this->validate_tasks == "true") {

            $this->autoValidate();
        }
    }
    public $selectedFollowers;

    public function updateCheckbox($personId)
    {

        if (in_array($personId, $this->selectedPeopleForFollowers)) {
            $this->selectedPeopleForFollowers = array_diff($this->selectedPeopleForFollowers, [$personId]);
        } else {

            $this->selectedPeopleForFollowers[] = $personId;
        }

        $this->updateFollowers();

        if (count($this->selectedPeopleForFollowers) > $this->maxFollowers) {
            $this->validationFollowerMessage = "You can only select up to 5 followers.";
        } else {
            $this->validationFollowerMessage = '';
        }
    }

    public $selectedPeopleForFollowers = [];

    public function togglePersonSelection($personId)
    {

        
        if (in_array($personId, $this->selectedPeopleForFollowers)) {

            // Deselect the person
            $this->selectedPeopleForFollowers = array_diff($this->selectedPeopleForFollowers, [$personId]);
        } else {
            // Select the person
            $this->selectedPeopleForFollowers[] = $personId;
        }


        // Ensure state is updated correctly

        $this->getFollowers();
        // dd($this->maxFollowers, count($this->selectedPeopleForFollowers));
        if (count($this->selectedPeopleForFollowers) > $this->maxFollowers) {
            $this->validationFollowerMessage = "You can only select up to 5 followers.";
        } else {
            $this->validationFollowerMessage = '';
        }
    }



    public function getFollowers()
    {


        // Extract the assigned employee ID from the selected assignee
        preg_match('/#\((.*?)\)/', $this->selectedPeopleName, $matches);
        $assignedEmployeeId = isset($matches[1]) ? $matches[1] : null;


        // Map through selected followers to get their formatted names
        $this->selectedPeopleNamesForFollowers = array_map(function ($id) use ($assignedEmployeeId) {
            $selectedPerson = $this->peoples->where('emp_id', $id)->first();

            // Only format and return the person if they are not the assigned employee
            if ($selectedPerson && $selectedPerson->emp_id !== $assignedEmployeeId) {
                return ucwords(strtolower($selectedPerson->first_name . ' ' . $selectedPerson->last_name)) . ' #(' . $selectedPerson->emp_id . ')';
            }
            return null; // Return null for assigned employee
        }, $this->selectedPeopleForFollowers);

        // Filter out any null or empty results
        $this->selectedPeopleNamesForFollowers = array_filter($this->selectedPeopleNamesForFollowers);
        // Convert to a string for display
        $this->followers = implode(', ', array_unique($this->selectedPeopleNamesForFollowers));

        // Determine if there are any followers to show
        $this->selectedFollowers = count($this->selectedPeopleNamesForFollowers);
        
       
    }



    public function openForTasks($taskId)
    {
        $task = Task::find($taskId);

        if ($task) {
            // Update task status to "closed"
            $task->update(['status' => 11]);


            // Retrieve the assignee from the task (assuming assignee is stored in the format 'Name #(EmpId)')
            preg_match('/#\((.*?)\)/', $task->assignee, $matches);
            $assigneeEmpId = isset($matches[1]) ? $matches[1] : null;

            if ($task && $assigneeEmpId) {
                Notification::create([
                    'emp_id' => $task->emp_id,
                    'notification_type' => 'task-Closed',
                    'task_name' => $this->task_name,
                    'assignee' => $task->assignee,
                ]);
            }

            if ($assigneeEmpId) {
                // Retrieve the assignee details using the emp_id
                $assigneeDetails = EmployeeDetails::where('emp_id', $assigneeEmpId)->first();

                if ($assigneeDetails) {
                    // Retrieve assignee's email
                    $assigneeEmail = $assigneeDetails->email;

                    if (!empty($assigneeEmail)) {
                        // Prepare task details for email
                        $taskName = $task->task_name;
                        $description = $task->description;
                        $priority = $task->priority;
                        $assignedByEmpId = $task->emp_id; // Assuming 'emp_id' field in task points to the assigned person
                        $assignedByDetails = EmployeeDetails::where('emp_id', $assignedByEmpId)->first();

                        if ($assignedByDetails) {
                            // Format the assignedBy name
                            $assignedBy = $assignedByDetails->first_name . ' ' . $assignedByDetails->last_name;
                        } else {
                            $assignedBy = 'Unknown'; // In case the assignedBy is not found
                        }
                        $dueDate = $task->due_date; // Using task's due date
                        $formattedAssignee = ucwords(strtolower(trim($task->assignee))); // Format assignee name

                        // Send notification email to assignee about task closure
                        Mail::to($assigneeEmail)->send(new TaskClosedNotification(
                            $taskName,
                            $description,
                            $dueDate,
                            $priority,
                            $assignedBy,
                            $formattedAssignee
                        ));

                        // Flash success message for email
                        FlashMessageHelper::flashSuccess('Task closed successfully and notification email sent!');
                    } else {
                        // Log error if assignee email is empty
                        Log::error('Assignee email is empty for emp_id: ' . $assigneeEmpId);
                    }
                } else {
                    // Log error if assignee not found
                    Log::error('Employee not found for emp_id: ' . $assigneeEmpId);
                }
            } else {
                // Log error if emp_id was not found in the assignee field
                Log::error('Employee ID not found in assignee field for task: ' . $task->task_name);
            }
        }


        session()->flash('showAlert', true);

        // Reload tasks after update
        $this->loadTasks();
    }

    public $showReopenDialog = false;
    public $newDueDate;
    public function closeForTasks($taskId)
    {
        $this->taskId = $taskId;
        $this->newDueDate = null; // Clear any existing due date

        // Show the modal
        $this->showReopenDialog = true;


        // $task = Task::find($taskId);

        // if ($task) {
        //     $task->update([
        //         'status' => 10,
        //         'reopened_date' => now()
        //     ]);
        // }
        // FlashMessageHelper::flashSuccess('Task has been Re-Opened.');
        // session()->flash('showAlert', true);
        // // $this->activeTab = 'open';
        // $this->loadTasks();
    }
    public function validateDueDate()
    {
        $this->validate([
            'newDueDate' => 'required|date|after_or_equal:today',


        ]);
    }
    public function submitReopen()
    {

        // Perform validation
        $this->validateDueDate();


        // Find the task by ID
        $task = Task::find($this->taskId);


        if ($task && $this->newDueDate) {
            // Update the task with the new due date
            $task->update([
                'due_date' => $this->newDueDate, // Update the due date
                'status' => 10, // Assuming 10 is the "reopened" status
                'reopened_date' => now() // Update the reopened date
            ]);

            // Flash success message
            FlashMessageHelper::flashSuccess('Task has been Re-Opened and due date updated.');
            preg_match('/#\((.*?)\)/', $task->assignee, $matches);
            $empId = isset($matches[1]) ? $matches[1] : null;
            if ($task && $empId) {
                Notification::create([
                    'emp_id' => $task->emp_id,
                    'notification_type' => 'task-Reopen',
                    'task_name' => $this->task_name,
                    'assignee' => $task->assignee,
                ]);
            }

            if ($empId) {
                // Retrieve assignee details using the extracted emp_id
                $assigneeDetails = EmployeeDetails::where('emp_id', $empId)->first();


                // Check if assignee details are found
                if ($assigneeDetails) {
                    // Extract assignee email
                    $assigneeEmail = $assigneeDetails->email;

                    // Ensure the assignee email is valid
                    if (!empty($assigneeEmail)) {
                        // Prepare task details to send in email
                        $taskName = $task->task_name;
                        $description = $task->description;
                        $priority = $task->priority; // Assuming priority is set
                        $assignedByEmpId = $task->emp_id; // Assuming 'emp_id' field in task points to the assigned person
                        $assignedByDetails = EmployeeDetails::where('emp_id', $assignedByEmpId)->first();

                        if ($assignedByDetails) {
                            // Format the assignedBy name
                            $assignedBy = $assignedByDetails->first_name . ' ' . $assignedByDetails->last_name;
                        } else {
                            $assignedBy = 'Unknown'; // In case the assignedBy is not found
                        }
                        $dueDate = $this->newDueDate; // Use the new due date

                        // Format assignee name and ID (if needed)
                        $formattedAssignee = ucwords(strtolower(trim($task->assignee)));

                        // Send notification email to assignee
                        Mail::to($assigneeEmail)->send(new TaskReopenedNotification(
                            $taskName,
                            $description,
                            $dueDate,
                            $priority,
                            $assignedBy,
                            $formattedAssignee
                        ));
                    }
                }

                // Close the modal
                $this->closeReopen();

                // Reload tasks if necessary
                $this->loadTasks();
            } else {
                // Show error if no due date is entered
                FlashMessageHelper::flashError('Please provide a valid due date.');
            }
        }
    }

    // This method is called to close the modal
    public function closeReopen()
    {
        $this->showReopenDialog = false;
    }

    public function autoValidate()
    {
        if ($this->validate_tasks) {


            if (is_null($this->selectedPersonClients) || $this->selectedPersonClients->isEmpty()) {

                $this->validate([
                    'due_date' => 'required',
                    'assignee' => 'required',
                    'task_name' => 'required',
                    'description' => 'required|min:3',


                ]);
            } else {

                $this->validate([
                    'due_date' => 'required',
                    'client_id' => 'required',
                    'project_name' => 'required',
                    'assignee' => 'required',
                    'task_name' => 'required',
                    'description' => 'required|min:3',

                ]);
            }
        }
    }

    public $maxFollowers = 5;
    public $validationFollowerMessage = '';
    public  $file_paths = [];


    public function submit()
    {



        try {

            $this->validate_tasks = true;
            $this->autoValidate();




            if (count($this->selectedPeopleForFollowers) > $this->maxFollowers) {
                session()->flash('error', 'You can only select up to 5 followers.');
                // FlashMessageHelper::flashError('You can only select up to 5 followers.');
                return;
            }
            $filePaths = $this->file_paths ?? [];
        

            // Validate file uploads
            $validator = Validator::make($filePaths, [
                'file_paths.*' => 'required|file|mimes:xls,csv,xlsx,pdf,jpeg,png,jpg,gif|max:1024',
            ], [
                'file_paths.*.required' => 'You must upload at least one file.',
                'file_paths.*.max' => 'Your file is larger than 1 MB. Please select a file of up to 1 MB only.',
                'file_paths.*.mimes' => 'Invalid file type. Only xls, csv, xlsx, pdf, jpeg, png, jpg, gif are allowed.',
            ]);
            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            // Store files
            $fileDataArray = [];
            if ($filePaths) {
               
              
                foreach ($filePaths as $file) {
                    $fileContent = file_get_contents($file->getRealPath());
                    $mimeType = $file->getMimeType();
                    $base64File = base64_encode($fileContent);
                    $fileDataArray[] = [
                        'data' => $base64File,
                        'mime_type' => $mimeType,
                        'original_name' => $file->getClientOriginalName(),
                    ];
                }
               
            }
            // Validate and store the uploaded file
           

            $employeeId = auth()->guard('hr')->user()->emp_id;


            $this->employeeDetails = EmployeeDetails::where('emp_id', $employeeId)->first();


            Task::create([
                'emp_id' => $this->employeeDetails->emp_id,
                'task_name' => $this->task_name,
                'assignee' => $this->assignee,
                'client_id' => $this->client_id ?? "",
                'project_name' => $this->project_name,
                'priority' => $this->priority,
                'due_date' => $this->due_date,
                'tags' => $this->tags,
                'followers' => $this->followers,
                'subject' => $this->subject,
                'description' => $this->description,
                'file_paths' => json_encode($fileDataArray),
                'status' => 10,
            ]);

            // $this->showRecipients = false;

            // $this->selectedPeopleName=null;



            preg_match('/\((.*?)\)/', $this->assignee, $matches);
            $extracted = isset($matches[1]) ? $matches[1] : $this->assignee;
            $assigneeDetails = EmployeeDetails::find($extracted);

            if ($assigneeDetails) {
                $assigneeEmail = $assigneeDetails->email;

                if (!empty($assigneeEmail)) {
                    $searchData = $this->filterData ?: $this->records;

                    $taskName = $this->task_name;
                    $description = $this->description;
                    $assignee = $this->assignee;
                    preg_match('/^(.*?)\s+#\((.*?)\)$/', $assignee, $matches);

                    if (isset($matches[1]) && isset($matches[2])) {
                        $namePart = ucwords(strtolower(trim($matches[1]))); // Format the name
                        $idPart = strtoupper(trim($matches[2])); // Convert ID to uppercase
                        $formattedAssignee = $namePart . ' #(' . $idPart . ')'; // Combine formatted name and ID
                    } else {
                        $formattedAssignee = ucwords(strtolower($assignee)); // Fallback if the format doesn't match
                    }

                    $dueDate = $this->due_date; // Make sure this variable is defined
                    $priority = $this->priority; // Make sure this variable is defined
                    $assignedBy = $this->employeeDetails->first_name . ' ' . $this->employeeDetails->last_name;


                    Mail::to($assigneeEmail)->send(new TaskAssignedNotification(
                        $taskName,
                        $description,
                        $dueDate,
                        $priority,
                        $assignedBy,
                        $formattedAssignee,
                        $searchData,
                        '',
                        false
                    ));
                }
            }

            foreach ($this->selectedPeopleForFollowers as $followerId) {
                $followerDetails = EmployeeDetails::find($followerId);
                $searchData = $this->filterData ?: $this->records;

                $taskName = $this->task_name;
                $description = $this->description;
                $assignee = $this->assignee;
                preg_match('/^(.*?)\s+#\((.*?)\)$/', $assignee, $matches);

                if (isset($matches[1]) && isset($matches[2])) {
                    $namePart = ucwords(strtolower(trim($matches[1]))); // Format the name
                    $idPart = strtoupper(trim($matches[2])); // Convert ID to uppercase
                    $formattedAssignee = $namePart . ' #( ' . $idPart . ' )'; // Combine formatted name and ID
                } else {
                    $formattedAssignee = ucwords(strtolower($assignee)); // Fallback if the format doesn't match
                }


                if ($followerDetails) {
                    $followerFirstName = ucwords(strtolower($followerDetails->first_name));
                    $followerLastName = ucwords(strtolower($followerDetails->last_name));
                    $followerIdFormatted = strtoupper(trim($followerDetails->emp_id)); // Assuming this is the ID

                    // Combine follower's name and ID
                    $formattedFollowerName = $followerFirstName . ' ' . $followerLastName . ' #( ' . $followerIdFormatted . ' )';
                    $dueDate = $this->due_date; // Make sure this variable is defined
                    $priority = $this->priority; // Make sure this variable is defined
                    $assignedBy = $this->employeeDetails->first_name . ' ' . $this->employeeDetails->last_name;
                    if (!empty($followerDetails->email)) {
                        Mail::to($followerDetails->email)->send(new TaskAssignedNotification(
                            $taskName,
                            $description,
                            $dueDate,
                            $priority,
                            $assignedBy,
                            $formattedAssignee,
                            $searchData,
                            $formattedFollowerName, // Pass formatted follower name
                            true
                        ));
                    }
                }
            }


            if ($extracted != $this->employeeDetails->emp_id) {

                Notification::create([
                    'emp_id' => $this->employeeDetails->emp_id,
                    'notification_type' => 'task',
                    'task_name' => $this->task_name,
                    'assignee' => $this->assignee,
                ]);
            }

            session()->flash('showAlert', true);
            FlashMessageHelper::flashSuccess('Task created successfully!');
            $this->resetFields();
            $this->loadTasks();
            $this->showDialog = false;
            $this->filteredPeoples = [];
            $this->filteredFollowers = [];
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->setErrorBag($e->validator->getMessageBag());
        } catch (\Exception $e) {
            Log::error('Uploaded file error: ' . $e->getMessage(), ['exception' => $e]);
            FlashMessageHelper::flashError('An error occurred while creating the request. Please try again.');
        }
    }
    public function fileSelected()
    {
        FlashMessageHelper::flashSuccess('File Uploaded successfully!');
    }

    public function resetFields()
    {
        $this->task_name = null;
        $this->assignee = null;
        $this->client_id = null;
        $this->project_name = null;
        $this->priority = 'Low';
        $this->due_date = null;
        $this->tags = null;
        $this->followers = null;
        $this->subject = null;
        $this->description = null;
        $this->selectedPeopleName = null;
        $this->selectedPeopleNamesForFollowers = [];
        $this->showRecipients = false;
        $this->selectedFollowers = null;
        $this->file_path = null;
    }

    public $client_id, $project_name, $image_path;

    public $validate_tasks = false;

    public function show()
    {
        $this->showDialog = true;
        $this->selectedPeopleForFollowers = [];
        $this->selectedPersonClients = null;
        $this->selectedPersonClientsWithProjects = null;
    }
    public $recordId;
    public $viewrecord;
   

    public function close()
    {
        $this->resetErrorBag();
        $this->resetFields();
        $this->showDialog = false;
        $this->validate_tasks = false;
        $this->assigneeList = false;
        $this->followersList = false;
        $this->validationFollowerMessage = '';
        $this->selectedPeopleForFollowers = [];
        $this->searchTerm = '';
        $this->filteredPeoples = [];
        $this->filteredFollowers = [];
    }

    public function filter()
    {
        // Fetch the company_ids for the logged-in employee
        $employeeId = auth()->guard('hr')->user()->emp_id;
        $companyIds = EmployeeDetails::where('emp_id', $employeeId)->value('company_id');

        // Check if companyIds is an array; decode if it's a JSON string
        $companyIdsArray = is_array($companyIds) ? $companyIds : json_decode($companyIds, true);
        $trimmedSearchTerm = trim($this->searchTerm);


        $this->filteredPeoples = EmployeeDetails::where(function ($query) use ($companyIdsArray) {
            foreach ($companyIdsArray as $companyId) {
                $query->orWhereJsonContains('company_id', $companyId);
            }
        })
            ->where(function ($query) {
                $query->where('employee_status', 'active')
                    ->orWhere('employee_status', 'on-probation');
            })
            ->where(function ($query) use ($trimmedSearchTerm) {
                $query->where(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', '%' . $trimmedSearchTerm . '%')
                    ->orWhere('emp_id', 'like', '%' . $trimmedSearchTerm . '%');
            })
            ->orderByRaw("FIELD(emp_id, ?) DESC", [$employeeId])
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();



        $this->peopleFound = count($this->filteredPeoples) > 0;
    }
    public $filteredFollowers;
    public function filterFollower()
    {
        $employeeId = auth()->guard('hr')->user()->emp_id;

        // Fetch the company_ids for the logged-in employee
        $companyIds = EmployeeDetails::where('emp_id', $employeeId)->value('company_id');

        // Check if companyIds is an array; decode if it's a JSON string
        $companyIdsArray = is_array($companyIds) ? $companyIds : json_decode($companyIds, true);

        $trimmedSearchTerm = trim($this->searchTermFollower);
        // Assuming $this->selectedPeopleName contains the string
        $selectedPeopleName = $this->selectedPeopleName;

        // Use a regular expression to extract the ID
        preg_match('/#\((.*?)\)/', $selectedPeopleName, $matches);

        // Check if a match was found
        if (isset($matches[1])) {
            $assignedEmployeeId = $matches[1]; // This will hold the extracted ID (e.g., XSS-0490)
        } else {
            $assignedEmployeeId = null; // Handle the case where no ID is found
        }

        $this->filteredFollowers = EmployeeDetails::where(function ($query) use ($companyIdsArray) {
            foreach ($companyIdsArray as $companyId) {
                $query->orWhereJsonContains('company_id', $companyId);
            }
        })
            ->where(function ($query) {
                $query->where('employee_status', 'active')
                    ->orWhere('employee_status', 'on-probation');
            })
            ->where(function ($query) use ($trimmedSearchTerm) {
                $query->where(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', '%' . $trimmedSearchTerm . '%')
                    ->orWhere('emp_id', 'like', '%' . $trimmedSearchTerm . '%');
            })
            ->where('emp_id', '!=', $assignedEmployeeId)
            ->orderByRaw("FIELD(emp_id, ?) DESC", [$employeeId])
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();


        $this->peopleFound = count($this->filteredFollowers) > 0;
    }
    public function openAddCommentModal($taskId)
    {
        $this->taskId = $taskId;
        $this->newComment = '';
        $this->resetErrorBag('newComment');
        $this->showModal = true;
        $this->fetchTaskComments($taskId);
    }
    public function closeModal()
    {
        $this->showModal = false;
    }
    public function openEditCommentModal($commentId)
    {
        $comment = TaskComment::findOrFail($commentId);
        $this->editCommentId = $commentId;          // Store the comment ID being edited
        $this->editingComment = $comment->comment;  // Set the current comment into editingComment
        $this->newComment = '';
    }


    public function updateComment($commentId)
    {
        $this->validate([
            'editingComment' => 'required|string|word_count:500|max:3000',
        ]);

        $comment = TaskComment::findOrFail($commentId);
        $comment->update([
            'comment' => $this->editingComment,
        ]);
        FlashMessageHelper::flashSuccess('Comment updated successfully.');
        $this->showAlert = true;


        // Reset the edit state
        $this->editCommentId = null;
        $this->editingComment = '';
        $this->fetchTaskComments($this->taskId);
    }
    public function addComment()
    {
        $this->validate([
            'newComment' => 'required|string|word_count:500|max:3000',
        ]);
        $employeeId = auth()->guard('hr')->user()->emp_id;
        TaskComment::create([
            'emp_id' => $employeeId,
            'task_id' => $this->taskId,
            'comment' => $this->newComment,
        ]);

        $this->commentAdded = true; // Set the flag to indicate that a comment has been added
        $this->newComment = '';
        $this->showModal = false;
        FlashMessageHelper::flashSuccess('Comment added successfully.');
        $this->showAlert = true;
    }
    public function updatedNewComment($value)
    {
        $this->newComment = ucfirst($value); // Capitalize the first letter
        $this->validateOnly('newComment');
    }

    // Delete a comment
    public function deleteComment($commentId)
    {
        try {
            $comment = TaskComment::findOrFail($commentId);
            $comment->delete();
            FlashMessageHelper::flashSuccess('Comment deleted successfully.');
            $this->showAlert = true;
            $this->fetchTaskComments($this->taskId);
        } catch (\Exception $e) {
            FlashMessageHelper::flashError('Failed to delete comment: ' . $e->getMessage());
        }
    }
    public function cancelEdit()
    {
        $this->editCommentId = null;
    }

    public $taskComments = []; // Variable to hold comments for the modal

    public function fetchTaskComments($taskId)
    {
        if ($this->commentAdded) {
            // If a new comment has been added, reset the flag and fetch the comments again
            $this->commentAdded = false;
        }
        $this->taskComments = TaskComment::with(['employee' => function ($query) {
            $query->select(DB::raw("CONCAT(first_name, ' ', last_name) AS full_name"), 'emp_id');
        }])
            ->whereHas('employee', function ($query) {
                $query->whereColumn('emp_id', 'task_comments.emp_id');
            })
            ->where('task_id', $taskId)
            ->latest()
            ->get();
    }

   

    public function render()
    {

        $this->fetchTaskComments($this->taskId);
        // Retrieve the authenticated employee's ID
        $employeeId = auth()->guard('hr')->user()->emp_id;
        $companyIds = EmployeeDetails::where('emp_id', $employeeId)->value('company_id');

        // Check if companyIds is an array; decode if it's a JSON string
        $companyIdsArray = is_array($companyIds) ? $companyIds : json_decode($companyIds, true);

        // Fetch employees, ensuring the authenticated employee is shown first


        $this->peoples = EmployeeDetails::where(function ($query) use ($companyIdsArray) {
            foreach ($companyIdsArray as $companyId) {
                $query->orWhereJsonContains('company_id', $companyId);
            }
        })
            ->where(function ($query) {
                $query->where('employee_status', 'active')
                    ->orWhere('employee_status', 'on-probation');
            })
            ->orderByRaw("FIELD(emp_id, ?) DESC", [$employeeId])
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();

        // Assuming $this->selectedPeopleName contains the string
        $selectedPeopleName = $this->selectedPeopleName;


        // Use a regular expression to extract the ID
        preg_match('/#\((.*?)\)/', $selectedPeopleName, $matches);

        // Check if a match was found
        if (isset($matches[1])) {
            $assignedEmployeeId = $matches[1]; // This will hold the extracted ID (e.g., XSS-0490)
        } else {
            $assignedEmployeeId = null; // Handle the case where no ID is found
        }



        $this->followerPeoples = $this->peoples->where('emp_id', '!=', $assignedEmployeeId);




        $peopleAssigneeData = $this->filteredPeoples ? $this->filteredPeoples : $this->peoples;
        $peopleFollowerData = $this->filteredFollowers ? $this->filteredFollowers : $this->followerPeoples;

        $this->record = Task::all();
        $employeeName = auth()->user()->first_name . ' #(' . $employeeId . ')';
        $this->records = Task::with('emp')
            ->join('status_types', 'tasks.status', '=', 'status_types.status_code') // Join the status_types table
            ->where(function ($query) use ($employeeId, $employeeName) {
                $query->where('tasks.emp_id', $employeeId)
                    ->orWhere('tasks.assignee', 'LIKE', "%$employeeName%");
            })
            ->select('tasks.*', 'status_types.status_name') // Select all task fields and the status name
            ->orderBy('tasks.created_at', 'desc')
            ->get();

        $searchData = $this->filterData ?: $this->records;
      
        return view('livewire.tasks', [
            'peopleAssigneeData' => $peopleAssigneeData,
            'peopleFollowerData' => $peopleFollowerData,
            'searchData' => $searchData,
            'taskComments' => $this->taskComments,


        ]);
    }
}

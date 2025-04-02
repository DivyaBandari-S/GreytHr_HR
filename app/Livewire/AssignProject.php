<?php
namespace App\Livewire;

use App\Models\AssignProjects;
use App\Models\ClientDetails;
use App\Models\ClientProjects;
use App\Models\EmployeeDetails;
use App\Models\ClientsEmployee;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Helpers\FlashMessageHelper;
use Illuminate\Support\Facades\Log;

class AssignProject extends Component
{
    public $clients = [];
    public $projects = [];
    public $employees = [];
    public $selectedClient = null;
    public $selectedProject = null;
    public $selectedEmployee = null;
    public $search = '';
    public $showContainer = false;
    public $selectedEmployeeDetails = [];
    public $startDate = null;
    public $endDate = null;

    // Validation rules for the form
    protected $rules = [
        'selectedClient' => 'required',
        'selectedProject' => 'required',
        'selectedPeople' => 'required|array|min:1', // Validate that at least one employee is selected
        'startDate' => 'nullable|date',
        'endDate' => 'nullable|date',
    ];
    public function clearValidationMessages($field)
{
    // Clear the validation error for the specific field
    $this->resetErrorBag($field);
}

    
    public $showCcRecipents = false;
    public $selectedCCEmployees = [];
    public $ccRecipients;
    public $searchTerm = '';
    public $hasReachedLimit = false;
    public $selectedPeople = [];
    public function toggleSelection($empId)
    {
        try {
            if (isset($this->selectedPeople[$empId])) {
                // Deselect employee and reset limit flag
                unset($this->selectedPeople[$empId]);
                $this->hasReachedLimit = false;
            } else {
                // Check if the selection limit is reached
                if (count($this->selectedPeople) < 5) {
                    // Select employee if within limit
                    $this->selectedPeople[$empId] = true;
                } else {
                    // Show warning only once if limit reached
                    if (!$this->hasReachedLimit) {
                        FlashMessageHelper::flashWarning("You reached maximum limit of CC To");
                        $this->hasReachedLimit = true;
                    }
                }
            }
            // Always update recipients list and fetch employee details
            $this->searchCCRecipients();
            $this->fetchEmployeeDetails();
        } catch (\Exception $e) {
           
        
            // Notify the user if an error occurs
            FlashMessageHelper::flashError('An error occurred while processing your selection. Please try again.');
            return false;
        }
        
    }
    public function searchCCRecipients()
    {
        try {
            // Get the logged-in employee's ID
            $employeeId = auth()->guard('hr')->user()->emp_id;

            // Fetch the company IDs for the logged-in employee
            $companyIds = EmployeeDetails::where('emp_id', $employeeId)->value('company_id');

            // Ensure companyIds is an array; decode if it's a JSON string
            $companyIdsArray = is_array($companyIds) ? $companyIds : (is_null($companyIds) ? [] : json_decode($companyIds, true));

            // Initialize an empty collection for recipients
            $this->ccRecipients = collect();

            // Fetch all employees for the relevant company IDs at once
            $employees = EmployeeDetails::whereJsonContains('company_id', $companyIdsArray)
                ->where('emp_id', '!=', $employeeId)
                ->whereIn('employee_status', ['active', 'on-probation'])
                ->when($this->searchTerm, function ($query) {
                    $query->where(function ($subQuery) {
                        $subQuery->where('first_name', 'like', '%' . $this->searchTerm . '%')
                            ->orWhere('last_name', 'like', '%' . $this->searchTerm . '%');
                    });
                })
                ->groupBy('emp_id', 'image', 'gender')
                ->select(
                    'emp_id',
                    'gender',
                    'image',
                    DB::raw('MIN(CONCAT(first_name, " ", last_name)) as full_name')
                )
                ->orderBy('full_name')
                ->get();

            $this->ccRecipients = collect($employees);

            // Optionally, remove duplicates if necessary
            $this->ccRecipients = $this->ccRecipients->unique('emp_id');
        } catch (\Exception $e) {
            
            FlashMessageHelper::flashError('An error occurred while searching for CC recipients. Please try again later.');
          
        }
    }
    public function fetchEmployeeDetails()
    {
        // Reset the list of selected employees
        $this->selectedCCEmployees = [];

        // Fetch employee IDs from selected people
        $employeeIds = array_keys($this->selectedPeople);

        // Ensure there are employee IDs to fetch
        if (empty($employeeIds)) {
            return; // No selected employees to fetch
        }

        try {
            // Fetch details for selected employees in one query
            $employees = EmployeeDetails::whereIn('emp_id', $employeeIds)->get();

            // Map employees to selectedCCEmployees
            $this->selectedCCEmployees = $employees->map(function ($employee) {
                // Calculate initials
                $initials = strtoupper(substr($employee->first_name, 0, 1) . substr($employee->last_name, 0, 1));

                // Return the transformed employee data
                return [
                    'emp_id' => $employee->emp_id,
                    'first_name' => $employee->first_name,
                    'last_name' => $employee->last_name,
                    'initials' => $initials,
                ];
            })->toArray(); // Convert the collection back to an array

        } catch (\Exception $e) {
            FlashMessageHelper::flashError('An error occurred while fetching employee details. Please try again later.');
        }
    }
    public function openCcRecipientsContainer()
    {
        try {
            $this->showCcRecipents = true;
            if ($this->showCcRecipents = true) {
                $this->searchCCRecipients();
            }
        } catch (\Exception $e) {
            FlashMessageHelper::flashError('An error occurred. Please try again later.');
        }
    }
    public $showCCEmployees = false;
    public function openModal()
    {
        $this->showCCEmployees = !$this->showCCEmployees;
    }
    public $selectedCcTo = [];
    public $emp_id;
    public function removeFromCcTo($empId)
    {
        try {
            // Remove the employee from selectedCcTo array
            $this->selectedCcTo = array_values(array_filter($this->selectedCcTo, function ($recipient) use ($empId) {
                return $recipient['emp_id'] != $empId;
            }));

            // Update cc_to field with selectedCcTo (comma-separated string of emp_ids)
            $this->emp_id = implode(',', array_column($this->selectedCcTo, 'emp_id'));

            // Toggle selection state in selectedPeople
            unset($this->selectedPeople[$empId]);

            // Fetch updated employee details
            $this->fetchEmployeeDetails();
            $this->searchCCRecipients();
        } catch (\Exception $e) {
            // Notify the user
            FlashMessageHelper::flashError('An error occurred while removing CC recipients.');
            return false;
        }
    }

    public function mount()
    {
        $this->selectedPeople = [];
        // Fetch data for the clients and employees initially
        $this->clients = ClientDetails::has('clientProjects')->
        where('status', 0)
        ->get();
        $this->projects = [];
        $this->employees = EmployeeDetails::whereNotIn('employee_status', ['terminated', 'resigned'])->get();
    }
    public function closeCcRecipientsContainer()
    {
        try {
            $this->showCcRecipents = !$this->showCcRecipents;
        } catch (\Exception $e) {
            FlashMessageHelper::flashError('An error occurred. Please try again later.');
        }
    }
    public function updatedSelectedCCEmployees($value)
{
    // Clear validation errors when the list is not empty
    if (count($this->selectedCCEmployees) > 0) {
        $this->resetErrorBag('selectedPeople');
    }
}


    // Listen for changes to the selected client and update the projects list
    public function handleClientSelection($field)
    {
        $this->resetErrorBag($field);
        $clientId = $this->selectedClient;  // This retrieves the selected client ID from the bound property.
 
    
        if ($clientId) {
            // Fetch projects related to the selected client
            $this->projects = ClientProjects::where('client_id', $clientId)->get();
            $this->selectedProject = null; // Clear the selected project
        } else {
            $this->projects = []; // Clear projects if no client is selected
        }
    }
    

    // Handle search and show matching employees
    public function searchFilter()
    {
        if ($this->search !== '') {
            $this->showContainer = true;
            $trimmedSearchTerm = trim($this->search);

            // Search employees based on the search term
            $this->employees = EmployeeDetails::whereNotIn('employee_status', ['terminated', 'resigned'])
                ->where(function ($query) use ($trimmedSearchTerm) {
                    $query->where(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', '%' . $trimmedSearchTerm . '%')
                        ->orWhere('emp_id', 'like', '%' . $trimmedSearchTerm . '%');
                })
                ->get();
        } else {
            $this->showContainer = false;
            $this->employees = [];  // Clear the results if the search is empty
        }
    }

    // Select an employee and show their details
    public function selectEmployee($employeeId)
    {
        if (is_null($employeeId)) {
            // If the employee is deselected, clear the selected employee details
            $this->selectedEmployee = null;
            $this->selectedEmployeeDetails = [];  // Show the search bar again
            $this->showContainer = false;  // Show the container again
            $this->search = "";
        } else {
            // If an employee is selected, store the details in the array
            $this->selectedEmployee = $employeeId;
            $this->showContainer = false;  // Hide the container of employees
    
            // Fetch employee details and format them
            $employee = EmployeeDetails::with('personalInfo')
                ->where('emp_id', $this->selectedEmployee)
                ->first(); // Fetch the first match based on emp_id
        }
    }

    // Submit the form data
    public function submit()
    {
        
        $this->validate();
        $ccToDetails = [];
            foreach ($this->selectedCCEmployees as $selectedEmployee) {
                $selectedEmployeeId = $selectedEmployee['emp_id']; // Get the emp_id

                // Check if the emp_id is not already in $ccToDetails
                if (!in_array($selectedEmployeeId, array_column($ccToDetails, 'emp_id'))) {
                    $employeeDetails = EmployeeDetails::where('emp_id', $selectedEmployeeId)->first();

                    if ($employeeDetails) {
                        $ccToDetails[] = [
                            'emp_id' => $selectedEmployeeId,
                            'full_name' => $employeeDetails->first_name . ' ' . $employeeDetails->last_name,
                            'email' => $employeeDetails->email
                        ];
                    }
                }
            }

        // Store the project assignment
        AssignProjects::create([
            'client_id' => $this->selectedClient,
            'project_name' => $this->selectedProject,
            'emp_id' => json_encode($ccToDetails),
            'start_date' => $this->startDate,
            'end_date' => $this->endDate,
        ]);

        session()->flash('message', 'Project assigned successfully!');

        // Optionally, reset the form fields after submission
        $this->reset();
    }
    
    public $assignedProjects;

    public function render()
    {
        $this->assignedProjects = AssignProjects::get();
        return view('livewire.assign-project');
    }
}

<?php
namespace App\Livewire;

use App\Models\HrEmployee;
use App\Models\EmployeeDetails;
use Livewire\Component;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;
use App\Helpers\FlashMessageHelper;

class HrEmployeesList extends Component
{
    public $hrEmployees;
    public $emp_id, $employee_name, $email, $role;
    public $employee_id;
    public $password;
    public $search = '';
    public $employee;
    public $selectedEmployee = null; 
    public $showSearch = true;
    public $showContainer = false;
    public $employees ;
    public $employeeType = 'active';
    public $errorMessage = null;

    

    public $isEditMode = false; // For handling edit mode
    
    public function loadEmployees()
    {

        if ($this->employeeType === 'active') {
            $this->employees = EmployeeDetails::whereIn('employee_status', ['active', 'on-probation'])->get();
        } else {
            $this->employees = EmployeeDetails::whereIn('employee_status', ['terminated', 'resigned'])->get();
        }
    }
    public function searchFilter()
    {
        if ($this->search !== '') {
            $this->showContainer = true; // Show the container when the search is triggered
    
            // Search with the existing term
            $this->employees = EmployeeDetails::where(function ($query) {
                $query->where('first_name', 'like', '%' . $this->search . '%')
                      ->orWhere('last_name', 'like', '%' . $this->search . '%')
                      ->orWhere('emp_id', 'like', '%' . $this->search . '%'); // Ensure `like` for partial match
            })
            ->where(function ($query) {
                if ($this->employeeType === 'active') {
                    $query->whereIn('employee_status', ['active', 'on-probation']);
                } else {
                    $query->whereIn('employee_status', ['terminated', 'resigned']);
                }
            })
            ->get();
    
            // If no results found, the container should still be shown to display the message
            if ($this->employees->isEmpty()) {
                // You can decide if you want to show "No employees found." here or in the Blade.
            }
        } else {
            // If search term is empty, hide the container and reload the employees
            $this->showContainer = false; // Hide the container
            $this->loadEmployees(); // Reload current employees
        }
    }

    public function render()
    {
        if (empty($this->search)) {
        $this->hrEmployees = HrEmployee::all(); // Fetch all HR employees from the database
        }
        $selectedEmployeesDetails = $this->selectedEmployee ? 
        EmployeeDetails::where('emp_id', $this->selectedEmployee)->get() : [];
        return view('livewire.hr-employees-list',  [
            'selectedEmployeesDetails' => $selectedEmployeesDetails]);
    }
    public function selectEmployee($employeeId)
    {
        $employeeInHR = HrEmployee::where('emp_id', $employeeId)->exists(); // Assuming 'HrEmployees' is the model for the 'hremployees' table

    if ($employeeInHR) {
        // Set the error message if the employee exists in hremployees table
        $this->errorMessage = 'This employee has already been selected.';
        $this->showContainer = false;
        return;
    }

    // If no errors, reset the error message and proceed with selection
    $this->errorMessage = null;
        // Check if the employee is already selected
        $this->selectedEmployee = $employeeId; // Change here
        Log::info('Selected Employee: ', [$this->selectedEmployee]);
    
        if (is_null($employeeId)) {
            $this->showSearch = true;
            $this->search = '';
            $this->selectedEmployee = null;
            $this->employee_name = ''; // Reset name
            $this->email = ''; // Reset email
            $this->role = ''; // Reset role
        } else {
            $this->showSearch = false;
            $this->showContainer = false;
            
            // Load selected employee details
            $employee = EmployeeDetails::find($employeeId);
            if ($employee) {
                $this->employee_name = $employee->first_name . ' ' . $employee->last_name;
                $this->emp_id = $employee->emp_id;
                $this->password= $employee->password;
                $this->email = $employee->email;
                $this->role = $employee->role;
            }
        }
    }
    public function cancel()
    {
        $this->reset();
      
    }
    public $showModal = false; 
    public function showAddEditEmployee($employeeId = null){
        
            // If an employee is selected, open the modal in edit mode
            $this->showModal = true;
            
            if ($employeeId) {
                // Load selected employee data for editing
                $employee = HrEmployee::find($employeeId);
                if ($employee) {
                    // $this->selectedEmployee = $employee->emp_id;
                    $this->employee_name = $employee->first_name . ' ' . $employee->last_name;
                    $this->email = $employee->email;
                    $this->role = $employee->role;
                    $this->emp_id = $employee->emp_id;
                }
            } else {
                // Reset the form for adding a new employee
                $this->resetForm();
            }
        
        // return redirect()->route('add-edit-hr');
    }
    public function closeModal()
    {
        $this->showModal = false; // Close the modal
    }
    public function resetForm()
    {
        $this->employee_name = '';
        $this->email = '';
        $this->role = '';
        $this->search = '';
        $this->showSearch = true;
        $this->selectedEmployee = null; // Reset selected employee
    }
    

    public function save()
    {
        // Validation for the fields before saving
        $validatedData = $this->validate([
            'employee_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'role' => 'required|in:user,admin',
        ]);
        if ($this->emp_id) {
            // Update existing employee
            $employee = HrEmployee::find($this->emp_id);
            if ($employee) {
                $employee->update([
                    'employee_name' => $this->employee_name,
                    'email' => $this->email,
                    'role' => $this->role,
                ]);
            }
            FlashMessageHelper::flashSuccess('Employee updated successfully!');
        }  else {
       
            // Create a new employee record
            HrEmployee::create([
                'emp_id' => $this->emp_id,
                'employee_name' => $this->employee_name,
                'password' => $this->password,
                'email' => $this->email,
                'role' => $this->role,
                'status' => 0, // Active by default
            ]);
        FlashMessageHelper::flashSuccess('Employee saved successfully!');
    }
        $this->showModal = false;
        // If you want to reset the form fields after saving
        $this->resetForm();
         
    }

    public function searchEmployees()
    {
     
        // Filter the HR employees based on emp_id and employee_name
        $this->hrEmployees = HrEmployee::where('emp_id', 'like', '%' . $this->search . '%')
                                        ->orWhere('employee_name', 'like', '%' . $this->search . '%')
                                        ->get();
    }

   
    public function mount($emp_id = null)
    {
        $this->employees = new Collection();
        $this->loadEmployees();
    
        if ($emp_id) {
            $this->employee = EmployeeDetails::findOrFail($emp_id);
            $this->emp_id = $this->employee->emp_id;
            $this->password = $this->employee->password;
            $this->employee_name = $this->employee->first_name . ' ' . $this->employee->last_name; // Set employee name
            $this->email = $this->employee->email; // Set email
            $this->role = $this->employee->role; // Set role
        }
    }
    public function deleteEmployee($empId)
    {
        // Find the employee by emp_id
        $employee = HrEmployee::find($empId);

        if ($employee) {
            // Set the status to 'inactive' (1 means inactive)
            $employee->status = 1;
            $employee->save();

            // Flash message to indicate success
            FlashMessageHelper::flashSuccess('Employee Deleted successfully!');
           
        } else {
            FlashMessageHelper::flashError('Employee Not Found');
        }
    }

  
}

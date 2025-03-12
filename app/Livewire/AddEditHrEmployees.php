<?php
namespace App\Livewire;

use App\Models\EmployeeDetails;
use App\Models\HrEmployee;
use Livewire\Component;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class AddEditHrEmployees extends Component
{
   // For handling edit mode

    public function render()
    {
        $selectedEmployeesDetails = $this->selectedEmployee ? 
        EmployeeDetails::where('emp_id', $this->selectedEmployee)->get() : [];
        
        
        
        return view('livewire.add-edit-hr-employees',  [
            'selectedEmployeesDetails' => $selectedEmployeesDetails]);
    }
    public function selectEmployee($employeeId)
    {
        // Check if the employee is already selected
        $this->selectedEmployee = $employeeId; // Change here
     
    
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
    

    public $emp_id;
    public $password;
    public $employee_name;
    public $email;
    public $role;
    public $employee; // For editing an existing employee
    public $selectedEmployee = null; 
    public $showSearch = true;
    public $showContainer = false;
    public $employees ;
    public $search = '';
    public $employeeType = 'active';

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

    // For add/edit functionality
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
        
       
            // Create a new employee record
            HrEmployee::create([
                'emp_id' => $this->emp_id,
                'employee_name' => $this->employee_name,
                'password' => $this->password,
                'email' => $this->email,
                'role' => $this->role,
                'status' => 0, // Active by default
            ]);
     
    
        // Optionally, reset the form or handle any other necessary logic
        session()->flash('message', 'Employee saved successfully!');
        
        // If you want to reset the form fields after saving
        $this->resetForm();
    }
    


    public function cancel()
    {
        $this->reset();
        $this->emit('formCancelled');
    }
}

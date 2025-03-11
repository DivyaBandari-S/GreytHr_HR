<?php
namespace App\Livewire;

use App\Models\HrEmployee;
use Livewire\Component;

class HrEmployeesList extends Component
{
    public $hrEmployees;
    public $emp_id, $employee_name, $email, $status, $role;
    public $employee_id;
    public $search = '';

    public $isEditMode = false; // For handling edit mode

    public function render()
    {
        if (empty($this->search)) {
        $this->hrEmployees = HrEmployee::all(); // Fetch all HR employees from the database
        }
        return view('livewire.hr-employees-list');
    }
    public $showModal = false; 
    public function showAddEditEmployee(){
        $this->showModal = true;
        // return redirect()->route('add-edit-hr');
    }

    // Method to store or update HR employee
    public function storeOrUpdate()
    {
        $this->validate([
            'emp_id' => 'required|string|max:10',
            'employee_name' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:hr_employees,email,' . $this->employee_id,
            'status' => 'required|in:1,0',  // assuming status is either 1 or 0
            'role' => 'required|in:user,admin,super_admin',
        ]);

        if ($this->isEditMode) {
            // Update employee
            $employee = HrEmployee::find($this->employee_id);
            $employee->update([
                'emp_id' => $this->emp_id,
                'employee_name' => $this->employee_name,
                'email' => $this->email,
                'status' => $this->status,
                'role' => $this->role,
            ]);
        } else {
            // Create new employee
            HrEmployee::create([
                'emp_id' => $this->emp_id,
                'employee_name' => $this->employee_name,
                'email' => $this->email,
                'status' => $this->status,
                'role' => $this->role,
            ]);
        }

        $this->resetFields();
        session()->flash('message', $this->isEditMode ? 'Employee updated successfully!' : 'Employee added successfully!');
    }
    public function searchEmployees()
    {
     
        // Filter the HR employees based on emp_id and employee_name
        $this->hrEmployees = HrEmployee::where('emp_id', 'like', '%' . $this->search . '%')
                                        ->orWhere('employee_name', 'like', '%' . $this->search . '%')
                                        ->get();
    }
    

    // Set the properties for editing
    public function edit($id)
    {
        $this->isEditMode = true;
        $employee = HrEmployee::find($id);
        $this->employee_id = $employee->id;
        $this->emp_id = $employee->emp_id;
        $this->employee_name = $employee->employee_name;
        $this->email = $employee->email;
        $this->status = $employee->status;
        $this->role = $employee->role;
    }

    // Method to delete an employee
    public function delete($id)
    {
        HrEmployee::find($id)->delete();
        session()->flash('message', 'Employee deleted successfully!');
    }

    // Reset input fields
    private function resetFields()
    {
        $this->emp_id = '';
        $this->employee_name = '';
        $this->email = '';
        $this->status = 1;
        $this->role = 'user';
        $this->isEditMode = false;
    }
}

<?php
// File Name                       : UpdateEmployeeDetails.php
// Description                     : This file contains the implementation of list employee details admin side
// Creator                         : Bandari Divya
// Email                           : bandaridivya1@gmail.com
// Organization                    : PayG.
// Date                            : 2024-03-07
// Framework                       : Laravel (10.10 Version)
// Programming Language            : PHP (8.1 Version)
// Database                        : MySQL
// Models                          : EmployeeDetails
namespace App\Livewire;

use Livewire\Component;
use App\Models\EmployeeDetails;
use App\Models\Company;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class UpdateEmployeeDetails extends Component
{
    // public $employees;
    // public $employeeId;
    public $companies;
    public $hrDetails;
    public $counter = 1;
    public $search = '';
    public $employees = [];
    public $hrCompany_id;
    public $emp_gender = '';
    public $emp_status = '';
    public $sortColumn = 'emp_id'; // Default column to sort by
    public $sortDirection = 'asc';


    // soft delete employee
    public function deleteEmp($id)
    {
        try {
            $emp = EmployeeDetails::findOrFail($id); // Use findOrFail to throw an exception if the employee is not found
            $emp->update(['status' => $emp->status == 1 ? 0 : 1]);
        } catch (\Exception $e) {
            Log::error('Error occurred in delete Employee method: ' . $e->getMessage());
            session()->flash('error', 'An error occurred while deleting employee.');
        }
    }

    public $filteredEmployees;

    //this method for searching the employee details
    public function filter()
    {
        try {

            $this->filteredEmployees = EmployeeDetails::where(function ($query) {
                $query->where('first_name', 'like', '%' . $this->search . '%')
                    ->orWhere('last_name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%')
                    ->orWhere('emp_id', 'like', '%' . $this->search . '%')
                    ->orWhere('mobile_number', 'like', '%' . $this->search . '%');
            })->when($this->emp_gender != '', function ($query) {
                $query->where('gender', $this->emp_gender);
            })->get();
            dd($this->filteredEmployees);
        } catch (\Exception $e) {
            Log::error('Error occurred in filter method: ' . $e->getMessage());
            session()->flash('error', 'An error occurred while filtering employees.');
        }
    }

    public function sortBy($column)
{
    if ($this->sortColumn === $column) {
        // If the column is already selected, toggle the direction
        $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
    } else {
        // Otherwise, set the new column and default to ascending
        $this->sortColumn = $column;
        $this->sortDirection = 'asc';
    }

    // After updating, refresh the employee list
    $this->fetchEmployeeDetails();
}
    public function fetchEmployeeDetails()
    {
        try {
            // Prepare the base query
            $query = EmployeeDetails::query();

            // Filter by company_id using JSON_CONTAINS for each hrCompany_id
            if (!empty($this->hrCompany_id)) {
                $query->where(function ($query) {
                    foreach ($this->hrCompany_id as $companyId) {
                        $query->orWhereRaw("JSON_CONTAINS(company_id, '\"$companyId\"')");
                    }
                });
            }

            // Apply search filter
            if (!empty($this->search)) {
                $query->where(function ($query) {
                    $query->where('first_name', 'like', '%' . $this->search . '%')
                        ->orWhere('last_name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%')
                        ->orWhere('emp_id', 'like', '%' . $this->search . '%');
                    // ->orWhere('mobile_number', 'like', '%' . $this->search . '%');
                });
            }
            if(!empty($this->emp_gender)){
                $query->where('gender',$this->emp_gender);
            }
            if($this->emp_status!=''){

                $query->where('status',$this->emp_status);
            }
            // Ordering by status and fetching results
            $query->orderBy($this->sortColumn, $this->sortDirection);

        // Fetch results
        $this->employees = $query->get();
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Error fetching Employee details: ' . $e->getMessage());
            session()->flash('error_message', 'An error occurred while fetching employee details.');
            $this->employees = []; // Set employees to an empty array
        }
    }


    public function render()
    {
        try {
            $this->hrDetails = auth()->guard('hr')->user();
            $hr_Id =  $this->hrDetails->emp_id;
            $this->hrCompany_id = EmployeeDetails::where('emp_id', $hr_Id)->first()->company_id;

            $hrCompanies = Company::where('company_id',  $this->hrCompany_id)->get();
            $hrDetails = Company::where('company_id',  $this->hrCompany_id)->first();

            $this->companies = $hrCompanies;
            $this->hrDetails = $hrDetails;

            // Wrapping the database query in a try-catch block
            $this->fetchEmployeeDetails();

            foreach ($this->employees as $employee) {
                $employee->encrypted_emp_id = Crypt::encrypt($employee->emp_id);
            }

            return view('livewire.update-employee-details');
        } catch (\Exception $e) {
            // Log the error
            Log::error($e);
            // Flash a message to the session
            session()->flash('error_message', 'An error occurred while fetching data. Please try again later.');
            // Redirect back or to a specific route
            return redirect()->back(); // Or redirect()->route('route.name');
        }
    }
}

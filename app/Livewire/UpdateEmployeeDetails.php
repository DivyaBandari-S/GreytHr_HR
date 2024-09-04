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
    public $employees;
    public $employeeId;
    public $companies;
    public $hrDetails;
    public $counter = 1;
    public $search = '';

    //logic for logout
    public function logout()
    {
        try {
            auth()->guard('com')->logout();
        } catch (\Exception $e) {
            // Store the error message in the session
            Session::flash('error', 'An error occurred during logout. Please try again.');
        }

        return redirect('/Login&Register');
    }

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
            })
                ->get();
        } catch (\Exception $e) {
            Log::error('Error occurred in filter method: ' . $e->getMessage());
            session()->flash('error', 'An error occurred while filtering employees.');
        }
    }

    public function render()
    {
        try {
            $hrEmail = auth()->guard('hr')->user()->company_id;
            $hrCompanies = Company::where('company_id', $hrEmail)->get();
            $hrDetails = Company::where('company_id', $hrEmail)->first();

            $this->companies = $hrCompanies;
            $this->hrDetails = $hrDetails;

            // Wrapping the database query in a try-catch block
            try {
                $this->employees = EmployeeDetails::where('company_id', $hrDetails->company_id)
                    ->where(function ($query) {
                        $query->where('first_name', 'like', '%' . $this->search . '%')
                            ->orWhere('last_name', 'like', '%' . $this->search . '%')
                            ->orWhere('email', 'like', '%' . $this->search . '%')
                            ->orWhere('emp_id', 'like', '%' . $this->search . '%')
                            ->orWhere('mobile_number', 'like', '%' . $this->search . '%');
                    })
                    ->orderBy('status', 'desc')
                    ->get();
            } catch (\Illuminate\Database\QueryException $e) {
                Log::error('Error fetching Employee details: ' . $e->getMessage());
                session()->flash('error_message', 'An error occurred while fetching employee details.');
                $this->employees = []; // Set employees to an empty array or handle error as needed
            }

        }catch (\Exception $e) {
            // Log the error
            Log::error($e);
            // Flash a message to the session
            session()->flash('error_message', 'An error occurred while fetching data. Please try again later.');
            // Redirect back or to a specific route
            return redirect()->back(); // Or redirect()->route('route.name');
        }

        foreach ($this->employees as $employee) {
            $employee->encrypted_emp_id = Crypt::encrypt($employee->emp_id);
        }

        return view('livewire.update-employee-details');
    }


}

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
    public $perPage = 10; // Number of records per page
    public $currentPage = 1; // Current page number
    public $totalEmployees = 0;
    public $trerminateModal = false;
    public $seperation_type = '';
    public $seperation_date;
    public $seperation_reason = '';
    public $seperation_emp_id = '';
    public $successMsg = '';
    public $errMsg = '';



    public function removeFlashMsg(){
        $this->successMsg='';
        $this->errMsg='';

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
    public function terEmployee($empid)
    {

        $this->trerminateModal = true;
        $this->seperation_emp_id = $empid;
    }
    public function closeTerModal()
    {
        $this->trerminateModal = false;
        $this->reset(['seperation_type', 'seperation_date', 'seperation_reason']);

        // Clear validation errors
        $this->resetValidation();
    }
    public function updated($propertyName)
    {

        $this->validateOnly($propertyName);
    }

    protected $rules = [
        'seperation_type' => 'required',
        'seperation_date' => 'required|date|before_or_equal:today',
        'seperation_reason' => 'required|string|max:500|min:20',
    ];
    protected $messages = [
        'seperation_type.required' => 'Select seperation type.',
        'seperation_date.required' => 'Last working date is required.',
        'seperation_date.before_or_equal' => ' Date field must be before or equal to today.',
        'seperation_reason.required' => 'Seperation reason is required.',
        'seperation_reason.min' => 'Reason must contain atleast 20 characters.',
        'seperation_reason.max' => 'Reason must not exceed 500 characters.',
    ];

    public function submit()
    {
        $this->validate();
        try {


            $emp = EmployeeDetails::findOrFail($this->seperation_emp_id); // Use findOrFail to throw an exception if the employee is not found
            // dd($emp);
            $emp->update(
                [
                    'status' => 0,
                    'resignation_date' => $this->seperation_date,
                    'resignation_reason' => $this->seperation_reason,
                    'employee_status' => $this->seperation_type

                ]

            );
           $this->successMsg= ucwords($emp->first_name).' '.ucwords($emp->last_name).' '.'seperated successfully .';
            $this->trerminateModal = false;
            $this->fetchEmployeeDetails();
        } catch (\Exception $e) {

            Log::error('Error occurred in Seperating Employee : ' . $e->getMessage());
        //    $this->errMsg= 'An error occurred while seperating employee.';
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

    public function mount()
    {
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
                        ->orWhere('emp_id', 'like', '%' . $this->search . '%')
                        ->orWhere('emergency_contact', 'like', '%' . $this->search . '%');
                });
                $this->currentPage = 1;
            }
            if (!empty($this->emp_gender)) {
                $query->where('gender', $this->emp_gender);
                $this->currentPage = 1;
            }
            if ($this->emp_status != '') {

                $query->where('status', $this->emp_status);
                $this->currentPage = 1;
            }

            $this->totalEmployees = $query->count();
            // Ordering by status and fetching results
            $this->employees = $query->orderBy($this->sortColumn, $this->sortDirection)->get()->toArray();

            //    dd(  $this->employees );
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Error fetching Employee details: ' . $e->getMessage());
           $this->errMsg= 'An error occurred while fetching employee details.';
            $this->employees = []; // Set employees to an empty array
        }
    }

    public function setPage($page)
    {
        $this->currentPage = max(1, min($page, ceil($this->totalEmployees / $this->perPage)));
        $this->getPaginatedEmployees();
    }
    public function getPaginatedEmployees()
    {
        return array_slice($this->employees, ($this->currentPage - 1) * $this->perPage, $this->perPage);
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

            foreach ($this->employees as &$employee) {
                $employee['encrypted_emp_id'] = Crypt::encrypt($employee['emp_id']);
            }

            $paginatedEmployees = $this->getPaginatedEmployees();

            // dd($paginatedEmployees);
            return view('livewire.update-employee-details', [
                'totalemployees' => $paginatedEmployees,
                'totalPages' => ceil($this->totalEmployees / $this->perPage),
            ]);
        } catch (\Exception $e) {
            // Log the error
            Log::error($e);
            // Flash a message to the session
            $this->errMsg= 'An error occurred while fetching data. Please try again later.';
            // Redirect back or to a specific route
            return redirect()->back(); // Or redirect()->route('route.name');
        }
    }
}

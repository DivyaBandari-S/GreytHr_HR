<?php

namespace App\Livewire;

use App\Helpers\FlashMessageHelper;
use App\Models\EmployeeDetails;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class EmployeeSeparation extends Component
{
    public $searchTerm = '';
    public $employee_type;
    public $filterEmp = '';
    public $employeeIds = [];
    public $selectedFilterEmp;
    public $showEmployeeSearch = false;

    //filter  dropdown
    public function empfilteredData()
    {
        $this->selectedFilterEmp = $this->filterEmp;
        $this->loadEmployeeList();
    }

    public function closeSearchContainer()
    {
        $this->showEmployeeSearch = !$this->showEmployeeSearch;
    }
    //get employee filter based on dropdown
    public function loadEmployeeList()
    {
        try {
            // Get the logged-in employee's ID
            $loggedInEmpID = auth()->guard('hr')->user()->emp_id;

            // Fetch the company ID for the logged-in employee
            $companyID = EmployeeDetails::where('emp_id', $loggedInEmpID)
                ->pluck('company_id')
                ->first();

            // Check if company ID is an array or a string and decode it if necessary
            $companyIdsArray = is_array($companyID) ? $companyID : json_decode($companyID, true);

            // Query employees based on company IDs and status
            $query = EmployeeDetails::where(function ($query) use ($companyIdsArray) {
                foreach ($companyIdsArray as $companyId) {
                    $query->orWhere('company_id', 'like', "%\"$companyId\"%");
                }
            });
            // Add the searchTerm filter if it's provided (not null or empty)
            if (!empty($this->searchTerm)) {
                $query->where(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', '%' . $this->searchTerm . '%');
            }

            if($this->filterEmp == 'current_emp'){
                $query->where('status', true);
            }elseif($this->filterEmp == 'resign_emp'){
                $query->where('status', false);
            }
            // Fetch the employee details with image and full name
            $this->employeeIds = $query->orderBy(DB::raw("CONCAT(first_name, ' ', last_name)"))
                ->get(['emp_id', 'first_name', 'last_name', 'image', DB::raw("CONCAT(first_name, ' ', last_name) as full_name")])
                ->mapWithKeys(function ($employee) {
                    // Map each employee's ID to their full name and image URL
                    return [
                        $employee->emp_id => [
                            'full_name' => $employee->full_name,
                            'image' => $employee->image
                        ]
                    ];
                })
                ->toArray();
        } catch (\Illuminate\Database\QueryException $e) {
            // Catch database-related exceptions
            Log::error("Database query error in loadEmployeeList: " . $e->getMessage());
            FlashMessageHelper::flashError('There was a database error while loading the employee list.');
        } catch (\Exception $e) {
            // Catch any other exceptions
            Log::error("Unexpected error in loadEmployeeList: " . $e->getMessage());
            FlashMessageHelper::flashError('An unexpected error occurred while loading the employee list.');
        }
    }
    public $showResignSection = false;
    //get selected employee
    public function getSelectedEmp($empId){
        $this->showResignSection = true;
        $this->showEmployeeSearch=false;
    }
    //open resingation type section
    public function openResignSec(){
        $this->showResignSection = true;
    }
    public function render()
    {
        return view(
            'livewire.employee-separation',
            [
                'employeeIds' => $this->employeeIds
            ]
        );
    }
}

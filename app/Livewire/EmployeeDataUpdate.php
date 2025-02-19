<?php

namespace App\Livewire;

use App\Helpers\FlashMessageHelper;
use App\Models\EmployeeDetails;
use Carbon\Carbon;
use DateInterval;
use DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class EmployeeDataUpdate extends Component
{
    public $action;
    public $employeeIds = [];
    public $searchTerm = '';
    public $showEmployeeSearch = false;
    public $deleteEmpId;
    public $openSelecetdEmpDetails = false;
    public $empToBeDelet;
    public $new_confirmation_date;
    public $formattedConfirmDate;
    public $confirmEmpId;
    public $openConfirmEmpDetails = false;
    public $empToBeConfirm;
    public function mount($action)
    {
        $this->action = $action;
    }
    public function toggleSearchEmployee()
    {
        // Toggle the visibility of the employee search container
        $this->showEmployeeSearch = true;
        $this->loadEmployeeList();
    }
    public function closeSearchContainer()
    {
        $this->showEmployeeSearch = false;
    }

    // Load employee list based on logged-in employee's company ID
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

            // Fetch the employee details with image and full name
            $this->employeeIds = $query->orderBy(DB::raw("CONCAT(first_name, ' ', last_name)"))
                ->where('employee_status', 'active')
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


    //get selecetd employee
    public function getSelectedEmp($empId)
    {
        $this->showEmployeeSearch = false;
        // Check the action
        if ($this->action == 'delete') {
            $this->deleteEmpId = $empId;
            $this->openSelecetdEmpDetails = true;
            // Fetch the employee details by emp_id
            $this->empToBeDelet = EmployeeDetails::where('emp_id', $this->deleteEmpId)->first();
        } elseif ($this->action == 'confirm') {
            $this->confirmEmpId = $empId;
            $this->openConfirmEmpDetails = true;
            // Fetch the employee details by emp_id
            $this->empToBeConfirm = EmployeeDetails::where('emp_id', $this->confirmEmpId)->first();
            $probationDays  = $this->empToBeConfirm->probation_Period;
            // Create a DateTime object with the current date
            $joiningDate = Carbon::parse($this->empToBeConfirm->hire_date);
            $joiningDate->add('P' . $probationDays . 'D');
            // Format the result as needed (e.g., Y-m-d format)
            $this->formattedConfirmDate = $joiningDate->format('Y-m-d');
        } else {
            // Default action (confirm)
            // Add your confirm logic here
        }
    }


    //conform Selecetd employee
    public function confirmSelecetedEmployee()
    {
        try {
            if ($this->confirmEmpId) {
                $empToBeConfirm = EmployeeDetails::where('emp_id', $this->confirmEmpId)->first();
                if ($empToBeConfirm && $this->formattedConfirmDate) {
                    $empToBeConfirm->update(
                        [
                            'confirmation_date' => $this->formattedConfirmDate
                        ]
                    );
                }
            }
            FlashMessageHelper::flashSuccess('Employee confirmation updated scuccessfully!');
        } catch (\Illuminate\Database\QueryException $e) {
            // Catch database-related exceptions
            Log::error("Database query error in Deleteing employee: " . $e->getMessage());
            FlashMessageHelper::flashError('There was a database error while delconfirmationeting the employee.');
        } catch (\Exception $e) {
            // Catch any other exceptions
            Log::error("Unexpected error in deleteing employee: " . $e->getMessage());
            FlashMessageHelper::flashError('An unexpected error occurred while confirmation the employee.');
        }
    }
    //delete employee record
    public function deleteSelecetedEmployee()
    {
        try {
            if ($this->deleteEmpId) {
                $empToBeDelet = EmployeeDetails::where('emp_id', $this->deleteEmpId)->first();
                if ($empToBeDelet) {
                    $empToBeDelet->delete();
                }
            }
            FlashMessageHelper::flashSuccess('Employee record deleted scuccessfully!');
        } catch (\Illuminate\Database\QueryException $e) {
            // Catch database-related exceptions
            Log::error("Database query error in Deleteing employee: " . $e->getMessage());
            FlashMessageHelper::flashError('There was a database error while deleting the employee.');
        } catch (\Exception $e) {
            // Catch any other exceptions
            Log::error("Unexpected error in deleteing employee: " . $e->getMessage());
            FlashMessageHelper::flashError('An unexpected error occurred while deleting the employee.');
        }
    }
    public function render()
    {
        return view('livewire.employee-data-update', [
            'employeeIds' => $this->employeeIds,
            'empToBeDelet' => $this->empToBeDelet,
            'empToBeConfirm' => $this->empToBeConfirm,
            'formattedConfirmDate'=> $this->formattedConfirmDate
        ]);
    }
}

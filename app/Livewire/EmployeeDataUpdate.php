<?php

namespace App\Livewire;

use App\Helpers\FlashMessageHelper;
use App\Models\EmployeeDetails;
use App\Models\SwipeRecord;
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
    public $lastLoginTime;
    public $openConfirmEmpDetails = false;
    public $empToBeConfirm;

    public $openExtendProbDetails;
    public $extendProbEmpId;
    public $probToBeExtend;
    public $extend_probation;
    public $revisedConfirmationDate;
    public $actualConfirmDate;
    public $openDisablePortalaAccess = false;
    public $disableEmpId;
    public $enableEmpId;
    public $openEnablePortalaAccess = false;
    public $enableEmpDetails;
    public $disableEmployeeDetails;
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
        try {
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

                $probationDays = $this->empToBeConfirm->probation_Period;
                // Create a DateTime object with the current date
                $joiningDate = Carbon::parse($this->empToBeConfirm->hire_date);
                $joiningDate->add('P' . $probationDays . 'D');
                // Format the result as needed (e.g., Y-m-d format)
                $this->formattedConfirmDate = $joiningDate->format('d M, Y');
            } elseif ($this->action == 'extend') {
                $this->openExtendProbDetails = true;
                $this->extendProbEmpId = $empId;
                $this->probToBeExtend = EmployeeDetails::where('emp_id', $this->extendProbEmpId)->first();
                // Original probation period
                $probationDays = $this->probToBeExtend->probation_Period;
                // Create a DateTime object with the current date
                $joiningDate = Carbon::parse($this->probToBeExtend->hire_date);
                $joiningDate->add('P' . $probationDays . 'D');
                $this->actualConfirmDate = $joiningDate->format('d M, Y');
            } elseif ($this->action == 'disable') {
                $this->openDisablePortalaAccess = true;
                $this->disableEmpId = $empId;
                $this->disableEmployeeDetails = EmployeeDetails::where('emp_id', $this->disableEmpId)->first();
                $this->lastLoginTime = SwipeRecord::where('emp_id', $this->disableEmpId)->where('in_or_out', 'IN')->latest()->first();
            } elseif ($this->action == 'enable') {
                $this->openEnablePortalaAccess = true;
                $this->enableEmpId = $empId;
                $this->enableEmpDetails = EmployeeDetails::where('emp_id', $this->enableEmpId)->first();
                $this->lastLoginTime = SwipeRecord::where('emp_id', $this->enableEmpId)->where('in_or_out', 'IN')->latest()->first();
            }
        } catch (\Exception $e) {
            // Handle any exception that occurs
            FlashMessageHelper::flashError('An error occured, please try again later.');
            // Log the error message for debugging purposes
            Log::error('Error in getSelectedEmp: ' . $e->getMessage());
        }
    }

    //disable portal access to selecetd employee
    public function disablePortalAccess()
    {
        try {
            if ($this->disableEmployeeDetails) {
                if ($this->disableEmployeeDetails->status == false) {
                    FlashMessageHelper::flashInfo('Portal access is already disabled for this employee.');
                    return;
                } else {
                    // Enable portal access if it's not already enabled
                    $this->disableEmployeeDetails->update([
                        'status' => false
                    ]);
                    FlashMessageHelper::flashSuccess('Portal access disabled for the employee successfully!');
                }
            } else {
                FlashMessageHelper::flashError('An error occured while getting employee details.');
            }
        } catch (\Exception $e) {
            FlashMessageHelper::flashError('An error occured while disabling employee portal access.');
        } catch (\Illuminate\Database\QueryException $e) {
            // Catch database-related exceptions
            Log::error("Database query error in Deleteing employee: " . $e->getMessage());
            FlashMessageHelper::flashError('There was a database error while disable the employee.');
        }
    }

    //disenable able portal access to selecetd employee
    public function enablePortalAccess()
    {
        try {
            if ($this->enableEmpDetails) {
                if ($this->enableEmpDetails->status) {
                    FlashMessageHelper::flashInfo('Portal access is already enabled for this employee.');
                    return;
                } else {
                    // Enable portal access if it's not already enabled
                    $this->enableEmpDetails->update([
                        'status' => true
                    ]);
                    FlashMessageHelper::flashSuccess('Portal access enabled for the employee successfully!');
                }
            } else {
                FlashMessageHelper::flashError('An error occured while getting employee details.');
            }
        } catch (\Exception $e) {
            FlashMessageHelper::flashError('An error occured while enable employee portal access.');
        } catch (\Illuminate\Database\QueryException $e) {
            // Catch database-related exceptions
            Log::error("Database query error in Deleteing employee: " . $e->getMessage());
            FlashMessageHelper::flashError('There was a database error while disable the employee.');
        }
    }

    //get dynamic revised onfirmation date by updating extend probation
    public function getExtendedProbationDays()
    {
        try {
            if (empty($this->extend_probation)) {
                FlashMessageHelper::flashWarning('Please select Extend Probation Period.');
                return; // If no value is selected, do nothing
            }

            // Original probation period
            $probationDays = $this->probToBeExtend->probation_Period;
            // Get extended probation
            $probationMonths = (int)$this->extend_probation;
            // Create a DateTime object with the current date
            $joiningDate = Carbon::parse($this->probToBeExtend->hire_date);
            $joiningDate->add('P' . $probationDays . 'D');
            // Add the selected months to the joining date
            $joiningDate->addMonths($probationMonths);
            // Format the result as needed (e.g., Y-m-d format)
            $this->revisedConfirmationDate = $joiningDate->format('d M, Y');
        } catch (\Exception $e) {
            // Handle any exception that occurs
            FlashMessageHelper::flashError('Error: ' . $e->getMessage());
            // Log the error message for debugging purposes
            Log::error('Error in getExtendedProbationDays: ' . $e->getMessage());
        }
    }


    //extend probation methos
    public function extendProbation()
    {
        try {
            if (empty($this->extend_probation)) {
                FlashMessageHelper::flashWarning('Please select Extend Probation Period.');
                return; // If no value is selected, do nothing
            }
            // Check if extend_probation has a value
            if ($this->extend_probation) {
                // Convert the probation period to days from the probation_Period
                $existingProbationDays = $this->probToBeExtend->probation_Period;
                // Convert the extend_probation months into days
                $extendProbationMonths = (int)$this->extend_probation;
                $extendProbationDays = $extendProbationMonths * 30; // Assuming 1 month = 30 days
                // Add the existing probation days to the extended probation days
                $newProbationDays = $existingProbationDays + $extendProbationDays;
                // Update the probation period with the new calculated days
                $this->probToBeExtend->update(
                    [
                        'probation_Period' => $newProbationDays
                    ]
                );
                FlashMessageHelper::flashSuccess('Employee probation extended successfully!.');
                $this->extend_probation = null;
                $this->revisedConfirmationDate = null;
            }
        } catch (\Exception $e) {
            // Handle any exception that occurs
            FlashMessageHelper::flashError('Error: ' . $e->getMessage());
            // Log the error message for debugging purposes
            Log::error('Error in extendProbation: ' . $e->getMessage());
        }
    }

    //confirm Selecetd employee
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
            'formattedConfirmDate' => $this->formattedConfirmDate,
            'probToBeExtend' => $this->probToBeExtend,
            'revisedConfirmationDate' => $this->revisedConfirmationDate,
            'actualConfirmDate' => $this->actualConfirmDate,
            'lastLoginTime' => $this->lastLoginTime
        ]);
    }
}

<?php

namespace App\Livewire;

use App\Helpers\FlashMessageHelper;
use App\Models\EmployeeDetails;
use App\Models\EmpSeparationDetails;
use Illuminate\Support\Facades\Auth;
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
    public $separation_mode;
    public $showEmployeeSearch = false;
    public $showOtherDetails = false;
    public $showOtherDetailsExp = false;
    public $showOtherDetailsRetired = false;

    public $showResignationDetails = false;
    public $showResignSection = false;
    public $seleceted_emp_id;
    public $showEdit = false;
    public $emp_id;
    public $resignation_submitted_on;
    public $reason;
    public $notice_required;
    public $exclude_final_settlement;
    public $notice_period;
    public $short_fall_notice_period;
    public $tentative_date;
    public $remarks;
    public $notes;
    public $exit_interview_date;
    public $leaving_date;
    public $settled_date;
    public $is_left_org;
    public $is_served_notice;
    public $fit_to_rehire;
    public $alt_email_id;
    public $alt_mbl_no;
    public $date_of_demise;
    public $retired_date;
    public $section;
    public $showResignationEdit = false;
    public $showExitDetailsEdit = false;
    public $showExitInterviewEdit = false;
    public $other_date;
    public $separationDetails;
    public $selecetdEmpDetails;

    //get selected employee
    public function getSelectedEmp($empId)
    {
        $this->showResignSection = true;
        $this->showEmployeeSearch = false;
        $this->seleceted_emp_id = $empId;
        $this->getEmpDetailsFor();
    }

    public function getEmpDetailsFor()
    {
        $this->selecetdEmpDetails = EmployeeDetails::where('emp_id', $this->seleceted_emp_id)->first();
    }
    public function getEmpSeparationDetails()
    {
        try {
            // Fetch the record or provide a default object with null values
            $this->separationDetails = EmpSeparationDetails::where('emp_id', $this->seleceted_emp_id)->first() ?? (object) [
                'emp_id' => null,
                'hr_emp_id' => null,
                'separation_mode' => null,
                'other_date' => null,
                'resignation_submitted_on' => null,
                'reason' => null,
                'notice_required' => null,
                'exclude_final_settlement' => null,
                'notice_period' => null,
                'short_fall_notice_period' => null,
                'tentative_date' => null,
                'remarks' => null,
                'notes' => null,
                'exit_interview_date' => null,
                'leaving_date' => null,
                'settled_date' => null,
                'is_left_org' => null,
                'is_served_notice' => null,
                'fit_to_rehire' => null,
                'alt_email_id' => null,
                'alt_mbl_no' => null,
                'date_of_demise' => null,
                'retired_date' => null,
            ];
        } catch (\Exception $e) {
            Log::error('Error fetching separation details: ' . $e->getMessage());
            FlashMessageHelper::flashError('An error occured please try again later.');
        }
    }


    //edit method
    public function toggleEdit()
    {
        $this->showEdit = !$this->showEdit;
        if ($this->showEdit) {
            // Load separation details if not already fetched
            $this->is_left_org = $this->separationDetails->is_left_org ?? null;
            $this->other_date = $this->separationDetails->other_date ?? null;
            $this->remarks = $this->separationDetails->remarks ?? null;
            $this->alt_email_id = $this->separationDetails->alt_email_id ?? null;
            $this->alt_mbl_no = $this->separationDetails->alt_mbl_no ?? null;
            $this->date_of_demise = $this->separationDetails->date_of_demise ?? null;
            $this->retired_date = $this->separationDetails->retired_date ?? null;
            $this->exit_interview_date = $this->separationDetails->exit_interview_date ?? null;
            $this->notes = $this->separationDetails->notes ?? null;
            $this->resignation_submitted_on = $this->separationDetails->resignation_submitted_on ?? null;
            $this->reason = $this->separationDetails->reason ?? null;
            $this->notice_required = $this->separationDetails->notice_required ?? null;
            $this->exclude_final_settlement = $this->separationDetails->exclude_final_settlement ?? null;
            $this->notice_period = $this->separationDetails->notice_period ?? null;
            $this->short_fall_notice_period = $this->separationDetails->short_fall_notice_period ?? null;
            $this->tentative_date = $this->separationDetails->tentative_date ?? null;
            $this->settled_date = $this->separationDetails->settled_date ?? null;
            $this->leaving_date = $this->separationDetails->leaving_date ?? null;
            $this->fit_to_rehire = $this->separationDetails->fit_to_rehire ?? null;
            $this->is_served_notice = $this->separationDetails->is_served_notice ?? null;
        }
    }


    // edit method for resigned deatils
    public function toggleEditResignedDet($section)
    {
        // Reset all sections to false
        $this->showResignationEdit = false;
        $this->showExitInterviewEdit = false;
        $this->showExitDetailsEdit = false;
        // Toggle the section that was clicked
        if ($section == 'resignation') {
            $this->showResignationEdit = !$this->showResignationEdit;
            if ($this->showResignationEdit) {
                // Load Resignation Details data
                $this->resignation_submitted_on = $this->separationDetails->resignation_submitted_on ?? null;
                $this->reason = $this->separationDetails->reason ?? null;
                $this->notice_required = $this->separationDetails->notice_required ?? null;
                $this->notice_period = $this->separationDetails->notice_period ?? null;
                $this->short_fall_notice_period = $this->separationDetails->short_fall_notice_period ?? null;
                $this->tentative_date = $this->separationDetails->tentative_date ?? null;
                $this->exclude_final_settlement = $this->separationDetails->exclude_final_settlement ?? null;
                $this->remarks = $this->separationDetails->remarks ?? null;
            }
        }

        if ($section == 'exit_interview') {
            $this->showExitInterviewEdit = !$this->showExitInterviewEdit;
            if ($this->showExitInterviewEdit) {
                // Load Exit Interview data
                $this->exit_interview_date = $this->separationDetails->exit_interview_date ?? null;
                $this->notes = $this->separationDetails->notes ?? null;
            }
        }

        if ($section == 'exit_details') {
            $this->showExitDetailsEdit = !$this->showExitDetailsEdit;
            if ($this->showExitDetailsEdit) {
                // Load Exit Details data
                $this->is_left_org = $this->separationDetails->is_left_org ?? null;
                $this->leaving_date = $this->separationDetails->leaving_date ?? null;
                $this->settled_date = $this->separationDetails->settled_date ?? null;
                $this->fit_to_rehire = $this->separationDetails->fit_to_rehire ?? null;
                $this->is_served_notice = $this->separationDetails->is_served_notice ?? null;
                $this->alt_email_id = $this->separationDetails->alt_email_id ?? null;
                $this->alt_mbl_no = $this->separationDetails->alt_mbl_no ?? null;
            }
        }
    }

    //filter  dropdown
    public function empfilteredData()
    {
        $this->selectedFilterEmp = $this->filterEmp;
        $this->loadEmployeeList();
    }

    public function closeSearchContainer()
    {
        $this->showEmployeeSearch = !$this->showEmployeeSearch;
        $this->getEmpDetailsFor();
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

            if ($this->filterEmp == 'current_emp') {
                $query->where('status', true);
            } elseif ($this->filterEmp == 'resign_emp') {
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

    //filter resign status
    public function toggleContent()
    {
        switch ($this->separation_mode) {
            case 'others':
                $this->showOtherDetails = true;
                $this->showResignationDetails = false;
                $this->showOtherDetailsExp = false;
                $this->showOtherDetailsRetired = false;
                $this->getEmpSeparationDetails();
                break;
            case 'resigned':
                $this->showResignationDetails = true;
                $this->showOtherDetails = false;
                $this->showOtherDetailsExp = false;
                $this->showOtherDetailsRetired = false;
                $this->getEmpSeparationDetails();
                break;
            case 'expired':
                $this->showOtherDetailsExp = true;
                $this->showResignationDetails = false;
                $this->showOtherDetails = false;
                $this->getEmpSeparationDetails();
                break;
            case 'retired':
                $this->showOtherDetailsRetired = true;
                $this->showOtherDetailsExp = false;
                $this->showResignationDetails = false;
                $this->showOtherDetails = false;
                $this->getEmpSeparationDetails();
                break;
            default:
                $this->showOtherDetails = true;
                $this->showResignationDetails = false;
                $this->showOtherDetailsExp = false;
                $this->showOtherDetailsRetired = false;
                $this->getEmpSeparationDetails();
                break;
        }
    }

    public $showWarningModal = false;
    public $warningMessage;
    public $method;
    //create or update other details section
    public function saveOtherDetails()
    {
        try {
            // Get the logged-in employee's HR ID
            $logginEmpId = Auth::guard('hr')->user()->hr_emp_id;

            // Check if the selected employee's separation mode already exists
            $existingSeparation = EmpSeparationDetails::where('emp_id', $this->seleceted_emp_id)->first();

            // If the separation mode exists and it's different from the entered one
            if ($existingSeparation && $existingSeparation->separation_mode != $this->separation_mode) {
                // Trigger warning modal (can be done by setting a flag for the frontend)
                $this->showWarningModal = true;
                $this->method = 'saveOtherDetails';
                $this->warningMessage = "The separation type will be modified to <span style='font-size: 18px; font-weight: bold;'>{$this->separation_mode}</span>. Do you want to continue?";
                // Don't save until the user confirms
                return;
            }

            // Proceed with saving/updating the record if no issues or if the user confirmed
            EmpSeparationDetails::updateOrCreate(
                ['emp_id' => $this->seleceted_emp_id], // Condition to find the record
                [
                    'separation_mode' => $this->separation_mode,
                    'hr_emp_id' => $logginEmpId,           // Logged-in employee's HR ID
                    'emp_id' => $this->seleceted_emp_id,   // Employee ID being updated
                    'is_left_org' => $this->is_left_org,   // Whether the employee left the organization
                    'other_date' => $this->other_date,     // The other date (possibly related to separation)
                    'alt_mbl_no' => $this->alt_mbl_no,     // Alternative mobile number
                    'remarks' => $this->remarks,           // Additional remarks
                    'alt_email_id' => $this->alt_email_id  // Alternative email ID
                ]
            );

            // Check if EmployeeDetails exists and update `employee_status`
            $employee = EmployeeDetails::where('emp_id', $this->seleceted_emp_id)->first();
            if ($employee) {
                $employee->update([
                    'employee_status' => $this->separation_mode,
                    'status' => false
                ]);
            }

            // Reset details and update UI
            $this->resetDetails();
            $this->showEdit = false;
            $this->getEmpSeparationDetails();
            // Flash a success message after saving the details
            FlashMessageHelper::flashSuccess('Data updated successfully');
        } catch (\Exception $e) {
            // Log the exception (optional)
            Log::error('Error while saving separation details: ' . $e->getMessage());
            // Flash an error message to inform the user
            FlashMessageHelper::flashError('An error occurred while saving the data. Please try again.');
        }
    }

    /// create or update Expire section
    public function saveExpireDetails()
    {
        try {
            // Get the logged-in employee's HR ID
            $logginEmpId = Auth::guard('hr')->user()->hr_emp_id;
            // Check if the selected employee's separation mode already exists
            $existingSeparation = EmpSeparationDetails::where('emp_id', $this->seleceted_emp_id)->first();
            // If the separation mode exists and it's different from the entered one
            if ($existingSeparation && $existingSeparation->separation_mode != $this->separation_mode) {
                // Trigger warning modal (can be done by setting a flag for the frontend)
                $this->showWarningModal = true;
                $this->method = 'saveExpireDetails';
                $this->warningMessage = "The separation type will be modified to <span style='font-size: 18px; font-weight: bold;'>{$this->separation_mode}</span>. Do you want to continue?";
                // Don't save until the user confirms
                return;
            }

            // Create a new EmpSeparationDetails record with the provided data
            EmpSeparationDetails::updateOrCreate(
                ['emp_id' => $this->seleceted_emp_id], // Condition to find the record (e.g., using emp_id)
                [
                    'separation_mode' => $this->separation_mode,
                    'hr_emp_id' => $logginEmpId,
                    'date_of_demise' => $this->date_of_demise,
                    'emp_id' => $this->seleceted_emp_id,
                    'is_left_org' => $this->is_left_org,
                    'other_date' => $this->other_date,
                    'remarks' => $this->remarks,
                    'retired_date' => null
                ]
            );
            // Check if EmployeeDetails exists and update `employee_status`
            $employee = EmployeeDetails::where('emp_id', $this->seleceted_emp_id)->first();
            if ($employee) {
                $employee->update([
                    'employee_status' => $this->separation_mode,
                    'status' => false
                ]);
            }

            $this->resetDetails();
            $this->showEdit = false;
            $this->showWarningModal = false;
            $this->getEmpSeparationDetails();
            // Flash a success message after saving the details
            FlashMessageHelper::flashSuccess('Data updated successfully');
        } catch (\Exception $e) {
            // Log the exception (optional, based on your needs)
            Log::error('Error while saving separation details: ' . $e->getMessage());
            // Flash an error message to inform the user
            FlashMessageHelper::flashError('An error occurred while saving the data. Please try again.');
        }
    }

    //handle retriment section

    public function saveRetireDetails()
    {
        try {
            // Get the logged-in employee's HR ID
            $logginEmpId = Auth::guard('hr')->user()->hr_emp_id;
            // Check if the selected employee's separation mode already exists
            $existingSeparation = EmpSeparationDetails::where('emp_id', $this->seleceted_emp_id)->first();
            // If the separation mode exists and it's different from the entered one
            if ($existingSeparation && $existingSeparation->separation_mode != $this->separation_mode) {
                // Trigger warning modal (can be done by setting a flag for the frontend)
                $this->showWarningModal = true;
                $this->method = 'saveRetireDetails';
                $this->warningMessage = "The separation type will be modified to <span style='font-size: 18px; font-weight: bold;'>{$this->separation_mode}</span>. Do you want to continue?";
                // Don't save until the user confirms
                return;
            }

            // Create a new EmpSeparationDetails record with the provided data
            EmpSeparationDetails::updateOrCreate(
                ['emp_id' => $this->seleceted_emp_id], // Condition to find the record (e.g., using emp_id)
                [
                    'separation_mode' => $this->separation_mode,
                    'hr_emp_id' => $logginEmpId,
                    'retired_date' => $this->retired_date,
                    'date_of_demise' => null,
                    'emp_id' => $this->seleceted_emp_id,
                    'is_left_org' => $this->is_left_org,
                    'other_date' => $this->other_date,
                    'remarks' => $this->remarks,
                ]
            );
            // Check if EmployeeDetails exists and update `employee_status`
            $employee = EmployeeDetails::where('emp_id', $this->seleceted_emp_id)->first();
            if ($employee) {
                $employee->update([
                    'employee_status' => $this->separation_mode,
                    'status' => false
                ]);
            }

            $this->resetDetails();
            $this->showEdit = false;
            $this->showWarningModal = false;
            $this->getEmpSeparationDetails();
            // Flash a success message after saving the details
            FlashMessageHelper::flashSuccess('Data updated successfully');
        } catch (\Exception $e) {
            // Log the exception (optional, based on your needs)
            Log::error('Error while saving separation details: ' . $e->getMessage());
            // Flash an error message to inform the user
            FlashMessageHelper::flashError('An error occurred while saving the data. Please try again.');
        }
    }

    //update separation mode as resign
    public function saveResignDetails()
    {
        try {
            // Get the logged-in employee's HR ID
            $logginEmpId = Auth::guard('hr')->user()->hr_emp_id;
            // Check if the selected employee's separation mode already exists
            $existingSeparation = EmpSeparationDetails::where('emp_id', $this->seleceted_emp_id)->first();
            // If the separation mode exists and it's different from the entered one
            if ($existingSeparation && $existingSeparation->separation_mode != $this->separation_mode) {
                // Trigger warning modal (can be done by setting a flag for the frontend)
                $this->showWarningModal = true;
                $this->method = 'saveResignDetails';
                $this->warningMessage = "The separation type will be modified to <span style='font-size: 18px; font-weight: bold;'>{$this->separation_mode}</span>. Do you want to continue?";
                // Don't save until the user confirms
                return;
            }

            // Create a new EmpSeparationDetails record with the provided data
            EmpSeparationDetails::updateOrCreate(
                ['emp_id' => $this->seleceted_emp_id], // Condition to find the record (e.g., using emp_id)
                [
                    'separation_mode' => $this->separation_mode,
                    'hr_emp_id' => $logginEmpId,
                    'retired_date' => null,
                    'date_of_demise' => null,
                    'emp_id' => $this->seleceted_emp_id,
                    'resignation_submitted_on' => $this->resignation_submitted_on,
                    'reason' => $this->reason,
                    'notice_required' => $this->notice_required,
                    'exclude_final_settlement' => $this->exclude_final_settlement,
                    'notice_period' => $this->notice_period,
                    'short_fall_notice_period' => $this->short_fall_notice_period,
                    'tentative_date' => $this->tentative_date,
                    'remarks' => $this->remarks
                ]
            );
            // Check if EmployeeDetails exists and update `employee_status`
            $employee = EmployeeDetails::where('emp_id', $this->seleceted_emp_id)->first();
            if ($employee) {
                $employee->update([
                    'employee_status' => $this->separation_mode,
                    'status' => false
                ]);
            }
            $this->resetDetails();
            $this->showResignationDetails = false;
            $this->showWarningModal = false;
            $this->getEmpSeparationDetails();
            // Flash a success message after saving the details
            FlashMessageHelper::flashSuccess('Data updated successfully');
        } catch (\Exception $e) {
            // Log the exception (optional, based on your needs)
            Log::error('Error while saving separation details: ' . $e->getMessage());
            // Flash an error message to inform the user
            FlashMessageHelper::flashError('An error occurred while saving the data. Please try again.');
        }
    }


    //save exit interbiew details
    public function saveExitInterviewDetails()
    {
        try {
            // Get the logged-in employee's HR ID
            $logginEmpId = Auth::guard('hr')->user()->hr_emp_id;
            // Check if the selected employee's separation mode already exists
            $existingSeparation = EmpSeparationDetails::where('emp_id', $this->seleceted_emp_id)->first();
            // If the separation mode exists and it's different from the entered one
            if ($existingSeparation && $existingSeparation->separation_mode != $this->separation_mode) {
                // Trigger warning modal (can be done by setting a flag for the frontend)
                $this->showWarningModal = true;
                $this->method = 'saveExitInterviewDetails';
                $this->warningMessage = "The separation type will be modified to <span style='font-size: 18px; font-weight: bold;'>{$this->separation_mode}</span>. Do you want to continue?";
                // Don't save until the user confirms
                return;
            }

            // Create a new EmpSeparationDetails record with the provided data
            EmpSeparationDetails::updateOrCreate(
                ['emp_id' => $this->seleceted_emp_id], // Condition to find the record (e.g., using emp_id)
                [
                    'separation_mode' => $this->separation_mode,
                    'hr_emp_id' => $logginEmpId,
                    'retired_date' => null,
                    'date_of_demise' => null,
                    'emp_id' => $this->seleceted_emp_id,
                    'notes' => $this->notes,
                    'exit_interview_date' => $this->exit_interview_date
                ]
            );

            // Check if EmployeeDetails exists and update `employee_status`
            $employee = EmployeeDetails::where('emp_id', $this->seleceted_emp_id)->first();
            if ($employee) {
                $employee->update([
                    'employee_status' => $this->separation_mode,
                    'status' => false
                ]);
            }
            $this->resetDetails();
            $this->showExitInterviewEdit = false;
            $this->showWarningModal = false;
            $this->getEmpSeparationDetails();
            // Flash a success message after saving the details
            FlashMessageHelper::flashSuccess('Data updated successfully');
        } catch (\Exception $e) {
            // Log the exception (optional, based on your needs)
            Log::error('Error while saving separation details: ' . $e->getMessage());
            // Flash an error message to inform the user
            FlashMessageHelper::flashError('An error occurred while saving the data. Please try again.');
        }
    }

    //save exit interbiew details
    public function saveExitDetails()
    {
        try {
            // Get the logged-in employee's HR ID
            $logginEmpId = Auth::guard('hr')->user()->hr_emp_id;
            // Check if the selected employee's separation mode already exists
            $existingSeparation = EmpSeparationDetails::where('emp_id', $this->seleceted_emp_id)->first();

            // If the separation mode exists and it's different from the entered one
            if ($existingSeparation && $existingSeparation->separation_mode != $this->separation_mode) {
                // Trigger warning modal (can be done by setting a flag for the frontend)
                $this->showWarningModal = true;
                $this->method = 'saveExitDetails';
                $this->warningMessage = "The separation type will be modified to <span style='font-size: 18px; font-weight: bold;'>{$this->separation_mode}</span>. Do you want to continue?";
                // Don't save until the user confirms
                return;
            }

            // Create a new EmpSeparationDetails record with the provided data
            EmpSeparationDetails::updateOrCreate(
                ['emp_id' => $this->seleceted_emp_id], // Condition to find the record (e.g., using emp_id)
                [
                    'separation_mode' => $this->separation_mode,
                    'hr_emp_id' => $logginEmpId,
                    'retired_date' => null,
                    'date_of_demise' => null,
                    'emp_id' => $this->seleceted_emp_id,
                    'settled_date' => $this->settled_date,
                    'is_left_org' => $this->is_left_org,
                    'leaving_date' => $this->leaving_date,
                    'fit_to_rehire' => $this->fit_to_rehire,
                    'is_served_notice' => $this->is_served_notice,
                    'alt_email_id' => $this->alt_email_id,
                    'alt_email_no' => $this->alt_email_no
                ]
            );
            // Check if EmployeeDetails exists and update `employee_status`
            $employee = EmployeeDetails::where('emp_id', $this->seleceted_emp_id)->first();
            if ($employee) {
                $employee->update([
                    'employee_status' => $this->separation_mode,
                    'status' => false
                ]);
            }
            $this->resetDetails();
            $this->showExitDetailsEdit = false;
            $this->showWarningModal = false;
            $this->getEmpSeparationDetails();
            // Flash a success message after saving the details
            FlashMessageHelper::flashSuccess('Data updated successfully');
        } catch (\Exception $e) {
            // Log the exception (optional, based on your needs)
            Log::error('Error while saving separation details: ' . $e->getMessage());
            // Flash an error message to inform the user
            FlashMessageHelper::flashError('An error occurred while saving the data. Please try again.');
        }
    }

    // handle dynamic method calling
    public function handleConfirmation()
    {
        try {

            // Ensure separation_mode is updated before calling the dynamic method
            if ($this->separation_mode) {
                // Check if emp_id exists in EmpSeparationDetails
                $existingSeparation = EmpSeparationDetails::where('emp_id', $this->seleceted_emp_id)->first();
    
                if ($existingSeparation) {
                    $existingSeparation->update([
                        'separation_mode' => $this->separation_mode, // Update the separation mode
                    ]);
                } else {
                    FlashMessageHelper::flashWarning('EmpSeparationDetails not found for emp_id: ' . $this->seleceted_emp_id);
                }
            }


            // Check if EmployeeDetails exists and update `employee_status`
            $employee = EmployeeDetails::where('emp_id', $this->seleceted_emp_id)->first();
            if ($employee) {
                Log::info('Updating EmployeeDetails for emp_id: ' . $this->seleceted_emp_id);
                $employee->update([
                    'employee_status' => $this->separation_mode,
                    'status' => false
                ]);
            } else {
                FlashMessageHelper::flashWarning('EmployeeDetails not found for emp_id: ' . $this->seleceted_emp_id);
            }
    

            // Check if the method name is set and exists
            if (isset($this->method) && method_exists($this, $this->method)) {
                $this->{$this->method}();
            } else {
                Log::error('Invalid method: ' . ($this->method ?? 'NULL'));
                FlashMessageHelper::flashError('Invalid method call.');
            }
    
            FlashMessageHelper::flashSuccess('Separation details updated successfully!');
            $this->showWarningModal = false;

        } catch (\Exception $e) {
            // Log the exception for debugging
            Log::error('Error in handleConfirmation: ' . $e->getMessage());
    
            // Optional: log the stack trace for deeper debugging
            Log::error($e->getTraceAsString());
    
            // Show error message to the user
            FlashMessageHelper::flashError('An error occurred while updating separation details. Please try again.');
        }
    }


    public function cancelWarningModal()
    {
        $this->showWarningModal = false;
        $this->method = null;
    }

    public function resetDetails()
    {
        // Reset all form data properties
        $this->is_left_org = null;
        $this->other_date = null;
        $this->alt_mbl_no = null;
        $this->remarks = null;
        $this->date_of_demise = null;
        $this->alt_email_id = null;
        $this->retired_date = null;
        $this->resignation_submitted_on = null;
        $this->reason = null;
        $this->notice_required = null;
        $this->exclude_final_settlement = null;
        $this->notice_period = null;
        $this->short_fall_notice_period = null;
        $this->tentative_date = null;
        $this->notes = null;
        $this->exit_interview_date = null;
        $this->settled_date = null;
        $this->leaving_date = null;
        $this->fit_to_rehire = null;
        $this->is_served_notice = null;
        $this->showEdit = false;
    }

    //open resingation type section
    public function openResignSec()
    {
        $this->showResignSection = true;
    }

    public function render()
    {
        return view(
            'livewire.employee-separation',
            [
                'employeeIds' => $this->employeeIds,
                'separationDetails' => $this->separationDetails,
                'warningMessage' => $this->warningMessage,
                'method' => $this->method,
                'selecetdEmpDetails' => $this->selecetdEmpDetails,
                'seleceted_emp_id' => $this->seleceted_emp_id
            ]
        );
    }
}

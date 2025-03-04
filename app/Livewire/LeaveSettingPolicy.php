<?php

namespace App\Livewire;

use App\Helpers\FlashMessageHelper;
use App\Models\Company;
use App\Models\EmployeeDetails;
use App\Models\LeavePolicySetting;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class LeaveSettingPolicy extends Component
{
    public $leave_name;
    public $grant_days;
    public $leave_frequency;
    public $is_active = 1;
    public $allowedLeavesAsPerPolicy = [];
    public $leave_code;
    public $company_id;
    public $companyID;
    public $showAddForm = false;
    public $leavePolicy;
    public  $currentYear;
    public $leavePolicies = [];
    protected $rules = [
        'leave_name' => 'required|string|max:50',
        'grant_days' => 'required|integer',
        'leave_frequency' => 'required|in:Annual,Monthly',
        'leave_code' => 'required|string|unique:leave_policy_settings,leave_code',
        'company_id' => 'required|exists:companies,company_id',
    ];

    protected $messages = [
        'leave_name.required' => 'Leave name required',
        'grant_days.required' => 'Grant days required',
        'leave_frequency.required' => 'Leave frequency required',
        'leave_code.required' => 'Leave code required',
    ];

    public function mount()
    {
        try {
            $this->currentYear = Carbon::now()->format('Y');
            $this->leavePolicies = LeavePolicySetting::all()->keyBy('id')->toArray();
            $loggedInEmpID = auth()->guard('hr')->user()->emp_id;

            // Step 2: Find the employee's details
            $employeeDetails = EmployeeDetails::where('emp_id', $loggedInEmpID)->first();

            // Step 3: Decode the company_id if it's in JSON format
            $companyIds = $employeeDetails->company_id;
            if (is_array($companyIds)) {
                // Fetch the first company that matches the criteria
                $company = Company::whereIn('company_id', $companyIds)
                    ->where('is_parent', 'yes')
                    ->first();

                if ($company) {
                    $this->company_id = $company->company_id; // Set the fetched company_id
                }
            }
            $this->getLeaveTypes();
        } catch (\Exception $e) {
            Log::error('Error in mounting LeaveSettingPolicy: ' . $e->getMessage());
            FlashMessageHelper::flashError('An error occurred while loading leave policies.');
        }
    }

    public function getLeaveTypes()
    {
        try {
            $this->allowedLeavesAsPerPolicy = LeavePolicySetting::whereJsonContains('company_id', $this->company_id)->get();
        } catch (\Exception $e) {
            Log::error('Error in getLeaveTypes method: ' . $e->getMessage());
            FlashMessageHelper::flashError('An error occurred while fetching leave types.');
        }
    }

    public function toggleAddForm()
    {
        try {
            $this->showAddForm = !$this->showAddForm; // Toggle the form visibility
        } catch (\Exception $e) {
            Log::error('Error in toggleAddForm method: ' . $e->getMessage());
            FlashMessageHelper::flashError('An error occurred while toggling the add form.');
        }
    }

    public function validateField($field)
    {
        try {
            $this->validateOnly($field);
        } catch (\Exception $e) {
            Log::error('Error in validateField method: ' . $e->getMessage());
            FlashMessageHelper::flashError('An error occurred during field validation.');
        }
    }

    public function updateLeaveCode()
    {
        try {
            // Mapping of leave types to leave codes
            $leaveCodes = [
                'Sick Leave' => 'SL',
                'Casual Leave' => 'CL',
                'Casual Probation Leave' => 'CPL',
                'Work From Home' => 'WFH',
                'Marriage Leave' => 'ML',
                'Paternity Leave' => 'PL',
                'Maternity Leave' => 'MML',
                'Earned Leave' => 'EL',
                'Compensatory Off' => 'COMPOFF', // Compensatory Off
                'Privilege Leave' => 'PLV', // Privilege Leave
            ];

            // Set the leave code based on the selected leave name
            $this->leave_code = $leaveCodes[$this->leave_name] ?? ''; // Set to empty if no match
        } catch (\Exception $e) {
            Log::error('Error in updateLeaveCode method: ' . $e->getMessage());
            FlashMessageHelper::flashError('An error occurred while updating the leave code.');
        }
    }

    public function handleLeaveNameChange()
    {
        try {
            // Validate the leave name field
            $this->validate([
                'leave_name' => 'required',
            ]);

            // After validation, update the leave code
            $this->updateLeaveCode();
        } catch (\Exception $e) {
            Log::error('Error in handleLeaveNameChange method: ' . $e->getMessage());
            FlashMessageHelper::flashError('An error occurred while handling leave name change.');
        }
    }

    public function editPolicy($policyId)
    {
        try {
            $policyData = $this->leavePolicies[$policyId];

            // Update the policy in the database
            $policy = LeavePolicySetting::find($policyId);
            $policy->update([
                'leave_name' => $policyData['leave_name'],
                'leave_code' => $policyData['leave_code'],
                'leave_frequency' => $policyData['leave_frequency'],
                'grant_days' => $policyData['grant_days'],
            ]);

            FlashMessageHelper::flashSuccess('Leave policy updated successfully!');
        } catch (\Exception $e) {
            Log::error('Error in editPolicy method: ' . $e->getMessage());
            FlashMessageHelper::flashError('An error occurred while updating the leave policy.');
        }
    }

    public function deletePolicy($policyId)
    {
        try {
            // Find the policy by ID
            $policy = LeavePolicySetting::find($policyId);

            // Check if policy exists
            if ($policy) {
                // Delete the policy
                $policy->delete();

                FlashMessageHelper::flashSuccess('Policy deleted successfully!');

                // Refresh the policies list
                $this->leavePolicies = LeavePolicySetting::all()->keyBy('id')->toArray();
            } else {
                FlashMessageHelper::flashError('Policy not found!');
            }

            $this->getLeaveTypes();
        } catch (\Exception $e) {
            Log::error('Error in deletePolicy method: ' . $e->getMessage());
            FlashMessageHelper::flashError('An error occurred while deleting the policy.');
        }
    }

    public function valdiateField($field)
    {
        try {
            $this->validateOnly($field);
        } catch (\Exception $e) {
            Log::error('Error in valdiateField method: ' . $e->getMessage());
            FlashMessageHelper::flashError('An error occurred during field validation.');
        }
    }

    public function addNewType()
    {
        $this->validate();
        try {
            // Create the new leave policy after validation
            $check = LeavePolicySetting::create([
                'leave_name' => $this->leave_name,
                'grant_days' => $this->grant_days,
                'leave_frequency' => $this->leave_frequency,
                'is_active' => $this->is_active,
                'leave_code' => $this->leave_code,
                'company_id' => json_encode($this->company_id),
            ]);

            if ($check) {
                $this->getLeaveTypes();
            }

            FlashMessageHelper::flashSuccess('Leave policy added successfully!');

            // Reset fields after submission
            $this->resetFileds();
        } catch (\Exception $e) {
            Log::error('Error in addNewType method: ' . $e->getMessage());
            FlashMessageHelper::flashError('An error occurred while adding the leave policy.');
        }
    }

    public function resetFileds()
    {
        $this->reset(['leave_name', 'grant_days', 'leave_frequency', 'leave_code']);
    }

    public function render()
    {
        return view('livewire.leave-setting-policy', [
            'company_id' => $this->company_id,
            'allowedLeavesAsPerPolicy' => $this->allowedLeavesAsPerPolicy,
            'currentYear' => $this->currentYear
        ]);
    }
}

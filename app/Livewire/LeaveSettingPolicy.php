<?php

namespace App\Livewire;

use App\Helpers\FlashMessageHelper;
use App\Models\Company;
use App\Models\EmployeeDetails;
use App\Models\LeavePolicySetting;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Validate;
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
    }
    public function getLeaveTypes()
    {
        $this->allowedLeavesAsPerPolicy = LeavePolicySetting::where('company_id', $this->company_id)->get();
    }
    public function toggleAddForm()
    {
        $this->showAddForm = !$this->showAddForm; // Toggle the form visibility
    }
    public function editPolicy($policyId)
    {
        $policyData = $this->leavePolicies[$policyId];

        // Update the policy in the database
        $policy = LeavePolicySetting::find($policyId);
        $policy->update([
            'leave_name' => $policyData['leave_name'],
            'leave_code' => $policyData['leave_code'],
            'leave_frequency' => $policyData['leave_frequency'],
            'grant_days' => $policyData['grant_days'],
        ]);

        FlashMessageHelper::flashSuccess('Leave policy updated successfully!.');
    }
    public function deletePolicy($policyId)
    {
        // Find the policy by ID
        $policy = LeavePolicySetting::find($policyId);

        // Check if policy exists
        if ($policy) {
            // Delete the policy
            $policy->delete();

            // Optionally, send a success message
            FlashMessageHelper::flashSuccess('Policy deleted successfully!');

            // Optionally, you can refresh the list or update the array after deletion
            $this->leavePolicies = LeavePolicySetting::all()->keyBy('id')->toArray(); // Refresh policies list
        } else {
            FlashMessageHelper::flashError('Policy not found!');
        }
        $this->getLeaveTypes();
    }
    public function valdiateField($field)
    {
        $this->validateOnly($field);
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

            // Reset fields after submission
            $this->reset(['leave_name', 'grant_days', 'leave_frequency', 'leave_code', 'company_id']);

            // Optionally, add a success message
            FlashMessageHelper::flashSuccess('Leave policy added successfully!');
        } catch (\Exception $e) {
            // Flash an error message if an exception occurs
            FlashMessageHelper::flashError('An error occurred while adding the leave policy.');
        }
    }


    public function render()
    {
        return view('livewire.leave-setting-policy', [
            'company_id' => $this->company_id,
            'allowedLeavesAsPerPolicy' => $this->allowedLeavesAsPerPolicy
        ]);
    }
}

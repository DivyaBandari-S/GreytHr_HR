<?php

namespace App\Livewire;

use App\Helpers\FlashMessageHelper;
use App\Models\Company;
use App\Models\EmployeeDetails;
use App\Models\LeavePolicySetting;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class LeaveSettingPolicy extends Component
{
    public $leave_name;
    public $grant_days;
    public $leave_frequency;
    public $is_active = 1;
    public $leave_code;
    public $company_id;
    public $companyID;
    public $showAddForm = false;
    protected $rules = [
        'leave_name' => 'required|string|max:50',
        'grant_days' => 'required|integer',
        'leave_frequency' => 'nullable|in:Annual,Monthly',
        'leave_code' => 'required|string|unique:leave_policy_settings,leave_code',
        'company_id' => 'required|exists:companies,company_id',
    ];

    public function mount(){
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
    }
    public function toggleAddForm()
    {
        $this->showAddForm = !$this->showAddForm; // Toggle the form visibility
    }
    public function addNewType()
    {
        try {
            $check = LeavePolicySetting::create([
                'leave_name' => $this->leave_name,
                'grant_days' => $this->grant_days,
                'leave_frequency' => $this->leave_frequency,
                'is_active' => $this->is_active,
                'leave_code' => $this->leave_code,
                'company_id' => json_encode($this->company_id),
            ]);

            // Reset fields after submission
            $this->reset(['leave_name', 'grant_days', 'leave_frequency', 'leave_code', 'company_id']);

            // Optionally, add a success message
            FlashMessageHelper::flashSuccess('Leave policy added successfully!');
        } catch (\Exception $e) {
            // flash an error message
            FlashMessageHelper::flashError('An error occurred while adding the leave policy.');
        }
    }

    public function render()
    {
        return view('livewire.leave-setting-policy',[
            'company_id' => $this->company_id
        ]);
    }
}

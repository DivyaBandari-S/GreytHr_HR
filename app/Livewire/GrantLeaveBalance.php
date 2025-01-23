<?php

namespace App\Livewire;

use App\Models\EmployeeDetails;
use App\Models\EmployeeLeaveBalances;
use App\Models\LeavePolicySetting;
use Illuminate\Database\QueryException;
use Livewire\Component;

class GrantLeaveBalance extends Component
{
    public $emp_ids = [];
    public $emp_id;
    public $employeeIds = [];
    public $selectedEmpIds = [];
    public $leave_type;
    public $leave_balance;
    public $from_date;
    public $to_date;
    public $selectedLeavePolicies = [];
    public $leavePolicies= [];
    public $showEmployees = 'false';
    public $selectAllEmployees = false;

    public function mount()
    {
        // Step 1: Retrieve the logged-in user's emp_id
        $loggedInEmpID = auth()->guard('hr')->user()->emp_id;

        // Step 2: Retrieve the company_id associated with the logged-in emp_id
        $companyID = EmployeeDetails::where('emp_id', $loggedInEmpID)
            ->pluck('company_id')
            ->first();
            $companyIdsArray = is_array($companyID) ? $companyID : json_decode($companyID, true);

        // Step 3: Fetch all emp_id values where company_id matches the logged-in user's company_id
        $this->employeeIds = EmployeeDetails:: where(function ($query) use ($companyIdsArray) {
            // Check if any of the company IDs match
            foreach ($companyIdsArray as $companyId) {
                $query->orWhere('company_id', 'like', "%\"$companyId\"%");
            }
        })
        ->whereIn('employee_status',['active','on-probation'])
            ->pluck('emp_id')
            ->toArray();

            $this->leavePolicies = LeavePolicySetting::all();
    }

    public function openEmployeeIds()
    {
        $this->showEmployees = 'true';
    }

    public function closeEmployeeIds()
    {
        $this->showEmployees = 'false';
    }

    public function toggleSelectAllEmployees()
    {
        if ($this->selectAllEmployees) {
            // If "Select All" is checked, set selectedEmpIds to all employee IDs
            $this->selectedEmpIds = $this->employeeIds;
        } else {
            // If "Select All" is unchecked, clear selectedEmpIds
            $this->selectedEmpIds = [];
        }
    }

    public function grantLeavesForEmp()
    {
        foreach ($this->selectedEmpIds as $emp_id) {
            try {
                $leaveBalance = EmployeeLeaveBalances::where('emp_id', $emp_id)->first();
    
                if ($leaveBalance) {
                    // Decode the leave types and balances if they are JSON
                    $leaveTypes = $leaveBalance->leave_type ?? [];
                    $leaveBalances = $leaveBalance->leave_balance ?? [];
    
                    // Add the new leave types and balances for selected leave policies
                    foreach ($this->selectedLeavePolicies as $leavePolicyId) {
                        $leavePolicy = LeavePolicySetting::find($leavePolicyId);

                        if ($leavePolicy && !in_array($leavePolicy->leave_name, $leaveTypes)) {
                            $leaveTypes[] = $leavePolicy->leave_name;  // Store the leave name
                            $leaveBalances[$leavePolicy->leave_name] = $leavePolicy->grant_days;  // Store the grant days
                        }
                    }
    
                    // Update the existing leave balance record
                    $leaveBalance->update([
                        'leave_type' => json_encode($leaveTypes),  // Store leave names as JSON
                        'leave_balance' => json_encode($leaveBalances),  // Store leave balances as JSON
                        'from_date' => $this->from_date,
                        'to_date' => $this->to_date,
                    ]);
                } else {
                    // Create a new leave balance record if none exists
                    $newLeaveTypes = [];
                    $newLeaveBalances = [];
                    $leavePolicyIds = [];
    
                    foreach ($this->selectedLeavePolicies as $leavePolicyId) {
                        $leavePolicy = LeavePolicySetting::find($leavePolicyId);
    
                        if ($leavePolicy) {
                            // Add leave name to leave_type
                            $newLeaveTypes[] = $leavePolicy->leave_name;
    
                            // Add the corresponding grant days to leave_balance
                            $newLeaveBalances[$leavePolicy->leave_name] = $leavePolicy->grant_days;
    
                            // Store the leave_policy_id for foreign key
                            $leavePolicyIds[] = $leavePolicy->id;
                        }
                    }
    
                    // Assuming you want to store a single leave policy ID, you can use the first one or modify as needed
                    $leavePolicyId = !empty($leavePolicyIds) ? $leavePolicyIds[0] : null;
    
                    // Create the new leave balance record
                    $data = EmployeeLeaveBalances::create([
                        'emp_id' => $emp_id,
                        'leave_type' => json_encode($newLeaveTypes),  // Store leave names as JSON
                        'leave_balance' => json_encode($newLeaveBalances),  // Store leave balances as JSON
                        'from_date' => $this->from_date,
                        'to_date' => $this->to_date,
                        'leave_policy_id' => $leavePolicyId,  // Store the leave policy ID as a foreign key
                    ]);
    
                    dd($data);
                }
    
                session()->flash('success', 'Leave balances added successfully.');
    
            } catch (QueryException $e) {
                if ($e->errorInfo[1] == 1062) {
                    session()->flash('error', 'Leaves have already been added for the selected employee(s).');
                } else {
                    session()->flash('error', 'An error occurred while updating leave balances.');
                }
                return; // Exit on error
            }
        }
    
        return redirect()->to('/hr/user/grant-summary');
    }



    public function render()
    {
        return view('livewire.grant-leave-balance');
    }
}

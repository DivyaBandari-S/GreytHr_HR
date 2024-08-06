<?php

namespace App\Livewire;

use App\Models\EmployeeDetails;
use App\Models\EmployeeLeaveBalances;
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
    public $showEmployees = 'false';
    public $selectAllEmployees = false;

    public function mount()
    {
        // Step 1: Retrieve the logged-in user's emp_id
        $loggedInEmpID = auth()->guard('hr')->user()->emp_id;

        // Step 2: Retrieve the company_id associated with the logged-in emp_id
        $companyID = EmployeeDetails::where('emp_id', $loggedInEmpID)
            ->pluck('company_id')
            ->first(); // Assuming company_id is unique for emp_id

        // Step 3: Fetch all emp_id values where company_id matches the logged-in user's company_id
        $this->employeeIds = EmployeeDetails::where('company_id', $companyID)
            ->pluck('emp_id')
            ->toArray();
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
        $this->validate([
            'selectedEmpIds' => 'required|array|min:1',
            'selectedEmpIds.*' => 'exists:employee_details,emp_id',
            'leave_type' => 'required|string',
            'leave_balance' => 'required|integer|min:0',
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
        ]);

        foreach ($this->selectedEmpIds as $emp_id) {
            try {
                $leaveBalance = EmployeeLeaveBalances::where('emp_id', $emp_id)->first();

                if ($leaveBalance) {
                    // Decode JSON data to ensure it's an array
                    $leaveTypes = $leaveBalance->leave_type ?? [];
                    $leaveBalances = $leaveBalance->leave_balance ?? [];
                    $fromDates = $leaveBalance->from_date ?? [];
                    $toDates = $leaveBalance->to_date ?? [];

                    // Ensure leaveTypes is an array
                    if (!is_array($leaveTypes)) {
                        $leaveTypes = [];
                    }

                    // Update leave types and balances
                    if (!in_array($this->leave_type, $leaveTypes)) {
                        $leaveTypes[] = $this->leave_type;
                    }
                    $leaveBalances[$this->leave_type] = $this->leave_balance;
                    $fromDates[] = $this->from_date;
                    $toDates[] = $this->to_date;

                    $leaveBalance->update([
                        'leave_type' => $leaveTypes, // No need to encode manually
                        'leave_balance' => $leaveBalances, // No need to encode manually
                        'from_date' => $fromDates, // No need to encode manually
                        'to_date' => $toDates, // No need to encode manually
                    ]);
                } else {
                    // Create new record
                    EmployeeLeaveBalances::create([
                        'emp_id' => $emp_id,
                        'leave_type' => [$this->leave_type], // Laravel will encode this as JSON
                        'leave_balance' => [$this->leave_type => $this->leave_balance], // Laravel will encode this as JSON
                        'from_date' => [$this->from_date], // Laravel will encode this as JSON
                        'to_date' => [$this->to_date], // Laravel will encode this as JSON
                    ]);
                }

                // Flash success message
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

        return redirect()->to('/addLeaves');
    }
 
    public function render()
    {
        return view('livewire.grant-leave-balance');
    }
}

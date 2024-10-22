<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\EmployeeDetails;
use App\Models\EmployeeLeaveBalances;
use App\Models\LeaveRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Helpers\LeaveHelper;

class EmployeeLeave extends Component
{
    public $search = '';
    public $employeeType = 'active'; // Default to 'active'
    public $employees = [];
    public $showContainer = false;
    public $selectedEmployees = [];
    public $activeTab = 'Overview';

    

    public function mount()
    {
        $this->loadEmployees();
    }
    public function closeFollowers(){
        $this->showContainer = false;
    }

    public function loadEmployees()
    {

        if ($this->employeeType === 'active') {
            $this->employees = EmployeeDetails::whereIn('employee_status', ['active', 'on-probation'])->get();
        } else {
            $this->employees = EmployeeDetails::whereIn('employee_status', ['terminated', 'resigned'])->get();
        }
    }

    public function filterEmployeeType()
    {
        $this->loadEmployees(); // Reload employees when type changes
    }

    public function searchFilter()
    {
        $this->showContainer = true; // Show the container when the search is triggered

        // If search term is empty, reload current employees
        if ($this->search === '') {
            $this->loadEmployees();
            return;
        }

        // Search with the existing term
        $this->employees = EmployeeDetails::where(function ($query) {
            $query->where('first_name', 'like', '%' . $this->search . '%')
                  ->orWhere('last_name', 'like', '%' . $this->search . '%')
                  ->orWhere('emp_id', $this->search);
        })
        ->where(function ($query) {
            if ($this->employeeType === 'active') {
                $query->whereIn('employee_status', ['active', 'on-probation']);
            } else {
                $query->whereIn('employee_status', ['terminated', 'resigned']);
            }
        })
        ->get();
    }
    public function selectEmployee($employeeId)
    {
        // Check if the employee is already selected
        if (in_array($employeeId, $this->selectedEmployees)) {
            // Deselect employee
            $this->selectedEmployees = array_diff($this->selectedEmployees, [$employeeId]);
        } else {
            // Select employee
            $this->selectedEmployees[] = $employeeId;
        }
        Log::info('Selected Employees: ', $this->selectedEmployees);
    }
//     public function showLeaveBalances($employeeId)
// {

//     $selectedEmployee = EmployeeDetails::find($employeeId);
    
//     // Ensure employee exists
//     if (!$selectedEmployee) {
//         return redirect()->back()->with('error', 'Employee not found.');
//     }

//     // Fetch leave balances for the employee
//     $leaveBalances = EmployeeLeaveBalances::where('emp_id', $employeeId)->first();
    
//     // Fetch leave requests if needed
//     $leaveRequests = LeaveRequest::where('emp_id', $employeeId)->get();

//     // Pass the data to the view
//     return view('livewire.employee-leave', [
//         'selectedEmployee' => $selectedEmployee,
//         'leaveBalances' => $leaveBalances,
//         'leaveRequests' => $leaveRequests,
//     ]);
// }

public static function getLeaveBalances($employeeId, $selectedYear)
    {
        try {
         
          
            // $selectedYear = now()->year;
            $employeeDetails = EmployeeDetails::where('emp_id', $employeeId)->first();
            $sickLeavePerYear = EmployeeLeaveBalances::getLeaveBalancePerYear($employeeId, 'Sick Leave', $selectedYear);
            $casualLeavePerYear = EmployeeLeaveBalances::getLeaveBalancePerYear($employeeId, 'Casual Leave', $selectedYear);
            $casualProbationLeavePerYear = EmployeeLeaveBalances::getLeaveBalancePerYear($employeeId, 'Casual Leave Probation', $selectedYear);
            $lossOfPayPerYear = EmployeeLeaveBalances::getLeaveBalancePerYear($employeeId, 'Loss Of Pay', $selectedYear);

            if (!$employeeDetails) {
                return null;
            }

            // Get the logged-in employee's approved leave days for all leave types
            $approvedLeaveDays = LeaveHelper::getApprovedLeaveDays($employeeId, $selectedYear);

            // Calculate leave balances
            $sickLeaveBalance = $sickLeavePerYear - $approvedLeaveDays['totalSickDays'];
            $casualLeaveBalance = $casualLeavePerYear - $approvedLeaveDays['totalCasualDays'];
            $lossOfPayBalance = $approvedLeaveDays['totalLossOfPayDays'];
            $casualProbationLeaveBalance = $casualProbationLeavePerYear - $approvedLeaveDays['totalCasualLeaveProbationDays'];

            return [
                'sickLeaveBalance' => $sickLeaveBalance,
                'casualLeaveBalance' => $casualLeaveBalance,
                'lossOfPayBalance' => $lossOfPayBalance,
                'casualProbationLeaveBalance' => $casualProbationLeaveBalance,
                'sickLeavePerYear' => $sickLeavePerYear,
                'casualLeavePerYear' => $casualLeavePerYear,
                'casualProbationLeavePerYear' => $casualProbationLeavePerYear,
                'lossOfPayPerYear' => $lossOfPayPerYear,
            ];
        
        } catch (\Exception $e) {
            if ($e instanceof \Illuminate\Database\QueryException) {
                // Handle database query exceptions
                Log::error("Database error in getLeaveBalances(): " . $e->getMessage());
                session()->flash('emp_error', 'Database connection error occurred. Please try again later.');
            } else {
                Log::error("Error in getLeaveBalances(): " . $e->getMessage());
                session()->flash('emp_error', 'Failed to retrieve leave balances. Please try again later.');
            }
            return null;
        }
    }

    public function render()
    {
        $leaveData = [];
        $today = Carbon::now()->year;
        foreach ($this->selectedEmployees as $empId) {
            $leaveRequests = LeaveRequest::where('emp_id', $empId)
            ->where('status', 'approved')
            ->get();
            // $leaveBalances = EmployeeLeaveBalances::where('emp_id', $empId)->get();
            $leaveBalances = EmployeeLeave::getLeaveBalances($empId, $today);
            // $balances = [];
            // foreach ($leaveRequests as $request) {
            //     $balances[$request->leave_type] = EmployeeLeaveBalances::getLeaveBalancePerYear($empId, $request->leave_type, date('Y'));
            // }
    
            // Structure your leave data here
            $leaveData[$empId] = [
                'leaveRequests' => $leaveRequests,
                'leaveBalances' => $leaveBalances,
            ];
        }
        $selectedEmployeesDetails = EmployeeDetails::whereIn('emp_id', $this->selectedEmployees)->get();
        
        return view('livewire.employee-leave', [
            'selectedEmployeesDetails' => $selectedEmployeesDetails,
            'leaveData' => $leaveData,
        ]);
    }
}

<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\EmployeeDetails;
use App\Models\EmployeeLeaveBalances;
use App\Models\LeaveRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Helpers\LeaveHelper;
use Illuminate\Support\Collection;

class EmployeeLeave extends Component
{
    public $search = '';
    public $employeeType = 'active'; // Default to 'active'
    public $employees = [];
    public $showContainer = false;
    public $selectedEmployee = null; 
    public $activeTab = 'Overview';
    public $showSearch = true;

    

    public function mount()
    {
        $this->employees = new Collection();
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
        if ($this->search !== '') {
            $this->showContainer = true; // Show the container when the search is triggered
    
            // Search with the existing term
            $this->employees = EmployeeDetails::where(function ($query) {
                $query->where('first_name', 'like', '%' . $this->search . '%')
                      ->orWhere('last_name', 'like', '%' . $this->search . '%')
                      ->orWhere('emp_id', 'like', '%' . $this->search . '%'); // Ensure `like` for partial match
            })
            ->where(function ($query) {
                if ($this->employeeType === 'active') {
                    $query->whereIn('employee_status', ['active', 'on-probation']);
                } else {
                    $query->whereIn('employee_status', ['terminated', 'resigned']);
                }
            })
            ->get();
    
            // If no results found, the container should still be shown to display the message
            if ($this->employees->isEmpty()) {
                // You can decide if you want to show "No employees found." here or in the Blade.
            }
        } else {
            // If search term is empty, hide the container and reload the employees
            $this->showContainer = false; // Hide the container
            $this->loadEmployees(); // Reload current employees
        }
    }
    
    public function selectEmployee($employeeId)
    {
        // Check if the employee is already selected
        $this->selectedEmployee = $employeeId; // Change here
        Log::info('Selected Employee: ', [$this->selectedEmployee]);
        if (is_null($employeeId)) {
            $this->showSearch = true;
            $this->search = '';
            // $this->showContainer = true;
            // $this->showSearch = false;
            $this->selectedEmployee = null;
        } else {
            $this->showSearch = false;
            $this->showContainer = false;
        }
    }
    // public function closeEmployee(){
    //     $this->showSearch = true;
    // }
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
        if ($this->selectedEmployee) {
            $leaveRequests = LeaveRequest::where('emp_id', $this->selectedEmployee)
                ->where('leave_status', 2)
                ->get();
            $leaveBalances = EmployeeLeave::getLeaveBalances($this->selectedEmployee, $today);
            
            // Structure leave data
            $leaveData[$this->selectedEmployee] = [
                'leaveRequests' => $leaveRequests,
                'leaveBalances' => $leaveBalances,
            ];
        }
        $selectedEmployeesDetails = $this->selectedEmployee ? 
        EmployeeDetails::where('emp_id', $this->selectedEmployee)->get() : [];

    return view('livewire.employee-leave', [
        'selectedEmployeesDetails' => $selectedEmployeesDetails,
        'leaveData' => $leaveData,
    ]);
    }
}

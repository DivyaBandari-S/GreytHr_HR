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
use App\Exports\LeaveRequestsExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class EmployeeLeave extends Component
{
    public $search = '';
    public $employeeType = 'active'; // Default to 'active'
    public $employees ;
    public $showContainer = false;
    public $selectedEmployee = null; 
    public $activeTab = 'Overview';
    public $showSearch = true;
    public $showHelp = false;
    public $activeButton = 'Main';
    public $filterPeriodValue;
    public $leaveRequests = [];  
    public $monthlyCounts = [];
    public $showExportModal = false;
public $fromDate;
public $toDate;
public $selectedLeaveType;
public $selectedTransactionType;
public $orderBy = 'date';
public $exportFormat = 'pdf';
public function updateFromDate()
{
    $this->resetErrorBag('fromDate');
}

public function updateToDate()
{
    $this->resetErrorBag('toDate');
}
public function updateSelectedLeaveType()
{
    $this->resetErrorBag('selectedLeaveType');
}

public function updateSelectedTransactionType()
{
    $this->resetErrorBag('selectedTransactionType');
}
private function cleanString($string)
{
    return mb_convert_encoding($string, 'UTF-8', 'UTF-8');
}



public function generateReport()
{
    // $employeeId = auth()->guard('hr')->user()->emp_id;

    // Fetch employee details
    
    $this->validate([
        'fromDate' => 'required|date',
        'toDate' => 'required|date|after_or_equal:fromDate',
        'selectedLeaveType' => 'required|string',
        'selectedTransactionType' => 'required|string',
        'orderBy' => 'required|string',
        'exportFormat' => 'required|string',
    ], [
        'fromDate.required' => 'The start date is required.',
        'fromDate.date' => 'The start date must be a valid date.',
        'toDate.required' => 'The end date is required.',
        'toDate.date' => 'The end date must be a valid date.',
        'toDate.after_or_equal' => 'The end date must be after or equal to the start date.',
        'selectedLeaveType.required' => 'Please select a leave type.',
        'selectedTransactionType.required' => 'Please select a transaction type.',
        'orderBy.required' => 'Please select an order option.',
        'exportFormat.required' => 'Please select an export format.',
    ]);

    $leaveRequests = LeaveRequest::where('emp_id', $this->selectedEmployee)
    ->whereBetween('created_at', [$this->fromDate, $this->toDate])
    ->when($this->selectedLeaveType, function ($query) {
        return $query->where('leave_type', $this->selectedLeaveType);
    })
    ->orderBy($this->orderBy === 'date' ? 'created_at' : $this->orderBy)
    ->get();
    // dd($leaveRequests);
    
        // Access emp_id of the first leave request
        // $employeeId = $leaveRequests->first()->emp_id; // Or you can use $leaveRequests[0]->emp_id
        // Now you can use $employeeId as needed

    // $employeeId= $leaveRequests->emp_id;
    $employeeDetails = EmployeeDetails::where('emp_id', $this->selectedEmployee)->first();
    // ->map(function ($leave) {
    //     return [
    //         'id' => $leave->id,
    //         'emp_id' => $this->cleanString($leave->emp_id),
    //         'leave_type' => $this->cleanString($leave->leave_type),
    //         'created_at' => $this->cleanString($leave->created_at),
    //         // Include other fields you need...
    //     ];
    // });
    // dd($leaveRequests);


if ($this->exportFormat === 'pdf') {

    try {
        $pdf = PDF::loadView('reports.leave_reports', [
            'employeeDetails' => $employeeDetails,
            'leaveTransactions' => $leaveRequests,
            'fromDate' => $this->fromDate,
            'toDate' => $this->toDate,]);
        // return $pdf->download('leave_requests.pdf');
        return response()->streamDownload(function() use($pdf) {
            echo $pdf->stream();
        }, 'leave_requests.pdf');
    } catch (\Exception $e) {
        Log::error('PDF generation error: ' . $e->getMessage());
        Log::error('Data being processed: ', $leaveRequests->toArray());
        return response()->json(['error' => 'Failed to generate PDF.'], 500);
    }
    
} elseif ($this->exportFormat === 'excel') {
    return Excel::download(new LeaveRequestsExport($leaveRequests), 'leave_requests.xlsx');
}
    $this->resetErrorBag();

    // Close the modal after generating the report
    $this->showExportModal = false;
}



    public function filterPeriodChanged()
    {
        $this->render();
    }

    public function mount()
    {
        $this->filterPeriodValue = 'this_year';
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
    public function hideHelp()
    {

        $this->showHelp = true;
    }
    public function showhelp()
    {
        $this->showHelp = false;
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
    // public function showCasualLeave()
    // {
    //     Log::info('showCasualLeave method called.');
    //     $this->activeTab= "CL";
        
    //     try {
    //         $today = Carbon::now()->year;
    //         $yearToFetch = $this->filterPeriodValue === 'current_year' ? $today - 1 : $today;
        
    //         if (!$this->selectedEmployee) {
    //             Log::warning('No employee selected.');
    //             return;
    //         }
    
    //         $this->leaveRequests = LeaveRequest::where('emp_id', $this->selectedEmployee)
    //             ->where('leave_status', 2)
    //             ->whereYear('created_at', $yearToFetch)
    //             ->where('leave_type', 'Casual Leave')
    //             ->get();
            
    //         $this->monthlyCounts = array_fill(1, 12, 0); // Initialize array for 12 months
    
    //         foreach ($this->leaveRequests as $request) {
    //             $month = $request->created_at->format('n'); // Get the month (1-12)
    //             $this->monthlyCounts[$month]++;
    //         }
           
    
         
    //         $this->dispatchBrowserEvent('update-chart', ['monthlyCounts' => $this->monthlyCounts]);
    //         Log::info('Monthly Counts: ', $this->monthlyCounts);
        
    
    //     } catch (\Exception $e) {
    //         Log::error('Error in showCasualLeave: ' . $e->getMessage() . ' Stack trace: ' . $e->getTraceAsString());

    //         // You might also want to return a default value or error message
    //         $this->monthlyCounts = array_fill(1, 12, 0); // Reset or handle the error accordingly
    //     }
    // }
    // public function showLossOfPay()
    // {
    //     Log::info('showCasualLeave method called.');
    //  $this->activeTab= "LOP";
        
    //     try {
    //         $today = Carbon::now()->year;
    //         $yearToFetch = $this->filterPeriodValue === 'current_year' ? $today - 1 : $today;
        
    //         if (!$this->selectedEmployee) {
    //             Log::warning('No employee selected.');
    //             return;
    //         }
    
    //         $this->leaveRequests = LeaveRequest::where('emp_id', $this->selectedEmployee)
    //             ->where('leave_status', 2)
    //             ->whereYear('created_at', $yearToFetch)
    //             ->where('leave_type', 'Loss Of Pay')
    //             ->get();
            
    //         $this->monthlyCounts = array_fill(1, 12, 0); // Initialize array for 12 months
    
    //         foreach ($this->leaveRequests as $request) {
    //             $month = $request->created_at->format('n'); // Get the month (1-12)
    //             $this->monthlyCounts[$month]++;
    //         }
           
    
         
    //         $this->dispatchBrowserEvent('update-chart', ['monthlyCounts' => $this->monthlyCounts]);
    //         Log::info('Monthly Counts: ', $this->monthlyCounts);
        
    
    //     } catch (\Exception $e) {
    //         Log::error('Error in showCasualLeave: ' . $e->getMessage() . ' Stack trace: ' . $e->getTraceAsString());

    //         // You might also want to return a default value or error message
    //         $this->monthlyCounts = array_fill(1, 12, 0); // Reset or handle the error accordingly
    //     }
    // }
    // public function showSickLeave()
    // {
    //     $this->activeTab= "SL";
        
    //     try {
    //         $today = Carbon::now()->year;
    //         $yearToFetch = $this->filterPeriodValue === 'current_year' ? $today - 1 : $today;
        
    //         if (!$this->selectedEmployee) {
    //             Log::warning('No employee selected.');
    //             return;
    //         }
    
    //         $this->leaveRequests = LeaveRequest::where('emp_id', $this->selectedEmployee)
    //             ->where('leave_status', 2)
    //             ->whereYear('created_at', $yearToFetch)
    //             ->where('leave_type', 'Sick Leave')
    //             ->get();
            
    //         $this->monthlyCounts = array_fill(1, 12, 0); // Initialize array for 12 months
    
    //         foreach ($this->leaveRequests as $request) {
    //             $month = $request->created_at->format('n'); // Get the month (1-12)
    //             $this->monthlyCounts[$month]++;
    //         }
           
    
         
    //         $this->dispatchBrowserEvent('update-chart', ['monthlyCounts' => $this->monthlyCounts]);
    //         Log::info('Monthly Counts: ', $this->monthlyCounts);
        
    
    //     } catch (\Exception $e) {
    //         Log::error('Error in showCasualLeave: ' . $e->getMessage() . ' Stack trace: ' . $e->getTraceAsString());

    //         // You might also want to return a default value or error message
    //         $this->monthlyCounts = array_fill(1, 12, 0); // Reset or handle the error accordingly
    //     }
    // }
    public function showLeaveType($leaveType)
{
  
    // Set the active tab based on the leave type
    $this->activeTab = $leaveType; // e.g., 'CL' for 'Casual Leave'

    Log::info("showLeaveType method called for: {$leaveType}");

    try {
        $today = Carbon::now()->year;
        $yearToFetch = $this->filterPeriodValue === 'current_year' ? $today - 1 : $today;

        if (!$this->selectedEmployee) {
            Log::warning('No employee selected.');
            return;
        }

        // Fetch leave requests based on the leave type
        $this->leaveRequests = LeaveRequest::where('emp_id', $this->selectedEmployee)
            ->where('leave_status', 2)
            ->whereYear('created_at', $yearToFetch)
            ->where('leave_type', $leaveType)
            ->get();

        // Initialize monthly counts
        $this->monthlyCounts = array_fill(1, 12, 0);

        // Count the leave requests per month
        foreach ($this->leaveRequests as $request) {
            $month = $request->created_at->format('n'); // Get the month (1-12)
            $this->monthlyCounts[$month]++;
        }

        // Dispatch the updated monthly counts for the chart
        $this->dispatchBrowserEvent('update-chart', ['monthlyCounts' => $this->monthlyCounts]);
        Log::info('Monthly Counts: ', $this->monthlyCounts);

    } catch (\Exception $e) {
        Log::error('Error in showLeaveType: ' . $e->getMessage() . ' Stack trace: ' . $e->getTraceAsString());

        // Reset or handle the error accordingly
        $this->monthlyCounts = array_fill(1, 12, 0);
    }
}

    
    
    
    // public function selectLeaveType($leaveType)
    // {
    //     $this->activeTab = $leaveType;
    // }

    public function render()
    {
        $leaveData = [];
        $today = Carbon::now()->year;
        $yearToFetch = $this->filterPeriodValue === 'current_year' ? $today - 1 : $today;
        if ($this->selectedEmployee) {
            $leaveRequests = LeaveRequest::where('emp_id', $this->selectedEmployee)
                ->where('leave_status', 2)
                ->whereYear('created_at', $yearToFetch)
                ->get();

            $leaveBalances = EmployeeLeave::getLeaveBalances($this->selectedEmployee,  $yearToFetch);
            
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
        'leaveRequests' => $this->leaveRequests,
    ]);
    
    }
}

<?php

namespace App\Livewire;

use App\Models\EmployeeDetails;
use App\Models\Hr;
use App\Models\RegularisationDates;
use App\Models\SwipeRecord;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Spatie\SimpleExcel\SimpleExcelWriter;

class HrAttendanceOverviewNew extends Component
{
    public $totalemployees;
    
    public $openApprovePopupModal=false;

    public $openRejectPopupModal=false;
    public $openAccordionForActive=null;
    public $absentemployeescount;

    public $lateemployeescount;

    public $mobileEmployeeCount;

    public $laptopEmployeeCount;
    public  $earlyemployeescount;
    public $showHelp=false;
    public $totalemployeesinexcel;

    public $regularisations;

    public $whoisin=[
       [ 'Label'=>'Not Yet In','Value'=>48],
       [ 'Label'=>'Late In','Value'=>45],
       [ 'Label'=>'On Time','Value'=>47]
    ];
    public function mount()
    {
        $currentDate = now()->toDateString();
        $currentDate = Carbon::today();
        $today = $currentDate->toDateString(); // Get today's date in 'Y-m-d' format
        $month = $currentDate->format('n');
        $year = $currentDate->format('Y');
 
        // Construct the table name for SQL Server
      
    }
    public function hideHelp()
    {
   
         $this->showHelp=true;
    }

    public function showhelp()
    {
        $this->showHelp=false;

    }
    public function downloadexcelForNotAssigned()
    {
        $userId = Auth::id();
       
        $companyName = Hr::join('companies', 'hr.company_id', '=', 'companies.company_id')
    ->where('hr.hr_emp_id', $userId)
    ->value('companies.company_name');
         
      
        $this->totalemployeesinexcel = EmployeeDetails::select('emp_id', 'first_name', 'last_name')->get();
        $data[]=[$companyName];
        $data[] = ['Not Assigned Employees'];
        $data[] = ['Employee ID', 'First Name', 'Last Name'];
        foreach ($this->totalemployeesinexcel as $employee) {
            $data[] = [$employee->emp_id, $employee->first_name, $employee->last_name];
        }
    
        $filePath = storage_path('app/employees.xlsx');
    
        SimpleExcelWriter::create($filePath)->addRows($data);
    
        return response()->download($filePath, 'notassignedemployees.xlsx');
    }
    public function downloadexcelForAttendanceType()
    {
        $userId = Auth::id();
       
        $companyName = Hr::join('companies', 'hr.company_id', '=', 'companies.company_id')
        ->where('hr.hr_emp_id', $userId)
        ->value('companies.company_name');
        $employees = EmployeeDetails::join('swipe_records', 'employee_details.emp_id', '=', 'swipe_records.emp_id')
        ->select('employee_details.emp_id', 'employee_details.first_name', 'employee_details.last_name', 'employee_details.job_title','employee_details.job_location','swipe_records.sign_in_device', 'swipe_records.in_or_out', 'swipe_records.swipe_time')
        ->where('swipe_records.in_or_out', 'IN')
        ->whereDate('swipe_records.created_at', now()->toDateString()) // Filter for today's date
        ->orderBy('employee_details.first_name')
        ->get();
    
    // Now $employees contains the first swipe record for each employee where in_or_out is 'IN'
    
        $data[]=[$companyName];
        $data[] = ['Employees By Attendance Type'];
        $data[] = ['Employee No', 'Employee Name', 'Sign Date & Time','Designation','Location','Attendance Type'];
        foreach ($employees as $employee) {
            $deviceLabel = ($employee->sign_in_device === 'mobile') ? 'Mobile Sign In' : 'Web Sign In';
            $data[] = [$employee->emp_id, $employee->first_name.' '.$employee->last_name,$employee->swipe_time,$employee->job_title,$employee->job_location,$deviceLabel];
        }
        $filePath = storage_path('app/employees.xlsx');
    
        SimpleExcelWriter::create($filePath)->addRows($data);
    
        return response()->download($filePath, 'employeesattendanceType.xlsx');
    }
    public function toggleActiveAccordion($id)
    {
        
        if ($this->openAccordionForActive === $id) {
            $this->openAccordionForActive = null; // Close if already open
        } else {
            $this->openAccordionForActive = $id; // Set to open
        }
    }
  
    public function render()
    {
       
        // $companyName = $yourModels->com->company_name;
        $hremployeeId = auth()->guard('hr')->user()->hr_emp_id;
        $employeeId=Hr::where('hr_emp_id',$hremployeeId)->value('emp_id');
        $companyIdJson = EmployeeDetails::where('emp_id', $employeeId)->value('company_id');
        if (is_array($companyIdJson)) {
            $companyIds = $companyIdJson; // It's already an array
        } elseif (is_string($companyIdJson)) {
            $companyIds = json_decode($companyIdJson, true); // Decode the JSON string
        } else {
            $companyIds = []; // Default to an empty array if it's neither
        }
        $empIds = EmployeeDetails::where(function($query) use ($companyIds) {
            foreach ($companyIds as $companyId) {
                // Use JSON_CONTAINS to check if company_id field contains the companyId
                $query->orWhereRaw('JSON_CONTAINS(company_id, \'' . json_encode($companyId) . '\')');
            }
        })->pluck('emp_id');
       
        $this->regularisations = RegularisationDates::where('is_withdraw', 0) // Only records with is_withdraw set to 0
        ->where('status', 'pending')
        ->whereIn('emp_id', $empIds) // Filter by emp_id
        ->selectRaw('*, JSON_LENGTH(regularisation_entries) AS regularisation_entries_count')
        ->whereRaw('JSON_LENGTH(regularisation_entries) > 0')
        ->whereRaw('DATEDIFF(CURRENT_DATE, updated_at) > 3')  // Check if the difference from today is greater than 3 days
        ->with('employee')
        ->get();
        
        $companyId =auth()->guard('hr')->user()->emp_id;
        $employeeCompanyId=EmployeeDetails::where('emp_id',$companyId)->value('company_id');
       
        $currentDate1=now()->toDateString();

        $this->absentemployeescount = EmployeeDetails::select('emp_id', 'first_name', 'last_name')
        ->where('company_id', '=', $employeeCompanyId) // Replace $yourCompanyId with the actual company_id you are filtering
        ->whereNotIn('emp_id', function ($query) use ($currentDate1) {
            $query->select('emp_id')
                ->from('swipe_records')
                ->whereDate('created_at', $currentDate1);
        })
        ->count();
        
        $this->lateemployeescount = EmployeeDetails::where('company_id', '=', $employeeCompanyId) // Replace $yourCompanyId with the actual company_id you are filtering
        ->whereExists(function ($query) use ($currentDate1) {
            $query->select('emp_id')
                ->from('swipe_records')
                ->whereRaw('DATE(created_at) = ?', [$currentDate1])
                ->whereRaw('TIME(created_at) > ?', ['10:00:00'])
                ->whereRaw('swipe_records.emp_id = employee_details.emp_id')
                ->where('in_or_out', 'IN'); // Add condition for in_or_out
        })
        ->count();
        $this->earlyemployeescount = EmployeeDetails::whereExists(function ($query) use ($currentDate1) {
            $query->select('emp_id')
                ->from('swipe_records')
                ->whereRaw('DATE(created_at) = ?', [$currentDate1])
                ->whereRaw('TIME(created_at) < ?', ['10:00:00']) // Adjust the condition to be less than
                ->whereRaw('swipe_records.emp_id = employee_details.emp_id')
                ->where('in_or_out', 'IN');
        })
        ->select('emp_id', 'first_name', 'last_name')
        ->addSelect(['swipe_time' => SwipeRecord::select('swipe_time')
            ->whereColumn('swipe_records.emp_id', 'employee_details.emp_id')
            ->whereRaw('DATE(swipe_records.created_at) = ?', [$currentDate1])
            ->whereRaw('TIME(swipe_records.created_at) < ?', ['10:00:00']) // Adjust the condition to be less than
            ->where('in_or_out', 'IN')
            ->limit(1)
        ])
        ->count();
        
        $this->totalemployees = EmployeeDetails::select('emp_id', 'first_name', 'last_name')->orderBy('first_name')->get();
        foreach($this->totalemployees as $employee)
        {
            $normalizedEmployeeId = str_replace('-', '', $employee->emp_id);
            
        }
        $employees = EmployeeDetails::join('swipe_records', 'employee_details.emp_id', '=', 'swipe_records.emp_id')
    ->select('employee_details.emp_id', 'employee_details.first_name', 'employee_details.last_name',  'swipe_records.in_or_out', 'swipe_records.swipe_time')
    ->where('swipe_records.in_or_out', 'IN')
    ->whereDate('swipe_records.created_at', now()->toDateString()) // Filter for today's date
    ->orderBy('employee_details.first_name')
    ->get();
   

$this->mobileEmployeeCount = 0;
$this->laptopEmployeeCount = 0;

foreach ($employees as $employee) {
    if ($employee->sign_in_device === 'mobile') {
        $this->mobileEmployeeCount++;
    } elseif ($employee->sign_in_device === 'laptop') {
        $this->laptopEmployeeCount++;
    }
}
        return view('livewire.hr-attendance-overview-new',['Employees'=>$employees]);
    }
}

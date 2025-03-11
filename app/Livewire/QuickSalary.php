<?php

namespace App\Livewire;

use App\Exports\QuickSalaryExport;
use App\Models\EmpBankDetail;
use App\Models\EmployeeDetails;
use App\Models\EmpPersonalInfo;
use App\Models\EmpSalary;
use App\Models\EmpSalaryRevision;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class QuickSalary extends Component
{
   public $employees;
   public $employeeIds;
   public $searchTerm;
   public $selectedMonth, $salaryMonth;
   public $emp_id;
   public $salary;
public $salaryData=[];
public $totals=[];
public $pftotals = [];

   public $options;
 
   public $allEmployees;
   public $salaryRevision;

   public $empSalaryDetails;
   public $allSalaryDetails=[];
   public $empId;
   public $salaryDivisions;
   public $empBankDetails ;
   public $hire_date;
   public $employeeDetails;
   public $employeePersonalDetails;

    public $showDetails=true;
    public function toggleDetails()
    {
        $this->showDetails = !$this->showDetails;
    }
    
    public function mount()
    {
        if (auth()->guard('hr')->check()) {
            $loggedInEmpID = auth()->guard('hr')->user()->emp_id;
        } else {
            return;
        }

        $this->empId = $loggedInEmpID;

        // Fetch company ID of logged-in user
        $employeeDetails = EmployeeDetails::where('emp_id', $loggedInEmpID)->first();

        if (!$employeeDetails) {
            return;
        }

        $companyID = $employeeDetails->company_id;
        if (!$companyID) {
            $this->employeeIds = [];
            return;
        }

        // Fetch all employee IDs under the same company
        $this->employeeIds = EmployeeDetails::where('company_id', $companyID)->pluck('emp_id')->toArray();

        $this->options = [];
        $this->selectedMonth = $this->selectedMonth ?? date('Y-m');
        $this->generateMonths();

        // Fetch Employees under the same company
        $this->employees = EmployeeDetails::where('company_id', $companyID)
            ->where(function ($query) {
                $query->where('first_name', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('last_name', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('emp_id', 'like', '%' . $this->searchTerm . '%');
            })
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();
    }
    public function getSalaryDetails()
    {
        // Fetch salary details for all employees for the selected month
        $this->salaryRevision = EmpSalary::join('salary_revisions', 'emp_salaries.sal_id', '=', 'salary_revisions.id')
            ->join('employee_details', 'salary_revisions.emp_id', '=', 'employee_details.emp_id')
            ->where('emp_salaries.month_of_sal', 'like', $this->selectedMonth . '%')
            ->where('emp_salaries.is_payslip', 1)
            ->select(
                'emp_salaries.*', 
                'salary_revisions.emp_id', 
                'employee_details.first_name', 
                'employee_details.last_name', 
                'employee_details.hire_date'
            )
            ->get();
    
        // If no data is found, assign an empty collection
        if ($this->salaryRevision->isEmpty()) {
            $this->salaryRevision = collect([]);
        }
    
        $this->salaryData = [];
    
        // Initialize salary totals
        $this->totals = [
            'basic' => 0, 'hra' => 0, 'conveyance' => 0, 'medical_allowance' => 0, 
            'special_allowance' => 0, 'earnings' => 0, 'gross' => 0, 'pf' => 0, 
            'esi' => 0, 'professional_tax' => 0, 'total_deductions' => 0, 'net_pay' => 0, 'working_days' => 0,
        ];
    

    
        // Process salary data
        foreach ($this->salaryRevision as $salary) {
            $empId = $salary->emp_id;
    
            if ($empId) {
                // Calculate salary components if the method exists
                $salaryComponents = method_exists($salary, 'calculateSalaryComponents') 
                    ? $salary->calculateSalaryComponents($salary->salary) 
                    : $this->zeroSalaryDetails();
    
                // Calculate PF components if the method exists
                $pfComponents = method_exists($salary, 'calculatePfComponents') 
                    ? $salary->calculatePfComponents($salary->salary) 
                    : $this->zeroSalaryDetails();
    
                // Store salary and PF details per employee
                $this->salaryData[$empId] = [
                    'salary' => $salaryComponents,
                    'pf' => $pfComponents,
                ];
    
                // Update salary totals
                foreach ($salaryComponents as $key => $value) {
                    if (isset($this->totals[$key])) {
                        $this->totals[$key] += is_numeric($value) ? $value : 0;
                    }
                }
    
                // Update PF totals
                foreach ($pfComponents as $key => $value) {
                    if (isset($this->pftotals[$key])) {
                        $this->pftotals[$key] += is_numeric($value) ? $value : 0;
                    }
                }
            }
        }
    
        // Update Livewire state
        $this->allSalaryDetails = $this->salaryRevision;
    }
    public function export()
    {
        if (empty($this->salaryData)) {
            session()->flash('error', 'No salary data available for export.');
            return;
        }
    
        return Excel::download(new QuickSalaryExport($this->salaryData, $this->allSalaryDetails), 'quicksalary_data.xlsx');
    }
    
    
    public function generateMonths()
    {
        $this->options = [];

        $currentYear = date('Y');
        $lastMonth = date('n');

        for ($year = $currentYear; $year >= $currentYear - 1; $year--) {
            $startMonth = ($year == $currentYear) ? $lastMonth : 12;
            $endMonth = 1;

            for ($month = $startMonth; $month >= $endMonth; $month--) {
                $monthPadded = sprintf('%02d', $month);
                $dateObj = DateTime::createFromFormat('!m', $monthPadded);
                $monthName = $dateObj->format('F');
                $this->options["$year-$monthPadded"] = "$monthName $year";
            }
        }
    }

    public function render()
    {
        if (empty($this->salaryRevision)) { 
            $this->allSalaryDetails = $this->getSalaryDetails();// Ensure data is available
        }
    
      

        $allSalaryDetails = EmpSalary::join('salary_revisions', 'emp_salaries.sal_id', '=', 'salary_revisions.id')
            ->where('salary_revisions.emp_id', $this->emp_id)
            ->where('month_of_sal', 'like', $this->selectedMonth . '%')
            ->where('emp_salaries.is_payslip', 1)
            ->get();

            if ($allSalaryDetails->isNotEmpty()) {
                $this->salaryDivisions = $allSalaryDetails->map(function ($salary) {
                    return [
                        'salaryComponents' => method_exists($salary, 'calculateSalaryComponents') 
                            ? $salary->calculateSalaryComponents($salary->salary) 
                            : $this->zeroSalaryDetails(),
            
                        'pfComponents' => method_exists($salary, 'calculatePfComponents') 
                            ? $salary->calculatePfComponents($salary->salary) 
                            : $this->zeroSalaryDetails(),
                    ];
                });
            } else {
                $this->salaryDivisions = [];
            }
            

        // Fix: Ensure empBankDetails query is correct
        $bankIds = $allSalaryDetails->pluck('bank_id')->unique();
        $this->empBankDetails = EmpBankDetail::whereIn('id', $bankIds)->get();

        // Fix: Ensure employeePersonalDetails query is correct
        $employeeIds = $allSalaryDetails->pluck('emp_id')->unique();
        $this->employeePersonalDetails = EmpPersonalInfo::whereIn('emp_id', $employeeIds)->get();

        return view('livewire.quick-salary', [
            'options' => $this->options,
            'allSalaryDetails' => $this->salaryRevision, 
            'employees' => $this->employees,
            'salaryData' => $this->salaryData,
        ]);
    }

    public function zeroSalaryDetails()
    {
        return [
            'basic' => 0, 'hra' => 0, 'conveyance' => 0, 'medical_allowance' => 0,
            'special_allowance' => 0, 'earnings' => 0, 'gross' => 0, 'pf' => 0,
            'esi' => 0, 'professional_tax' => 0, 'total_deductions' => 0, 'net_pay' => 0, 'working_days' => 0,
        ];
    }

    public function changeMonth()
    {
        $this->getSalaryDetails();
        $this->generateMonths();
       
    }

    
  
 
    
    
    


 
}

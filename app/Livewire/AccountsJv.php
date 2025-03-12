<?php

namespace App\Livewire;

use App\Exports\AccountsJVExport;
use App\Models\EmpBankDetail;
use App\Models\EmployeeDetails;
use App\Models\EmpPersonalInfo;
use App\Models\EmpSalary;
use App\Models\EmpSalaryRevision;
use Carbon\Carbon;
use DateTime;
use Hashids\Hashids;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Vinkla\Hashids\Facades\Hashids as FacadesHashids;

class AccountsJv extends Component
{

    public $showDetails=true;
    public $selectedMonth;
    public $salary;
    public $employees;
    public $options=[];
    public $empBankDetails;
    public $employeePersonalDetails;
   
    public $allSalaryDetails;
    public $salaryDivisions=[];
    public $employeeDetails;
    public $allEmployees;
    public $salaryRevision;
     public $empSalaryDetails;
     public $AccountsjvDialog=false;
     public $showModal=false;
   
     public $isCancelled = false;
     public $cancelledAt;
     public $showTable = true; // Initially show table

     
     public function showTable()
    {
        $this->showTable = true;
        $this->emit('$refresh'); // Livewire will re-render the component
    }
  public function closeModal(){
    $this->AccountsjvDialog = false;
    $this->showModal = false;
  }
    public function hideTable()
    {
        $this->showTable = false;
        $this->emit('$refresh'); // Ensure component refreshes when hiding
    }


    public function cancelJV()
    {
        $this->AccountsjvDialog = true;
        $this->showModal = true;
    }

    public function validateAndPublish()
    {
        $this->isCancelled = true;
        $this->cancelledAt = Carbon::now()->format('d M Y H:i:s');
        $this->showModal = false;
        $this->showTable = false; // Hide table after cancellation
    }

     public function convertNumberToWords($number)
     {
         // Array to represent numbers from 0 to 19 and the tens up to 90
         $words = [
             0 => 'zero',
             1 => 'one',
             2 => 'two',
             3 => 'three',
             4 => 'four',
             5 => 'five',
             6 => 'six',
             7 => 'seven',
             8 => 'eight',
             9 => 'nine',
             10 => 'ten',
             11 => 'eleven',
             12 => 'twelve',
             13 => 'thirteen',
             14 => 'fourteen',
             15 => 'fifteen',
             16 => 'sixteen',
             17 => 'seventeen',
             18 => 'eighteen',
             19 => 'nineteen',
             20 => 'twenty',
             30 => 'thirty',
             40 => 'forty',
             50 => 'fifty',
             60 => 'sixty',
             70 => 'seventy',
             80 => 'eighty',
             90 => 'ninety'
         ];
 
         // Handle special cases
         if ($number < 0) {
             return 'minus ' . $this->convertNumberToWords(-$number);
         }
 
         // Handle numbers less than 100
         if ($number < 100) {
             if ($number < 20) {
                 return $words[$number];
             } else {
                 $tens = $words[10 * (int) ($number / 10)];
                 $ones = $number % 10;
                 if ($ones > 0) {
                     return $tens . ' ' . $words[$ones];
                 } else {
                     return $tens;
                 }
             }
         }
 
         // Handle numbers greater than or equal to 100
         if ($number < 1000) {
             $hundreds = $words[(int) ($number / 100)] . ' hundred';
             $remainder = $number % 100;
             if ($remainder > 0) {
                 return $hundreds . ' ' . $this->convertNumberToWords($remainder);
             } else {
                 return $hundreds;
             }
         }
 
         // Handle larger numbers
         if ($number < 1000000) {
             $thousands = $this->convertNumberToWords((int) ($number / 1000)) . ' thousand';
             $remainder = $number % 1000;
             if ($remainder > 0) {
                 return $thousands . ' ' . $this->convertNumberToWords($remainder);
             } else {
                 return $thousands;
             }
         }
 
         // Handle even larger numbers
         if ($number < 1000000000) {
             $millions = $this->convertNumberToWords((int) ($number / 1000000)) . ' million';
             $remainder = $number % 1000000;
             if ($remainder > 0) {
                 return $millions . ' ' . $this->convertNumberToWords($remainder);
             } else {
                 return $millions;
             }
         }
 
         // Handle numbers larger than or equal to a billion
         return 'number too large to convert';
     }
 
 
     public function mount(){
        $employeeId = auth()->user()->emp_id;


        $this->allEmployees = EmployeeDetails::select('employee_details.*', 'emp_departments.department')
        ->leftJoin('emp_departments', 'employee_details.dept_id', '=', 'emp_departments.dept_id')
        ->leftJoin('emp_personal_infos', 'employee_details.emp_id', '=', 'emp_personal_infos.emp_id')
        ->get();
        $this->salaryRevision = EmpSalaryRevision::where('emp_id', $employeeId)->get();
        $this->empSalaryDetails = EmpSalary::join('salary_revisions', 'emp_salaries.sal_id', '=', 'salary_revisions.id')
        ->where('salary_revisions.emp_id',$employeeId)
            ->where('month_of_sal', 'like', $this->selectedMonth . '%')
            ->get();
          
        $this->options = []; // Initialize to avoid null
        $this->generateMonths();
     }
     public function changeMonth()
    {
   
        $this->getSalaryDetails();
        $this->generateMonths(); // Refresh month options if necessary
    }
    public function getSalaryDetails()
    {
        // Fetch all employees with department info
        $this->allEmployees = EmployeeDetails::select('employee_details.*', 'emp_departments.department')
            ->leftJoin('emp_departments', 'employee_details.dept_id', '=', 'emp_departments.dept_id')
            ->leftJoin('emp_personal_infos', 'employee_details.emp_id', '=', 'emp_personal_infos.emp_id')
            ->get();
        
        // Fetch salary revisions for all employees
        $this->salaryRevision = EmpSalaryRevision::all();
        
        // Fetch salary details for all employees
        $this->empSalaryDetails = EmpSalary::join('salary_revisions', 'emp_salaries.sal_id', '=', 'salary_revisions.id')
            ->where('month_of_sal', 'like', $this->selectedMonth . '%')
            ->get();
        
        // Fetch detailed salary info for all employees
        $salaryDetails = DB::table('emp_salaries')
            ->join('salary_revisions', 'emp_salaries.sal_id', '=', 'salary_revisions.id')
            ->where('month_of_sal', 'like', $this->selectedMonth . '%')
            ->select('emp_salaries.*', 'salary_revisions.emp_id')
            ->get();
    
    
    
        // Return the data
        return $salaryDetails;
    }
    
    
    public function generateMonths()
    {
        $this->options = []; // Reset options before generating new ones
    
        $currentYear = date('Y');
        $lastMonth = date('n'); // Current month (1-12)
    
        for ($year = $currentYear; $year >= $currentYear - 1; $year--) {
            $startMonth = ($year == $currentYear) ? $lastMonth : 12;
            $endMonth = 1; // Always end at January
    
            for ($month = $startMonth; $month >= $endMonth; $month--) {
                $monthPadded = sprintf('%02d', $month);
                $dateObj = DateTime::createFromFormat('!m', $monthPadded);
                $monthName = $dateObj->format('F');
                $this->options["$year-$monthPadded"] = "$monthName $year";
            }
        }
    }
    public function toggleDetails()
    {
        $this->showDetails = !$this->showDetails;
    }
    private function decodeCTC($value)
    {
        
        $decoded = FacadesHashids::decode($value);

        if (count($decoded) === 0) {
            return 0;
        }

        $integerValue = $decoded[0];
        $decimalPlaces = $decoded[1] ?? 0;

        return $integerValue / pow(10, $decimalPlaces);
    }

    public function exportToExcel(): BinaryFileResponse
    {
        // Fetch latest totals before exporting
        $this->allEmployees = EmployeeDetails::select('employee_details.*', 'emp_departments.department')
        ->leftJoin('emp_departments', 'employee_details.dept_id', '=', 'emp_departments.dept_id')
        ->leftJoin('emp_personal_infos', 'employee_details.emp_id', '=', 'emp_personal_infos.emp_id')
        ->get();
        $this->salaryRevision = EmpSalaryRevision::all();
    
        // Fetch salary details for all employees
        $empSalaryDetails = EmpSalary::join('salary_revisions', 'emp_salaries.sal_id', '=', 'salary_revisions.id')
            ->where('month_of_sal', 'like', $this->selectedMonth . '%')
            ->select('emp_salaries.*', 'salary_revisions.emp_id')
            ->get();
    
        // Prepare salary divisions for each employee
        $salaryDivisions = [];
    
        foreach ($empSalaryDetails as $salary) {
            if (isset($salary->salary)) {
                $salaryDivisions[$salary->emp_id] = $this->calculateSalaryComponents($salary->salary);
             
            }
        }
    
        $this->salaryDivisions = $salaryDivisions;
    
        $totals = [
            'basic' => 0, 'hra' => 0, 'conveyance' => 0, 'special_allowance' => 0,
            'pf' => 0, 'employeer_pf' => 0, 'salary_payable' => 0
        ];
    
        foreach ($this->allEmployees as $employee) {
            if (isset($salaryDivisions[$employee->emp_id])) {
                $totals['basic'] += $salaryDivisions[$employee->emp_id]['basic'];
                $totals['hra'] += $salaryDivisions[$employee->emp_id]['hra'];
                $totals['conveyance'] += $salaryDivisions[$employee->emp_id]['conveyance'];
                $totals['special_allowance'] += $salaryDivisions[$employee->emp_id]['special_allowance'];
                $totals['pf'] += $salaryDivisions[$employee->emp_id]['pf'];
                $totals['employeer_pf'] += $salaryDivisions[$employee->emp_id]['employeer_pf'];
                $totals['salary_payable'] += $salaryDivisions[$employee->emp_id]['net_pay'];
            }
        }
    
        // Log totals for debugging
        Log::info('Exporting Totals:', $totals);
    
        return Excel::download(new AccountsJVExport($totals), 'accounts-jv-report.xlsx');
    }
    
    public function calculateSalaryComponents($value)
    {
        $gross = $value ? $this->decodeCTC($value) : 0;
    
        // Calculate each salary component based on given percentages
        $basic = round($gross * 0.4200, 2); // 42.00%
        $hra = round($gross * 0.1680, 2); // 16.80%
        $conveyance = round($gross * 0.0767, 2); // 7.67%
        $medicalAllowance = round($gross * 0.0600, 2); // 6.00%
        $specialAllowance = round($gross * 0.2750, 2); // 27.50%
    
        // Deductions
        $pf = round($gross * 0.0504, 2); // 5.04%
        $esi = round($gross * 0.00753, 2); // 0.753%
        $professionalTax = round($gross * 0.0096, 2); // 0.96%
        $employeer_pf = round($pf * 0.3000,2);
        // Total Deductions
        $totalDeductions = round($pf + $esi + $professionalTax, 2); // Total deductions
    
        // Total Earnings (before deductions)
        $totalEarnings = round($basic + $hra + $conveyance + $medicalAllowance + $specialAllowance, 2);
    
        // Net Pay (after deductions)
        $netPay = round($totalEarnings - $totalDeductions, 2);
    
        // Return all salary components
        return [
            'basic' => $basic,
            'hra' => $hra,
            'conveyance' => $conveyance,
            'medical_allowance' => $medicalAllowance,
            'special_allowance' => $specialAllowance,
            'earnings' => $totalEarnings,
            'gross' => round($gross, 2),
            'pf' => $pf,
            'esi' => $esi,
            'professional_tax' => $professionalTax,
            'total_deductions' => $totalDeductions,
            'net_pay' => $netPay,
            'employeer_pf' => $employeer_pf,
            'working_days' => '30'
        ];
    }
     
    public function render()
    {
        // Fetch all employees with department info
        $this->allEmployees = EmployeeDetails::select('employee_details.*', 'emp_departments.department')
            ->leftJoin('emp_departments', 'employee_details.dept_id', '=', 'emp_departments.dept_id')
            ->leftJoin('emp_personal_infos', 'employee_details.emp_id', '=', 'emp_personal_infos.emp_id')
            ->get();
          
        $this->salaryRevision = EmpSalaryRevision::all();
    
        // Fetch salary details for all employees
        $empSalaryDetails = EmpSalary::join('salary_revisions', 'emp_salaries.sal_id', '=', 'salary_revisions.id')
            ->where('month_of_sal', 'like', $this->selectedMonth . '%')
            ->select('emp_salaries.*', 'salary_revisions.emp_id')
            ->get();
    
        // Prepare salary divisions for each employee
        $salaryDivisions = [];
    
        foreach ($empSalaryDetails as $salary) {
            if (isset($salary->salary)) {
                $salaryDivisions[$salary->emp_id] = $this->calculateSalaryComponents($salary->salary);
             
            }
        }
    
        $this->salaryDivisions = $salaryDivisions;
    
        return view('livewire.accounts-jv', [
            'allEmployees' => $this->allEmployees,
            'salaryDivisions' => $this->salaryDivisions,
        ]);
    }
}    
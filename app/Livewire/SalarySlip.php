<?php

namespace App\Livewire;

use App\Helpers\FlashMessageHelper;
use App\Mail\PayrollProcessedMail;
use App\Models\Company;
use App\Models\EmpBankDetail;
use App\Models\EmployeeDetails;
use App\Models\EmpPersonalInfo;
use App\Models\EmpSalary;
use App\Models\EmpSalaryRevision;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Vinkla\Hashids\Facades\Hashids as FacadesHashids;
use ZipArchive;

class SalarySlip extends Component
{
    public $showDetails = true;
    public $activeTab = 'payslip';
    public $allSalaryDetails=[];

    public $selectAll;
    public $employees;
    public $salaryDivisions;
    public $salaryRevision;
    public $allEmployees;
    public $employeeIds ;
    public $selectedMonth;
    public $empSalaryDetails;
    public $empCompanyLogoUrl;
    public $employeeDetails = [];
    public $empBankDetails;
    public $employeePersonalDetails;
    public $empId;
    public $options=[];
    public $employeeNames;
    public $showModal = false;
public $remarks;
public $selectedEmployeeId;
public $selectedEmployeeFirstName;
public $selectedEmployeeLastName;
public $searchTerm;
public $selectedPeopleNames = [];
public $selectedPeople = [];
public $selectedPeopleData;
public $currentEmpId;
public $cc_to;

protected $fillable = ['remarks']; // Ensure 'remarks' is fillable

public function mount()
{
    // Retrieve the logged-in user's emp_id from the 'hr' guard
    if (auth()->guard('hr')->check()) {
        $loggedInEmpID = auth()->guard('hr')->user()->emp_id;
        // Debugging to ensure correct emp_id is fetched
        // Check if emp_id is correct
    } else {
        return;
    }


    $employeeId = auth()->user()->emp_id;

    $this->employeeNames = [];
    $this->allEmployees = EmpSalary::join('salary_revisions', 'emp_salaries.sal_id', '=', 'salary_revisions.id')
  
        ->where('month_of_sal', 'like', $this->selectedMonth . '%')
        ->select('emp_salaries.remarks') 
        ->get();
 

    $this->salaryRevision = EmpSalaryRevision::where('emp_id', $employeeId)->get();
    $this->empSalaryDetails = EmpSalary::join('salary_revisions', 'emp_salaries.sal_id', '=', 'salary_revisions.id')
    ->where('salary_revisions.emp_id',$employeeId)
        ->where('month_of_sal', 'like', $this->selectedMonth . '%')
        ->select('emp_salaries.*') 
        ->get();
      

        $this->selectedEmployees = [];
        
        if (!empty($this->selectedEmployees)) {
            $this->employeeNames = EmpSalary::whereIn('id', $this->selectedEmployees)
                ->pluck('first_name', 'id') // Adjust based on your DB column
                ->toArray();
        }
        
    // Adjust this line based on your actual database column for category

    $loggedInEmpID = auth()->guard('hr')->user()->emp_id;
    $companyId = EmployeeDetails::where('emp_id', $loggedInEmpID)
        ->pluck('company_id') // This returns the array of company IDs
        ->first();
    if (is_array($companyId)) {
        $firstCompanyID = $companyId[0]; // Get the first element from the array
    } else {
        $firstCompanyID = $companyId; // Handle case where it's not an array
    }

    $this->empId = auth()->user()->emp_id;
    // Fetch the company_id associated with the employee
    $companyID = EmployeeDetails::where('emp_id', $firstCompanyID)
        ->pluck('company_id')
        ->first(); // This will return the first company ID for the employee

    // Outputs the company_id based on whether it's a parent or not


    // Retrieve the company_id associated with the logged-in emp_id
    $employeeDetails = EmployeeDetails::where('emp_id', $loggedInEmpID)->first();

    if (!$employeeDetails) {
        // Debug if no employee details are found for this emp_id

        return;
    }

    $companyID = $employeeDetails->company_id;

    if (!$companyID) {
        // Handle the case where companyID is null

        $this->employeeIds = [];
        return;
    }

    // Fetch all emp_id values where company_id matches the logged-in user's company_id
    $this->employeeIds = EmployeeDetails::whereJsonContains('company_id', $firstCompanyID)->pluck('emp_id')->toArray();



    $this->options = []; // Initialize to avoid null
    $this->selectedMonth = $this->selectedMonth ?? date('Y-m');
    $this->generateMonths();
    
    // Fetch the employee IDs after filtering
 

    if (empty($this->employeeIds)) {
        // Handle the case where no employees are found

        return;
    }

}  
public function openRemarks()
{
    $this->showModal = true;

    if (!empty($this->selectedEmployees)) {
        // Fetch full names (concatenating first_name and last_name)
        $this->employeeNames = EmployeeDetails::whereIn('emp_id', $this->selectedEmployees)
            ->selectRaw("CONCAT(first_name, ' ', last_name) AS full_name, emp_id")
            ->pluck('full_name', 'emp_id')
            ->toArray();

        // Fetch salary data and remarks for selected employees
        $this->allEmployees = EmpSalary::join('salary_revisions', 'emp_salaries.sal_id', '=', 'salary_revisions.id')
          
            ->where('month_of_sal', 'like', $this->selectedMonth . '%')
            ->select('emp_salaries.id', 'salary_revisions.emp_id', 'emp_salaries.remarks')
            ->get()  ->toArray();;
    }
}

public function saveRemarks()
{
    // Check if remarks are provided
    if ($this->remarks) {
        // Step 1: Fetch emp_ids from salary_revisions based on selected employees
        $empIdsFromRevisions = EmpSalaryRevision::whereIn('emp_id', $this->selectedEmployees)
            ->pluck('id') // Get sal_id values
            ->toArray();

        // Step 2: Fetch salary IDs from emp_salaries
        $selectedSalIds = EmpSalary::whereIn('sal_id', $empIdsFromRevisions)
            ->where('month_of_sal', 'like', $this->selectedMonth . '%')
            ->pluck('id')
            ->toArray();

        // Debugging: Check selected salary IDs
  
        // Step 3: Update remarks if records exist
        if (!empty($selectedSalIds)) {
            EmpSalary::whereIn('id', $selectedSalIds)->update([
                'remarks' => $this->remarks
            ]);

            session()->flash('message', 'Remarks updated successfully.');
        } else {
            session()->flash('error', 'No matching salary records found.');
        }
    } else {
        session()->flash('error', 'Please enter a remark before saving.');
    }

    $this->closeModal();
}





public function closeModal()
{
    $this->showModal = false;
}
   
   
    
        


     


public $selectedEmployeeImage;
public function selectEmployee($empId)
{

    $this->selectedEmployeeId = $empId;
    $this->selectedEmployeeFirstName = EmployeeDetails::where('emp_id', $empId)->value('first_name');
    $this->selectedEmployeeLastName = EmployeeDetails::where('emp_id', $empId)->value('last_name');
    $this->selectedEmployeeImage = EmployeeDetails::where('emp_id', $empId)->value('image');
    $this->searchTerm = '';
}

public function searchforEmployee()
{

    if (!empty($this->searchTerm)) {
        // Fetch employees matching the search term
        $this->employees = EmployeeDetails::where(function ($query) {
            $query->where('first_name', 'like', '%' . $this->searchTerm . '%')
                ->orWhere('last_name', 'like', '%' . $this->searchTerm . '%')
                ->orWhere('emp_id', 'like', '%' . $this->searchTerm . '%');
        })->get();

        // Include previously selected employees not currently displayed in the search
        foreach ($this->selectedPeople as $selectedEmpId) {
            // Check if selected employee is in the current employees
            if (!$this->employees->contains('emp_id', $selectedEmpId)) {
                $selectedEmployee = EmployeeDetails::where('emp_id', $selectedEmpId)->first();
                if ($selectedEmployee) {
                    // Ensure it's marked as checked
                    $selectedEmployee->isChecked = true;
                    $this->employees->push($selectedEmployee);
                }
            }
        }

        // Set isChecked for employees in the current search results
        foreach ($this->employees as $employee) {
            $employee->isChecked = in_array($employee->emp_id, $this->selectedPeople);
        }
    } else {
        $this->employees = collect(); // Reset employees if no search term
    }
    $this->selectedEmployeeId = null;
    $this->selectedEmployeeFirstName = null;
    $this->selectedEmployeeLastName = null;
    $this->selectedEmployeeImage = null;
}


    

    public function setTab($tab)
    {
        $this->activeTab = $tab;
    }
    public $selectionType = 'all'; // Default: All Employees
    public $selectedEmployees = []; // Store selected employees
    public $payslipFormat = 'pdf';
    public function setSelectionType($type)
    {
        $this->selectionType = $type;
        if ($type !== 'selected') {
            $this->selectedEmployees = []; // Reset selection
        }
    }
    public function setPayslipFormat($format)
    {
        $this->payslipFormat = $format;
    }

    public function toggleDetails()
    {
        $this->showDetails = !$this->showDetails;
    }
    public function changeMonth()
    {
        
        $this->salaryDivisions;
      $this->getSalaryDetails();
     
        $this->generateMonths(); // Refresh month options if necessary
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
    private function getEmpCompanyLogoUrl()
    {
        // Get the current authenticated employee's company ID
        if (auth()->check()) {
            // Get the current authenticated employee's company ID
            $empCompanyId = auth()->user()->company_id;
            $employeeId = auth()->user()->emp_id;
            $employeeDetails = DB::table('employee_details')
                ->where('emp_id', $employeeId)
                ->select('company_id') // Select only the company_id
                ->first();

            // Assuming you have a Company model with a 'company_logo' attribute
            $companyIds = json_decode($employeeDetails->company_id);
       
            $company = DB::table('companies')
                ->where('company_id', $companyIds)
                ->where('is_parent', 'yes')
                ->first();

            // Return the company logo URL, or a default if company not found
            return $company ? $company->company_logo : asset('user.jpg');
        } elseif (auth()->guard('hr')->check()) {
            $empCompanyId = auth()->guard('hr')->user()->company_id;

            // Assuming you have a Company model with a 'company_logo' attribute
            $company = Company::where('company_id', $empCompanyId)->first();
            return $company ? $company->company_logo : asset('user.jpg');
        }
    }
    public function getSalaryDetails()
    {
        // Fetch salary details for all employees for the selected month
        $salaryDetails = DB::table('emp_salaries')
            ->join('salary_revisions', 'emp_salaries.sal_id', '=', 'salary_revisions.id')
            ->where('month_of_sal', 'like', $this->selectedMonth . '%')
            ->select('emp_salaries.*', 'salary_revisions.emp_id')
            ->get();
    
        // Fetch salary details for other months (to check previous records)
        $previousSalaryDetails = DB::table('emp_salaries')
            ->join('salary_revisions', 'emp_salaries.sal_id', '=', 'salary_revisions.id')
            ->whereNot('month_of_sal', 'like', $this->selectedMonth . '%') // Exclude selected month
            ->select('emp_salaries.*', 'salary_revisions.emp_id')
            ->get();
    
        // Initialize salary divisions
        $salaryDivisions = [];
    
        foreach ($salaryDetails as $salary) {
            if (isset($salary->salary)) {
                $salaryDivisions[$salary->emp_id] = $this->calculateSalaryComponents($salary->salary);
                $salaryDivisions[$salary->emp_id]['is_payslip'] = $salary->is_payslip ?? 0; // Default to 0 (No)
                $salaryDivisions[$salary->emp_id]['remarks'] = $salary->remarks ; 
            }
        }
    
        // Handle employees with previous months' salary records but not for the selected month
        foreach ($previousSalaryDetails as $prevSalary) {
            if (!isset($salaryDivisions[$prevSalary->emp_id])) {
                $salaryDivisions[$prevSalary->emp_id] = [
                    'salary' => $prevSalary->salary,
                    'is_payslip' => 0, // Default to 0 (No)
                ];
            }
        }
    
        // Fetch employee bank details
        $empBankDetails = EmpBankDetail::whereIn('emp_id', $salaryDetails->pluck('emp_id'))->get();
    
        // Fetch employee personal details
        $employeePersonalDetails = EmpPersonalInfo::whereIn('emp_id', $salaryDetails->pluck('emp_id'))->get();
    
        // Return all collected details
        return [
            'salaryDivisions' => $salaryDivisions,
            'empBankDetails' => $empBankDetails,
            'employeePersonalDetails' => $employeePersonalDetails,
            'allSalaryDetails' => $salaryDetails,
        ];
    }

    
    public function downloadPayslips()
    {
        if (empty($this->selectedEmployees)) {
            session()->flash('error', 'Please select at least one employee.');
            return;
        }
    
        if ($this->payslipFormat === 'zip') {
            // If only 1 employee is selected, download PDF instead of ZIP
            if (count($this->selectedEmployees) === 1) {
                return $this->downloadPdf($this->selectedEmployees[0]);
            }
            return $this->downloadPayslipsZip();
        }
    
        if ($this->payslipFormat === 'pdf') {
            // If only one employee is selected, generate their PDF
            if (count($this->selectedEmployees) === 1) {
                return $this->downloadPdf($this->selectedEmployees[0]);
            }
            session()->flash('error', 'Please select only one employee for Consolidated Payslip as PDF.');
        }
    }
    public function selectPerson($emp_id)
    {
        if (!empty($this->selectedPeople) && !in_array($emp_id, $this->selectedPeople)) {
            // Flash an error message to the session
            FlashMessageHelper::flashWarning('You can only select one employee ');
            return; // Stop further execution
        }


        try {

            // Ensure $this->selectedPeople is initialized as an array
            if (!is_array($this->selectedPeople)) {
                $this->selectedPeople = [];
            }


            // Find the selected person from the list of employees
            $selectedPerson = $this->employees->where('emp_id', $emp_id)->first();

            if ($selectedPerson) {
                // Check if person is already selected
                if (in_array($emp_id, $this->selectedPeople)) {
                    // Person is already selected, so remove them

                    // Remove from selectedPeople array
                    $this->selectedPeople = array_diff($this->selectedPeople, [$emp_id]);

                    // Remove the person's entry from the selectedPeopleData array
                    $this->selectedPeopleData = array_filter(
                        $this->selectedPeopleData,
                        fn($data) => $data['emp_id'] !== $emp_id
                    );
                } else {
                    // Person is not selected, so add them
                    $this->selectedPeople[] = $emp_id;

                    // Create the person's name string
                    $personName = $selectedPerson->first_name . ' ' . $selectedPerson->last_name . ' #(' . $selectedPerson->emp_id . ')';

                    // Determine the image URL
                    if ($selectedPerson->image && $selectedPerson->image !== 'null') {
                        $imageUrl = 'data:image/jpeg;base64,' . base64_encode($selectedPerson->image);
                    } else {
                        // Add default image based on gender
                        if ($selectedPerson->gender == "Male") {
                            $imageUrl = asset('images/male-default.png');
                        } elseif ($selectedPerson->gender == "Female") {
                            $imageUrl = asset('images/female-default.jpg');
                        } else {
                            $imageUrl = asset('images/user.jpg');
                        }
                    }

                    // Add the person's data to the combined array
                    $this->selectedPeopleData[] = [
                        'name' => $personName,
                        'image' => $imageUrl,
                        'emp_id' => $emp_id
                    ];
                }

                // Update the cc_to field with the unique names
                $this->cc_to = implode(', ', array_unique(array_column($this->selectedPeopleData, 'name')));
                // After setting currentEmpId
                $this->currentEmpId = $emp_id;
                Log::info('Current emp_id set to: ' . $this->currentEmpId);
            }
        } catch (\Exception $e) {
            // Handle the exception
            // Optionally, you can log the error or display a user-friendly message
            $this->dispatch('error', ['message' => 'An error occurred: ' . $e->getMessage()]);
        }
    }
    public function updateselectedEmployee($empId)
    {
       
        $this->selectedEmployeeId ;
        // dd($empId);

        $employee = EmployeeDetails::find($empId);

        if ($employee) {
            $this->selectedEmployeeId = $employee->emp_id;
            $this->selectedEmployeeFirstName = ucfirst(strtolower($employee->first_name));
            $this->selectedEmployeeLastName = ucfirst(strtolower($employee->last_name));
            $this->searchTerm = ''; // Clears search term, but input retains full name
        }
        $this->selectedEmployeeFirstName = EmployeeDetails::where('emp_id', $empId)->value('first_name');
        $this->selectedEmployeeLastName = EmployeeDetails::where('emp_id', $empId)->value('last_name');
        if (!empty($this->selectedEmployeeId)) {
         
            $this->allSalaryDetails = $this->getSalaryDetails();

            $this->selectedEmployeeId ;
           
          

            $this->employeeDetails = EmployeeDetails::select('employee_details.*', 'emp_departments.department')
                ->leftJoin('emp_departments', 'employee_details.dept_id', '=', 'emp_departments.dept_id')
                ->leftJoin('emp_personal_infos', 'employee_details.emp_id', '=', 'emp_personal_infos.emp_id')
                ->where('employee_details.emp_id', $this->selectedEmployeeId)
                ->first();

          
        } 
    }
    public function downloadPayslipsZip()
{
    if (count($this->selectedEmployees) === 1) {
        return $this->downloadPdf($this->selectedEmployees[0]);
    }

    $salaryDetails = DB::table('emp_salaries')
        ->join('salary_revisions', 'emp_salaries.sal_id', '=', 'salary_revisions.id')
        ->whereIn('salary_revisions.emp_id', $this->selectedEmployees)
        ->where('emp_salaries.is_payslip', 1) // Only employees with payslips
        ->select('emp_salaries.*', 'salary_revisions.emp_id')
        ->get();

    if ($salaryDetails->isEmpty()) {
        session()->flash('error', 'No payslips found for the selected employees.');
        return;
    }

    $zip = new ZipArchive;
    $zipFileName = 'payslips-' . Carbon::now()->format('MY') . '.zip';
    $zipFilePath = tempnam(sys_get_temp_dir(), 'payslips_');

    if ($zip->open($zipFilePath, ZipArchive::CREATE) !== TRUE) {
        return response()->json(['error' => 'Could not create ZIP file'], 500);
    }

    foreach ($salaryDetails as $salary) {
        $employee = EmployeeDetails::where('emp_id', $salary->emp_id)->first();
        if (!$employee) continue;

        $salaryDivisions = $this->calculateSalaryComponents($salary->salary);
        $empBankDetails = EmpBankDetail::where('emp_id', $salary->emp_id)->first();

        $pdf = Pdf::loadView('download-pdf', [
            'employeeDetails' => $employee,
            'salaryRevision' => $salaryDivisions,
            'empBankDetails' => $empBankDetails,
            'rupeesInText' => $this->convertNumberToWords($salaryDivisions['net_pay']),
            'salMonth' => Carbon::now()->format('F Y'),
            'empCompanyLogoUrl' => $this->getEmpCompanyLogoUrl(),
        ]);

        $pdfContent = $pdf->output();
        $pdfFileName = 'payslip-' . $employee->emp_id . '-' . Carbon::now()->format('MY') . '.pdf';

        $zip->addFromString($pdfFileName, $pdfContent);
    }

    $zip->close();

    return response()->download($zipFilePath, $zipFileName)->deleteFileAfterSend(true);
}
public function sendPayslipEmails()
{
   
    $selectedMonth = $this->selectedMonth;
    foreach ($this->selectedEmployees as $employee) {
      
        $employee = EmployeeDetails::where('emp_id', $employee)->first();
        if (!empty($employee->email)) {
            Mail::to($employee->email)->send(new PayrollProcessedMail($employee, $selectedMonth));
        }
 


}
}
public function sendPayslipallEmails()
{
    $selectedMonth = $this->selectedMonth;

    // If no specific employees are selected, fetch all employees
    $this->allEmployees = EmpSalary::join('salary_revisions', 'emp_salaries.sal_id', '=', 'salary_revisions.id')
  
        ->where('month_of_sal', 'like', $this->selectedMonth . '%')
        ->select('emp_salaries.remarks') 
        ->get();

  
    foreach ($this->allEmployees as $employee) {
 
        $employee = EmployeeDetails::where('emp_id', $employee)->get();
        if (!empty($employee->email)) {
            dd($employee->email);
            Mail::to($employee->email)->send(new PayrollProcessedMail($employee, $selectedMonth));
          
        }
 


}



}
private function sendPayslipEmailsToSelected()
{
    $selectedMonth = $this->selectedMonth;
    foreach ($this->selectedEmployees as $employee) {
    
        $employee = EmployeeDetails::where('emp_id', $employee)->first();
        if (!empty($employee->email)) {
            Mail::to($employee->email)->send(new PayrollProcessedMail($employee, $selectedMonth));
        }
 

    }

    FlashMessageHelper::flashSuccess("Payslips sent successfully!");
}

private function sendPayslipEmailsToAll()
{
    $employees = EmployeeDetails::whereNotNull('email')->get();

    foreach ($employees as $employee) {
        $pdf = $this->generatePayslipPdf($employee->emp_id);
        Mail::to($employee->email)->send(new PayrollProcessedMail($employee, $this->selectedMonth, $pdf));
    }

    FlashMessageHelper::flashSuccess("Payslips sent to all employees!");
}

public function downloadPdf($empId)
{
    $employee = EmployeeDetails::where('emp_id', $empId)->first();
    if (!$employee) {
        session()->flash('error', 'Employee not found.');
        return;
    }

    $salary = DB::table('emp_salaries')
        ->join('salary_revisions', 'emp_salaries.sal_id', '=', 'salary_revisions.id')
        ->where('salary_revisions.emp_id', $empId)
        ->where('emp_salaries.is_payslip', 1)
        ->select('emp_salaries.*', 'salary_revisions.emp_id')
        ->first();

    if (!$salary) {
        session()->flash('error', 'No payslip found for the selected employee.');
        return;
    }

    $salaryDivisions = $this->calculateSalaryComponents($salary->salary);
    $empBankDetails = EmpBankDetail::where('emp_id', $empId)->first();

    $pdf = Pdf::loadView('download-pdf', [
        'employeeDetails' => $employee,
        'salaryRevision' => $salaryDivisions,
        'empBankDetails' => $empBankDetails,
        'rupeesInText' => $this->convertNumberToWords($salaryDivisions['net_pay']),
        'salMonth' => Carbon::now()->format('F Y'),
        'empCompanyLogoUrl' => $this->getEmpCompanyLogoUrl(),
    ]);

    return response()->streamDownload(
        fn() => print($pdf->output()),
        'payslip-' . $employee->emp_id . '-' . Carbon::now()->format('MY') . '.pdf'
    );
}



    public function toggleSelectAll()
    {
        if ($this->selectAll) {
            // Ensure only employees in empSalaryDetails are selected
            $this->selectedEmployees = $this->salaryDivisions ? array_keys($this->salaryDivisions) : [];
            
       
        } else {
            $this->selectedEmployees = [];
        }
    
      
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
    public function calculateSalaryComponents($value)
    {
        $gross = $value ? $this->decodeCTC($value) : 0;
        // $gross=20853;
        // Calculate each component
        $basic =round($gross * 0.4200,2); // 41.96%
        $hra =round($gross * 0.168,2);// 16.8%
        $conveyance = round($gross * 0.0767,2); // 7.67%
        $medicalAllowance = round($gross * 0.0600,2); // 5.99%
        $specialAllowance = round($gross * 0.275,2); // 27.5%

        $pf = round($gross * 0.0504,2); // 5.04%
        $esi = round($gross * 0.00753,2); // 0.753%
        $professionalTax = round($gross * 0.0096,2); // 0.96%

        // Calculate total deductions
        $totalDeductions =round( $pf + $esi + $professionalTax,2); //6.753%

        // Calculate total
        $total = round($basic + $hra + $conveyance + $medicalAllowance + $specialAllowance,2);

        // Return all components and total
        return [
            'basic' => $basic,
            'hra' => $hra,
            'conveyance' => $conveyance,
            'medical_allowance' => $medicalAllowance,
            'special_allowance' => $specialAllowance,
            'earnings' => $total,
            'gross'=> round($gross,2),
            'pf' => $pf,
            'esi' => $esi,
            'professional_tax' => $professionalTax,
            'total_deductions' => $totalDeductions,
            'net_pay'=> $total- $totalDeductions,
            'working_days'=>'30'
        ];
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


public function render()
{
    $this->allEmployees = EmployeeDetails::select('employee_details.*', 'emp_departments.department')
        ->leftJoin('emp_departments', 'employee_details.dept_id', '=', 'emp_departments.dept_id')
        ->leftJoin('emp_personal_infos', 'employee_details.emp_id', '=', 'emp_personal_infos.emp_id')
        ->get();

    $this->salaryRevision = EmpSalaryRevision::all();
    $currentYear = date('Y');
    $lastMonth = date('n');

    for ($year = $currentYear; $year >= $currentYear - 1; $year--) {
        $startMonth = ($year == $currentYear) ? $lastMonth : 12;
        $endMonth = ($year == $currentYear - 1) ? 1 : 1;
        for ($month = $startMonth; $month >= $endMonth; $month--) {
            $monthPadded = sprintf('%02d', $month);
            $dateObj = DateTime::createFromFormat('!m', $monthPadded);
            $monthName = $dateObj->format('F');
            $options["$year-$monthPadded"] = "$monthName $year";
        }
    }

    // Fetch salary details for employees
    $empSalaryDetails = EmpSalary::join('salary_revisions', 'emp_salaries.sal_id', '=', 'salary_revisions.id')
        ->where('month_of_sal', 'like', $this->selectedMonth . '%')
        ->select('emp_salaries.*', 'salary_revisions.emp_id', 'emp_salaries.is_payslip')
        ->get();
        $this->allSalaryDetails = $this->getSalaryDetails();
     
    $salaryDivisions = [];
    foreach ($empSalaryDetails as $salary) {
        if (isset($salary->salary)) {
            $salaryDivisions[$salary->emp_id] = $this->calculateSalaryComponents($salary->salary);
            $salaryDivisions[$salary->emp_id]['is_payslip'] = $salary->is_payslip ?? 0;
            $salaryDivisions[$salary->emp_id]['remarks'] = $salary->remarks ;
        }

    }

    $this->salaryDivisions = $salaryDivisions;

    return view('livewire.salary-slip', [
        'options' => $options,
    ]);
}
}

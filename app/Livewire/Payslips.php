<?php

namespace App\Livewire;

use App\Helpers\FlashMessageHelper;
use App\Mail\PayrollProcessedMail;
use App\Models\Company;
use App\Models\EmpBankDetail;
use App\Models\EmployeeDetails;
use App\Models\LetterRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Http\Request;
use App\Models\EmployeeDocument;
use App\Models\EmpPersonalInfo;
use App\Models\EmpSalary;
use App\Models\EmpSalaryRevision;
use App\Models\SalaryRevision;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

use DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Vinkla\Hashids\Facades\Hashids;
use Twilio\Rest\Client;

class Payslips extends Component
{
    use WithFileUploads;

    public $requests;
    public $options = [];
    public $salaryRevision;
    public $allSalaryDetails;

    public $empBankDetails;

    public $netPay;

    public $net_pay;
    public $email;
    public $showPopup = false;
    public $selectedMonth, $salaryMonth;
    public $salary;
    public $pdfUrl;

    public $empSalaryDetails;
    public $salaryDivisions;
    public $allEmployees = [];
    public $employeePersonalDetails;
    public $pdfPath;

    public $showConfDialog = false;
    public $filePath;
    public $selectedOption = 'all';
    public $searchTerm = '';
    public $peopleData = [];
    public $empId;
    public $empCompanyLogoUrl;

    public $selectedEmployeeId = '';

    public $employeeName;
    public $currentEmpId;


    public $searchEmployee;
    public $documents;
    public $selectedPeopleImages = [];

    public $selectedEmployeeFirstName;

    public $selectedEmployeeLastName;
    public $monthlySalary;
    public $selectedEmployee;
    public $showDocDialog = false;
    public $isNames = false;
    public $record;

    public $employeeId;
    public $hrempid;
    public $fullName;

    public $showDetails = true;

    public $activeTab = 'active';
    public $image;
    public $selectedPerson = null;
    public $cc_to;
    public $peoples;
    public $filteredPeoples;
    public $selectedPeopleNames = [];
    public $selectedPeople = [];
    public $records;
    public $peopleFound = true;
    public $file_path;


    public $showSuccessMessage = false;


    public $employeess;
    public $employeeIds = [];





    public $recentHires = [];

    public $employees;


    public $employeeDetails = [];
    public $editingField = false;
    public $PayrollDialog = false;
    public $showModal = false;
    public function PayrollRequest()
    {



        $this->PayrollDialog = true;
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->PayrollDialog = false; // If you are using this flag for the modal
    }

    public function getFormattedMonthProperty()
    {
        return Carbon::createFromFormat('Y-m', $this->selectedMonth)->format('F Y');
    }
    protected $rules = [
        'documentName' => 'required|string|max:255',
        'category' => 'required|string',
        'description' => 'nullable|string',

    ];
    protected $messages = [
        'documentName' => 'Document name is required',
        'category' => 'Category is required',
        'description' => 'Description is required',

    ];

    public $showSearch = false;
    public function toggleDetails()
    {
        $this->showDetails = !$this->showDetails;
    }
    // Tracks whether the search input is visible

    public function toggleSearch()
    {
        $this->showSearch = !$this->showSearch;  // Toggle search visibility
    }

    public function updatesearchTerm()
    {
        $this->searchTerm = $this->searchTerm;
    }
    public function updatedSelectedPeople()
    {
        $this->cc_to = implode(', ', array_unique($this->selectedPeopleNames));
    }

    public $selectedPeopleData = [];
    public $activeTab1 = 'tab1'; // Default tab

    public function switchTab($tab)
    {
        $this->activeTab1 = $tab;
    }


    public function NamesSearch()
    {
        $this->isNames = true;
        $this->selectedPeopleNames = [];
        $this->cc_to = '';
    }

    public function closePeoples()
    {
        $this->isNames = false;
    }
    public function filter()
    {

        $employeeId = auth()->user()->emp_id;

        $companyId = Auth::user()->company_id;


        $this->peopleData = EmployeeDetails::where('first_name', 'like', '%' . $this->searchTerm . '%')
            ->orWhere('last_name', 'like', '%' . $this->searchTerm . '%')
            ->orWhere('emp_id', 'like', '%' . $this->searchTerm . '%')
            ->get();

        $this->filteredPeoples = $this->searchTerm ? $this->employees : null;
    }
    public function addDocs()
    {
        $this->showDocDialog = true;
    }



    public $combinedRequests = [];

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


        $this->allEmployees = EmployeeDetails::select('employee_details.*', 'emp_departments.department')
            ->leftJoin('emp_departments', 'employee_details.dept_id', '=', 'emp_departments.dept_id')
            ->leftJoin('emp_personal_infos', 'employee_details.emp_id', '=', 'emp_personal_infos.emp_id')
            ->get();
        $this->salaryRevision = EmpSalaryRevision::where('emp_id', $employeeId)->get();
        $this->empSalaryDetails = EmpSalary::join('salary_revisions', 'emp_salaries.sal_id', '=', 'salary_revisions.id')
            ->where('salary_revisions.emp_id', $employeeId)
            ->where('month_of_sal', 'like', $this->selectedMonth . '%')
            ->where('emp_salaries.is_payslip', 1)
            ->get();



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
        $employees = EmployeeDetails::where('emp_id', $loggedInEmpID)->first();

        if (!$employees) {
            // Debug if no employee details are found for this emp_id

            return;
        }

        $companyID = $employees->company_id;

        if (!$companyID) {
            // Handle the case where companyID is null

            $this->employeeIds = [];
            return;
        }

        // Fetch all emp_id values where company_id matches the logged-in user's company_id
        $this->employeeIds = EmployeeDetails::whereJsonContains('company_id', $firstCompanyID)->pluck('emp_id')->toArray();

        $this->empCompanyLogoUrl = $this->getEmpCompanyLogoUrl();

        $this->options = []; // Initialize to avoid null
        $this->selectedMonth = $this->selectedMonth ?? date('Y-m');
        $this->generateMonths();

        // Fetch the employee IDs after filtering


        if (empty($this->employeeIds)) {
            // Handle the case where no employees are found

            return;
        }

        // Initialize employees based on search term and company_id
        $employeesQuery = EmployeeDetails::whereJsonContains('company_id', $firstCompanyID)
            ->where(function ($query) {
                $query->where('first_name', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('last_name', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('emp_id', 'like', '%' . $this->searchTerm . '%');
            })
            ->orderBy('first_name')
            ->orderBy('last_name');

        // Fetch the employees
        $employees = $employeesQuery->get();

        if ($employees->isEmpty()) {
            // Handle the case where no employees match the search term

        }





        // Debug output for fetched employees


        // Set the component's employee data
        $this->employees = $employees;



        $this->employeess = EmployeeDetails::whereJsonContains('company_id', $firstCompanyID)
            ->orderBy('hire_date', 'desc') // Order by hire_date descending

            ->take(5) // Limit to 5 records
            ->get();
        // Initialize other properties
        $this->peopleData = $this->filteredPeoples ? $this->filteredPeoples : $this->employees;
        $this->selectedPeople = [];
        $this->selectedPeopleNames = [];
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









    public function updateselectedEmployee($empId)
    {

        $this->selectedEmployeeId;
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

            $this->selectedEmployeeId;



            $this->employees = EmployeeDetails::select('employee_details.*', 'emp_departments.department')
                ->leftJoin('emp_departments', 'employee_details.dept_id', '=', 'emp_departments.dept_id')
                ->leftJoin('emp_personal_infos', 'employee_details.emp_id', '=', 'emp_personal_infos.emp_id')
                ->where('employee_details.emp_id', $this->selectedEmployeeId)
                ->first();

            // Debugging output
            Log::info('Fetched Letter Requests: ' . $this->requests->toJson());
        } else {
            $this->requests = collect(); // No selected employee, empty collection
            Log::info('No Employee Selected, Returning Empty Requests');
        }
    }


    public function closeEmployeeBox()
    {
        $this->searchEmployee;
    }
    private function getEmpCompanyLogoUrl()
    {
        // Get the current authenticated employee's company ID
        if (auth()->check()) {
            // Get the current authenticated employee's company ID
            $empCompanyId = auth()->user()->company_id;
            $employeeId = auth()->user()->emp_id;
            $employees = DB::table('employee_details')
                ->where('emp_id', $employeeId)
                ->select('company_id') // Select only the company_id
                ->first();

            // Assuming you have a Company model with a 'company_logo' attribute
            $companyIds = json_decode($employees->company_id);

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
    public function downloadPdf($month)
    {


        if (!$this->selectedEmployeeId) {
            return response()->json(['error' => 'No Employee Selected'], 400);
        }


        // Fetch employee salary details
        $empSalaryDetails = EmpSalaryRevision::join('emp_salaries', 'emp_salaries.sal_id', '=', 'salary_revisions.id')
            ->where('salary_revisions.emp_id', $this->selectedEmployeeId)
            ->where('month_of_sal', 'like',  $month . '%')
            ->where('emp_salaries.is_payslip', 1)
            ->first()->toArray();
           
        

        if (!$empSalaryDetails) {
            return response()->json(['error' => 'Salary details not found for selected employee'], 404);
        }

        // Fetch employee personal & bank details
        $employees = EmployeeDetails::select('employee_details.*', 'emp_departments.department')
            ->leftJoin('emp_departments', 'employee_details.dept_id', '=', 'emp_departments.dept_id')
            ->where('employee_details.emp_id', $this->selectedEmployeeId)
            ->first();
        

        // ✅ Define variables correctly before passing them
        // ✅ Call the function correctly
        if($employees){
            $salaryDivisions = EmpSalaryRevision::getFullAndActualSalaryComponents(
                $empSalaryDetails['salary'],
                $empSalaryDetails['revised_ctc'],
                $empSalaryDetails['total_working_days'],
                $empSalaryDetails['lop_days']
            );
        }
       


        $empBankDetails = EmpBankDetail::where('emp_id', $this->selectedEmployeeId)
            ->where('id', $empSalaryDetails['bank_id'])->first();

        // Debugging log (Check Laravel logs)
        Log::info('Generating payslip for:', [
            'Employee ID' => $this->selectedEmployeeId,
            'Salary Details' => $salaryDivisions,
            'Bank Details' => $empBankDetails
        ]);
        $this->empCompanyLogoUrl = $this->getEmpCompanyLogoUrl();
        // Pass data to PDF view
        $pdf = Pdf::loadView('download-pdf', [
            'employees' => $employees, // Pass employee details
            'salaryRevision' => $salaryDivisions,  // Pass salary breakdown
            'empBankDetails' => $empBankDetails,   // Pass bank details
            'rupeesInText' => $this->convertNumberToWords($salaryDivisions['actual_net_salary']), // Pass net pay in words
            'salMonth' => Carbon::parse($month)->format('F Y'), // Pass month formatted
          
        ]);
   

        $name = Carbon::parse($month)->format('MY');

        // Return PDF as download
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'payslip-' . $name . '.pdf');
    }


    public function cancel()
    {
        $this->showPopup = false;
    }
    public $rupeesInText;
    public $salMonth;
    public $month;
    public function viewPdf($month)
    {

        $this->selectedEmployeeId;

        $empSalaryDetails = EmpSalary::join('salary_revisions', 'emp_salaries.sal_id', '=', 'salary_revisions.id')
            ->where('salary_revisions.emp_id', $this->selectedEmployeeId)
            ->where('month_of_sal', 'like',  $month . '%')
            ->first();

        if ($empSalaryDetails) {
            $this->salaryDivisions = $empSalaryDetails->getFullAndActualSalaryComponents($empSalaryDetails->salary, $empSalaryDetails->lop_days, $empSalaryDetails->total_working_days, $empSalaryDetails->revised_ctc);
            $this->empBankDetails = EmpBankDetail::where('emp_id', $this->selectedEmployeeId)
                ->where('id', $empSalaryDetails->bank_id)->first();
            $this->employeePersonalDetails = EmpPersonalInfo::where('emp_id', $this->selectedEmployeeId)->first();
            $this->rupeesInText = $this->convertNumberToWords($this->salaryDivisions['net_pay']);
        } else {
            $this->salaryDivisions = [];
        }

        $this->salMonth = Carbon::parse($month)->format('F Y');

        $this->month = $empSalaryDetails->month_of_sal;




        // Emit event to open modal
        $this->showPopup = true;
    }


    private function calculateNetPay()
    {
        $totalGrossPay = 0;
        $totalDeductions = 0;

        foreach ($this->salaryRevision as $revision) {
            $totalGrossPay += $revision->calculateTotalAllowance();
            $totalDeductions += $revision->calculateTotalDeductions();
        }

        return $totalGrossPay - $totalDeductions;
    }
    private function encodeCTC($value)
    {
        $decimalPlaces = strpos($value, '.') !== false ? strlen(substr(strrchr($value, "."), 1)) : 0;
        $factor = pow(10, $decimalPlaces);

        $integerValue = intval($value * $factor);
        return Hashids::encode($integerValue, $decimalPlaces);
    }


    public  $to;
    public $message;
    public $bank_id;
    public $sendPayslipNotification = false; // Track checkbox state

    public function validateAndPublish()
    {
        if (!$this->sendPayslipNotification) {
            FlashMessageHelper::flashWarning('⚠️ Please check the box to proceed with payslips');
            return;
        }

        $this->confirmAndPublish();
    }

    public function confirmAndPublish()
    {
        $selectedMonth = $this->selectedMonth;
        $employeeId = Auth::user()->emp_id;

        // Fetch company ID
        $company = DB::table('employee_details')
            ->where('emp_id', $employeeId)
            ->select('company_id')
            ->first();

        if (!$company) {
            return session()->flash('error', "Error: Employee not found.");
        }

        $companyId = $company->company_id;
        $currentMonth = Carbon::now()->format('Y-m');
        $selectedMonthFormatted = Carbon::parse($selectedMonth . '-01')->format('Y-m');

        if ($selectedMonth > $currentMonth) {
            return session()->flash('error', "⚠️ Error: Selected month cannot be greater than the current month.");
        }

        // Fetch only records where is_payslip = 0 for the selected month
        $existingSalaries = EmpSalary::where('is_payslip', 0)
            ->where('month_of_sal', 'like', $selectedMonthFormatted . '%')
            ->get();

        if ($existingSalaries->isNotEmpty()) {
            // Extract salary IDs for update
            $existingSalaryIds = $existingSalaries->pluck('sal_id')->toArray();

            // Update `is_payslip` to 1 for existing records
            EmpSalary::whereIn('sal_id', $existingSalaryIds)
                ->update(['is_payslip' => 1]);

            FlashMessageHelper::flashSuccess("Payslip Released for " . Carbon::parse($this->selectedMonth)->translatedFormat('F Y') . "!");

            // Fetch employee details for sending emails
            $employees = DB::table('employee_details as ed')
                ->join('salary_revisions as sr', 'ed.emp_id', '=', 'sr.emp_id')
                ->join('emp_salaries as es', 'sr.id', '=', 'es.sal_id')
                ->whereIn('es.sal_id', $existingSalaryIds)
                ->select('ed.email', 'ed.emp_id', 'ed.first_name', 'ed.last_name')
                ->get();

            // Send emails to employees
            foreach ($employees as $employee) {
                if (!empty($employee->email)) {
                    Mail::to($employee->email)->send(new PayrollProcessedMail($employee, $selectedMonth));
                }
            }
        } 

        $this->showModal = false;
    }




    public function getSalaryDetails()
    {
        // $employeeId = auth()->user()->emp_id;

        // Querying the database directly using the DB facade

        $salaryDetails = DB::table('emp_salaries')
            ->join('salary_revisions', 'emp_salaries.sal_id', '=', 'salary_revisions.id')
            ->where('salary_revisions.emp_id', $this->selectedEmployeeId)
            ->where('month_of_sal', 'like', $this->selectedMonth . '%')
            ->select('emp_salaries.*')->where('is_payslip', 1)
            ->first();



        // Group the results manually by the financial_year_start


        return  $salaryDetails;
        if ($salaryDetails) {
            $this->salaryDivisions = $salaryDetails->calculateSalaryComponents($salaryDetails->salary);
            $this->empBankDetails = EmpBankDetail::where('emp_id', $this->selectedEmployeeId)
                ->where('id', $this->empSalaryDetails->bank_id)->first();
            $this->employeePersonalDetails = EmpPersonalInfo::where('emp_id', $this->selectedEmployeeId)->first();

            // dd( $this->employeePersonalDetails);
        } else {
            // Handle the null case (e.g., log an error or set a default value)
            $this->salaryDivisions = [];
        }
    }
    public function clearSelectedEmployee()
    {
        $this->selectedEmployeeId = '';
        $this->selectedEmployeeFirstName = '';
        $this->selectedEmployeeLastName = '';
        $this->searchTerm = '';
    }
    public function changeMonth()
    {
        $this->selectedEmployeeId;

        $this->getSalaryDetails();

        $this->generateMonths(); // Refresh month options if necessary
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

    public function clearSelection()
    {
        $this->selectedEmployeeId = '';
        $this->selectedEmployeeFirstName = '';
        $this->selectedEmployeeLastName = '';
        $this->searchTerm = '';
    }
    public function removeSelectedEmployee()
    {
        $this->selectedEmployeeId = null;
        $this->selectedEmployeeFirstName = null;
        $this->selectedEmployeeLastName = null;
        $this->selectedEmployeeImage = null;
    }

    public function render()
    {
        $loggedInEmpID = auth()->guard('hr')->user()->emp_id;
        $employeeId = auth()->user()->emp_id;
        if (auth()->guard('hr')->check()) {
            $loggedInEmpID = auth()->guard('hr')->user()->emp_id;
            // Debugging to ensure correct emp_id is fetched
            // Check if emp_id is correct
        } else {
            return;
        }



        $loggedInEmpID = auth()->guard('hr')->user()->emp_id;

        // Fetch the company_id associated with the employee
        $companyID = EmployeeDetails::where('emp_id', $loggedInEmpID)
            ->pluck('company_id')
            ->first(); // This will return the first company ID for the employee


        $companyId = EmployeeDetails::where('emp_id', $loggedInEmpID)
            ->pluck('company_id') // This returns the array of company IDs
            ->first();
        if (is_array($companyId)) {
            $firstCompanyID = $companyId[0]; // Get the first element from the array
        } else {
            $firstCompanyID = $companyId; // Handle case where it's not an array
        }



        // Determine if there are people found
        $peopleFound = $this->employees->count() > 0;
        $this->requests = collect();
        $options = [];
        $options = [];
        if (!empty($this->selectedEmployeeId)) {

            $this->allSalaryDetails = $this->getSalaryDetails();
            $options = [];
            $this->selectedEmployeeId;
            $empSalaryDetails = EmpSalary::join('salary_revisions', 'emp_salaries.sal_id', '=', 'salary_revisions.id')
                ->where('salary_revisions.emp_id', $this->selectedEmployeeId)
                ->where('month_of_sal', 'like', $this->selectedMonth . '%')
                ->where('emp_salaries.is_payslip', 1)
                ->first();

            $currentYear = date('Y');


            $lastMonth = date('n');



            for ($year = $currentYear; $year >= $currentYear - 1; $year--) {
                $startMonth = ($year == $currentYear) ? $lastMonth : 12; // Start from the current month or December
                $endMonth = ($year == $currentYear - 1) ? 1 : 1; // End at January

                for ($month = $startMonth; $month >= $endMonth; $month--) {
                    // Format the month to always have two digits
                    $monthPadded = sprintf('%02d', $month); // Adds leading zero to single-digit months
                    $dateObj = DateTime::createFromFormat('!m', $monthPadded);
                    $monthName = $dateObj->format('F');
                    $options["$year-$monthPadded"] = "$monthName $year";
                }
            }
            $this->empCompanyLogoUrl = $this->getEmpCompanyLogoUrl();

            if ($empSalaryDetails) {
                $this->salaryDivisions = $empSalaryDetails->calculateSalaryComponents($empSalaryDetails->salary);
                $this->empBankDetails = EmpBankDetail::where('emp_id', $this->selectedEmployeeId)
                    ->where('id', $empSalaryDetails->bank_id)->first();
                $this->employeePersonalDetails = EmpPersonalInfo::where('emp_id', $this->selectedEmployeeId)->first();
                $this->rupeesInText = $this->convertNumberToWords($this->salaryDivisions['net_pay']);
            } else {
                $this->salaryDivisions = [];
            }


            $this->employees = EmployeeDetails::select('employee_details.*', 'emp_departments.department')
                ->leftJoin('emp_departments', 'employee_details.dept_id', '=', 'emp_departments.dept_id')
                ->leftJoin('emp_personal_infos', 'employee_details.emp_id', '=', 'emp_personal_infos.emp_id')
                ->where('employee_details.emp_id', $this->selectedEmployeeId)
                ->first();

            // Debugging output
            Log::info('Fetched Letter Requests: ' . $this->requests->toJson());
        }


        return view('livewire.payslips', [
            'employees' => $this->employees,
            'selectedPeople' => $this->selectedPeople,
            'salaryRevision' => $this->salaryRevision,
            'peopleFound' => $peopleFound,
            'records' => $this->records,
            'combinedRequests' => $this->combinedRequests,
            'options' => $options,
            'requests' => $this->requests,
            'empCompanyLogoUrl' => $this->empCompanyLogoUrl,

        ]);
    }
}

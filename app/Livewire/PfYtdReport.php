<?php

namespace App\Livewire;

use App\Helpers\FlashMessageHelper;
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
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PfYtdReport extends Component
{
    use WithFileUploads;
    public $requests;
    public $salaryRevision;
    public $allSalaryDetails;
    public $empBankDetails;

    public $netPay;
    public $net_pay;
    public $showPopup = false;
    public $empCompanyLogoUrl ; 
    public $pdfUrl;
    public $empSalaryDetails;
    public $salaryDivisions;
    public $employeePersonalDetails;
    public $pdfPath;

    public $showConfDialog = false;
    public $filePath;
    public $selectedOption = 'all';
    public $searchTerm = '';
    public $peopleData = [];
    public $empId;

    public $selectedEmployeeId = '';

    public $employeeName;
    public $currentEmpId;


    public $searchEmployee;
    public $documents;
    public $selectedPeopleImages = [];

    public $selectedEmployeeFirstName;

    public $selectedEmployeeLastName;
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


    public $employeeDetails ;

    
    public $financialYears = [];
    public $selectedFinancialYear;
    public $salaryData = [];
    public $pfData = [];
    public $totals;
    public $pftotals;
    public $start_date;
    public $end_date;
  
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
        $this->empCompanyLogoUrl = $this->getEmpCompanyLogoUrl(); 

        $employeeId = auth()->user()->emp_id;


        $currentDate = now();
        $currentYear = $currentDate->year;
        $this->employeeDetails = EmployeeDetails::where('emp_id', $employeeId)->first(); // Assuming Employee model has hire_date

        $joiningDate = $this->employeeDetails->hire_date ? Carbon::parse($this->employeeDetails->hire_date) : now();
        // Adjust start year based on the joining date
        $startYear = $joiningDate->month < 4 ? $joiningDate->year - 1 : $joiningDate->year;

        // Only include the current financial year if the month is greater than March
        $includeCurrentYear = $currentDate->month > 3;
        $endYear = $includeCurrentYear ? $currentYear : $currentYear - 1;

        // Generate financial years
        for ($year = $startYear; $year <= $endYear; $year++) {
            $this->financialYears[] = [
                'label' => "Apr $year - Mar " . ($year + 1),
                'start_date' => "$year-04-01",
                'end_date' => ($year + 1) . "-03-31",
            ];
        }

        // Reverse financial years for descending order
        $this->financialYears = array_reverse($this->financialYears);

        // Set the default selected financial year
        $this->selectedFinancialYear = $this->financialYears[0]['start_date'] . '|' . $this->financialYears[0]['end_date'];

        // Fetch salary data
        $this->getSalaryData();

  
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
       
        $this->selectedEmployeeId ;
        // dd($empId);
        $employee = EmployeeDetails::find($empId);

        if ($employee) {
            $this->selectedEmployeeId = $employee->emp_id;
            $this->selectedEmployeeFirstName = ucfirst(strtolower($employee->first_name));
            $this->selectedEmployeeLastName = ucfirst(strtolower($employee->last_name));
            $this->searchTerm = ''; // Clears search term, but input retains full name
        }
        if (!empty($this->selectedEmployeeId)) {
         
            $this->allSalaryDetails = $this->getSalaryDetails();

            $this->selectedEmployeeId ;
           


            $this->employeeDetails = EmployeeDetails::select('employee_details.*', 'emp_departments.department')
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

    
    public function cancel()
    {
        $this->showPopup = false;
    }
    public $rupeesInText;
    public $salMonth;
    public $month;


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

    public function getSalaryDetails()
    {
        // $employeeId = auth()->user()->emp_id;

        // Querying the database directly using the DB facade
        $salaryDetails = DB::table('emp_salaries')
        ->join('salary_revisions', 'emp_salaries.sal_id', '=', 'salary_revisions.id')
        ->where('salary_revisions.emp_id', $this->selectedEmployeeId)
        ->select('emp_salaries.*') 
       // Ensure distinct months
        ->orderBy('emp_salaries.month_of_sal', 'desc')
        ->limit(1) // Get the latest 3 months
        ->get();
        
    


        // Group the results manually by the financial_year_start


        return  $salaryDetails;
    }
    public function clearSelectedEmployee()
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
    
    public function getSalaryData()
    {
        $this->selectedEmployeeId;

      
        list($startDate, $endDate) = explode('|', $this->selectedFinancialYear);
        $this->start_date = Carbon::parse($startDate)->format('Y');
        $this->end_date = Carbon::parse($endDate)->format('Y');
        $months = [];
        $currentMonth = Carbon::parse($startDate);
        while ($currentMonth <= Carbon::parse($endDate)) {
            $months[] = $currentMonth->format('Y-m');
            $currentMonth->addMonth();
        }


        $this->salaryRevision = EmpSalary::join('salary_revisions', 'emp_salaries.sal_id', '=', 'salary_revisions.id')
            ->where('salary_revisions.emp_id', $this->selectedEmployeeId)
            ->whereBetween('month_of_sal', [$startDate, $endDate])
            ->get()
            ->keyBy(function ($item) {
                return Carbon::parse($item->month_of_sal)->format('Y-m');
            });


        $this->salaryData = [];
        $this->pfData = [];
        $this->totals = [
            'basic' => 0,
            'hra' => 0,
            'conveyance' => 0,
            'medical_allowance' => 0,
            'special_allowance' => 0,
            'earnings' => 0,
            'gross' => 0,
            'pf' => 0,
            'esi' => 0,
            'professional_tax' => 0,
            'total_deductions' => 0,
            'net_pay' => 0,
            'working_days' => 0,
        ];
        $this->pftotals = [
            'basic' => 0,
            'pf' => 0,
            'vpf' => 0,
            'employeer_pf' => 0,
            'employeer_pension' => 0,
        ];

        foreach ($months as $month) {
            $currentDate = Carbon::now();
            $monthDate = Carbon::parse($month . '-01');

            if (isset($this->salaryRevision[$month])) {
                // Calculate salary components for the available month
                $salaryComponents = $this->salaryRevision[$month]->calculateSalaryComponents($this->salaryRevision[$month]->salary);
                $pfComponent = $this->salaryRevision[$month]->calculatePfComponents($this->salaryRevision[$month]->salary);
                $this->salaryData[$month] = $salaryComponents;
                $this->pfData[$month] = $pfComponent;

                // Add each component to the total
                foreach ($salaryComponents as $key => $value) {
                    if (isset($this->totals[$key])) {
                        $this->totals[$key] += is_numeric($value) ? $value : 0;
                    }
                }
                foreach ($pfComponent as $key => $value) {
                    if (isset($this->pftotals[$key])) {
                        $this->pftotals[$key] += is_numeric($value) ? $value : 0;
                    }
                }
            } elseif ($monthDate->isFuture()) {
                // For future dates, set all components to 0
                $this->salaryData[$month] = $this->zeroSalaryDetails();
                // $this->pfData[$month] = $this->zeroPfDetails();
            } elseif ($monthDate->isCurrentMonth()) {
                // For the current month, if no data, set all components to 0
                $this->salaryData[$month] = $this->zeroSalaryDetails();
                // $this->pfData[$month] = $this->zeroPfDetails();
            } else {
                // For missing past data, use empty salary details
                $this->salaryData[$month] = $this->emptySalaryDetails();
                $this->pfData[$month] = $this->emptyPfDetails();
            }
        }



        // Add total row to the salary data

        // dd($this->salaryData, $this->totals);

    }


    public function SelectedFinancialYear()
    {
        $this->getSalaryData();
    }
    public function emptySalaryDetails()
    {
        return [
            'basic' => '-',
            'hra' => '-',
            'conveyance' => '-',
            'medical_allowance' => '-',
            'special_allowance' => '-',
            'earnings' => '-',
            'gross' => '-',
            'pf' => '-',
            'esi' => '-',
            'professional_tax' => '-',
            'total_deductions' => '-',
            'net_pay' => '-',
            'working_days' => '-',
        ];
    }
    public function emptyPfDetails()
    {
        return [
            'basic' => '-',
            'pf' => '-',
            'vpf' => '-',
            'employeer_pf' => '-',
            'employeer_pension' => '-',
        ];
    }
    public function clearSelection()
    {
        $this->selectedEmployeeId = '';
        $this->selectedEmployeeFirstName = '';
        $this->selectedEmployeeLastName = '';
        $this->searchTerm = '';
    }
    public function zeroSalaryDetails()
    {
        return [
            'basic' => 0,
            'hra' => 0,
            'conveyance' => 0,
            'medical_allowance' => 0,
            'special_allowance' => 0,
            'earnings' => 0,
            'gross' => 0,
            'pf' => 0,
            'esi' => 0,
            'professional_tax' => 0,
            'total_deductions' => 0,
            'net_pay' => 0,
            'working_days' => 0,
        ];
    }
    public function downloadytd()
    {
      $this->selectedEmployeeId;
      if( $this->selectedEmployeeId){
       
// $width = (842 + 595) / 2;  // Midpoint of A3 and A4 width
            // $height = (1191 + 842) / 2; // Midpoint of A3 and A4 height
            $width = 840;
            $height = 1100;

            $this->empCompanyLogoUrl = $this->getEmpCompanyLogoUrl(); 

            $pdf = Pdf::loadView('download-pfytd-pdf', [
                'employees' => $this->employeeDetails,
                'empBankDetails' => $this->empBankDetails,
                'pfData' =>  $this->pfData,
                'pfTotals' => $this->pftotals,
                'startDate' => $this->start_date,
                'endDate' => $this->end_date,
                'empCompanyLogoUrl' => $this->empCompanyLogoUrl,
            ]);
            return response()->streamDownload(function () use ($pdf) {
                echo $pdf->stream();
            }, 'PF-YTD-Payslip-' . $this->start_date . '-' . $this->end_date . '.pdf');

            
      }


            

              

      
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
        if (!empty($this->selectedEmployeeId)) {
         
            $this->allSalaryDetails = $this->getSalaryDetails();
            try {
                $this->getSalaryData();
    
    
            } catch (\Exception $e) {
                $this->salaryData = collect();
            }

            $this->selectedEmployeeId ;
            $empSalaryDetails = EmpSalary::join('salary_revisions', 'emp_salaries.sal_id', '=', 'salary_revisions.id')
            ->where('salary_revisions.emp_id', $this->selectedEmployeeId)
           
            ->first();
         
    if ($empSalaryDetails) {
        $this->salaryDivisions = $empSalaryDetails->calculateSalaryComponents($empSalaryDetails->salary);
        $this->empBankDetails = EmpBankDetail::where('emp_id', $this->selectedEmployeeId)
            ->where('id', $empSalaryDetails->bank_id)->first();
        $this->employeePersonalDetails = EmpPersonalInfo::where('emp_id', $this->selectedEmployeeId)->first();
        $this->rupeesInText = $this->convertNumberToWords($this->salaryDivisions['net_pay']);
    } else {
        $this->salaryDivisions = [];
    }


            $this->employeeDetails = EmployeeDetails::select('employee_details.*', 'emp_departments.department')
                ->leftJoin('emp_departments', 'employee_details.dept_id', '=', 'emp_departments.dept_id')
                ->leftJoin('emp_personal_infos', 'employee_details.emp_id', '=', 'emp_personal_infos.emp_id')
                ->where('employee_details.emp_id', $this->selectedEmployeeId)
                ->first();

            // Debugging output
            Log::info('Fetched Letter Requests: ' . $this->requests->toJson());
        } 

        // Initialize the requests collection to prevent undefined errors
        $this->requests = LetterRequest::all();



        $query = EmployeeDocument::whereIn('employee_id', (array)$this->selectedEmployeeId)->orderBy('created_at', 'desc');


      


        $this->documents = $query->get();


        return view('livewire.pf-ytd-report', [
            'employees' => $this->employees,
            'selectedPeople' => $this->selectedPeople,
            'peopleFound' => $peopleFound,
            'records' => $this->records,
            'combinedRequests' => $this->combinedRequests,
            'requests' => $this->requests,
            'salaryData' => $this->salaryData,
           'empCompanyLogoUrl' => $this->empCompanyLogoUrl,
        ]);
    }
}

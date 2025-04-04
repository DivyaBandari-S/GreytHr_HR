<?php

namespace App\Livewire;

use App\Helpers\FlashMessageHelper;
use App\Models\EmployeeDetails;
use App\Models\EmpSalary;
use App\Models\EmpSalaryRevision;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class IncomeTax extends Component
{

  public $showDetails=true;
  public $selectedEmployeeId;
 public $isNames=false;
 public $allSalaryDetails=[];
 public $options=[];
      public $showSearch = false;
      public $employees;
      public $selectedEmployeeLastName;
      public $selectedEmployeeFirstName;
      public $empId;
      public $allEmployees=[];
      public $employeeIds=[];
      public $empSalaryDetails=[];
      public $salaryRevision;
     
      public $cc_to;
      public $searchTerm;
      public $selectedPeopleNames = [];
    public $selectedPeople = [];
    public $peoples;
    public $filteredPeoples;
    public $peopleData=[];
    public $selectedMonth;
      public $currentEmpId;
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
  public function changeMonth()
  {
      $this->selectedEmployeeId;

      $this->getSalaryDetails();

      $this->generateMonths(); // Refresh month options if necessary
  }


  public function NamesSearch()
  {
      $this->isNames = true;
      $this->selectedPeopleNames = [];
      $this->cc_to = '';
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
  public function closePeoples()
  {
      $this->isNames = false;
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

          $this->allEmployees = [];
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



   
      // Initialize other properties
      $this->peopleData = $this->filteredPeoples ? $this->filteredPeoples : $this->employees;
      $this->selectedPeople = [];
      $this->selectedPeopleNames = [];
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

       
      } 
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

    public function toggleDetails()
    {
        $this->showDetails = !$this->showDetails;
    }
    public function render()
    {
        return view('livewire.income-tax');
    }
}

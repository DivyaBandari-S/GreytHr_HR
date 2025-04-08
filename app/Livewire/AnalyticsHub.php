<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\EmployeeDetails;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect; 
use App\Helpers\FlashMessageHelper;

class AnalyticsHub extends Component
{
    public $selectedCard = 'Basic Information';
    // public $dynamicCards = [];
    // public $recentCards = [];
    public $genderCounts = [
        'Male' => 0,
        'Female' => 0,
        'Others' => 0,
    ];
    public $basicSearch = '';
    public $piSearch = '';
    public $allInfoSearch = '';
    public $genderSearch = '';
    public $resigneesSearch = '';
    public $filteredEmployees;
    public $filteredPI;
    public $filteredAllInfo;
    public $filteredGenderWise;
    public $filteredResignees;
    public $peopleFound = true;
    public $filteredGenderCounts = [];

    public function selectCard($card)
    {
        $this->selectedCard = $card;
        // if (!in_array($card, $this->recentCards)) {
        //     $this->recentCards[] = $card;
        //     if (count($this->recentCards) > 5) {
        //         array_shift($this->recentCards); // Remove the oldest
        //     }
        //     session()->put('recentCards', $this->recentCards);
        // }
        $this->basicSearch = '';
        $this->piSearch = '';
        $this->allInfoSearch = '';
        $this->genderSearch = '';
        $this->resigneesSearch = '';
        // return redirect()->route('analytics-hub', ['selectedCard' => $card]);
    }
    public function analyticsHubList()
    {
        return redirect()->route('analytics-hub-viewall'); 
    }
    public function filterBasicInformation(){
        $hrId = auth()->guard('hr')->user()->emp_id;
        $employee = EmployeeDetails::find($hrId);
        $empCompanyId = $employee->company_id;
        $employeeId = auth()->guard('hr')->user()->emp_id;

        // Fetch the company_ids for the logged-in employee
        $companyIds = EmployeeDetails::where('emp_id', $employeeId)->value('company_id');

        // Check if companyIds is an array; decode if it's a JSON string
        $companyIdsArray = is_array($companyIds) ? $companyIds : json_decode($companyIds, true);
        $this->filteredEmployees = EmployeeDetails::where(function($query) use ($companyIdsArray) {
            foreach ($companyIdsArray as $companyId) {
                $query->orWhereJsonContains('company_id', $companyId);
            }
        })
        ->where('employee_status', 'active')
        ->when($this->basicSearch, function ($query) {
            $query->where(function ($subQuery) {
                $subQuery->where('emp_id', 'like', "%{$this->basicSearch}%")
                          ->orWhere(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', "%{$this->basicSearch}%")
                          ->orWhere('gender', 'like', "%{$this->basicSearch}%")
                          ->orWhere('email', 'like', "%{$this->basicSearch}%");
            });
        })
        ->get();
        $this->peopleFound = count($this->filteredEmployees) > 0;
    }
    public function filterPersonalInformation(){
        $hrId = auth()->guard('hr')->user()->emp_id;
        $employee = EmployeeDetails::find($hrId);
        $empCompanyId = $employee->company_id;
        $employeeId = auth()->guard('hr')->user()->emp_id;

            // Fetch the company_ids for the logged-in employee
            $companyIds = EmployeeDetails::where('emp_id', $employeeId)->value('company_id');

            // Check if companyIds is an array; decode if it's a JSON string
            $companyIdsArray = is_array($companyIds) ? $companyIds : json_decode($companyIds, true);
       
        $this->filteredPI =  EmployeeDetails::with('empPersonalInfo')
        ->where(function($query) use ($companyIdsArray) {
            foreach ($companyIdsArray as $companyId) {
                $query->orWhereJsonContains('company_id', $companyId);
            }
        })
        ->where('employee_status','active')
        ->when($this->piSearch, function ($query) {
            $query->where(function ($subQuery) {
                $subQuery->where('emp_id', 'like', "%{$this->piSearch}%")
                          ->orWhere(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', "%{$this->piSearch}%")
                          ->orWhere('gender', 'like', "%{$this->piSearch}%");
            });
        })
        ->get();
        $this->peopleFound = count($this->filteredPI) > 0;
    }
    public function filterAllInfo(){
        $hrId = auth()->guard('hr')->user()->emp_id;
        $employee = EmployeeDetails::find($hrId);
        $empCompanyId = $employee->company_id;
        $employeeId = auth()->guard('hr')->user()->emp_id;

            // Fetch the company_ids for the logged-in employee
            $companyIds = EmployeeDetails::where('emp_id', $employeeId)->value('company_id');

            // Check if companyIds is an array; decode if it's a JSON string
            $companyIdsArray = is_array($companyIds) ? $companyIds : json_decode($companyIds, true);
        $this->filteredAllInfo = EmployeeDetails::with('empPersonalInfo')
        ->where(function($query) use ($companyIdsArray) {
            foreach ($companyIdsArray as $companyId) {
                $query->orWhereJsonContains('company_id', $companyId);
            }
        })
        ->where('employee_status','active')
        ->when($this->allInfoSearch, function ($query) {
            $query->where(function ($subQuery) {
                $subQuery->where('emp_id', 'like', "%{$this->allInfoSearch}%")
                          ->orWhere(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', "%{$this->allInfoSearch}%")
                          ->orWhere('gender', 'like', "%{$this->allInfoSearch}%")
                          ->orWhere('email', 'like', "%{$this->allInfoSearch}%");
            });
        })
        ->get();
        $this->peopleFound = count($this->filteredAllInfo) > 0;
    }
    public function filterGenderWise()
    {
        $hrId = auth()->guard('hr')->user()->emp_id;
        $employee = EmployeeDetails::find($hrId);
        $empCompanyId = $employee->company_id;
        $employeeId = auth()->guard('hr')->user()->emp_id;

            // Fetch the company_ids for the logged-in employee
            $companyIds = EmployeeDetails::where('emp_id', $employeeId)->value('company_id');

            // Check if companyIds is an array; decode if it's a JSON string
            $companyIdsArray = is_array($companyIds) ? $companyIds : json_decode($companyIds, true);
    
        // Get all gender counts
        $genderWiseCount = EmployeeDetails::where(function($query) use ($companyIdsArray) {
            foreach ($companyIdsArray as $companyId) {
                $query->orWhereJsonContains('company_id', $companyId);
            }
        })
            ->selectRaw('gender, COUNT(*) as count')
            ->groupBy('gender')
            ->where('employee_status', 'active');
    
        // Fetch gender data
        $genderWiseCount = $genderWiseCount->get();
    
        // Initialize gender counts
        $this->filteredGenderCounts = [
            'Male' => 0,
            'Female' => 0,
            'Others' => 0,
        ];
    
        // Populate counts based on actual data
        foreach ($genderWiseCount as $genderCount) {
            $standardizedGender = ucfirst(strtolower(trim($genderCount->gender)));
            // if (in_array($genderCount->gender, ['Male', 'Female'])) {
            //     $this->filteredGenderCounts[$genderCount->gender] += $genderCount->count;
            // } else {
            //     $this->filteredGenderCounts['Others'] += $genderCount->count;
            // }
            if (in_array($standardizedGender, ['Male', 'Female'])) {
                $this->filteredGenderCounts[$standardizedGender] += $genderCount->count;
            } else {
                $this->filteredGenderCounts['Others'] += $genderCount->count;
            }
        }
    
        // Filter based on the search term
        if ($this->genderSearch) {
            $searchTerm = strtolower(trim($this->genderSearch));
            $this->filteredGenderCounts = array_filter($this->filteredGenderCounts, function($count, $key) use ($searchTerm) {
                return (stripos($key, $searchTerm) !== false) || (strval($count) === $searchTerm);
            }, ARRAY_FILTER_USE_BOTH);
        }
    }
    
    
    public function mount()
{
    // $this->recentCards = session()->get('recentCards', []);
    $this->filterGenderWise(); // Load all data initially
    // $this->dynamicCards = session()->get('filenames', []);

}
public function filterResignees()
{
    try {
        $hrId = auth()->guard('hr')->user()->emp_id;
        $employee = EmployeeDetails::find($hrId);
        $empCompanyId = $employee->company_id;
        $employeeId = auth()->guard('hr')->user()->emp_id;

        // Fetch the company_ids for the logged-in employee
        $companyIds = EmployeeDetails::where('emp_id', $employeeId)->value('company_id');

        // Check if companyIds is an array; decode if it's a JSON string
        $companyIdsArray = is_array($companyIds) ? $companyIds : json_decode($companyIds, true);

        // Fetch filtered resignees based on criteria
        $this->filteredResignees = EmployeeDetails::with('empResignations')
            ->whereHas('empResignations', function ($query) {
                $lastMonthStart = Carbon::now()->subMonth()->startOfMonth();
                $currentDate = Carbon::now();
    
                $query->whereBetween('emp_resignations.last_working_day', [$lastMonthStart, $currentDate])
                      ->whereColumn('employee_details.emp_id', 'emp_resignations.emp_id');
            })
            ->where(function ($query) use ($companyIdsArray) {
                foreach ($companyIdsArray as $companyId) {
                    $query->orWhereJsonContains('company_id', $companyId);
                }
            })
            ->when($this->resigneesSearch, function ($query) {
                $query->where(function ($subQuery) {
                    $subQuery->where('emp_id', 'like', "%{$this->resigneesSearch}%")
                              ->orWhere(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', "%{$this->resigneesSearch}%")
                              ->orWhere('gender', 'like', "%{$this->resigneesSearch}%")
                              ->orWhere('status', 'like', "%{$this->resigneesSearch}%")
                              ->orWhere('manager_id', 'like', "%{$this->resigneesSearch}%");
                });
            })
            ->get();

        // Set the flag if any resignees are found
        $this->peopleFound = count($this->filteredResignees) > 0;
    } catch (\Exception $e) {
       

        // Flash an error message to the session (you can use a helper to handle this)
        FlashMessageHelper::flashError('error', 'An error occurred while filtering the resignees.');

        // Set the filteredResignees to an empty collection in case of an error
        $this->filteredResignees = collect();

        // Optionally, set peopleFound to false if no resignees are found
        $this->peopleFound = false;
    }
}

public function render()
{
    try {
        $hrId = auth()->guard('hr')->user()->emp_id;
        $employee = EmployeeDetails::find($hrId);
        $empCompanyId = $employee->company_id;
        $employeeId = auth()->guard('hr')->user()->emp_id;

        // Fetch the company_ids for the logged-in employee
        $companyIds = EmployeeDetails::where('emp_id', $employeeId)->value('company_id');

        // Check if companyIds is an array; decode if it's a JSON string
        $companyIdsArray = is_array($companyIds) ? $companyIds : json_decode($companyIds, true);

        // Fetch active employees
        $employees = EmployeeDetails::where(function($query) use ($companyIdsArray) {
            foreach ($companyIdsArray as $companyId) {
                $query->orWhereJsonContains('company_id', $companyId);
            }
        })
        ->where('employee_status', 'active')->get();

        // Fetch employees with personal information
        $employeesPersonal = EmployeeDetails::with('empPersonalInfo')
            ->where(function($query) use ($companyIdsArray) {
                foreach ($companyIdsArray as $companyId) {
                    $query->orWhereJsonContains('company_id', $companyId);
                }
            })
            ->where('employee_status', 'active')->get();

        // Fetch resignees (employees who resigned in the last month)
        $resigneeEmployees = EmployeeDetails::with('empResignations')
            ->whereHas('empResignations', function($query) {
                $lastMonthStart = Carbon::now()->subMonth()->startOfMonth();
                $currentDate = Carbon::now();

                $query->whereBetween('emp_resignations.last_working_day', [$lastMonthStart, $currentDate])
                    ->whereColumn('employee_details.emp_id', 'emp_resignations.emp_id');
            })
            ->where(function($query) use ($companyIdsArray) {
                foreach ($companyIdsArray as $companyId) {
                    $query->orWhereJsonContains('company_id', $companyId);
                }
            })
            ->get();

        // Assign data to variables based on filters or default values
        $employeesData = $this->filteredEmployees ? $this->filteredEmployees : $employees;
        $personalInformationData = $this->filteredPI ? $this->filteredPI : $employeesPersonal;
        $allInfoData = $this->filteredAllInfo ? $this->filteredAllInfo : $employeesPersonal;
        $genderWiseData = $this->filteredGenderWise ? $this->filteredGenderWise : $this->genderCounts;
        $resigneesData = $this->filteredResignees ? $this->filteredResignees : $resigneeEmployees;

        // Return the view with the data
        return view('livewire.analytics-hub', [
            'employeesData' => $employeesData,
            'personalInformationData' => $personalInformationData,
            'resigneesData' => $resigneesData,
            'allInfoData' => $allInfoData,
            'genderWiseData' => $this->filteredGenderCounts,
            // 'recentCards' => $this->recentCards, 
        ]);
    } catch (\Exception $e) {


        // Flash an error message to notify the user
        FlashMessageHelper::flashError('error', 'An error occurred while loading the data. Please try again later.');

        // Optionally, you can return the view with empty data to prevent breaking the UI
        return view('livewire.analytics-hub', [
            'employeesData' => collect(),
            'personalInformationData' => collect(),
            'resigneesData' => collect(),
            'allInfoData' => collect(),
            'genderWiseData' => collect(),
            // 'recentCards' => collect(),
        ]);
    }
}

    public function addEmployee()
    {
        return Redirect::route('add-employee-details');
    }
}

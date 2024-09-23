<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\EmployeeDetails;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsHub extends Component
{
    public $selectedCard = 'Basic Information';
    public $genderCounts;
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

    public function selectCard($card)
    {
        $this->selectedCard = $card;
        $this->basicSearch = '';
        $this->piSearch = '';
        $this->allInfoSearch = '';
        $this->genderSearch = '';
        $this->resigneesSearch = '';
    }
    public function analyticsHubList()
    {
        return redirect()->route('analytics-hub-viewall'); 
    }
    public function filterBasicInformation(){
        $hrId = auth()->guard('hr')->user()->emp_id;
        $employee = EmployeeDetails::find($hrId);
        $empCompanyId = $employee->company_id;
        $this->filteredEmployees = EmployeeDetails::where('company_id', $empCompanyId)
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
       
        $this->filteredPI =  EmployeeDetails::with('empPersonalInfo')
        ->where('company_id', $empCompanyId)
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
        $this->filteredAllInfo = EmployeeDetails::with('empPersonalInfo')
        ->where('company_id', $empCompanyId)
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
    public function filterGenderWise(){
        $hrId = auth()->guard('hr')->user()->emp_id;
        $employee = EmployeeDetails::find($hrId);
        $empCompanyId = $employee->company_id;
        $this->filteredGenderWise = EmployeeDetails::where('company_id', $empCompanyId)
        ->where('employee_status', 'active')
        ->when($this->genderSearch, function ($query) {
            $query->where(function ($subQuery) {
                $subQuery->where('emp_id', 'like', "%{$this->genderSearch}%")
                          ->orWhere(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', "%{$this->genderSearch}%")
                          ->orWhere('gender', 'like', "%{$this->genderSearch}%")
                          ->orWhere('email', 'like', "%{$this->genderSearch}%");
            });
        })
        ->get();
        $this->peopleFound = count($this->filteredGenderWise) > 0;
    }
    public function filterResignees(){
        $hrId = auth()->guard('hr')->user()->emp_id;
        $employee = EmployeeDetails::find($hrId);
        $empCompanyId = $employee->company_id;
        $this->filteredResignees = EmployeeDetails::with('empResignations')
        ->whereHas('empResignations', function($query) {
            $lastMonthStart = Carbon::now()->subMonth()->startOfMonth();
            $currentDate = Carbon::now();
    
            $query->whereBetween('emp_resignations.last_working_day', [$lastMonthStart, $currentDate])
                  ->whereColumn('employee_details.emp_id', 'emp_resignations.emp_id');
        })
        ->where('company_id', $empCompanyId)
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
        $this->peopleFound = count($this->filteredResignees) > 0;
    }
    public function render()
    {
        $hrId = auth()->guard('hr')->user()->emp_id;
        $employee = EmployeeDetails::find($hrId);
        $empCompanyId = $employee->company_id;

        $employees = EmployeeDetails::where('company_id', $empCompanyId)
        ->where('employee_status','active')->get();

        $employeesPersonal = EmployeeDetails::with('empPersonalInfo')
        ->where('company_id', $empCompanyId)
        ->where('employee_status','active')->get();

        $genderWiseCount =EmployeeDetails::where('company_id', $empCompanyId)
        ->selectRaw('gender, COUNT(*) as count')
    ->groupBy('gender')
        ->where('employee_status','active')->get();
        $this->genderCounts = [
            'Male' => 0,
            'Female' => 0,
            'Others' => 0, // This will be used if there are any unexpected gender entries
        ];

        foreach ($genderWiseCount as $genderCount) {
            if (in_array($genderCount->gender, ['Male', 'Female'])) {
                $this->genderCounts[$genderCount->gender] += $genderCount->count;
            } else {
                $this->genderCounts['Others'] += $genderCount->count;
            }
        }
 
    $resigneeEmployees = EmployeeDetails::with('empResignations')
    ->whereHas('empResignations', function($query) {
        $lastMonthStart = Carbon::now()->subMonth()->startOfMonth();
        $currentDate = Carbon::now();

        $query->whereBetween('emp_resignations.last_working_day', [$lastMonthStart, $currentDate])
              ->whereColumn('employee_details.emp_id', 'emp_resignations.emp_id');
    })
    ->where('company_id', $empCompanyId)
    ->get();


        $employeesData = $this->filteredEmployees ? $this->filteredEmployees : $employees;
        $personalInformationData = $this->filteredPI ? $this->filteredPI : $employeesPersonal;
        $allInfoData = $this->filteredAllInfo ? $this->filteredAllInfo : $employeesPersonal;
        $genderWiseData = $this->filteredGenderWise ? $this->filteredGenderWise : $this->genderCounts;
        $resigneesData = $this->filteredResignees ? $this->filteredResignees : $resigneeEmployees;


        return view('livewire.analytics-hub', ['employeesData' => $employeesData,
        'personalInformationData' => $personalInformationData,
        'resigneesData' => $resigneesData,
        'allInfoData' => $allInfoData,
        'genderWiseData' => $genderWiseData,
    ]);
    }
}

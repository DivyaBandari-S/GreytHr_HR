<?php

namespace App\Livewire;

use App\Models\EmployeeDetails;
use App\Models\Hr;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AdminDashboard extends Component
{
    public $show= false;
    public $totalEmployeeCount;
    public $totalNewEmployeeCount;
    public $totalNewEmployees;
    public $labels;
    public $data;
    public $departmentCount;
    public $colors;
    public $maleCount = 0;
    public $femaleCount = 0;
    public $employeeCountsByLocation;
    public $loginEmployee;
    public $activeTab = 'summary';
    public function setActiveTab($tab)
    {
            $this->activeTab = $tab;
    }


    public function mount()
    {
        try {
            $this->setActiveTab($this->tab);
            $employeeId = auth()->guard('hr')->user()->emp_id;
            $this->loginEmployee = Hr::where('emp_id',$employeeId)->select('emp_id', 'employee_name')->first();
            $companyId = Auth::user()->company_id;
            // Count total employees
            $this->totalEmployeeCount = EmployeeDetails::where('company_id', $companyId)->count();

            // Get total employees grouped by location
            $this->employeeCountsByLocation = EmployeeDetails::select('job_location', DB::raw('count(*) as count'))
                ->where('company_id', $companyId)
                ->groupBy('job_location')
                ->get();

            // Count new employees for the current year
            $this->totalNewEmployees = EmployeeDetails::where('company_id', $companyId)
                ->whereYear('hire_date', Carbon::now()->year)
                ->get();
            $this->totalNewEmployeeCount = $this->totalNewEmployees->count();

            $departmentNames = [];
            // Check if $newEmployees is not empty
            if ($this->totalNewEmployees->isNotEmpty()) {
                foreach ($this->totalNewEmployees as $employee) {
                    $departmentNames[] = $employee->department;
                }
                $uniqueDepartments = array_unique($departmentNames);

                $this->departmentCount = count($uniqueDepartments);
            } else {
                $this->departmentCount = 0;
            }

            // Get gender distribution for the company
            $genderDistribution = EmployeeDetails::select('gender', DB::raw('count(*) as count'))
                ->where('company_id', $companyId)
                ->groupBy('gender')
                ->get();

            $this->labels = $genderDistribution->pluck('gender');
            $this->data = $genderDistribution->pluck('count');
            $this->colors = [
                'Male' => 'rgb(255, 99, 132)',
                'Female' => 'rgb(54, 162, 235)',
                'Not Active' => 'rgb(255, 205, 86)'
            ];

            // Loop through the gender distribution data to calculate male and female counts
            foreach ($genderDistribution as $distribution) {
                if ($distribution->gender === 'Male') {
                    $maleCount = $distribution->count;
                } elseif ($distribution->gender === 'Female') {
                    $femaleCount = $distribution->count;
                }
            }

            $this->maleCount = $maleCount ?? 0;
            $this->femaleCount = $femaleCount ?? 0;
        } catch (\Exception $e) {
            if ($e instanceof \Illuminate\Database\QueryException) {
                // Handle database query exceptions
                Log::error("Database error registering employee: " . $e->getMessage());
                session()->flash('emp_error', 'Database connection error occurred. Please try again later.');
            }
            elseif (strpos($e->getMessage(), 'Call to a member function store() on null') !== false) {
                // Display a user-friendly error message for null image
                session()->flash('emp_error', 'Please upload an image.');
            }elseif ($e instanceof \Illuminate\Http\Client\RequestException) {
                // Handle network request exceptions
                Log::error("Network error registering employee: " . $e->getMessage());
                session()->flash('emp_error', 'Network error occurred. Please try again later.');
            } elseif ($e instanceof \PDOException) {
                // Handle database connection errors
                Log::error("Database connection error registering employee: " . $e->getMessage());
                session()->flash('emp_error', 'Database connection error. Please try again later.');
            } else {
                // Handle other generic exceptions
                Log::error("Error registering employee: " . $e->getMessage());
                session()->flash('emp_error', 'Failed to register employee. Please try again later.');
            }
            // Redirect the user back to the registration page or any other appropriate action
            return redirect()->back();
        }
    }


    public function open(){
        $this->show= true;
    }
    public function render()
    {
        return view('livewire.admin-dashboard',[
            'departmentCount' => $this->departmentCount,
        ]);
    }
}

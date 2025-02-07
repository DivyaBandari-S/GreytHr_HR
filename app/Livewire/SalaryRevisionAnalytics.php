<?php

namespace App\Livewire;

use App\Models\EmpDepartment;
use App\Models\EmployeeDetails;
use App\Models\EmpSalaryRevision;
use Carbon\Carbon;
use Livewire\Component;

class SalaryRevisionAnalytics extends Component
{
    public $isShowHelp = true;


    public $minMonths = 1;
    public $maxMonths = 30;
    public $startDate;
    public $endDate;
    public $tableData;
    public $medianRevision;
    public $longestRevision;


    public $employeeIds = []; // Optional array of employee IDs to filter

    public $filteredEmployees;
    public $selectedRange = [1, 30]; // Default selected range
    public $activeTab = 'not_revised';
    protected $listeners = ['updateRange'];



    public function changeTab($tab)
    {
        $this->activeTab = $tab;
        $this->filterEmployees();
    }
    public function updateRange($range)
    {
// dd();
        $this->selectedRange = [$range[0], $range[1]];
        $this->minMonths = $range[0];
        $this->maxMonths = $range[1];
        $this->filterEmployees();
        // Additional logic here if needed
    }

    public function toogleHelp()
    {
        $this->isShowHelp = !$this->isShowHelp;
    }

    public function mount()
    {
        $this->employeeIds = EmployeeDetails::whereIn('employee_status', ['active', 'on-probation'])
            ->pluck('emp_id')
            ->toArray(); // Converts the result to an array


        $this->filterEmployees();
    }

    public function filterEmployees()
    {
        // dd();
        $this->filteredEmployees=[];
        $this->startDate = Carbon::now()->subMonths($this->minMonths-1); // Start date (N months ago)
        $this->endDate = Carbon::now()->subMonths($this->maxMonths); // End date (30 months ago)

        if ($this->activeTab === 'revised') {
            // Get employees with revisions in the dynamic date range
            $this->filteredEmployees = EmpSalaryRevision::whereIn('emp_id', $this->employeeIds)
                ->whereBetween('revision_date', [$this->endDate, $this->startDate])
                ->distinct()
                ->pluck('emp_id');
        } else {
            $this->filteredEmployees = collect($this->employeeIds)->diff(
                EmpSalaryRevision::whereBetween('revision_date', [$this->endDate, $this->startDate])
                    ->distinct()
                    ->pluck('emp_id')
            )->values();
        }

        // dd( $this->filteredEmployees);
        $this->getPreparedTableData();
    }
    public function getPreparedTableData()
    {
        if (!$this->filteredEmployees->isEmpty()) {
            $this->tableData = [];
            $revisionPeriods = [];
            $longestRevisionPeriods = [];

            foreach ($this->filteredEmployees as $employee) {


                    $employeeLastRevision = EmpSalaryRevision::where('emp_id', $employee)
                        ->whereBetween('revision_date', [$this->endDate, $this->startDate]) // Filter within date range
                        ->latest('created_at') // Order by latest first
                        ->get()
                        ->groupBy('emp_id') // Group by emp_id
                        ->map(fn($records) => $records->first()) // Get only the latest record for each emp_id
                        ->values();


                // Fetch employee details
                $empDetails = EmployeeDetails::where('emp_id', $employee)->first(['emp_id', 'first_name', 'last_name', 'job_role', 'hire_date', 'dept_id']);
                $empDepartment = EmpDepartment::where('dept_id', $empDetails->dept_id)->value('department');

                $experience = $empDetails->hire_date ? EmpSalaryRevision::calculateExperience($empDetails->hire_date) : 0;
                $emp_name = trim("{$empDetails->first_name} {$empDetails->last_name}");
                $emp_role = $empDetails->job_role ?? '';
// dd($this->startDate, $this->endDate);
                // Get all revisions to calculate longest and median revision period
                $employeeRevisions = EmpSalaryRevision::where('emp_id', $employee)
                ->whereBetween('revision_date', [$this->endDate,$this->startDate]) // Filter by date range
                ->orderBy('revision_date', 'asc')
                ->pluck('revision_date');
                    // dd( $employeeRevisions);

                $this->longestRevision = '-';
                if ($employeeRevisions->count() > 1) {
                    $periods = [];
                    for ($i = 1; $i < $employeeRevisions->count(); $i++) {
                        $prevDate = Carbon::parse($employeeRevisions[$i - 1]);
                        $currDate = Carbon::parse($employeeRevisions[$i]);
                        $diffMonths = $prevDate->diffInMonths($currDate);
                        $periods[] = $diffMonths;
                        $revisionPeriods[] = $diffMonths; // Store for median calculation
                    }
                    $longestRevisionPeriods[]=max($periods) ;
                    $this->longestRevision = max($longestRevisionPeriods);
                }

                if (!$employeeLastRevision->isEmpty()) {
                    $current_ctc = $employeeLastRevision[0]->current_ctc ?? 0;
                    $revised_ctc = $employeeLastRevision[0]->revised_ctc ?? 0;
                    $revision_date = $employeeLastRevision[0]->revision_date ?? '-';
                    $difference_amount = $revised_ctc - $current_ctc;
                    $percentage_change = $current_ctc != 0 ? round((($revised_ctc - $current_ctc) / $current_ctc) * 100, 2) : 100;
                } else {
                    $current_ctc = '0';
                    $revised_ctc = '0';
                    $revision_date = '-';
                    $difference_amount = '0';
                    $percentage_change = '0';
                }

                $this->tableData[] = [
                    'emp_id' => $empDetails->emp_id,
                    'current_ctc' => $current_ctc,
                    'revised_ctc' => $revised_ctc,
                    'designation' => $emp_role,
                    'revision_date' => $revision_date,
                    'percentage_change' => $percentage_change,
                    'difference_amount' => $difference_amount,
                    'experience' => $experience,
                    'emp_name' => $emp_name,
                    'department' => $empDepartment,
                ];
            }
            // Calculate Median Revision Period
            if (!empty($revisionPeriods)) {
                sort($revisionPeriods);
                $count = count($revisionPeriods);
                $this->medianRevision = ($count % 2 == 0)
                    ? ($revisionPeriods[$count / 2 - 1] + $revisionPeriods[$count / 2]) / 2
                    : $revisionPeriods[floor($count / 2)];
            } else {
                $this->medianRevision = '-';

            }

// dd( $this->medianRevision,$this->longestRevision);
        } else {
            $this->tableData = [];
            $this->medianRevision = '-';
            $this->longestRevision = '-';
        }
    }

    public function render()
    {
        return view('livewire.salary-revision-analytics');
    }
}

<?php

namespace App\Livewire;

use App\Exports\EmployeePeersExport;
use App\Helpers\FlashMessageHelper;
use App\Models\EmployeeDetails;
use App\Models\EmpSalaryRevision;
use App\Models\SalaryRevision;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Queue\Listener;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Session;

class SalaryRevisionHistory extends Component
{
    public $search = '';
    public $peerSearch = '';

    public $employeeType = 'active'; // Default to 'active'
    public $employees = [];
    public $showContainer = false;
    public $viewDetails = false;
    public $selectedEmployee = null;
    public $mainEmp_id = null;

    public $showSearch = true;
    public $salaryRevisions;
    public $salaryComponentDetails;
    public $decryptedData = [];
    public $empDetails;
    public $effectiveDate;
    public $remarks;
    public $chartData;
    protected $listeners = ['getChartData'];
    public  $showPage1 = true;
    public  $selectedDate;
    public $selected_current_ctc;
    public $selected_revised_ctc;
    public $new_revised_ctc;
    public $ctc_percentage;
    public $reason;
    public $notes;
    public $isNewRevised = false;
    public $last_revision_date;
    public $last_revised_ctc;
    public $isNoDataFound = false;
    public $isPeersModal = false;
    public $comparisionData = [];
    public $allEmployees = [];
    public $selectedEmployees = [];
    public $selectedEmployeesPeers;
    public $selectedEmployeesPeersData;
    public $isShowHelp = true;
    public function toogleHelp()
    {
        $this->isShowHelp = !$this->isShowHelp;
    }


    public function removePeersEmployee($emp_id)
    {
        if (in_array($emp_id, $this->selectedEmployees)) {
            // Remove employee from selected list
            $this->selectedEmployees = array_values(array_filter($this->selectedEmployees, fn($id) => $id !== $emp_id));
        }

        $this->getSelectedEmployees();
    }

    public function exportToExcel()
    {

        $this->dispatch('getChartData');
        if (empty($this->selectedEmployeesPeersData)) {
            session()->flash('');
            FlashMessageHelper::flashError('No data available for export.');
            return;
        }

        return Excel::download(new EmployeePeersExport($this->selectedEmployeesPeersData), 'employee_peers.xlsx');
    }

    public function showPeersModal()
    {
        $this->peerSearch = "";
        $this->isPeersModal = true;
        $this->getEmployeesList();
        // dd($this->allEmployees);
    }
    public function getEmployeesList()
    {
        $query = EmployeeDetails::whereIn('employee_status', ['active', 'on-probation'])
            ->where('emp_id', '!=', $this->selectedEmployee);

        // Apply search filter if $peerSearch is not empty
        if (!empty($this->peerSearch)) {
            $query->where(function ($q) {
                $q->where('emp_id', 'like', "%{$this->peerSearch}%")
                    ->orWhere('first_name', 'like', "%{$this->peerSearch}%")
                    ->orWhere('last_name', 'like', "%{$this->peerSearch}%")
                    ->orWhere('email', 'like', "%{$this->peerSearch}%");
            });
        }

        $this->allEmployees = $query->get(['emp_id', 'first_name', 'last_name', 'email']);
    }

    public function hidePeersModal()
    {
        $this->dispatch('getChartData');
        $this->isPeersModal = false;
    }

    public function prepareSelectedEmployeesPeersData()
    {
        $this->selectedEmployeesPeersData = [];
        foreach ($this->selectedEmployeesPeers as $peers) {

            $emp_id = $peers->emp_id;
            $emp_details = EmployeeDetails::where('emp_id', $emp_id)
                ->first(['first_name', 'last_name', 'job_role', 'hire_date']);
            // dd( $emp_details);
            $current_ctc = $peers->current_ctc;
            $revised_ctc = $peers->revised_ctc;
            $revision_date = $peers->revision_date;
            $difference_amount = $revised_ctc - $current_ctc;
            $experience = 0;
            $emp_name = '';
            $emp_role = '';
            if ($emp_details->job_role) {

                $emp_role = $emp_details->job_role;
            }

            if ($emp_details->first_name || $emp_details->last_name) {
                $emp_name = $emp_details->first_name . ' ' . $emp_details->last_name;
            }

            if ($emp_details->hire_date) {
                $experience = EmpSalaryRevision::calculateExperience($emp_details->hire_date);
            }

            $percentage_chance = ($current_ctc > 0)
                ? round((($revised_ctc -  $current_ctc) /  $current_ctc) * 100, 2)
                : 0;

            $this->selectedEmployeesPeersData[] = [
                'emp_id' => $emp_id,
                'current_ctc' => $current_ctc,
                'revised_ctc' => $revised_ctc,
                'designation' => $emp_role,
                'revision_date' => $revision_date,
                'percentage_change' => $percentage_chance,
                'difference_amount' => $difference_amount,
                'experience' => $experience,
                'emp_name' => $emp_name,
            ];
        }
        // dd($this->selectedEmployeesPeersData);
    }

    public function toggleEmployee($empId)
    {
        if (in_array($empId, $this->selectedEmployees)) {
            // Remove employee from selected list
            $this->selectedEmployees = array_values(array_filter($this->selectedEmployees, fn($id) => $id !== $empId));
        } else {
            // Add employee to selected list
            $this->selectedEmployees[] = $empId;
        }
    }
    public function getSelectedEmployees()
    {

        $this->selectedEmployeesPeers = EmpSalaryRevision::whereIn('emp_id', $this->selectedEmployees)
            ->latest('created_at') // Order by latest first
            ->get()
            ->groupBy('emp_id') // Group by emp_id
            ->map(fn($records) => $records->first()) // Get only the latest record for each emp_id
            ->values(); // Reset keys

        $this->prepareSelectedEmployeesPeersData();
        $this->isPeersModal = false;
        $this->dispatch('getChartData');
    }





    public function saveSalaryRevision()
    {
        if ($this->new_revised_ctc == '' || $this->new_revised_ctc < 0) {
            FlashMessageHelper::flashError('The salary components should have at least one value greater than Zero');
            return;
        }
        $this->last_revision_date = $this->decryptedData[0]['revision_date'];
        if (Carbon::parse($this->effectiveDate)->format('Y-m-d') <= Carbon::parse($this->last_revision_date)->format('Y-m-d')) {
            FlashMessageHelper::flashError('The Effective Date you have selected overlaps with already existing revision for the selected Employee.');
            return;
        }
        // dd($this->last_revised_ctc);

        if ($this->selectedEmployee) {
            $revision = EmpSalaryRevision::create([
                'emp_id' => $this->selectedEmployee,
                'current_ctc' => $this->last_revised_ctc,
                'revised_ctc' => $this->new_revised_ctc,
                'revision_date' => $this->effectiveDate,
                'revision_type' => $this->notes,
                'reason' => $this->remarks,
            ]);
            FlashMessageHelper::flashSuccess('Salary Revision created Successfully! for' . $this->selectedEmployee);
            $this->dispatch('getChartData');
            $this->showPage1 = true;
            $this->isNewRevised = false;
            $this->getSalaryData();
        };
    }



  
    public function showRevisedSalary()
    {
        return redirect('/hr/user/salary-revision');
    }
    // {
    //     $this->showPage1 = !$this->showPage1;
    //     if ($this->showPage1) {
    //         $this->dispatch('getChartData');
    //         $this->isNewRevised = false;
    //         $this->getSalaryData();
    //     } else {
    //         $this->selectedDate = $this->decryptedData[0]['revision_date'];
    //         $this->selected_current_ctc = $this->decryptedData[0]['current_ctc'];
    //         $this->selected_revised_ctc = $this->decryptedData[0]['revised_ctc'];
    //         $this->last_revised_ctc = $this->decryptedData[0]['revised_ctc'];
    //         $this->effectiveDate = Carbon::parse($this->decryptedData[0]['revision_date'])->format('Y-m-d');

    //         if ($this->selected_revised_ctc == 0) {
    //             $this->new_revised_ctc = '';
    //         } else {
    //             $this->new_revised_ctc = $this->selected_revised_ctc;
    //         }
    //         $this->comparisionData = EmpSalaryRevision::getComparisionData($this->selected_current_ctc, $this->selected_revised_ctc);
    //         $this->ctc_percentage = $this->comparisionData['percentage_change'];

    //     }

    // }
    public function showModal($sal, $payoutmonth, $remarks)
    {

        $this->effectiveDate = $payoutmonth;
        $this->remarks = $remarks;
        $this->salaryComponentDetails = EmpSalaryRevision::calculateSalaryComponents($sal);

        $this->viewDetails = true;
        $this->dispatch('getChartData');
    }

    public function hideDetailsModal()
    {
        $this->viewDetails = false;
        $this->dispatch('getChartData');
    }

    public function filterEmployeeType()
    {
        $this->loadEmployees(); // Reload employees when type changes
    }
    public function loadEmployees()
    {

        if ($this->employeeType === 'active') {
            $this->employees = EmployeeDetails::whereIn('employee_status', ['active', 'on-probation'])->get();
        } else {
            $this->employees = EmployeeDetails::whereIn('employee_status', ['terminated', 'resigned'])->get();
        }
    }

    public function searchFilter()
    {
        if ($this->search !== '') {
            $this->showContainer = true; // Show the container when the search is triggered

            // Search with the existing term
            $this->employees = EmployeeDetails::where(function ($query) {
                $query->where('first_name', 'like', '%' . $this->search . '%')
                    ->orWhere('last_name', 'like', '%' . $this->search . '%')
                    ->orWhere('emp_id', 'like', '%' . $this->search . '%'); // Ensure `like` for partial match
            })
                ->where(function ($query) {
                    if ($this->employeeType === 'active') {
                        $query->whereIn('employee_status', ['active', 'on-probation']);
                    } else {
                        $query->whereIn('employee_status', ['terminated', 'resigned']);
                    }
                })
                ->get();

            // If no results found, the container should still be shown to display the message

        } else {
            // If search term is empty, hide the container and reload the employees
            $this->showContainer = false; // Hide the container
            $this->loadEmployees(); // Reload current employees
        }
    }

    public function mount()
    {
        $selectedEmployee = session('selectedEmployee', null);
        $this->selectEmployee($selectedEmployee);
    }

    public function selectEmployee($employeeId)
    {
        Session::put('selectedEmployee', $employeeId);
        // Check if the employee is already selected
        $this->selectedEmployee = $employeeId; // Change here
        $this->mainEmp_id = $employeeId;
        $this->selectedEmployees[] = $employeeId;
        Log::info('Selected Employee: ', [$this->selectedEmployee]);
        if (is_null($this->selectedEmployee)) {
            $this->showSearch = true;
            $this->isNoDataFound = false;
            $this->search = '';
            $this->decryptedData = [];
            $this->selectedEmployees = [];
            session()->forget('selectedEmployee');

            // $this->showContainer = true;
            // $this->showSearch = false;
            $this->selectedEmployee = null;
            $this->empDetails = [];
        } else {
            $this->showSearch = false;
            $this->showContainer = false;

            $this->getSalaryData();
            $this->getEmpDetails();
        }
    }
    public function getEmpDetails()
    {
        $this->empDetails = DB::table('employee_details')
            ->join('emp_departments', 'emp_departments.dept_id', '=', 'employee_details.dept_id')
            ->where('employee_details.emp_id', $this->selectedEmployee)
            ->select('emp_departments.department', 'employee_details.job_role', 'employee_details.hire_date', 'employee_details.emp_id', 'employee_details.job_role')
            ->first();
    }
    public function getSalaryData()
    {
        $this->decryptedData = [];
        $this->salaryRevisions = EmpSalaryRevision::where('emp_id',   $this->selectedEmployee)
            ->orderBy('created_at', 'asc')
            ->get();

        if (count($this->salaryRevisions) != 0) {
            $previous_revision_date = null;
            $time_gaps = [];
            $previous_revised_ctc = 0;
            $difference_amount = 0;
            $percentage_change_diff = 0;

            foreach ($this->salaryRevisions as $revision) {
                $current_ctc = $revision->current_ctc;
                // dd($revision->current_ctc);
                $revised_ctc = intval($revision->revised_ctc);
                $revision_date = Carbon::parse($revision->revision_date);
                $revision_type = $revision->revision_type;
                $reason = $revision->reason;

                $time_gap = 0;

                if ($previous_revision_date !== null) {
                    $time_diff_days = $previous_revision_date->diffInDays($revision_date);
                    $time_gap = $this->formatDuration($time_diff_days);
                    $time_gaps[] = $time_diff_days;
                } else {
                    $time_diff_days = $revision_date->diffInDays(Carbon::now());
                    $time_gap = $this->formatDuration($time_diff_days);
                    $time_gaps[] = $time_diff_days;
                }
                if ($previous_revised_ctc !== 0) {
                    $difference_amount = $revised_ctc - $previous_revised_ctc; // Current revised_ctc - Previous revised_ctc
                }
                if ($previous_revised_ctc !== 0) {
                    $difference_amount = $revised_ctc - $previous_revised_ctc; // Current revised_ctc - Previous revised_ctc
                    $percentage_change_diff = round((($revised_ctc - $previous_revised_ctc) / $previous_revised_ctc) * 100, 2); // Percentage change from previous revised_ctc
                }

                $this->decryptedData[] = [
                    'current_ctc' => $current_ctc,
                    'revised_ctc' => $revised_ctc,
                    'difference_amount' => $difference_amount,
                    'percentage_change_diff' => $percentage_change_diff,
                    'revision_date' => $revision_date,
                    'revision_type' => $revision_type,
                    'reason' => $reason,
                    'time_gap' => $time_gap,
                    'remarks' => $revision->reason,
                    'revision_id' => $revision->id
                ];
                $previous_revision_date = $revision_date;
                $previous_revised_ctc = $revised_ctc;
                // dd( $previous_revised_ctc);
            }

            $this->decryptedData = array_reverse($this->decryptedData);

            $max_revision_period = max($time_gaps);  // Maximum time gap
            sort($time_gaps);
            $count = count($time_gaps);
            $middle = floor($count / 2);
            $median_revision_period = ($count % 2 == 0)
                ? ($time_gaps[$middle - 1] + $time_gaps[$middle]) / 2
                : $time_gaps[$middle];

            // Add the calculated periods to decryptedData
            foreach ($this->decryptedData as $key => &$revision) {
                $revision['medium_revision_period'] = $this->formatDuration($median_revision_period);
                $revision['max_revision_period'] = $this->formatDuration($max_revision_period);
            }
            // dd($this->decryptedData);
            $this->getChartData();
            $this->getSelectedEmployees();
        } else {
            $this->isNoDataFound = true;
        }
    }
    private function formatDuration($time_diff_days)
    {
        $years = floor($time_diff_days / 365);
        $remaining_days = $time_diff_days % 365;

        $months = floor($remaining_days / 30);
        $days = $remaining_days % 30;

        $duration = [];
        if ($years > 0) {
            $duration[] = "$years year" . ($years > 1 ? 's' : '');
        }
        if ($months > 0) {
            $duration[] = "$months month" . ($months > 1 ? 's' : '');
        }
        if ($days > 0) {
            $duration[] = "$days day" . ($days > 1 ? 's' : '');
        }

        return implode(', ', $duration);
    }

    public function getChartData()
    {

        $dates = [];
        $revisedSalaries = [];

        foreach ($this->decryptedData as $data) {
            $dates[] = $data['revision_date']->format('d M, Y'); // Format the date as desired
            $revisedSalaries[] = round($data['revised_ctc'], 2);
        }
        $dates = array_reverse($dates);
        $revisedSalaries = array_reverse($revisedSalaries);

        $this->chartData = [
            'labels' => $dates,
            'data' => $revisedSalaries,
        ];

        $this->dispatch('update-chart', ['chartData' => $this->chartData]);
    }
    public function render()
    {
        // dd($this->empDetails);
        $selectedEmployeesDetails = $this->selectedEmployee ?
            EmployeeDetails::where('emp_id', $this->selectedEmployee)->get() : [];


        // dd("ok", $this->empDetails);
        return view('livewire.salary-revision-history', [
            'selectedEmployeesDetails' => $selectedEmployeesDetails,
        ]);
    }
}

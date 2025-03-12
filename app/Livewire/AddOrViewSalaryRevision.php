<?php

namespace App\Livewire;

use App\Helpers\FlashMessageHelper;
use App\Models\EmployeeDetails;
use App\Models\EmpSalaryRevision;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Illuminate\Support\Facades\Session;

class AddOrViewSalaryRevision extends Component
{
    public $isShowHelp = true;
    public $showSearch = true;
    public $showContainer = false;
    public $search = '';
    public $employeeType = 'active';
    public $empDetails;
    public $employees = [];
    public $selectedEmployee;
    public $isNoDataFound = false;
    public $decryptedData = [];
    public $salaryRevisions;
    public $last_revision_date;
    public $isNewRevised = false;
    public $comparisionData = [];
    public $new_revised_ctc;
    public $ctc_percentage;
    public  $selectedDate;
    public $reason;
    public $notes;
    public $effectiveDate;
    public $selected_revised_ctc;
    public $selected_current_ctc;
    public $last_revised_ctc;
    public $payout_month;
    public $months;
    public $last_payout_month;
    public $currentPayoutMonth;
    public $end_payout_month;
    public $previous_url;



    public function toogleHelp()
    {
        $this->isShowHelp = !$this->isShowHelp;
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

    public function loadEmployees()
    {
        if ($this->employeeType === 'active') {
            $this->employees = EmployeeDetails::whereIn('employee_status', ['active', 'on-probation'])->get();
        } else {
            $this->employees = EmployeeDetails::whereIn('employee_status', ['terminated', 'resigned'])->get();
        }
    }
    public function hideContainer()
    {
        $this->showSearch = true;
        $this->showContainer = false;
    }

    public function selectEmployee($employeeId)
    {

        // Check if the employee is already selected
        $this->selectedEmployee = $employeeId; // Change here
        Session::put('selectedEmployee', $employeeId);
        Log::info('Selected Employee: ', [$this->selectedEmployee]);
        if (is_null($this->selectedEmployee)) {
            $this->showSearch = true;
            $this->isNewRevised = false;
            $this->search = '';
            $this->selectedEmployee = null;
            $this->new_revised_ctc = '';
            $this->ctc_percentage = '';
            $this->reason = null;
            $this->notes = null;
            $this->selected_revised_ctc = 0;
        } else {

            $this->showSearch = false;
            $this->showContainer = false;
            $this->getSalaryData();
            $this->getEmpDetails();
            $this->showRevisedSalary();
        }
    }

    public function mount()
    {
        // $this->selectedEmployee = 'xss-0481';
        $this->currentPayoutMonth = Carbon::parse(now())->format('M Y');
        $selectedEmployee = session('selectedEmployee', null);
        $this->selectEmployee($selectedEmployee);
        // dd(session()->all());
        $this->previous_url = session('previous_url', url('/'));
    }

    public function getEmpDetails()
    {
        $this->empDetails = DB::table('employee_details')
            ->join('emp_departments', 'emp_departments.dept_id', '=', 'employee_details.dept_id')
            ->where('employee_details.emp_id', $this->selectedEmployee)
            ->select('emp_departments.department', 'employee_details.job_role', 'employee_details.hire_date', 'employee_details.emp_id', 'employee_details.job_role')
            ->first();
        // dd($this->empDetails);
    }
    public function getSalaryData()
    {

        $this->decryptedData = [];
        $this->salaryRevisions = EmpSalaryRevision::where('emp_id',   $this->selectedEmployee)
            ->orderBy('created_at', 'asc')
            ->get();
        // dd( $this->salaryRevisions);

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
                    'payout_month' => $revision->payout_month,
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
        } else {

            $this->isNoDataFound = true;
        }
    }

    public function saveSalaryRevision()
    {
        if ($this->new_revised_ctc == '' || $this->new_revised_ctc <= 0) {
            FlashMessageHelper::flashError('The salary components should have at least one value greater than Zero');
            return;
        }

        if (count($this->salaryRevisions) != 0) {
            $this->last_payout_month = $this->decryptedData[0]['payout_month'];

            // Convert to Carbon instances
            $effectiveDate = Carbon::parse($this->effectiveDate)->format('Y-m');
            $payoutMonth = Carbon::parse($this->payout_month)->format('Y-m');
            $lastPayoutMonth = Carbon::parse($this->last_payout_month)->format('Y-m');

            // Validation: Effective Date should be after the last Payout Month
            if ($effectiveDate <= $lastPayoutMonth) {
                FlashMessageHelper::flashError('The Effective Date overlaps with an existing payout month for the selected Employee.');
                return;
            }
            // Validation: Payout Month should be after the last Payout Month
            if ($payoutMonth <= $lastPayoutMonth) {
                FlashMessageHelper::flashError('The Payout Month overlaps with an existing payout month for the selected Employee.');
                return;
            }
        }

        $effectiveDate = Carbon::createFromFormat('Y-m', $effectiveDate);
        $payoutMonth = Carbon::createFromFormat('Y-m', $payoutMonth);
        // dd($effectiveDate, $payoutMonth);
        if ($effectiveDate->gt($payoutMonth)) {
            FlashMessageHelper::flashError('The Payout Month should be greater than or equal to Effective Month');
            return;
        }

        $this->payout_month=Carbon::parse($this->payout_month)->format('Y-m');
        // dd( $this->payout_month);
        if ($this->selectedEmployee) {
            $revision = EmpSalaryRevision::create([
                'emp_id' => $this->selectedEmployee,
                'current_ctc' => $this->last_revised_ctc,
                'revised_ctc' => $this->new_revised_ctc,
                'revision_date' => $this->effectiveDate,
                'payout_month' => $this->payout_month,
                'revision_type' => $this->notes,
                'reason' => $this->reason,
                'status'=>0,
            ]);
            FlashMessageHelper::flashSuccess('Salary Revision created Successfully! for' . ' ' . $this->selectedEmployee);
            // $this->dispatch('getChartData');
            // $this->isNewRevised = false;
            // $this->getSalaryData();
            return redirect(request()->header('Referer'));
        };
    }
    public function getPercentageRevisedSalary()
    {
        if ($this->isNewRevised) {
            if ($this->ctc_percentage != '') {
                // dd($this->ctc_percentage);
                $this->comparisionData = EmpSalaryRevision::getPercentageWiseRevisedSalary($this->selected_revised_ctc, $this->ctc_percentage);
                $this->new_revised_ctc = $this->comparisionData['revised']['annual_ctc'];
                // dd(  $this->new_revised_ctc);
            }
        }
    }
    public function getNewRevisedSalary()
    {
        // dd($this->isNewRevised);
        if ($this->isNewRevised) {
            if ($this->new_revised_ctc != '') {
                // dd($this->selected_revised_ctc);
                $this->comparisionData = EmpSalaryRevision::getComparisionData($this->selected_revised_ctc, $this->new_revised_ctc);
                $this->ctc_percentage = $this->comparisionData['percentage_change'];
            } else {
                $this->comparisionData = EmpSalaryRevision::getComparisionData($this->selected_revised_ctc, 0);
                $this->ctc_percentage = '';
            }
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

    public function selectRevision($date, $current_ctc, $revised_ctc, $reason, $notes, $payout_month)
    {

        $this->selectedDate = $date;
        $this->reason = $reason;
        $this->notes = $notes;
        if ($revised_ctc == 0) {
            $this->new_revised_ctc = '';
        } else {
            $this->new_revised_ctc = $revised_ctc;
        }

        if ($date == null) {
            $this->isNewRevised = true;
            $this->effectiveDate = now()->startOfMonth()->format('Y-m-d');
        } else {
            $this->effectiveDate = Carbon::parse($date)->format('Y-m-d');
            // dd( $this->effectiveDate);
            $this->isNewRevised = false;

            // dd($this->isNewRevised);
        }
        $this->payout_month = trim($payout_month);
        // $this->months = EmpSalaryRevision::generatePayoutMonths($this->payout_month);
        $this->comparisionData = EmpSalaryRevision::getComparisionData($current_ctc, $revised_ctc);
        $this->ctc_percentage = $this->comparisionData['percentage_change'];
        if ($date == null) {
            $this->ctc_percentage = '';
        }
    }

    public function showRevisedSalary()
    {
        if (count($this->salaryRevisions) != 0) {
            $this->selectedDate = $this->decryptedData[0]['revision_date'];
            $this->selected_current_ctc = $this->decryptedData[0]['current_ctc'];
            $this->selected_revised_ctc = $this->decryptedData[0]['revised_ctc'];
            $this->last_revised_ctc = $this->decryptedData[0]['revised_ctc'];
            $this->payout_month = Carbon::parse($this->decryptedData[0]['payout_month'])->format('M Y');
            $this->end_payout_month =Carbon::parse(end($this->decryptedData)['payout_month'])->format('M Y');
            // dd(  $this->payout_month);
            $this->last_payout_month = Carbon::parse($this->decryptedData[0]['payout_month'])->format('M Y');
            $this->effectiveDate = Carbon::parse($this->decryptedData[0]['revision_date'])->format('Y-m-d');

            if ($this->selected_revised_ctc == 0) {
                $this->new_revised_ctc = '';
            } else {
                $this->new_revised_ctc = $this->selected_revised_ctc;
            }
            $this->comparisionData = EmpSalaryRevision::getComparisionData($this->selected_current_ctc, $this->selected_revised_ctc);
            $this->ctc_percentage = $this->comparisionData['percentage_change'];
        } else {
            $this->comparisionData = EmpSalaryRevision::getComparisionData(0, 0);
            $this->isNewRevised = true;
            $this->effectiveDate = now()->startOfMonth()->format('Y-m-d');
            $this->payout_month = $this->currentPayoutMonth;
            $this->last_revised_ctc = 0;
        }
        $this->generatePayoutMonths();
    }

    public function generatePayoutMonths()
    {
        // Debugging to check if payout_month and end_payout_month are set correctly
        // dd($this->end_payout_month, $this->payout_month);

        // Ensure last_payout_month and payout_month are set
        $effectiveLastPayout = $this->end_payout_month
            ? Carbon::createFromFormat('M Y', $this->end_payout_month)
            : now();

        $effectivePayout = $this->payout_month
            ? Carbon::createFromFormat('M Y', $this->payout_month)
            : now();

        // Clone to prevent modification
        $months = collect();
        // dd( $months);

        // 6 months before last_payout_month
        for ($i = 6; $i > 0; $i--) {
            $months->push($effectiveLastPayout->copy()->subMonths($i)->format('M Y'));
        }


        // Include months between last_payout_month and payout_month (inclusive)
        if ($effectivePayout->greaterThan($effectiveLastPayout)) {
            $months->push($effectiveLastPayout->format('M Y'));

            // Safeguard: Add months between last_payout_month and payout_month (inclusive)
            for ($date = $effectiveLastPayout->copy(); $date->lessThanOrEqualTo($effectivePayout); $date->addMonth()) {
                $months->push($date->format('M Y'));
            }
        } else {
            $months->push($effectivePayout->format('M Y'));
        }

        // 6 months after payout_month
        for ($i = 0; $i <= 6; $i++) {
            $months->push($effectivePayout->copy()->addMonths($i)->format('M Y'));
        }

        // Assign to class property
        $this->months = $months->toArray();

        // Debugging to check the generated months
        // dd($this->months);
    }

    public function goBack()
    {
        return redirect($this->previous_url);
    }

    public function render()
    {
        $selectedEmployeesDetails = $this->selectedEmployee ?
            EmployeeDetails::where('emp_id', $this->selectedEmployee)->get() : [];

        return view('livewire.add-or-view-salary-revision', [
            'selectedEmployeesDetails' => $selectedEmployeesDetails,
        ]);
    }
}


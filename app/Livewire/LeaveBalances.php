<?php
// File Name                       : LeaveBalances.php
// Description                     : This file contains the implementation displaying leave balance and consumed leaves for each type of leaves
// Creator                         : Bandari Divya
// Email                           : bandaridivya1@gmail.com
// Organization                    : PayG.
// Date                            : 2024-03-07
// Framework                       : Laravel (10.10 Version)
// Programming Language            : PHP (8.1 Version)
// Database                        : MySQL
// Models                          : LeaveRequest,EmployeeDetails.
namespace App\Livewire;

use App\Helpers\FlashMessageHelper;
use Illuminate\Support\Carbon;
use Livewire\Component;
use App\Helpers\LeaveHelper;
use App\Models\EmployeeDetails;
use App\Models\EmployeeLeaveBalances;
use App\Models\HolidayCalendar;
use App\Models\LeaveRequest;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;

class LeaveBalances extends Component
{
    public $employeeDetails;
    public $casualLeavePerYear;
    public $casualProbationLeavePerYear;
    public $year;
    public $lossOfPayPerYear;
    public $marriageLeaves;
    public $maternityLeaves;
    public $paternityLeaves;
    public $sickLeavePerYear;
    public $sickLeaveBalance;
    public $casualLeaveBalance;
    public $casualProbationLeaveBalance;
    public $lossOfPayBalance;
    public $leaveTransactions;
    public $leaveTypeModal = 'all';
    public $transactionTypeModal = 'all';
    public $employeeId;
    public $leave_status;
    public $fromDateModal;
    public $toDateModal;
    public $leaveType;
    public $transactionType;
    public $consumedSickLeaves;
    public $percentageMarriageLeaves;
    public $percentagePaternityLeaves;
    public $consumedCasualLeaves;
    public $consumedLossOfPayLeaves;
    public $gender;
    public $consumedProbationLeaveBalance;
    public $sortBy = 'newest_first';
    public $selectedYear;
    public $totalCasualDays;
    public $totalSickDays;
    public $totalLossOfPayDays;
    public $totalCasualLeaveProbationDays;
    public $previousYear;
    public $nextYear;
    public $currentYear;
    public $beforePreviousYear;
    public $percentageCasual;
    public $percentageSick;
    public $percentageCasualProbation, $differenceInMonths;
    public $showCasualLeaveProbation, $showCasualLeaveProbationYear;
    public $consumedMarriageLeaves;
    public $consumedMaternityLeaves;
    public $consumedPaternityLeaves;
    public $showModal = false;
    public $hideCasualLeave;
    public $dateErrorMessage;
    protected $rules = [
        'fromDateModal' => 'required|date',
        'toDateModal' => 'required|date|after_or_equal:fromDateModal',
    ];
    protected $messages = [
        'fromDateModal.required' => 'From date is required',
        'toDateModal.required' => 'To date is required',
    ];
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);

        if ($this->toDateModal < $this->fromDateModal) {
            $this->dateErrorMessage = 'To date must be greater than to From date.';
        } else {
            $this->dateErrorMessage = null;
        }
    }

    //in this method will get leave balance for each type
    public function mount()
    {
        try {
            $this->selectedYear = Carbon::now()->format('Y'); // Initialize to the current year
            $this->updateLeaveBalances();
            $employeeId = auth()->guard('emp')->user()->emp_id;
            $this->employeeDetails = EmployeeDetails::where('emp_id', $employeeId)->first();
            $this->showCasualLeaveProbation = $this->employeeDetails && empty($this->employeeDetails->confirmation_date);
            $this->hideCasualLeave = $this->employeeDetails && !empty($this->employeeDetails->confirmation_date);
            $currentYear = date('Y');
            $this->showCasualLeaveProbationYear = $this->employeeDetails &&
                !empty($this->employeeDetails->confirmation_date) &&
                (date('Y', strtotime($this->employeeDetails->confirmation_date)) == $currentYear);
        } catch (\Exception $e) {
            // Set an error message in the session
            FlashMessageHelper::flashError('An error occurred while loading the component. Please try again later.');
        }
    }
    public function updatedSelectedYear($value)
    {
        // Debugging output
        logger()->info('Selected Year Updated: ' . $value);
        $this->updateLeaveBalances();
    }

    public $marriageLeaveBalance, $maternityLeaveBalance, $paternityLeaveBalance;

    private function updateLeaveBalances()
    {
        try {
            $this->currentYear = $this->selectedYear;
            $this->employeeId = auth()->guard('emp')->user()->emp_id;
            $this->employeeDetails = EmployeeDetails::where('emp_id', $this->employeeId)->first();
            $this->fromDateModal = Carbon::createFromDate($this->currentYear, 1, 1)->format('Y-m-d');
            $this->toDateModal = Carbon::createFromDate($this->currentYear, 12, 31)->format('Y-m-d');

            if ($this->employeeDetails) {
                $hireDate = $this->employeeDetails->hire_date;
                if ($hireDate) {
                    $hireDate = Carbon::parse($hireDate);
                    $currentDate = Carbon::now();
                    $this->differenceInMonths = $hireDate->diffInMonths($currentDate);
                } else {
                    $this->differenceInMonths = null;
                }

                $this->sickLeavePerYear = EmployeeLeaveBalances::getLeaveBalancePerYear($this->employeeId, 'Sick Leave', $this->currentYear);
                $this->lossOfPayPerYear = EmployeeLeaveBalances::getLeaveBalancePerYear($this->employeeId, 'Loss Of Pay', $this->currentYear);
                $this->casualLeavePerYear = EmployeeLeaveBalances::getLeaveBalancePerYear($this->employeeId, 'Casual Leave', $this->currentYear);
                $this->casualProbationLeavePerYear = EmployeeLeaveBalances::getLeaveBalancePerYear($this->employeeId, 'Casual Leave Probation', $this->currentYear);
                $this->maternityLeaves = EmployeeLeaveBalances::getLeaveBalancePerYear($this->employeeId, 'Maternity Leave', $this->currentYear);
                $this->paternityLeaves = EmployeeLeaveBalances::getLeaveBalancePerYear($this->employeeId, 'Paternity Leave', $this->currentYear);
                $leaveBalances = LeaveHelper::getApprovedLeaveDays($this->employeeId, $this->selectedYear);
                $this->totalCasualDays = $leaveBalances['totalCasualDays'];
                $this->totalSickDays = $leaveBalances['totalSickDays'];
                $this->totalCasualLeaveProbationDays = $leaveBalances['totalCasualLeaveProbationDays'];
                $this->totalLossOfPayDays = $leaveBalances['totalLossOfPayDays'];
                $this->marriageLeaves = EmployeeLeaveBalances::getLeaveBalancePerYear($this->employeeId, 'Marriage Leave', $this->currentYear);

                // Retrieve the lapsed status for Sick Leave
                $toggleLapsedData = EmployeeLeaveBalances::where('emp_id', $this->employeeId)
                    ->where('period', 'like', "%$this->selectedYear%")
                    ->first();
                if ($toggleLapsedData && $toggleLapsedData->is_lapsed) {
                    // If lapsed, set the balance directly to leavePerYeacr
                    $this->sickLeaveBalance = 0;
                    $this->casualLeaveBalance = 0;
                    $this->casualProbationLeaveBalance = 0;
                    $this->marriageLeaveBalance = 0;
                    $this->maternityLeaveBalance = 0;
                    $this->paternityLeaveBalance = 0;
                    $this->consumedCasualLeaves = $this->casualLeavePerYear - $this->casualLeaveBalance;
                    $this->consumedSickLeaves = $this->sickLeavePerYear - $this->sickLeaveBalance;
                    $this->consumedProbationLeaveBalance = $this->casualProbationLeavePerYear - $this->casualProbationLeaveBalance;
                    $this->consumedMarriageLeaves = $this->marriageLeaves - $this->marriageLeaveBalance;
                    $this->consumedMaternityLeaves = $this->maternityLeaves - $this->maternityLeaveBalance;
                    $this->consumedPaternityLeaves  = $this->paternityLeaves - $this->paternityLeaveBalance;
                } else {
                    // Otherwise, apply the deduction logic
                    $this->sickLeaveBalance = ($this->sickLeavePerYear ?? 0) - ($this->totalSickDays ?? 0);
                    $this->casualLeaveBalance = ($this->casualLeavePerYear ?? 0) - ($this->totalCasualDays ?? 0);
                    $this->casualProbationLeaveBalance = ($this->casualProbationLeavePerYear ?? 0) - ($this->totalCasualLeaveProbationDays ?? 0);
                    $this->marriageLeaveBalance = ($this->marriageLeaves ?? 0) - ($leaveBalances['totalMarriageDays'] ?? 0);
                    $this->maternityLeaveBalance = ($this->maternityLeaves ?? 0) - ($leaveBalances['totalMaternityDays'] ?? 0);
                    $this->paternityLeaveBalance = ($this->paternityLeaves ?? 0) - ($leaveBalances['totalPaternityDays'] ?? 0);
                    $this->consumedCasualLeaves = $this->casualLeavePerYear - $this->casualLeaveBalance;
                    $this->consumedSickLeaves = $this->sickLeavePerYear - $this->sickLeaveBalance;
                    $this->consumedProbationLeaveBalance = $this->casualProbationLeavePerYear - $this->casualProbationLeaveBalance;
                    $this->consumedMarriageLeaves = $this->marriageLeaves - $this->marriageLeaveBalance;
                    $this->consumedMaternityLeaves = $this->maternityLeaves - $this->maternityLeaveBalance;
                    $this->consumedPaternityLeaves  = $this->paternityLeaves - $this->paternityLeaveBalance;
                }
            }
        } catch (\Exception $e) {
            FlashMessageHelper::flashError('An error occurred while loading the component. Please try again later.');
        }
    }

    //this method will show increment color a color if leave are consumed,
    protected function getTubeColor($consumedLeaves, $leavePerYear, $leaveType)
    {
        try {
            // Check if $leavePerYear is greater than 0 to avoid division by zero
            if ($leavePerYear > 0) {
                $percentage = ($consumedLeaves / $leavePerYear) * 100;
                // Define color thresholds based on the percentage consumed and leave type
                switch ($leaveType) {
                    case 'Sick Leave':
                        return $this->getSickLeaveColor($percentage);
                    case 'Casual Leave Probation':
                        return $this->getSickLeaveColor($percentage);
                    case 'Casual Leave':
                        return $this->getSickLeaveColor($percentage);
                    case 'Marriage Leave':
                        return $this->getSickLeaveColor($percentage);
                    case 'Paternity Leave':
                        return $this->getSickLeaveColor($percentage);
                    case 'Maternity Leave':
                        return $this->getSickLeaveColor($percentage);
                    default:
                        return '#000000';
                }
            } else {
                return '#0ea8fc';
            }
        } catch (\Exception $e) {
            FlashMessageHelper::flashError('An error occured while getting leave balance.');
            return '#000000';
        }
    }


    protected function getSickLeaveColor($percentage)
    {
        return 'rgb(11 25 87)';
    }

    //this method will fetch the oldest nd newest  data
    public function checkSortBy()
    {
        $employeeId = auth()->guard('emp')->user()->emp_id;
        $this->employeeDetails = EmployeeDetails::where('emp_id', $employeeId)->first();
        $query = LeaveRequest::join('employee_details', 'leave_applications.emp_id', '=', 'employee_details.emp_id')
            ->select('leave_applications.*', 'employee_details.*', 'leave_applications.created_at as leave_created_at')
            ->where('leave_applications.emp_id', $employeeId);
        if ($this->sortBy == 'oldest_first') {
            $query->orderBy('leave_created_at', 'asc');
        } else {
            $query->orderBy('leave_created_at', 'desc');
        }
        $this->leaveTransactions = $query->get();
    }
    public function showPopupModal()
    {
        $this->showModal = true;
        $this->leaveType = 'all';
        $this->transactionType = 'all';
    }
    public function closeModal()
    {
        $this->showModal = false;
        $this->updateLeaveBalances();
        $this->resetFields();
    }

    public function render()
    {
        try {

            $employeeId = auth()->guard('emp')->user()->emp_id;
            if ($this->casualLeavePerYear > 0) {
                $this->percentageCasual = ($this->consumedCasualLeaves / $this->casualLeavePerYear) * 100;
            }
            if ($this->sickLeavePerYear > 0) {
                $this->percentageSick = ($this->consumedSickLeaves / $this->sickLeavePerYear) * 100;
            }
            if ($this->casualProbationLeavePerYear > 0) {
                $this->percentageCasualProbation = ($this->consumedProbationLeaveBalance / $this->casualProbationLeavePerYear) * 100;
            }
            if ($this->marriageLeaves > 0) {
                $this->percentageMarriageLeaves = ($this->consumedMarriageLeaves / $this->marriageLeaves) * 100;
            }
            if ($this->paternityLeaves > 0) {
                $this->percentagePaternityLeaves= ($this->consumedPaternityLeaves / $this->paternityLeaves) * 100;
            }


            $this->yearDropDown();
            // Check if employeeDetails is not null before accessing its properties
            $this->employeeDetails = EmployeeDetails::where('emp_id', $employeeId)->first();

            //to check employee details are not null
            if ($this->employeeDetails) {
                $this->gender = $this->employeeDetails->gender;
                $leaveBalances = LeaveBalances::getLeaveBalances($employeeId, $this->selectedYear);
                return view('livewire.leave-balances', [
                    'gender' => $this->gender,
                    'employeeDetails' => $this->employeeDetails,
                    'leaveTransactions' => $this->leaveTransactions,
                    'percentageCasual' => $this->percentageCasual,
                    'percentageSick' => $this->percentageSick,
                    'differenceInMonths' => $this->differenceInMonths,
                    'percentageCasualProbation' => $this->percentageCasualProbation
                ]);
            }
        } catch (\Exception $e) {
            FlashMessageHelper::flashError('error occurred. Please try again later.'); // Flash an error message to the user
            return redirect()->back(); // Redirect back to the previous page
        }
    }


    public static function getLeaveBalances($employeeId, $selectedYear)
    {
        try {
            $selectedYear = now()->year;
            $employeeDetails = EmployeeDetails::where('emp_id', $employeeId)->first();
            $sickLeavePerYear = EmployeeLeaveBalances::getLeaveBalancePerYear($employeeId, 'Sick Leave', $selectedYear);
            $lossOfPayPerYear = EmployeeLeaveBalances::getLeaveBalancePerYear($employeeId, 'Loss Of Pay', $selectedYear);
            $casualLeavePerYear = EmployeeLeaveBalances::getLeaveBalancePerYear($employeeId, 'Casual Leave', $selectedYear);
            $casualProbationLeavePerYear = EmployeeLeaveBalances::getLeaveBalancePerYear($employeeId, 'Casual Leave Probation', $selectedYear);
            $marriageLeaves = EmployeeLeaveBalances::getLeaveBalancePerYear($employeeId, 'Marriage Leave', $selectedYear);
            $maternityLeaves = EmployeeLeaveBalances::getLeaveBalancePerYear($employeeId, 'Maternity Leave', $selectedYear);
            $paternityLeaves = EmployeeLeaveBalances::getLeaveBalancePerYear($employeeId, 'Petarnity Leave', $selectedYear);

            if (!$employeeDetails) {
                return null;
            }

            // Get the logged-in employee's approved leave days for all leave types
            $approvedLeaveDays = LeaveHelper::getApprovedLeaveDays($employeeId, $selectedYear);

            // Retrieve the lapsed status for Sick Leave
            $toggleLapsedData = EmployeeLeaveBalances::where('emp_id', $employeeId)
                ->where('is_lapsed', true)
                ->where('period', 'like', "%$selectedYear%")
                ->first();
            if ($toggleLapsedData && $toggleLapsedData->is_lapsed) {
                // If lapsed, set the balance directly to leavePerYear
                $sickLeaveBalance = 0;
                $casualLeaveBalance = 0;
                $casualProbationLeaveBalance = 0;
                $marriageLeaveBalance = 0;
                $maternityLeaveBalance = 0;
                $paternityLeaveBalance = 0;
                $lossOfPayBalance = $lossOfPayPerYear - $approvedLeaveDays['totalLossOfPayDays'];
            } else {
                // Otherwise, apply the deduction logic
                $sickLeaveBalance = $sickLeavePerYear - $approvedLeaveDays['totalSickDays'];
                $casualLeaveBalance = $casualLeavePerYear - $approvedLeaveDays['totalCasualDays'];
                $casualProbationLeaveBalance = $casualProbationLeavePerYear - $approvedLeaveDays['totalCasualLeaveProbationDays'];
                $marriageLeaveBalance = $marriageLeaves - $approvedLeaveDays['totalMarriageDays'];
                $maternityLeaveBalance = $maternityLeaves - $approvedLeaveDays['totalMaternityDays'];
                $paternityLeaveBalance = $paternityLeaves - $approvedLeaveDays['totalPaternityDays'];
                $lossOfPayBalance = $lossOfPayPerYear - $approvedLeaveDays['totalLossOfPayDays'];
            }
            return [
                'sickLeaveBalance' => $sickLeaveBalance,
                'casualLeaveBalance' => $casualLeaveBalance,
                'lossOfPayBalance' => $lossOfPayBalance,
                'casualProbationLeaveBalance' => $casualProbationLeaveBalance,
                'marriageLeaveBalance' => $marriageLeaveBalance,
                'maternityLeaveBalance' => $maternityLeaveBalance,
                'paternityLeaveBalance' => $paternityLeaveBalance,
            ];
        } catch (\Exception $e) {
            if ($e instanceof \Illuminate\Database\QueryException) {
                // Handle database query exceptions
                FlashMessageHelper::flashError('Database connection error occurred. Please try again later.');
            } else {
                FlashMessageHelper::flashError('Failed to retrieve leave balances. Please try again later.');
            }
            return null;
        }
    }
    public static function getLeaveLapsedBalances($employeeId, $selectedYear)
    {
        try {
            $selectedYear = now()->year;
            $employeeDetails = EmployeeDetails::where('emp_id', $employeeId)->first();
            $sickLeavePerYear = EmployeeLeaveBalances::getLeaveBalancePerYear($employeeId, 'Sick Leave', $selectedYear);
            $lossOfPayPerYear = EmployeeLeaveBalances::getLeaveBalancePerYear($employeeId, 'Loss Of Pay', $selectedYear);
            $casualLeavePerYear = EmployeeLeaveBalances::getLeaveBalancePerYear($employeeId, 'Casual Leave', $selectedYear);
            $casualProbationLeavePerYear = EmployeeLeaveBalances::getLeaveBalancePerYear($employeeId, 'Casual Leave Probation', $selectedYear);
            $marriageLeaves = EmployeeLeaveBalances::getLeaveBalancePerYear($employeeId, 'Marriage Leave', $selectedYear);
            $maternityLeaves = EmployeeLeaveBalances::getLeaveBalancePerYear($employeeId, 'Maternity Leave', $selectedYear);
            $paternityLeaves = EmployeeLeaveBalances::getLeaveBalancePerYear($employeeId, 'Petarnity Leave', $selectedYear);

            if (!$employeeDetails) {
                return null;
            }

            // Get the logged-in employee's approved leave days for all leave types
            $approvedLeaveDays = LeaveHelper::getApprovedLeaveDays($employeeId, $selectedYear);



            // Retrieve the lapsed status for Sick Leave
            $toggleLapsedData = EmployeeLeaveBalances::where('emp_id', $employeeId)
                ->where('is_lapsed', true)
                ->where('period', 'like', "%$selectedYear%")
                ->first();

            if ($toggleLapsedData && $toggleLapsedData->is_lapsed) {
                // If lapsed, set the balance directly to leavePerYear
                $sickLeaveBalance = $sickLeavePerYear - $approvedLeaveDays['totalSickDays'];
                $casualLeaveBalance = $casualLeavePerYear - $approvedLeaveDays['totalCasualDays'];
                $casualProbationLeaveBalance = $casualProbationLeavePerYear - $approvedLeaveDays['totalCasualLeaveProbationDays'];
                $marriageLeaveBalance = $marriageLeaves - $approvedLeaveDays['totalMarriageDays'];
                $maternityLeaveBalance = $maternityLeaves - $approvedLeaveDays['totalMaternityDays'];
                $paternityLeaveBalance = $paternityLeaves - $approvedLeaveDays['totalPaternityDays'];
                $lossOfPayBalance = $lossOfPayPerYear - $approvedLeaveDays['totalLossOfPayDays'];
            } else {
                // Otherwise, apply the deduction logic


                $sickLeaveBalance = 0;
                $casualLeaveBalance = 0;
                $casualProbationLeaveBalance = 0;
                $marriageLeaveBalance = 0;
                $maternityLeaveBalance = 0;
                $paternityLeaveBalance = 0;
                $lossOfPayBalance = $lossOfPayPerYear - $approvedLeaveDays['totalLossOfPayDays'];
            }
            return [
                'sickLeaveBalance' => $sickLeaveBalance,
                'casualLeaveBalance' => $casualLeaveBalance,
                'lossOfPayBalance' => $lossOfPayBalance,
                'casualProbationLeaveBalance' => $casualProbationLeaveBalance,
                'marriageLeaveBalance' => $marriageLeaveBalance,
                'maternityLeaveBalance' => $maternityLeaveBalance,
                'paternityLeaveBalance' => $paternityLeaveBalance,
            ];
        } catch (\Exception $e) {
            if ($e instanceof \Illuminate\Database\QueryException) {
                // Handle database query exceptions
                FlashMessageHelper::flashError('Database connection error occurred. Please try again later.');
            } else {
                FlashMessageHelper::flashError('Failed to retrieve leave balances. Please try again later.');
            }
            return null;
        }
    }


    //calcalate number of days for leave
    public function calculateNumberOfDays($fromDate, $fromSession, $toDate, $toSession, $leaveType)
    {
        try {
            $startDate = Carbon::parse($fromDate);
            $endDate = Carbon::parse($toDate);

            // Fetch holidays between the fromDate and toDate
            $holidays = HolidayCalendar::whereBetween('date', [$startDate, $endDate])->get();

            // Check if the start or end date is a weekend for non-Marriage leave
            if (!in_array($leaveType, ['Marriage Leave', 'Sick Leave', 'Maternity Leave', 'Paternity Leave']) && ($startDate->isWeekend() || $endDate->isWeekend())) {
                return 0;
            }

            // Check if the start and end sessions are different on the same day
            if (
                $startDate->isSameDay($endDate) &&
                $this->getSessionNumber($fromSession) === $this->getSessionNumber($toSession)
            ) {
                // Inner condition to check if both start and end dates are weekdays (for non-Marriage leave)
                if (!in_array($leaveType, ['Marriage Leave', 'Sick Leave', 'Maternity Leave', 'Paternity Leave']) && !$startDate->isWeekend() && !$endDate->isWeekend() && !$this->isHoliday($startDate, $holidays) && !$this->isHoliday($endDate, $holidays)) {
                    return 0.5;
                } else {
                    return 0.5;
                }
            }

            if (
                $startDate->isSameDay($endDate) &&
                $this->getSessionNumber($fromSession) !== $this->getSessionNumber($toSession)
            ) {
                // Inner condition to check if both start and end dates are weekdays (for non-Marriage leave)
                if (!in_array($leaveType, ['Marriage Leave', 'Sick Leave', 'Maternity Leave', 'Paternity Leave']) && !$startDate->isWeekend() && !$endDate->isWeekend() && !$this->isHoliday($startDate, $holidays) && !$this->isHoliday($endDate, $holidays)) {
                    return 1;
                } else {
                    return 1;
                }
            }

            $totalDays = 0;

            while ($startDate->lte($endDate)) {
                // For non-Marriage leave type, skip holidays and weekends, otherwise include weekdays
                if (!in_array($leaveType, ['Marriage Leave', 'Sick Leave', 'Maternity Leave', 'Paternity Leave'])) {
                    if (!$this->isHoliday($startDate, $holidays) && $startDate->isWeekday()) {
                        $totalDays += 1;
                    }
                } else {
                    // For Marriage leave type, count all weekdays without excluding weekends or holidays
                    if (!$this->isHoliday($startDate, $holidays)) {
                        $totalDays += 1;
                    }
                }

                // Move to the next day
                $startDate->addDay();
            }

            // Deduct weekends based on the session numbers
            if ($this->getSessionNumber($fromSession) > 1) {
                $totalDays -= $this->getSessionNumber($fromSession) - 1; // Deduct days for the starting session
            }
            if ($this->getSessionNumber($toSession) < 2) {
                $totalDays -= 2 - $this->getSessionNumber($toSession); // Deduct days for the ending session
            }

            // Adjust for half days
            if ($this->getSessionNumber($fromSession) === $this->getSessionNumber($toSession)) {
                if ($this->getSessionNumber($fromSession) !== 1) {
                    $totalDays += 0.5; // Add half a day
                } else {
                    $totalDays += 0.5;
                }
            } elseif ($this->getSessionNumber($fromSession) !== $this->getSessionNumber($toSession)) {
                if ($this->getSessionNumber($fromSession) !== 1) {
                    $totalDays += 1; // Add half a day
                }
            } else {
                $totalDays += ($this->getSessionNumber($toSession) - $this->getSessionNumber($fromSession) + 1) * 0.5;
            }

            return $totalDays;
        } catch (\Exception $e) {
            FlashMessageHelper::flashError('An error occurred while calculating the number of days.');
            return false;
        }
    }
    // Helper method to check if a date is a holiday
    private function isHoliday($date, $holidays)
    {
        // Check if the date exists in the holiday collection
        return $holidays->contains('date', $date->toDateString());
    }

    private function getSessionNumber($session)
    {
        return (int) str_replace('Session ', '', $session);
    }

    //method to use dynamic years in a dropdown
    public function yearDropDown()
    {
        try {
            $currentYear = Carbon::now()->format('Y');
            if ($this->isTrue($currentYear - 2)) {
            } elseif ($this->isTrue($currentYear - 1)) {
            } elseif ($this->isTrue($currentYear)) {
            } else {
            }
        } catch (\Exception $e) {
            FlashMessageHelper::flashError('An error occurred. Please try again later.');
        }
    }
    public function isTrue($year)
    {
        return $this->selectedYear === $year;
    }



    public function generatePdf()
    {

        $this->validate();

        if ($this->dateErrorMessage) {
            return;
        }
        try {

            $employeeId = auth()->guard('emp')->user()->emp_id;

            // Fetch employee details
            $employeeDetails = EmployeeDetails::where('emp_id', $employeeId)->first();
            if ($this->transactionType == 'granted') {
                // Logic for 'granted' transaction type

                $employeeId = auth()->guard('emp')->user()->emp_id;

                // Fetch leave balances for granted transaction
                $leaveBalance = EmployeeLeaveBalances::where('emp_id', $employeeId)
                    ->when($this->leaveType && $this->leaveType != 'all', function ($query) {
                        $leaveTypes = [
                            'casual_probation' => 'Casual Leave',
                            'maternity' => 'Maternity Leave',
                            'paternity' => 'Paternity Leave',
                            'sick' => 'Sick Leave',
                            'lop' => 'Loss of Pay',
                        ];

                        if (array_key_exists($this->leaveType, $leaveTypes)) {
                            $query->whereJsonContains('leave_policy_id', [['leave_name' => $leaveTypes[$this->leaveType]]]);
                        }
                    })
                    ->get()
                    ->map(function ($balance) {
                        // Declare startDate and endDate based on the period
                        $period = $balance->period;
                        $startDate = Carbon::createFromFormat('Y', $period)->firstOfYear()->toDateString(); // '2024-01-01'

                        // Get the end date (last day of the year)
                        $endDate = Carbon::createFromFormat('Y', $period)->lastOfYear()->toDateString(); // End date for the year

                        // Decode the leave_policy_id from JSON
                        $leaveDetails = json_decode($balance->leave_policy_id, true);
                        $formattedLeaves = [];
                        $leaveTypes = [
                            'casual_probation' => 'Casual Leave',
                            'maternity' => 'Maternity Leave',
                            'paternity' => 'Paternity Leave',
                            'sick' => 'Sick Leave',
                            'lop' => 'Loss of Pay',
                        ];

                        // Loop through the leave details and format them
                        foreach ($leaveDetails as $leave) {
                            // Filter leaves based on the leave type (if selected)
                            if ($this->leaveType && $this->leaveType != 'all' && $leave['leave_name'] !== $leaveTypes[$this->leaveType]) {
                                continue;  // Skip if the leave name doesn't match the selected leave type
                            }

                            // Add formatted leave object to the array
                            $formattedLeaves[] = (object)[
                                'from_date' => $startDate,
                                'to_date' => $endDate,
                                'days' => $leave['grant_days'],
                                'leave_name' => $leave['leave_name'],
                                'transaction_type' => $balance->status,
                                'created_at' => $balance->created_at,
                            ];
                        }

                        return $formattedLeaves;
                    })
                    ->flatten(1);
            } elseif ($this->transactionType == 'lapsed') {
                $employeeId = auth()->guard('emp')->user()->emp_id;

                // Fetch lapsed leave transactions for the employee
                $lapsedLeaveBalances = EmployeeLeaveBalances::where('emp_id', $employeeId)
                    ->where('is_lapsed', 1)  // Filter for lapsed leave
                    ->when($this->leaveType && $this->leaveType != 'all', function ($query) {
                        $leaveTypes = [
                            'casual_probation' => 'Casual Leave',
                            'maternity' => 'Maternity Leave',
                            'paternity' => 'Paternity Leave',
                            'sick' => 'Sick Leave',
                            'lop' => 'Loss of Pay',
                        ];

                        if (array_key_exists($this->leaveType, $leaveTypes)) {
                            $query->whereJsonContains('leave_policy_id', [['leave_name' => $leaveTypes[$this->leaveType]]]);
                        }
                    })
                    ->get()
                    ->map(function ($balance) use ($employeeId) {
                        // Declare startDate and endDate based on the last month of the year
                        $period = $balance->period;
                        $decemberStart = Carbon::createFromFormat('Y', $period)->month(12)->startOfMonth()->toDateString(); // First day of December
                        $decemberEnd = Carbon::createFromFormat('Y', $period)->month(12)->endOfMonth()->toDateString();   // Last day of December

                        // Get the remaining leave balances using the getLeaveBalances method
                        $leaveBalances = LeaveBalances::getLeaveLapsedBalances($employeeId, $period);

                        // Use the leave balance for the respective leave type
                        $leaveDetails = json_decode($balance->leave_policy_id, true);
                        $formattedLeaves = [];
                        $leaveTypes = [
                            'casual_probation' => 'Casual Leave',
                            'maternity' => 'Maternity Leave',
                            'paternity' => 'Paternity Leave',
                            'sick' => 'Sick Leave',
                            'lop' => 'Loss of Pay',
                        ];
                        function normalizeLeaveNameToBalanceKey($leaveName)
                        {
                            $leaveName = str_replace(' ', '', ucwords(strtolower($leaveName))); // Remove spaces and capitalize words
                            $balanceKey = lcfirst($leaveName) . 'Balance'; // Ensure the first letter is lowercase and append 'Balance'
                            return $balanceKey;
                        }

                        // Loop through the leave details and format them
                        foreach ($leaveTypes as $key => $leaveName) {
                            if ($this->leaveType && $this->leaveType != 'all' && $leaveName !== $leaveTypes[$this->leaveType]) {
                                continue;  // Skip if the leave name doesn't match the selected leave type
                            }
                            $balanceKey = normalizeLeaveNameToBalanceKey($leaveName);

                            // This will print the balance key for debugging

                            // Get the balance for this leave type (defaults to 0 if not found)
                            $remainingBalance = $leaveBalances[$balanceKey] ?? 0;

                            // Skip if there's no remaining balance for this leave type
                            if ($remainingBalance <= 0) {
                                continue;
                            }

                            // Add formatted leave object to the array with remaining balance (days)
                            $formattedLeaves[] = (object)[
                                'from_date' => $decemberStart,
                                'to_date' => $decemberEnd,
                                'days' => $remainingBalance,  // Use the remaining balance (days)
                                'leave_name' => $leaveName,
                                'transaction_type' => 'lapsed',  // Indicate it's a lapsed transaction
                                'created_at' => $balance->created_at,
                            ];
                        }

                        return $formattedLeaves;
                    })
                    ->flatten(1);
            }


            // If transaction type is 'all', merge both 'granted' and the other transaction types
            elseif ($this->transactionType == 'all') {
                $employeeId = auth()->guard('emp')->user()->emp_id;

                // Fetch leave balances for granted transaction
                $leaveBalance = EmployeeLeaveBalances::where('emp_id', $employeeId)
                    ->when($this->leaveType && $this->leaveType != 'all', function ($query) {
                        $leaveTypes = [
                            'casual_probation' => 'Casual Leave',
                            'maternity' => 'Maternity Leave',
                            'paternity' => 'Paternity Leave',
                            'sick' => 'Sick Leave',
                            'lop' => 'Loss of Pay',
                        ];

                        if (array_key_exists($this->leaveType, $leaveTypes)) {
                            $query->whereJsonContains('leave_policy_id', [['leave_name' => $leaveTypes[$this->leaveType]]]);
                        }
                    })
                    ->get()
                    ->map(function ($balance) {
                        // Declare startDate and endDate based on the period
                        $period = $balance->period;
                        $startDate = Carbon::createFromFormat('Y', $period)->firstOfYear()->toDateString(); // '2024-01-01'

                        // Get the end date (last day of the year)
                        $endDate = Carbon::createFromFormat('Y', $period)->lastOfYear()->toDateString(); // End date for the year   // End date for the year

                        // Decode the leave_policy_id from JSON
                        $leaveDetails = json_decode($balance->leave_policy_id, true);
                        $formattedLeaves = [];
                        $leaveTypes = [
                            'casual_probation' => 'Casual Leave',
                            'maternity' => 'Maternity Leave',
                            'paternity' => 'Paternity Leave',
                            'sick' => 'Sick Leave',
                            'lop' => 'Loss of Pay',
                        ];

                        // Loop through the leave details and format them
                        foreach ($leaveDetails as $leave) {
                            // Filter leaves based on the leave type (if selected)
                            if ($this->leaveType && $this->leaveType != 'all' && $leave['leave_name'] !== $leaveTypes[$this->leaveType]) {
                                continue;  // Skip if the leave name doesn't match the selected leave type
                            }

                            // Add formatted leave object to the array
                            $formattedLeaves[] = (object)[
                                'from_date' => $startDate,
                                'to_date' => $endDate,
                                'days' => $leave['grant_days'],
                                'leave_name' => $leave['leave_name'],
                                'transaction_type' => $balance->status,
                                'created_at' => $balance->created_at,
                            ];
                        }

                        return $formattedLeaves;
                    })
                    ->flatten(1);


                // Fetch leave requests (other transaction types like availed, withdrawn, etc.)
                $leaveRequests = LeaveRequest::where('emp_id', $employeeId)
                    ->when($this->fromDateModal, function ($query) {
                        $query->where('from_date', '>=', $this->fromDateModal);
                    })
                    ->when($this->toDateModal, function ($query) {
                        $query->where('to_date', '<=', $this->toDateModal);
                    })
                    ->when($this->leaveType && $this->leaveType != 'all', function ($query) {
                        $leaveTypes = [
                            'casual_probation' => 'Casual Leave',
                            'maternity' => 'Maternity Leave',
                            'paternity' => 'Paternity Leave',
                            'sick' => 'Sick Leave',
                            'lop' => 'Loss of Pay'
                        ];

                        if (array_key_exists($this->leaveType, $leaveTypes)) {
                            $query->where('leave_type', $leaveTypes[$this->leaveType]);
                        }
                    })
                    ->when($this->transactionType && $this->transactionType != 'all', function ($query) {
                        $transactionTypes = [
                            'granted' => 'Granted',
                            'availed' => 2,
                            'withdrawn' => 4,
                            'rejected' => 3,
                        ];
                        if (array_key_exists($this->transactionType, $transactionTypes)) {
                            $query->where('leave_status', $transactionTypes[$this->transactionType]);
                        }
                    })
                    ->where('leave_status', '!=', 5)
                    ->orderBy('created_at', $this->sortBy === 'oldest_first' ? 'asc' : 'desc')
                    ->get()
                    ->map(function ($leaveRequest) {
                        // Calculate the leave days and transform the data into objects
                        $leaveRequest->days = $leaveRequest->calculateLeaveDays(
                            $leaveRequest->from_date,
                            $leaveRequest->from_session,
                            $leaveRequest->to_date,
                            $leaveRequest->to_session,
                            $leaveRequest->leave_type
                        );

                        return (object)[
                            'from_date' => $leaveRequest->from_date,
                            'to_date' => $leaveRequest->to_date,
                            'days' => $leaveRequest->days,
                            'leave_name' => $leaveRequest->leave_type,
                            'transaction_type' => $leaveRequest->leave_status,
                            'reason' => $leaveRequest->reason,
                            'created_at' => $leaveRequest->created_at,
                        ];
                    });

                $lapsedLeaveBalances = EmployeeLeaveBalances::where('emp_id', $employeeId)
                    ->where('is_lapsed', 1)  // Filter for lapsed leave
                    ->when($this->leaveType && $this->leaveType != 'all', function ($query) {
                        $leaveTypes = [
                            'casual_probation' => 'Casual Leave',
                            'maternity' => 'Maternity Leave',
                            'paternity' => 'Paternity Leave',
                            'sick' => 'Sick Leave',
                            'lop' => 'Loss of Pay',
                        ];

                        if (array_key_exists($this->leaveType, $leaveTypes)) {
                            $query->whereJsonContains('leave_policy_id', [['leave_name' => $leaveTypes[$this->leaveType]]]);
                        }
                    })
                    ->get()
                    ->map(function ($balance) use ($employeeId) {
                        // Declare startDate and endDate based on the last month of the year
                        $period = $balance->period;
                        $decemberStart = Carbon::createFromFormat('Y', $period)->month(12)->startOfMonth()->toDateString(); // First day of December
                        $decemberEnd = Carbon::createFromFormat('Y', $period)->month(12)->endOfMonth()->toDateString();   // Last day of December

                        // Get the remaining leave balances using the getLeaveBalances method
                        $leaveBalances = LeaveBalances::getLeaveLapsedBalances($employeeId, $period);

                        // Use the leave balance for the respective leave type
                        $leaveDetails = json_decode($balance->leave_policy_id, true);
                        $formattedLeaves = [];
                        $leaveTypes = [
                            'casual_probation' => 'Casual Leave',
                            'maternity' => 'Maternity Leave',
                            'paternity' => 'Paternity Leave',
                            'sick' => 'Sick Leave',
                            'lop' => 'Loss of Pay',
                        ];
                        function normalizeLeaveNameToBalanceKey($leaveName)
                        {
                            $leaveName = str_replace(' ', '', ucwords(strtolower($leaveName))); // Remove spaces and capitalize words
                            $balanceKey = lcfirst($leaveName) . 'Balance'; // Ensure the first letter is lowercase and append 'Balance'
                            return $balanceKey;
                        }

                        // Loop through the leave details and format them
                        foreach ($leaveTypes as $key => $leaveName) {
                            if ($this->leaveType && $this->leaveType != 'all' && $leaveName !== $leaveTypes[$this->leaveType]) {
                                continue;  // Skip if the leave name doesn't match the selected leave type
                            }
                            $balanceKey = normalizeLeaveNameToBalanceKey($leaveName);

                            // This will print the balance key for debugging

                            // Get the balance for this leave type (defaults to 0 if not found)
                            $remainingBalance = $leaveBalances[$balanceKey] ?? 0;

                            // Skip if there's no remaining balance for this leave type
                            if ($remainingBalance <= 0) {
                                continue;
                            }

                            // Add formatted leave object to the array with remaining balance (days)
                            $formattedLeaves[] = (object)[
                                'from_date' => $decemberStart,
                                'to_date' => $decemberEnd,
                                'days' => $remainingBalance,  // Use the remaining balance (days)
                                'leave_name' => $leaveName,
                                'transaction_type' => 'lapsed',  // Indicate it's a lapsed transaction
                                'created_at' => $balance->created_at,
                            ];
                        }

                        return $formattedLeaves;
                    })
                    ->flatten(1);

                // Merge both leave balances and leave requests
                $leaveTransactions = $leaveBalance->merge($leaveRequests)->merge($lapsedLeaveBalances);
            } else {

                // Logic for other transaction types (e.g., availed, withdrawn, etc.)
                $employeeId = auth()->guard('emp')->user()->emp_id;


                $leaveRequests = LeaveRequest::where('emp_id', $employeeId)
                    ->when($this->fromDateModal, function ($query) {
                        $query->where('from_date', '>=', $this->fromDateModal);
                    })
                    ->when($this->toDateModal, function ($query) {
                        $query->where('to_date', '<=', $this->toDateModal);
                    })
                    ->when($this->leaveType && $this->leaveType != 'all', function ($query) {
                        $leaveTypes = [
                            'casual_probation' => 'Casual Leave',
                            'maternity' => 'Maternity Leave',
                            'paternity' => 'Paternity Leave',
                            'sick' => 'Sick Leave',
                            'lop' => 'Loss of Pay'
                        ];

                        if (array_key_exists($this->leaveType, $leaveTypes)) {
                            $query->where('leave_type', $leaveTypes[$this->leaveType]);
                        }
                    })
                    ->when($this->transactionType && $this->transactionType != 'all', function ($query) {
                        $transactionTypes = [
                            'granted' => 'Granted',
                            'availed' => 2,
                            'withdrawn' => 4,
                            'rejected' => 3,
                        ];
                        if (array_key_exists($this->transactionType, $transactionTypes)) {
                            $query->where('leave_status', $transactionTypes[$this->transactionType]);
                        }
                    })
                    ->where('leave_status', '!=', 5)
                    ->orderBy('created_at', $this->sortBy === 'oldest_first' ? 'asc' : 'desc')
                    ->get()
                    ->map(function ($leaveRequest) {
                        // Calculate the leave days and transform the data into objects
                        $leaveRequest->days = $leaveRequest->calculateLeaveDays(
                            $leaveRequest->from_date,
                            $leaveRequest->from_session,
                            $leaveRequest->to_date,
                            $leaveRequest->to_session,
                            $leaveRequest->leave_type
                        );

                        return (object)[
                            'from_date' => $leaveRequest->from_date,
                            'to_date' => $leaveRequest->to_date,
                            'days' => $leaveRequest->days,
                            'leave_name' => $leaveRequest->leave_type,
                            'transaction_type' => $leaveRequest->leave_status,
                            'reason' => $leaveRequest->reason,
                            'created_at' => $leaveRequest->created_at,
                        ];
                    });
            }

            Log::info('Starting PDF generation for leave transactions', [
                'employeeDetails' => $employeeDetails,
                'transaction_type' => $this->transactionType,
                'from_date' => $this->fromDateModal,
                'to_date' => $this->toDateModal
            ]);

            // Generate the PDF using merged data for 'all'
            $pdf = Pdf::loadView('pdf_template', [
                'employeeDetails' => $employeeDetails,
                'leaveTransactions' => isset($leaveTransactions)
                    ? $leaveTransactions
                    : ($this->transactionType == 'granted'
                        ? $leaveBalance
                        : ($this->transactionType == 'lapsed'
                            ? $lapsedLeaveBalances
                            : $leaveRequests)
                    ),

                'fromDate' => $this->fromDateModal,
                'toDate' => $this->toDateModal,
            ]);

            // Log::info('PDF generated successfully', [
            //     'employee_id' => $employeeDetails->emp_id,
            //     'transaction_type' => $this->transactionType
            // ]);

            $this->showModal = false;
            $this->updateLeaveBalances();
            FlashMessageHelper::flashSuccess('Leave Transaction Report Downloaded Successfully!');

            // Return the PDF as a downloadable response
            return response()->streamDownload(function () use ($pdf) {
                echo $pdf->stream();
            }, 'leave_transactions.pdf');
        } catch (Exception $e) {
            // Log the exception
            Log::error('Error generating PDF for leave transactions: ' . $e->getMessage(), [
                'transaction_type' => $this->transactionType,
                'employee_id' => $employeeId ?? 'N/A'
            ]);

            // Show a friendly error message to the user
            FlashMessageHelper::flashError('An error occurred while generating the report. Please try again later.');

            // Optionally, you could rethrow the exception if you want to handle it elsewhere
            // throw $e;
        }
    }



    public function resetFields()
    {
        $this->leaveType = 'all';
        $this->transactionType = 'all';
    }
}

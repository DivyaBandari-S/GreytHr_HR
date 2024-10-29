<?php

namespace App\Livewire;
use Livewire\Component;
use Illuminate\Support\Facades\Log;
use App\Models\LeaveRequest;
use Illuminate\Support\Carbon;
use App\Helpers\LeaveHelper;

class HrLeaveOverview extends Component
{
    public $activeTab = 'Main';
    public $showHelp = false;
    public $filterPeriodValue; 
    public $months = [];
    public $selectedMonth;
    public $leaveData = [];
    public $leavesCount = [];
    public $filterPeriod = 'this_week'; 
    public $leaveType = 'all';
    public $monthFilterLeaveType = 'all';
    public $teamOnLeaveType = 'all';
    public $leaveTypeColors = [
        'Loss Of Pay' => '#ffadad', 
        'Sick Leave' => '#ffd6a5',
        'Maternity Leave' => '#fdffb6', 
        'Casual Leave' => '#caffbf', 
        'Marriage Leave' => '#a0c4ff', 
        'Casual Leave Probation' => '#bdb2ff', 
        'Paternity Leave' => '#8996cb',
    ];
    public $leaveTypeAbbreviations = [
        'Casual Leave' => 'CL',
        'Sick Leave' => 'SL',
        'Maternity Leave' => 'ML',
        'Loss Of Pay' => 'LOP',
        'Paternity Leave' => 'PL',
        'Marriage Leave' => 'ML',
        'Casual Leave Probation' => 'CLP',
    ];
    public $selectedLeave;
    public $selectedLeaveType = 'all';

    public function mount($month = null, $leaveType = null, $monthLeaveType = null)
    {
        $this->filterPeriodValue = 'this_year';
        $this->selectedMonth = $month ?? 'Jan';
        $this->selectedLeaveType = $leaveType ?? 'all';
        $this->monthFilterLeaveType = $monthLeaveType ?? 'all';
        $this->updateMonths(); 
        $this->fetchLeaveData();
        $this->calculateLeaves();
    }

    public function calculateLeaves()
    {
        $this->leavesCount = array_fill(0, 12, 0);
        $currentYear = now()->year;
        $query = LeaveRequest::where('leave_status', 2)
            ->whereYear('from_date', $currentYear);
        if ($this->monthFilterLeaveType !== 'all') {
            $query->where('leave_type', $this->monthFilterLeaveType);
        }
        $monthlyLeaves = $query
            ->selectRaw('MONTH(from_date) as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->get();
        foreach ($monthlyLeaves as $entry) {
            $this->leavesCount[$entry->month - 1] = $entry->count;
        }
    }

    public function filterPeriodChanged()
    {
        $this->selectedMonth = 'Jan';
        $this->updateMonths();
        $this->fetchLeaveData();
    }

    public function selectMonth($month)
    {
        $this->selectedMonth = $month;
        $this->fetchLeaveData();
        return redirect()->route('leave-overview.month', ['month' => $month]);
    }

    public function updateMonths()
    {
        $currentYear = date('Y');
        $year = $this->filterPeriodValue === 'this_year' ? $currentYear : $currentYear - 1;
        $this->months = $this->getMonths($year);
    }

    public function getMonths($year)
    {
        return [
            'Jan' => $year,
            'Feb' => $year,
            'Mar' => $year,
            'Apr' => $year,
            'May' => $year,
            'Jun' => $year,
            'Jul' => $year,
            'Aug' => $year,
            'Sep' => $year,
            'Oct' => $year,
            'Nov' => $year,
            'Dec' => $year,
        ];
    }

    public function filterLeaveType()
    {
        if ($this->selectedLeaveType === 'all') {
            $this->fetchLeaveData(); 
        } else {
            $this->leaveData = $this->fetchLeaveDataByType($this->selectedLeaveType);
        }
        return redirect()->route('leave-overview.month', [
            'month' => $this->selectedMonth,
            'leaveType' => $this->selectedLeaveType,
        ]);
    }

    protected function fetchLeaveDataByType($leaveType)
    {
        $year = $this->filterPeriodValue === 'this_year' ? date('Y') : date('Y') - 1;
        $startDate = Carbon::createFromFormat('M Y', $this->selectedMonth . ' ' . $year)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();
        $leaveApplications = LeaveRequest::whereBetween('from_date', [$startDate, $endDate])
            ->where('leave_type', $leaveType)
            ->get()
            ->count();
        return [$leaveType => $leaveApplications];
    }

    public function fetchLeaveData()
    {
        $year = $this->filterPeriodValue === 'this_year' ? date('Y') : date('Y') - 1;
        $startDate = Carbon::createFromFormat('M Y', $this->selectedMonth . ' ' . $year)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();
        $leaveApplications = LeaveRequest::whereBetween('from_date', [$startDate, $endDate]);
        if ($this->selectedLeaveType !== 'all') {
            $leaveApplications->where('leave_type', $this->selectedLeaveType);
        }
        $this->leaveData = $leaveApplications->get()->groupBy('leave_type')->map->count()->toArray();
    }

    public function hideHelp()
    {
        $this->showHelp = true;
    }

    public function showhelp()
    {
        $this->showHelp = false;
    }

    public function monthFilter()
    {
        $this->getFilteredLeaveRequests();
    }

    public function leaveTypeFilter()
    {
        $this->getFilteredLeaveRequests(); 
    }

    public function monthLeaveTypeFilter()
    {
        if ($this->monthFilterLeaveType === 'all') {
            $this->calculateLeaves(); 
        } else {
            $this->calculateLeavesForType($this->monthFilterLeaveType);
        }
        return redirect()->route('leave-overview.monthLeaveType', [
            'monthLeaveType' => $this->monthFilterLeaveType,
        ]);
    }

    public function calculateLeavesForType($leaveType)
    {
        $this->leavesCount = array_fill(0, 12, 0);
        $currentYear = now()->year;
        $query = LeaveRequest::where('leave_status', 2)
            ->whereYear('from_date', $currentYear)
            ->where('leave_type', $leaveType); 
        $monthlyLeaves = $query
            ->selectRaw('MONTH(from_date) as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->get();
        foreach ($monthlyLeaves as $entry) {
            $this->leavesCount[$entry->month - 1] = $entry->count;
        }
    }

    public function getFilteredLeaveRequests()
    {
        $query = LeaveRequest::with('employee')->where('leave_status', 2);
        if ($this->teamOnLeaveType && $this->teamOnLeaveType !== 'all') {
            $query->where('leave_type', $this->teamOnLeaveType);
        }
        if ($this->filterPeriod && $this->filterPeriod !== 'all') {
            $startDate = now()->startOfMonth();
            $endDate = now()->endOfMonth();
            switch ($this->filterPeriod) {
                case 'this_week':
                    $query->whereBetween('from_date', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'this_month':
                    $query->where(function ($q) use ($startDate, $endDate) {
                        $q->whereBetween('from_date', [$startDate, $endDate])
                            ->orWhereBetween('to_date', [$startDate, $endDate]);
                    });
                    break;
                case 'last_month':
                    $query->whereMonth('from_date', now()->subMonth()->month);
                    break;
                case 'this_year':
                    $query->whereYear('from_date', now()->year);
                    break;
            }
        }
        $leaveRequests = $query->get();
        foreach ($leaveRequests as $leaveRequest) {
            $leaveRequest->calculated_days = LeaveHelper::calculateNumberOfDays(
                $leaveRequest->from_date,
                $leaveRequest->from_session,
                $leaveRequest->to_date,
                $leaveRequest->to_session,
                $leaveRequest->leave_type
            );
        }
        return $leaveRequests;
    }
    public function render()
    {
        $leaveRequests = $this->getFilteredLeaveRequests();
        return view('livewire.hr-leave-overview', compact('leaveRequests'));
    }
}

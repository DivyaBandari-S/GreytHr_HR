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
    public $filterPeriodValue; // Bind this to the dropdown
    public $months = [];
    public $selectedMonth;
    public $leaveData = [];
    public $leavesCount = [];
    public $filterPeriod = 'this_week'; // Default value
    public $leaveType = 'all';
    public $monthFilterLeaveType = 'all';

    public $leaveTypeColors = [
        'Loss Of Pay' => '#ffadad', // Lighter shade of red
        'Sick Leave' => '#ffd6a5', // Lighter shade of orange
        'Maternity Leave' => '#fdffb6', // Lighter shade of yellow
        'Casual Leave' => '#caffbf', // Lighter shade of green
        'Marriage Leave' => '#a0c4ff', // Lighter shade of blue
        'Casual Leave Probation' => '#bdb2ff', // Lighter shade of purple
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



    public function mount($month = null, $leaveType = null)
    {
        $this->filterPeriodValue = 'this_year'; // Default to current year
        $this->selectedMonth = $month ?? 'Jan';
        if ($leaveType) {
            $this->selectedLeaveType = $leaveType;
            $this->monthFilterLeaveType = $leaveType; // Set both if needed
        } else {
            $this->selectedLeaveType = 'all';
            $this->monthFilterLeaveType = 'all'; // Default to all
        }
        $this->updateMonths(); // Initialize months

        $this->fetchLeaveData();
        $this->calculateLeaves();
    }
    public function calculateLeaves()
    {
        $this->leavesCount = array_fill(0, 12, 0);
    $currentYear = now()->year;

    // Build the query
    $query = LeaveRequest::where('status', 'approved')
        ->whereYear('from_date', $currentYear);

    // If a leave type is selected, add it to the query
    if ($this->monthFilterLeaveType !== 'all') {
        $query->where('leave_type', $this->monthFilterLeaveType);
    }

    // Fetch the count of approved leaves grouped by month
    $monthlyLeaves = $query
        ->selectRaw('MONTH(from_date) as month, COUNT(*) as count')
        ->groupBy('month')
        ->orderBy('month')
        ->get();

    // Populate the leaves count
    foreach ($monthlyLeaves as $entry) {
        $this->leavesCount[$entry->month - 1] = $entry->count; // Zero-based index
    }
    }

    public function filterPeriodChanged()
    {
        // This method will be called when the dropdown value changes
        $this->selectedMonth = 'Jan';
        $this->updateMonths(); // Update months based on the new selection

        $this->fetchLeaveData();
    }
    public function selectMonth($month)
    {
        $this->selectedMonth = $month; // Update selected month
        $this->fetchLeaveData();
        return redirect()->route('leave-overview.month', ['month' => $month]);
    }

    public function updateMonths()
    {
        $currentYear = date('Y');

        // Determine the year based on filterPeriodValue
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
        // Call fetchLeaveData to get the latest data based on month and year
        if ($this->selectedLeaveType === 'all') {
            // Fetch all leave data
            $this->fetchLeaveData(); // This will get all leave types with counts
        } else {
            // Fetch specific leave type data
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

        // Fetch all leave applications
        $leaveApplications = LeaveRequest::whereBetween('from_date', [$startDate, $endDate]);

        if ($this->selectedLeaveType !== 'all') {
            $leaveApplications->where('leave_type', $this->selectedLeaveType);
        }

        // Get all leave data grouped by leave type
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


        // Optionally, you could refresh the data or handle any additional logic here
        $this->render(); // Not needed usually, as Livewire auto-triggers render
    }
   

    public function leaveTypeFilter()
    {


        // Optionally, you could refresh the data or handle any additional logic here
        $this->render(); // Not needed usually, as Livewire auto-triggers render
    }
    public function monthLeaveTypeFilter()
{
    $this->calculateLeaves(); // Recalculate leaves based on the selected type
    return redirect()->route('leave-overview.leaveType', [
        'leaveType' => $this->monthFilterLeaveType,
    ]); // Recalculate leaves when the filter changes
}



    public function render()
    {
        $query = LeaveRequest::with('employee')->where('status', 'approved');

        // Filter based on selected leave type
        if ($this->leaveType && $this->leaveType !== 'all') {
            $query->where('leave_type', $this->leaveType);
        }

        // Filter based on selected period
        if ($this->filterPeriod && $this->filterPeriod !== 'all') {
            $startDate = now()->startOfMonth();
            $endDate = now()->endOfMonth();

            switch ($this->filterPeriod) {
                case 'this_week':
                    $query->whereBetween('from_date', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'this_month':
                    // Ensure both from_date and to_date fall within the current month
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


        // Get the filtered leave requests
        $leaveRequests = $query->get();

        // Calculate the number of days for each leave request
        foreach ($leaveRequests as $leaveRequest) {
            $leaveRequest->calculated_days = LeaveHelper::calculateNumberOfDays(
                $leaveRequest->from_date,
                $leaveRequest->from_session,
                $leaveRequest->to_date,
                $leaveRequest->to_session,
                $leaveRequest->leave_type
            );
        }
        return view('livewire.hr-leave-overview', compact('leaveRequests'));
    }
}

<?php

namespace App\Exports;

use App\Models\EmployeeDetails;
use App\Models\HolidayCalendar;
use App\Models\Hr;
use App\Models\LeaveRequest;
use App\Models\SwipeRecord;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EmployeesExport implements FromCollection,WithHeadings,WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $selectedMonth;
    protected $selectedYear;

    protected $selectedOption;
    public $employeeStatuses;
    public function __construct($selectedMonth, $selectedYear,$selectedOption)
    {
        $this->selectedMonth = $selectedMonth;
        $this->selectedYear = $selectedYear;
        $this->selectedOption = $selectedOption;
      
    }
    public function collection()
    {
        $hremployeeId = auth()->guard('hr')->user()->hr_emp_id;
        $employeeId=Hr::where('hr_emp_id',$hremployeeId)->value('emp_id');
        $companyIdJson = EmployeeDetails::where('emp_id', $employeeId)->value('company_id');
        $companyIds = json_decode($companyIdJson, true);
        $empIds = EmployeeDetails::where(function($query) use ($companyIds) {
            foreach ($companyIds as $companyId) {
                // Use JSON_CONTAINS to check if company_id field contains the companyId
                $query->orWhereRaw('JSON_CONTAINS(company_id, \'' . json_encode($companyId) . '\')');
            }
        })->pluck('emp_id');
        if($this->selectedOption=='all')
        {
            $employees = EmployeeDetails::whereIn('emp_id', $empIds)->get();
        }
        elseif($this->selectedOption=='current')
        {
            $employees = EmployeeDetails::whereIn('emp_id', $empIds)->where('employee_status','active')->get();
        }
        elseif($this->selectedOption=='past')
        {
            $employees = EmployeeDetails::whereIn('emp_id', $empIds)->where('employee_status','resigned')->orWhere('employee_status','terminated')->get();
        }
        elseif($this->selectedOption=='intern')
        {
            $employees = EmployeeDetails::whereIn('emp_id', $empIds)->where('job_role','intern')->get(); 
        }
        $todaysDate=Carbon::now()->format('Y-m-d');
        $employeeIds=$employees->pluck('emp_id');
        $monthName = Carbon::create()->month($this->selectedMonth)->format('F'); 
        $holidays = HolidayCalendar::where('year', $this->selectedYear)
        ->where('month', $monthName)
        ->pluck('date')
        ->toArray();
        $distinctDatesMap = SwipeRecord::whereIn('emp_id', $empIds)
        ->whereMonth('created_at', $this->selectedMonth) // December
        ->whereYear('created_at', $this->selectedYear) // December
        ->selectRaw('DISTINCT emp_id, DATE(created_at) as distinct_date ')
        ->get()
        ->groupBy('emp_id')
        ->map(function ($dates) {
            return $dates->pluck('distinct_date')->toArray();
        })
        ->toArray();
        $approvedLeaveRequests1 =   LeaveRequest::join('employee_details', 'leave_applications.emp_id', '=', 'employee_details.emp_id')
            ->where('leave_applications.status', 'approved')
            ->whereIn('leave_applications.emp_id', $empIds)
            ->whereDate('from_date', '>=', $this->selectedYear . '-' . $this->selectedMonth . '-01') // Dynamically set year and month
            ->whereDate('to_date', '<=', $this->selectedYear . '-' . $this->selectedMonth . '-31') // Dynamically set year and month
            ->get(['leave_applications.*', 'employee_details.emp_id', 'employee_details.first_name', 'employee_details.last_name'])
            ->mapWithKeys(function ($leaveRequest) {
                $fromDate = \Carbon\Carbon::parse($leaveRequest->from_date);
                $toDate = \Carbon\Carbon::parse($leaveRequest->to_date);
                $number_of_days = $fromDate->diffInDays($toDate) + 1;
                $dates = [];
                for ($i = 0; $i < $number_of_days; $i++) {
                    $dates[] = $fromDate->copy()->addDays($i)->toDateString();
                }
                return [
                    $leaveRequest->emp_id => [
                        'emp_id' => $leaveRequest->emp_id,
                        'dates' => $dates,
                    ],
                ];
            }); 
            $leaveDates = [];
            foreach ($approvedLeaveRequests1 as $emp_id => $leaveRequest) {
                foreach ($leaveRequest['dates'] as $date) {
                    $leaveDates[$emp_id][] = $date;
                }
            }
        $employeeStatuses = [];
        // Prepare the attendance data
        $attendanceData = [];
        foreach ($employees as $employee) {
            // Create a row for each employee
            $employeeStatuses[$employee->emp_id] = $employee->employee_status; // Store status
            $row = [
                $employee->emp_id,
                ucwords(strtolower($employee->first_name)) . ' ' . ucwords(strtolower($employee->last_name)),
            ];

            // Get the number of days in the selected month
            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $this->selectedMonth, $this->selectedYear);
            
            for ($day = 1; $day <= $daysInMonth; $day++) {
                $currentDate = Carbon::create($this->selectedYear, $this->selectedMonth, $day);
                $dayName = $currentDate->format('l');
                $currentDateFormatted = $currentDate->format('Y-m-d');
    
                if($currentDateFormatted<=$todaysDate)
                {
                    if (in_array($currentDateFormatted, $holidays)) {
                        // If it's a holiday
                        $row[] = 'H';
                    } elseif ($dayName === 'Saturday' || $dayName === 'Sunday') {
                        // If it's a weekend
                        $row[] = 'Off';
                    }
                    elseif($employee->employee_status=='resigned'||$employee->employee_status=='terminated')
                    {
                        $row[] = 'Status Unknown';
                    }
                    elseif (isset($leaveDates[$employee->emp_id]) && in_array($currentDateFormatted, $leaveDates[$employee->emp_id])) {
                        $row[] = 'L'; // Leave
                    } 
                    elseif (isset($distinctDatesMap[$employee->emp_id]) && in_array($currentDateFormatted, $distinctDatesMap[$employee->emp_id])) {
                        $row[] = 'P'; // Present
                    } else {
                        // Otherwise, mark as Absent
                        $row[] = 'A';
                    }
                }
                else
                {
                    $row[]='-';
                }
            }

            // Add the employee's row to the attendance data
            $attendanceData[] = $row;
        }
        $this->employeeStatuses = $employeeStatuses;
        return collect($attendanceData); // Convert to collection
    }
    public function headings(): array
    {
        // Create the headings including employee details and days
        $reportTitle = "Attendance Muster Report for " . 
        Carbon::create()->month($this->selectedMonth)->format('F').','.$this->selectedYear ;
        $headings = [
            'Employee ID',
            'Employee Name',
        ];

        // Get the days for the selected month and add them to the headings
        $days = $this->getDaysInMonth();
        // Return the full headings array
    return [
        [$reportTitle], // Add report title as first row
        [], // Add an empty row for spacing
        array_merge($headings, $days) // Merge employee details with days as headings
    ];
    }
    public function styles(Worksheet $sheet)
    {
        $rows = $sheet->getHighestRow();
        $columns = $sheet->getHighestColumn(); // Get the last column (e.g., 'Z')
    
        // Loop through each row to apply styles for resigned or terminated employees
        for ($row = 2; $row <= $rows; $row++) {
            $employeeId = $sheet->getCell('A' . $row)->getValue(); // Get employee ID
    
            // Check employee status from the stored data
            $employeeStatus = $this->employeeStatuses[$employeeId] ?? null;
    
            if (in_array(strtolower($employeeStatus), ['resigned', 'terminated'])) {
                // Apply red color styling to the entire row from A to the last column
                $sheet->getStyle("A{$row}:{$columns}{$row}")->applyFromArray([
                    'font' => [
                        'color' => ['rgb' => 'FF0000'], // Red color
                    ],
                ]);
            }
        }
    }
    
    
    public function getDaysInMonth()
    {
        // Calculate the number of days in the selected month and year
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $this->selectedMonth, $this->selectedYear);

        // Initialize an array to hold the days
        $daysArray = [];

        // Iterate from 1 to the last day of the month
        for ($day = 1; $day <= $daysInMonth; $day++) {
            // Create a Carbon date for the current day
            $date = Carbon::createFromDate($this->selectedYear, $this->selectedMonth, $day);
            // Get the day name
            $dayName = $date->format('l'); // 'l' gives full textual representation of the day

            // Format as "1 (Monday)"
            $daysArray[] = "{$day} ({$dayName})"; // Format day with day name
        }

        return $daysArray; // Return the array of formatted days
    }
}

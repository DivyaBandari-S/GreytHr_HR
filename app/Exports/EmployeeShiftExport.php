<?php

namespace App\Exports;

use App\Models\EmployeeDetails;
use App\Models\HolidayCalendar;
use App\Models\Hr;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EmployeeShiftExport implements FromCollection,WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $selectedMonth;

    protected $selectedYear;

    protected $selectedOption;

    public $empIds;
    protected $employees;
    public function __construct($selectedMonth, $currentYear,$selectedOption)
    {
        $this->selectedMonth = $selectedMonth;
        $this->selectedYear = $currentYear;
        $this->selectedOption = $selectedOption;
        
      
    }
    public function collection()
    {
        $hremployeeId = auth()->guard('hr')->user()->hr_emp_id;
        $employeeId=Hr::where('hr_emp_id',$hremployeeId)->value('emp_id');
        $companyIdJson = EmployeeDetails::where('emp_id', $employeeId)->value('company_id');
        if (is_array($companyIdJson)) {
            $companyIds = $companyIdJson; // It's already an array
        } elseif (is_string($companyIdJson)) {
            $companyIds = json_decode($companyIdJson, true); // Decode the JSON string
        } else {
            $companyIds = []; // Default to an empty array if it's neither
        }
        
        $this->empIds = EmployeeDetails::where(function($query) use ($companyIds) {
            foreach ($companyIds as $companyId) {
                // Use JSON_CONTAINS to check if company_id field contains the companyId
                $query->orWhereRaw('JSON_CONTAINS(company_id, \'' . json_encode($companyId) . '\')');
            }
        })->pluck('emp_id');
        if($this->selectedOption=='All')
        {
            $this->employees = EmployeeDetails::whereIn('emp_id', $this->empIds)->get();
        }
        elseif($this->selectedOption=='Current')
        {
            $this->employees = EmployeeDetails::whereIn('emp_id', $this->empIds)->where('employee_status','active')->get();
        }
        elseif($this->selectedOption=='Past')
        {
            $this->employees = EmployeeDetails::whereIn('emp_id', $this->empIds)
            ->where(function ($query) {
                $query->where('employee_status', 'resigned')
                    ->orWhere('employee_status', 'terminated');
            })
            ->get();
        }
        elseif($this->selectedOption=='Intern')
        {
            $this->employees = EmployeeDetails::whereIn('emp_id', $this->empIds)->where('job_role','intern')->get(); 
        }
        
        $employeeIds = $this->employees->pluck('emp_id');
        $monthName = Carbon::create()->month($this->selectedMonth)->format('F');
        $holidays = HolidayCalendar::where('year', $this->selectedYear)
        ->where('month', $monthName)
        ->pluck('date')
        ->toArray();
        $attendanceData = [];

    // Add the headings
    $headings = ['Employee ID', 'Employee Name', 'Working Days', 'Off Days'];
    $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $this->selectedMonth, $this->selectedYear);

    // Add additional columns for each day of the month
    for ($day = 1; $day <= $daysInMonth; $day++) {
        $currentDate = Carbon::create($this->selectedYear, $this->selectedMonth, $day);
        $dayName = $currentDate->format('l');
        $headings[] =  $day.'('.$dayName.')'; // For example, Day 1, Day 2, etc.
    }
    $attendanceData[] = $headings;
    $offdaycount=$this->calculateOffdays($this->selectedYear,$this->selectedMonth);
    $workingdayCount=$this->calculateWorkingdays($this->selectedYear,$this->selectedMonth);

    foreach ($this->employees as $employee) {
        $row = [
            $employee->emp_id,
            ucwords(strtolower($employee->first_name)) . ' ' . ucwords(strtolower($employee->last_name)),
           0, // Placeholder for Working Days
            0, // Placeholder for Off Days
        ];
        $row[2] = $workingdayCount;
        $row[3] = $offdaycount;
        if(in_array($employee->employee_status, ['resigned', 'terminated']))
        {
            $row[] = 'Shift Details Not Available';

            // Fill remaining columns for the days in the month with empty or null values, if needed
            for ($day = 1; $day <= $daysInMonth - 1; $day++) {
                $row[] = ''; // You can use null, empty string, or some placeholder
            }
        }
        else
        {
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $currentDate = Carbon::create($this->selectedYear, $this->selectedMonth, $day);
            $dayName = $currentDate->format('l');
            $currentDateFormatted = $currentDate->format('Y-m-d');
            if($dayName=='Saturday'||$dayName=='Sunday')
            {
                $row[]='O';
            }
            elseif(in_array($currentDateFormatted, $holidays)) {
                // If it's a holiday
                $row[] = 'H';
            }
            else
            {
                $row[]='GS';
            }
        }
    }
        // Add the row to the attendance data
        $attendanceData[] = $row;
    }

    return collect($attendanceData);
        
    }
    public function styles(Worksheet $sheet)
{
    // Determine the number of columns dynamically based on headings or data
    $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $this->selectedMonth, $this->selectedYear);
    $totalColumns = 4 + $daysInMonth; // 4 for 'Employee ID', 'Employee Name', 'Working Days', 'Off Days'

    // Convert column count to Excel letter range (e.g., 'A' to 'Z', 'A' to 'AA')
    $lastColumn = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($totalColumns);

    // Apply styles based on the employee status
    foreach ($this->employees as $index => $employee) {
        // Start from the second row (first row is headings)
        $rowIndex = $index + 2;

        // Check if the employee is resigned or terminated
        if (in_array($employee->employee_status, ['resigned', 'terminated'])) {
            $sheet->getStyle('A' . $rowIndex . ':' . $lastColumn . $rowIndex)->applyFromArray([
                'font' => [
                    'color' => ['rgb' => 'FF0000'], // Red color
                ],
            ]);
        }
    }
}
    public function calculateOffdays($sy,$sm)
    {
        $offDay=0;
        Log::info('Selected Year is:'.$sy.'and Selected Month is:'.$sm );
        $daysinmonthlocal=cal_days_in_month(CAL_GREGORIAN, $this->selectedMonth, $this->selectedYear);
        for ($day = 1; $day <= $daysinmonthlocal; $day++) {
            $currentDate = Carbon::create($sy, $sm, $day);
            $dayName = $currentDate->format('l');
             // For example, Day 1, Day 2, etc.
             if($dayName=='Saturday'||$dayName=='Sunday')
             {
                $offDay+=1;
             }
        }
        return $offDay;
    }
    
    public function calculateWorkingdays($sy,$sm)
    {
        
        Log::info('Selected Year is:'.$sy.'and Selected Month is:'.$sm );
        $daysinmonthlocal=cal_days_in_month(CAL_GREGORIAN, $this->selectedMonth, $this->selectedYear);
        $holiday = HolidayCalendar::where('month',Carbon::createFromDate(null, $sm, 1)->format('F'))
        ->where('year', $sy)
        ->count();
        $offDay=$this->calculateOffdays($sy,$sm);
        $regularDays=$daysinmonthlocal-$offDay-$holiday;
        return $regularDays;
    }
}

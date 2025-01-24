<?php

namespace App\Livewire;

use Livewire\Component;
use App\Helpers\FlashMessageHelper;
use App\Models\Company;
use Carbon\Carbon;
use App\Models\LeaveRequest;
use App\Models\EmployeeDetails;
use App\Models\HolidayCalendar;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Spatie\SimpleExcel\SimpleExcelWriter;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class HrLeaveCalendar extends Component
{
    public $year, $date;
    public $month;
    public $calendar;
    public $leaveData;
    public $restrictedHolidayData;
    public $generalHolidayData;
    public $leaveRequests;
    public $selectedDate;
    public $eventDetails;
    public $companyId;
    public $leaveTransactions = [];
    public $searchTerm = '';
    public $showDialog = false;
    public $showLocations = false;
    public $showDepartment = false;
    public $selectedLocations = [];
    public $filterType;
    public $backgroundColor;
    public $selectedDepartments = [];


    public function toggleSelection($location)
    {
        try {
            if ($location === 'All') {
                if (in_array('All', $this->selectedLocations)) {
                    $this->selectedLocations = [];
                } else {
                    $this->selectedLocations = ['All'];
                }
            } else {
                $key = array_search('All', $this->selectedLocations);
                if ($key !== false) {
                    unset($this->selectedLocations[$key]);
                }

                if (in_array($location, $this->selectedLocations)) {
                    $this->selectedLocations = array_diff($this->selectedLocations, [$location]);
                } else {
                    $this->selectedLocations[] = $location;
                }
            }
        } catch (\Illuminate\Database\QueryException $e) {
            // Handle database query exceptions
            FlashMessageHelper::flashError('An error occurred while processing your request. Please try again later.');
        } catch (\Exception $e) {
            // Handle other general exceptions
            FlashMessageHelper::flashError('An 71 unexpected error occurred. Please try again later.');
        }
    }
    public function toggleDeptSelection($dept)
    {
        try {
            if ($dept === 'All') {
                if (in_array('All', $this->selectedDepartments)) {
                    $this->selectedDepartments = [];
                } else {
                    $this->selectedDepartments = ['All'];
                }
            } else {
                $key = array_search('All', $this->selectedDepartments);
                if ($key !== false) {
                    unset($this->selectedDepartments[$key]);
                }

                if (in_array($dept, $this->selectedDepartments)) {
                    $this->selectedDepartments = array_diff($this->selectedDepartments, [$dept]);
                } else {
                    $this->selectedDepartments[] = $dept;
                }
            }
        } catch (\Illuminate\Database\QueryException $e) {
            // Handle database query exceptions
            FlashMessageHelper::flashError('An error occurred while processing your request. Please try again later.');
        } catch (\Exception $e) {
            // Handle other general exceptions
            FlashMessageHelper::flashError('An 100 unexpected error occurred. Please try again later.');
        }
    }


    public function isSelectedAll()
    {
        return in_array('All', $this->selectedLocations);
    }

    public function openDept()
    {
        $this->showDepartment = !$this->showDepartment;
    }

    public function open()
    {
        $this->showDialog = true;
    }

    public function close()
    {
        $this->showDialog = false;
    }
    public function openLocations()
    {
        $this->showLocations = !$this->showLocations;
    }

    public function closeLocations()
    {
        $this->showLocations = false;
    }

    public function closeDept()
    {
        $this->showDepartment = false;
    }
    public function isSelecteDeptdAll()
    {
        return in_array('All', $this->selectedDepartments);
    }
    public function isSelectedAllDept()
    {
        return count($this->selectedDepartments) === 1 && in_array('All', $this->selectedDepartments);
    }


    public function mount()
    {
        $this->year = now()->year;
        $this->month = now()->month;
        $this->leaveRequests = LeaveRequest::all();
        $this->searchTerm = '';
        $this->selectedLocations = ['All'];
        $this->selectedDepartments = ['All'];
        $this->loadLeaveTransactions(now()->toDateString());
        $this->generateCalendar();
    }
    public function generateCalendar()
    {
        try {
            $firstDay = Carbon::create($this->year, $this->month, 1);
            $daysInMonth = $firstDay->daysInMonth;
            $today = now();
            $calendar = [];
            $dayCount = 1;
            $publicHolidays = $this->getPublicHolidaysForMonth($this->year, $this->month);

            // Calculate the first day of the week for the current month
            $firstDayOfWeek = $firstDay->dayOfWeek;

            // Calculate the starting date of the previous month
            $startOfPreviousMonth = $firstDay->copy()->subMonth();

            // Fetch holidays for the previous month
            $publicHolidaysPreviousMonth = $this->getPublicHolidaysForMonth(
                $startOfPreviousMonth->year,
                $startOfPreviousMonth->month
            );

            // Calculate the last day of the previous month
            $lastDayOfPreviousMonth = $firstDay->copy()->subDay();

            for ($i = 0; $i < ceil(($firstDayOfWeek + $daysInMonth) / 7); $i++) {
                $week = [];
                for ($j = 0; $j < 7; $j++) {
                    if ($i === 0 && $j < $firstDay->dayOfWeek) {
                        // Add the days of the previous month
                        $previousMonthDays = $lastDayOfPreviousMonth->copy()->subDays($firstDay->dayOfWeek - $j - 1);
                        $week[] = [
                            'day' => $previousMonthDays->day,
                            'isToday' => false,
                            'isPublicHoliday' => in_array($previousMonthDays->toDateString(), $publicHolidaysPreviousMonth->pluck('date')->toArray()),
                            'isCurrentMonth' => false,
                            'isPreviousMonth' => true,
                            'backgroundColor' => '', // Initialize with an empty background color
                            'leaveCount' => 0, // Removed 'Me' and 'MyTeam' filters, just counting leave
                        ];
                    } elseif ($dayCount <= $daysInMonth) {
                        // Add the days of the current month
                        $isToday = $dayCount === $today->day && $this->month === $today->month && $this->year === $today->year;
                        $isPublicHoliday = in_array(
                            Carbon::create($this->year, $this->month, $dayCount)->toDateString(),
                            $publicHolidays->pluck('date')->toArray()
                        );

                        $this->backgroundColor = $isPublicHoliday ? 'background-color: IRIS;' : '';

                        $date = Carbon::create($this->year, $this->month, $dayCount)->toDateString();
                        $leaveCount = $this->loadLeaveTransactions($date); // Removed the filterType parameter

                        $week[] = [
                            'day' => $dayCount,
                            'isToday' => $isToday,
                            'isPublicHoliday' => $isPublicHoliday,
                            'isCurrentMonth' => true,
                            'isPreviousMonth' => false,
                            'backgroundColor' => $this->backgroundColor,
                            'leaveCount' => $leaveCount, // Just counting leave
                        ];
                        $dayCount++;
                    } else {
                        // Add the days of the next month
                        $week[] = [
                            'day' => $dayCount - $daysInMonth,
                            'isToday' => false,
                            'isPublicHoliday' => in_array($lastDayOfPreviousMonth->copy()->addDays($dayCount - $daysInMonth)->toDateString(), $this->getPublicHolidaysForMonth($startOfPreviousMonth->year, $startOfPreviousMonth->month)->pluck('date')->toArray()),
                            'isCurrentMonth' => false,
                            'isNextMonth' => true,
                            'backgroundColor' => '', // Initialize with an empty background color
                            'leaveCount' => 0, // No leave count for next month days
                        ];
                        $dayCount++;
                    }
                }
                $calendar[] = $week;
            }

            $this->calendar = $calendar;
        } catch (\Illuminate\Database\QueryException $e) {
            // Handle database query exceptions
            FlashMessageHelper::flashError('An error occurred while processing your request. Please try again later.');
        } catch (\Exception $e) {
            // Handle other general exceptions
            FlashMessageHelper::flashError('An 246 unexpected error occurred. Please try again later.');
        }
    }


    protected function getPublicHolidaysForMonth($year, $month)
    {
        try {
            return HolidayCalendar::whereYear('date', $year)
                ->whereMonth('date', $month)
                ->get();
        } catch (\Illuminate\Database\QueryException $e) {
            // Handle database query exceptions
            FlashMessageHelper::flashError('An error occurred while processing your request. Please try again later.');
        } catch (\Exception $e) {
            // Handle other general exceptions
            FlashMessageHelper::flashError('An 263 unexpected error occurred. Please try again later.');
        }
    }


    public function previousMonth()
    {
        try {
            $date = Carbon::create($this->year, $this->month, 1)->subMonth();
            $this->year = $date->year;
            $this->month = $date->month;
            $this->generateCalendar();
        } catch (\Illuminate\Database\QueryException $e) {
            // Handle database query exceptions
            FlashMessageHelper::flashError('An error occurred while processing your request. Please try again later.');
        } catch (\Exception $e) {
            // Handle other general exceptions
            FlashMessageHelper::flashError('An 280 unexpected error occurred. Please try again later.');
        }
    }

    public function nextMonth()
    {
        try {
            $date = Carbon::create($this->year, $this->month, 1)->addMonth();
            $this->year = $date->year;
            $this->month = $date->month;
            $this->generateCalendar();
        } catch (\Illuminate\Database\QueryException $e) {
            // Handle database query exceptions
            FlashMessageHelper::flashError('An error occurred while processing your request. Please try again later.');
        } catch (\Exception $e) {
            // Handle other general exceptions
            FlashMessageHelper::flashError('An 296 unexpected error occurred. Please try again later.');
        }
    }
    public function searchData()
    {
        $this->loadLeaveTransactions($this->selectedDate);
    }


    public function loadLeaveTransactions($date)
    {
        try {
            $loggedInEmpId = auth()->guard('hr')->user()->emp_id;
            $employee = EmployeeDetails::where('emp_id', $loggedInEmpId)->first();

            if ($employee) {
                $companyId = $employee->company_id;
                // Ensure company_id is decoded if it's a JSON string
                $companyIdsArray = is_array($companyId) ? $companyId : json_decode($companyId, true);
            }

            // Check if companyIdsArray is an array and not empty
            if (empty($companyIdsArray)) {
                // Handle the case where companyIdsArray is empty or invalid
                return 0;  // or handle as needed
            }
            // Step 1: Get all employees' emp_ids that belong to the company or companies in companyIdsArray
            $employeeIds = EmployeeDetails::whereJsonContains('company_id', $companyIdsArray)
                ->whereNotIn('employee_status', ['resigned', 'terminated'])
                ->pluck('emp_id') // Get a collection of emp_ids
                ->toArray(); // Convert to an array
            $dateFormatted = Carbon::parse($date)->format('Y-m-d');
            $searchTerm = '%' . $this->searchTerm . '%';
            $leaveCount = 0;

            // Fetch leave transactions for employees belonging to the company
            $leaveTransactions = LeaveRequest::with('employee')
                ->where('category_type', 'Leave')
                ->whereDate('from_date', '<=', $dateFormatted)
                ->whereDate('to_date', '>=', $dateFormatted)
                ->where(function ($query) use ($searchTerm) {
                    $query->where('emp_id', 'like', $searchTerm)
                        ->orWhereHas('employee', function ($query) use ($searchTerm) {
                            $query->where('first_name', 'like', $searchTerm)
                                ->orWhere('last_name', 'like', $searchTerm);
                        });
                })
                ->where(function ($query) {
                    $query->where('leave_status', 2)
                        ->whereIn('cancel_status', [6, 3, 4, 5]);
                })
                // Add company_id filter to ensure the employee belongs to the right company
                ->whereIn('emp_id', $employeeIds)
                ->get();

            // Check if leaveTransactions is not null before counting
            if ($leaveTransactions !== null) {
                $leaveCount = $leaveTransactions->count();
            }

            $this->leaveTransactions = $leaveTransactions;

            return $leaveCount;
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('dfghj' . $e->getMessage());
            FlashMessageHelper::flashError('An error occurred while processing your request. Please try again later.');
        } catch (\Exception $e) {
            FlashMessageHelper::flashError('An unexpected error occurred. Please try again later.');
        }
    }


    public $showAccordion = true;
    public function dateClicked($date)
    {
        try {
            $this->showAccordion = true;
            $this->selectedDate = Carbon::createFromDate($this->year, $this->month, (int)$date)->format('Y-m-d');
            $this->loadLeaveTransactions($this->selectedDate);
        } catch (\Exception $e) {
            FlashMessageHelper::flashError('An error occurred while processing your request. Please try again later.');
            return redirect()->back();
        }
    }


    public function render()
    {
        try {
            $holidays = $this->getHolidays();
            return view('livewire.hr-leave-calendar', [
                'holidays' => $holidays,
                'leaveTransactions' => $this->leaveTransactions,
                'backgroundColor' => $this->backgroundColor
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            // Handle database query exceptions
            FlashMessageHelper::flashError('An error occurred while processing your request. Please try again later.');
        } catch (\Exception $e) {
            Log::error('Database query error: ' . $e->getMessage());
            // Handle other general exceptions
            FlashMessageHelper::flashError('An 381 unexpected error occurred. Please try again later.');
        }
    }

    public function getHolidays()
    {
        try {
            // Extract only the date part before the space
            $dateParts = explode(' ', $this->selectedDate);
            $dateOnly = $dateParts[0]; // Take only the date part

            // Get only the first two characters for the date part
            $dateFormatted = substr($dateOnly, 0, 10);

            // Parse the cleaned date
            $clickedDate = Carbon::parse($dateFormatted);

            return HolidayCalendar::whereDate('date', $clickedDate->toDateString())->get();
        } catch (\Illuminate\Database\QueryException $e) {
            // Handle database query exceptions
            FlashMessageHelper::flashError('An error occurred while processing your request. Please try again later.');
        } catch (\Exception $e) {
            // Handle other general exceptions
            FlashMessageHelper::flashError('An 404 unexpected error occurred. Please try again later.');
        }
    }
    public function calculateNumberOfDays($fromDate, $fromSession, $toDate, $toSession, $leaveType)
    {
        try {
            $startDate = Carbon::parse($fromDate);
            $endDate = Carbon::parse($toDate);

            // Check if the start or end date is a weekend
            if ($startDate->isWeekend() || $endDate->isWeekend()) {
                return 0;
            }

            // Check if the start and end sessions are different on the same day
            if (
                $startDate->isSameDay($endDate) &&
                $this->getSessionNumber($fromSession) === $this->getSessionNumber($toSession)
            ) {
                // Inner condition to check if both start and end dates are weekdays
                if (!$startDate->isWeekend() && !$endDate->isWeekend()) {
                    return 0.5;
                } else {
                    // If either start or end date is a weekend, return 0
                    return 0;
                }
            }

            if (
                $startDate->isSameDay($endDate) &&
                $this->getSessionNumber($fromSession) !== $this->getSessionNumber($toSession)
            ) {
                // Inner condition to check if both start and end dates are weekdays
                if (!$startDate->isWeekend() && !$endDate->isWeekend()) {
                    return 1;
                } else {
                    // If either start or end date is a weekend, return 0
                    return 0;
                }
            }

            $totalDays = 0;

            while ($startDate->lte($endDate)) {
                if ($leaveType == 'Sick Leave') {
                    $totalDays += 1;
                } else {
                    if ($startDate->isWeekday()) {
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
                // If start and end sessions are the same, check if the session is not 1
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
            FlashMessageHelper::flashError('An error occured while calculating the no. of days.');
        }
    }
    private function getSessionNumber($session)
    {
        // You might need to customize this based on your actual session values
        return (int) str_replace('Session ', '', $session);
    }
    public function downloadexcelforLeave()
    {
        try {
            $employeeId = auth()->guard('hr')->user()->emp_id;
            $startDate = Carbon::create($this->year, $this->month, 1)->startOfMonth();
            $endDate = Carbon::create($this->year, $this->month, 1)->endOfMonth();
            $formattedStartDate = $startDate->toDateString();
            $formattedEndDate = $endDate->toDateString();

            // Fetch company name and address based on user's company ID
            $companyIds = EmployeeDetails::where('emp_id', $employeeId)->value('company_id');
            $companyIdsArray = is_array($companyIds) ? $companyIds : json_decode($companyIds, true);

            if (count($companyIdsArray) === 1) {
                $companyName = Company::whereIn('company_id', $companyIdsArray)->value('company_name');
                $companyDetails = Company::whereIn('company_id', $companyIdsArray)
                    ->select('company_present_address', 'company_permanent_address')
                    ->first();
            } else {
                $companyName = Company::whereIn('company_id', $companyIdsArray)
                    ->where('is_parent', 'yes')
                    ->value('company_name');
                $companyDetails = Company::whereIn('company_id', $companyIdsArray)
                    ->where('is_parent', 'yes')
                    ->select('company_present_address', 'company_permanent_address')
                    ->first();
            }

            // Default values if no company info is found
            if (!$companyName || !$companyDetails) {
                $companyName = 'N/A';
                $companyDetails = (object)[
                    'company_present_address' => 'N/A',
                    'company_permanent_address' => 'N/A'
                ];
            }

            $companyAddress1 = $companyDetails->company_present_address;
            $companyAddress2 = $companyDetails->company_permanent_address;
            $concatenatedAddress = $companyAddress1 . ' ' . $companyAddress2;

            // Fetch all leave requests for the current month (no filter for MyTeam or Me)
            $employeesOnLeave = LeaveRequest::join('employee_details', 'leave_applications.emp_id', '=', 'employee_details.emp_id')
                ->where(function ($query) use ($formattedStartDate, $formattedEndDate) {
                    $query->whereBetween('from_date', [$formattedStartDate, $formattedEndDate])
                        ->orWhereBetween('to_date', [$formattedStartDate, $formattedEndDate])
                        ->orWhere(function ($subQuery) use ($formattedStartDate, $formattedEndDate) {
                            $subQuery->where('from_date', '<=', $formattedStartDate)
                                ->where('to_date', '>=', $formattedEndDate);
                        });
                })
                ->where('leave_applications.leave_status', 2)
                ->where('leave_applications.cancel_status', '!=', 2)
                ->select('employee_details.first_name', 'employee_details.last_name', 'leave_applications.from_date', 'leave_applications.to_date', 'leave_applications.leave_type', 'employee_details.emp_id')
                ->get();
            // Create a new spreadsheet
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Set header data and apply styles (with company info)
            $sheet->setCellValue('A1', $companyName);
            $sheet->setCellValue('A2', $concatenatedAddress);
            $sheet->setCellValue('A3', 'Leave Data for ' . $this->month . '/' . $this->year);

            // Header row
            $sheet->setCellValue('A4', 'Employee No');
            $sheet->setCellValue('B4', 'Name of the Employee');
            $sheet->setCellValue('C4', 'Type');
            $sheet->setCellValue('D4', 'Days');
            $sheet->setCellValue('E4', 'From Date');
            $sheet->setCellValue('F4', 'To Date');
            $sheet->setCellValue('G4', 'Remarks');

            // Apply styles to header row
            $headerStyleArray = [
                'font' => ['bold' => true],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => ['borderStyle' => Border::BORDER_THIN],
                ],
            ];
            $sheet->getStyle('A4:G4')->applyFromArray($headerStyleArray);

            // Set column widths to be the same
            foreach (range('A', 'G') as $columnID) {
                $sheet->getColumnDimension($columnID)->setWidth(20);
            }

            // Insert data
            $row = 5; // Start from row 5
            foreach ($employeesOnLeave as $eol) {
                $fromDate = Carbon::parse($eol->from_date);
                $toDate = Carbon::parse($eol->to_date);
                $numberOfDays = $fromDate->diffInDays($toDate) + 1;

                $sheet->setCellValue('A' . $row, $eol->emp_id);
                $sheet->setCellValue('B' . $row, $eol->first_name . ' ' . $eol->last_name);
                $sheet->setCellValue('C' . $row, $eol->leave_type);
                $sheet->setCellValue('D' . $row, $numberOfDays);
                $sheet->setCellValue('E' . $row, $fromDate->format('d M,Y'));
                $sheet->setCellValue('F' . $row, $toDate->format('d M,Y'));
                $sheet->setCellValue('G' . $row, '');

                // Apply borders to all data rows
                $sheet->getStyle('A' . $row . ':G' . $row)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                $row++;
            }

            // Save the file
            $writer = new Xlsx($spreadsheet);
            $filePath = storage_path('app/leave_calendar.xlsx');
            $writer->save($filePath);

            // Return file for download
            return response()->download($filePath, 'leave_calendar.xlsx');
        } catch (\Exception $e) {
            FlashMessageHelper::flashError('An error occurred while processing your request. Please try again later.');
        }
    }
}

<?php

namespace App\Livewire;

use App\Exports\AbsentEmployeesExport;
use App\Exports\AbsentEmployeesReportExport;
use App\Exports\AttendanceMusterReportExport;
use App\Exports\ShiftSummaryExport;
use App\Helpers\FlashMessageHelper;
use App\Models\AddFavoriteReport;
use DatePeriod;
use Livewire\Component;
use Carbon\Carbon;
use App\Models\EmployeeDetails;
use Illuminate\Support\Facades\Auth;
use App\Models\LeaveRequest;
use App\Models\EmployeeLeaveBalances;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use App\Helpers\LeaveHelper;
use App\Models\Company;
use App\Models\EmpParentDetails;
use App\Models\EmpPersonalInfo;
use App\Models\EmpSpouseDetails;
use App\Models\HolidayCalendar;
use App\Models\SwipeRecord;
use DateInterval;
use DateTime;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\SimpleExcel\SimpleExcelWriter;

class ReportsManagement extends Component
{
    public $activeSection = 'All';
    public $currentSection = " ";
    public $reportsGallery;
    public $fromDate;
    public $toDate;
    public $employeeType = 'active';
    public $leaveType = 'all';
    public $sortBy;
    public $transactionType = 'all';
    public $leaveBalance = [];

    public $selection = 'all';
    public $EmployeeId=[];
    public $employeesForSelection;
    public $filteredEmployees;
    public $searching = 0;
    public $notFound;

    public $formattedWorkHrsMinutes;
    public $excessHrsMinutes;
    public $holiday;
    public $employees;
    public $search;

    public $selectedEmployees=[];
    public $currentDate;
    public $isToggleSelectedEmployee=false;

    public $isToggleSelectedEmployeeForAttendanceMuster=false;
    public $offDayCount = 0;

    public $workingDayCount = 0;

    public $reportOutputType;
    public $totalDayCount = 0;
    public $employeeTypeForAttendance;

    public $employeeTypeForAttendanceMuster;
    public function updateLeaveType()
    {
        $this->leaveType = $this->leaveType;
       
    }

    public function updateEmployeeId()
    {
       $this->EmployeeId=$this->EmployeeId;
    }

    public function toggleSelectedEmployee()
    {
        $this->isToggleSelectedEmployee=!$this->isToggleSelectedEmployee;
    }

    public function toggleSelectedEmployeeForAttendanceMuster()
    {
        $this->isToggleSelectedEmployeeForAttendanceMuster=!$this->isToggleSelectedEmployeeForAttendanceMuster;
    }
    public function  updateSortBy()
    {
        $this->sortBy = $this->sortBy;
    }
    public  function updateTransactionType($event)
    {
        $this->transactionType = $event;
    }

    public function updatereportOutputType()
    {
        $this->reportOutputType=$this->reportOutputType;
    }
    

   public function toggleSelection($selection)
   {
       $this->selection = $selection; // Update the selection state
   }


    public function close()
    {
        $this->currentSection = '';
        $this->resetErrorBag();
        $this->resetFields();
    }

    public function updateFromDate()
    {
        $this->fromDate = $this->fromDate;
    }
    public function updateToDate()
    {
        $this->toDate = $this->toDate;
    }

    protected $rules = [
        'fromDate' => 'required|date',
        'toDate' => 'required|date|after_or_equal:fromDate',
        'leaveType' => 'required',
    ];
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
        $this->resetErrorBag($propertyName); // Clear errors for the updated property
    }

    public function mount() {
        $this->currentDate = now()->toDateString();
        $this->employeeTypeForAttendance='allEmployees';
        $this->employeeTypeForAttendanceMuster='allEmployees';
        $this->getReportsData();

    }

    public function getReportsData()
    {
        $this->reportsGallery = AddFavoriteReport::all();
       
    }
    
   

public function downloadAbsentReportInExcel()
{
    Log::info('Starting downloadAbsentReportInExcel method.');

    // Step 1: Get active employees
    $this->selectedEmployees = EmployeeDetails::where('employee_status', 'active')->whereIn('emp_id',['XSS-0477','XSS-0479','XSS-0488'])->pluck('emp_id')->toArray();
    Log::info('Selected employees:', ['selectedEmployees' => $this->selectedEmployees]);

    $employees = EmployeeDetails::whereIn('emp_id', $this->selectedEmployees)->get();
    Log::info('Fetched employee details:', ['count' => $employees->count()]);

    $missingData = [];

    foreach ($employees as $employee) {
        $empId = $employee['emp_id'];
        Log::info("Processing employee: {$empId}");
        $startDate = new DateTime($this->fromDate);
        $endDate = new DateTime($this->toDate);
        // Step 2: Fetch approved leave requests
        $approvedLeaveRequests = LeaveRequest::join('employee_details', 'leave_applications.emp_id', '=', 'employee_details.emp_id')
            ->where('leave_applications.leave_status', 2)
            ->where('leave_applications.emp_id', $empId)
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('leave_applications.from_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
                      ->orWhereBetween('leave_applications.to_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')]);
            })
            ->get(['leave_applications.*', 'employee_details.first_name', 'employee_details.last_name']);

        Log::info("Approved leave requests fetched for employee {$empId}:", ['count' => $approvedLeaveRequests->count()]);

        // Step 3: Fetch swipe records (earliest 'IN' records per day)
        $subquery = SwipeRecord::select(DB::raw('MIN(id) as min_id'))
            ->whereIn('emp_id', $this->selectedEmployees)
            ->where('in_or_out', 'IN')
            ->whereBetween(DB::raw('DATE(created_at)'), [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->groupBy(DB::raw('DATE(created_at)'));

        $swipeRecords = SwipeRecord::whereIn('id', function ($query) use ($subquery) {
                $query->fromSub($subquery, 'sub');
            })
            ->select('emp_id', DB::raw('DATE(created_at) as swipe_date'))
            ->get();

        Log::info("Swipe records fetched for employee {$empId}:", ['count' => $swipeRecords->count()]);

        // Step 4: Define date range
        $startDate = new DateTime($this->fromDate);
        $endDate = new DateTime($this->toDate);
        Log::info("Date range set from {$startDate->format('Y-m-d')} to {$endDate->format('Y-m-d')}");

        // Step 5: Extract leave dates
        $leaveDates = $approvedLeaveRequests->flatMap(function ($leaveRequest) {
            $fromDate = Carbon::parse($leaveRequest->from_date);
            $toDate = Carbon::parse($leaveRequest->to_date);
            $dates = [];
            while ($fromDate <= $toDate) {
                $dates[] = $fromDate->format('Y-m-d');
                $fromDate->addDay();
            }
            return $dates;
        })->unique();

        Log::info("Leave dates for employee {$empId}:", ['leaveDates' => $leaveDates]);

        // Step 6: Fetch holidays
        $holidays = HolidayCalendar::pluck('date')->map(function ($date) {
            return Carbon::parse($date)->format('Y-m-d');
        })->toArray();

        Log::info("Holidays retrieved:", ['holidays' => $holidays]);

        // Step 7: Collect all working days in the given range
        $datesInMonth = collect();
        $date = Carbon::parse($startDate);
        while ($date <= $endDate) {
            if (!in_array($date->format('Y-m-d'), $leaveDates->toArray()) &&
                !in_array($date->format('Y-m-d'), $holidays) &&
                !$date->isWeekend()) {
                $datesInMonth->push($date->format('Y-m-d'));
            }
            $date->addDay();
        }

        Log::info("Working dates calculated:", ['datesInMonth' => $datesInMonth]);

        // Step 8: Check for missing swipe records
        foreach ($datesInMonth as $date) {
            foreach ($this->selectedEmployees as $empId) {
                if (!$swipeRecords->contains(function ($record) use ($date, $empId) {
                    return $record->swipe_date === $date && $record->emp_id === $empId;
                })) {
                    $missingData[] = [$empId, $date];
                }
            }
        }
    }

    Log::info("Final missing data for absent report:", ['missingData' => $missingData]);

    if (empty($missingData)) {
        Log::warning("No missing data found for absent report.");
        return back()->with('error', 'No data available for export.');
    }

    // Step 9: Generate and download Excel report
    return Excel::download(new AbsentEmployeesExport($missingData), 'absent_report_employees.xlsx');
}

    public function downloadFamilyDetailsReport()
    {
        $rows = [];
        $data = [
            ['List of Family Details of Employees'],
            ['Employee Number', 'Employee Name', 'Joined On',    'Company',    'Designation',    'Member Name',    'Relation', 'Date of Birth',    'Gender', 'Blood Group', 'Nationality', 'Profession'],
 
        ];
        $rows=$data;
        $employees2=EmployeeDetails::where('employee_status','active')->get();
        foreach ($employees2 as $employee) {

            $selfData = [
                $employee['emp_id'],
                ucwords(strtolower($employee['first_name'])) . ' ' . ucwords(strtolower($employee['last_name'])),
                isset($employee['hire_date']) ? \Carbon\Carbon::parse($employee['hire_date'])->format('jS F Y') : 'NA',
                isset($employee['company_id']) ? Company::where('company_id', $employee['company_id'])->value('company_name') :'NA'
                ,
                isset($employee['job_role'])? $employee['job_role']:'NA',
                ucwords(strtolower($employee['first_name'])) . ' ' . ucwords(strtolower($employee['last_name'])),
                'self',
                isset($employee['date_of_birth'])? EmpPersonalInfo::where('emp_id',$employee['emp_id'])->value('date_of_birth'):'NA',
                isset($employee['gender'])? ucwords(strtolower($employee['gender'])):'NA',
                isset($employee['blood_group']) ? EmpPersonalInfo::where('emp_id',$employee['emp_id'])->value('blood_group'):'NA',
                isset($employee['nationality'])? EmpPersonalInfo::where('emp_id',$employee['emp_id'])->value('nationality'):'NA',
                isset($employee['job_role'])? $employee['job_role']:'NA',
            ];
            
            // Add the employee's own data row to the rows array
            $rows[] = $selfData;
            $parent_details_exists=EmpParentDetails::where('emp_id', $employee['emp_id'])->exists();
            $employee_is_married=EmpSpouseDetails::where('emp_id', $employee['emp_id'])->exists();
            $employee_has_children = DB::table('emp_spouse_details')->where('emp_id', $employee['emp_id'])
            ->whereNotNull('children')
            ->where('children', '!=', '[]')
            ->exists();
            $parentdetails=EmpParentDetails::where('emp_id', $employee['emp_id'])->first();  
            $spouseDetails=EmpSpouseDetails::where('emp_id', $employee['emp_id'])->first();
            if ($parent_details_exists) {
                // Data row for the parent's details
                $motherData = [
                    $employee['emp_id'],
                    ucwords(strtolower($employee['first_name'])) . ' ' . ucwords(strtolower($employee['last_name'])),
                    isset($employee['hire_date']) ? \Carbon\Carbon::parse($employee['hire_date'])->format('jS F Y') : 'NA',
                    isset($employee['company_id']) ? Company::where('company_id', $employee['company_id'])->value('company_name') :'NA',
                    isset($employee['job_role']) ? $employee['job_role']:'NA',
                    isset($parentdetails->mother_first_name) ? ucwords(strtolower($parentdetails->mother_first_name)) . ' ' . ucwords(strtolower($parentdetails->mother_last_name)):'NA',
                    'mother',
                    isset($parentdetails->mother_dob) ? \Carbon\Carbon::parse($parentdetails->mother_dob)->format('jS F Y'):'NA',
                    'Female',
                    isset($parentdetails->mother_blood_group) ? $parentdetails->mother_blood_group :'NA',
                    isset($parentdetails->mother_nationality) ? $parentdetails->mother_nationality :'NA',
                    isset($parentdetails->mother_occupation) ? ucwords(strtolower($parentdetails->mother_occupation)):'NA'
                ];
                $fatherData = [
                    $employee['emp_id'],
                    ucwords(strtolower($employee['first_name'])) . ' ' . ucwords(strtolower($employee['last_name'])),
                    isset($employee['hire_date']) ? \Carbon\Carbon::parse($employee['hire_date'])->format('jS F Y') : 'NA',
                    isset($employee['company_id']) ? Company::where('company_id', $employee['company_id'])->value('company_name') :'NA',
                    isset($employee['job_role']) ? ucwords(strtolower($employee['job_role'])):'NA',
                    ucwords(strtolower($parentdetails->father_first_name)) . ' ' . ucwords(strtolower($parentdetails->father_last_name)),
                    'Father',
                    \Carbon\Carbon::parse($parentdetails->father_dob)->format('jS F Y'),
                    'Male',
                    $parentdetails->father_blood_group,
                    ucwords(strtolower($parentdetails->father_nationality)),
                    ucwords(strtolower($parentdetails->father_occupation))
                ];
     
                // Add the parent's details row to the rows array
                $rows[] = $motherData;
                $rows[]= $fatherData;
            }
            if ($employee_is_married) {
                $spouseDetails = DB::table('emp_spouse_details')->where('emp_id', $employee['emp_id'])->first(); // Use first() instead of get() to get a single record
                if ($spouseDetails) {
                    // Data row for the parent's details
                    if($spouseDetails->gender=='Female')
                    {
                            $spouseData = [
                                $employee['emp_id'],
                                $employee['first_name'] . ' ' . $employee['last_name'],
                                isset($employee['hire_date']) ? \Carbon\Carbon::parse($employee['hire_date'])->format('jS F Y') : 'NA',
                                isset($employee['company_id']) ? Company::where('company_id', $employee['company_id'])->value('company_name') :'NA',
                                isset($employee['job_role']) ? $employee['job_role']:'NA',
                                ucwords(strtolower($spouseDetails->name)),
                                'wife',
                                \Carbon\Carbon::parse($spouseDetails->dob)->format('jS F Y'),
                                'Female',
                                $spouseDetails->bld_group,
                                ucwords(strtolower($spouseDetails->nationality)),
                                ucwords(strtolower($spouseDetails->profession))
                            ];
                        }
                        else
                        {
                            $spouseData = [
                                $employee['emp_id'],
                                $employee['first_name'] . ' ' . $employee['last_name'],
                                isset($employee['hire_date']) ? \Carbon\Carbon::parse($employee['hire_date'])->format('jS F Y') : 'NA',
                                isset($employee['company_id']) ? Company::where('company_id', $employee['company_id'])->value('company_name') :'NA',
                                isset($employee['job_role']) ? $employee['job_role']:'NA',
                                ucwords(strtolower($spouseDetails->name)),
                                'Husband',
                                \Carbon\Carbon::parse( $spouseDetails->dob)->format('jS F Y'),
                                'Male',
                                $spouseDetails->bld_group,
                                ucwords(strtolower($spouseDetails->nationality)),
                                ucwords(strtolower($spouseDetails->profession))
                            ];
                        }
                       
                       
                       
                    // Add the parent's details row to the rows array
                    $rows[] = $spouseData;
                    if ($employee_has_children) {
                        $childrenDetails = DB::table('emp_spouse_details')->where('emp_id', $employee['emp_id'])->select('children')->first();
                       
                        $children = json_decode($childrenDetails->children, true);
                       
                        if (is_array($children)) {
                         
                            foreach ($children as $child) {
                           
                               if($child['gender']=='female'||$child['gender']=='Female'||$child['gender']=='FEMALE')
                               {
                                $childrenData = [
                                    $employee['emp_id'],
                                    $employee['first_name'] . ' ' . $employee['last_name'],
                                    isset($employee['hire_date']) ? \Carbon\Carbon::parse($employee['hire_date'])->format('jS F Y') : 'NA',
                                    isset($employee['company_id']) ? Company::where('company_id', $employee['company_id'])->value('company_name') :'NA',
                                    isset($employee['job_role']) ? $employee['job_role']:'NA',
                                    $child['name'],
                                    'daughter',
                                    isset($child['dob']) ? \Carbon\Carbon::parse( $child['dob'])->format('jS F Y'):'NA',
                                    isset($child['gender'])? $child['gender']:'NA',
                                    isset($child['blood_group']) ? $child['blood_group'] : 'NA', // Assuming blood group is not available for children
                                    isset($child['nationality']) ? $child['nationality'] : 'NA', // Assuming nationality is not available for children
                                    'NA'  // Assuming occupation is not applicable for children
                                ];
                               }
                               else
                               {
                                        $childrenData = [
                                            $employee['emp_id'],
                                            $employee['first_name'] . ' ' . $employee['last_name'],
                                            isset($employee['hire_date']) ? \Carbon\Carbon::parse($employee['hire_date'])->format('jS F Y') : 'NA',
                                            isset($employee['company_id']) ? Company::where('company_id', $employee['company_id'])->value('company_name') :'NA',
                                            isset($employee['job_role']) ? $employee['job_role']:'NA',
                                            $child['name'],
                                            'son',
                                            isset($child['dob'] ) ? \Carbon\Carbon::parse( $child['dob'] )->format('jS F Y'):'NA',
                                            isset($child['age'] ) ? $child['age']:'NA',
                                            isset($child['school'] ) ? $child['school']:'NA',
                                            isset($child['gender']) ? $child['gender']:'NA',
                                            isset($child['blood_group']) ?  $child['blood_group'] :'NA', // Assuming blood group is not available for children
                                            isset($child['nationality']) ? $child['nationality']:'NA', // Assuming nationality is not available for children
                                            'NA'  // Assuming occupation is not applicable for children
                                        ];
                            }
                                // Add the child's details row to the rows array
                                $rows[] = $childrenData;
                            }
                        }
                       
                        }
                   
                   
                }
            }
           
        }
        $filePath = storage_path('app/family_reports.xlsx');
        SimpleExcelWriter::create($filePath)->addRows($rows);
        return response()->download($filePath, 'family_reports.xlsx');
          
    }
    // Method to switch between tabs
    public function toggleSection($tab)
    {
        $this->activeSection = $tab;
    }
    public function showContent($section)
    {
        $this->currentSection = $section;
        $this->resetFields();
    }
    public function resetFields()
    {
        $this->fromDate = null;
        $this->toDate = null;
        $this->leaveType = 'all';
        $this->transactionType = 'all';
        $this->leaveBalance = [];
        $this->employeeType = 'active';
        $this->sortBy = 'newest_first';
        $this->employeeTypeForAttendance='allEmployees';
        $this->EmployeeId=[];
        $this->employeesForSelection='all';
    }

    public function toggleStarred($id)
    {
        try {
            // Find the report by ID
            $report = AddFavoriteReport::find($id);

            // Check if the report exists
            if (!$report) {
                FlashMessageHelper::flashError('Report not found');
            }

            if ($report->favorite == true) {
                $report->favorite = false;
                $report->save();
                FlashMessageHelper::flashSuccess('Removed from favorite successfully!');
            } elseif ($report->favorite == false) {
                $report->favorite = true;
                $report->save();
                FlashMessageHelper::flashSuccess('Added to favorite successfully!');
            }
            $this->getReportsData();
            // Flash a success message
        } catch (\Exception $e) {
            // Handle any errors that occur
            FlashMessageHelper::flashError('An error occurred: ' . $e->getMessage());
        }
    }

    public function downloadLeaveAvailedReportInExcel()
    {
        $this->validate([
            'fromDate' => 'required|date',
            'toDate' => 'required|date|after_or_equal:fromDate',
        ], [
            'fromDate.required' => 'From date is required.',
            'toDate.required' => 'To date is required.',
            'toDate.after_or_equal' => 'To date must be a date after or equal to the from date.',
        ]);
        try {

            $loggedInEmpId = Auth::guard('hr')->user()->emp_id;

            $query = LeaveRequest::select(
                DB::raw('DATE(from_date) as date_only'),
                DB::raw('count(*) as total_requests'),
                'leave_applications.emp_id',
                'employee_details.first_name',
                'employee_details.last_name',
                'leave_applications.leave_type',
                'leave_applications.from_date as leave_from_date',
                'leave_applications.to_date as leave_to_date',
                'leave_applications.reason',
                'leave_applications.created_at',
                'leave_applications.from_session',
                'leave_applications.to_session',
                'leave_applications.leave_status',
                'leave_applications.updated_at',
            )
                ->join('employee_details', 'leave_applications.emp_id', '=', 'employee_details.emp_id')
                ->where('leave_applications.leave_status', 2)
                ->when($this->leaveType && $this->leaveType != 'all', function ($query) {

                    $leaveTypes = [
                        'lop' => 'Loss of Pay',
                        'casual_leave' => 'Casual Leave',
                        'sick' => 'Sick Leave',
                        'petarnity' => 'Petarnity Leave',
                        'casual_leave_probation' => 'Casual Leave Probation',
                        'maternity' => 'Maternity Leave',
                        'marriage_leave' => 'Marriage Leave',
                    ];

                    if (array_key_exists($this->leaveType, $leaveTypes)) {
                        $query->where('leave_applications.leave_type', $leaveTypes[$this->leaveType]);
                    }
                })
                ->where(function ($query) {
                    $query->whereBetween('from_date', [$this->fromDate, $this->toDate])
                        ->orWhereBetween('to_date', [$this->fromDate, $this->toDate])
                        ->orWhere(function ($query) {
                            $query->where('from_date', '<=', $this->toDate)
                                ->where('to_date', '>=', $this->fromDate);
                        });
                });

            if ($this->employeeType == 'active') {
                $query->where(function ($query) {
                    $query->where('employee_details.employee_status', 'active')
                        ->orWhere('employee_details.employee_status', 'on-probation');
                });
            } else {
                $query->where(function ($query) {
                    $query->where('employee_details.employee_status', 'resigned')
                        ->orWhere('employee_details.employee_status', 'terminated');
                });
            }


            $query = $query->groupBy(
                'date_only',
                'leave_applications.emp_id',
                'employee_details.first_name',
                'employee_details.last_name',
                'leave_applications.leave_type',
                'leave_applications.from_date',
                'leave_applications.to_date',
                'leave_applications.reason',
                'leave_applications.created_at',
                'leave_applications.from_session',
                'leave_applications.to_session',
                'leave_applications.leave_status',
                'leave_applications.updated_at',
            )
                ->orderBy('date_only', 'asc')
                ->get();



            // Aggregate data by date
            $aggregatedData = $query->groupBy('date_only')->map(function ($group) {
                return [
                    'date' => Carbon::parse($group->first()->date_only)->format('d M Y'),
                    'details' => $group->map(function ($item) {
                        $leaveRequest = new LeaveRequest();
                        $leaveDays = $leaveRequest->calculateLeaveDays(
                            $item->leave_from_date,
                            $item->from_session,
                            $item->leave_to_date,
                            $item->to_session,
                            $item->leave_type
                        );
                        $employeeDetails = EmployeeDetails::where('emp_id', $item->emp_id)->first();

                        // Get manager details using the manager_id from the employee's details
                        $managerDetails = $employeeDetails ? EmployeeDetails::where('emp_id', $employeeDetails->manager_id)->first() : null;

                        $managerId = $managerDetails ? $managerDetails->emp_id : null;
                        $managerFirstName = $managerDetails ? $managerDetails->first_name : null;
                        $managerLastName = $managerDetails ? $managerDetails->last_name : null;

                        return [
                            'emp_id' => $item->emp_id,
                            'first_name' => $item->first_name,
                            'last_name' => $item->last_name,
                            'leave_type' => $item->leave_type,
                            'leave_from_date' => $item->leave_from_date,
                            'leave_to_date' => $item->leave_to_date,
                            'reason' => $item->reason,
                            'created_at' => $item->created_at,
                            'updated_at' => $item->updated_at,
                            'from_session' => $item->from_session,
                            'to_session' => $item->to_session,
                            'leave_days' => $leaveDays,
                            'leave_status' => $item->status,
                            'managerDetails' => $managerDetails,
                            'manager_id' => $managerId,
                            'manager_first_name' => $managerFirstName,
                            'manager_last_name' => $managerLastName,
                            'manager_first_name' => $managerFirstName,
                            'manager_last_name' => $managerLastName,
                        ];
                    })
                ];
            });



            $employeeDetails = EmployeeDetails::where('emp_id', $loggedInEmpId)->first();
            $pdf = Pdf::loadView('leaveAvailedReportPdf', [
                'employeeDetails' => $employeeDetails,
                'leaveTransactions' => $aggregatedData,
                'fromDate' => $this->fromDate,
                'toDate' => $this->toDate,
            ]);
            $this->currentSection = '';
            FlashMessageHelper::flashSuccess('Leave Availed Report Downloaded Successfully!');

            return response()->streamDownload(function () use ($pdf) {
                echo $pdf->stream();
            }, 'leave_availed_report.pdf');
        } catch (\Exception $e) {

            Log::error('Error generating Leave Availed Report', [
                'exception' => $e, // Logs the exception details
            ]);
            // Optionally, return a user-friendly error message
            FlashMessageHelper::flashError('An error occurred while generating the report. Please try again.');
        }
    }

    public function downloadAttendanceMusterReportInExcel()
{ 
    Log::info('Starting downloadAttendanceMusterReportInExcel function');

    if ($this->fromDate && $this->toDate) {
        $fromDate = Carbon::parse($this->fromDate);
        $toDate = Carbon::parse($this->toDate);
        if(!empty($this->EmployeeId))
        {
            $employees = EmployeeDetails::whereIn('emp_id',$this->EmployeeId)->whereNotIn('employee_status', ['terminated', 'resigned'])->get();    
        }
        else
        {
            $employees = EmployeeDetails::whereNotIn('employee_status', ['terminated', 'resigned'])->get();    
        }
        Log::info('Date range provided: from ' . $fromDate->format('Y-m-d') . ' to ' . $toDate->format('Y-m-d'));

        return Excel::download(new AttendanceMusterReportExport($this->fromDate, $this->toDate,$employees), 'attendance_muster_report.xlsx');
    } else {
        Log::info('No date range provided, generating empty report');
        return Excel::download(new AttendanceMusterReportExport(null, null,null), 'attendance_muster_report.xlsx');
    }

    
}
    public function getDatesAndWeeknames()
    {
        try
        {

        
                $fromDate = Carbon::parse($this->fromDate);
                $toDate = Carbon::parse($this->toDate);

                $period = new DatePeriod(
                    new DateTime($fromDate->toDateString()),
                    new DateInterval('P1D'),
                    (new DateTime($toDate->toDateString()))->modify('+1 day')
                );

                $datesAndWeeknames = [];

                foreach ($period as $date) {
                    $datesAndWeeknames[] = [
                        'date' => $date->format('Y-m-d'),
                        'weekname' => $date->format('D') // D gives the day of the week in short form (Mon, Tue, etc.)
                    ];
                }

                return $datesAndWeeknames;
        }catch (\Exception $e) {
            Log::error('Error updating selected year: ' . $e->getMessage());
            session()->flash('error', 'An error occurred while updating the selected year. Please try again.');
        }
    }

    
    public function downloadShiftSummaryReport()
{
    // Log the start of the method execution
    Log::info('Welcome to Download Shift Summary Report started.');

    // Validate input dates
    Log::info('Validating dates.', ['fromDate' => $this->fromDate, 'toDate' => $this->toDate]);
    $this->validate([
        'fromDate' => 'required|date',
        'toDate' => 'required|date',
    ], [
        'fromDate.required' => 'From Date is required.',
        'toDate.required' => 'To Date is required.',
    ]);

    // Log validated dates
    Log::info('Validated Dates', ['fromDate' => $this->fromDate, 'toDate' => $this->toDate]);

    try {
        // Retrieve employees
        Log::info('Retrieving employees.');
        if (!empty($this->EmployeeId)) {
            $employees1 = EmployeeDetails::select(
                'employee_details.emp_id',
                'employee_details.first_name',
                'employee_details.last_name',
                'employee_details.shift_type',
                'employee_details.employee_status',
                'company_shifts.shift_start_time',
                'company_shifts.shift_end_time',
                'company_shifts.shift_name'
            )
            ->whereIn('employee_details.emp_id', $this->EmployeeId)
            ->join('company_shifts', 'employee_details.shift_type', '=', 'company_shifts.shift_name')
            ->get();
        } else {
            $employees1 = EmployeeDetails::select(
                'employee_details.emp_id',
                'employee_details.first_name',
                'employee_details.last_name',
                'employee_details.shift_type',
                'employee_details.employee_status',
                'company_shifts.shift_start_time',
                'company_shifts.shift_end_time',
                'company_shifts.shift_name'
            )
            ->join('company_shifts', 'employee_details.shift_type', '=', 'company_shifts.shift_name')
            ->get();
        }

        // Log number of employees retrieved
        Log::info('Employees Retrieved', ['count' => $employees1->count()]);

        // Initialize counters
        $this->offDayCount = 0;
        $this->workingDayCount = 0;
        
        // Log before processing dates
        Log::info('Processing dates and weeknames.');
        $datesAndWeeknames = $this->getDatesAndWeeknames();

        foreach ($datesAndWeeknames as $daw) {
            if ($daw['weekname'] == 'Sat' || $daw['weekname'] == 'Sun') {
                $this->offDayCount++;
            } else {
                $this->workingDayCount++;
            }
        }

        $this->totalDayCount = $this->offDayCount + $this->workingDayCount;

        // Log the computed day counts
        Log::info('Day Counts Computed', [
            'totalDayCount' => $this->totalDayCount,
            'workingDayCount' => $this->workingDayCount,
            'offDayCount' => $this->offDayCount,
        ]);

        // Prepare data for report
        $data = [];
        Log::info('Preparing data for report.');

        foreach ($employees1 as $s1) {
            if ($s1['shift_type'] == 'ES' || $s1['shift_type'] == 'AS' || $s1['shift_type'] == 'GS') {
                $data[] = [
                    'emp_id' => $s1['emp_id'],
                    'name' => ucwords(strtolower($s1['first_name'])) . ' ' . ucwords(strtolower($s1['last_name'])),
                    'total_days' => $this->totalDayCount,
                    'work_days' => $this->workingDayCount,
                    'off_day' => $this->offDayCount,
                    'shift_schedule' => Carbon::parse($s1['shift_start_time'])->format('H:i a') . ' to ' . Carbon::parse($s1['shift_end_time'])->format('H:i a'),
                    'employee_status' => $s1['employee_status'],
                ];
            }
        }

        // Log the data that is about to be written to the file
        Log::info('Data prepared for export', ['rowCount' => count($data)]);

        // Export data using Maatwebsite Excel
      
        Log::info('Exporting as XLSX.');
        return Excel::download(new ShiftSummaryExport($data, $this->fromDate, $this->toDate), 'shift_summary_report.xlsx');
      

      
       
       
    } catch (\Exception $e) {
        // Log any error that occurs
        Log::error('Error generating shift summary report', ['error' => $e->getMessage()]);

        // Return an error response
        return response()->json(['error' => 'Error generating the report. Please try again later.'], 500);
    }
}


public function downloadAbsentReport()
{
    $this->validate([
        'fromDate' => 'required|date',
        'toDate' => 'required|date',
    ], [
        'fromDate.required' => 'From Date is required.',
        'toDate.required' => 'To Date is required.',
    ]);
    $employees = EmployeeDetails::where('employee_status','active')->get();
    foreach ($employees as $employee) {
        $empId = $employee['emp_id'];
        $approvedLeaveRequests = LeaveRequest::join('employee_details', 'leave_applications.emp_id', '=', 'employee_details.emp_id')
        ->where('leave_applications.status', 2)
        ->where('leave_applications.emp_id', $empId)
        ->get(['leave_applications.*', 'employee_details.first_name', 'employee_details.last_name']);
        $subquery = SwipeRecord::select(DB::raw('MIN(id) as min_id'))
        ->where('in_or_out', 'IN')
        ->groupBy(DB::raw('DATE(created_at)'));
        $swipeRecords = SwipeRecord::whereIn('id', function ($query) use ($subquery) {
            $query->fromSub($subquery, 'sub');
        })
        ->select('emp_id', DB::raw('DATE(created_at) as swipe_date'))
        ->get();
    // Fetch swipe records for this employee
    $startOfMonth = Carbon::now()->startOfMonth();
    $endOfMonth = Carbon::now()->endOfMonth();
    
        $startDate = new DateTime($this->fromDate);
        $endDate = new DateTime($this->toDate);    
        $leaveDates = $approvedLeaveRequests->flatMap(function ($leaveRequest) {
            $fromDate = Carbon::parse($leaveRequest->from_date);
            $toDate = Carbon::parse($leaveRequest->to_date);

            // Generate an array of dates within the range
            $dates = [];
            while ($fromDate <= $toDate) {
                $dates[] = $fromDate->format('Y-m-d');
                $fromDate->addDay();
            }
            return $dates;
        })->unique();
        $holidays = HolidayCalendar::pluck('date')->map(function($date) {
            return Carbon::parse($date)->format('Y-m-d'); // Ensure dates are in 'Y-m-d' format
        })->toArray();
    // $swipeDates = $swipeRecords->map->format('Y-m-d')->unique();
   
    // Get all dates in the current month (or any range you want to cover)
    $datesInMonth = collect();
    $startOfMonth = Carbon::parse($startDate);
    $endOfMonth = Carbon::parse($endDate);
    $date = $startOfMonth->copy();
    $missingData = [];
    while ($date <= $endOfMonth) {
        if (!in_array($date->format('Y-m-d'), $leaveDates->toArray()) &&
            !in_array($date->format('Y-m-d'), $holidays)&& 
            !$date->isWeekend()) {
            $datesInMonth->push($date->format('Y-m-d'));
        }
        $date->addDay();
    }
    foreach ($datesInMonth as $date) {
        foreach ($this->selectedEmployees as $empId) {
            // Check if the date and employee ID are not present in swipe records
            if (!$swipeRecords->contains(function ($record) use ($date, $empId) {
                return $record->swipe_date === $date && $record->emp_id === $empId;
            })) {
                // If not present, add to the missing data list
                $missingData[] = [$empId, $date];
            }
        }
    }
     }
     $filePath = storage_path('app/absent_employees.xlsx');

    // Create the Excel file
    $writer = SimpleExcelWriter::create($filePath);

    // Add a header row
    $writer->addRow(['Emp ID', 'Absent Date']);

    // Add the data rows
    foreach ($missingData as $row) {
        $writer->addRow($row);
    }
    // Close the writer to ensure the file is saved
    $writer->close();

    // Return the file as a download response
    return response()->download($filePath, 'absent_employees.xlsx');
   
    
}
    public function downloadNegativeLeaveBalanceReport()
    {
        $this->validate([
            'toDate' => 'required|date',
        ], [
            'toDate.required' => 'Date is required.',
        ]);
        try {



            $loggedInEmpId = Auth::guard('hr')->user()->emp_id;

            $query = LeaveRequest::select(
                DB::raw('DATE(to_date) as date_only'),
                DB::raw('count(*) as total_requests'),
                'leave_applications.emp_id',
                'employee_details.first_name',
                'employee_details.last_name',
                'employee_details.hire_date',
                'employee_details.job_role',
                'leave_applications.leave_type',
                'leave_applications.from_date as leave_from_date',
                'leave_applications.to_date as leave_to_date',
                'leave_applications.from_session',
                'leave_applications.to_session',
                'leave_applications.leave_status'
            )
                ->join('employee_details', 'leave_applications.emp_id', '=', 'employee_details.emp_id')
                ->when($this->leaveType && $this->leaveType != 'all', function ($query) {

                    $leaveTypes = [
                        'lop' => 'Loss of Pay',
                        'casual_leave' => 'Casual Leave',
                        'sick' => 'Sick Leave',
                        'petarnity' => 'Petarnity Leave',
                        'casual_leave_probation' => 'Casual Leave Probation',
                        'maternity' => 'Maternity Leave',
                        'marriage_leave' => 'Marriage Leave',
                    ];

                    if (array_key_exists($this->leaveType, $leaveTypes)) {
                        $query->where('leave_applications.leave_type', $leaveTypes[$this->leaveType]);
                    }
                })

                ->where('to_date', '<=', $this->toDate)
                ->where('leave_applications.leave_status', 2);




            if ($this->employeeType == 'active') {
                $query->where(function ($query) {
                    $query->where('employee_details.employee_status', 'active')
                        ->orWhere('employee_details.employee_status', 'on-probation');
                });
            } else {
                $query->where(function ($query) {
                    $query->where('employee_details.employee_status', 'resigned')
                        ->orWhere('employee_details.employee_status', 'terminated');
                });
            }



            $query = $query->groupBy(
                'date_only',
                'leave_applications.emp_id',
                'employee_details.first_name',
                'employee_details.last_name',
                'employee_details.hire_date',
                'employee_details.job_role',
                'leave_applications.leave_type',
                'leave_applications.to_date',
                'leave_applications.from_date',
                'leave_applications.from_session',
                'leave_applications.to_session',
                'leave_applications.leave_status',
            )
                ->orderBy('date_only', 'asc')
                ->get();







            // Aggregate data by date
            $aggregatedData = $query->groupBy('date_only')->map(function ($group) {
                return [
                    'date' => Carbon::parse($group->first()->date_only)->format('d M Y'),
                    'details' => $group->map(function ($item) {
                        $leaveRequest = new LeaveRequest();
                        $leaveDays = $leaveRequest->calculateLeaveDays(
                            $item->leave_from_date,
                            $item->from_session,
                            $item->leave_to_date,
                            $item->to_session,
                            $item->leave_type
                        );
                        return [
                            'emp_id' => $item->emp_id,
                            'first_name' => $item->first_name,
                            'last_name' => $item->last_name,
                            'hire_date' => $item->hire_date,
                            'job_role' => $item->job_role,
                            'leave_type' => $item->leave_type,
                            'leave_from_date' => $item->leave_from_date,
                            'leave_to_date' => $item->leave_to_date,
                            'from_session' => $item->from_session,
                            'to_session' => $item->to_session,
                            'leave_days' => $leaveDays,
                            'leave_status' => $item->status,
                        ];
                    })
                ];
            });




            $employeeDetails = EmployeeDetails::where('emp_id', $loggedInEmpId)->first();
            $pdf = Pdf::loadView('negativeLeaveBalanceReportPdf', [
                'employeeDetails' => $employeeDetails,
                'leaveTransactions' => $aggregatedData,
                'fromDate' => $this->fromDate,
                'toDate' => $this->toDate,
            ]);
            $this->currentSection = '';
            FlashMessageHelper::flashSuccess('Negative Leave Balance Report Downloaded Successfully!');

            return response()->streamDownload(function () use ($pdf) {
                echo $pdf->stream();
            }, 'negative_leave_balance_report.pdf');
        } catch (\Exception $e) {

            Log::error('Error generating Leave Availed Report', [
                'exception' => $e, // Logs the exception details
            ]);
            // Optionally, return a user-friendly error message
            FlashMessageHelper::flashError('An error occurred while generating the report. Please try again.');
        }
    }

    public function dayWiseLeaveTransactionReport()
    {
        $this->validate(
            [
                'fromDate' => 'required|date',
                'toDate' => 'required|date|after_or_equal:fromDate',
                'transactionType' => 'required',
                'employeeType' => 'required',
            ],
            [
                'fromDate.required' => 'From date is required.',
                'toDate.required' => 'To date is required.',
                'toDate.after_or_equal' => 'To date must be a date after or equal to the from date.',
                'transactionType.required' => 'Leave type is required.',
                'employeeType.required' => 'Employee type is required.',
            ]
        );
        try {
            $loggedInEmpId = Auth::guard('hr')->user()->emp_id;
            // Log::info('Logged in employee ID: ' . $loggedInEmpId);

            if ($this->transactionType === 'granted') {

                // When the transaction type is granted, fetch data from employee_leave_balances
                // Log::info('Fetching granted leave data from employee_leave_balances.');

                // Query to fetch leave data
                $query = EmployeeLeaveBalances::select(
                    'employee_leave_balances.emp_id',
                    'employee_details.first_name',
                    'employee_details.last_name',
                    DB::raw("JSON_EXTRACT(employee_leave_balances.leave_policy_id, '$') AS leave_policy_id"),  // Extract the entire leave policy array
                    'employee_leave_balances.status',
                    'employee_leave_balances.created_at',
                    'employee_leave_balances.period'
                )
                    ->join('employee_details', 'employee_leave_balances.emp_id', '=', 'employee_details.emp_id')
                    // ->where(function ($query) use ($loggedInEmpId) {
                    //     // Fetch team members whose manager is the logged-in employee
                    //     $query->where('employee_details.manager_id', $loggedInEmpId)
                    ->whereNotIn('employee_details.employee_status', ['terminated', 'resigned'])
                    // })
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

                    ->get();


                // Log::info('Granted leave data retrieved successfully.', ['query' => $query->toArray()]);

                // Group the data by emp_id
                $grantedData = $query->groupBy('emp_id')->map(function ($group) {
                    return [
                        'emp_id' => $group->first()->emp_id,
                        'first_name' => $group->first()->first_name,
                        'last_name' => $group->first()->last_name,
                        'leave_details' => $group->map(function ($item) {
                            // Decode the JSON to get all leave types
                            $leavePolicy = json_decode($item->leave_policy_id, true);
                            $leaveTypes = [
                                'casual_probation' => 'Casual Leave',
                                'maternity' => 'Maternity Leave',
                                'paternity' => 'Paternity Leave',
                                'sick' => 'Sick Leave',
                                'lop' => 'Loss of Pay',
                            ];
                            $employeeDetails = EmployeeDetails::where('emp_id', $item->emp_id)->first();



                            // Get manager details using the manager_id from the employee's details

                            $managerDetails = $employeeDetails ? EmployeeDetails::where('emp_id', $employeeDetails->manager_id)->first() : null;



                            $managerId = $managerDetails ? $managerDetails->emp_id : null;

                            $managerFirstName = $managerDetails ? $managerDetails->first_name : null;

                            $managerLastName = $managerDetails ? $managerDetails->last_name : null;

                            // Loop through all the leave types in the JSON array
                            $leaveDetails = [];
                            foreach ($leavePolicy as $leave) {
                                if ($this->leaveType && $this->leaveType != 'all' && $leave['leave_name'] !== $leaveTypes[$this->leaveType]) {
                                    continue;  // Skip if the leave name doesn't match the selected leave type
                                }
                                $period = $item->period;

                                // Create Carbon instance for the start and end date of the year
                                $startDate = Carbon::createFromFormat('Y', $period)->firstOfYear()->toDateString(); // '2024-01-01'
                                $endDate = Carbon::createFromFormat('Y', $period)->lastOfYear()->toDateString(); // '2024-12-31'
                                $leaveDetails[] = [
                                    'leave_name' => $leave['leave_name'] ?? 'Unknown',
                                    'grant_days' => $leave['grant_days'] ?? 0,
                                    'status' => $item->status,
                                    'created_at' => $item->created_at,
                                    'from_date' => $startDate,  // The start date of the year
                                    'to_date' => $endDate,
                                    'managerDetails' => $managerDetails,

                                    'manager_id' => $managerId,

                                    'manager_first_name' => $managerFirstName,

                                    'manager_last_name' => $managerLastName,
                                ];
                            }

                            return $leaveDetails;
                        }),
                    ];
                });
            } elseif ($this->transactionType === 'lapsed') {
                $query = EmployeeLeaveBalances::select(
                    'employee_leave_balances.emp_id',
                    'employee_details.first_name',
                    'employee_details.last_name',
                    DB::raw("JSON_EXTRACT(employee_leave_balances.leave_policy_id, '$') AS leave_policy_id"),  // Extract the entire leave policy array
                    'employee_leave_balances.status',
                    'employee_leave_balances.created_at',
                    'employee_leave_balances.period'
                )
                    ->join('employee_details', 'employee_leave_balances.emp_id', '=', 'employee_details.emp_id')
                    // ->where(function ($query) use ($loggedInEmpId) {
                    //     // Fetch team members whose manager is the logged-in employee
                    //     $query->where('employee_details.manager_id', $loggedInEmpId)
                    ->whereNotIn('employee_details.employee_status', ['terminated', 'resigned'])
                    // })
                    ->where('employee_leave_balances.is_lapsed', 1)
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

                    ->get();


                // Log::info('Granted leave data retrieved successfully.', ['query' => $query->toArray()]);

                // Group the data by emp_id
                $lapsedData = $query->groupBy('emp_id')->map(function ($group) {
                    return [
                        'emp_id' => $group->first()->emp_id,
                        'first_name' => $group->first()->first_name,
                        'last_name' => $group->first()->last_name,
                        'leave_details' => $group->map(function ($item) {

                            $period = $item->period;
                            $decemberStart = Carbon::createFromFormat('Y', $period)->month(12)->startOfMonth()->toDateString(); // First day of December
                            $decemberEnd = Carbon::createFromFormat('Y', $period)->month(12)->endOfMonth()->toDateString();   // Last day of December

                            $leaveBalances = LeaveBalances::getLeaveLapsedBalances($item->emp_id, $item->period);



                            // Decode the JSON to get all leave types
                            $leaveDetails1 = json_decode($item->leave_policy_id, true);
                            $leaveDetails = [];
                            $leaveTypes = [
                                'casual_probation' => 'Casual Leave',
                                'maternity' => 'Maternity Leave',
                                'paternity' => 'Paternity Leave',
                                'sick' => 'Sick Leave',
                                'lop' => 'Loss of Pay',
                            ];
                            $employeeDetails = EmployeeDetails::where('emp_id', $item->emp_id)->first();



                            // Get manager details using the manager_id from the employee's details

                            $managerDetails = $employeeDetails ? EmployeeDetails::where('emp_id', $employeeDetails->manager_id)->first() : null;



                            $managerId = $managerDetails ? $managerDetails->emp_id : null;

                            $managerFirstName = $managerDetails ? $managerDetails->first_name : null;

                            $managerLastName = $managerDetails ? $managerDetails->last_name : null;
                            // Loop through all the leave types in the JSON array

                            foreach ($leaveTypes as $key => $leaveName) {

                                if ($this->leaveType && $this->leaveType != 'all' && $leaveName !== $leaveTypes[$this->leaveType]) {
                                    continue;  // Skip if the leave name doesn't match the selected leave type
                                }
                                $balanceKey = $this->normalizeLeaveNameToBalanceKey($leaveName);

                                // This will print the balance key for debugging

                                // Get the balance for this leave type (defaults to 0 if not found)
                                $remainingBalance = $leaveBalances[$balanceKey] ?? 0;


                                // Skip if there's no remaining balance
                                if ($remainingBalance <= 0) {
                                    continue;
                                }

                                // Add formatted leave object to the array with remaining balance (days)
                                $leaveDetails[] = [
                                    'leave_name' => $leaveName,
                                    'grant_days' => $remainingBalance,
                                    'status' => 'Lapsed',
                                    'created_at' => $item->created_at,
                                    'from_date' => $decemberStart,  // The start date of the year
                                    'to_date' => $decemberEnd,
                                    'managerDetails' => $managerDetails,

                                    'manager_id' => $managerId,

                                    'manager_first_name' => $managerFirstName,

                                    'manager_last_name' => $managerLastName,     // The end date of the year


                                ];
                            }

                            return $leaveDetails;
                        }),
                    ];
                });
            } elseif ($this->transactionType === 'all') {

                $query = EmployeeLeaveBalances::select(
                    'employee_leave_balances.emp_id',
                    'employee_details.first_name',
                    'employee_details.last_name',
                    DB::raw("JSON_EXTRACT(employee_leave_balances.leave_policy_id, '$') AS leave_policy_id"),  // Extract the entire leave policy array
                    'employee_leave_balances.status',
                    'employee_leave_balances.created_at',
                    'employee_leave_balances.period'
                )
                    ->join('employee_details', 'employee_leave_balances.emp_id', '=', 'employee_details.emp_id')
                    // ->where(function ($query) use ($loggedInEmpId) {
                    //     // Fetch team members whose manager is the logged-in employee
                    //     $query->where('employee_details.manager_id', $loggedInEmpId)
                    ->whereNotIn('employee_details.employee_status', ['terminated', 'resigned'])
                    // })
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

                    ->get();


                // Log::info('Granted leave data retrieved successfully.', ['query' => $query->toArray()]);

                // Group the data by emp_id
                $grantedData = $query->groupBy('emp_id')->map(function ($group) {
                    return [
                        'emp_id' => $group->first()->emp_id,
                        'first_name' => $group->first()->first_name,
                        'last_name' => $group->first()->last_name,
                        'leave_details' => $group->map(function ($item) {
                            // Decode the JSON to get all leave types
                            $leavePolicy = json_decode($item->leave_policy_id, true);
                            $leaveTypes = [
                                'casual_probation' => 'Casual Leave',
                                'maternity' => 'Maternity Leave',
                                'paternity' => 'Paternity Leave',
                                'sick' => 'Sick Leave',
                                'lop' => 'Loss of Pay',
                            ];

                            // Loop through all the leave types in the JSON array
                            $leaveDetails = [];
                            foreach ($leavePolicy as $leave) {
                                if ($this->leaveType && $this->leaveType != 'all' && $leave['leave_name'] !== $leaveTypes[$this->leaveType]) {
                                    continue;  // Skip if the leave name doesn't match the selected leave type
                                }
                                $period = $item->period;

                                // Create Carbon instance for the start and end date of the year
                                $startDate = Carbon::createFromFormat('Y', $period)->firstOfYear()->toDateString(); // '2024-01-01'
                                $endDate = Carbon::createFromFormat('Y', $period)->lastOfYear()->toDateString(); // '2024-12-31'
                                $employeeDetails = EmployeeDetails::where('emp_id', $item->emp_id)->first();

                                // Get manager details using the manager_id from the employee's details
                                $managerDetails = $employeeDetails ? EmployeeDetails::where('emp_id', $employeeDetails->manager_id)->first() : null;

                                $managerId = $managerDetails ? $managerDetails->emp_id : null;
                                $managerFirstName = $managerDetails ? $managerDetails->first_name : null;
                                $managerLastName = $managerDetails ? $managerDetails->last_name : null;
                                $leaveDetails[] = [
                                    'leave_name' => $leave['leave_name'] ?? 'Unknown',
                                    'grant_days' => $leave['grant_days'] ?? 0,
                                    'status' => $item->status,
                                    'created_at' => $item->created_at,
                                    'from_date' => $startDate,  // The start date of the year
                                    'to_date' => $endDate,
                                    'managerDetails' => $managerDetails,
                                    'manager_id' => $managerId,
                                    'manager_first_name' => $managerFirstName,
                                    'manager_last_name' => $managerLastName,
                                ];
                            }


                            return $leaveDetails;
                        }),

                    ];
                });


                $query = EmployeeLeaveBalances::select(
                    'employee_leave_balances.emp_id',
                    'employee_details.first_name',
                    'employee_details.last_name',
                    DB::raw("JSON_EXTRACT(employee_leave_balances.leave_policy_id, '$') AS leave_policy_id"),  // Extract the entire leave policy array
                    'employee_leave_balances.status',
                    'employee_leave_balances.created_at',
                    'employee_leave_balances.period'
                )
                    ->join('employee_details', 'employee_leave_balances.emp_id', '=', 'employee_details.emp_id')
                    // ->where(function ($query) use ($loggedInEmpId) {
                    //     // Fetch team members whose manager is the logged-in employee
                    //     $query->where('employee_details.manager_id', $loggedInEmpId)
                    ->whereNotIn('employee_details.employee_status', ['terminated', 'resigned'])
                    // })
                    ->where('employee_leave_balances.is_lapsed', 1)
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

                    ->get();


                // Log::info('Granted leave data retrieved successfully.', ['query' => $query->toArray()]);

                // Group the data by emp_id
                $lapsedData = $query->groupBy('emp_id')->map(function ($group) {
                    return [
                        'emp_id' => $group->first()->emp_id,
                        'first_name' => $group->first()->first_name,
                        'last_name' => $group->first()->last_name,
                        'leave_details' => $group->map(function ($item) {

                            $period = $item->period;
                            $decemberStart = Carbon::createFromFormat('Y', $period)->month(12)->startOfMonth()->toDateString(); // First day of December
                            $decemberEnd = Carbon::createFromFormat('Y', $period)->month(12)->endOfMonth()->toDateString();   // Last day of December

                            $leaveBalances = LeaveBalances::getLeaveLapsedBalances($item->emp_id, $item->period);



                            // Decode the JSON to get all leave types
                            $leaveDetails1 = json_decode($item->leave_policy_id, true);
                            $leaveDetails = [];
                            $leaveTypes = [
                                'casual_probation' => 'Casual Leave',
                                'maternity' => 'Maternity Leave',
                                'paternity' => 'Paternity Leave',
                                'sick' => 'Sick Leave',
                                'lop' => 'Loss of Pay',
                            ];
                            $employeeDetails = EmployeeDetails::where('emp_id', $item->emp_id)->first();

                            // Get manager details using the manager_id from the employee's details
                            $managerDetails = $employeeDetails ? EmployeeDetails::where('emp_id', $employeeDetails->manager_id)->first() : null;

                            $managerId = $managerDetails ? $managerDetails->emp_id : null;
                            $managerFirstName = $managerDetails ? $managerDetails->first_name : null;
                            $managerLastName = $managerDetails ? $managerDetails->last_name : null;

                            // Loop through all the leave types in the JSON array

                            foreach ($leaveTypes as $key => $leaveName) {

                                if ($this->leaveType && $this->leaveType != 'all' && $leaveName !== $leaveTypes[$this->leaveType]) {
                                    continue;  // Skip if the leave name doesn't match the selected leave type
                                }
                                $balanceKey = $this->normalizeLeaveNameToBalanceKey($leaveName);

                                // This will print the balance key for debugging

                                // Get the balance for this leave type (defaults to 0 if not found)
                                $remainingBalance = $leaveBalances[$balanceKey] ?? 0;


                                // Skip if there's no remaining balance
                                if ($remainingBalance <= 0) {
                                    continue;
                                }

                                // Add formatted leave object to the array with remaining balance (days)
                                $leaveDetails[] = [
                                    'leave_name' => $leaveName,
                                    'grant_days' => $remainingBalance,
                                    'status' => 'Lapsed',
                                    'created_at' => $item->created_at,
                                    'from_date' => $decemberStart,  // The start date of the year
                                    'to_date' => $decemberEnd,
                                    'managerDetails' => $managerDetails,
                                    'manager_id' => $managerId,
                                    'manager_first_name' => $managerFirstName,
                                    'manager_last_name' => $managerLastName,     // The end date of the year


                                ];
                            }

                            return $leaveDetails;
                        }),
                    ];
                });



                $query = LeaveRequest::select(
                    DB::raw('DATE(from_date) as date_only'),
                    DB::raw('count(*) as total_requests'),
                    'leave_applications.emp_id',
                    'employee_details.first_name',
                    'employee_details.last_name',
                    'leave_applications.leave_type',
                    'leave_applications.from_date as leave_from_date',
                    'leave_applications.to_date as leave_to_date',
                    'leave_applications.reason',
                    'leave_applications.created_at',
                    'leave_applications.from_session',
                    'leave_applications.to_session',
                    'leave_applications.leave_status'
                )
                    ->join('employee_details', 'leave_applications.emp_id', '=', 'employee_details.emp_id')
                    ->when($this->leaveType && $this->leaveType != 'all', function ($query) {
                        $leaveTypes = [
                            'lop' => 'Loss of Pay',
                            'casual_leave' => 'Casual Leave',
                            'sick' => 'Sick Leave',
                            'petarnity' => 'Petarnity Leave',
                            'casual_leave_probation' => 'Casual Leave Probation',
                            'maternity' => 'Maternity Leave',
                            'marriage_leave' => 'Marriage Leave',
                        ];

                        if (array_key_exists($this->leaveType, $leaveTypes)) {
                            $query->where('leave_applications.leave_type', $leaveTypes[$this->leaveType]);
                        }
                    })

                    ->where(function ($query) {
                        $query->whereBetween('from_date', [$this->fromDate, $this->toDate])
                            ->orWhereBetween('to_date', [$this->fromDate, $this->toDate])
                            ->orWhere(function ($query) {
                                $query->where('from_date', '<=', $this->toDate)
                                    ->where('to_date', '>=', $this->fromDate);
                            });
                    })
                    ->when($this->transactionType && $this->transactionType != 'all', function ($query) {
                        $transactionTypes = [
                            'availed' => 2,
                            'withdrawn' => 4,
                            'rejected' => 3,
                        ];
                        if (array_key_exists($this->transactionType, $transactionTypes)) {
                            $query->where('leave_status', $transactionTypes[$this->transactionType]);
                        }
                    })
                    ->when($this->employeeType, function ($query) {
                        if ($this->employeeType == 'active') {
                            $query->where(function ($query) {
                                $query->where('employee_details.employee_status', 'active')
                                    ->orWhere('employee_details.employee_status', 'on-probation');
                            });
                        } else {
                            $query->where(function ($query) {
                                $query->where('employee_details.employee_status', 'resigned')
                                    ->orWhere('employee_details.employee_status', 'terminated');
                            });
                        }
                    })
                    ->where('leave_status', '!=', 5)
                    ->orderBy('created_at', $this->sortBy === 'oldest_first' ? 'asc' : 'desc')
                    ->groupBy(
                        DB::raw('DATE(from_date)'),
                        'leave_applications.emp_id',
                        'employee_details.first_name',
                        'employee_details.last_name',
                        'leave_applications.leave_type',
                        'leave_applications.from_date',
                        'leave_applications.to_date',
                        'leave_applications.reason',
                        'leave_applications.created_at',
                        'leave_applications.from_session',
                        'leave_applications.to_session',
                        'leave_applications.leave_status'
                    )
                    ->get();




                // Log::info('Leave request data retrieved successfully.');

                $leaveTransactionData = $query->groupBy('date_only')->map(function ($group) {

                    return [
                        'date' => Carbon::parse($group->first()->date_only)->format('d M Y'),
                        'emp_id' => $group->first()->emp_id,  // Add emp_id
                        'first_name' => $group->first()->first_name,  // Add first_name
                        'last_name' => $group->first()->last_name,
                        'leave_details' => $group->map(function ($item) {
                            $employeeDetails = EmployeeDetails::where('emp_id', $item->emp_id)->first();

                            // Get manager details using the manager_id from the employee's details
                            $managerDetails = $employeeDetails ? EmployeeDetails::where('emp_id', $employeeDetails->manager_id)->first() : null;

                            $managerId = $managerDetails ? $managerDetails->emp_id : null;
                            $managerFirstName = $managerDetails ? $managerDetails->first_name : null;
                            $managerLastName = $managerDetails ? $managerDetails->last_name : null;
                            $leaveRequest = new LeaveRequest();
                            $leaveDays = $leaveRequest->calculateLeaveDays(
                                $item->leave_from_date,
                                $item->from_session,
                                $item->leave_to_date,
                                $item->to_session,
                                $item->leave_type
                            );
                            return [
                                'emp_id' => $item->emp_id,
                                'first_name' => $item->first_name,
                                'last_name' => $item->last_name,
                                'leave_type' => $item->leave_type,
                                'leave_from_date' => $item->leave_from_date,
                                'leave_to_date' => $item->leave_to_date,
                                'reason' => $item->reason,
                                'created_at' => $item->created_at,
                                'from_session' => $item->from_session,
                                'to_session' => $item->to_session,
                                'leave_days' => $leaveDays,
                                'leave_status' => $item->leave_status,
                                'managerDetails' => $managerDetails,
                                'manager_id' => $managerId,
                                'manager_first_name' => $managerFirstName,
                                'manager_last_name' => $managerLastName,
                            ];
                        })
                    ];
                });
                $leaveTransactions = $grantedData->concat($leaveTransactionData)->concat($lapsedData);
            } else {
                // The existing code for handling leave requests in 'availed', 'rejected', etc.
                // Log::info('Fetching leave request data for transaction type: ' . $this->transactionType);

                $query = LeaveRequest::select(
                    DB::raw('DATE(from_date) as date_only'),
                    DB::raw('count(*) as total_requests'),
                    'leave_applications.emp_id',
                    'employee_details.first_name',
                    'employee_details.last_name',
                    'leave_applications.leave_type',
                    'leave_applications.from_date as leave_from_date',
                    'leave_applications.to_date as leave_to_date',
                    'leave_applications.reason',
                    'leave_applications.created_at',
                    'leave_applications.from_session',
                    'leave_applications.to_session',
                    'leave_applications.leave_status'
                )
                    ->join('employee_details', 'leave_applications.emp_id', '=', 'employee_details.emp_id')
                    ->when($this->leaveType && $this->leaveType != 'all', function ($query) {
                        $leaveTypes = [
                            'lop' => 'Loss of Pay',
                            'casual_leave' => 'Casual Leave',
                            'sick' => 'Sick Leave',
                            'petarnity' => 'Petarnity Leave',
                            'casual_leave_probation' => 'Casual Leave Probation',
                            'maternity' => 'Maternity Leave',
                            'marriage_leave' => 'Marriage Leave',
                        ];

                        if (array_key_exists($this->leaveType, $leaveTypes)) {
                            $query->where('leave_applications.leave_type', $leaveTypes[$this->leaveType]);
                        }
                    })

                    ->where(function ($query) {
                        $query->whereBetween('from_date', [$this->fromDate, $this->toDate])
                            ->orWhereBetween('to_date', [$this->fromDate, $this->toDate])
                            ->orWhere(function ($query) {
                                $query->where('from_date', '<=', $this->toDate)
                                    ->where('to_date', '>=', $this->fromDate);
                            });
                    })
                    ->when($this->transactionType && $this->transactionType != 'all', function ($query) {
                        $transactionTypes = [
                            'availed' => 2,
                            'withdrawn' => 4,
                            'rejected' => 3,
                        ];
                        if (array_key_exists($this->transactionType, $transactionTypes)) {
                            $query->where('leave_status', $transactionTypes[$this->transactionType]);
                        }
                    })
                    ->when($this->employeeType, function ($query) {
                        if ($this->employeeType == 'active') {
                            $query->where(function ($query) {
                                $query->where('employee_details.employee_status', 'active')
                                    ->orWhere('employee_details.employee_status', 'on-probation');
                            });
                        } else {
                            $query->where(function ($query) {
                                $query->where('employee_details.employee_status', 'resigned')
                                    ->orWhere('employee_details.employee_status', 'terminated');
                            });
                        }
                    })
                    ->where('leave_status', '!=', 5)
                    ->orderBy('created_at', $this->sortBy === 'oldest_first' ? 'asc' : 'desc')
                    ->groupBy(
                        DB::raw('DATE(from_date)'),
                        'leave_applications.emp_id',
                        'employee_details.first_name',
                        'employee_details.last_name',
                        'leave_applications.leave_type',
                        'leave_applications.from_date',
                        'leave_applications.to_date',
                        'leave_applications.reason',
                        'leave_applications.created_at',
                        'leave_applications.from_session',
                        'leave_applications.to_session',
                        'leave_applications.leave_status'
                    )
                    ->get();




                // Log::info('Leave request data retrieved successfully.');

                $leaveTransactionData = $query->groupBy('date_only')->map(function ($group) {
                    return [
                        'date' => Carbon::parse($group->first()->date_only)->format('d M Y'),
                        'emp_id' => $group->first()->emp_id,  // Add emp_id
                        'first_name' => $group->first()->first_name,  // Add first_name
                        'last_name' => $group->first()->last_name,
                        'leave_details' => $group->map(function ($item) {
                            $employeeDetails = EmployeeDetails::where('emp_id', $item->emp_id)->first();



                            // Get manager details using the manager_id from the employee's details

                            $managerDetails = $employeeDetails ? EmployeeDetails::where('emp_id', $employeeDetails->manager_id)->first() : null;



                            $managerId = $managerDetails ? $managerDetails->emp_id : null;

                            $managerFirstName = $managerDetails ? $managerDetails->first_name : null;

                            $managerLastName = $managerDetails ? $managerDetails->last_name : null;
                            $leaveRequest = new LeaveRequest();
                            $leaveDays = $leaveRequest->calculateLeaveDays(
                                $item->leave_from_date,
                                $item->from_session,
                                $item->leave_to_date,
                                $item->to_session,
                                $item->leave_type
                            );
                            return [
                                'emp_id' => $item->emp_id,
                                'first_name' => $item->first_name,
                                'last_name' => $item->last_name,
                                'leave_type' => $item->leave_type,
                                'leave_from_date' => $item->leave_from_date,
                                'leave_to_date' => $item->leave_to_date,
                                'reason' => $item->reason,
                                'created_at' => $item->created_at,
                                'from_session' => $item->from_session,
                                'to_session' => $item->to_session,
                                'leave_days' => $leaveDays,
                                'leave_status' => $item->leave_status,


                                'managerDetails' => $managerDetails,

                                'manager_id' => $managerId,

                                'manager_first_name' => $managerFirstName,

                                'manager_last_name' => $managerLastName,
                            ];
                        })
                    ];
                });
            }

            // Log data before generating the PDF
            // Log::info('Preparing to generate the leave transaction report PDF.');

            // Load the view and generate the PDF report
            $employeeDetails = EmployeeDetails::where('emp_id', $loggedInEmpId)->first();

            $pdf = Pdf::loadView('daywiseLeaveTransactionReportPdf', [
                'employeeDetails' => $employeeDetails,
                'leaveTransactions' => isset($leaveTransactions)
                    ? $leaveTransactions
                    : ($this->transactionType == 'granted'
                        ? $grantedData
                        : ($this->transactionType == 'lapsed'
                            ? $lapsedData
                            : $leaveTransactionData)
                    ),

                'toDate' => $this->toDate,
                'fromDate' => $this->fromDate,
                'transactionType' => $this->transactionType
            ]);
            $this->currentSection = '';
            FlashMessageHelper::flashSuccess('DayWise Leave Transaction Report Downloaded Successfully!');

            return response()->streamDownload(function () use ($pdf) {
                echo $pdf->stream();
            }, 'Daywise_leave_transactions.pdf');
        } catch (\Exception $e) {

            Log::error('Error generating Leave Availed Report', [
                'exception' => $e, // Logs the exception details
            ]);
            // Optionally, return a user-friendly error message
            FlashMessageHelper::flashError('An error occurred while generating the report. Please try again.');
        }
    }

    public function normalizeLeaveNameToBalanceKey($leaveName)
    {
        $leaveName = str_replace(' ', '', ucwords(strtolower($leaveName))); // Remove spaces and capitalize words
        $balanceKey = lcfirst($leaveName) . 'Balance'; // Ensure the first letter is lowercase and append 'Balance'
        return $balanceKey;
    }


    public function searchfilterleave()
    {
        $this->searching = 1;
        $loggedInEmpId = Auth::guard('hr')->user()->emp_id;
        $employees = EmployeeDetails::whereNotIn('employee_details.employee_status', ['terminated', 'resigned'])
            ->get();
        $nameFilter = $this->search;

        $filteredEmployees = $employees->filter(function ($employee) use ($nameFilter) {
            return stripos($employee->first_name, $nameFilter) !== false ||
                stripos($employee->last_name, $nameFilter) !== false ||
                stripos($employee->emp_id, $nameFilter) !== false ||
                stripos($employee->job_title, $nameFilter) !== false ||
                stripos($employee->city, $nameFilter) !== false ||
                stripos($employee->state, $nameFilter) !== false;
        });



        if ($filteredEmployees->isEmpty()) {
            $this->notFound = true;
        } else {
            $this->notFound = false;
        }
    }

    public function updateEmployeeType()
    {
        // Your logic here when the employee type changes
        // For example, you can log the change or perform some action
        
        Log::info('Employee type changed to: ' . $this->employeeTypeForAttendance);
    }


    public function leaveBalanceAsOnADayReport()
    {
        $this->validate([
            'toDate' => 'required|date',
        ], [
            'toDate.required' => 'Date is required.',
        ]);
        try {

            // Validate the 'toDate' input



            // Check if any employees are selected
            if (empty($this->leaveBalance)) {
                return redirect()->back()->with('error', 'Select at least one employee detail');
            } else {

                // Fetch employee details for selected employees
                $loggedInEmpId = Auth::guard('hr')->user()->emp_id;


                $employees = EmployeeDetails::whereIn('emp_id', $this->leaveBalance)
                    ->select('emp_id', 'first_name', 'last_name')
                    ->get();





                $combinedData = $employees->map(function ($employee) {
                    $employeeDetails = EmployeeDetails::where('emp_id', $employee->emp_id)->first();



                    // Get manager details using the manager_id from the employee's details

                    $managerDetails = $employeeDetails ? EmployeeDetails::where('emp_id', $employeeDetails->manager_id)->first() : null;



                    $managerId = $managerDetails ? $managerDetails->emp_id : null;

                    $managerFirstName = $managerDetails ? $managerDetails->first_name : null;

                    $managerLastName = $managerDetails ? $managerDetails->last_name : null;
                    $leaveBalances = ReportsManagement::getLeaveBalances($employee->emp_id, $this->toDate);



                    return [
                        'emp_id' => $employee->emp_id,
                        'first_name' => $employee->first_name,
                        'last_name' => $employee->last_name,
                        'sick_leave_balance' => $leaveBalances['sickLeaveBalance'] ?? 0,
                        'casual_leave_balance' => $leaveBalances['casualLeaveBalance'] ?? 0,
                        'casual_leave_probation_balance' => $leaveBalances['casualProbationLeaveBalance'] ?? 0,
                        'loss_of_pay_balance' => $leaveBalances['lossOfPayBalance'] ?? 0,
                        'manager_id' => $managerId,

                        'manager_first_name' => $managerFirstName,

                        'manager_last_name' => $managerLastName,
                    ];
                });



                $employeeDetails = EmployeeDetails::where('emp_id', $loggedInEmpId)->first();

                $pdf = Pdf::loadView('leaveBalanceAsOnDayReport', [
                    'employeeDetails' => $employeeDetails,
                    'leaveTransactions' => $combinedData,
                    'fromDate' => $this->fromDate,
                    'toDate' => $this->toDate,
                ]);
                $this->currentSection = '';
                FlashMessageHelper::flashSuccess('Leave Balance Report Downloaded Successfully!');

                return response()->streamDownload(function () use ($pdf) {
                    echo $pdf->stream();
                }, 'leave_balance_as_on_a_day.pdf');
            }
        } catch (\Exception $e) {


            // Optionally, return a user-friendly error message
            FlashMessageHelper::flashError('An error occurred while generating the report. Please try again.');
        }
    }

    public static function getLeaveBalances($employeeId, $selectedYear)
    {
        try {

            $selectedParticularYear = Carbon::parse($selectedYear)->year;


            $employeeDetails = EmployeeDetails::where('emp_id', $employeeId)->first();
            $sickLeavePerYear = EmployeeLeaveBalances::getLeaveBalancePerYear($employeeId, 'Sick Leave', $selectedParticularYear);
            $casualLeavePerYear = EmployeeLeaveBalances::getLeaveBalancePerYear($employeeId, 'Casual Leave', $selectedParticularYear);
            $casualProbationLeavePerYear = EmployeeLeaveBalances::getLeaveBalancePerYear($employeeId, 'Casual Leave Probation', $selectedParticularYear);
            $lossOfPayPerYear = EmployeeLeaveBalances::getLeaveBalancePerYear($employeeId, 'Loss Of Pay', $selectedParticularYear);

            if (!$employeeDetails) {
                return null;
            }

            // Get the logged-in employee's approved leave days for all leave types
            $approvedLeaveDays = LeaveHelper::getApprovedLeaveDaysOnSelectedDay($employeeId, $selectedYear);


            // Calculate leave balances
            $sickLeaveBalance = $sickLeavePerYear - $approvedLeaveDays['totalSickDays'];
            $casualLeaveBalance = $casualLeavePerYear - $approvedLeaveDays['totalCasualDays'];
            $lossOfPayBalance = $approvedLeaveDays['totalLossOfPayDays'];
            $casualProbationLeaveBalance = $casualProbationLeavePerYear - $approvedLeaveDays['totalCasualLeaveProbationDays'];

            return [
                'sickLeaveBalance' => $sickLeaveBalance,
                'casualLeaveBalance' => $casualLeaveBalance,
                'lossOfPayBalance' => $lossOfPayBalance,
                'casualProbationLeaveBalance' => $casualProbationLeaveBalance,
            ];
        } catch (\Exception $e) {
            if ($e instanceof \Illuminate\Database\QueryException) {
                // Handle database query exceptions
                Log::error("Database error in getLeaveBalances(): " . $e->getMessage());
                session()->flash('emp_error', 'Database connection error occurred. Please try again later.');
            } else {
                Log::error("Error in getLeaveBalances(): " . $e->getMessage());
                session()->flash('emp_error', 'Failed to retrieve leave balances. Please try again later.');
            }
            return null;
        }
    }

    public function downloadLeaveTransactionReport()
    {
        $this->validate([
            'fromDate' => 'required|date',
            'toDate' => 'required|date|after_or_equal:fromDate',
        ], [
            'fromDate.required' => 'From date is required.',
            'toDate.required' => 'To date is required.',
            'toDate.after_or_equal' => 'To date must be a date after or equal to the from date.',
        ]);
        try {
            // Log::info('Starting the leave transaction report generation.');

            // Validate the input


            // Log::info('Validation passed for the date range.', ['fromDate' => $this->fromDate, 'toDate' => $this->toDate]);

            $loggedInEmpId = Auth::guard('hr')->user()->emp_id;
            // Log::info('Logged in employee ID: ' . $loggedInEmpId);

            if ($this->transactionType === 'granted') {
                // When the transaction type is granted, fetch data from employee_leave_balances
                // Log::info('Fetching granted leave data from employee_leave_balances.');

                // Query to fetch leave data
                $query = EmployeeLeaveBalances::select(
                    'employee_leave_balances.emp_id',
                    'employee_details.first_name',
                    'employee_details.last_name',
                    DB::raw("JSON_EXTRACT(employee_leave_balances.leave_policy_id, '$') AS leave_policy_id"),  // Extract the entire leave policy array
                    'employee_leave_balances.status',
                    'employee_leave_balances.created_at',
                    'employee_leave_balances.period'
                )
                    ->join('employee_details', 'employee_leave_balances.emp_id', '=', 'employee_details.emp_id')
                    // ->where(function ($query) use ($loggedInEmpId) {
                    //     // Fetch team members whose manager is the logged-in employee
                    //     $query->where('employee_details.manager_id', $loggedInEmpId)
                    ->whereNotIn('employee_details.employee_status', ['terminated', 'resigned'])
                    // })
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

                    ->get();


                // Log::info('Granted leave data retrieved successfully.', ['query' => $query->toArray()]);

                // Group the data by emp_id
                $grantedData = $query->groupBy('emp_id')->map(function ($group) {
                    return [
                        'emp_id' => $group->first()->emp_id,
                        'first_name' => $group->first()->first_name,
                        'last_name' => $group->first()->last_name,
                        'leave_details' => $group->map(function ($item) {
                            // Decode the JSON to get all leave types
                            $leavePolicy = json_decode($item->leave_policy_id, true);
                            $leaveTypes = [
                                'casual_probation' => 'Casual Leave',
                                'maternity' => 'Maternity Leave',
                                'paternity' => 'Paternity Leave',
                                'sick' => 'Sick Leave',
                                'lop' => 'Loss of Pay',
                            ];
                            $employeeDetails = EmployeeDetails::where('emp_id', $item->emp_id)->first();



                            // Get manager details using the manager_id from the employee's details

                            $managerDetails = $employeeDetails ? EmployeeDetails::where('emp_id', $employeeDetails->manager_id)->first() : null;



                            $managerId = $managerDetails ? $managerDetails->emp_id : null;

                            $managerFirstName = $managerDetails ? $managerDetails->first_name : null;

                            $managerLastName = $managerDetails ? $managerDetails->last_name : null;

                            // Loop through all the leave types in the JSON array
                            $leaveDetails = [];
                            foreach ($leavePolicy as $leave) {
                                if ($this->leaveType && $this->leaveType != 'all' && $leave['leave_name'] !== $leaveTypes[$this->leaveType]) {
                                    continue;  // Skip if the leave name doesn't match the selected leave type
                                }
                                $period = $item->period;

                                // Create Carbon instance for the start and end date of the year
                                $startDate = Carbon::createFromFormat('Y', $period)->firstOfYear()->toDateString(); // '2024-01-01'
                                $endDate = Carbon::createFromFormat('Y', $period)->lastOfYear()->toDateString(); // '2024-12-31'
                                $leaveDetails[] = [
                                    'leave_name' => $leave['leave_name'] ?? 'Unknown',
                                    'grant_days' => $leave['grant_days'] ?? 0,
                                    'status' => $item->status,
                                    'created_at' => $item->created_at,
                                    'from_date' => $startDate,  // The start date of the year
                                    'to_date' => $endDate,
                                    'manager_id' => $managerId,

                                    'manager_first_name' => $managerFirstName,

                                    'manager_last_name' => $managerLastName,
                                ];
                            }

                            return $leaveDetails;
                        }),
                    ];
                });
            } elseif ($this->transactionType == 'lapsed') {
                $employeeId = auth()->guard('hr')->user()->emp_id;


                $query = EmployeeLeaveBalances::select(
                    'employee_leave_balances.emp_id',
                    'employee_details.first_name',
                    'employee_details.last_name',
                    DB::raw("JSON_EXTRACT(employee_leave_balances.leave_policy_id, '$') AS leave_policy_id"),  // Extract the entire leave policy array
                    'employee_leave_balances.status',
                    'employee_leave_balances.created_at',
                    'employee_leave_balances.period'
                )
                    ->join('employee_details', 'employee_leave_balances.emp_id', '=', 'employee_details.emp_id')
                    // ->where(function ($query) use ($loggedInEmpId) {
                    //     // Fetch team members whose manager is the logged-in employee
                    //     $query->where('employee_details.manager_id', $loggedInEmpId)
                    ->whereNotIn('employee_details.employee_status', ['terminated', 'resigned'])
                    // })
                    ->where('employee_leave_balances.is_lapsed', 1)  // Filter for lapsed leave
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
                    ->get();


                // Check what is being grouped by emp_id


                // Group the data by emp_id
                $lapsedData = $query->groupBy('emp_id')->map(function ($group) {
                    return [
                        'emp_id' => $group->first()->emp_id,
                        'first_name' => $group->first()->first_name,
                        'last_name' => $group->first()->last_name,
                        'leave_details' => $group->map(function ($item) {

                            $period = $item->period;
                            $decemberStart = Carbon::createFromFormat('Y', $period)->month(12)->startOfMonth()->toDateString(); // First day of December
                            $decemberEnd = Carbon::createFromFormat('Y', $period)->month(12)->endOfMonth()->toDateString();   // Last day of December

                            $leaveBalances = LeaveBalances::getLeaveLapsedBalances($item->emp_id, $item->period);



                            // Decode the JSON to get all leave types
                            $leaveDetails1 = json_decode($item->leave_policy_id, true);
                            $leaveDetails = [];
                            $leaveTypes = [
                                'casual_probation' => 'Casual Leave',
                                'maternity' => 'Maternity Leave',
                                'paternity' => 'Paternity Leave',
                                'sick' => 'Sick Leave',
                                'lop' => 'Loss of Pay',
                            ];
                            $employeeDetails = EmployeeDetails::where('emp_id', $item->emp_id)->first();



                            // Get manager details using the manager_id from the employee's details

                            $managerDetails = $employeeDetails ? EmployeeDetails::where('emp_id', $employeeDetails->manager_id)->first() : null;



                            $managerId = $managerDetails ? $managerDetails->emp_id : null;

                            $managerFirstName = $managerDetails ? $managerDetails->first_name : null;

                            $managerLastName = $managerDetails ? $managerDetails->last_name : null;

                            // Loop through all the leave types in the JSON array

                            foreach ($leaveTypes as $key => $leaveName) {

                                if ($this->leaveType && $this->leaveType != 'all' && $leaveName !== $leaveTypes[$this->leaveType]) {
                                    continue;  // Skip if the leave name doesn't match the selected leave type
                                }
                                $balanceKey = $this->normalizeLeaveNameToBalanceKey($leaveName);

                                // This will print the balance key for debugging

                                // Get the balance for this leave type (defaults to 0 if not found)
                                $remainingBalance = $leaveBalances[$balanceKey] ?? 0;


                                // Skip if there's no remaining balance
                                if ($remainingBalance <= 0) {
                                    continue;
                                }

                                // Add formatted leave object to the array with remaining balance (days)
                                $leaveDetails[] = [
                                    'leave_name' => $leaveName,
                                    'grant_days' => $remainingBalance,
                                    'status' => 'Lapsed',
                                    'created_at' => $item->created_at,
                                    'from_date' => $decemberStart,  // The start date of the year
                                    'to_date' => $decemberEnd,      // The end date of the year
                                    'manager_id' => $managerId,

                                    'manager_first_name' => $managerFirstName,

                                    'manager_last_name' => $managerLastName,


                                ];
                            }

                            return $leaveDetails;
                        }),
                    ];
                });



                // The $lapsedData will now contain the employee leave details with the remaining balances.

            } elseif ($this->transactionType === 'all') {
                // Query to fetch leave data
                $query = EmployeeLeaveBalances::select(
                    'employee_leave_balances.emp_id',
                    'employee_details.first_name',
                    'employee_details.last_name',
                    DB::raw("JSON_EXTRACT(employee_leave_balances.leave_policy_id, '$') AS leave_policy_id"),  // Extract the entire leave policy array
                    'employee_leave_balances.status',
                    'employee_leave_balances.created_at',
                    'employee_leave_balances.period'
                )
                    ->join('employee_details', 'employee_leave_balances.emp_id', '=', 'employee_details.emp_id')
                    // ->where(function ($query) use ($loggedInEmpId) {
                    //     // Fetch team members whose manager is the logged-in employee
                    //     $query->where('employee_details.manager_id', $loggedInEmpId)
                    ->whereNotIn('employee_details.employee_status', ['terminated', 'resigned'])
                    // })
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

                    ->get();


                // Log::info('Granted leave data retrieved successfully.', ['query' => $query->toArray()]);

                // Group the data by emp_id
                $grantedData = $query->groupBy('emp_id')->map(function ($group) {
                    return [
                        'emp_id' => $group->first()->emp_id,
                        'first_name' => $group->first()->first_name,
                        'last_name' => $group->first()->last_name,
                        'leave_details' => $group->map(function ($item) {
                            // Decode the JSON to get all leave types
                            $leavePolicy = json_decode($item->leave_policy_id, true);
                            $leaveTypes = [
                                'casual_probation' => 'Casual Leave',
                                'maternity' => 'Maternity Leave',
                                'paternity' => 'Paternity Leave',
                                'sick' => 'Sick Leave',
                                'lop' => 'Loss of Pay',
                            ];

                            // Loop through all the leave types in the JSON array
                            $leaveDetails = [];
                            $employeeDetails = EmployeeDetails::where('emp_id', $item->emp_id)->first();



                            // Get manager details using the manager_id from the employee's details

                            $managerDetails = $employeeDetails ? EmployeeDetails::where('emp_id', $employeeDetails->manager_id)->first() : null;



                            $managerId = $managerDetails ? $managerDetails->emp_id : null;

                            $managerFirstName = $managerDetails ? $managerDetails->first_name : null;

                            $managerLastName = $managerDetails ? $managerDetails->last_name : null;
                            foreach ($leavePolicy as $leave) {
                                if ($this->leaveType && $this->leaveType != 'all' && $leave['leave_name'] !== $leaveTypes[$this->leaveType]) {
                                    continue;  // Skip if the leave name doesn't match the selected leave type
                                }
                                $period = $item->period;

                                // Create Carbon instance for the start and end date of the year
                                $startDate = Carbon::createFromFormat('Y', $period)->firstOfYear()->toDateString(); // '2024-01-01'
                                $endDate = Carbon::createFromFormat('Y', $period)->lastOfYear()->toDateString(); // '2024-12-31'
                                $leaveDetails[] = [
                                    'leave_name' => $leave['leave_name'] ?? 'Unknown',
                                    'grant_days' => $leave['grant_days'] ?? 0,
                                    'status' => $item->status,
                                    'created_at' => $item->created_at,
                                    'from_date' => $startDate,  // The start date of the year
                                    'to_date' => $endDate,
                                    'manager_id' => $managerId,

                                    'manager_first_name' => $managerFirstName,

                                    'manager_last_name' => $managerLastName,
                                ];
                            }

                            return $leaveDetails;
                        }),
                    ];
                });




                $query = LeaveRequest::select(
                    DB::raw('DATE(from_date) as date_only'),
                    DB::raw('count(*) as total_requests'),
                    'leave_applications.emp_id',
                    'employee_details.first_name',
                    'employee_details.last_name',
                    'leave_applications.leave_type',
                    'leave_applications.from_date as leave_from_date',
                    'leave_applications.to_date as leave_to_date',
                    'leave_applications.reason',
                    'leave_applications.created_at',
                    'leave_applications.from_session',
                    'leave_applications.to_session',
                    'leave_applications.leave_status'
                )
                    ->join('employee_details', 'leave_applications.emp_id', '=', 'employee_details.emp_id')
                    ->when($this->leaveType && $this->leaveType != 'all', function ($query) {
                        $leaveTypes = [
                            'lop' => 'Loss of Pay',
                            'casual_leave' => 'Casual Leave',
                            'sick' => 'Sick Leave',
                            'petarnity' => 'Paternity Leave',
                            'casual_leave_probation' => 'Casual Leave Probation',
                            'maternity' => 'Maternity Leave',
                            'marriage_leave' => 'Marriage Leave',
                        ];

                        if (array_key_exists($this->leaveType, $leaveTypes)) {
                            $query->where('leave_applications.leave_type', $leaveTypes[$this->leaveType]);
                        }
                    })
                    // ->where(function ($query) use ($loggedInEmpId) {
                    //     $query->whereJsonContains('applying_to', [['manager_id' => $loggedInEmpId]])
                    //         ->orWhereJsonContains('cc_to', [['emp_id' => $loggedInEmpId]]);
                    // })
                    ->where(function ($query) {
                        $query->whereBetween('from_date', [$this->fromDate, $this->toDate])
                            ->orWhereBetween('to_date', [$this->fromDate, $this->toDate])
                            ->orWhere(function ($query) {
                                $query->where('from_date', '<=', $this->toDate)
                                    ->where('to_date', '>=', $this->fromDate);
                            });
                    })
                    ->when($this->transactionType && $this->transactionType != 'all', function ($query) {
                        $transactionTypes = [
                            'availed' => 2,
                            'withdrawn' => 4,
                            'rejected' => 3,
                        ];
                        if (array_key_exists($this->transactionType, $transactionTypes)) {
                            $query->where('leave_status', $transactionTypes[$this->transactionType]);
                        }
                    })
                    ->when($this->employeeType, function ($query) {
                        if ($this->employeeType == 'active') {
                            $query->where(function ($query) {
                                $query->where('employee_details.employee_status', 'active')
                                    ->orWhere('employee_details.employee_status', 'on-probation');
                            });
                        } else {
                            $query->where(function ($query) {
                                $query->where('employee_details.employee_status', 'resigned')
                                    ->orWhere('employee_details.employee_status', 'terminated');
                            });
                        }
                    })
                    ->where('leave_status', '!=', 5)
                    ->orderBy('created_at', $this->sortBy === 'oldest_first' ? 'asc' : 'desc')
                    ->groupBy(
                        DB::raw('DATE(from_date)'),
                        'leave_applications.emp_id',
                        'employee_details.first_name',
                        'employee_details.last_name',
                        'leave_applications.leave_type',
                        'leave_applications.from_date',
                        'leave_applications.to_date',
                        'leave_applications.reason',
                        'leave_applications.created_at',
                        'leave_applications.from_session',
                        'leave_applications.to_session',
                        'leave_applications.leave_status'
                    )
                    ->get();

                $leaveTransactionData = $query->groupBy('date_only')->map(function ($group) {
                    return [
                        'date' => Carbon::parse($group->first()->date_only)->format('d M Y'),
                        'emp_id' => $group->first()->emp_id,  // Add emp_id
                        'first_name' => $group->first()->first_name,  // Add first_name
                        'last_name' => $group->first()->last_name,
                        'leave_details' => $group->map(function ($item) {
                            $employeeDetails = EmployeeDetails::where('emp_id', $item->emp_id)->first();



                            // Get manager details using the manager_id from the employee's details

                            $managerDetails = $employeeDetails ? EmployeeDetails::where('emp_id', $employeeDetails->manager_id)->first() : null;



                            $managerId = $managerDetails ? $managerDetails->emp_id : null;

                            $managerFirstName = $managerDetails ? $managerDetails->first_name : null;

                            $managerLastName = $managerDetails ? $managerDetails->last_name : null;
                            $leaveRequest = new LeaveRequest();
                            $leaveDays = $leaveRequest->calculateLeaveDays(
                                $item->leave_from_date,
                                $item->from_session,
                                $item->leave_to_date,
                                $item->to_session,
                                $item->leave_type
                            );
                            return [
                                'leave_type' => $item->leave_type,
                                'leave_from_date' => $item->leave_from_date,
                                'leave_to_date' => $item->leave_to_date,
                                'reason' => $item->reason,
                                'created_at' => $item->created_at,
                                'leave_days' => $leaveDays,
                                'leave_status' => $item->leave_status,
                                'manager_id' => $managerId,

                                'manager_first_name' => $managerFirstName,

                                'manager_last_name' => $managerLastName,
                            ];
                        }),
                    ];
                });



                $query = EmployeeLeaveBalances::select(
                    'employee_leave_balances.emp_id',
                    'employee_details.first_name',
                    'employee_details.last_name',
                    DB::raw("JSON_EXTRACT(employee_leave_balances.leave_policy_id, '$') AS leave_policy_id"),  // Extract the entire leave policy array
                    'employee_leave_balances.status',
                    'employee_leave_balances.created_at',
                    'employee_leave_balances.period'
                )
                    ->join('employee_details', 'employee_leave_balances.emp_id', '=', 'employee_details.emp_id')
                    // ->where(function ($query) use ($loggedInEmpId) {
                    //     // Fetch team members whose manager is the logged-in employee
                    //     $query->where('employee_details.manager_id', $loggedInEmpId)
                    ->whereNotIn('employee_details.employee_status', ['terminated', 'resigned'])
                    // })
                    ->where('employee_leave_balances.is_lapsed', 1)  // Filter for lapsed leave
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
                    ->get();


                // Check what is being grouped by emp_id


                // Group the data by emp_id
                $lapsedData = $query->groupBy('emp_id')->map(function ($group) {
                    return [
                        'emp_id' => $group->first()->emp_id,
                        'first_name' => $group->first()->first_name,
                        'last_name' => $group->first()->last_name,
                        'leave_details' => $group->map(function ($item) {

                            $period = $item->period;
                            $decemberStart = Carbon::createFromFormat('Y', $period)->month(12)->startOfMonth()->toDateString(); // First day of December
                            $decemberEnd = Carbon::createFromFormat('Y', $period)->month(12)->endOfMonth()->toDateString();   // Last day of December

                            $leaveBalances = LeaveBalances::getLeaveLapsedBalances($item->emp_id, $item->period);



                            // Decode the JSON to get all leave types
                            $leaveDetails1 = json_decode($item->leave_policy_id, true);
                            $leaveDetails = [];
                            $leaveTypes = [
                                'casual_probation' => 'Casual Leave',
                                'maternity' => 'Maternity Leave',
                                'paternity' => 'Paternity Leave',
                                'sick' => 'Sick Leave',
                                'lop' => 'Loss of Pay',
                            ];
                            $employeeDetails = EmployeeDetails::where('emp_id', $item->emp_id)->first();



                            // Get manager details using the manager_id from the employee's details

                            $managerDetails = $employeeDetails ? EmployeeDetails::where('emp_id', $employeeDetails->manager_id)->first() : null;



                            $managerId = $managerDetails ? $managerDetails->emp_id : null;

                            $managerFirstName = $managerDetails ? $managerDetails->first_name : null;

                            $managerLastName = $managerDetails ? $managerDetails->last_name : null;

                            // Loop through all the leave types in the JSON array

                            foreach ($leaveTypes as $key => $leaveName) {

                                if ($this->leaveType && $this->leaveType != 'all' && $leaveName !== $leaveTypes[$this->leaveType]) {
                                    continue;  // Skip if the leave name doesn't match the selected leave type
                                }
                                $balanceKey = $this->normalizeLeaveNameToBalanceKey($leaveName);

                                // This will print the balance key for debugging

                                // Get the balance for this leave type (defaults to 0 if not found)
                                $remainingBalance = $leaveBalances[$balanceKey] ?? 0;


                                // Skip if there's no remaining balance
                                if ($remainingBalance <= 0) {
                                    continue;
                                }

                                // Add formatted leave object to the array with remaining balance (days)
                                $leaveDetails[] = [
                                    'leave_name' => $leaveName,
                                    'grant_days' => $remainingBalance,
                                    'status' => 'Lapsed',
                                    'created_at' => $item->created_at,
                                    'from_date' => $decemberStart,  // The start date of the year
                                    'to_date' => $decemberEnd,      // The end date of the year
                                    'manager_id' => $managerId,

                                    'manager_first_name' => $managerFirstName,

                                    'manager_last_name' => $managerLastName,


                                ];
                            }

                            return $leaveDetails;
                        }),
                    ];
                });




                $leaveTransactions = $leaveTransactionData->concat($lapsedData)->concat($grantedData);
            } else {
                // The existing code for handling leave requests in 'availed', 'rejected', etc.
                // Log::info('Fetching leave request data for transaction type: ' . $this->transactionType);

                $query = LeaveRequest::select(
                    DB::raw('DATE(from_date) as date_only'),
                    DB::raw('count(*) as total_requests'),
                    'leave_applications.emp_id',
                    'employee_details.first_name',
                    'employee_details.last_name',
                    'leave_applications.leave_type',
                    'leave_applications.from_date as leave_from_date',
                    'leave_applications.to_date as leave_to_date',
                    'leave_applications.reason',
                    'leave_applications.created_at',
                    'leave_applications.from_session',
                    'leave_applications.to_session',
                    'leave_applications.leave_status'
                )
                    ->join('employee_details', 'leave_applications.emp_id', '=', 'employee_details.emp_id')
                    ->when($this->leaveType && $this->leaveType != 'all', function ($query) {
                        $leaveTypes = [
                            'lop' => 'Loss of Pay',
                            'casual_leave' => 'Casual Leave',
                            'sick' => 'Sick Leave',
                            'petarnity' => 'Paternity Leave',
                            'casual_leave_probation' => 'Casual Leave Probation',
                            'maternity' => 'Maternity Leave',
                            'marriage_leave' => 'Marriage Leave',
                        ];

                        if (array_key_exists($this->leaveType, $leaveTypes)) {
                            $query->where('leave_applications.leave_type', $leaveTypes[$this->leaveType]);
                        }
                    })
                    // ->where(function ($query) use ($loggedInEmpId) {
                    //     $query->whereJsonContains('applying_to', [['manager_id' => $loggedInEmpId]])
                    //         ->orWhereJsonContains('cc_to', [['emp_id' => $loggedInEmpId]]);
                    // })
                    ->where(function ($query) {
                        $query->whereBetween('from_date', [$this->fromDate, $this->toDate])
                            ->orWhereBetween('to_date', [$this->fromDate, $this->toDate])
                            ->orWhere(function ($query) {
                                $query->where('from_date', '<=', $this->toDate)
                                    ->where('to_date', '>=', $this->fromDate);
                            });
                    })
                    ->when($this->transactionType && $this->transactionType != 'all', function ($query) {
                        $transactionTypes = [
                            'availed' => 2,
                            'withdrawn' => 4,
                            'rejected' => 3,
                        ];
                        if (array_key_exists($this->transactionType, $transactionTypes)) {
                            $query->where('leave_status', $transactionTypes[$this->transactionType]);
                        }
                    })
                    ->when($this->employeeType, function ($query) {
                        if ($this->employeeType == 'active') {
                            $query->where(function ($query) {
                                $query->where('employee_details.employee_status', 'active')
                                    ->orWhere('employee_details.employee_status', 'on-probation');
                            });
                        } else {
                            $query->where(function ($query) {
                                $query->where('employee_details.employee_status', 'resigned')
                                    ->orWhere('employee_details.employee_status', 'terminated');
                            });
                        }
                    })
                    ->where('leave_status', '!=', 5)
                    ->orderBy('created_at', $this->sortBy === 'oldest_first' ? 'asc' : 'desc')
                    ->groupBy(
                        DB::raw('DATE(from_date)'),
                        'leave_applications.emp_id',
                        'employee_details.first_name',
                        'employee_details.last_name',
                        'leave_applications.leave_type',
                        'leave_applications.from_date',
                        'leave_applications.to_date',
                        'leave_applications.reason',
                        'leave_applications.created_at',
                        'leave_applications.from_session',
                        'leave_applications.to_session',
                        'leave_applications.leave_status'
                    )
                    ->get();

                // Refactor the data grouping
                $leaveTransactionData = $query->groupBy('date_only')->map(function ($group) {
                    return [
                        'date' => Carbon::parse($group->first()->date_only)->format('d M Y'),
                        'emp_id' => $group->first()->emp_id,  // Add emp_id
                        'first_name' => $group->first()->first_name,  // Add first_name
                        'last_name' => $group->first()->last_name,
                        'leave_details' => $group->map(function ($item) {
                            $employeeDetails = EmployeeDetails::where('emp_id', $item->emp_id)->first();



                            // Get manager details using the manager_id from the employee's details

                            $managerDetails = $employeeDetails ? EmployeeDetails::where('emp_id', $employeeDetails->manager_id)->first() : null;



                            $managerId = $managerDetails ? $managerDetails->emp_id : null;

                            $managerFirstName = $managerDetails ? $managerDetails->first_name : null;

                            $managerLastName = $managerDetails ? $managerDetails->last_name : null;
                            $leaveRequest = new LeaveRequest();
                            $leaveDays = $leaveRequest->calculateLeaveDays(
                                $item->leave_from_date,
                                $item->from_session,
                                $item->leave_to_date,
                                $item->to_session,
                                $item->leave_type
                            );
                            return [
                                'leave_name' => $item->leave_type,
                                'from_date' => $item->leave_from_date,
                                'to_date' => $item->leave_to_date,
                                'reason' => $item->reason,
                                'created_at' => $item->created_at,
                                'grant_days' => $leaveDays,
                                'status' => $item->leave_status,
                                'manager_id' => $managerId,

                                'manager_first_name' => $managerFirstName,

                                'manager_last_name' => $managerLastName,
                            ];
                        }),
                    ];
                });
            }

            // Log data before generating the PDF
            // Log::info('Preparing to generate the leave transaction report PDF.');

            // Load the view and generate the PDF report
            $employeeDetails = EmployeeDetails::where('emp_id', $loggedInEmpId)->first();
            $pdf = Pdf::loadView('leaveTransactionReportPdf', [
                'employeeDetails' => $employeeDetails,
                // 'leaveTransactions' => isset($leaveTransactions) ? $leaveTransactions : ($this->transactionType == 'granted' ? $grantedData : $leaveTransactionData),
                'leaveTransactions' => isset($leaveTransactions)
                    ? $leaveTransactions
                    : ($this->transactionType == 'granted'
                        ? $grantedData
                        : ($this->transactionType == 'lapsed'
                            ? $lapsedData
                            : $leaveTransactionData)
                    ),
                'fromDate' => $this->fromDate,
                'toDate' => $this->toDate,
                'transactionType' => $this->transactionType,
            ]);
            $this->currentSection = '';

            FlashMessageHelper::flashSuccess('Leave Transaction Report Downloaded Successfully!');
            // Log::info('Leave transaction report generated successfully.');

            return response()->streamDownload(function () use ($pdf) {
                echo $pdf->stream();
            }, 'leave_transactions_report.pdf');
        } catch (\Exception $e) {
            // Log the exception message
            Log::error('An error occurred while generating the leave transaction report.', ['error' => $e->getMessage()]);
            FlashMessageHelper::flashError('An error occurred while generating the report. Please try again.');
        }
    }

    public function render()
    {
         // For Leave Balance On Day
        if($this->isToggleSelectedEmployee==true)
        {
            $this->employeesForSelection = EmployeeDetails::whereIn('emp_id',$this->EmployeeId)->orderBy('first_name')->get();
        }
        else
        {
            $this->employeesForSelection = EmployeeDetails::whereNotIn('employee_status',['resigned','terminated'])->orderBy('first_name')->get();
        }
          
         $this->employees = EmployeeDetails::whereNotIn('employee_details.employee_status', ['terminated', 'resigned'])
         ->select('emp_id', 'first_name', 'last_name')->get();
         if ($this->searching == 1) {
             $nameFilter = $this->search; // Assuming $this->search contains the name filter
             $this->filteredEmployees = $this->employees->filter(function ($employee) use ($nameFilter) {
                 return stripos($employee->first_name, $nameFilter) !== false ||
                     stripos($employee->last_name, $nameFilter) !== false ||
                     stripos($employee->emp_id, $nameFilter) !== false ||
                     stripos($employee->job_title, $nameFilter) !== false ||
                     stripos($employee->city, $nameFilter) !== false ||
                     stripos($employee->state, $nameFilter) !== false;
             });
           
 
 
             if ($this->filteredEmployees->isEmpty()) {
                 $this->notFound = true; // Set a flag indicating that the name was not found
             } else {
                 $this->notFound = false;
             }
         } else {
             $this->filteredEmployees = $this->employees;
         }
        return view('livewire.reports-management', [
            'reportsGallery' => $this->reportsGallery,
            'Employees'=>$this->employeesForSelection,
        ]);
    }
}

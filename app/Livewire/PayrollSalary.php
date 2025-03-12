<?php

namespace App\Livewire;

use App\Models\EmployeeDetails;
use App\Models\EmpSalary;
use App\Models\EmpSalaryRevision;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class PayrollSalary extends Component
{
    public $isShowHelp = true;
    public $isPageOne = true;
    public $showContainer = false;
    public $employees = [];
    public $selectedEmployee = null;
    public $showSearch = true;
    public $search = '';
    public $searchComponent = '';
    public $employeeType = 'active';
    public $empDetails;
    public $payout_month;
    public $components = [];
    public $filteredcomponents = [];
    public $expanded = [];
    public $expandAll = false;

    public function toogleHelp()
    {
        $this->isShowHelp = !$this->isShowHelp;
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

    public function selectEmployee($employeeId)
    {
        // Check if the employee is already selected
        Session::put('selectedEmployee', $employeeId);
        $this->selectedEmployee = $employeeId; // Change here

        Log::info('Selected Employee: ', [$this->selectedEmployee]);
        if (is_null($this->selectedEmployee)) {
            $this->showSearch = true;
            $this->search = '';
            // $this->showContainer = true;
            // $this->showSearch = false;
            $this->selectedEmployee = null;
        } else {
            $this->showSearch = false;
            $this->showContainer = false;
           $this->loadComponents();
            $this->getEmpDetails();
        }
    }
    public function hideContainer()
    {
        $this->showSearch = true;
        $this->showContainer = false;
    }
    public function getEmpDetails()
    {
        $this->empDetails = DB::table('employee_details')
        ->leftJoin('emp_personal_infos', 'emp_personal_infos.emp_id', '=', 'employee_details.emp_id')
            ->where('employee_details.emp_id', $this->selectedEmployee)
            ->select('emp_personal_infos.date_of_birth', 'employee_details.hire_date', 'employee_details.emp_id', 'employee_details.job_location', 'employee_details.first_name', 'employee_details.last_name')
            ->first();
        // dd( $this->empDetails);
    }

    public function showRevisedSalary()
    {
        return redirect('/hr/user/salary-revision');
    }
    public function mount()
    {
        $this->selectEmployee(null);


    }
    public function loadComponents(){

        $this->components = [
            [
                'name' => 'NET PAY',
                'amount' => 0,
                'children' => [
                    [
                        'name' => 'GROSS',
                        'amount' => 0,
                        'children' => [
                            ['name' => 'Basic', 'amount' => 0, 'children' => []],
                            ['name' => 'BASIC ARREARS', 'amount' => 0, 'children' => []],
                            ['name' => 'BASIC REVERSAL', 'amount' => 0, 'children' => []],
                            ['name' => 'DA', 'amount' => 0, 'children' => []],
                            ['name' => 'DA ARREARS', 'amount' => 0, 'children' => []],
                            ['name' => 'DA REVERSAL', 'amount' => 0, 'children' => []],
                            ['name' => 'HRA', 'amount' => 0, 'children' => []],
                            ['name' => 'HRA ARREARS', 'amount' => 0, 'children' => []],
                            ['name' => 'HRA REVERSAL', 'amount' => 0, 'children' => []],
                            ['name' => 'CONVEYANCE', 'amount' => 0, 'children' => []],
                            ['name' => 'CONVEYANCE ARREARS', 'amount' => 0, 'children' => []],
                            ['name' => 'CONVEYANCE REVERSAL', 'amount' => 0, 'children' => []],
                            ['name' => 'SPECIAL ALLOWANCE', 'amount' => 0, 'children' => []],
                            ['name' => 'SPECIAL ALLOWANCE ARREARS', 'amount' => 0, 'children' => []],
                            ['name' => 'SPECIAL ALLOWANCE REVERSAL', 'amount' => 0, 'children' => []],
                            ['name' => 'BONUS', 'amount' => 0, 'children' => []],
                            ['name' => 'INCENTIVE', 'amount' => 0, 'children' => []],
                            ['name' => 'NOTICE PAYABLE', 'amount' => 0, 'children' => []],
                            ['name' => 'OT PAYOUT', 'amount' => 0, 'children' => []],
                            ['name' => 'STIPEND', 'amount' => 0, 'children' => []],
                            ['name' => 'OTHER EARNINGS', 'amount' => 0, 'children' => []],
                            ['name' => 'MEDICAL ALLOWANCE', 'amount' => 0, 'children' => []],
                            ['name' => 'MEDICAL ALLOWANCE ARREARS', 'amount' => 0, 'children' => []],
                            ['name' => 'MEDICAL ALLOWANCE REVERSAL', 'amount' => 0, 'children' => []],
                            ['name' => 'LTA', 'amount' => 0, 'children' => []],
                            ['name' => 'LTA ARREARS', 'amount' => 0, 'children' => []],
                            ['name' => 'LTA REVERSAL', 'amount' => 0, 'children' => []],
                            ['name' => 'LEAVE ENCASHMENT', 'amount' => 0, 'children' => []],
                            ['name' => 'GRATUITY', 'amount' => 0, 'children' => []],
                            ['name' => 'FOOD ALLOWANCE', 'amount' => 0, 'children' => []],
                            ['name' => 'FOOD ALLOWANCE ARREARS', 'amount' => 0, 'children' => []],
                            ['name' => 'FOOD ALLOWANCE REVERSAL', 'amount' => 0, 'children' => []],
                            ['name' => 'CHILDREN EDUCATION ALLOWANCE', 'amount' => 0, 'children' => []],
                            ['name' => 'CHILDREN EDUCATION ALLOWANCE ARREARS', 'amount' => 0, 'children' => []],
                            ['name' => 'CHILDREN EDUCATION ALLOWANCE REVERSAL', 'amount' => 0, 'children' => []],
                            ['name' => 'TELEPHONE ALLOWANCE', 'amount' => 0, 'children' => []],
                            ['name' => 'TELEPHONE ALLOWANCE ARREARS', 'amount' => 0, 'children' => []],
                            ['name' => 'TELEPHONE ALLOWANCE REVERSAL', 'amount' => 0, 'children' => []],
                            ['name' => 'Overtime Hours', 'amount' => 0, 'children' => []],

                        ]
                    ],
                    [
                        'name' => 'TOTAL DEDUCTIONS',
                        'amount' => 0,
                        'children' => [
                            ['name' => 'PF', 'amount' => 0, 'children' => []],
                            ['name' => 'ESI', 'amount' => 0, 'children' => []],
                            ['name' => 'VPF', 'amount' => 0, 'children' => []],
                            ['name' => 'INCOME TAX', 'amount' => 0, 'children' => []],
                            ['name' => 'PROF TAX', 'amount' => 0, 'children' => []],
                            ['name' => 'LOAN', 'amount' => 0, 'children' => []],
                            ['name' => 'OTHER LOAN', 'amount' => 0, 'children' => []],
                            ['name' => 'LABOUR WELFARE FUND', 'amount' => 0, 'children' => []],
                            ['name' => 'OTHER DEDUCTION', 'amount' => 0, 'children' => []],
                            ['name' => 'NOTICE RECOVERY', 'amount' => 0, 'children' => []],
                            ['name' => 'SALARY ADVANCE', 'amount' => 0, 'children' => []],
                            ['name' => 'Late deduction', 'amount' => 0, 'children' => []],



                        ]
                    ],
                    ['name' => 'SALARY MASTER', 'amount' => 0, 'children' => [
                        ['name' => 'FULL BASIC', 'amount' => 0, 'children' => []],
                        ['name' => 'FULL HRA', 'amount' => 0, 'children' => []],
                        ['name' => 'FULL CONVEYANCE', 'amount' => 0, 'children' => []],
                        ['name' => 'FULL DA', 'amount' => 0, 'children' => []],
                        ['name' => 'FULL SPECIAL ALLOWANCE', 'amount' => 0, 'children' => []],
                        ['name' => 'FULL LTA', 'amount' => 0, 'children' => []],
                        ['name' => 'FULL FOOD ALLOWANCE', 'amount' => 0, 'children' => []],
                        ['name' => 'FULL CEA', 'amount' => 0, 'children' => []],
                    ]],
                ],
            ],
            [
                'name' => 'CALCULATION FIELDS',
                'amount' => 0,
                'children' => [
                    ['name' => 'CTC ITEMS', 'amount' => 0, 'children' => [
                        ['name' => 'MONTHLY CTC', 'amount' => 0, 'children' => []],
                        ['name' => 'ANNUAL CTC', 'amount' => 0, 'children' => []],

                    ]],
                    ['name' => 'PF RELATED ITEMS', 'amount' => 0, 'children' => [
                        ['name' => 'PF BASIC', 'amount' => 0, 'children' => []],
                        ['name' => 'ELIGIBLE FOR PF', 'amount' => 0, 'children' => []],
                        ['name' => 'EMPLOYERS PF', 'amount' => 0, 'children' => []],
                        ['name' => 'FAMILY PENSION FUND', 'amount' => 0, 'children' => []],
                        ['name' => 'PF BASE LIMIT', 'amount' => 0, 'children' => []],
                        ['name' => 'AGE', 'amount' => 0, 'children' => []],
                        ['name' => 'ELIGIBLE FOR PF', 'amount' => 0, 'children' => []],
                        ['name' => 'INTERNATIONAL EMPLOYEE', 'amount' => 0, 'children' => []],
                        ['name' => 'EPS EXCESS CONTRIBUTION', 'amount' => 0, 'children' => []],
                        ['name' => 'EPF EXCESS CONTRIBUTION', 'amount' => 0, 'children' => []],
                        ['name' => 'EPS NEW JOINEE', 'amount' => 0, 'children' => []],
                        ['name' => 'EPS BASIC', 'amount' => 0, 'children' => []],
                        ['name' => 'EDLI BASIC', 'amount' => 0, 'children' => []],
                        ['name' => 'EDLI CONTRIBUTION', 'amount' => 0, 'children' => []],
                        ['name' => 'EDLI ADMIN CHARGES', 'amount' => 0, 'children' => []],
                        ['name' => 'EPF ADMIN CHARGES', 'amount' => 0, 'children' => []],
                        ['name' => 'EMPLOYER PF FULL', 'amount' => 0, 'children' => []],

                    ]],
                    ['name' => 'ESI RELATED ITEMS', 'amount' => 0, 'children' => [
                        ['name' => 'ESI BASIC', 'amount' => 0, 'children' => []],
                        ['name' => 'EMPLOYERS ESI', 'amount' => 0, 'children' => []],

                    ]],
                    ['name' => 'PROF TAX RELATED ITEMS', 'amount' => 0, 'children' => [
                        ['name' => 'PROF TAX BASIC', 'amount' => 0, 'children' => []],

                    ]],
                    ['name' => 'INCOME TAX RELATED ITEMS', 'amount' => 0, 'children' => [
                        ['name' => 'TAXABLE AMOUNT WITH PREV EMP', 'amount' => 0, 'children' => []],
                        ['name' => 'TAXABLE AMOUNT', 'amount' => 0, 'children' => []],
                        ['name' => 'RAW TAX MONTHLY WITH PREV EMP', 'amount' => 0, 'children' => []],
                        ['name' => 'SURCHARGE MONTHLY WITH PREV EMP', 'amount' => 0, 'children' => []],
                        ['name' => 'CESS MONTHLY WITH PREV EMP', 'amount' => 0, 'children' => []],
                        ['name' => 'RAW TAX MONTHLY', 'amount' => 0, 'children' => []],
                        ['name' => 'SURCHARGE MONTHLY', 'amount' => 0, 'children' => []],
                        ['name' => 'CESS MONTHLY', 'amount' => 0, 'children' => []],
                        ['name' => 'TDS RAW TAX', 'amount' => 0, 'children' => []],
                        ['name' => 'TDS SURCHARGE', 'amount' => 0, 'children' => []],
                        ['name' => 'TDS CESS', 'amount' => 0, 'children' => []],
                        ['name' => 'DIRECT TDS', 'amount' => 0, 'children' => []],
                        ['name' => 'BASIC DA PERCENTAGE', 'amount' => 0, 'children' => []],
                        ['name' => 'HOUSING PERQ PERCENTAGE', 'amount' => 0, 'children' => []],
                        ['name' => 'CLA / HRA', 'amount' => 0, 'children' => []],
                        ['name' => 'TAX REGIME(1-OLD, 2-NEW)', 'amount' => 0, 'children' => []],

                    ]],
                    ['name' => 'PERQUISITE ITEMS', 'amount' => 0, 'children' => [
                        ['name' => 'VEHICLE PERQ MONTHLY VALUE', 'amount' => 0, 'children' => []],
                        ['name' => 'VEHICLE PERQUISITE', 'amount' => 0, 'children' => []],
                        ['name' => 'HOUSE PERQUISITE', 'amount' => 0, 'children' => []],
                        ['name' => 'ASSETS IN RESIDENCE', 'amount' => 0, 'children' => []],
                        ['name' => 'EMPLOYER PF PERQUISITE', 'amount' => 0, 'children' => []],

                    ]],
                    ['name' => 'EXEMPTION ITEMS', 'amount' => 0, 'children' => [
                        ['name' => 'EDUCATION EXEMPT', 'amount' => 0, 'children' => []],
                        ['name' => 'MEDICAL EXEMPT', 'amount' => 0, 'children' => []],
                        ['name' => 'LA EXEMPT', 'amount' => 0, 'children' => []],
                        ['name' => 'CONVEYANCE EXEMPT', 'amount' => 0, 'children' => []],

                    ]],
                    ['name' => 'OTHER PAYMENT ITEMS', 'amount' => 0, 'children' => [
                        ['name' => 'MISC REIMBURSEMENT', 'amount' => 0, 'children' => []],

                    ]],
                    ['name' => 'SETTLEMENT RELATED ITEMS', 'amount' => 0, 'children' => [
                        ['name' => 'ENCASHMENT DAYS', 'amount' => 0, 'children' => []],
                        ['name' => 'NOTICE PERIOD SHORTFALL DAYS', 'amount' => 0, 'children' => []],
                        ['name' => 'NOTICE PERIOD EXCESS DAYS', 'amount' => 0, 'children' => []],

                    ]],
                    ['name' => 'PROJECTION ITEMS', 'amount' => 0, 'children' => [
                        ['name' => 'PROJ PROF TAX BASIC', 'amount' => 0, 'children' => []],
                        ['name' => 'PROJ PROF TAX', 'amount' => 0, 'children' => []],
                        ['name' => 'PROJ PF BASIC', 'amount' => 0, 'children' => []],
                        ['name' => 'PROJ PF', 'amount' => 0, 'children' => []],
                        ['name' => 'PROJ HOUSE PERQUISITE', 'amount' => 0, 'children' => []],
                        ['name' => 'PROJ VEHICLE PERQUISITE', 'amount' => 0, 'children' => []],
                        ['name' => 'PROJ CONV EXEMPTION', 'amount' => 0, 'children' => []],

                    ]],
                    ['name' => 'MISCELLANEOUS ITEMS', 'amount' => 0, 'children' => [
                        ['name' => 'PAYROLL MONTH', 'amount' => 0, 'children' => []],
                        ['name' => 'REMAINING MONTH', 'amount' => 0, 'children' => []],
                        ['name' => 'EMPLOYER LABOUR WELFARE CONTRIBUTION', 'amount' => 0, 'children' => []],
                        ['name' => 'PRORATA FACTOR', 'amount' => 0, 'children' => []],
                        ['name' => 'OT DAYS', 'amount' => 0, 'children' => []],
                        ['name' => 'OT HOURS', 'amount' => 0, 'children' => []],
                        ['name' => 'HOLD RELEASE SALARY', 'amount' => 0, 'children' => []],
                        ['name' => 'RETENTION BONUS', 'amount' => 0, 'children' => []],
                        ['name' => 'REFERRAL BONUS', 'amount' => 0, 'children' => []],
                        ['name' => 'RELOCATION BONUS', 'amount' => 0, 'children' => []],
                        ['name' => 'LOCATION INDICATOR', 'amount' => 0, 'children' => []],
                        ['name' => 'JOINING BONUS', 'amount' => 0, 'children' => []],
                        ['name' => 'NO. OF CHILDREN', 'amount' => 0, 'children' => []],
                        ['name' => 'PROF TAX AGENT', 'amount' => 0, 'children' => []],
                    ]],

                    ['name' => 'REIMBURSEMENT ITEMS', 'amount' => 0, 'children' => [
                        ['name' => 'GROUP1', 'amount' => 0, 'children' => [
                            ['name' => 'TELEPHONE REIMBURSEMENT', 'amount' => 0, 'children' => []],
                            ['name' => 'MEDICAL REIMBURSEMENT', 'amount' => 0, 'children' => []],
                            ['name' => 'LTA REIMBURSEMENT', 'amount' => 0, 'children' => []],

                        ]],
                        ['name' => 'GROUP2', 'amount' => 0, 'children' => [
                            ['name' => 'CHAUFFERS SALARY REIMBURSEMENT', 'amount' => 0, 'children' => []],
                            ['name' => 'FUEL MAINTENANCE A1600cc REIMBURSEMENT', 'amount' => 0, 'children' => []],
                            ['name' => 'FUEL MAINTENANCE B1600cc REIMBURSEMENT', 'amount' => 0, 'children' => []],
                            ['name' => 'BOOKS and PERIODICAL', 'amount' => 0, 'children' => []],
                            ['name' => 'PROFESSIONAL PURSUIT REIMBURSEMENT', 'amount' => 0, 'children' => []],
                            ['name' => 'HARD FURNISHING REIMBURSEMENT', 'amount' => 0, 'children' => []],
                            ['name' => 'MEDICAL ANNUAL REIMBURSEMENT', 'amount' => 0, 'children' => []],
                            ['name' => 'INTERNET REIMBURSEMENT', 'amount' => 0, 'children' => []],
                        ]],

                    ]],
                    ['name' => 'STATUTORY ITEMS', 'amount' => 0, 'children' => [
                        ['name' => 'CONVEYANCE LIMIT MONTHLY', 'amount' => 0, 'children' => []],
                        ['name' => 'CONVEYANCE LIMIT MONTHLY', 'amount' => 0, 'children' => []],

                    ]],
                    ['name' => 'FBP ENTITLEMENTS', 'amount' => 0, 'children' => [
                        ['name' => 'FUEL ENTITLEMENT', 'amount' => 0, 'children' => []],
                        ['name' => 'FBP LTA ENTITLEMENT', 'amount' => 0, 'children' => []],
                        ['name' => 'FBP MEDICAL ENTITLEMENT', 'amount' => 0, 'children' => []],
                        ['name' => 'FBP TELEPHONE ENTITLEMENT', 'amount' => 0, 'children' => []],

                    ]],
                    ['name' => 'FBP RELATED ITEMS', 'amount' => 0, 'children' => [
                        ['name' => 'FBP TOTAL', 'amount' => 0, 'children' => []],
                        ['name' => 'FBP FUEL', 'amount' => 0, 'children' => []],
                        ['name' => 'FBP SPECIAL', 'amount' => 0, 'children' => []],
                        ['name' => 'FBP LTA', 'amount' => 0, 'children' => []],
                        ['name' => 'FBP MEDICAL', 'amount' => 0, 'children' => []],
                        ['name' => 'FBP TELEPHONE', 'amount' => 0, 'children' => []],

                    ]],
                    ['name' => 'ACCUMULATION RELATED ITEMS', 'amount' => 0, 'children' => [
                        ['name' => 'ACCUMULATED TELEPHONE', 'amount' => 0, 'children' => []],
                        ['name' => 'ACCUMULATED MEDICAL', 'amount' => 0, 'children' => []],
                        ['name' => 'ACCUMULATED INTERNET', 'amount' => 0, 'children' => []],

                    ]],
                    ['name' => 'ENTITLEMENT RELATED ITEMS', 'amount' => 0, 'children' => [
                        ['name' => 'MEDICAL MONTHLY ENTITLEMENT', 'amount' => 0, 'children' => []],
                        ['name' => 'FUEL MAINTENANCE ABOVE 1600cc', 'amount' => 0, 'children' => []],
                        ['name' => 'FUEL MAINTENANCE BELOW 1600cc', 'amount' => 0, 'children' => []],
                        ['name' => 'CHAUFFERS SALARY ELIGIBILITY', 'amount' => 0, 'children' => []],
                        ['name' => 'TELEPHONE ENTITLEMENT', 'amount' => 0, 'children' => []],
                        ['name' => 'MEDICAL ANNUAL ENTITLEMENT', 'amount' => 0, 'children' => []],
                        ['name' => 'INTERNET ENTITLEMENT', 'amount' => 0, 'children' => []],
                        ['name' => 'LTA ENTITLEMENT', 'amount' => 0, 'children' => []],

                    ]],
                    ['name' => 'PRORATA RELATED ITEMS', 'amount' => 0, 'children' => [
                        ['name' => 'PRORATA MEDICAL', 'amount' => 0, 'children' => []],
                        ['name' => 'PRORATA TELEPHONE', 'amount' => 0, 'children' => []],
                        ['name' => 'PRORATA INTERNET', 'amount' => 0, 'children' => []],

                    ]],
                    ['name' => 'UNCLAIMED RELATED ITEMS', 'amount' => 0, 'children' => [
                        ['name' => 'UNCLAIMED BOOKS and PERIODICAL', 'amount' => 0, 'children' => []],
                        ['name' => 'UNCLAIMED CHAUFFERS SALARY', 'amount' => 0, 'children' => []],
                        ['name' => 'UNCLAIMED FUEL MAINTENANCE A1600cc', 'amount' => 0, 'children' => []],
                        ['name' => 'UNCLAIMED FUEL MAINTENANCE B1600cc', 'amount' => 0, 'children' => []],
                        ['name' => 'UNCLAIMED PROFESSIONAL PURSUIT', 'amount' => 0, 'children' => []],
                        ['name' => 'UNCLAIMED HARD FURNISHING', 'amount' => 0, 'children' => []],
                        ['name' => 'UNCLAIMED INTERNET', 'amount' => 0, 'children' => []],
                        ['name' => 'MEDICAL UNCLAIMED', 'amount' => 0, 'children' => []],

                    ]],
                    ['name' => 'Leave Encash Related Items', 'amount' => 0, 'children' => [
                        ['name' => 'Years in Service', 'amount' => 0, 'children' => []],
                        ['name' => 'YIS1', 'amount' => 0, 'children' => []],
                        ['name' => 'AVERAGE 10 MONTH BASIC', 'amount' => 0, 'children' => []],

                    ]],

                ],
            ],
            [
                'name' => ' EMPLOYEE WORKDAYS',
                'amount' => 0,
                'children' => [
                    ['name' => 'EMP EFFECTIVE WORKDAYS', 'amount' => 0, 'children' => []],
                    ['name' => 'EMP EFFECTIVE WORKDAYS FOR DISPLAY', 'amount' => 0, 'children' => []],
                    ['name' => 'EMPLOYEE WORKDAYS FOR DISPLAY', 'amount' => 0, 'children' => []],
                    ['name' => 'DAYS IN MONTH', 'amount' => 0, 'children' => []],
                    ['name' => 'EMPLOYEE WORKDAYS', 'amount' => 0, 'children' => []],
                    ['name' => 'LOP', 'amount' => 0, 'children' => []],
                    ['name' => 'LOP REVERSAL', 'amount' => 0, 'children' => []],
                ]
            ],
        ];

        $this->getSalaryDetails();
        $this->filteredcomponents = $this->components;
        $this->initializeExpandState($this->filteredcomponents);
        foreach ($this->filteredcomponents as $component) {
            if (!empty($component['children'])) {
                $this->expanded[$component['name']] = true; // Open first-level children
            }
        }
        // dd($this->filteredcomponents);
    }

    public function getFilteredComponentsProperty()
    {
        // dd();
        if (!$this->searchComponent) {
            $this->filteredcomponents = $this->components;
        }

        $this->filteredcomponents = $this->filterComponents($this->components, $this->searchComponent);
        // dd($this->filteredcomponents);
    }

    public function filterComponents($components, $searchTerm, $isChildVisible = false)
    {
        $filtered = [];
        foreach ($components as $component) {
            $children = !empty($component['children'])
                ? $this->filterComponents($component['children'], $searchTerm, $isChildVisible || stripos($component['name'], $searchTerm) !== false)
                : [];

            if ($isChildVisible || stripos($component['name'], $searchTerm) !== false || !empty($children)) {
                $filtered[] = [
                    'name' => $component['name'],
                    'amount' => $component['amount'],
                    'children' => $children
                ];
            }
        }

        return $filtered;
    }

    private function initializeExpandState($items)
    {
        foreach ($items as $item) {
            $this->expanded[$item['name']] = false;
            if (!empty($item['children'])) {
                $this->initializeExpandState($item['children']);
            }
        }
    }
    public function toggleExpand($name)
    {
        $this->expanded[$name] = !$this->expanded[$name];
    }

    public function toggleExpandAll()
    {
        $this->expandAll = !$this->expandAll;
        foreach ($this->expanded as $key => $value) {
            $this->expanded[$key] = $this->expandAll;
        }

        foreach ($this->filteredcomponents as $component) {
            if (!empty($component['children'])) {
                $this->expanded[$component['name']] = true; // Open first-level children
            }
        }
    }


    public function getSalaryDetails()
    {

        $lastSalary = EmpSalaryRevision::join('emp_salaries', 'salary_revisions.id', '=', 'emp_salaries.sal_id')
            ->where('salary_revisions.emp_id', $this->selectedEmployee)
            ->latest('emp_salaries.month_of_sal') // Get latest salary by month
            ->select('emp_salaries.*', 'salary_revisions.*') // Ensure all columns are selected
            ->first();

        $lastSalary = $lastSalary ? $lastSalary->toArray() : [];
        // dd($lastSalary );
        if ($lastSalary) {
            $salaryComponents = EmpSalaryRevision::getFullAndActualSalaryComponents($lastSalary['salary'], $lastSalary['revised_ctc'],$lastSalary['total_working_days'],$lastSalary['lop_days']);
            // $salaryComponents = EmpSalaryRevision::getFullAndActualSalaryComponents(18000, 240000,30,3);
// dd($salaryComponents);
            $this->updateComponentAmount($this->components, 'FULL BASIC', $salaryComponents['actual_basic']);
            $this->updateComponentAmount($this->components, 'FULL HRA', $salaryComponents['actual_hra']);
            $this->updateComponentAmount($this->components, 'FULL CONVEYANCE', $salaryComponents['actual_conveyance']);
            $this->updateComponentAmount($this->components, 'SALARY MASTER', $salaryComponents['actual_basic'] + $salaryComponents['actual_hra'] + $salaryComponents['actual_conveyance'] + $salaryComponents['actual_special']);
            $this->updateComponentAmount($this->components, 'FULL SPECIAL ALLOWANCE', $salaryComponents['actual_special']);
            $this->updateComponentAmount($this->components, 'NET PAY', $salaryComponents['actual_net_salary']);
            $this->updateComponentAmount($this->components, 'GROSS', $salaryComponents['actual_gross']);
            $this->updateComponentAmount($this->components, 'Basic', $salaryComponents['actual_basic']);
            $this->updateComponentAmount($this->components, 'HRA', $salaryComponents['actual_hra']);
            $this->updateComponentAmount($this->components, 'CONVEYANCE', $salaryComponents['actual_conveyance']);
            $this->updateComponentAmount($this->components, 'SPECIAL ALLOWANCE', $salaryComponents['actual_special']);
            $this->updateComponentAmount($this->components, 'MEDICAL ALLOWANCE', $salaryComponents['actual_medical']);
            $this->updateComponentAmount($this->components, 'TOTAL DEDUCTIONS', $salaryComponents['actual_total_deductions']);
            $this->updateComponentAmount($this->components, 'PF', $salaryComponents['actual_pf']);
            $this->updateComponentAmount($this->components, 'ESI', $salaryComponents['actual_esi']);
            $this->updateComponentAmount($this->components, 'LOP', $salaryComponents['lop_days']);
            $this->updateComponentAmount($this->components, 'EMPLOYEE WORKDAYS', $salaryComponents['full_days']);
            $this->updateComponentAmount($this->components, 'DAYS IN MONTH', $salaryComponents['full_days']);
            $this->updateComponentAmount($this->components, 'EMPLOYEE WORKDAYS FOR DISPLAY', $salaryComponents['full_days']);
            $this->updateComponentAmount($this->components, 'EMP EFFECTIVE WORKDAYS FOR DISPLAY', $salaryComponents['actual_working_days']);
            $this->updateComponentAmount($this->components, 'EMP EFFECTIVE WORKDAYS', $salaryComponents['actual_working_days']);
            // $this->updateComponentAmount($this->filteredcomponents, 'EMP EFFECTIVE WORKDAYS', $salaryComponents['net_salary']);
            // $this->updateComponentAmount($this->filteredcomponents, 'EMPLOYEE WORKDAYS FOR DISPLAY', $salaryComponents['net_salary']);
            // $this->updateComponentAmount($this->filteredcomponents, 'HRA', $salaryComponents['net_salary']);

        }
    }


    private function updateComponentAmount(&$components, $name, $amount)
    {
        foreach ($components as &$component) {
            if ($component['name'] === $name) {
                $component['amount'] = $amount;
                return; // Stop further iteration once updated
            }

            // Recursively check in children if they exist
            if (!empty($component['children'])) {
                $this->updateComponentAmount($component['children'], $name, $amount);
            }
        }
    }

    // Example usage

    public function render()
    {
        // dd( $this->filteredcomponents);
        // dd($this->components);
        $selectedEmployeesDetails = $this->selectedEmployee ?
            EmployeeDetails::where('emp_id', $this->selectedEmployee)->get() : [];

        return view('livewire.payroll-salary', [
            'selectedEmployeesDetails' => $selectedEmployeesDetails,
        ]);
    }
}

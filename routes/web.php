<?php

use App\Livewire\AccountsJv;
use App\Livewire\AddEditHrEmployees;
use App\Livewire\AddEmployeeDetails;
use App\Livewire\AddOrViewSalaryRevision;
use App\Livewire\AddProjects;
use App\Livewire\AdminDashboard;
use App\Livewire\AnalyticsHub;
use App\Livewire\AnalyticsHubViewAll;
use App\Livewire\AssignProject;
use App\Livewire\AttendanceExceptionForDisplay;
use App\Livewire\AttendanceLockConfiguration;
use App\Livewire\AttendanceMusterHr;
use App\Livewire\AttendanceProcess;
use App\Livewire\ClientRegistration;
use App\Livewire\ClientSList;
use App\Livewire\CompanyInfo;
use App\Livewire\CreateAttendanceExceptionPage;
use App\Livewire\CreateDamageComponent;
use App\Livewire\CreateEmployeeWeekDayChart;
use App\Livewire\CreateFineComponent;
use App\Livewire\CreateNewLockConfigurationPage;
use App\Livewire\CreateShiftOverride;

use App\Livewire\CTCSlips;
use App\Livewire\DamageComponent;
use App\Livewire\EditAttendanceExceptionPage;
use App\Livewire\EditShiftOverride;
use App\Livewire\EditSignatory;
use App\Livewire\EmpBulkPhotoUpload;
use App\Livewire\EmpDocument;
use App\Livewire\EmpLeaveGranterDetails;
use App\Livewire\EmployeeAsset;

use App\Livewire\EmployeeDirectory;
use App\Livewire\EmployeeLeave;

use App\Livewire\EmployeeProfile;
use App\Livewire\EmployeeSalary;
use App\Livewire\EmployeeSalaryCommonComponent;

use App\Livewire\EmployeeSwipesForHr;
use App\Livewire\EmployeeWeekDayChart;

use App\Livewire\Everyone;
use App\Livewire\Feeds;
use App\Livewire\FinalSettlement;
use App\Livewire\FinalSettlementStepper;
use App\Livewire\FineAndDamageComponent;
use App\Livewire\Flowchart;
use App\Livewire\GenerateLetters;
use App\Livewire\GrantLeaveBalance;
use App\Livewire\HelpDesk;
use App\Livewire\HoldSalaries;
use App\Livewire\HomeDashboard;
use App\Livewire\HrAttendanceInfo;
use App\Livewire\HrAttendanceOverviewNew;
use App\Livewire\HrEmployeesList;
use App\Livewire\HrHolidayList;
use App\Livewire\HrLeaveCalendar;
use App\Livewire\HrLeaveOverview;
use App\Livewire\HrLogin;
use App\Livewire\HrMainOverview;
use App\Livewire\HrManualOverride;
use App\Livewire\HrOrganisationChart;

use App\Livewire\LeaveSettingPolicy;
use App\Livewire\LeaveTypeReviewer;
use App\Livewire\LetterPreparePage;
use App\Livewire\LetterPreview;

use App\Livewire\ParentDetails;

use App\Livewire\PayrollOverview;

use App\Livewire\Payslips;
use App\Livewire\PfYtdReport;
use App\Livewire\PositionHistory;
use App\Livewire\PreviousEmployeement;
use App\Livewire\ProjectsList;

use App\Livewire\RegularisationPendingForHr;
use App\Livewire\ReimbursementStatement;

use App\Livewire\ReportsManagement;
use App\Livewire\Requests;
use App\Livewire\ResettlementProcessPage;
use App\Livewire\Resignationrequests;
use App\Livewire\SalaryRevision;
use App\Livewire\SalaryRevisionAnalytics;
use App\Livewire\SalaryRevisionHistory;
use App\Livewire\SalaryRevisionList;

use App\Livewire\ShiftOverrideHr;
use App\Livewire\ShiftRosterHr;
use App\Livewire\ShiftRotationCalendar;
use App\Livewire\StopSalaries;
use App\Livewire\SwipeManagementForHr;
use App\Livewire\AuthorizeSignatory;
use App\Livewire\BankAccount;
use App\Livewire\CreateSignatory;
use App\Livewire\EmployeeDataUpdate;
use App\Livewire\EmployeeLopDays;
use App\Livewire\Loans;
use App\Livewire\ReleaseSalary;
use App\Livewire\EmployeeSeparation;
use App\Livewire\EmpResettlement;
use App\Livewire\IncomeTax;
use App\Livewire\ItStatement;
use App\Livewire\SalarySlip;
use App\Livewire\PayrollSalary;
use App\Livewire\LeaveApplyOnBehalf;
use App\Livewire\QuickSalary;
use App\Livewire\PayrollArrears;
use App\Livewire\Tasks;
use App\Livewire\UpdateEmployeeDetails;
use App\Livewire\WeekendOverride;
use App\Livewire\WhoIsInChartHr;
use App\Livewire\YearEndProcess;
use App\Livewire\YtdReport;
use App\Models\EmpResignations;
use App\Models\Task;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Response;
use App\Livewire\HrProfile;
use App\Livewire\KnowledgeBase;
use App\Models\ClientProjects;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });
   
Route::middleware(['guest'])->group(function () {
    Route::get('/hrlogin', HrLogin::class)->name('hrlogin');
});
Route::middleware(['auth', 'auth.session'])->group(function () {

        
    Route::get('/', HomeDashboard::class)->name('home');
    // Group routes under the 'hr' prefix
    Route::prefix('hr')->group(function () {
        //home page routes
        Route::get('/profile', HrProfile::class)->name('profile');
        Route::get('/add-employee-details/{employee?}', AddEmployeeDetails::class)->name('add-employee-details');
        Route::get('/update-employee-details', UpdateEmployeeDetails::class)->name('update-employee-details');
        Route::get('/resig-requests', Resignationrequests::class)->name('resig-requests');
        Route::get('/HelpDesk', HelpDesk::class)->name('HelpDesk');
        Route::get('/request', Requests::class)->name('request');
        Route::get('/user/tasks', Tasks::class)->name('tasks');
        // In your web.php file
        Route::get('/admin-dashboard/{activeTab}/{selectedOption?}', AdminDashboard::class)->name('admin-dashboard');

        // Route::get('/user/client-registration', ClientRegistration::class)->name('client.create');
        // Route for adding a new client
        Route::get('/user/client-registration', ClientRegistration::class)->name('client.create');
        Route::get('/hr-employees-list', HrEmployeesList::class)->name('hr-employees-list');
        Route::get('/add-edit-hr', AddEditHrEmployees::class)->name('add-edit-hr');

        // Route for editing an existing client
        Route::get('/user/client-registration/{clientId}', ClientRegistration::class)->name('client.edit');

        Route::get('/letter-preview', LetterPreview::class);
        Route::get('/clientList', ClientSList::class)->name('clientList.page');
        Route::get('/projectList', ProjectsList::class)->name('project.list');
        Route::get('/projects/create/{client_id}', AddProjects::class)->name('project.create');
        Route::get('assign-project', AssignProject::class)->name('assign.project');
        Route::get('/taskfile/{id}', function ($id) {
            $file = Task::findOrFail($id);

            return Response::make($file->file_path, 200, [
                'Content-Type' => $file->mime_type,
                'Content-Disposition' => (strpos($file->mime_type, 'image') === false ? 'attachment' : 'inline') . '; filename="' . $file->file_name . '"',
            ]);
        })->name('files.showTask');

        //company info
        Route::get('company-info', CompanyInfo::class)->name('company_info');
        //feeds
        Route::get('/hrFeeds', Feeds::class)->name('hrfeeds');
        Route::get('/everyone', Everyone::class)->name('everyone');


        //HR Payroll Submodule Routes->Published Info
        Route::get('/payslips', Payslips::class)->name('payslips');
        Route::get('/ctcslips', CTCSlips::class)->name('ctcslips');
        Route::get('/ytdreport', YtdReport::class)->name('ytdreport');
        Route::get('/pfytdreport', PfYtdReport::class)->name('pfytdreport');
        Route::get('/reimbursement', ReimbursementStatement::class)->name('reimbursement');
        Route::get('/itstatement', ItStatement::class)->name('itstatement');
        //HR Payroll Submodule Routes->Payouts
        Route::get('/accountsjv', AccountsJv::class)->name('accountsjv');
        Route::get('/salaryslip', SalarySlip::class)->name('salaryslip');
        //HR Payroll Submodule Routes->Verify
        Route::get('/quicksalary', QuickSalary::class)->name('quicksalary');
        Route::get('/income-tax', IncomeTax::class)->name('income-tax');
        //HR Payroll Submodule Routes->Verify
        Route::get('/quicksalary', QuickSalary::class)->name('quicksalary');


        //HR Employee-Main Submodule Routes
        Route::get('/user/main-overview', HrMainOverview::class)->name('main-overview');
        Route::get('/user/analytics-hub', AnalyticsHub::class)->name('analytics-hub');
        Route::get('/user/analytics-hub-viewall', AnalyticsHubViewAll::class)->name('analytics-hub-viewall');
        Route::get('/user/hremployeedirectory', EmployeeDirectory::class)->name('employee-directory');
        Route::get('/user/create-attendance-exception', CreateAttendanceExceptionPage::class)->name('create-attendance-exception');
        Route::get('/user/shift-rotation-calendar', ShiftRotationCalendar::class)->name('shift-rotation-calendar');
        Route::get('/user/attendance-lock-configuration', AttendanceLockConfiguration::class)->name('attendance-lock-configuration');
        Route::get('/user/create-lock-configuration', CreateNewLockConfigurationPage::class)->name('create-new-lock-configuration-page');
        Route::get('/user/weekend-override',  WeekendOverride::class)->name('weekend-override');

        //HR Employee-Information Submodule Routes
        Route::get('/employee-profile', EmployeeProfile::class)->name('employee-profile');
        Route::get('/employee-asset', EmployeeAsset::class)->name('employee-asset');
        Route::get('/position-history', PositionHistory::class)->name('position-history');
        Route::get('parent-details', ParentDetails::class)->name('parent-details');
        Route::get('/emp-document', EmpDocument::class)->name('emp-document');
        Route::get('/bank-account', BankAccount::class)->name('bank-account');
        Route::get('/previous', PreviousEmployeement::class)->name('previous');
        Route::get('/user/attendance-process', AttendanceProcess::class)->name('attendance-process');
        Route::get('/user/swipe-management-for-hr', SwipeManagementForHr::class)->name('swipe-management-for-hr');
        Route::get('/user/employee-swipes-for-hr', EmployeeSwipesForHr::class)->name('employee-swipes-for-hr');
        Route::get('/user/hr-manual-override', HrManualOverride::class)->name('hr-manual-override');
        Route::get('/user/fine-and-damage', FineAndDamageComponent::class)->name('fine-and-damage');
        Route::get('/user/add-fine-page/{id?}/{viewMode?}', CreateFineComponent::class)->name('add-fine-page');
        Route::get('/user/add-damage-page/{id?}/{viewMode?}', CreateDamageComponent::class)->name('add-damage-page');
        Route::get('/user/damage-page', DamageComponent::class)->name('damage-page');
        Route::get('/user/flowchart/{selectedEmployeeId}', Flowchart::class)->name('flowchart');

        //Employee-Admin sub modules
        Route::get('/user/generate-letter', GenerateLetters::class)->name('generate-letter');
        Route::get('/letter/prepare', LetterPreparePage::class)->name('letter.prepare');
        Route::get('/letter-preview', LetterPreview::class);
        Route::get('/authorize-signatory', AuthorizeSignatory::class)->name('authorize-signatory.page');
        Route::get('/signatories/create', CreateSignatory::class)->name('signatory.create');
        Route::get('/signatory/edit/{id}', EditSignatory::class)->name('signatory.edit');
        Route::get('user/emp/admin/bulkphoto-upload', EmpBulkPhotoUpload::class)->name('emp-bulk-photo-upload');


        //HR Leave-Main Submodule Routes
        Route::get('/user/hr-organisation-chart/{selectedEmployeeId?}', HrOrganisationChart::class)->name('hr-organisation-chart');
        Route::get('/user/leave-overview', HrLeaveOverview::class)->name('leave-overview');
        Route::get('/user/leave-overview/{month}/{leaveType?}', HrLeaveOverview::class)->name('leave-overview.month');
        Route::get('/leave-overview/{monthLeaveType?}', HrLeaveOverview::class)->name('leave-overview.monthLeaveType');
        Route::get('/user/hr-attendance-overview', HrAttendanceOverviewNew::class)->name('attendance-overview');
        Route::get('/user/leave-calendar', HrLeaveCalendar::class)->name('Leave-calendar');
        Route::get('/user/who-is-in-chart-hr', WhoIsInChartHr::class)->name('who-is-in-chart-hr');

        //HR Leave-Infomation Submodule Routes
        Route::get('/user/employee-leave', EmployeeLeave::class)->name('employee-leave');
        Route::get('/user/apply-leave-behalf', LeaveApplyOnBehalf::class)->name('apply-leave-behalf');
        Route::get('/user/shift-roster-hr', ShiftRosterHr::class)->name(name: 'shift-roster-hr');
        Route::get('/user/attendance-info', HrAttendanceInfo::class)->name('attendance-info');
        Route::get('/user/attendance-muster-hr', AttendanceMusterHr::class)->name(name: 'attendance-muster-hr');
        Route::get('/user/swipe-management-for-hr', SwipeManagementForHr::class)->name('swipe-management-for-hr');
        Route::get('/user/employee-swipes-for-hr', EmployeeSwipesForHr::class)->name('employee-swipes-for-hr');

        //HR Leave-Admin Submodule Routes
        Route::get('/user/grantLeave', GrantLeaveBalance::class)->name('grantLeave');
        Route::get('/user/grant-summary', EmpLeaveGranterDetails::class)->name('grant-summary');
        Route::get('/user/leavePolicySettings', LeaveSettingPolicy::class)->name('leavePolicySettings');
        Route::get('/user/leaveYearEndProcess', YearEndProcess::class)->name('year-end-process');
        Route::get('/user/attendance-process', AttendanceProcess::class)->name('attendance-process');
        Route::get('/user/swipe-management-for-hr', SwipeManagementForHr::class)->name('swipe-management-for-hr');
        Route::get('/user/hr-organisation-chart', HrOrganisationChart::class)->name('hr-organisation-chart');
       
        Route::get('/user/hr-manual-override', HrManualOverride::class)->name('hr-manual-override');
        Route::get('/user/edit-attendance-exception-page/{id}', EditAttendanceExceptionPage::class)->name('edit-attendance-exception-page');
        Route::get('/user/edit-shift-override/{id}', EditShiftOverride::class)->name('edit-shift-override');
        Route::get('/user/shift-override', ShiftOverrideHr::class)->name('shift-override');
        Route::get('/user/attendance-exception', AttendanceExceptionForDisplay::class)->name(name: 'attendance-exception');
        Route::get('/user/create-attendance-exception', CreateAttendanceExceptionPage::class)->name('create-attendance-exception');
        Route::get('/user/attendance-lock-configuration', AttendanceLockConfiguration::class)->name('attendance-lock-configuration');
        Route::get('/user/create-lock-configuration', CreateNewLockConfigurationPage::class)->name('create-new-lock-configuration-page');
        Route::get('/user/edit-shift-override/{id}', EditShiftOverride::class)->name('edit-shift-override');
        Route::get('/user/create-shift-override', CreateShiftOverride::class)->name('create-shift-override');
        Route::get('/review-pending-regularisation-for-hr/{id}/{emp_id}', RegularisationPendingForHr::class)->name('review-pending-regularisation-for-hr');

        //HR Leave-SetUp Submodule Routes
        Route::get('/user/holidayList', HrHolidayList::class)->name('holidayList');
        Route::get('/user/employee-weekday-chart', EmployeeWeekDayChart::class)->name('employee-weekday-chart');
        Route::get('/user/create-employee-weekday-chart', CreateEmployeeWeekDayChart::class)->name('create-employee-weekday-chart');
        Route::get('/user/leave/setup/leave-type-reviewer', LeaveTypeReviewer::class)->name('leave-type-reviewer');
        Route::get('/user/shift-rotation-calendar', ShiftRotationCalendar::class)->name('shift-rotation-calendar');

        //extra routes
        Route::get('/review-pending-regularisation-for-hr/{id}/{emp_id}', RegularisationPendingForHr::class)->name('review-pending-regularisation-for-hr');
        Route::get('/employee-data-update/{action}', EmployeeDataUpdate::class)->name('employee.data.update');
        Route::get('/user/employee-separation', EmployeeSeparation::class)->name('employee-separation');

        //Reports
        Route::get('/user/reports/', ReportsManagement::class)->name('reports');

        //Payroll
        Route::get('/user/salary-revision', SalaryRevision::class)->name('salary-revision');
        Route::get('/user/employee-salary', EmployeeSalary::class)->name('employee-salary');
        Route::get('/user/employee-salary-component', EmployeeSalaryCommonComponent::class)->name('employee-salary-component');
        Route::get('/user/stop-salaries', StopSalaries::class)->name('stop-salaries');
        Route::get('/user/hold-salaries', HoldSalaries::class)->name('hold-salaries');
        Route::get('/user/employee-salary-history', SalaryRevisionHistory::class)->name('employee-salary-history');
        Route::get('/user/salary-revision-list', SalaryRevisionList::class)->name('salary-revision-list');
        Route::get('/user/salary-revision-analytics', SalaryRevisionAnalytics::class)->name('salary-revision-analytics');
        Route::get('/user/addorview-salary-revision', AddOrViewSalaryRevision::class)->name('addorview-salary-revision');
        Route::get('/user/loans', Loans::class)->name('loans');
        Route::get('/user/release-salary', ReleaseSalary::class)->name('release-salary');
        Route::get('/user/employee-lop-days', EmployeeLopDays::class)->name('employee-lop-days');
        Route::get('/user/payroll-salary', PayrollSalary::class)->name('payroll-salary');
        Route::get('/user/final-settlement', FinalSettlement::class)->name('final-settlement');
        Route::get('/user/final-settlement-stepper', FinalSettlementStepper::class)->name('final-settlement-stepper');
        Route::get('/user/payroll/arrears', PayrollArrears::class)->name('arrears');
        Route::get('/user/payroll/resttlement', EmpResettlement::class)->name('resttlement');
        Route::get('/user/payroll/resttlement/process', ResettlementProcessPage::class)->name('resttelement-process');
    });
});

Route::get('/file/{id}', function ($id) {
    $file = EmpResignations::findOrFail($id);

    return Response::make($file->signature, 200, [
        'Content-Type' => $file->mime_type,
        'Content-Disposition' => (strpos($file->mime_type, 'image') === false ? 'attachment' : 'inline') . '; filename="' . $file->file_name . '"',
    ]);
})->name('file.show');


Route::get('/privacy-and-policy', function () {
    return view('privacy_policy_view');
});

Route::get('/knowledge-base', function () {
    return view('knowledge_base_view');
});

Route::get('/Terms&Services', function () {
    return view('terms_services_view');
});

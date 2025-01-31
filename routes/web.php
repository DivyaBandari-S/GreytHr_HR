<?php

use App\Livewire\AdminDashboard;
use App\Livewire\Dashboard;
use App\Livewire\HrLogin;
use App\Livewire\HomeDashboard;
use App\Livewire\AddEmployeeDetails;
use App\Livewire\AnalyticsHub;
use App\Livewire\AnalyticsHubViewAll;
use App\Livewire\AttendanceException;
use App\Livewire\AttendanceExceptionForDisplay;
use App\Livewire\AttendanceMusterHr;
use App\Livewire\AttendanceProcess;
use App\Livewire\BankAccount;
use App\Livewire\CreateAttendanceExceptionPage;
use App\Livewire\CreateShiftOverride;
use App\Livewire\EditAttendanceExceptionPage;
use App\Livewire\EditShiftOverride;
use App\Livewire\EmpBulkPhotoUpload;
use App\Livewire\EmpDocument;
use App\Livewire\EmpLeaveGranterDetails;
use App\Livewire\EmployeeAsset;
use App\Livewire\GrantLeaveBalance;
use App\Livewire\RegularisationPendingForHr;
use App\Livewire\UpdateEmployeeDetails;
use App\Livewire\Resignationrequests;
use App\Livewire\EmployeeDirectory;
use App\Livewire\EmployeeLeave;
use App\Livewire\EmployeeProfile;
use App\Livewire\EmployeeSwipesForHr;
use App\Livewire\EmployeeWeekDayChart;
use App\Livewire\EmployeeSalary;
use App\Livewire\Everyone;
use App\Livewire\Feeds;
use App\Livewire\HelpDesk;
use App\Livewire\GenerateLetters;
use App\Livewire\HrAttendanceInfo;
use App\Livewire\HrAttendanceOverviewNew;
use App\Livewire\HrHolidayList;
use App\Livewire\HrLeaveCalendar;
use App\Livewire\HrLeaveOverview;
use App\Livewire\HrMainOverview;
use App\Livewire\HrOrganisationChart;
use App\Livewire\LeaveRecalculator;
use App\Livewire\LeaveSettingPolicy;
use App\Livewire\LeaveTypeReviewer;
use App\Livewire\LetterPreparePage;
use App\Livewire\ParentDetails;
use App\Livewire\PositionHistory;
use App\Livewire\ReportsManagement;
use App\Livewire\Requests;
use App\Livewire\ShiftOverrideHr;
use App\Livewire\ShiftRosterHr;
use App\Livewire\ShiftRotationCalendar;
use App\Livewire\SwipeManagementForHr;
use App\Livewire\Tasks;
use App\Livewire\WhoIsInChartHr;
use App\Livewire\YearEndProcess;
use App\Models\EmpResignations;
use App\Models\Task;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Response;

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

Route::middleware(['checkauth'])->group(function () {
    Route::get('/hrlogin', HrLogin::class)->name('hrlogin');
});
Route::get('/file/{id}', function ($id) {
    $file = EmpResignations::findOrFail($id);

    return Response::make($file->signature, 200, [
        'Content-Type' => $file->mime_type,
        'Content-Disposition' => (strpos($file->mime_type, 'image') === false ? 'attachment' : 'inline') . '; filename="' . $file->file_name . '"',
    ]);
})->name('file.show');

Route::middleware(['auth:hr', 'handleSession'])->group(function () {

    Route::get('/', HomeDashboard::class)->name('home');
    Route::get('/request', Requests::class)->name('request');
    // Group routes under the 'hr' prefix
    Route::prefix('hr')->group(function () {


        //home page routes
        Route::get('/add-employee-details/{employee?}', AddEmployeeDetails::class)->name('add-employee-details');
        Route::get('/update-employee-details', UpdateEmployeeDetails::class)->name('update-employee-details');
        Route::get('/resig-requests', Resignationrequests::class)->name('resig-requests');
        Route::get('/HelpDesk', HelpDesk::class)->name('HelpDesk');
        Route::get('/user/tasks', Tasks::class)->name('tasks');
        Route::get('/taskfile/{id}', function ($id) {
            $file = Task::findOrFail($id);

            return Response::make($file->file_path, 200, [
                'Content-Type' => $file->mime_type,
                'Content-Disposition' => (strpos($file->mime_type, 'image') === false ? 'attachment' : 'inline') . '; filename="' . $file->file_name . '"',
            ]);
        })->name('files.showTask');


        //feeds
        Route::get('/hrFeeds', Feeds::class)->name('hrfeeds');
        Route::get('/everyone', Everyone::class)->name('everyone');

        //HR Employee-Main Submodule Routes
        Route::get('/user/main-overview', HrMainOverview::class)->name('main-overview');
        Route::get('/user/analytics-hub', AnalyticsHub::class)->name('analytics-hub');
        Route::get('/user/analytics-hub-viewall', AnalyticsHubViewAll::class)->name('analytics-hub-viewall');
        Route::get('/user/hremployeedirectory', EmployeeDirectory::class)->name('employee-directory');
        Route::get('/user/hr-organisation-chart', HrOrganisationChart::class)->name('hr-organisation-chart');

        //HR Employee-Information Submodule Routes
        Route::get('/employee-profile', EmployeeProfile::class)->name('employee-profile');
        Route::get('/employee-asset', EmployeeAsset::class)->name('employee-asset');
        Route::get('/position-history', PositionHistory::class)->name('position-history');
        Route::get('parent-details', ParentDetails::class)->name('parent-details');
        Route::get('/emp-document', EmpDocument::class)->name('emp-document');
        Route::get('/bank-account', BankAccount::class)->name('bank-account');
        Route::get('/user/employee-salary', EmployeeSalary::class)->name('employee-salary');

        //HR Employee-Admin Submodule Routes
        Route::get('/user/generate-letter', GenerateLetters::class)->name('generate-letter');
        Route::get('/letter/prepare', LetterPreparePage::class)->name('letter.prepare');
        Route::get('/user/emp/admin/bulkphoto-upload', EmpBulkPhotoUpload::class)->name('bulk-photo-upload');

        //HR Employee-setUp Submodule Routes


        //HR Leave-Main Submodule Routes
        Route::get('/user/leave-overview', HrLeaveOverview::class)->name('leave-overview');
        Route::get('/user/leave-overview/{month}/{leaveType?}', HrLeaveOverview::class)->name('leave-overview.month');
        Route::get('/leave-overview/{monthLeaveType?}', HrLeaveOverview::class)->name('leave-overview.monthLeaveType');
        Route::get('/user/hr-attendance-overview', HrAttendanceOverviewNew::class)->name('attendance-overview');
        Route::get('/user/leave-calendar', HrLeaveCalendar::class)->name('Leave-calendar');
        Route::get('/user/who-is-in-chart-hr', WhoIsInChartHr::class)->name('who-is-in-chart-hr');

        //HR Leave-Infomation Submodule Routes
        Route::get('/user/employee-leave', EmployeeLeave::class)->name('employee-leave');
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
        Route::get('/user/attendance-exception', AttendanceExceptionForDisplay::class)->name(name: 'attendance-exception');
        Route::get('/user/create-attendance-exception', CreateAttendanceExceptionPage::class)->name('create-attendance-exception');
        Route::get('/user/edit-attendance-exception-page/{id}', EditAttendanceExceptionPage::class)->name('edit-attendance-exception-page');
        Route::get('/user/create-shift-override', CreateShiftOverride::class)->name(name: 'create-shift-override');
        Route::get('/user/edit-shift-override/{id}', EditShiftOverride::class)->name('edit-shift-override');
        Route::get('/user/shift-override', ShiftOverrideHr::class)->name('shift-override');
        Route::get('/user/leave/admin/leaveRecalculator', LeaveRecalculator::class)->name('leave-recalculator');
        Route::get('/user/attendance-process', AttendanceProcess::class)->name('attendance-process');

        //HR Leave-SetUp Submodule Routes
        Route::get('/user/holidayList', HrHolidayList::class)->name('holidayList');
        Route::get('/user/leave/setup/leave-type-reviewer', LeaveTypeReviewer::class)->name('leave-type-reviewer');
        Route::get('/user/employee-weekday-chart', EmployeeWeekDayChart::class)->name('employee-weekday-chart');
        Route::get('/user/shift-rotation-calendar', ShiftRotationCalendar::class)->name('shift-rotation-calendar');

        //extra routes
        Route::get('/review-pending-regularisation-for-hr/{id}/{emp_id}', RegularisationPendingForHr::class)->name('review-pending-regularisation-for-hr');

        //Reports
        Route::get('/user/reports/', ReportsManagement::class)->name('reports');
    });
});

#########################################This are routes for checking hash and encrypt values################################################################################################################################################################

use App\Models\EmpSalary;
use Illuminate\Support\Facades\Artisan;
use Vinkla\Hashids\Facades\Hashids;

Route::get('/encode/{value}', function ($value) {
    // Determine the number of decimal places
    $decimalPlaces = strpos($value, '.') !== false ? strlen(substr(strrchr($value, "."), 1)) : 0;

    // Convert the float to an integer with precision
    $factor = pow(10, $decimalPlaces);
    $integerValue = intval($value * $factor);

    // Encode the integer value along with the decimal places
    $hash = Hashids::encode($integerValue, $decimalPlaces);

    return response()->json([
        'value' => $value,
        'hash' => $hash,
        // 'decimalPlaces' => $decimalPlaces
    ]);
});



Route::get('/decode/{hash}', function ($hash) {
    // Decode the hash
    $decoded = Hashids::decode($hash);

    // Check if decoding was successful
    if (count($decoded) === 0) {
        return response()->json(['error' => 'Invalid hash'], 400);
    }

    // Retrieve the integer value and decimal places
    $integerValue = $decoded[0];
    $decimalPlaces = $decoded[1] ?? 0; // Fallback to 0 if not present

    // Convert back to float
    $originalValue = $integerValue / pow(10, $decimalPlaces);

    return response()->json([
        'hash' => $hash,
        'value' => $originalValue
    ]);
});



Route::get('/salary/{emp_id}', function ($emp_id) {
    $empSalary = EmpSalary::findOrFail($emp_id);
    // Return the salary attribute
    return response()->json([
        'emp_id' => $empSalary->emp_id,
        'salary' => $empSalary->salary, // This will automatically call the getSalaryAttribute method
        'effective_date' => $empSalary->effective_date,
        'remarks' => $empSalary->remarks,
    ]);
});

use Illuminate\Support\Facades\Crypt;

Route::get('/encode-decode/{value}', function ($value) {
    try {
        // Attempt to decrypt the value
        $decrypted = Crypt::decryptString($value);
        return response()->json(['action' => 'decrypted', 'value' => $decrypted]);
    } catch (\Exception $e) {
        // If decryption fails, encrypt the value
        $encrypted = Crypt::encryptString($value);
        return response()->json(['action' => 'encrypted', 'value' => $encrypted]);
    }
});

use Illuminate\Support\Facades\Hash;

Route::get('/hash-verify/{value}', function ($value) {
    // Attempt to verify the value against the hashed version
    // Here, we'll assume that a certain value (e.g., 'originalValue') needs to be verified
    $originalValue = 'originalValue'; // Replace this with the actual value you want to verify against

    if (Hash::check($originalValue, $value)) {
        return response()->json(['action' => 'verified', 'value' => $originalValue]);
    } else {
        // If not verified, hash the original value
        $hashed = Hash::make($originalValue);
        return response()->json(['action' => 'hashed', 'value' => $hashed]);
    }
});

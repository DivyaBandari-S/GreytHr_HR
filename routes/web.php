<?php

use App\Livewire\AdminDashboard;
use App\Livewire\Dashboard;
use App\Livewire\HrLogin;
use App\Livewire\HomeDashboard;
use App\Livewire\AddEmployeeDetails;
use App\Livewire\AnalyticsHub;
use App\Livewire\AnalyticsHubViewAll;
use App\Livewire\AttendanceMusterHr;
use App\Livewire\EmpDocument;
use App\Livewire\EmpLeaveGranterDetails;
use App\Livewire\EmployeeAsset;
use App\Livewire\GrantLeaveBalance;
use App\Livewire\UpdateEmployeeDetails;
use App\Livewire\Resignationrequests;
use App\Livewire\EmployeeDirectory;
use App\Livewire\EmployeeLeave;
use App\Livewire\EmployeeProfile;
use App\Livewire\EmployeeSalary;
use App\Livewire\Feeds;
use App\Livewire\HrAttendanceInfo;
use App\Livewire\HrAttendanceOverviewNew;
use App\Livewire\HrHolidayList;
use App\Livewire\HrLeaveOverview;
use App\Livewire\HrMainOverview;
use App\Livewire\LeaveSettingPolicy;
use App\Livewire\ParentDetails;
use App\Livewire\PositionHistory;
use App\Livewire\ShiftRosterHr;
use App\Livewire\WhoIsInChartHr;
use App\Models\EmpResignations;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Response;
use Vinkla\Hashids\Facades\Hashids;

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
    // Group routes under the 'hr' prefix
    Route::prefix('hr')->group(function () {


        //home page routes
        Route::get('/add-employee-details/{employee?}', AddEmployeeDetails::class)->name('add-employee-details');
        Route::get('/update-employee-details', UpdateEmployeeDetails::class)->name('update-employee-details');
        Route::get('/resig-requests', Resignationrequests::class)->name('resig-requests');



        //HR Employee-Main Submodule Routes
        Route::get('/user/main-overview', HrMainOverview::class)->name('main-overview');
        Route::get('/user/analytics-hub', AnalyticsHub::class)->name('analytics-hub');
        Route::get('/user/analytics-hub-viewall', AnalyticsHubViewAll::class)->name('analytics-hub-viewall');
        Route::get('/user/hremployeedirectory', EmployeeDirectory::class)->name('employee-directory');

        //HR Employee-Information Submodule Routes
        Route::get('/hrFeeds', Feeds::class)->name('hrfeeds');
        Route::get('/employee-profile', EmployeeProfile::class)->name('employee-profile');
        Route::get('/employee-asset', EmployeeAsset::class)->name('employee-asset');
        Route::get('/position-history', PositionHistory::class)->name('position-history');
        Route::get('/parent', ParentDetails::class)->name('parent-details');
        Route::get('/emp-document', EmpDocument::class)->name('emp-document');
        Route::get('/bank-account', EmpDocument::class)->name('bank-account');
        Route::get('/employee-salary', EmployeeSalary::class)->name('employee-salary');
        //HR Leave-Main Submodule Routes
        Route::get('/user/hr-attendance-overview', HrAttendanceOverviewNew::class)->name('attendance-overview');
        Route::get('/user/who-is-in-chart-hr', WhoIsInChartHr::class)->name('who-is-in-chart-hr');
        Route::get('/user/attendance-info', HrAttendanceInfo::class)->name('attendance-info');

        //HR Leave-Infomation Submodule Routes
        Route::get('/user/employee-leave', EmployeeLeave::class)->name('employee-leave');

        //HR Leave Related Routes

        //HR Leave-Main Submodule Routes
        Route::get('/user/leave-overview', HrLeaveOverview::class)->name('leave-overview');
        Route::get('/user/leave-overview/{month}/{leaveType?}', HrLeaveOverview::class)->name('leave-overview.month');
        Route::get('/leave-overview/{monthLeaveType?}', HrLeaveOverview::class)->name('leave-overview.monthLeaveType');

        Route::get('/user/attendance-info', HrAttendanceInfo::class)->name('attendance-info');
        Route::get('/user/attendance-muster-hr', AttendanceMusterHr::class)->name(name: 'attendance-muster-hr');
        Route::get('/user/shift-roster-hr', ShiftRosterHr::class)->name(name: 'shift-roster-hr');
        Route::get('/user/employee-leave', EmployeeLeave::class)->name(name: 'employee-leave');


        //HR Leave-Admin Submodule Routes
        Route::get('/user/grantLeave', GrantLeaveBalance::class)->name('grantLeave');
        Route::get('/user/grant-summary', EmpLeaveGranterDetails::class)->name( 'grant-summary');
        Route::get('/user/leavePolicySettings', LeaveSettingPolicy::class)->name( 'leavePolicySettings');


        //HR Leave-SetUp Submodule Routes
        Route::get('/user/holidayList', HrHolidayList::class)->name('holidayList');


    });
});


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

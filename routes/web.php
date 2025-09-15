<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\PolicyController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\SpecialAccessController;
use App\Http\Controllers\ITRequestController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\MpdfController;
use App\Http\Controllers\QRPrintController;
use App\Http\Controllers\VersionControlController;
use App\Http\Controllers\RequestFieldController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ActivitiesController;
use App\Http\Controllers\TeamsController;
use App\Http\Controllers\GeocodeController;
use App\Http\Controllers\FormActivityController;
Route::post('/qr-process', [QRPrintController::class, 'print'])->name('qr.print');
/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
| Routes accessible only after login
| Maintenance Routes
*/
Route::middleware(['guest', 'prevent-back-history'])->group(function () {
Route::get('/', [PageController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'processLogin'])->name('login.process');
});
Route::middleware(['auth', 'dashboard.maintenance'])->group(function () {

    // Dashboard and common user actions
Route::get('/dashboard', [PageController::class, 'dashboard'])->name('dashboard');
Route::get('/inventory/data', [InventoryController::class, 'getData'])->name('inventory.data');
Route::get('/send-gmail/{id}', [MailController::class, 'sendGmail'])->name('send.button');
Route::get('/pdf/view', [PDFController::class, 'view'])->name('pdf.view');
  
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::post('/change-password', [AuthController::class, 'changePassword'])->name('password.update');

Route::get('/mpdf', [MpdfController::class, 'generate']);
Route::get('/collection', [MpdfController::class, 'collection']);
Route::get('/scanner', [PageController::class, 'scanner'])->name('scanner');
/*
|--------------------------------------------------------------------------

|--------------------------------------------------------------------------
| Accessible only when dashboard is active and user is authenticated
*/
Route::get('/computer_inventory', [PageController::class, 'computer_inventory'])->name('computer.inventory');
Route::get('/policy-render', [PolicyController::class, 'policy_render'])->name('policy.render');
Route::get('/download-file', [PDFController::class, 'downloadFile'])->name('sftp.download');

Route::get('/calendar/events', [CalendarController::class, 'events'])->name('calendar.events'); // Load events for FullCalendar
Route::post('/calendar/store', [CalendarController::class, 'store'])->name('calendar.store')->middleware('can:agenda.create');   // Store new event
Route::get('/calendar/event/{id}', [CalendarController::class, 'show'])->name('calendar.show'); // Show event details
Route::delete('/calendar/event/{id}', [CalendarController::class, 'destroy'])->name('calendar.destroy')->middleware('can:agenda.delete');
Route::get('/api/holidays', [CalendarController::class, 'getHolidayJson'])->name('api.holidays');
Route::get('/docs', [PageController::class, 'docs_version'])->name('docs.version_control');
//version control
Route::get('/versions', [VersionControlController::class, 'index'])->name('versions.view');


/*
|--------------------------------------------------------------------------
| Policy Management Routes
|--------------------------------------------------------------------------
| Requires roles: user_s3, developer, users_s1
*/
Route::middleware(['role:user_s3,developer,users_s1,admin'])->group(function () {
Route::get('/policies', [PageController::class, 'policies'])->name('policies.page.upload');
Route::post('/fetch-filename', [PolicyController::class, 'getData'])->name('filename.policies');

// Upload Policy (requires Gate permission)
Route::post('/policy-upload', [PolicyController::class, 'upload_sftp_sql'])
->middleware('can:features.upload_sftp_sql')
->name('upload.policies');

// Update Policy (requires Gate permission)
Route::post('/policy/update', [PolicyController::class, 'update_sftp_sql'])
->middleware('can:features.update_sftp_sql')
->name('policy.update');
Route::post('/pdf/delete', [PDFController::class, 'deleteFile']) ->middleware('can:features.policy.delete')->name('pdf.delete');

    });

/*
|--------------------------------------------------------------------------
| Policy Data View Routes
|--------------------------------------------------------------------------
| Requires roles: user_s3, developer, users_s2, users_s1
*/
Route::middleware(['role:user_s3,developer,users_s2,users_s1,admin'])->group(function () {
Route::post('/policy/data', [PolicyController::class, 'policy_tbl'])->name('policy.data');
    });

/*
|--------------------------------------------------------------------------
| Special Access Management Routes
|--------------------------------------------------------------------------
| Requires roles: user_s3, developer
*/
Route::middleware(['auth', 'role:user_s3,developer'])->group(function () {
Route::get('/special-access', [SpecialAccessController::class, 'index'])->name('special-access.index');
Route::post('/special-access/assign', [SpecialAccessController::class, 'store'])->middleware('can:features.assign')->name('special.access.assign');
Route::post('/special-access/add-feature', [SpecialAccessController::class, 'store_feature_tbl'])->name('special.access.addFeature');
Route::delete('/special-access/remove/{user}/{feature}', [SpecialAccessController::class, 'destroy'])->name('special.access.removeFeature');
Route::delete('/special-access/features/delete/{id}', [SpecialAccessController::class, 'deleteFeature'])
->middleware('can:features.delete')->name('special-access.features.delete');
});

Route::post('/IT_REQUEST/insert', [ITRequestController::class, 'it_request_insert'])->name('it.request.insert');
Route::get('/fields', [RequestFieldController::class, 'getFields'])->name('fields');

//FORM
Route::prefix('Form')->group(function () {
Route::get('/IT', [FormController::class, 'IT_Request_Form'])->name('IT.Request.Form');
Route::get('/Shuttle', [FormController::class, 'Shuttle_Form'])->name('Shuttle_Form');
Route::get('/Deposit', [FormController::class, 'Deposit_Form'])->name('Deposit_Form')->middleware('can:form.deposit');
Route::get('/LunchPass', [FormController::class, 'LunchPass_Form'])->name('LunchPass_Form');

//Form Data


});

Route::prefix('FormData')->group(function () {
//Form Data
Route::get('/IT_REQUEST/view', [ITRequestController::class, 'view_IT_REQUEST_data'])->name('IT.Request.Data.view');
Route::get('/IT_REQUEST/FormData', [ITRequestController::class, 'fetch_all_data'])->name('IT.Request.Form.Data');
Route::get('/IT_REQUEST/Reference_data/{reference_no}',  [ITRequestController::class, 'referenceData'])->name('it_request.reference_data');
Route::post('/IT_REQUEST/Approval/{reference_no}', [ITRequestController::class, 'storeApproval'])
    ->name('it_request.approval.store');
// routes/web.php
Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');

});

// Route::get('/gps', function () {
//     return view('gps');
// });

// Route::get('/reverse-geocode', [GeocodeController::class, 'reverse']);

Route::prefix('activities')->group(function () {
Route::post('/', [ActivitiesController::class, 'store'])->name('activities.store');
Route::get('/create', [ActivitiesController::class, 'showForm'])->name('activities.create_view')->middleware('can:activities.create_view');
Route::get('/statistics', [ActivitiesController::class, 'statistics_view'])->name('activities.statistics_view');
  Route::get('/team-registration', [ActivitiesController::class, 'registration_view'])->name('activities.team_registration');
Route::get('/list', [ActivitiesController::class, 'getActivity'])->name('activities.list');
Route::get('/select-fields', [ActivitiesController::class, 'getFields'])->name('activities.getFields');
Route::get('/form-log', [ActivitiesController::class, 'Form_Logs'])->name('activities.log-form');
Route::get('/listActivity', [FormActivityController::class, 'listActivity'])->name('activities.listActivity');
Route::get('/findPendingLevel/{activity_id}/{user_id}', [FormActivityController::class, 'findPendingLevel'])->name('activities.findPendingLevel');
Route::post('/submission/store', [FormActivityController::class, 'store'])->name('submission.store');
Route::post('/activity-log/update-status', [ActivitiesController::class, 'updateStatus'])
    ->name('activityLog.updateStatus');
});

Route::post('/teams', [TeamsController::class, 'store'])->name('teams.store');
Route::get('/teams', [TeamsController::class, 'index'])->name('teams.index');
Route::get('/teams/activity/approval/{log_id}', [TeamsController::class, 'approval_view'])->name('approvalForm.activity');

Route::get('/teams/activity-log/{log_id}', [TeamsController::class, 'select_log_id'])
    ->name('activityLog.data');
Route::get('/teams/list', [TeamsController::class, 'list'])->name('teams.list');
Route::get('/teams/activity-logs', [TeamsController::class, 'activityLogslist'])
    ->name('teams.activity_logs');
Route::get('/teams/check-name', [TeamsController::class, 'checkName'])->name('teams.checkName');
Route::get('/teams/activitiesList', [TeamsController::class, 'listActivity'])->name('teams.activitiesList');

Route::get('/teams/user-pending-status', [TeamsController::class, 'checkUserPendingStatus'])->name('teams.checkUserPendingStatus');
// routes/web.php
Route::get('/teams/{team}/check-size', [TeamsController::class, 'checkTeamSize'])->name('teams.checkSize');
Route::get('/teams/by-invite/{code}', [TeamsController::class, 'getByInvite'])
     ->where('code', '[A-Za-z0-9\-]+');

});

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ActivitiesController;
use App\Http\Controllers\FormActivityController;
Route::middleware(['auth', 'dashboard.maintenance', 'password.changed'])->group(function () {
Route::prefix('activities')->group(function () {
    Route::post('/', [ActivitiesController::class, 'store'])->name('activities.store');
    Route::delete('/activities/{id}', [ActivitiesController::class, 'destroy'])->name('activities.destroy');
    Route::get('/create', [ActivitiesController::class, 'showForm'])->name('activities.create_view')->middleware('can:activities.create_view');
    Route::get('/statistics', [ActivitiesController::class, 'statistics_view'])->name('activities.statistics_view');
    Route::get('/team-registration', [ActivitiesController::class, 'registration_view'])->name('activities.team_registration');
    Route::get('/list', [ActivitiesController::class, 'getActivity'])->name('activities.list');
    Route::get('/list/AL', [ActivitiesController::class, 'activityList'])->name('activities.view.list')->middleware('can:activities.listview');
    Route::get('/select-fields', [ActivitiesController::class, 'getFields'])->name('activities.getFields');
    Route::get('/form-log', [ActivitiesController::class, 'Form_Logs'])->name('activities.log-form');
    Route::get('/listActivity', [FormActivityController::class, 'listActivity'])->name('activities.listActivity');
    Route::get('/findPendingLevel/{activity_id}/{user_id}', [FormActivityController::class, 'findPendingLevel'])->name('activities.findPendingLevel');
    Route::post('/submission/store', [FormActivityController::class, 'store'])->name('submission.store');
    Route::post('/activity-log/update-status', [ActivitiesController::class, 'updateStatus'])->name('activityLog.updateStatus');
    Route::put('/{id}/update-level', [ActivitiesController::class, 'updateLevel'])->name('activities.updateLevel');
});
});
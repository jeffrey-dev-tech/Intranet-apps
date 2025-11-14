<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FormController;
use App\Http\Controllers\ITRequestController;
use App\Http\Controllers\RequestFieldController;
use App\Http\Controllers\NotificationController;

// Form display
Route::middleware(['auth', 'dashboard.maintenance', 'password.changed'])->group(function () {
Route::prefix('Form')->group(function () {
    Route::get('/FormIT', [FormController::class, 'IT_Request_Form'])->name('IT.RequestForm');
    Route::get('/Shuttle', [FormController::class, 'Shuttle_Form'])->name('Shuttle_Form');
    Route::get('/Deposit', [FormController::class, 'Deposit_Form'])->name('Deposit_Form')->middleware('can:form.deposit');
    Route::get('/LunchPass', [FormController::class, 'LunchPass_Form'])->name('LunchPass_Form');
});

// Form data
Route::prefix('FormData')->group(function () {
    Route::get('/IT_REQUEST/view', [ITRequestController::class, 'view_IT_REQUEST_data'])->name('IT.Request.Data.view');
    Route::get('/IT_REQUEST/FormData', [ITRequestController::class, 'fetch_all_data'])->name('IT.Request.Form.Data');
    Route::get('/IT_REQUEST/Reference_data/{reference_no}',  [ITRequestController::class, 'referenceData'])->name('it_request.reference_data');
    Route::get('/it-request/approval/{reference_no}', [ITRequestController::class, 'approvalForm'])->name('it_request.approvalForm');
    Route::post('/it-request/approval/{reference_no}', [ITRequestController::class, 'updateStatus'])->name('itrequest.updateStatus');
    Route::get('/it-request/download/{filename}', [ITRequestController::class, 'downloadAttachment'])->name('it_request.download');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
});

// IT request insert
Route::post('/IT_REQUEST/insert', [ITRequestController::class, 'it_request_insert'])->name('it.request.insert');
Route::get('/fields', [RequestFieldController::class, 'getFields'])->name('fields');
});
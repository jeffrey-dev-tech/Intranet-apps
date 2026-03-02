<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TravelRequestController;
use App\Http\Controllers\TravelFormFieldController;
// Form display
Route::middleware(['auth', 'dashboard.maintenance', 'password.changed'])->group(function () {

Route::prefix('TravelRequest')->group(function () {

Route::get('/FormTravel', [TravelRequestController::class, 'TravelRequestForm'])->name('travel.RequestForm');
Route::get('/TravelRequestPage', [TravelRequestController::class, 'TravelRequestPage'])->name('travel.TravelRequestPage');
// JSON data route
Route::get('/TravelRequestData', [TravelRequestController::class, 'TravelRequestData'])->name('travel.TravelRequestData');
Route::post('/TravelRequestInsert', [TravelRequestController::class, 'TravelRequestInsert'])->name('travel.Insert');
Route::get('/ApprovalForm/{trf_no}', [TravelRequestController::class, 'approvalForm'])->name('travel.approvalForm');
Route::get('/fields', [TravelFormFieldController::class, 'getFields'])->name('Travelfields');
Route::get('/travel/download/{filename}', [TravelRequestController::class, 'downloadTravelAttachment'])->name('travel.download');
    });

Route::post('/update-status/{trf_no}', [TravelRequestController::class, 'updateStatus'])->name('travel.updateStatus');
});

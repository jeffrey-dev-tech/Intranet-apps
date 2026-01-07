<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PettyCashController;
use App\Http\Controllers\MpdfController;
// Form display
Route::middleware(['auth', 'dashboard.maintenance', 'password.changed'])->group(function () {
Route::post('/download-voucher', [PettyCashController::class, 'download_voucher'])->name('download_voucher');
Route::prefix('PCV')->group(function () {
Route::get('/voucher_form', [PettyCashController::class, 'pettycashform'])->name('voucher_form');
});


});
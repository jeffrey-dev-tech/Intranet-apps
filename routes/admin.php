<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SpecialAccessController;
use App\Http\Controllers\VpnController;
Route::get('/vpn-expired-list', [VpnController::class, 'emailExpiredList'])->name('vpn.expired_list');
Route::middleware(['auth', 'dashboard.maintenance', 'password.changed'])->group(function () {
Route::middleware(['auth', 'role:6,5'])->group(function () {
Route::get('/special-access', [SpecialAccessController::class, 'index'])->name('special-access.index');
Route::get('/maintenance-mode', [SpecialAccessController::class, 'maintenance_form'])->name('maintenance.form');
Route::get('/vpn-accounts', [VpnController::class, 'vpn_page'])->name('vpn.page');
Route::post('/vpn-pass-gen', [VpnController::class, 'generatepass'])->name('vpn.generate');
Route::post('/vpn-send-mail', [VpnController::class, 'vpn_send_mail'])->name('vpn.send_mail');
Route::post('/vpn/store', [VpnController::class, 'store'])->name('vpn.store');
Route::post('/vpn/bulk-delete', [VpnController::class, 'bulkDelete'])->name('vpn.bulk_delete');
Route::post('/special-access/assign', [SpecialAccessController::class, 'store'])->middleware('can:features.assign')->name('special.access.assign');
Route::post('/special-access/add-feature', [SpecialAccessController::class, 'store_feature_tbl'])->name('special.access.addFeature');
Route::delete('/special-access/remove/{user}/{feature}', [SpecialAccessController::class, 'destroy'])->name('special.access.removeFeature');
Route::delete('/special-access/features/delete/{id}', [SpecialAccessController::class, 'deleteFeature'])->middleware('can:features.delete')->name('special-access.features.delete');
});
});
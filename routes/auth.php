<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\AuthController;
 Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/logout', function () { return redirect()->route('login'); });
Route::middleware(['guest', 'prevent-back-history'])->group(function () {
    Route::get('/', [PageController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'processLogin'])->name('login.process');
});
Route::middleware(['auth', 'dashboard.maintenance'])->group(function () {
    Route::get('/change-password-login', [AuthController::class, 'showChangeForm'])->name('password.change');
    Route::post('/change-password-login', [AuthController::class, 'update'])->name('password.custom.update');
   Route::post('/change-password', [AuthController::class, 'changePassword'])->name('password.update'); 
   
});
   
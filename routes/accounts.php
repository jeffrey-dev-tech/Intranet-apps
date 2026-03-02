<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountsController;
use App\Http\Controllers\FormActivityController;
Route::middleware(['auth', 'dashboard.maintenance', 'password.changed'])->group(function () {
Route::prefix('Accounts')->group(function () {

Route::get('/EmployeeList', [AccountsController::class, 'AccountPage'])->name('accounts.page');
Route::patch('/employees/{id}/status', [AccountsController::class, 'updateStatus'])->name('accounts.updateStatus');
Route::patch('/employees/{id}/department', [AccountsController::class, 'updateDepartment'])->name('accounts.updateDepartment');
Route::patch('/employees/{id}/head', [AccountsController::class, 'updateHead'])->name('accounts.updateHead');
Route::patch('/employees/{id}/position', [AccountsController::class, 'updatePosition'])->name('accounts.updatePosition');
Route::patch('/employees/{id}/reset-password', [AccountsController::class, 'resetPassword'])->name('accounts.resetPassword');





});
});
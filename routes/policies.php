<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PolicyController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\PageController;
Route::middleware(['auth', 'dashboard.maintenance', 'password.changed'])->group(function () {
Route::middleware(['role:2,5,6'])->group(function () {
Route::get('/policies', [PageController::class, 'policies'])->name('policies.page.upload');
Route::post('/fetch-filename', [PolicyController::class, 'getData'])->name('filename.policies');
Route::post('/policy/toggle-status', [PolicyController::class, 'toggleStatus'])->name('policy.toggleStatus')->middleware('can:features.toggleStatus');
Route::post('/policy-upload', [PolicyController::class, 'upload_sftp_sql'])->middleware('can:features.upload_sftp_sql')->name('upload.policies');
Route::post('/policy/update', [PolicyController::class, 'update_sftp_sql'])->middleware('can:features.update_sftp_sql')->name('policy.update');
Route::post('/pdf/delete', [PDFController::class, 'deleteFile'])->middleware('can:features.policy.delete')->name('pdf.delete');
});
Route::middleware(['role:1,2,3,4,5,6'])->group(function () {
Route::post('/policy/data', [PolicyController::class, 'policy_tbl'])->name('policy.data');
Route::post('/document/data', [PolicyController::class, 'document_tbl'])->name('document.data');
});
Route::get('/document-category', [PolicyController::class, 'categorytbl'])->name('categorytbl');
Route::get('/document-render/{category_id}/{category_name}', [PolicyController::class, 'document_render'])->name('document.render');
});


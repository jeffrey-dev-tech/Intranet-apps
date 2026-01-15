<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PortalController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\MpdfController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\VersionControlController;
use App\Http\Controllers\SubsystemController;
use App\Http\Controllers\FetchTestController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\VideoController;
Route::middleware(['auth', 'dashboard.maintenance', 'password.changed'])->group(function () {
    
Route::get('/render-cyber-videos', [VideoController::class, 'renderAllCyberSecurityVideos'])
    ->name('render-cyber-videos');
    Route::get('/dashboard', [PageController::class, 'dashboard'])->name('dashboard');
    Route::get('/portal', [PortalController::class, 'index'])->name('portal');
    Route::get('/inventory/data', [InventoryController::class, 'getData'])->name('inventory.data');
    Route::get('/send-gmail/{id}', [MailController::class, 'sendGmail'])->name('send.button');
    
    Route::get('/pdf/view', [PDFController::class, 'view'])->name('pdf.view');
    Route::get('/company-handbook', [PDFController::class, 'handbook'])->name('company.handbook.page');
    
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/logout', function () { return redirect()->route('login'); });

    // PDF & MPDF routes
    Route::get('/mpdf', [MpdfController::class, 'generate']);
    Route::get('/mpdf/sample', [MpdfController::class, 'sample']);
    Route::get('/collection', [MpdfController::class, 'collection']);
    
    // Pages
    Route::get('/scanner', [PageController::class, 'scanner'])->name('scanner');
    Route::get('/dropball', [PageController::class, 'dropball'])->name('dropball');
    
    // Subsystem
    Route::get('/subsystem/{type}', [SubsystemController::class, 'index']);

    // Calendar
    Route::get('/calendar/events', [CalendarController::class, 'events'])->name('calendar.events');
    Route::post('/calendar/store', [CalendarController::class, 'store'])->name('calendar.store');
    Route::get('/calendar/event/{id}', [CalendarController::class, 'show'])->name('calendar.show');
    Route::delete('/calendar/event/{id}', [CalendarController::class, 'destroy'])->name('calendar.destroy')->middleware('can:agenda.delete');
    Route::get('/api/holidays', [CalendarController::class, 'getHolidayJson'])->name('api.holidays');

    // Version control
    Route::get('/docs', [PageController::class, 'docs_version'])->name('docs.version_control');
    Route::get('/versions', [VersionControlController::class, 'index'])->name('versions.view');

    // Fetch test
    Route::get('/test', [FetchTestController::class,'fetch']);
});

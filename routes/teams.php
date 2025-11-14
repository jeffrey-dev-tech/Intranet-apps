<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TeamsController;
Route::middleware(['auth', 'dashboard.maintenance', 'password.changed'])->group(function () {
Route::post('/teams', [TeamsController::class, 'store'])->name('teams.store');
Route::get('/teams', [TeamsController::class, 'index'])->name('teams.index');
Route::get('/teams/activity/approval/{log_id}', [TeamsController::class, 'approval_view'])->name('approvalForm.activity');
Route::get('/teams/activity-log/{log_id}', [TeamsController::class, 'select_log_id'])->name('activityLog.data');
Route::get('/teams/list', [TeamsController::class, 'list'])->name('teams.list');
Route::get('/teams/activity-logs', [TeamsController::class, 'activityLogslist'])->name('teams.activity_logs');
Route::get('/teams/{team}/members', [TeamsController::class, 'members'])->name('teams.members');
Route::get('/teams/check-name', [TeamsController::class, 'checkName'])->name('teams.checkName');
Route::get('/teams/activitiesList', [TeamsController::class, 'listActivity'])->name('teams.activitiesList');
Route::get('/team-ranking', [TeamsController::class, 'teamRanking'])->name('team.ranking');
Route::delete('/teams/{id}', [TeamsController::class, 'destroy'])->name('teams.delete');
Route::get('/teams/user-pending-status', [TeamsController::class, 'checkUserPendingStatus'])->name('teams.checkUserPendingStatus');
Route::get('/teams/{team}/check-size', [TeamsController::class, 'checkTeamSize'])->name('teams.checkSize');
Route::get('/teams/by-invite/{code}', [TeamsController::class, 'getByInvite'])->where('code', '[A-Za-z0-9\-]+');
});
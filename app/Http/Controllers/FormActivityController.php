<?php

namespace App\Http\Controllers;
use App\Models\Team;
use Illuminate\Http\Request;
use App\Models\Submission;
use App\Models\Activity;
use App\Models\TeamUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
class FormActivityController extends Controller
{
    public function listActivity()
    {
        $activities = Activity::all(['id', 'name']); // Only fetch id & name
        return response()->json($activities);
    }


public function findPendingLevel($activity_id, $user_id)
{
    // Find the team with pending status where this user is a member
    $team = Team::where('activity_id', $activity_id)
        ->where('status', 'pending')
        ->whereHas('users', function ($query) use ($user_id) {
            $query->where('user_id', $user_id);
        })
        ->with([
            'level',      // Get the level details
            'activity',   // Get the activity details (for unit)
            'users' => function ($query) use ($user_id) {
                $query->where('user_id', $user_id); // Get pivot for this user
            }
        ])
        ->first(['id', 'name', 'level_id', 'activity_id']);

    if ($team) {
        // ✅ Get progress_value from pivot table (team_user)
        $progressValue = $team->users->first()->pivot->progress_value ?? 0;

        return response()->json([
            'team_id'       => $team->id,
            'team_name'       => $team->name,
            'pending_level'   => $team->level?->id,
            'display_level'   => $team->level?->level_number,
            'progress_value'  => $progressValue,             // From team_user pivot
            'unit'            => $team->activity?->unit,     // From activities table
        ]);
    }

    // No team found
    return response()->json([
        'team_name'       => null,
        'pending_level'   => null,
        'progress_value'  => 0,
        'unit'            => null,
    ]);
}


public function store(Request $request)
{
    try {
        $validated = $request->validate([
            'activity_id' => 'required|exists:activities,id',
            'progress_value' => 'required|numeric',
            'other_informations' => 'required|string',
            'team_id' => 'required|exists:teams,id',
            'level_id' => 'required|integer',
            'department' => 'required|string',
            'email' => 'required|email',
        ]);

        // Create the submission
$prefix = 'LOG';             // text prefix
$date = date('Ymd');         // current date YYYYMMDD

// Generate 4-character alphanumeric string
$randomSuffix = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 4);

$logId = $prefix . '-' . $date . '-' . $randomSuffix;

Submission::create([
    'log_id' => $logId,
    'user_id' => auth()->id(),
    'team_id' => $validated['team_id'],
    'activity_id' => $validated['activity_id'],
    'activity_name' => Activity::find($validated['activity_id'])->name,
    'level_id' => $validated['level_id'],
    'progress_value' => $validated['progress_value'],
    'other_informations' => $validated['other_informations'],
    'department' => $validated['department'],
    'email' => $validated['email'],
]);
        // Update user's progress_value in team_user pivot — add to existing value
        $teamUser = TeamUser::where('team_id', $validated['team_id'])
                            ->where('user_id', auth()->id())
                            ->first();

        if ($teamUser) {
            $teamUser->progress_value += $validated['progress_value']; // add instead of replace
            $teamUser->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Submission has been recorded and progress updated!'
        ]);

    } catch (\Throwable $e) {
        Log::error('Submission error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Server error: ' . $e->getMessage(),
        ], 500);
    }
}




}

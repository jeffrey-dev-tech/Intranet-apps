<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activity;
use App\Models\Team;
use App\Models\ChallengeLevel;
use App\Models\Submission;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
class ActivitiesController extends Controller
{
  
public function showForm()
{
return view('activities.create');
}

    /**
     * Store a new activity and its levels
     */
public function store(Request $request)
{
    // Validate input
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'unit' => 'required|string|max:50',
        'levels.*.level_number' => 'required|integer|min:1',
        'levels.*.required_value' => 'required|numeric|min:1',
        'levels.*.team_size' => 'required|integer|min:1',
    ]);

    // Check duplicate activity name
    $exists = Activity::where('name', $request->name)->exists();
    if ($exists) {
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Activity name already exists!'], 422);
        }
        return redirect()->back()->withErrors(['name' => 'Activity name already exists!'])->withInput();
    }

    // Create activity
    $activity = Activity::create([
        'name' => $request->name,
        'description' => $request->description,
        'unit' => $request->unit,
        'level_count' => count($request->levels),
    ]);

    // Create challenge levels
    foreach ($request->levels as $level) {
        ChallengeLevel::create([
            'activity_id' => $activity->id,
            'activity_name' => $activity->name,
            'level_number' => $level['level_number'],
            'required_value' => $level['required_value'],
            'team_size' => $level['team_size'],
        ]);
    }

    // Return JSON if AJAX
    if ($request->expectsJson()) {
        return response()->json(['message' => 'Activity and levels created successfully!'], 200);
    }

    // Fallback redirect for normal requests
    return redirect()->back()->with('success', 'Activity and levels created successfully!');
}

public function updateLevel(Request $request, $id)
{
    $request->validate([
        'level_active' => 'required|integer',
        'status' => 'required|in:active,inactive',
    ]);

    $activity = Activity::findOrFail($id);
    $activity->level_active = $request->level_active;
    $activity->status = $request->status;
    $activity->save();

    return redirect()->back()->with('success', 'Activity updated successfully!');
}

public function activityList() {
    // Fetch all activities
    $activities = Activity::all(); // SELECT * FROM activities

    // Pass activities to the view
    return view('activities.activityList', compact('activities'));
}



  public function destroy($id)
    {
        // Find the activity by ID
        $activity = Activity::find($id);

        if (!$activity) {
            return redirect()->back()->with('error', 'Activity not found.');
        }

        // Delete the activity
        $activity->delete();

        return redirect()->back()->with('success', 'Activity deleted successfully.');
    }
   public function registration_view(){
         // Define registration period
    $registrationStart = \Carbon\Carbon::parse('2025-10-01');
    $registrationEnd = \Carbon\Carbon::parse('2026-01-10');

    return view('activities.teams_registration', [
        'registrationStart' => $registrationStart,
        'registrationEnd' => $registrationEnd,
        'today' => now(),
    ]);
    }

     public function statistics_view(){
        return view('activities.statistics');
    }

public function getActivity()
{
    // Get all activities with their challenge levels
    $allActivities = Activity::with('challengeLevels')->get();

    $activityOptions = '<option selected disabled>Choose Activity</option>';
    $levelsOptions = [];
    $progressInfo = [];

    foreach ($allActivities as $activity) {
        // Build activity dropdown
        $activityOptions .= '<option value="' . $activity->id . '">' . $activity->name . '</option>';

        // Build levels dropdown (only up to level_active)
        $levelHtml = '<option selected disabled>Choose Level</option>';
        foreach ($activity->challengeLevels as $level) {
            if ($level->level_number <= $activity->level_active) {
                $levelHtml .= '<option value="' . $level->id . '">' . $level->level_number . '</option>';
                // if you have a "name" column: $level->name
            }
        }

        // Store level options for this activity
        $levelsOptions[$activity->id] = $levelHtml;

        // Optional progress info
        $progressInfo[$activity->id] = [
            'unlockedLevelsCount' => $activity->level_active,
            'activeLevels' => $activity->challengeLevels
                                ->where('level_number', '<=', $activity->level_active)
                                ->pluck('level_number')
                                ->toArray()
        ];
    }

    return response()->json([
        'html' => $activityOptions,
        'levelsHtml' => $levelsOptions,
        'progressInfo' => $progressInfo
    ]);
}



public function updateStatus(Request $request)
{
    $request->validate([
        'log_id' => 'required|exists:submissions,log_id',
        'status' => 'required|in:approved,disapproved',
    ]);

    DB::beginTransaction();

    try {
        Log::info('Starting updateStatus process', [
            'log_id' => $request->log_id,
            'status' => $request->status
        ]);

        $log = Submission::with('team.users', 'level')
            ->where('log_id', $request->log_id)
            ->firstOrFail();

        // Preserve original progress_value (before overwriting it)
        $originalProgress = $log->getOriginal('progress_value');

        Log::info('Fetched submission', [
            'log_id' => $log->log_id,
            'user_id' => $log->user_id,
            'team_id' => $log->team_id,
            'activity_id' => $log->activity_id,
            'level_id' => $log->level_id,
            'progress_value_original' => $originalProgress,
            'status' => $log->status
        ]);

        if ($request->status === 'approved') {
            // Team size
            $teamSize = $log->team?->users->count() ?? 1;

            // Required progress per user
            $perMemberRequired = ($log->level?->required_value ?? 0) / max($teamSize, 1);
            Log::info('Per-member required value', [
                'level_id' => $log->level_id,
                'required_value' => $log->level?->required_value,
                'team_size' => $teamSize,
                'per_member_required' => $perMemberRequired
            ]);

            // Sum all approved submissions for this user (exclude current)
            $currentApprovedSum = Submission::where('user_id', $log->user_id)
                ->where('team_id', $log->team_id)
                ->where('activity_id', $log->activity_id)
                ->where('level_id', $log->level_id)
                ->where('status', 'approved')
                ->where('id', '!=', $log->id)
                ->sum('progress_value');

            Log::info('Current approved sum (excluding this submission)', [
                'current_approved_sum' => $currentApprovedSum
            ]);

            // Remaining allowed progress
            $remaining = max($perMemberRequired - $currentApprovedSum, 0);
            Log::info('Remaining allowed progress', ['remaining' => $remaining]);

            // ✅ Use original value to calculate allowed & overflow
            $allowedProgress = min($originalProgress, $remaining);
            $overflow = max($originalProgress - $allowedProgress, 0);

            $log->progress_value = $allowedProgress;
            $log->progress_value_exceed = $overflow;

            Log::info('Calculated overflow', [
                'original_progress' => $originalProgress,
                'allowed_progress' => $allowedProgress,
                'overflow' => $overflow
            ]);

            // ======== Check if team is completed ========
            $existingTeamProgress = Submission::where('team_id', $log->team_id)
                ->where('activity_id', $log->activity_id)
                ->where('level_id', $log->level_id)
                ->where('status', 'approved')
                ->where('id', '!=', $log->id)
                ->sum('progress_value');

            $totalTeamProgress = $existingTeamProgress + $log->progress_value;
            $totalRequired = $log->level?->required_value ?? 0;

            Log::info('Total team progress including current submission', [
                'team_id' => $log->team_id,
                'existingTeamProgress' => $existingTeamProgress,
                'currentProgress' => $log->progress_value,
                'totalTeamProgress' => $totalTeamProgress,
                'required_value' => $totalRequired
            ]);

            if ($totalTeamProgress >= $totalRequired && $log->team && $log->team->status !== 'completed') {
                $log->team->status = 'completed';
                $log->team->save();

                Log::info('Team marked as completed', [
                    'team_id' => $log->team->id,
                    'status' => $log->team->status
                ]);
            }
        } else {
              $log->progress_value = $originalProgress;

    // Optionally, keep overflow as is or reset to 0
    $log->progress_value_exceed = 0; // or max($originalProgress - allowed, 0)
        }

        // Update submission status
        $log->status = $request->status;
        $log->save();

        Log::info('Submission successfully updated', [
            'log_id' => $log->log_id,
            'user_id' => $log->user_id,
            'progress_value' => $log->progress_value,
            'overflow' => $log->progress_value_exceed,
            'status' => $log->status
        ]);

        DB::commit();
        return response()->json(['success' => true]);

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Update Status Failed', [
            'log_id' => $request->log_id,
            'status' => $request->status,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Failed to update status',
            'error' => $e->getMessage()
        ], 500);
    }
}



public function Form_Logs()
{

return view('activities.form_logs');
}



public function getFields(Request $request)
{
    $type = $request->query('type');
    $html = '';

    switch ($type) {
        case 'leader':
            $html = '
            <div id="leaderFields">
                <div class="mb-3">
                    <label class="form-label">Team Name:</label>
                    <input type="text" class="form-control" name="team_name" placeholder="Enter team name">
                </div>
                <div class="mb-3">
                    <label class="form-label">Activity:</label>
                    <select name="activity_id" id="activity_id"></select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Level:</label>
                    <select name="level_id" id="level_id"></select>
                </div>
            </div>';
            break;

        case 'member':
            $html = '
            <div id="memberFields">
                <div class="mb-3">
                    <label class="form-label">Invite Code:</label>
                    <input type="text" class="form-control" name="invite_code" placeholder="Enter invite code">
                </div>
            </div>';
            break;

        default:
            $html = '';
    }

    return response()->json(['html' => $html]);
}



}

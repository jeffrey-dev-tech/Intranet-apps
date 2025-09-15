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



     public function registration_view(){
        return view('activities.teams_registration');
    }

     public function statistics_view(){
        return view('activities.statistics');
    }

public function getActivity()
{
    // Get all activities with their levels
    $allActivities = Activity::with('levels')->get();

    // Get all teams in one query, grouped by activity_id
    $allTeams = Team::all()->groupBy('activity_id');

    $activityOptions = '<option selected disabled>Choose Activity</option>';
    $levelsOptions = [];
    $progressInfo = []; // <-- Extra info per activity

    foreach ($allActivities as $activity) {
        $activityOptions .= '<option value="' . $activity->id . '">' . $activity->name . '</option>';

        $levelHtml = '<option selected disabled>Choose Level</option>';
        $levels = $activity->levels->sortBy('level_number');
        $teams = $allTeams->get($activity->id, collect());

        $unlockedCount = 0;
        $pendingLevel = null;
        $unlockNext = true;

        // If no teams exist yet for this activity, only show Level 1
        if ($teams->isEmpty()) {
            $firstLevel = $levels->first();
            if ($firstLevel) {
                $levelHtml .= '<option value="' . $firstLevel->id . '">' . $firstLevel->level_number . '</option>';
                $unlockedCount = 1;
            }
            $levelsOptions[$activity->id] = $levelHtml;
            $progressInfo[$activity->id] = [
                'unlockedLevelsCount' => $unlockedCount,
                'pendingLevel' => $pendingLevel
            ];
            continue;
        }

        // Unlock levels based on pending/completed status
        foreach ($levels as $level) {
            $pending = $teams->where('level_id', $level->id)
                             ->where('status', 'pending')
                             ->isNotEmpty();

            if (!$unlockNext) break;

            $levelHtml .= '<option value="' . $level->id . '">' . $level->level_number . '</option>';
            $unlockedCount++;

            if ($pending) {
                $pendingLevel = $level->level_number;
                $unlockNext = false;
            }
        }

        $levelsOptions[$activity->id] = $levelHtml;
        $progressInfo[$activity->id] = [
            'unlockedLevelsCount' => $unlockedCount,
            'pendingLevel' => $pendingLevel
        ];
    }

    return response()->json([
        'html' => $activityOptions,
        'levelsHtml' => $levelsOptions,
        'progressInfo' => $progressInfo // <-- NEW field
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
        $log = Submission::where('log_id', $request->log_id)->firstOrFail();
        $log->status = $request->status;
        $log->save();

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

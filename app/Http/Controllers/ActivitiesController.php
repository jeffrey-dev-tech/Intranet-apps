<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activity;
use App\Models\Team;
use App\Models\ChallengeLevel;
use App\Models\Submission;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
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
        'submitted_value' => 'required|numeric|min:0', // the new submitted value
    ]);

    DB::beginTransaction();

    try {
        // Fetch submission with related team & level
        $log = Submission::with('team.users', 'level')
            ->where('log_id', $request->log_id)
            ->firstOrFail();

        $submittedValue = $request->input('submitted_value');

        if ($request->status === 'approved') {

            // ================= Per-member quota =================
            $teamSize = ($log->submission_type === 'party')
                ? ($log->team?->users->count() ?: 1)
                : 1;

            $perMemberRequired = ($log->level?->required_value ?? 0) / max($teamSize, 1);

            // ================= Total previously approved progress for this user =================
            $totalApprovedForUser = Submission::where('activity_id', $log->activity_id)
                ->where('level_id', $log->level_id)
                ->where('status', 'approved')
                ->where('user_id', $log->user_id)
                ->sum('progress_value');

            // ================= Determine allowed progress and overflow =================
            if ($totalApprovedForUser >= $perMemberRequired) {
                // User already met quota → all new submission goes to overflow
                $allowedProgress = 0;
                $overflow = $submittedValue;
            } else {
                $remaining = $perMemberRequired - $totalApprovedForUser;
                $allowedProgress = 0; // always put into overflow if already met
                $overflow = $submittedValue; 
            }

            // ================= Save submission =================
            $log->progress_value = $allowedProgress;          
            $log->progress_value_exceed += $overflow;        
            $log->status = 'approved';
            $log->save();

            // ================= Team Completion Logic =================
            if ($log->team) {
                $teamMemberCount = $log->team->users()->count();

                // Count distinct members who have at least one approved party submission
                $partySubmitterCount = Submission::where('team_id', $log->team_id)
                    ->where('activity_id', $log->activity_id)
                    ->where('level_id', $log->level_id)
                    ->where('status', 'approved')
                    ->where('submission_type', 'party')
                    ->distinct('user_id')
                    ->count('user_id');

                // Team completes only if all members submitted at least once party
                if ($partySubmitterCount >= $teamMemberCount && $log->team->status !== 'completed') {
                    $log->team->timestamps = false;       // prevent automatic updated_at change
                    $log->team->status = 'completed';
                    $log->team->updated_at = $log->created_at; // use submission created_at
                    $log->team->save();
                    $log->team->timestamps = true;
                }
            }

        } else {
            // ================= Disapproved Submission =================
            $log->progress_value = 0;                  // reset progress
            $log->progress_value_exceed = 0;           // reset overflow
            $log->status = 'disapproved';
            $log->save();
        }

        DB::commit();

        return response()->json(['success' => true]);

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Update Status Failed', [
            'request_data' => $request->all(),
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

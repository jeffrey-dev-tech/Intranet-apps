<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Team;
use App\Models\TeamUser;
use App\Models\Activity;
use App\Models\Submission;
use Illuminate\Support\Str;
use App\Models\ChallengeLevel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
class TeamsController extends Controller
{
    /**
     * Show all teams (optional dashboard page)
     */


public function index()
{  
    return view('teams.index');
}




public function approval_view($log_id)
{
    // Fetch any data you need (optional)
    $submissions = Submission::with(['activity', 'team', 'user', 'level'])
        ->where('log_id', $log_id)
        ->get();

    return view('approval_form.activity_form_approval', [
        'log_id' => $log_id,
        'submissions' => $submissions,
    ]);
}

public function select_log_id(Request $request, $log_id)
{
    // Get submissions for this log_id
    $submissions = Submission::with(['activity', 'team', 'user', 'level'])
        ->where('log_id', $log_id)
        ->get();

    // Transform the response
    $data = $submissions->map(function ($submission) {
        return [
            'log_id' => $submission->log_id,
            'activity_name' => $submission->activity?->name,
            'level_number' => $submission->level?->level_number,
            'progress_value' => $submission->progress_value,
            'status' => $submission->status,
            'team_name' => $submission->team?->name,
            'user_name' => $submission->user?->name,
            'activity' => $submission->activity,
            'unit' => $submission->activity?->unit,
            'other_informations' => $submission->other_informations,
        ];
    });

    return response()->json([
        'activity_log' => $data
    ]);
}

 public function listActivity()
{
    $activities = Activity::select('id', 'name')->get();
    return response()->json($activities->values()); // Forces a JSON array
}

    /**
     * Handle team registration
     */
public function store(Request $request)
{
    $request->validate([
        'role_type' => 'required|in:leader,member',
        'department' => 'required|string',
        'email' => 'required|email',
    ]);

    $user = auth()->user();

    try {
        DB::beginTransaction();

        // ===== LEADER FLOW =====
        if ($request->role_type === 'leader') {
            $request->validate([
                'activity_id' => 'required|exists:activities,id',
                'level_id' => 'required|exists:challenge_levels,id',
                'team_name' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('teams', 'name')->where(function ($query) use ($request) {
                        return $query->where('activity_id', $request->activity_id);
                    }),
                ],
            ], [
                'team_name.unique' => 'A team with this name already exists in the selected activity.',
            ]);

            $activity = Activity::findOrFail($request->activity_id);
            $level = ChallengeLevel::findOrFail($request->level_id);

            $inviteCode = 'A' . $activity->id
                        . '-L' . $level->level_number
                        . '-' . $level->team_size
                        . '-' . strtoupper(Str::random(4));

            $team = Team::create([
                'name' => $request->team_name,
                'activity_id' => $activity->id,
                'activity_name' => $activity->name,
                'level_id' => $level->id,
                'captain_id' => $user->id,
                'invite_code' => $inviteCode,
                'status' => 'pending',
            ]);

            $team->users()->syncWithoutDetaching([
                $user->id => [
                    'role' => 'captain',
                    'progress_value' => 0,
                    'joined_at' => now(),
                ]
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Team created successfully! Invitation code generated.',
                'invite_code' => $inviteCode,
                'redirect' => route('teams.index'),
            ], 201);
        }

        // ===== MEMBER FLOW =====
        if ($request->role_type === 'member') {
            $request->validate([
                'invite_code' => 'required|string|exists:teams,invite_code',
            ]);

            $team = Team::where('invite_code', $request->invite_code)->firstOrFail();

            // Check if user already in a team for this activity
            $existingTeam = $user->teams()->where('activity_id', $team->activity_id)->first();
            if ($existingTeam) {
                throw new \Exception("You are already a member of team '{$existingTeam->name}' for this activity.");
            }

            // Attach user to team
            $team->users()->attach($user->id, [
                'role' => 'member',
                'progress_value' => 0,
                'joined_at' => now(),
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Joined team successfully!',
                'redirect' => route('teams.index'),
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Invalid registration request.'
        ], 422);

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Transaction failed: ' . $e->getMessage());

        return response()->json([
            'status' => 'error',
            'message' => 'Registration failed. ' . $e->getMessage()
        ], 500);
    }
}



public function getByInvite($code)
{
    $team = Team::where('invite_code', $code)->first();

    if (!$team) {
        return response()->json([
            'error' => 'Invalid invitation code'
        ], 404);
    }

    return response()->json([
        'id' => $team->id,
        'name' => $team->name,
        'level_id' => $team->level_id,
        'activity_id' => $team->activity_id,
        'invite_code' => $team->invite_code,
        'team_size' => optional($team->level)->team_size ?? null,
        'current_members' => $team->users()->count()
    ]);
}
public function list()
{
    $userId = auth()->id(); // logged-in user ID

    if ($userId == 69 || $userId == 119) {
        // Special users: fetch all teams
        $teams = Team::withCount('users')->get();
    } else {
        // All other users: fetch only pending teams they belong to
        $teams = Team::whereRaw("LOWER(TRIM(status)) = ?", ['pending'])
                     ->whereHas('users', function ($query) use ($userId) {
                         $query->where('user_id', $userId);
                     })
                     ->withCount('users')
                     ->get();
    }

    return response()->json([
        'teams' => $teams
    ]);
}


public function activityLogsList()
{
    $userId = auth()->user()->id;

    // Determine which submissions to fetch
    if (in_array($userId, [69, 119])) {
        // Admins: get all submissions
        $submissions = Submission::with(['activity', 'team', 'user', 'level'])->get();
    } else {
        // Regular users: get only their submissions
        $submissions = Submission::with(['activity', 'team', 'user', 'level'])
            ->where('user_id', $userId)
            ->get();
    }

    // Transform data
    $data = $submissions->map(function ($submission) use ($userId) {
        return [
            'log_id' => $submission->log_id,
            'activity_name' => $submission->activity_name,
            'level_number' => $submission->level?->level_number,
            'progress_value' => $submission->progress_value,
            'status' => $submission->status,
            'team_name' => $submission->team?->name,
            'user_name' => $submission->user?->name,
            'activity' => $submission->activity,
            'unit' => $submission->activity?->unit,
            'can_approve' => in_array($userId, [69, 119]) && $submission->status === 'pending',
        ];
    });

    return response()->json([
        'activity_log' => $data
    ]);
}





public function checkUserPendingStatus()
{
    $userId = auth()->id();

    // Check if user is already in a team with "pending" status
    $pending = TeamUser::where('user_id', $userId)
        ->whereHas('team', function ($query) {
            $query->where('status', 'pending');
        })
        ->with('team') // load team for name
        ->first();

    if ($pending) {
        return response()->json([
            'hasPending' => true,
            'team_name' => $pending->team->name,
            'progress_value' => $pending->progress_value,
        ]);
    }

    return response()->json([
        'hasPending' => false,
    ]);
}
public function checkTeamSize($teamId)
{
    $team = Team::withCount('users')->findOrFail($teamId);
    $level = ChallengeLevel::find($team->level_id);

    if (!$level) {
        return response()->json(['status' => 'error', 'message' => 'Level not found.'], 404);
    }

    $isFull = $team->users_count >= $level->team_size;

    return response()->json([
        'status' => 'success',
        'is_full' => $isFull,
        'team_size' => $level->team_size,
        'current_members' => $team->users_count
    ]);
}

public function checkName(Request $request)
{
    $request->validate([
        'team_name' => 'required|string',
        'activity_id' => 'required|integer|exists:activities,id',
    ]);

    $exists = Team::where('name', $request->team_name)
                  ->where('activity_id', $request->activity_id)
                  ->exists();

    return response()->json(['exists' => $exists]);
}


}

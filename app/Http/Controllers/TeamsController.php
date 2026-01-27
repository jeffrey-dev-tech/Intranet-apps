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
use App\Services\ActivitiesMailService;
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
              'submission_type' => $submission->submission_type,
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
        // 'email' => 'required|email',
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
                        return $query->where('activity_id', $request->activity_id)
                                     ->where('level_id', $request->level_id);
                    }),
                ],
            ], [
                'team_name.unique' => 'A team with this name already exists in the selected activity and level.',
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

            // ===== Handle Attachments =====
            $attachments = [];

            // ===== Prepare Email =====
$to = [$user->getEmailForMailing()];
            $cc = []; // Example: cc department head email
            $bcc = [];
            $subject = "Team Registration: {$team->name} ({$activity->name})";

            $logoPath = public_path('img/sanden-logo-white.png');
            $logoDataUri = 'data:' . mime_content_type($logoPath) . ';base64,' . base64_encode(file_get_contents($logoPath));

         $body = view('emails.team_mail', [
    'logoDataUri'     => $logoDataUri,
    'team_name'       => $team->name,
    'activity_name'   => $activity->name,
    'captain_name'   =>$user->name,
    'activity_level'  => $level->level_number, // or $level->name if you prefer
    'invitation_code' => $inviteCode,
])->render();
            // ===== Send Email via Controller =====
        $mailService = new ActivitiesMailService();
        $mailService->registration_mail(
            $to,
            $cc,
            $bcc,
            $subject,
            $body,
            $attachments
        );
            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Team created successfully! Invitation code generated and mail sent.',
                'invite_code' => $inviteCode,
                'redirect' => route('teams.index'),
            ], 201);
        }

        // ===== MEMBER FLOW =====
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

    // ✅ Get team size limit from challenge_levels
    $level = ChallengeLevel::findOrFail($team->level_id);
    $currentMembersCount = $team->users()->count();

    if ($currentMembersCount >= $level->team_size) {
        throw new \Exception("This team is already full. Maximum allowed members: {$level->team_size}.");
    }

    // Attach user as member
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
public function members($teamId)
{
    // Log::info("Fetching members for team ID: {$teamId}");

    try {
        // Find the team and its active level_id
        $team = Team::with(['activity', 'level'])
            ->findOrFail($teamId);

        $teamLevelId = $team->level?->id; // or $team->level_id
        // Log::info("Resolved team level ID", ['team_id' => $teamId, 'level_id' => $teamLevelId]);

        // Load users with submission sums & counts filtered by level_id
        $team->load(['users' => function ($query) use ($teamLevelId) {
            $query->withSum(['submissions as approved_progress_sum' => function ($q) use ($teamLevelId) {
                $q->where('status', 'approved');
                if ($teamLevelId) {
                    $q->where('level_id', $teamLevelId);
                }
            }], 'progress_value')
            ->withSum(['submissions as approved_progress_exceed_sum' => function ($q) use ($teamLevelId) {
                $q->where('status', 'approved');
                if ($teamLevelId) {
                    $q->where('level_id', $teamLevelId);
                }
            }], 'progress_value_exceed')
            ->withCount(['submissions as approved_submissions_count' => function ($q) use ($teamLevelId) {
                $q->where('status', 'approved');
                if ($teamLevelId) {
                    $q->where('level_id', $teamLevelId);
                }
            }]);
        }]);

        // Team-level calculations
        $teamSize = $team->level?->team_size ?? max($team->users->count(), 1);
        $perMemberRequired = ($team->level?->required_value ?? 0) / $teamSize;

        // Map members including DB-based exceed column
        $members = $team->users->map(function ($user) use ($perMemberRequired, $teamId, $teamLevelId) {
            $approved = (float) ($user->approved_progress_sum ?? 0.00);
            $approvedExceed = (float) ($user->approved_progress_exceed_sum ?? 0.00);

            // 🔎 Log all raw submissions for this user (to debug over-exceed)
            $rawSubmissions = DB::table('submissions')
                ->where('team_id', $teamId)
                ->where('user_id', $user->id)
                ->where('status', 'approved')
                ->when($teamLevelId, function ($q) use ($teamLevelId) {
                    $q->where('level_id', $teamLevelId);
                })
                ->pluck('progress_value_exceed');

            // Log::info("Member progress debug", [
            //     'team_id' => $teamId,
            //     'user_id' => $user->id,
            //     'approved_progress_sum' => $approved,
            //     'progress_value_exceed_sum' => $approvedExceed,
            //     'raw_progress_value_exceed' => $rawSubmissions, // 👈 all DB values
            //     'per_member_required' => $perMemberRequired,
            //     'approved_submissions_count' => $user->approved_submissions_count ?? 0,
            // ]);

            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'approved_progress_sum' => $approved,
                'approved_submissions_count' => $user->approved_submissions_count ?? 0,
                'progress_value_exceed' => $approvedExceed,
                'role' => $user->pivot?->role ?? null,
                'joined_at' => $user->pivot?->joined_at ?? null,
            ];
        });

        $totalApprovedProgress = $members->sum('approved_progress_sum');
        $teamRequiredValue = $team->level?->required_value ?? 0;

        $percentageOfRequired = $teamRequiredValue > 0
            ? ($totalApprovedProgress / $teamRequiredValue) * 100
            : 0;

        // Log::info("Team summary", [
        //     'team_id' => $teamId,
        //     'total_approved_progress' => $totalApprovedProgress,
        //     'team_required_value' => $teamRequiredValue,
        //     'percentage_of_required' => $percentageOfRequired,
        //     'members_count' => $members->count(),
        // ]);

        // Check if no submissions yet
        $hasSubmissions = $members->sum('approved_submissions_count') > 0;

        return response()->json([
            'team' => [
                'id' => $team->id,
                'name' => $team->name,
                'status' => $team->status ?? 'N/A',
                'activity_name' => $team->activity?->name ?? 'N/A',
                'per_member_required' => $perMemberRequired,
                'total_approved_progress' => $totalApprovedProgress,
                'percentage_of_required' => round($percentageOfRequired, 2),
                'message' => $hasSubmissions
                    ? null
                    : 'No submissions yet on this level',
            ],
            'members' => $members,
            'unit' => $team->activity?->unit ?? 'N/A',
        ]);
    } catch (\Exception $e) {
        Log::error("Failed to fetch members", [
            'team_id' => $teamId,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return response()->json([
            'error' => 'Failed to fetch members'
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



   public function teamRanking()
    {
        return response()->json(Submission::teamProgress());
    }

public function list()
{
    $userId = auth()->id(); // logged-in user ID

    if ($userId == 63 || $userId == 100 || $userId == 34 || $userId == 18) {
        // Special users: fetch all teams (all statuses)
        $teams = Team::withCount('users')->get();
    } else {
        // All other users: fetch teams they belong to (all statuses)
        $teams = Team::whereHas('users', function ($query) use ($userId) {
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

    // Base query: admins see all, users see only their submissions
    $submissionsQuery = Submission::with(['activity', 'team', 'user', 'level']);

    if (!in_array($userId, [63, 100])) {
        $submissionsQuery->where('user_id', $userId);
    }

    // Sort by creation date so admin sees earliest submissions first
    $submissions = $submissionsQuery->orderBy('created_at', 'asc')->get();

    $data = $submissions->map(function ($submission) use ($userId) {

        // Determine if admin can approve
        $canApprove = false;
        if (in_array($userId, [69, 119]) && $submission->status === 'pending') {
            // Check earliest pending submission for this user/team/level
            $earliestPending = Submission::where('activity_id', $submission->activity_id)
                ->where('level_id', $submission->level_id)
                ->where('user_id', $submission->user_id)
                ->where('status', 'pending')
                ->orderBy('created_at', 'asc')
                ->first();

            if ($earliestPending && $earliestPending->id === $submission->id) {
                $canApprove = true;
            }
        }

        return [
            'log_id' => $submission->log_id,
            'activity_name' => $submission->activity_name,
            'level_number' => $submission->level?->level_number,
            'progress_value' => $submission->progress_value,
            'submission_type' => $submission->submission_type,
            'status' => $submission->status,
            'team_name' => $submission->team?->name,
            'user_name' => $submission->user?->name,
            'activity' => $submission->activity,
            'unit' => $submission->activity?->unit,
            'created_at' => $submission->created_at, // show submission date
            'can_approve' => $canApprove,
        ];
    });

    return response()->json([
        'activity_log' => $data
    ]);
}



public function checkUserPendingStatus()
{
    $userId = auth()->id();
    $currentYear = now()->year; // ✅ today's year

    // Step 1: Get all team_ids where status = 'pending' AND the activity is active AND team created this year
    $pendingTeamIds = Team::where('status', 'pending')
        // ->whereYear('created_at', $currentYear) // ✅ filter by current year
        ->whereHas('activity', function ($query) {
            $query->where('status', 'active'); // only active activities
        })
        ->pluck('id');

    if ($pendingTeamIds->isEmpty()) {
        return response()->json([
            'hasPending' => false,
            'pending_teams' => [],
        ]);
    }

    // Step 2: Find if the user is part of any of those pending teams
    $userPendingTeams = Team::whereIn('id', $pendingTeamIds)
        ->whereHas('users', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })
        ->with(['level', 'users', 'activity']) // include activity info if needed
        ->get();

    if ($userPendingTeams->isEmpty()) {
        return response()->json([
            'hasPending' => false,
            'pending_teams' => [],
        ]);
    }

    // Step 3: Build response for each pending team
    $teamsData = $userPendingTeams->map(function ($team) use ($userId) {
        $teamSize = $team->users->count() ?? 1;
        $requiredValue = $team->level?->required_value ?? 0;

        $totalApproved = Submission::where('team_id', $team->id)
            ->where('status', 'approved')
            ->sum('progress_value');

        $userApproved = Submission::where('team_id', $team->id)
            ->where('user_id', $userId)
            ->where('status', 'approved')
            ->sum('progress_value');

        $perMemberRequired = $requiredValue / max($teamSize, 1);

        $teamPercentage = $requiredValue > 0
            ? min(100, ($totalApproved / $requiredValue) * 100)
            : 0;

        $userPercentage = $perMemberRequired > 0
            ? min(100, ($userApproved / $perMemberRequired) * 100)
            : 0;

        return [
            'team_id' => $team->id,
            'team_name' => $team->name,
            'activity_id' => $team->activity->id,
            'activity_name' => $team->activity->name,
            'team_size' => $teamSize,
            'required_value' => $requiredValue,
            'per_member_required' => $perMemberRequired,
            'team_total_approved' => $totalApproved,
            'team_percentage' => round($teamPercentage, 2),
            'user_total_approved' => $userApproved,
            'user_percentage' => round($userPercentage, 2),
            'year' => $team->created_at->year, // ✅ extra info
        ];
    });

    // 🚨 Block registration if user belongs to one or more pending teams in active activities this year
    return response()->json([
        'error' => 'You cannot register while you are already in one or more pending teams of active activities this year.',
        'hasPending' => true,
        'pending_teams' => $teamsData,
    ], 403);
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

public function destroy($id)
{
    $team = Team::find($id);
    if (!$team) {
        return response()->json(['message' => 'Team not found'], 404);
    }

    $team->delete();
    return response()->json(['message' => 'Team deleted successfully']);
}


}

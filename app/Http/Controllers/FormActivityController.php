<?php

namespace App\Http\Controllers;
use App\Models\Team;
use Illuminate\Http\Request;
use App\Models\Submission;
use App\Models\Activity;
use App\Models\ChallengeLevel;
use App\Models\TeamUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Services\ActivitiesMailService;
class FormActivityController extends Controller
{
    public function listActivity()
    {
        $activities = Activity::all(['id', 'name']); // Only fetch id & name
        return response()->json($activities);
    }
public function findPendingLevel($activity_id, $user_id)
{
    $activity = Activity::findOrFail($activity_id);
    $levelActive = $activity->level_active ?? 0; // Admin-set active level

    // 1️⃣ Check if user has a pending team
    $pendingTeam = Team::where('teams.activity_id', $activity_id)
        ->where('teams.status', 'pending')
        ->whereHas('users', fn($q) => $q->where('users.id', $user_id))
        ->with(['level', 'activity'])
        ->first();

    if ($pendingTeam) {
        $totalProgress = Submission::where('submissions.user_id', $user_id)
            ->where('submissions.team_id', $pendingTeam->id)
            ->where('submissions.activity_id', $activity_id)
            ->where('submissions.level_id', $pendingTeam->level_id)
            ->where('submissions.status', 'approved')
            ->sum('progress_value');

        $teamSize = $pendingTeam->level?->team_size ?: 1;
        $perMemberRequired = ($pendingTeam->level?->required_value ?? 0) / max($teamSize, 1);

        return response()->json([
            'has_pending_level'   => true,
            'team_id'             => $pendingTeam->id,
            'team_name'           => $pendingTeam->name,
            'pending_level'       => $pendingTeam->level?->id,
            'display_level'       => $pendingTeam->level?->level_number,
            'progress_value'      => null,
            'per_member_required' => $perMemberRequired,
            'unit'                => $pendingTeam->activity?->unit,
            'last_team_id'        => $pendingTeam->id,
            'last_progress_value' => $totalProgress,
            'level' => [
                'id' => $pendingTeam->level?->id,
                'level_number' => $pendingTeam->level?->level_number,
                'required_value' => $pendingTeam->level?->required_value,
            ],
        ]);
    }

    // 2️⃣ No pending team → get all user teams for this activity
    $userTeams = Team::where('teams.activity_id', $activity_id)
        ->whereHas('users', fn($q) => $q->where('users.id', $user_id))
        ->get(['id', 'name']);

    $activityUnit = $activity->unit;

    // Map last progress for each team
    $teams = $userTeams->map(fn($team) => [
        'id' => $team->id,
        'name' => $team->name,
        'last_progress_value' => $team->submissions()
            ->where('submissions.user_id', $user_id)
            ->where('submissions.status', 'approved')
            ->sum('progress_value'),
    ]);

    // 3️⃣ Get last approved submission
    $lastCompletedSubmission = Submission::where('submissions.user_id', $user_id)
        ->where('submissions.activity_id', $activity_id)
        ->where('submissions.status', 'approved')
        ->orderByDesc('submissions.created_at')
        ->first();

    $lastLevelNumber = $lastCompletedSubmission?->level?->level_number ?? 0;

    // 4️⃣ Collect levels **up to admin active level**
    $levels = ChallengeLevel::where('activity_id', $activity_id)
        ->where('level_number', '<=', $levelActive)
        ->orderBy('level_number')
        ->get(['id', 'level_number', 'required_value']);

    // Determine next level to preselect
    $nextLevel = $levels->firstWhere('level_number', $lastLevelNumber + 1) ?? $levels->last();

    return response()->json([
        'has_pending_level'   => false,
        'activity_id'         => $activity_id,
        'teams'               => $teams,
        'unit'                => $activityUnit,
        'last_team_id'        => $lastCompletedSubmission?->team_id ?? null,
        'last_progress_value' => $teams->sum('last_progress_value'),
        'level'               => $nextLevel
            ? [
                'id' => $nextLevel->id,
                'level_number' => $nextLevel->level_number,
                'required_value' => $nextLevel->required_value,
            ]
            : null,
        'levels'              => $levels->map(fn($l) => [
            'id' => $l->id,
            'level_number' => $l->level_number,
            'required_value' => $l->required_value,
        ]),
        'last_level_number' => $lastLevelNumber,
        'level_active'      => $levelActive, // send to frontend for reference
    ]);
}


public function store(Request $request)
{
    DB::beginTransaction();

    try {
        // --- Validate request ---
        $validated = $request->validate([
            'activity_id'        => 'required|exists:activities,id',
            'level_id'           => 'required|exists:challenge_levels,id',
            'progress_value'     => 'required|numeric',
            'other_informations' => 'required|string',
            'team_id'            => 'required|exists:teams,id',
            'department'         => 'required|string',
            'email'              => 'required|string',
            'submission_type'    => 'required|in:individual,party',
        ]);

        // --- Load Team ---
        $team = Team::with('users')->findOrFail($validated['team_id']);

        // --- Load Activity ---
        $activity = Activity::with('challengeLevels')->findOrFail($validated['activity_id']);

        // --- Load the level being submitted ---
        $submissionLevel = ChallengeLevel::findOrFail($validated['level_id']);

        // --- Ensure the level belongs to the activity ---
        if ($submissionLevel->activity_id !== $activity->id) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid level for this activity.'
            ], 422);
        }

        // 🔐 --- LEVEL LOCK: Use Activity helper ---
        if (!$activity->isLevelUnlocked($submissionLevel->level_number)) {
            return response()->json([
                'success' => false,
                'message' => "Submission blocked. Admin has only unlocked up to Level {$activity->maxActiveLevel()}."
            ], 403);
        }

        // --- Check if team is complete ---
        $requiredTeamSize = $submissionLevel->team_size ?? null;
        if ($requiredTeamSize) {
            $currentMembersCount = $team->users()->count();
            if ($currentMembersCount < $requiredTeamSize) {
                return response()->json([
                    'success' => false,
                    'message' => "Submission blocked: Team '{$team->name}' is not complete yet. {$currentMembersCount}/{$requiredTeamSize} members."
                ], 422);
            }
        }

        // --- Generate log_id ---
        $logId = 'LOG-' . date('Ymd') . '-' . substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 4);

        // --- Create submission ---
        $submission = Submission::create([
            'log_id'             => $logId,
            'user_id'            => auth()->id(),
            'team_id'            => $team->id,
            'activity_id'        => $activity->id,
            'activity_name'      => $activity->name,
            'level_id'           => $submissionLevel->id,
            'progress_value'     => $validated['progress_value'],
            'other_informations' => $validated['other_informations'],
            'department'         => $validated['department'],
            'email'              => $validated['email'],
            'submission_type'    => $validated['submission_type'],
        ]);

        // --- Handle attachments ---
        $attachments = [];
        $tempFiles = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('temp_attachments');
                $fullPath = storage_path('app/' . $path);

                $attachments[] = [
                    'path' => $fullPath,
                    'name' => $file->getClientOriginalName(),
                ];

                $tempFiles[] = $fullPath;
            }
        }

        // --- Prepare email ---
        $to = ['neil.olivera.no@sanden-rs.com','nelson.cubio.nc@sanden-rs.com'];
        $subject = "New Activity Submission - {$activity->name}";

        $logoPath = public_path('img/sanden-logo-white.png');
        $logoDataUri = file_exists($logoPath)
            ? 'data:' . mime_content_type($logoPath) . ';base64,' . base64_encode(file_get_contents($logoPath))
            : null;

        $body = view('emails.activity_mail', [
            'team_name'      => $team->name,
            'activity_name'  => $activity->name,
            'submission_type'=> $submission->submission_type,
            'activity_level' => $submissionLevel->level_number,
            'uploader_name'  => auth()->user()->name,
            'department'     => auth()->user()->department,
            'progress_value' => $submission->progress_value,
            'other_info'     => $submission->other_informations,
            'log_id'         => $submission->log_id,
            'logoDataUri'    => $logoDataUri,
        ])->render();

        (new ActivitiesMailService())->registration_mail($to, [], [], $subject, $body, $attachments);

        DB::commit();

        // --- Cleanup temp files ---
        foreach ($tempFiles as $file) {
            if (file_exists($file)) unlink($file);
        }

        return response()->json([
            'success' => true,
            'message' => 'Submission created successfully.'
        ], 201);

    } catch (\Throwable $e) {
        DB::rollBack();
        Log::error('Submission error: ' . $e->getMessage());

        return response()->json([
            'success' => false,
            'message' => 'Server error.'
        ], 500);
    }
}



}

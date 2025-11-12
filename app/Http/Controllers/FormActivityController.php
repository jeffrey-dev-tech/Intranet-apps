<?php

namespace App\Http\Controllers;
use App\Models\Team;
use Illuminate\Http\Request;
use App\Models\Submission;
use App\Models\Activity;
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
    // Find the team with pending status where this user is a member
    $team = Team::where('activity_id', $activity_id)
        ->where('status', 'pending')
        ->whereHas('users', function ($query) use ($user_id) {
            $query->where('user_id', $user_id);
        })
        ->with([
            'level',    // Get level details (includes team_size & required_value)
            'activity', // Get activity details (for unit)
        ])
        ->first(['id', 'name', 'level_id', 'activity_id']);

    if ($team) {
        // Sum all approved submissions for this user, activity, level, team
        $progressValue = Submission::where('user_id', $user_id)
            ->where('team_id', $team->id)
            ->where('activity_id', $activity_id)
            ->where('level_id', $team->level_id)
            ->where('status', 'approved')
            ->sum('progress_value');

        // Use fixed team size from challenge_levels
        $teamSize = $team->level?->team_size ?: 1;

        // Required value per member
        $perMemberRequired = ($team->level?->required_value ?? 0) / max($teamSize, 1);

        return response()->json([
            'team_id'            => $team->id,
            'team_name'          => $team->name,
            'pending_level'      => $team->level?->id,
            'display_level'      => $team->level?->level_number,
            'progress_value'     => $progressValue,
            'per_member_required'=> $perMemberRequired,
            'unit'               => $team->activity?->unit,
        ]);
    }

    // No team found
    return response()->json([
        'team_name'           => null,
        'pending_level'       => null,
        'progress_value'      => 0,
        'per_member_required' => 0,
        'unit'                => null,
    ]);
}

public function store(Request $request)
{
    DB::beginTransaction();

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

        // Generate log_id
        $prefix = 'LOG';
        $date = date('Ymd');
        $randomSuffix = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 4);
        $logId = $prefix . '-' . $date . '-' . $randomSuffix;

        // Create the submission (still inside transaction)
        $submission = Submission::create([
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

        // --- Collect attachments ---
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

        // --- Email details ---
        $to = ['jeffrey.salagubang.js@sanden-rs.com'];
        $cc = [];
        $bcc = [];
        $subject = "New Activity Submission - {$submission->activity_name}";

        // Optional logo embedding
        $logoPath = public_path('img/sanden-logo-white.png');
        $logoDataUri = null;
        if (file_exists($logoPath)) {
            $logoDataUri = 'data:' . mime_content_type($logoPath) . ';base64,' . base64_encode(file_get_contents($logoPath));
        }

        // Render email body with Blade view
      $body = view('emails.activity_mail', [
        'team_name'      => $submission->team->name ?? 'N/A',
        'activity_name'  => $submission->activity_name,
        'activity_level' => $submission->level_id,
        'uploader_name'  => auth()->user()->name,
        'department'     => auth()->user()->department,
        'progress_value' => $submission->progress_value,
        'other_info'     => $submission->other_informations,
        'log_id'         => $submission->log_id,
        'logoDataUri'           => $logoDataUri, // ✅ logo included
    ])->render();

        // --- Send email ---
        $mailService = new ActivitiesMailService();
        $mailService->registration_mail($to, $cc, $bcc, $subject, $body, $attachments);

        // --- If email succeeded, commit DB transaction ---
        DB::commit();

        // --- Cleanup temporary files ---
        foreach ($tempFiles as $file) {
            if (file_exists($file)) {
                unlink($file);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Submission created, email sent, and attachments cleaned up successfully',
        ], 201);

    } catch (\Throwable $e) {
        DB::rollBack(); // rollback if DB error OR email error
        Log::error('Submission error: ' . $e->getMessage());

        return response()->json([
            'success' => false,
            'message' => 'Server error: ' . $e->getMessage(),
        ], 500);
    }
}





}

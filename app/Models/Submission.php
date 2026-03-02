<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class Submission extends Model
{
    use HasFactory;

    protected $fillable = [
        'log_id',
        'team_id',
        'user_id',
        'activity_id',
        'level_id',
        'activity_name',
        'file_path',
        'other_informations',
        'progress_value',
        'progress_value_exceed',
        'status',
        'submission_type', // add this

    ];

    // Relationships
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

       public function level()
    {
         return $this->belongsTo(ChallengeLevel::class, 'level_id');
    }
public static function teamProgress()
{
    return self::select(
            'submissions.team_id',
            'submissions.level_id',
            DB::raw('SUM(submissions.progress_value) as total_progress'),
            'teams.name as team_name',
            'teams.status as team_status',
            'teams.updated_at as team_updated_at',
            'challenge_levels.activity_name',
            'challenge_levels.level_number',
            'challenge_levels.required_value',
            'activities.unit',
            'activities.level_active',
            DB::raw('(SUM(submissions.progress_value) / challenge_levels.required_value) * 100 as progress_percentage'),

            // ✅ Sum of approved submissions per member in JSON (progress_value and exceed)
            DB::raw("(SELECT JSON_ARRAYAGG(
                        CONCAT(u.name, ' (', sub.total_progress, ' | Exceed: ', sub.total_exceed, ')')
                      )
                      FROM (
                          SELECT s2.user_id, 
                                 SUM(s2.progress_value) AS total_progress,
                                 SUM(s2.progress_value_exceed) AS total_exceed
                          FROM submissions s2
                          WHERE s2.team_id = submissions.team_id
                            AND s2.level_id = submissions.level_id
                            AND s2.status = 'approved'
                          GROUP BY s2.user_id
                      ) AS sub
                      JOIN users u ON u.id = sub.user_id
            ) as member_submissions"),

            // Completed at logic
            DB::raw("(CASE 
                        WHEN (
                            SELECT COUNT(*) 
                            FROM submissions s2 
                            WHERE s2.team_id = submissions.team_id
                              AND s2.level_id = submissions.level_id
                              AND s2.status = 'approved'
                              AND s2.submission_type = 'party'
                        ) >= (
                            SELECT COUNT(*) 
                            FROM team_user tu 
                            WHERE tu.team_id = submissions.team_id
                        )
                        THEN teams.updated_at
                        ELSE NULL
                      END) as completed_at")
        )
        ->join('teams', 'teams.id', '=', 'submissions.team_id')
        ->join('challenge_levels', 'challenge_levels.id', '=', 'submissions.level_id')
        ->join('activities', 'activities.id', '=', 'challenge_levels.activity_id')
        ->where('submissions.status', 'approved')
        ->where('activities.status', 'active')
        ->whereNotNull('activities.level_active')
        ->whereColumn('challenge_levels.level_number', 'activities.level_active')
        ->groupBy(
            'submissions.team_id',
            'submissions.level_id',
            'teams.name',
            'teams.status',
            'teams.updated_at',
            'challenge_levels.activity_name',
            'challenge_levels.level_number',
            'challenge_levels.required_value',
            'activities.unit',
            'activities.level_active'
        )
        // Order: completed teams first by oldest completed_at, then in-progress by total_progress desc
        ->orderByRaw('completed_at IS NULL, completed_at ASC')
        ->orderByDesc('total_progress')
        ->get();
}




}

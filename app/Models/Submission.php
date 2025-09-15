<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'status',
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
}

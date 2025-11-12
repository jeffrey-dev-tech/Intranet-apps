<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'activity_id',
        'activity_name',
        'level_id',
        'captain_id',
        'invite_code',
        'status',
    ];

    // Relationship: Team belongs to Activity
public function activity()
{
    return $this->belongsTo(Activity::class, 'activity_id');
}

    // Relationship: Team belongs to ChallengeLevel
    public function level()
    {
        return $this->belongsTo(ChallengeLevel::class, 'level_id');
    }

    // Relationship: Team belongs to a Captain (User)
    public function captain()
    {
        return $this->belongsTo(User::class, 'captain_id');
    }

    // Relationship: Team has many Users (members) via pivot table
public function users()
{
    return $this->belongsToMany(User::class, 'team_user', 'team_id', 'user_id')
                ->withPivot('role', 'progress_value', 'joined_at');
}
    // Relationship: Team has many Submissions
    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }

    
}

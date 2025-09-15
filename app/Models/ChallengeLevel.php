<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChallengeLevel extends Model
{
    use HasFactory;

    protected $fillable = [
        'activity_id',
        'activity_name',
        'level_number',
        'required_value',
        'team_size',
    ];

    // Relationship: ChallengeLevel belongs to an Activity
    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    // Relationship: ChallengeLevel has many teams
    public function teams()
    {
        return $this->hasMany(Team::class, 'level_id');
    }
}

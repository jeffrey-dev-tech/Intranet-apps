<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'unit',
        'level_count',
    ];

    // Relationship: Activity has many challenge levels
    public function levels()
    {
        return $this->hasMany(ChallengeLevel::class);
    }

    // Relationship: Activity has many teams
    public function teams()
    {
        return $this->hasMany(Team::class);
    }
}

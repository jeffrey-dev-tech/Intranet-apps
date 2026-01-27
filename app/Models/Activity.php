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
        'status',
        'level_active', // max unlocked level controlled by admin
    ];

    // Relationship: Activity has many ChallengeLevels
    public function challengeLevels()
    {
        return $this->hasMany(ChallengeLevel::class);
    }

    // Alias
    public function levels()
    {
        return $this->challengeLevels();
    }

    // Relationship: Activity has many teams (optional)
    public function teams()
    {
        return $this->hasMany(Team::class);
    }

    /**
     * Get the highest admin-unlocked level
     */
    public function maxActiveLevel(): int
    {
        return $this->level_active ?? 0;
    }

    /**
     * Check if a given level number is unlocked
     */
    public function isLevelUnlocked(int $levelNumber): bool
    {
        return $levelNumber <= $this->maxActiveLevel();
    }
}

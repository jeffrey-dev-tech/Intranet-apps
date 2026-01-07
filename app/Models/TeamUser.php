<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class TeamUser extends Pivot
{
    public $timestamps = false; // <--- disable timestamps
    protected $table = 'team_user';

    protected $fillable = [
        'team_id',
        'user_id',
        'role',
        'progress_value',
        'joined_at'
    ];

    public function team()
{
    return $this->belongsTo(\App\Models\Team::class);
}
}

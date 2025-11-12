<?php

namespace App\Models;
use App\Models\SpecialAccessUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'password_changed',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Relationship to special access features
     */
public function features()
{
return $this->belongsToMany(Feature::class, 'special_access_users', 'user_id', 'feature_id');
}

// Check if user has a specific feature
public function hasFeature($featureName)
{
return $this->features()->where('name', $featureName)->exists();
}

// Check if user has any feature from a list
public function hasAnyFeature(array $features)
{
return $this->features()->whereIn('name', $features)->exists();
}

// Check if user has all features from a list
public function hasAllFeatures(array $features)
{
return $this->features()->whereIn('name', $features)->count() === count($features);
}
public function teams()
{
return $this->belongsToMany(Team::class, 'team_user', 'user_id', 'team_id')
->withPivot('role', 'progress_value', 'joined_at');
}

public function submissions()
{
return $this->hasMany(Submission::class, 'user_id');
}

// In User.php
public function getEmailForMailing()
{
return filter_var($this->email, FILTER_VALIDATE_EMAIL)
? $this->email
: 'mis.scp@sanden-rs.com';
}

public function head()
{
return $this->belongsTo(DepartmentHead::class, 'head_id');
}


}

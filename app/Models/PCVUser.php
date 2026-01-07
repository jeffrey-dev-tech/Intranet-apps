<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PCVUser extends Model
{
    use HasFactory;

    protected $table = 'pcv_user'; // matches your table

    protected $fillable = [
        'user_id',
        'allowed_department', // JSON array of department IDs
    ];

    // Cast JSON column to PHP array
    protected $casts = [
        'allowed_department' => 'array',
    ];

    /**
     * Relationship: PCVUser belongs to a user
     */
  public function user()
    {
        // The second parameter is the foreign key in pcv_user table ('user_id')
        // The third parameter is the primary key in users table ('id')
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Optional: get departments from allowed_department IDs
     */
    public function departments()
    {
        return Department::whereIn('id', $this->allowed_department ?? [])->get();
    }
}

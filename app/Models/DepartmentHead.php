<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DepartmentHead extends Model
{
    protected $fillable = [
        'name',
        'department',
        'user_id',
        'role',
        'email',
    ];

    // A department head can have many users
    public function users()
    {
        return $this->hasMany(User::class, 'head_id');
    }



}
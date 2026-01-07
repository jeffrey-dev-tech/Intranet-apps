<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    // Table name (optional if table is 'departments')
    protected $table = 'departments';

    // Fillable fields
    protected $fillable = [
        'name',       // department name
    ];

    /**
     * Optional: Relationship to PCVUser
     * 
     * Many users can have this department in allowed_department JSON.
     * Since allowed_department is JSON, we can't do a standard belongsToMany.
     * We'll handle this via queries in the controller.
     */
}

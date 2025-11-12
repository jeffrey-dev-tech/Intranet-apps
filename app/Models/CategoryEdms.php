<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryEdms extends Model
{
    protected $table = 'category_tbl'; // ✅ explicitly set

    protected $fillable = [
        'name',
    ];

    // A category can have many documents/policies
    public function policies()
    {
        return $this->hasMany(Policy::class, 'category_id', 'id');
    }
}

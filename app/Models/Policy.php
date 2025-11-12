<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Policy extends Model
{
    protected $table = 'documents';
    public $timestamps = false;

    // Add fillable fields here
    protected $fillable = [
        'filename',
        'label',
        'department',
        'doc_type',
        'category_id',
        'access_level',
        'upload_date',
    ];

      public function category()
    {
        return $this->belongsTo(CategoryEdms::class, 'category_id', 'id');
    }
}

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
        'file_type',
        'doc_type',
        'control_type',
         'upload_date',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VersionControl extends Model
{
    use HasFactory; // Keep this if you want to use factories

    protected $table = 'intranet_version';
    public $timestamps = false; // If you don't want created_at/updated_at auto-handled

    protected $fillable = [
        'version',
        'updates',
        'date_release',
        'author',
        'created_at',
        'updated_at'
    ];
}

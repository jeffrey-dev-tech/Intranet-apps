<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PCVLog extends Model
{
    use HasFactory;

    protected $table = 'pcv_logs';

    protected $fillable = [
        'user_id',
        'last_series_no',
        'department',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
public function departmentModel()
{
    return $this->belongsTo(Department::class, 'department', 'id');
}

}

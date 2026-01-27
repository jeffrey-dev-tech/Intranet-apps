<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class ApprovalRequest extends Model
{
    use HasFactory;

    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECT = 'reject';
    const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'user_id',
        'approval_level',
        'trf_no',
        'system_id',
        'status',
        'remarks', // add remarks here
    ];

    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}


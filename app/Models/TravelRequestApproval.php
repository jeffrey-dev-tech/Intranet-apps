<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TravelRequestApproval extends Model
{
    use HasFactory;

    protected $table = 'approval_requests';

    protected $fillable = [
        'trf_no',
        'user_id',
        'approval_level',
        'status',
        'system_id',
        'remarks',
        'active',
        'created_at',
        'updated_at',
    ];

    // --- Status constants ---
    const STATUS_PENDING   = 'pending';
    const STATUS_APPROVED  = 'approved';
    const STATUS_DISAPPROVED = 'disapproved';
    const STATUS_REJECTED  = 'rejected';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_COMPLETED = 'completed';
    const STATUS_RECEIVED  = 'received';

    const COO_USER_ID = 100;
    const VICE_USER_ID = 7;
    const SENIOR_MNGR_MGMT_DIV = 18;
    const ADMIN_USER = 63;
    public function travelRequest()
    {
        return $this->belongsTo(TravelRequest::class, 'trf_no', 'trf_no');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

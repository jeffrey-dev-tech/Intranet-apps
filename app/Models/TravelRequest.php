<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TravelRequest extends Model
{
    use HasFactory;

    // Table name
    protected $table = 'travel_requests';

    // Mass assignable fields
    protected $fillable = [
        'user_id',
        'department',
        'travel_date_from',
        'travel_date_to',
        'destination',
        'request_type',
        'preferred_time_departure',
        'preferred_time_return',
        'accommodation',
        'purpose',
        'trf_no',
        'additional_request',
        'attachments',
        'status',
    ];

    // Casts
    protected $casts = [
        'travel_date_from' => 'datetime',
        'travel_date_to' => 'datetime',
        'preferred_time_departure' => 'datetime',
        'preferred_time_return' => 'datetime',
        'attachments' => 'array',
        'attachments_admin' => 'array',
        'accommodation' => 'boolean',
    ];

    // --- Status constants ---
// TravelRequest model
const STATUS_PENDING   = 'pending';
const STATUS_APPROVED  = 'approved';
const STATUS_REJECTED    = 'rejected';
const STATUS_CANCELLED = 'cancelled';
const STATUS_COMPLETED = 'completed';

    /**
     * The user who made the travel request
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if the request is completed
     */
    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_APPROVED; // or whatever logic you want
    }

  public function approvals()
{
    return $this->hasMany(
        TravelRequestApproval::class,
        'trf_no',
        'trf_no'
    );
}

    public static function generateTrfNo(string $type): string
    {
        $year = date('y');
        $prefix = $type === 'Air Travel' ? 'A' : 'L';

        $lastRequest = self::where('request_type', $type)
            ->whereYear('created_at', date('Y'))
            ->orderBy('id', 'desc')
            ->first();

        $series = $lastRequest && preg_match('/(\d+)$/', $lastRequest->trf_no, $matches)
            ? intval($matches[1]) + 1
            : 1;

        $seriesPadded = str_pad($series, 3, '0', STR_PAD_LEFT);

        return "TRF{$year}-{$prefix}{$seriesPadded}";
    }
}

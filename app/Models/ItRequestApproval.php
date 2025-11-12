<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItRequestApproval extends Model
{
    use HasFactory;

    // Explicitly define table name
    protected $table = 'it_request_approval';

    // Allow mass assignment
    protected $fillable = [
        'reference_no',
        'approved_by',
        'status',
        'role',
        'approver_email',
        'current_approver_role',
        'remarks',
    ];

    /**
     * Relationship: An approval belongs to a single IT request.
     */
    public function itRequest()
    {
        return $this->belongsTo(ITRequest::class, 'reference_no', 'reference_no');
    }

}

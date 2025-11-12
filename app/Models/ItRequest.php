<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ItRequest extends Model
{
    protected $table = 'it_request_tbl';

   protected $fillable = [
      'type_request',
        'requestor_name',
        'department',
        'requestor_email',
        'description_of_request',
        'issue',
        'Level_Request',
        'attachment_name',
        'item_name',
        'date_needed',
        'plan_return_date',
        'purchase_item_name',
        'project_details',
        'intranet_request_type',
        'manager_email',
        'change_request_intranet',
    ];

    // Automatically generate reference_no on creation
 protected static function booted()
{
    static::creating(function ($request) {
        $request->reference_no = self::generateReferenceNo($request->type_request);
    });
}

private static function generateReferenceNo($typeRequest)
{
    $prefixMap = [
        'Repair_Request' => 'RR',
        'Borrow_Item' => 'BI',
        'Purchase_Item' => 'PI',
        'Project_Request' => 'PR',
        'Intranet_Request' => 'IR',
    ];

    $prefix = $prefixMap[$typeRequest] ?? 'GEN';
    $date = now()->format('Ym'); // Only Year + Month

    $count = DB::table('it_request_tbl')
        ->where('type_request', $typeRequest)
        ->whereYear('created_at', now()->year)
        ->whereMonth('created_at', now()->month)
        ->count() + 1;

    return sprintf('%s-%s-%03d', $prefix, $date, $count);
}

public function approvals()
{
    return $this->hasMany(ItRequestApproval::class, 'reference_no', 'reference_no');
}


}

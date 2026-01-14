<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ItRequestApproval;
use App\Models\ItRequest;
use App\Models\Submission;
use App\Models\AnotherModel; // Example third table

class NotificationController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // ===== 1) IT Request Approvals =====
        $approvalQuery = ItRequestApproval::select(
                'id',
                'reference_no as title',
                'status',
                'created_at',
                'approver_email'
            )
            ->where('status', 'pending');

        if ($user->department === '(SCP) RS-IT') {
            $approvalQuery->where('approver_email', 'sanden.mis.sm@sanden-rs.com');
        } else {
            $approvalQuery->where('approver_email', $user->email);
        }

        $approvals = $approvalQuery
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($row) {
                $row->type = 'IT Request Approval';
                return $row;
            });

// ===== 2) ACtivity Approval =====
$approval_fitnessChallenge = collect(); // empty collection by default
if (in_array(auth()->id(), [69, 119])) {
    $approval_fitnessChallenge = Submission::select(
            'log_id',
            'log_id as title',
            'status',
            'created_at'
        )
        ->where('status', 'pending')
        ->orderBy('created_at', 'desc')
        ->get()
        ->map(function ($row) {
            $row->type = 'Fitness Challenge';
            return $row;
        });
}


        // ===== 3) Another Table (example) =====
        // $another = AnotherModel::select(
        //         'id',
        //         'reference_no as title',
        //         'status',
        //         'created_at'
        //     )
        //     ->where('status', 'pending')
        //     ->orderBy('created_at', 'desc')
        //     ->get()
        //     ->map(function ($row) {
        //         $row->type = 'Another Notification Type';
        //         return $row;
        //     });

        // ===== Merge all collections =====
        $notifications = collect()
            ->merge($approvals)
            ->merge($approval_fitnessChallenge)
            // ->merge($another)
            ->sortByDesc('created_at')
            ->values();

        return response()->json([
            'count' => $notifications->count(),
            'notifications' => $notifications,
            'message' => $notifications->isEmpty() ? 'No notifications found' : null
        ]);
    }
}

<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ItRequestApproval;
use App\Models\ApprovalRequest;
use App\Models\Submission;

class Notifications extends Component
{
    public $notifications = [];
    public $count = 0;

    public function mount()
    {
        $this->loadNotifications();
    }

    public function loadNotifications()
    {
        $user = auth()->user();

        // IT Request approvals
        $approvals = ItRequestApproval::where('status', 'pending')
            ->when($user->department === 'MIS', fn($q) => 
                $q->where('approver_email', 'mis.scp@sanden-rs.com')
            )
            ->when($user->department !== 'MIS', fn($q) => 
                $q->where('approver_email', $user->email)
            )
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn($row) => [
                'id'         => $row->id,
                'title'      => $row->reference_no,
                'type'       => 'IT Request Approval',
                'url'        => route('it_request.approvalForm', $row->reference_no),
                'icon'       => 'check-circle',
                'created_at' => $row->created_at,
            ]);

        // Wellness Program (optional)
        $fitness = collect();
        if (in_array($user->id, [63, 100]) ) {
            $fitness = Submission::where('status', 'pending')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(fn($row) => [
                    'id'         => $row->log_id,
                    'title'      => $row->log_id,
                    'type'       => 'Wellness Program',
                    'url'        => route('approvalForm.activity', $row->log_id),
                    'icon'       => 'activity',
                    'created_at' => $row->created_at,
                ]);
        }


        

        // Merge and sort
        $this->notifications = collect()
            ->merge($approvals)
            ->merge($fitness)
            ->sortByDesc('created_at')
            ->values()
            ->toArray();

        $this->count = count($this->notifications);
    }

    public function clearAll()
    {
        $this->notifications = [];
        $this->count = 0;
    }

    public function render()
    {
        return view('livewire.notifications');
    }
}

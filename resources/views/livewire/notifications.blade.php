<div >
    <!-- Notification Dropdown -->
    <a class="nav-link dropdown-toggle" href="#" id="notificationDropdown" role="button"
       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i data-feather="bell"></i>
        @if($count > 0)
            <div class="indicator">
                <div class="circle"></div>
            </div>
        @endif
    </a>

    <div class="dropdown-menu" aria-labelledby="notificationDropdown" style="width: 320px;">
        <div class="dropdown-header d-flex align-items-center justify-content-between">
            <p class="mb-0 font-weight-medium">{{ $count }} New Notifications</p>
            <!-- <a href="#" wire:click.prevent="clearAll">Clear all</a> -->
        </div>

        <div class="dropdown-body" style="max-height: 300px; overflow-y: auto;">
            @forelse($notifications as $notif)
                <a href="{{ $notif['url'] }}" class="dropdown-item">
                    <div class="icon">
                        <i data-feather="{{ $notif['icon'] }}"></i>
                    </div>
                    <div class="content">
                        <p>{{ $notif['title'] }}</p>
                        <p class="sub-text text-muted">{{ $notif['type'] }}</p>
                        <p class="sub-text text-muted small">{{ \Carbon\Carbon::parse($notif['created_at'])->diffForHumans() }}</p>
                    </div>
                </a>
            @empty
                <p class="text-center text-muted m-2">No pending notifications</p>
            @endforelse
        </div>
    </div>

</div>


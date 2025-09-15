<div class="dropdown-header d-flex align-items-center justify-content-between">
    <p class="mb-0 font-weight-medium">{{ $pendingCount }} New Notifications</p>
    <a href="javascript:;" class="text-muted">Clear all</a>
</div>

<div class="dropdown-body">
    @forelse($pendingRequests as $request)
        <a href="{{ route('it_request.show', $request->id) }}" class="dropdown-item">
            <div class="icon">
                <i data-feather="alert-circle"></i>
            </div>
            <div class="content">
                <p>{{ $request->reference_no ?? 'Pending IT Request' }}</p>
                <p class="sub-text text-muted">{{ $request->created_at->diffForHumans() }}</p>
            </div>
        </a>
    @empty
        <p class="text-center text-muted m-2">No pending notifications</p>
    @endforelse
</div>

<div class="dropdown-footer d-flex align-items-center justify-content-center">
    <a href="{{ route('it_request.index') }}">View all</a>
</div>

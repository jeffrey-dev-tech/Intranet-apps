{{-- resources/views/it_requests/index.blade.php --}}

@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">IT Requests</h1>

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($requests->isEmpty())
        <div class="alert alert-info text-center">
            No IT requests found.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Status</th>
                        <th>Approver</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($requests as $index => $request)
                        <tr>
                            <td>{{ $requests->firstItem() + $index }}</td>
                            <td>{{ $request->title ?? 'Untitled' }}</td>
                            <td>{{ $request->status }}</td>
                            <td>{{ $request->approver_email }}</td>
                            <td>{{ $request->created_at->format('Y-m-d H:i') }}</td>
                            <td>
                               
                                    View
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="d-flex justify-content-center mt-3">
            {{ $requests->links() }}
        </div>
    @endif
</div>
@endsection

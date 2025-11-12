@extends('layouts.app')
@section('title', 'Features Assign')
@section('content')

<div class="container">
    <h2 class="mb-4">Maintenance Mode Records</h2>

    @if($results->isEmpty())
        <div class="alert alert-info">No maintenance records found.</div>
    @else
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Route Name</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                </tr>
            </thead>
            <tbody>
                @foreach($results as $index => $row)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                     
                        <td>{{ $row->route_name ?? '—' }}</td>
                           <td>{{ $row->enabled ?? 'N/A' }}</td>
                        <td>{{ $row->created_at ? $row->created_at->format('Y-m-d H:i') : '' }}</td>
                        <td>{{ $row->updated_at ? $row->updated_at->format('Y-m-d H:i') : '' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>


@endsection
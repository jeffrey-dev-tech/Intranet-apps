@extends('layouts.app')
@section('title', 'Maintenance Page')
@section('content')
<div class="page-content">
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Administrator</a></li>
            <li class="breadcrumb-item active" aria-current="page">VPN</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
          <div class="table-responsive">
    <h2 class="mb-4">Maintenance Mode Records</h2>

    @if($results->isEmpty())
        <div class="alert alert-info">No maintenance records found.</div>
    @else
        <table class="table table-bordered table-striped" id="maintenance_tbl">
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
      </div>
            </div>
        </div>
    </div>
</div>
<script src="assets/js/jquery.js"></script>
<script>
$(document).ready(function() {
   var table = $('#maintenance_tbl').DataTable({
        paging: true,
        searching: true,
        ordering: true,
        info: true,
        lengthChange: true,
        pageLength: 5
    });
});
</script>

@endsection
@extends('layouts.app')

@section('title', 'Activity List')

@section('content')
<div class="page-content">
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Wellness Program</a></li>
            <li class="breadcrumb-item active" aria-current="page">Activity List</li>
        </ol>
    </nav>

    <!-- Teams Table -->
    <div class="card">
        <div class="card-body">
            <h4 class="card-title mb-3">Activity List</h4>
            <div class="table-responsive">
                <table id="teamsTable" class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th style="background: rgb(80, 128, 233); color: white;">Activity Name</th>
                                <th style="background: rgb(80, 128, 233); color: white;">Levels</th>
                              <th style="background: rgb(80, 128, 233); color: white;">Descriptions</th>
                               <th style="background: rgb(80, 128, 233); color: white;">Active Level</th>
                            <th style="background: rgb(80, 128, 233); color: white;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($activities as $activity)
                        <tr>
                            <td>{{ $activity->name }}</td>
              <td class="align-top p-2">
    @php
        $levels = json_decode($activity->levels, true);
    @endphp

    @if(!empty($levels))
        <ul class="list-unstyled mb-0">
            @foreach($levels as $level)
                <li class="d-flex justify-content-between align-items-center bg-light border rounded px-2 py-1 mb-1 small">
                    <div class="text-truncate">
                        <strong class="text-dark">Lvl {{ $level['level_number'] }}</strong> 
                        <span class="text-muted">| {{ $level['required_value'] }}</span>
                    </div>
                    <span class="badge badge-primary badge-pill">
                        {{ $level['team_size'] }}
                    </span>
                </li>
            @endforeach
        </ul>
    @else
        <span class="text-muted small">N/A</span>
    @endif
</td>


                       <td class="text-wrap" style="max-width:300px;">{{ $activity->description }}</td>
                            <td>{{ $activity->level_active ?? 'Not Set' }}</td>
                            <td>
                                <!-- Delete Button -->
                                <!-- <form action="{{ route('activities.destroy', $activity->id) }}" method="POST" class="delete-form" style="display:inline;">
        @csrf
        @method('DELETE')
        <button type="button" class="btn btn-sm btn-danger delete-btn">Delete</button>
    </form> -->
                                <!-- Edit Level Button -->
                                <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#editLevelModal{{ $activity->id }}">
                                    Edit Level
                                </button>
<!-- Modal -->
<div class="modal fade" id="editLevelModal{{ $activity->id }}" tabindex="-1" role="dialog" aria-labelledby="editLevelModalLabel{{ $activity->id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('activities.updateLevel', $activity->id) }}" method="POST" class="edit-level-form">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editLevelModalLabel{{ $activity->id }}">Edit Activity</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <!-- Active Level -->
                    <div class="form-group">
                        <label for="level_active" class="form-label">Select Active Level</label>
                        <select name="level_active" class="form-control">
                            @if(!empty($levels))
                                @foreach($levels as $level)
                                    <option value="{{ $level['level_number'] }}" 
                                        {{ $activity->level_active == $level['level_number'] ? 'selected' : '' }}>
                                        Level {{ $level['level_number'] }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <!-- Status -->
                    <div class="form-group mt-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" class="form-control">
                            <option value="active" {{ $activity->status == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ $activity->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success save-level-btn">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

                                <!-- End Modal -->
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <hr>
</div>

<script>
$(document).ready(function() {
    // Initialize DataTable
    $('#teamsTable').DataTable({
        "paging": true,
        "searching": true,
        "ordering": true,
        "order": [[0, "asc"]],
        "columnDefs": [
            { "orderable": false, "targets": 4 }
        ]
    });

    // SweetAlert Delete Confirmation
    $('.delete-btn').click(function(e) {
        e.preventDefault();
        let form = $(this).closest('form');

        Swal.fire({
            title: 'Are you sure?',
            text: "It will delete all Teams and submitted data!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });

    // SweetAlert Edit Level Confirmation
    $('.save-level-btn').click(function(e) {
        e.preventDefault();
        let form = $(this).closest('form');

        Swal.fire({
            title: 'Confirm Change',
            text: "Are you sure you want to update the active level?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, save it!'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });

    // SweetAlert Success After Redirect
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: "{{ session('success') }}",
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK'
        }).then(() => {
            location.reload();
        });
    @endif
});
</script>
@endsection

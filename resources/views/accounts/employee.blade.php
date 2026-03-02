@extends('layouts.app')

@section('title', 'Employee List')

@section('content')
<div class="page-content">
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Accounts</a></li>
            <li class="breadcrumb-item active" aria-current="page">Employee List</li>
        </ol>
    </nav>

    <!-- Teams Table -->
    <div class="card">
        <div class="card-body">
            <h4 class="card-title mb-3">Employee List</h4>
            <div class="table-responsive">
                <table id="employeeList" class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Department</th>
                            <th>Position</th>
                            <th>Head Name</th>
                            <th>Status</th>
                            <th>Reset Password</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($employeeList as $index => $emp)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $emp->name }}</td>
<td>
    <div class="d-flex align-items-center">
        <select class="form-select edit-department" data-id="{{ $emp->id }}" disabled>
            @foreach($departments as $dept)
                <option value="{{ $dept->id }}" {{ $emp->department_id == $dept->id ? 'selected' : '' }}>
                    {{ $dept->name }}
                </option>
            @endforeach
        </select>
        <button type="button" class="btn btn-default btn-xs btn-icon toggle-edit-dept" title="Edit Department">
            <i data-feather="check-square"></i>
        </button>
    </div>
</td>

   <td>
    <div class="d-flex align-items-center">
        <input type="text" class="form-control edit-position" 
               data-id="{{ $emp->id }}" value="{{ $emp->position }}" readonly>
        <button type="button" class="btn btn-default btn-xs btn-icon toggle-edit" title="Edit Position">
	<i data-feather="check-square"></i>
</button>
    </div>
</td>
<td>
    <div class="d-flex align-items-center">
        <select class="form-select edit-head" data-id="{{ $emp->id }}" disabled>
            <option value="">-- Select Head --</option>
            @foreach($departmentHeads as $head)
                <option value="{{ $head->id }}" {{ $emp->head_id == $head->id ? 'selected' : '' }}>
                    {{ $head->name }}
                </option>
            @endforeach
        </select>
        <button type="button" class="btn btn-default btn-xs btn-icon toggle-edit-head" title="Edit Head">
            <i data-feather="check-square"></i>
        </button>
    </div>
</td>


                            <td>
                                @if($emp->status == 'Active')
                                    <button class="btn btn-sm btn-outline-danger toggle-status" 
                                            data-id="{{ $emp->id }}" data-status="Inactive" title="Deactivate">
                                    <i class="mdi mdi-close-octagon-outline"></i> 
                                    </button>
                                @elseif($emp->status == 'Inactive')
                                    <button class="btn btn-sm btn-outline-success toggle-status" 
                                            data-id="{{ $emp->id }}" data-status="Active" title="Activate">
                                        <i class="mdi mdi-checkbox-marked-outline"></i>
                                    </button>
                                @endif
                            </td>
                            <td style="text-align: center;">
    <button class="btn btn-sm btn-outline-warning reset-password" 
            data-id="{{ $emp->id }}" title="Reset Password">
            <i class="mdi mdi mdi-sync"></i>
    </button>
</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-danger">
                                No team members found
                            </td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
        </div>
    </div>

    <hr>
</div>
<script>
$(document).ready(function () {
    feather.replace();
    $('#employeeList').DataTable({
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50, 100],
        ordering: true,
        searching: true,
        responsive: true,
        columnDefs: [
            { orderable: false, targets: [5] } // Disable sorting on Team Head column
        ]
    });

    // Toggle Status
   $('.toggle-status').click(function() {
    let userId = $(this).data('id');
    let newStatus = $(this).data('status');
    let button = $(this);

    Swal.fire({
        title: `Are you sure?`,
        text: `You are about to set this user as ${newStatus}.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, change it!',
        cancelButtonText: 'Cancel',
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "{{ route('accounts.updateStatus', ':id') }}".replace(':id', userId),
                type: 'PATCH',
                data: {
                    status: newStatus,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    Swal.fire('Updated!', response.message, 'success')
                        .then(() => location.reload());
                },
                error: function(err) {
                    Swal.fire('Error!', 'Something went wrong.', 'error');
                }
            });
        }
    });
});

// Enable Department edit
$('#employeeList').on('click', '.toggle-edit-dept', function() {
    let select = $(this).siblings('select');
    select.data('old', select.val()); // store old value
    select.prop('disabled', false).focus();
});

// Save Department on blur
$('#employeeList').on('blur', '.edit-department', function() {
    let select = $(this);
    let userId = select.data('id');
    let oldValue = select.data('old');
    let newValue = select.val();

    select.prop('disabled', true); // always disable

    if (oldValue === newValue) return;

    Swal.fire({
        title: 'Change Department?',
        text: 'Are you sure you want to change this user’s department?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, update!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "{{ route('accounts.updateDepartment', ':id') }}".replace(':id', userId),
                type: 'PATCH',
                data: { department_id: newValue, _token: '{{ csrf_token() }}' },
                success: function(response) {
                    Swal.fire('Updated!', response.message, 'success');
                    select.data('old', newValue);
                },
                error: function() {
                    Swal.fire('Error!', 'Could not update department.', 'error');
                    select.val(oldValue); // revert
                }
            });
        } else {
            select.val(oldValue); // revert
        }
    });
});

// Enable Head edit
$('#employeeList').on('click', '.toggle-edit-head', function() {
    let select = $(this).siblings('select');
    select.data('old', select.val()); // store old value
    select.prop('disabled', false).focus();
});

// Save Head on blur
$('#employeeList').on('blur', '.edit-head', function() {
    let select = $(this);
    let userId = select.data('id');
    let oldValue = select.data('old');
    let newValue = select.val();

    select.prop('disabled', true);

    if (oldValue === newValue) return;

    Swal.fire({
        title: 'Change Department Head?',
        text: 'Are you sure you want to assign a new department head?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, update!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "{{ route('accounts.updateHead', ':id') }}".replace(':id', userId),
                type: 'PATCH',
                data: { head_id: newValue, _token: '{{ csrf_token() }}' },
                success: function(response) {
                    Swal.fire('Updated!', response.message, 'success');
                    select.data('old', newValue);
                },
                error: function() {
                    Swal.fire('Error!', 'Could not update head.', 'error');
                    select.val(oldValue); // revert
                }
            });
        } else {
            select.val(oldValue); // revert
        }
    });
});

// Click edit button → enable input and store old value
$('#employeeList').on('click', '.toggle-edit', function() {
    let input = $(this).siblings('input');
    input.data('old', input.val()); // store old value
    input.prop('readonly', false).focus(); // enable editing
});

// On blur → confirm change with SweetAlert
$('#employeeList').on('blur', '.edit-position', function() {
    let input = $(this);
    let userId = input.data('id');
    let oldValue = input.data('old');
    let newValue = input.val().trim();

    // Make input readonly immediately
    input.prop('readonly', true);

    // Only proceed if the value changed
    if (newValue === oldValue) return;

    Swal.fire({
        title: 'Change Position?',
        text: `Are you sure you want to change position from "${oldValue}" to "${newValue}"?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, update it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "{{ route('accounts.updatePosition', ':id') }}".replace(':id', userId),
                type: 'PATCH',
                data: {
                    position: newValue,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    Swal.fire('Updated!', response.message, 'success');
                    input.data('old', newValue); // update stored value
                },
                error: function() {
                    Swal.fire('Error!', 'Could not update position.', 'error');
                    input.prop('readonly', false); // allow editing again
                }
            });
        } else {
            input.val(oldValue); // revert value if canceled
        }
    });
});

$('#employeeList').on('click', '.reset-password', function() {
    let userId = $(this).data('id');

    Swal.fire({
        title: 'Reset Password?',
        text: 'The user password will be reset to the default and they will be required to change it on next login.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, reset it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "{{ route('accounts.resetPassword', ':id') }}".replace(':id', userId),
                type: 'PATCH',
                data: { _token: '{{ csrf_token() }}' },
                success: function(response) {
                    Swal.fire('Reset!', response.message, 'success');
                },
                error: function() {
                    Swal.fire('Error!', 'Could not reset password.', 'error');
                }
            });
        }
    });
});

});


</script>

@endsection

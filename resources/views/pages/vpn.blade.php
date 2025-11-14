@extends('layouts.app')

@section('title', 'VPN Accounts')

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
                    <h6 class="card-title">VPN Accounts</h6>
                    <div class="table-responsive">
                        <table id="vpn_tbl" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" id="select_all"></th>
                                    <th>#</th>
                                    <th>Username</th>
                                    <th>Password</th>
                                    <th>Status</th>
                                    <th>Email</th>
                                    <th>Expiration Date</th>
                                    <th>Sent Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $i = 1; @endphp
                                @foreach($results as $row)
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="row_checkbox" name="user_ids[]" value="{{ $row->id }}">
                                        </td>
                                        <td>{{ $i++ }}</td>
                                        <td>{{ $row->username ?? 'N/A' }}</td>
                                        <td style="position: relative; width: 150px;">
                                            <span class="password-text" data-password="{{ $row->password ?? '' }}">
                                                ••••••••
                                            </span>
                                            <button type="button" class="btn btn-sm btn-danger toggle-password" 
                                                    style="position: absolute; right: 0; top: 50%; transform: translateY(-50%); padding: 2px 6px;">
                                                <i class="fa fa-eye-slash"></i>
                                            </button>
                                        </td>
                                        <td>{{ $row->status ?? 'N/A' }}</td>
                                        <td>{{ strtolower($row->email) ?? 'N/A' }}</td>
                                        <td>
    {{ $row->updated_at ? \Carbon\Carbon::parse($row->updated_at)->addDays(30)->format('Y-m-d') : 'N/A' }}
</td>

                                       <td>
    @if(isset($row->send_status))
        {{ $row->send_status ? 'Sent' : 'Not Sent' }}
    @else
        N/A
    @endif
</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <button id="generate_passwords" class="btn btn-primary mt-3">
                            <i class="fa fa-cogs"></i> Generate Passwords
                        </button>

                        <button id="send_vpn_emails" class="btn btn-success mt-3">
    <i class="fa fa-envelope"></i> Send VPN Emails
</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="assets/js/jquery.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle password visibility
    document.querySelectorAll('.toggle-password').forEach(function(button) {
        button.addEventListener('click', function() {
            const passwordSpan = this.parentElement.querySelector('.password-text');
            const icon = this.querySelector('i');
            if (icon.classList.contains('fa-eye-slash')) {
                passwordSpan.textContent = passwordSpan.dataset.password;
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            } else {
                passwordSpan.textContent = '••••••••';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            }
        });
    });
});
$(document).ready(function() {
    // Track selected users across pages
    var selectedUserIds = {};

    var table = $('#vpn_tbl').DataTable({
        paging: true,
        searching: true,
        ordering: true,
        info: true,
        lengthChange: true,
        pageLength: 5
    });

    // Checkbox handling
    $('#select_all').on('click', function() {
        var isChecked = $(this).prop('checked');
        var rows = $(table.rows({ search: 'applied' }).nodes());
        rows.find('input.row_checkbox').each(function() {
            $(this).prop('checked', isChecked);
            selectedUserIds[$(this).val()] = isChecked;
        });
    });

    $('#vpn_tbl tbody').on('change', 'input.row_checkbox', function() {
        selectedUserIds[$(this).val()] = $(this).prop('checked');
        var rows = $(table.rows({ search: 'applied' }).nodes());
        var allChecked = rows.find('input.row_checkbox:checked').length === rows.find('input.row_checkbox').length;
        $('#select_all').prop('checked', allChecked);
        $('#select_all').prop('indeterminate', !allChecked && rows.find('input.row_checkbox:checked').length > 0);
    });

    table.on('draw', function() {
        var rows = $(table.rows({ search: 'applied' }).nodes());
        rows.find('input.row_checkbox').each(function() {
            $(this).prop('checked', !!selectedUserIds[$(this).val()]);
        });
        var allChecked = rows.find('input.row_checkbox:checked').length === rows.find('input.row_checkbox').length;
        $('#select_all').prop('checked', allChecked);
        $('#select_all').prop('indeterminate', !allChecked && rows.find('input.row_checkbox:checked').length > 0);
    });

    // ------------------------------
    // Generate Passwords Button
    // ------------------------------
$('#generate_passwords').on('click', function() {
    var checkedValues = Object.keys(selectedUserIds).filter(key => selectedUserIds[key]);
    if (checkedValues.length === 0) {
        Swal.fire('No users selected', 'Please select at least one user.', 'warning');
        return;
    }

    Swal.fire({
        title: 'Generate passwords?',
        text: `You are generating passwords for ${checkedValues.length} user(s).`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, generate!'
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading modal while backend is processing
            Swal.fire({
                title: 'Generating passwords...',
                html: 'Please wait while passwords are being generated.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: "{{ route('vpn.generate') }}",
                method: 'POST',
                data: { user_ids: checkedValues },
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function(response) {
                    Swal.close(); // close loading modal
                    console.log(response);

                    Swal.fire({
                        title: 'Passwords Generated!',
                        text: 'Passwords have been updated',
                        icon: 'success',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, refresh',
                        cancelButtonText: 'No, stay'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            location.reload();
                        }
                    });
                },
                error: function(xhr) {
                    Swal.close(); // close loading modal
                    console.error(xhr);
                    Swal.fire('Error', 'Failed to generate passwords.', 'error');
                }
            });
        }
    });
});


    // ------------------------------
    // Send Emails Button
    // ------------------------------
  $('#send_vpn_emails').on('click', function() {
    var checkedValues = Object.keys(selectedUserIds).filter(key => selectedUserIds[key]);

    if (checkedValues.length === 0) {
        Swal.fire('No users selected', 'Please select at least one user.', 'warning');
        return;
    }

    Swal.fire({
        title: 'Send VPN emails?',
        text: `Emails will be sent to ${checkedValues.length} user(s).`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, send!'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Sending emails...',
                html: 'Please wait while emails are being sent.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: "{{ route('vpn.send_mail') }}",
                method: 'POST',
                data: { user_ids: checkedValues },
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function(response) {
                    Swal.close(); // Close the loading modal
                    console.log(response);

                    if (response.sent_ids && response.sent_ids.length > 0) {
                        Swal.fire(
                            'Emails Sent!',
                            `Emails successfully sent to ${response.sent_ids.length} user(s).`,
                            'success'
                        ).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire(
                            'No Emails Sent',
                            'All selected users already received their credentials.',
                            'info'
                        );
                    }
                },
                error: function(xhr) {
                    Swal.close(); // Close the loading modal
                    console.error(xhr);
                    let message = 'Failed to send emails.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    }
                    Swal.fire('Error', message, 'error');
                }
            });
        }
    });
});


});


</script>
@endsection

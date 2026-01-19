@extends('layouts.app')

@section('title', 'Petty Cash Voucher')

@section('content')
<style>
.wrap-text {
    white-space: normal !important;  /* allow wrapping */
    word-break: break-word !important; /* break long words if needed */
    overflow-wrap: break-word !important;
    max-width: 150px; /* optional: set max width for the column */
}
</style>
<div class="page-content d-flex justify-content-center align-items-center">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <!-- Logo and Title -->
                <div class="sanden-logo text-center mb-4">
                    <img src="{{ asset('img/Sanden_Logo_SCP2_.png') }}" alt="sanden-logo" class="mb-2" style="max-height:80px;">
                    <div class="title-form">
                        <h6 class="card-title"style="
                    font-family: 'Courier New', Courier, monospace;
                    font-size: 25px;
                    font-weight: bold;
                    text-align: center;
                    letter-spacing: 2px;
                ">Petty Cash Voucher</h6>
                    </div>
                </div>

                <div class="row">
                    <!-- Form Section -->
                    <div class="col-md-3">
    <form id="downloadVoucherForm" action="{{ route('download_voucher') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="department" class="form-label">Department</label>
            <select class="form-select " id="department" name="department" required>
                <option selected disabled>Select Department</option>
                @foreach($departments as $dept)
                    <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" readonly value="{{ auth()->user()->name }}">
            <input type="hidden" name="user_id" value="{{ auth()->id() }}">
        </div>

        <button type="button" id="downloadBtn" class="btn btn-success w-100">
            <i class="fas fa-download"></i> Download
        </button>
    </form>
</div>
<div class="col-md-9">
    <div class="table-responsive">
        <table id="pcv_log_tbl" class="table table-bordered table-hover table-sm">
<div class="d-flex justify-content-center mb-2">
    <div class="me-2">
        <label for="departmentFilter" class="form-label me-1">Filter by Department:</label>
        <select id="departmentFilter" class="form-select d-inline-block" style="width: 200px;">
            <option value="">All Departments</option>
            @foreach($departments as $department)
                <option value="{{ $department->name }}">{{ $department->name }}</option>
            @endforeach
        </select>
    </div>
</div>
@php
    $showUniqueCodeFor = [100,18,53,34,97,84]; // Replace with the actual user IDs
@endphp
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Date</th>
                    <th>Requester</th>
                    <th>Department/Region</th>
                    <th>Series No</th>
                    @if(in_array(Auth::id(), $showUniqueCodeFor))
            <th>Unique Code</th>
        @endif
                </tr>
            </thead>
   <tbody>
@php $counter = 1; @endphp
@forelse($pcvLogs as $departmentLogs)
    @foreach($departmentLogs as $log)
        <tr>
            <td>{{ $counter++ }}</td>
            <td>{{ optional($log->created_at)->format('Y-m-d H:i:s') ?? 'N/A' }}</td>
            <td>{{ $log->user ? $log->user->name : 'N/A' }}</td>
            <td>{{ optional($log->departmentModel)->name ?? 'N/A' }}</td>
            <td>{{ $log->last_series_no }}</td>

            @if(in_array(Auth::id(), $showUniqueCodeFor))
                <td class="wrap-text" style="white-space: normal; word-break: break-word;">
                    @if($log->unique_code)
                        @php
                            // Decode JSON string if stored as JSON
                            $codes = json_decode($log->unique_code, true);
                        @endphp

                        @if(is_array($codes))
                            @foreach($codes as $series => $code)
                                {{ $series }}:{{ $code }}<br>
                            @endforeach
                        @else
                            {{ $log->unique_code }}
                        @endif
                    @else
                        N/A
                    @endif
                </td>
            @endif
        </tr>
    @endforeach
@empty
    <tr>
        <td colspan="{{ in_array(Auth::id(), $showUniqueCodeFor) ? 6 : 5 }}" class="text-center">
            No PCV logs found.
        </td>
    </tr>
@endforelse
</tbody>


        </table>
    </div>
</div>


                </div> <!-- End row -->

            </div>
        </div>
    </div>
</div>
<script src="assets/js/sweetalert2@11.js"></script>
<script>
    $(document).ready(function() {
        var table = $('#pcv_log_tbl').DataTable({
            paging: true,
            searching: true,
            ordering: true,
            info: true,
            lengthChange: true,
        });

        // Custom filter for Department
        $('#departmentFilter').on('change', function() {
            var selected = $(this).val();
            table.column(3) // Department is the 4th column (0-indexed)
                 .search(selected)
                 .draw();
        });
    });
</script>
<script>
document.getElementById('downloadBtn').addEventListener('click', function() {
    Swal.fire({
        title: 'Are you sure?',
        text: "You want to download the voucher?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, download it!'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById('downloadVoucherForm');
            const formData = new FormData(form);

            fetch("{{ route('download_voucher') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            })
            .then(response => {
                if (!response.ok) throw new Error('Something went wrong!');

                // Get filename from Content-Disposition header
                const contentDisposition = response.headers.get('Content-Disposition');
                const fileNameMatch = contentDisposition?.match(/filename="(.+)"/);
                const fileName = fileNameMatch ? fileNameMatch[1] : 'voucher.pdf';

                return response.blob().then(blob => ({ blob, fileName }));
            })
            .then(({ blob, fileName }) => {
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = fileName; // use backend-generated filename
                document.body.appendChild(a);
                a.click();
                a.remove();
                window.URL.revokeObjectURL(url);

                Swal.fire(
                    'Downloaded!',
                    'Your voucher has been downloaded.',
                    'success'
                ).then(() => {
                    location.reload();
                });
            })
            .catch(error => {
                Swal.fire(
                    'Error!',
                    error.message,
                    'error'
                );
            });
        }
    });
});
</script>


@endsection

@extends('layouts.app')

@section('title', 'IT Request Approval')

@section('content')
<style>
    table { border-collapse: collapse !important; padding: 0px !important; border: none !important; border-bottom-width: 0px !important; mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
    table td { border-collapse: collapse; text-decoration: none; }
    body { margin: 0px; padding: 0px; background-color: #f9f9f9; }
    .ExternalClass * { line-height: 100%; }

</style>

<div class="page-content">
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">IT Request Approval</a></li>
            <li class="breadcrumb-item active" aria-current="page">Approval</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-8 grid-margin stretch-card mx-auto">
              <div class="table-responsive">
            <table bgcolor="#f9f9f9" cellpadding="0" cellspacing="0" align="center">
                <tr>
                    <td align="center">
                        <table class="main" width="800" bgcolor="#ffffff" cellpadding="0" cellspacing="0">
                            <tr>
                                <td align="center" style="padding: 30px 0; border-bottom: 1px solid #eee;background:#009fe3;">
                                    <table width="600" align="center">
                                        <tr>
                                            <td align="center">
                                                <a href="#">
                                                    <img src="{{ asset('img/sanden-logo-white.png') }}" alt="Sanden Logo" width="90" height="90">
                                                </a>
                                                <p style="color:white; font-size:smaller;">
                                                    105 Makiling Drive, Carmelray Industrial Park II Km 54 National Hi-way,
                                                    Brgy. Milagrosa Calamba City, 4027 Laguna Tel. No. :(049) 554-9970 up
                                                    to 79 Manila Line: (02) 8-898- 5110
                                                </p>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                          <tr>
    <td align="center" style="padding: 60px 0;">
        <h3 style="margin: 0; text-transform: uppercase; font-size: 18px; color: #222;">Sanden</h3>
        <h1 style="margin: 15px 0 20px; text-transform: uppercase; font-size: 30px; color: #222;">Intranet</h1>
        <hr style="width: 40px; border: 1px solid #2fb28c; margin: 0 auto;">

        <table border="1" cellpadding="8" cellspacing="0" width="95%" 
               style="border-collapse: collapse; font-family: Arial, sans-serif; font-size: 14px; color: #333;">
            <tr>
                <td colspan="2" align="center" style="background-color: #ffffff;">
                    <h4>IT Request Approval Form</h4>
                </td>
            </tr>

            {{-- Request Info --}}
           {{-- Common Fields --}}
<tr>
    <td style="font-weight: bold; background-color: #f9f9f9;">Requestor Name</td>
    <td>{{ $itRequest->requestor_name ?? 'N/A' }}</td>
</tr>
<tr>
    <td style="font-weight: bold; background-color: #f9f9f9;">Reference No</td>
    <td>{{ $itRequest->reference_no ?? 'N/A' }}</td>
</tr>
<tr>
    <td style="font-weight: bold; background-color: #f9f9f9;">Department</td>
    <td>{{ $itRequest->department ?? 'N/A' }}</td>
</tr>
<tr>
    <td style="font-weight: bold; background-color: #f9f9f9;">Date Request</td>
    <td>{{ $itRequest->created_at ? $itRequest->created_at->format('Y-m-d') : 'N/A' }}</td>
</tr>

{{-- Conditional Fields --}}
@if($itRequest->type_request === 'Repair_Request')
    <tr>
        <td style="font-weight: bold; background-color: #f9f9f9;">Issue</td>
        <td>{{ $itRequest->issue ?? 'N/A' }}</td>
    </tr>
@elseif($itRequest->type_request === 'Borrow_Item')
    <tr>
        <td style="font-weight: bold; background-color: #f9f9f9;">Item Name</td>
        <td>{{ $itRequest->item_name ?? 'N/A' }}</td>
    </tr>
    <tr>
        <td style="font-weight: bold; background-color: #f9f9f9;">Date Needed</td>
        <td>{{ $itRequest->date_needed ?? 'N/A' }}</td>
    </tr>
    <tr>
        <td style="font-weight: bold; background-color: #f9f9f9;">Planned Return Date</td>
        <td>{{ $itRequest->plan_return_date ?? 'N/A' }}</td>
    </tr>
@elseif($itRequest->type_request === 'Intranet_Request')
    <tr>
        <td style="font-weight: bold; background-color: #f9f9f9;">Type</td>
        <td>{{ $itRequest->intranet_request_type ?? 'N/A' }}</td>
    </tr>
@endif

{{-- Common Fields After Condition --}}
<tr>
    <td style="font-weight: bold; background-color: #f9f9f9;">Priority</td>
    <td>{{ ucfirst($itRequest->priority ?? 'N/A') }}</td>
</tr>
<tr>
    <td style="font-weight: bold; background-color: #f9f9f9;">Type of Request</td>
    <td>{{ $itRequest->type_request ?? 'N/A' }}</td>
</tr>
<tr>
    <td style="font-weight: bold; background-color: #f9f9f9;">Description</td>
    <td>{{ $itRequest->description_of_request ?? 'N/A' }}</td>
</tr>


            {{-- Approvals --}}

            @if($itRequest->approvals->count() > 0)
                <tr>
                    <td colspan="2" align="center" style="background-color: #ffffff;">
                        <h4>Approval Status</h4>
                    </td>
                </tr>

                <tr>
                    <td colspan="2" style="padding: 0;">
                        <table border="1" cellpadding="6" cellspacing="0" width="100%" 
                               style="border-collapse: collapse; font-size: 13px;">
                            <tr style="background-color: #23082e; font-weight: bold; color:rgb(255, 255, 255);text-align:center;">
                                <td>Name</td>
                                <td>Role</td>
                                <td>Email</td>
                                <td>Status</td>
                                <td>Remarks</td>
                                <td>Date</td>
                            </tr>
                            @foreach ($itRequest->approvals as $approval)
                                <tr style="text-align:center;">
                                    <td>{{ $approval->approved_by ?? '-' }}</td>
                                    <td>{{ $approval->role ?? '-' }}</td>
                                    <td>{{ $approval->approver_email ?? '-' }}</td>
                                   <td>
    @if($approval->status)
      @php
    switch ($approval->status) {
        case 'Approved':
            $badgeClass = 'badge bg-success text-white';
            break;
        case 'Pending':
            $badgeClass = 'badge bg-warning text-white'; 
            break;
        case 'Disapproved':
            $badgeClass = 'badge bg-danger text-white';
            break;
        default:
            $badgeClass = 'badge bg-secondary text-white';
    }
@endphp


        <span class="{{ $badgeClass }}">
            {{ strtoupper($approval->status) }}
        </span>
    @else
        -
    @endif
</td>

                                   <td>
    <div style="max-width: 150px; max-height: 200px; overflow: auto;">
        {{ $approval->remarks ?? '-' }}
    </div>
</td>
                             <td>{{ $approval->created_at ? $approval->created_at->format('Y-m-d H:i:s') : '-' }}</td>

                                </tr>
                            @endforeach
                        </table>
                    </td>
                </tr>
            @endif

<tr>
    <td colspan="2" style="padding: 0;">
        <table class="table table-bordered" style="width:100%; border-collapse: collapse;">
            <thead style="background-color: #23082e; color: #fff;">
                <tr>
                    <th style="background-color: #23082e; font-weight: bold; color:rgb(255, 255, 255)">Filename</th>
                    <th style="background-color: #23082e; font-weight: bold; color:rgb(255, 255, 255);text-align:center;">Action</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $attachments = [];
                    if (!empty($itRequest->attachment_name)) {
                        $attachments = json_decode($itRequest->attachment_name, true);
                    }
                @endphp

                @forelse($attachments as $file)
                    <tr>
                        <td style="padding: 8px;">{{ $file }}</td>
                        <td style="padding: 8px; text-align:center;">
                            <a href="{{ route('it_request.download', ['filename' => $file]) }}" target="_blank"
                               style="background-color:#2fb28c; color:white; padding:5px 10px; border-radius:4px; text-decoration:none; display:inline-flex; align-items:center; gap:6px;">
                                <i class="fa-solid fa-download"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" style="text-align:center; color:#777; padding:8px;">No attachments found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </td>
</tr>
{{-- Approval Form --}}
@if($canApprove)


<hr>

<tr id="approvalRow">
    <td colspan="2" align="center" style="padding: 20px;">
        <div class="form-group">
            <div class="row align-items-center mb-3">
                <div class="col-md-2 text-md-end">
                    <label for="status" class="col-form-label">Action</label>
                </div>
                <div class="col-md-10">
                    <select class="form-control" id="status" name="status">
                        <option disabled selected>Choose your action</option>
                        

                        {{-- ✅ Only MIS users can mark as Completed --}}
                        @if($isMIS && $mis_approval_status == 'Approved')
                        <option value="Completed">Completed</option>
                        @elseif($isMIS && $mis_approval_status == 'Pending')
                        <option value="Approved">Approved</option>
                        <option value="Disapproved">Disapproved</option>
                        <option value="Completed">Completed</option>
                        @else
                        <option value="Approved">Approved</option>
                        <option value="Disapproved">Disapproved</option>
                        @endif
                    </select>
                </div>
            </div>

            {{-- ✅ Only MIS can select or modify Level of Request --}}
            @if($isMIS && $mis_approval_status == 'Pending')
                <div class="row align-items-center mb-3">
                    <div class="col-md-2 text-md-end">
                        <label for="level_request" class="col-form-label">Level of Request</label>
                    </div>
                    <div class="col-md-10">
                        <select class="form-control" id="level_request" name="Level_Request">
                            <option disabled selected>Choose level</option>
                            <option value="Low">Low</option>
                            <option value="Medium">Medium</option>
                            <option value="High">High</option>
                            <option value="Very High">Very High</option>
                        </select>
                    </div>
                </div>
            @endif

            <div class="row align-items-start mb-3">
                <div class="col-md-2 text-md-end">
                    <label for="remarks" class="col-form-label">Remarks</label>
                </div>
                <div class="col-md-10">
                    <textarea name="remarks" id="remarks" class="form-control"></textarea>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 text-end">
                    <button id="submitBtn" class="btn btn-success" type="button" name="submit_actions">
                        Submit
                    </button>
                </div>
            </div>
        </div>
    </td>
</tr>

@else
{{-- 🔒 Read-only / No Action --}}
<tr>
    <td colspan="2" align="center" style="padding: 20px;">
        <div class="alert alert-secondary" 
             style="font-size: 15px; background: #f5f5f5; color: #555; border-radius: 6px; padding: 12px 20px;">
            🔒 Closed Request
        </div>
    </td>
</tr>
@endif


        </table>
    </td>
</tr>



                            <tr>
                                <td bgcolor="#009fe3" style="padding: 50px 0; text-align:center; color:#fff; font-weight:bold;">
                                    mis.scp@sanden-rs.com
                                </td>
                            </tr>
                            <tr>
                                <td bgcolor="#009fe3" style="padding: 35px 0; text-align:center; color:#fff; font-weight:bold;">
                                    &copy; All Rights Reserved. MIS.
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
              </div>
        </div>
    </div>
</div>
<script>
document.getElementById('submitBtn').addEventListener('click', async () => {
    const status = document.getElementById('status').value;
    const levelRequestEl = document.getElementById('level_request');
    const levelRequest = levelRequestEl ? levelRequestEl.value : null;
    const remarks = document.getElementById('remarks').value;

    // Validation: must choose an action
    if (!status) {
        Swal.fire('Please choose an action first.', '', 'warning');
        return;
    }

    // ✅ Only MIS users have the level_request field, so only they must select it
    @if($isMIS && $mis_approval_status == 'Pending')
        if (!levelRequest) {
            Swal.fire('Please select a Level of Request.', '', 'warning');
            return;
        }
    @endif

    Swal.fire({
        title: `Are you sure you want to ${status}?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, proceed',
        cancelButtonText: 'Cancel',
    }).then(async (result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Processing...',
                text: 'Please wait while we update the request.',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            try {
                const response = await fetch("{{ route('itrequest.updateStatus', $itRequest->reference_no) }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({ 
                        status, 
                        remarks, 
                        Level_Request: levelRequest 
                    })
                });

                const data = await response.json();
                Swal.close();

                if (response.ok) {
                    Swal.fire('Success!', data.message, 'success')
                        .then(() => location.reload());
                } else {
                    Swal.fire('Error', data.message || 'Failed to update.', 'error');
                }
            } catch (error) {
                console.error(error);
                Swal.close();
                Swal.fire('Error', 'Something went wrong while processing your request.', 'error');
            }
        }
    });
});
</script>


@endsection

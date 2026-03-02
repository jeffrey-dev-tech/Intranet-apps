@extends('layouts.app')

@section('title', 'Travel Request Approval')

@section('content')
<style>
    table {
        border-collapse: collapse !important;
        padding: 0px !important;
        border: none !important;
        border-bottom-width: 0px !important;

    }

    table td {
        border-collapse: collapse;
        text-decoration: none;
    }

    body {
        margin: 0px;
        padding: 0px;
        background-color: #f9f9f9;
    }

    .ExternalClass * {
        line-height: 100%;
    }
</style>

<div class="page-content">
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Travel Request Approval</a></li>
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
                                    <td align="center" style="padding: 30px 0; border-bottom: 1px solid #eee;background:#342e68;">
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
                                        <hr style="width: 40px; border: 1px solid #10b759; margin: 0 auto;">

                                        <table border="1" cellpadding="8" cellspacing="0" width="95%"
                                            style="border-collapse: collapse; font-family: Arial, sans-serif; font-size: 14px; color: #333;">
                                            <tr>
                                                <td colspan="2" align="center" style="background-color: #ffffff;">
                                                    <h4>Travel Request Approval Form</h4>
                                                </td>
                                            </tr>

                                                 <tr>
                                                <td style="font-weight: bold; background-color: #f9f9f9;">TRF NO.</td>
                                                <td>{{$travelData['trf_no']}}</td>
                                            </tr>
                                            <tr>
                                                <td style="font-weight: bold; background-color: #f9f9f9;">TYPE OF REQUEST</td>
                                                <td>{{$travelData['request_type']}}</td>
                                            </tr>

                                        <tr>
                                            <td style="font-weight: bold; background-color: #f9f9f9;">TRAVEL DATES</td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($travelData['travel_date_from'])->format('M-d-Y h:i A') }}
                                                &nbsp;–&nbsp;
                                                {{ \Carbon\Carbon::parse($travelData['travel_date_to'])->format('M-d-Y h:i A') }}
                                            </td>
                                        </tr>

                                                <tr>
                                                <td style="font-weight: bold; background-color: #f9f9f9;">DESTINATION</td>
                                                <td><div style="width: 100%; max-height: 70px; overflow-y: auto; padding: 4px; word-wrap: break-word; white-space: normal;">
                                                    {{$travelData['destination']}}
                                                </div></td>
                                            </tr>
                                          <tr>
                                                <td style="font-weight: bold; background-color: #f9f9f9;">PURPOSE</td>
                                                <td><div style="width: 100%; max-height: 70px; overflow-y: auto; padding: 4px; word-wrap: break-word; white-space: normal;">
                                                    {{$travelData['purpose']}}
                                                </div></td>
                                            </tr>
                                             <tr>
                                                <td style="font-weight: bold; background-color: #f9f9f9;">ADDITIONAL REQUEST</td>
                                                <td><div style="width: 100%; max-height: 70px; overflow-y: auto; padding: 4px; word-wrap: break-word; white-space: normal;">
                                                    {{$travelData['additional_request']}}
                                                </div></td>
                                            </tr>

                                         @if ($travelData['request_type'] == 'Air Travel')
                                          <tr>
                                                <td style="font-weight: bold; background-color: #f9f9f9;">ACCOMMODATION</td>
                                                <td>{{ $travelData['accommodation'] == 1 ? 'Yes' : 'No' }}
                                                </td>
                                            </tr>
                                        <tr>
                                            <td style="font-weight: bold; background-color: #f9f9f9;">DEPARTURE & RETURN</td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($travelData['preferred_time_departure'])->format('M-d-Y h:i A') }}
                                                &nbsp;–&nbsp;
                                                {{ \Carbon\Carbon::parse($travelData['preferred_time_return'])->format('M-d-Y h:i A') }}
                                            </td>
                                        </tr>
                                            @elseif($travelData['request_type'] == 'Land Transport')

                                            @else
                                            <tr>        
                                                <td style="font-weight: bold; background-color: #f9f9f9;">No Data</td>
                                                <td>No Data Available</td>
                                            </tr>

                                            @endif
                                             
<tr>
    <td colspan="2" style="padding: 0;">
        <table border="1" cellpadding="6" cellspacing="0" width="100%" style="table-layout: fixed; border-collapse: collapse;">
            <thead style="background-color: #342e68; color: #fff;">
                <tr> 
                    <th style="width: 80px;">Approval Level</th>
                    <th style="width: 120px;">Approver Name</th>
                    <th style="width: 80px;">Status</th>
                    <th style="width: 150px;">Remarks</th> <!-- set fixed width -->
                    <th style="width: 120px;">Date Created</th>
                </tr>
            </thead>
            <tbody style="font-size: smaller">
                @foreach($travelData->approvals as $approvers)
                    <tr>
                        <td>{{ $approvers->approval_level ?? 'Unknown' }}</td>
                        <td>{{ $approvers->user->name ?? 'Unknown' }}</td>
                        <td>{{ $approvers->status ?? 'Unknown' }}</td>
                        <td style="padding: 0;">
                            <div style="width: 100%; max-height: 90px; overflow-y: auto; padding: 4px; word-wrap: break-word; white-space: normal;">
                                {{ $approvers->remarks ?? 'NULL' }}
                            </div>
                        </td>
                       <td> {{ $approvers->updated_at ? \Carbon\Carbon::parse($approvers->updated_at)->format('M-d-Y h:i A') : 'NULL' }}</td>

                    </tr>
                @endforeach
            </tbody>
        </table>
    </td>
</tr>
@php
    $attachments = $travelData->attachments ?? [];
@endphp

@if(!empty($attachments))
<tr>
     <td colspan="2" style="padding: 0;">
    <table border="1" cellpadding="8" cellspacing="0" width="100%" style="border-collapse: collapse; margin-top:10px;">
        <thead>
            <tr style="background-color:#342e68; color:white;">
                <th>Attachment of Requestor</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($attachments as $file)
                <tr>
                    <td style="padding: 8px;">{{ $file }}</td>
                    <td style="padding: 8px; text-align:center;">
                        <a href="{{ route('travel.download', ['filename' => $file]) }}" target="_blank"
                           style="background-color:#2fb28c; color:white; padding:5px 10px; border-radius:4px; text-decoration:none; display:inline-flex; align-items:center; gap:6px;">
                            <i class="fa-solid fa-download"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
     </td>
    </tr>
@else
    <p>No Requestor attachments available.</p>
@endif
@php
    $attachments = $travelData->attachments_admin ?? [];
@endphp

@if(!empty($attachments))
<tr>
     <td colspan="2" style="padding: 0;">
    <table border="1" cellpadding="8" cellspacing="0" width="100%" style="border-collapse: collapse; margin-top:10px;">
        <thead>
            <tr style="background-color:#342e68; color:white;">
                <th>Admin Attachment</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($attachments as $file)
                <tr>
                    <td style="padding: 8px;">{{ $file }}</td>
                    <td style="padding: 8px; text-align:center;">
                        <a href="{{ route('travel.download', ['filename' => $file]) }}" target="_blank"
                           style="background-color:#2fb28c; color:white; padding:5px 10px; border-radius:4px; text-decoration:none; display:inline-flex; align-items:center; gap:6px;">
                            <i class="fa-solid fa-download"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
     </td>
    </tr>
@else
<tr>
     <td colspan="2" style="padding: 0;">
         <table border="1" cellpadding="8" cellspacing="0" width="100%" style="border-collapse: collapse; margin-top:10px;">
        <thead>
            <tr style="background-color:#342e68; color:white;">
                <th>Admin Attachment</th>
            </tr>
        </thead>
            <tbody>
                <tr>
                    <td style="padding: 8px;">    <p>No admin attachments available.</p></td></tr>

           </tbody>
    </table>
     </td>
    </tr>
@endif




@if($canAct && !$hasFinalAction)
<tr id="approvalRow">
    <td colspan="2">
        <form id="approvalForm" data-trfno="{{ $travelData->trf_no }}" enctype="multipart/form-data">
            <div class="form-group">
                <!-- Action select -->
                <div class="row align-items-center mb-3">
                    <div class="col-md-2 text-md-end">
                        <label for="status" class="col-form-label">Action</label>
                    </div>
                    <div class="col-md-10">
                 <select class="form-control" id="status" name="status" required>
    <option disabled selected>Choose your action</option>

    @if($isApprover && !$isAdmin)
        <option value="approved">Approved</option>
        <option value="disapproved">Disapproved</option>
    @endif

    @if($isAdmin)
        <option value="received">Received</option>
         <option value="reject">Reject</option>
    @endif
</select>
                    </div>
                </div>
     
                <!-- Remarks -->
                <div class="row align-items-start mb-3">
                    <div class="col-md-2 text-md-end">
                        <label for="remarks" class="col-form-label">Remarks</label>
                    </div>
                    <div class="col-md-10">
                        <textarea name="remarks" id="remarks" class="form-control"></textarea>
                    </div>
                </div>

                <!-- Admin attachments -->
                @if($isAdmin && !$hasFinalAction)
                <div class="row align-items-start mb-3">
                    <div class="col-md-2 text-md-end">
                        <label class="col-form-label">Attachments</label>
                    </div>
                    <div class="col-md-10">
                        <input type="file" name="attachments[]" multiple accept=".jpeg,.jpg,.png,.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx" required>
                    </div>
                </div>
                @endif

                <!-- Submit -->
                <div class="row">
                    <div class="col-md-12 text-end">
                        <button id="submitBtn" class="btn btn-success" type="button">Submit</button>
                    </div>
                </div>
            </div>
        </form>
    </td>
</tr>

@endif




                                        </table>
                                    </td>
                                </tr>



                                <tr>
                                    <td bgcolor="#342e68" style="padding: 50px 0; text-align:center; color:#fff; font-weight:bold;">
                                        mis.scp@sanden-rs.com
                                    </td>
                                </tr>
                                <tr>
                                    <td bgcolor="#342e68" style="padding: 35px 0; text-align:center; color:#fff; font-weight:bold;">
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
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('approvalForm');
    if (!form) return;

    const submitBtn = document.getElementById('submitBtn');

    submitBtn.addEventListener('click', async function() {
        // Clear previous errors
        form.querySelectorAll('.invalid-feedback').forEach(e => e.remove());
        form.querySelectorAll('.is-invalid').forEach(e => e.classList.remove('is-invalid'));

        const formData = new FormData(form);

        // Confirm action
        const result = await Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to submit this action?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, submit!',
            cancelButtonText: 'Cancel'
        });

        if (!result.isConfirmed) return;

        Swal.fire({
            title: 'Submitting...',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        try {
            const trfNo = form.dataset.trfno;
            const res = await fetch(`/update-status/${trfNo}`, {
                method: 'POST',
                headers: {'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                body: formData
            });

            const data = await res.json();
            Swal.close();

            if (!res.ok) {
                if (res.status === 422 && data.errors) {
                    // Show validation errors
                    Object.keys(data.errors).forEach(field => {
                        let input = form.querySelector(`[name="${field}"]`);
                        if (input) {
                            input.classList.add('is-invalid');
                            const div = document.createElement('div');
                            div.classList.add('invalid-feedback');
                            div.innerText = data.errors[field][0];
                            input.parentNode.appendChild(div);
                        }
                    });
                }
                return Swal.fire('Error', data.message || 'Submission failed.', 'error');
            }

            Swal.fire('Success', data.message, 'success')
                 .then(() => location.reload());

        } catch (error) {
            Swal.fire('Error', error.message || 'Something went wrong.', 'error');
        }
    });
});
</script>
@endsection
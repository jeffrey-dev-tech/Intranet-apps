@extends('layouts.app')

@section('title', 'Approval')

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
            <li class="breadcrumb-item"><a href="#">Fitness Challenge</a></li>
            <li class="breadcrumb-item active" aria-current="page">Approval</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-8 grid-margin stretch-card mx-auto">
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

                                    <table border="1" cellpadding="8" cellspacing="0" width="95%" style="border-collapse: collapse; font-family: Arial, sans-serif; font-size: 14px; color: #333;">
                                        <tr>
                                            <td colspan="2" align="center" style="background-color: #ffffff;">
                                                <h2>Wellness Program</h2>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="font-weight: bold; background-color: #f9f9f9;">Team</td>
                                            <td id="teamCell"></td>
                                        </tr>
                                        <tr>
                                            <td style="font-weight: bold; background-color: #f9f9f9;">Activity Name</td>
                                            <td id="activityCell"></td>
                                        </tr>
                                        <tr>
                                            <td style="font-weight: bold; background-color: #f9f9f9;">Level</td>
                                            <td id="levelCell"></td>
                                        </tr>
                                            <tr>
                                            <td style="font-weight: bold; background-color: #f9f9f9;">Name</td>
                                            <td id="submitterName"></td>
                                        </tr>
                                        <tr>
                                            <td style="font-weight: bold; background-color: #f9f9f9;">Progress Value</td>
                                            <td id="progressCell"></td>
                                        </tr>
                                        <tr>
                                            <td style="font-weight: bold; background-color: #f9f9f9;">Other Information</td>
                                            <td id="otherCell"></td>
                                        </tr>
                                        <tr>
    <td style="font-weight: bold; background-color: #f9f9f9;">Log ID</td>
    <td>{{ $log_id }}</td>
</tr>
<tr id="approvalRow" style="display: none;">
    <td colspan="2" align="center" style="padding: 20px;">
        <div class="form-group">
            <div class="row">
                <div class="col-md-12">
                    <select class="form-control" id="status">
                        <option disabled selected>Choose your actions</option>
                        <option value="approved">Approved</option>
                        <option value="disapproved">Disapproved</option>
                    </select>
                </div>
                <div class="col-md-12 mt-4">
                   <button id="submitBtn" class="btn btn-success" type="button" name="submit_actions">Submit</button>
                </div>
            </div>
        </div>
    </td>
</tr>


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


<script>
document.addEventListener('DOMContentLoaded', async () => {
    const logId = "{{ $log_id }}";
    const fetchUrl = "{{ route('activityLog.data', ['log_id' => '__ID__']) }}".replace('__ID__', logId);
    const updateUrl = "{{ route('activityLog.updateStatus') }}"; 

    const approvalRow = document.getElementById('approvalRow');
    const submitBtn = document.getElementById('submitBtn');
    const statusSelect = document.getElementById('status');

    try {
        const res = await fetch(fetchUrl, { headers: { 'Accept': 'application/json' } });
        const data = await res.json();
        const log = data.activity_log[0] || {};

        document.getElementById('teamCell').textContent = log.team_name || 'N/A';
        document.getElementById('activityCell').textContent = log.activity_name || 'N/A';
        document.getElementById('levelCell').textContent = log.level_number || 'N/A';
        document.getElementById('progressCell').textContent = `${log.progress_value || 'N/A'} ${log.unit || ''}`;
        document.getElementById('otherCell').textContent = log.other_informations || 'N/A';
        document.getElementById('submitterName').textContent = log.user_name || 'N/A';

        // Show approval row only for user 69/119 AND pending logs
const userId = @json(auth()->user()->id);

if (approvalRow && (userId === 69 || userId === 119) && log.status === 'pending') {
    approvalRow.style.display = 'table-row';
    console.log('Approval row displayed');
} else {
    console.log('Approval row remains hidden');
}



    } catch (error) {
        console.error('Error loading log data:', error);
        Swal.fire('Error!', 'Failed to load activity log.', 'error');
    }

    if (!submitBtn) return;

    submitBtn.addEventListener('click', async () => {
        const status = statusSelect.value;
        if (!status) {
            Swal.fire('Please select a status!', '', 'warning');
            return;
        }

        const confirm = await Swal.fire({
            title: `Are you sure?`,
            text: `You are about to mark this as "${status}"`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, confirm',
            cancelButtonText: 'Cancel'
        });

        if (!confirm.isConfirmed) return;

        try {
            const response = await fetch(updateUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ log_id: logId, status })
            });

            const result = await response.json();

            if (response.ok && result.success) {
                Swal.fire('Updated!', 'Status has been updated successfully.', 'success')
                    .then(() => location.reload());
            } else {
                throw new Error(result.message || 'Failed to update status');
            }
        } catch (error) {
            console.error('Update failed:', error);
            Swal.fire('Error!', `Failed to update status. ${error.message}`, 'error');
        }
    });
});

</script>


@endsection

@extends('layouts.app')

@section('title', 'Teams')

@section('content')
<div class="page-content">
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Wellness Program</a></li>
            <li class="breadcrumb-item active" aria-current="page">Teams</li>
        </ol>
    </nav>

    <!-- Teams Table -->
    <div class="card">
        <div class="card-body">
            <h4 class="card-title mb-3">Teams List</h4>
            <div class="table-responsive">
                <table id="teamsTable" class="table table-hover table-bordered">
                    <thead style="background: rgb(80, 128, 233); color: white;">
                        <tr>
                            <th style="color: white;">Team Name</th>
                            <th style="color: white;">Activity</th>
                            <th style="color: white;">Status</th>
                            <th style="color: white;">Members</th>
                            <th style="color: white;">Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <hr>

    <!-- Activity Logs Table -->
    <div class="card">
        <div class="card-body">
            <h4 class="card-title mb-3">Activity Submission Logs</h4>
            <div class="row mb-3">
                <div class="col-md-3">
                    <select id="activityFilter" class="form-control">
                        <option value="">All Activities</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select id="teamFilter" class="form-control">
                        <option value="">All Teams</option>
                    </select>
                </div>
            </div>
            <div class="table-responsive">
                <table id="ActivityLogsTable" class="table table-hover table-bordered">
                    <thead style="background: rgb(245, 46, 72); color: white;">
                        <tr>
                            <th style="color: white;">LOG ID</th>
                            <th style="color: white;">Team Name</th>
                            <th style="color: white;">Activity</th>
                              <th style="color: white;">Submission type</th>
                            <th style="color: white;">Level</th>
                            <th style="color: white;">Progress Value</th>
                            <th style="color: white;">Unit</th>
                            <th style="color: white;">Name</th>
                                    <th style="color: white;">Date Submitted</th>
                            <th style="color: white;">Status</th>
                            <th style="color: white;">Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- View Members Modal -->
<div class="modal fade" id="view_modal_members" tabindex="-1" role="dialog" aria-labelledby="viewMembersLabel" aria-hidden="true">
<div class="modal-dialog modal-xl modal-dialog-centered" role="document">

    <div class="modal-content shadow-lg border-0">

      <!-- Modal Header -->
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title d-flex align-items-center" id="viewMembersLabel">
          <i class="fas fa-users mr-2"></i> Team Members
        </h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <!-- Modal Body -->
      <div class="modal-body">
        <div class="card mb-3 shadow-sm">
          <div class="card-body">
            <h5 id="teamName" class="font-weight-bold text-primary"></h5>
            <p><strong><i class="fas fa-running"></i> Activity:</strong> <span id="activityName"></span></p>
            <p><strong><i class="fas fa-info-circle"></i> Status:</strong> 
              <span id="teamStatus" class="badge badge-secondary"></span>
            </p>
          </div>
        </div>

        <div class="table-responsive">
          <table class="table table-hover table-bordered text-center">
            <thead class="thead-dark">
              <tr>
                <th><i class="fas fa-user"></i> Name</th>
                <th><i class="fas fa-battery-half"></i> Progress</th>
                <th>Required</th>
                <th><i class="fas fa-exclamation-triangle"></i>Exceed</th>
                      <th>Unit</th>
                <th><i class="fas fa-user-tag"></i> Role</th>
                <th><i class="fas fa-clock"></i> Joined At</th>
              </tr>
            </thead>
            <tbody id="teamMembersBody">
              <tr>
                <td colspan="6">
                  <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Loading...</span>
                  </div>
                  <p class="mt-2">Fetching members...</p>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Modal Footer -->
<div class="modal-footer" style="
    display: flex; 
    flex-direction: column; 
    align-items: center; 
    justify-content: center; 
    text-align: center;
  background:#fefefe;
    padding: 20px;

    color: black;
">
    <strong>Team Progress:</strong>
    <img src="{{asset('img/running-3.gif')}}" alt="" height="250px;">
    <span id="teamProgressPercentage"></span>
</div>


    </div>
  </div>
</div>



<script>
document.addEventListener('DOMContentLoaded', async () => {
    const teamsTableBody = document.querySelector('#teamsTable tbody');
    const logsTableBody = document.querySelector('#ActivityLogsTable tbody');
    const activityFilter = document.getElementById('activityFilter');
    const teamFilter = document.getElementById('teamFilter');
    let logsTable;

    // Fetch Activities
    async function fetchActivities() {
        try {
            const res = await fetch("{{ route('teams.activitiesList') }}");
            const activities = await res.json();
            (activities || []).forEach(activity => {
                const option = document.createElement('option');
                option.value = activity.name;
                option.textContent = activity.name;
                activityFilter.appendChild(option);
            });
        } catch (error) {
            console.error('Error fetching activities:', error);
        }
    }

    // Fetch Teams
async function fetchTeams() {
    try {
        const res = await fetch("{{ route('teams.list') }}");
        const data = await res.json();
        teamsTableBody.innerHTML = '';

        if (!data.teams?.length) {
            teamsTableBody.innerHTML = `<tr><td colspan="6" class="text-center">No teams found.</td></tr>`;
            return;
        }

    data.teams.forEach(team => {
    teamsTableBody.innerHTML += `
        <tr style="text-align:center;">
            <td>${team.name.toUpperCase()}</td>
            <td>${team.activity_name ?? 'N/A'}</td>
            <td>
                <span class="badge ${
                    team.status === 'completed' ? 'badge-success' :
                    team.status === 'cancelled' ? 'badge-danger' :
                    team.status === 'pending' ? 'badge-warning' :
                    'badge-secondary'
                }">${team.status}</span>
            </td>
            <td>${team.users_count}</td>
            <td>
                <button 
                    class='btn btn-success' 
                    data-toggle="modal" 
                    data-target="#view_modal_members" 
                    data-team-id="${team.id}">
                    View
                </button>
                ${
                    {{ auth()->user()->id }} === 69 
                    ? `<button 
                        class='btn btn-danger btn-delete-team' 
                        data-team-id="${team.id}">
                        Delete
                       </button>` 
                    : ''
                }
            </td>
        </tr>
    `;
});


        // SweetAlert Delete
        document.querySelectorAll('.btn-delete-team').forEach(btn => {
            btn.addEventListener('click', async (e) => {
                const teamId = e.target.dataset.teamId;

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!',
                    reverseButtons: true
                }).then(async (result) => {
                    if (result.isConfirmed) {
                        try {
                            const deleteRes = await fetch(`{{ route('teams.delete', '') }}/${teamId}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Content-Type': 'application/json'
                                }
                            });

                            if (deleteRes.ok) {
                                Swal.fire(
                                    'Deleted!',
                                    'Team has been deleted.',
                                    'success'
                                );
                                fetchTeams(); // Refresh the table
                                fetchActivityLogs();
                            } else {
                                Swal.fire(
                                    'Error!',
                                    'Failed to delete the team.',
                                    'error'
                                );
                            }
                        } catch (err) {
                            console.error(err);
                            Swal.fire(
                                'Error!',
                                'An error occurred while deleting the team.',
                                'error'
                            );
                        }
                    }
                });
            });
        });

        // Populate Team Filter
        const distinctTeams = [...new Set(data.teams.map(t => t.name))];
        teamFilter.innerHTML = `<option value="">All Teams</option>`;
        distinctTeams.forEach(name => {
            const option = document.createElement('option');
            option.value = name;
            option.textContent = name;
            teamFilter.appendChild(option);
        });

        if ($.fn.DataTable.isDataTable('#teamsTable')) $('#teamsTable').DataTable().destroy();
        $('#teamsTable').DataTable();

    } catch (error) {
        console.error('Error fetching teams:', error);
        teamsTableBody.innerHTML = `<tr><td colspan="6" class="text-center text-danger">Failed to load teams.</td></tr>`;
    }
}


    // Fetch Activity Logs
    async function fetchActivityLogs() {
        try {
            const res = await fetch("{{ route('teams.activity_logs') }}");
            const data = await res.json();
            logsTableBody.innerHTML = '';
            const logs = data.activity_log || [];
            if (!logs.length) {
                logsTableBody.innerHTML = `<tr><td colspan="9" class="text-center">No activity logs found.</td></tr>`;
                return;
            }

            const approvalUrlTemplate = "{{ route('approvalForm.activity', ['log_id' => '__id__']) }}";

logs.forEach(row => {
    const viewUrl = approvalUrlTemplate.replace('__id__', row.log_id);

    // Format created_at as "JAN-26-26, 02:36 PM"
    let createdAtFormatted = 'N/A';
    if (row.created_at) {
        const date = new Date(row.created_at);
        const monthAbbr = date.toLocaleString('en-US', { month: 'short' }).toUpperCase(); // e.g., JAN
        const day = String(date.getDate()).padStart(2, '0'); // 01, 02, 26 etc
        const year = String(date.getFullYear()).slice(-2); // last 2 digits of year
        const hour = date.getHours();
        const minute = String(date.getMinutes()).padStart(2, '0');

        // Convert 24h -> 12h format
        const hour12 = hour % 12 || 12; // 0 -> 12
        const ampm = hour < 12 ? 'AM' : 'PM';

        createdAtFormatted = `${monthAbbr}-${day}-${year}, ${hour12}:${minute} ${ampm}`;
    }

    logsTableBody.innerHTML += `
        <tr style="text-align:center;">
            <td>${row.log_id}</td>
            <td>${row.team_name ?? 'N/A'}</td>
            <td>${row.activity_name ?? 'N/A'}</td>
            <td>${row.submission_type ?? 'N/A'}</td>
            <td>${row.level_number ?? 'N/A'}</td>
      <td>${row.progress_value != null ? Number(row.progress_value).toFixed(2) : 'N/A'}</td>
            <td>${row.unit ?? 'N/A'}</td>
            <td>${row.user_name ?? 'N/A'}</td>
            <td>${createdAtFormatted}</td>
            <td>
                <span class="badge ${
                    row.status === 'approved' ? 'badge-success' :
                    row.status === 'disapproved' ? 'badge-danger' :
                    row.status === 'cancelled' ? 'badge-warning' :
                    'badge-secondary'
                }">${row.status}</span>
            </td>
            <td>
                <a class="btn btn-sm btn-primary" href="${viewUrl}" target="_blank">View</a>
            </td>
        </tr>
    `;
});


            if (logsTable) logsTable.destroy();
            logsTable = $('#ActivityLogsTable').DataTable({ search: { regex: true } });
        } catch (error) {
            console.error('Error fetching activity logs:', error);
            logsTableBody.innerHTML = `<tr><td colspan="9" class="text-center text-danger">Failed to load activity logs.</td></tr>`;
        }
    }

    // Filters
    activityFilter.addEventListener('change', () => {
        const val = activityFilter.value;
        logsTable.column(2).search(val ? `^${val}$` : '', true, false).draw();
    });
    teamFilter.addEventListener('change', () => {
        const val = teamFilter.value;
        logsTable.column(1).search(val ? `^${val}$` : '', true, false).draw();
    });
$('#view_modal_members').on('show.bs.modal', async function (event) {
    const button = $(event.relatedTarget);
    const teamId = button.data('team-id');

    const membersBody = $('#teamMembersBody');
    membersBody.html(`<tr><td colspan="7">Loading...</td></tr>`);

    const url = "{{ route('teams.members', ['team' => ':id']) }}".replace(':id', teamId);

    try {
        const res = await fetch(url);
        if (!res.ok) throw new Error(`HTTP error! Status: ${res.status}`);
        const response = await res.json();
        if (response.error) throw new Error(response.error);

        $('#teamName').text(`Team: ${response.team.name}`);
        $('#activityName').text(response.team.activity_name ?? 'N/A');
        $('#teamStatus').text(response.team.status ?? 'N/A');

        // Update team progress percentage (approved only)
        const progress = response.team.percentage_of_required ?? 0;
        $('#teamProgressPercentage').text(`${progress.toFixed(2)}%`);

        let rows = '';
        const perMemberRequired = response.team.per_member_required ?? 0;

        if (response.members && response.members.length > 0) {
            response.members.forEach(member => {
                const approved = parseFloat(member.approved_progress_sum ?? 0).toFixed(2);
                const exceed = parseFloat(member.progress_value_exceed ?? 0).toFixed(2);

                rows += `
                    <tr>
                        <td>${member.name}</td>
                        <td>${approved}</td>
                        <td>${perMemberRequired.toFixed(2)}</td>
                        <td class="${exceed > 0 ? 'text-success fw-bold' : 'text-muted'}">
                            ${exceed}
                        </td> <!-- ✅ Highlight Over-exceed -->
                        <td>${response.unit ?? ''}</td>
                        <td>${member.role ?? ''}</td>
                        <td>${member.joined_at ?? ''}</td>
                    </tr>
                `;
            });
        } else {
            rows = `<tr><td colspan="7">No members found.</td></tr>`;
        }

        membersBody.html(rows);
    } catch (error) {
        console.error('Failed to fetch members:', error);
        membersBody.html(`<tr><td colspan="7" class="text-danger">Failed to load members: ${error.message}</td></tr>`);
    }
});


    // Load all data
    await fetchActivities();
    await fetchTeams();
    await fetchActivityLogs();
});
</script>
@endsection

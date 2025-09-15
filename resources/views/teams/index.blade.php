@extends('layouts.app')

@section('title', 'Teams')

@section('content')
<div class="page-content">
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Teams</li>
        </ol>
    </nav>

    <!-- Teams Table -->
    <div class="card">
        <div class="card-body">
            <h4 class="card-title mb-3">Teams List</h4>
            <div class="table-responsive">
                <table id="teamsTable" class="table table-hover table-bordered">
                    <thead style="background: rgb(80, 128, 233);color: white;">
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
                 <thead style="background: rgb(245, 46, 72);">
                        <tr>
                            <th  style="color: white;">LOG ID</th>
                            <th  style="color: white;">Team Name</th>
                            <th  style="color: white;">Activity</th>
                            <th  style="color: white;">Level</th>
                            <th  style="color: white;">Progress Value</th>
                            <th  style="color: white;">Unit</th>
                            <th  style="color: white;">Name</th>
                            <th  style="color: white;">Status</th>
                            <th  style="color: white;">Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
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
    let logsTable; // store DataTable instance

    async function fetchActivities() {
        try {
            const res = await fetch(`{{ route('teams.activitiesList') }}`);
            let activities = await res.json();
            if (!Array.isArray(activities)) activities = [];

            activities.forEach(activity => {
                const option = document.createElement('option');
                option.value = activity.name;
                option.textContent = activity.name;
                activityFilter.appendChild(option);
            });
        } catch (error) {
            console.error('Error fetching activities:', error);
        }
    }

    async function fetchTeams() {
        try {
            const res = await fetch(`{{ route('teams.list') }}`);
            const data = await res.json();

            teamsTableBody.innerHTML = '';
            if (!data.teams?.length) {
                teamsTableBody.innerHTML = `<tr><td colspan="5" class="text-center">No teams found.</td></tr>`;
                return;
            }

            data.teams.forEach(team => {
                teamsTableBody.innerHTML += `
                    <tr style="text-align:center;">
                        <td>${team.name}</td>
                        <td>${team.activity_name ?? 'N/A'}</td>
                        <td>   <span class="badge ${
        team.status === 'completed' ? 'badge-success' :
        team.status === 'cancelled' ? 'badge-danger' :
        team.status === 'pending' ? 'badge-warning' :
        'badge-secondary'
    }">
        ${team.status}
    </span></td>
                        <td>${team.users_count}</td>
                        <td><button class='btn btn-success'>View</button></td>
                    </tr>
                `;
            });

            // Populate Team Filter (distinct)
            const distinctTeams = [...new Set(data.teams.map(t => t.name))];
            teamFilter.innerHTML = `<option value="">All Teams</option>`;
            distinctTeams.forEach(name => {
                const option = document.createElement('option');
                option.value = name;
                option.textContent = name;
                teamFilter.appendChild(option);
            });

            if ($.fn.DataTable.isDataTable('#teamsTable')) {
                $('#teamsTable').DataTable().destroy();
            }
            $('#teamsTable').DataTable();
        } catch (error) {
            console.error('Error fetching teams:', error);
            teamsTableBody.innerHTML = `<tr><td colspan="5" class="text-center text-danger">Failed to load teams.</td></tr>`;
        }
    }

    async function fetchActivityLogs() {
        try {
            const res = await fetch(`{{ route('teams.activity_logs') }}`);
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

    logsTableBody.innerHTML += `
   <tr style="text-align:center;">
            <td>${row.log_id}</td>
            <td>${row.team_name ?? 'N/A'}</td>
            <td>${row.activity_name ?? 'N/A'}</td>
            <td>${row.level_number ?? 'N/A'}</td>
            <td>${row.progress_value ?? 'N/A'}</td>
            <td>${row.unit ?? 'N/A'}</td>
            <td>${row.user_name ?? 'N/A'}</td>
     <td>
    <span class="badge ${
        row.status === 'approved' ? 'badge-success' :
        row.status === 'disapproved' ? 'badge-danger' :
        row.status === 'cancelled' ? 'badge-warning' :
        'badge-secondary'
    }">
        ${row.status}
    </span>
</td>

            <td>
                <a class="btn btn-sm btn-primary" href="${viewUrl}" target="_blank" rel="noopener noreferrer">View</a>
            </td>
        </tr>
    `;
});





            if (logsTable) {
                logsTable.destroy();
            }
            logsTable = $('#ActivityLogsTable').DataTable({
                search: { regex: true } // Allow regex for exact search
            });
        } catch (error) {
            console.error('Error fetching activity logs:', error);
            logsTableBody.innerHTML = `<tr><td colspan="9" class="text-center text-danger">Failed to load activity logs.</td></tr>`;
        }
    }

    // 🔹 Apply Exact Search on Dropdown Change
    activityFilter.addEventListener('change', () => {
        const val = activityFilter.value;
        logsTable.column(2).search(val ? `^${val}$` : '', true, false).draw();
    });

    teamFilter.addEventListener('change', () => {
        const val = teamFilter.value;
        logsTable.column(1).search(val ? `^${val}$` : '', true, false).draw();
    });

    // 🔹 Load Data
    await fetchActivities();
    await fetchTeams();
    await fetchActivityLogs();
});
</script>

@endsection

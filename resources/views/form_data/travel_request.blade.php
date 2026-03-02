@extends('layouts.app')

@section('title', 'Travel Request Data')

@section('content')
<style>
.sanden-logo {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
}
.sanden-logo img { width: 400px; margin-bottom: 10px; }
.title-form img { width: 70px; margin-bottom: 10px; }
.scrollable-cell {
    max-height: 70px;
    overflow-y: auto;
    word-wrap: break-word;
    overflow-wrap: break-word;
    white-space: normal;
}
.badge { font-size: 0.75rem; }
.nav-tabs .nav-link { cursor: pointer; }
.date-filter { margin-bottom: 15px; display: flex; gap: 10px; align-items: center; }
.date-filter label { font-weight: bold; }
</style>

<div class="page-content">
    <div class="row">
        <div class="col-md-12 stretch-card">
            <div class="card">
                <div class="card-body">

                    {{-- Logo & Title --}}
                    <div class="sanden-logo">
                        <img src="{{ asset('img/Sanden_Logo_SCP2_.png') }}" alt="sanden-logo">
                        <div class="title-form">
                            <h6 class="card-title" style="font-size:25px;">Travel Request Data</h6>
                        </div>
                    </div>

             
{{-- Date & Request Type Filter --}}
<div class="date-filter">
    <label for="fromDate">From:</label>
    <input type="date" id="fromDate" class="form-control">
    
    <label for="toDate">To:</label>
    <input type="date" id="toDate" class="form-control">

    <label for="requestType">Request Type:</label>
    <select id="requestType" class="form-control">
        <option value="">All</option>
        <option value="Air Travel">Air Travel</option>
        <option value="Land Transport">Land Transport</option>
        <option value="Other">Other</option>
    </select>

    <button id="filterBtn" class="btn btn-primary">Filter</button>
    <button id="resetBtn" class="btn btn-secondary">Reset</button>
</div>

                    {{-- Tabs --}}
                    <ul class="nav nav-tabs mt-3" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="pending-tab" data-toggle="tab" href="#pending" role="tab">Pending</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="completed-tab" data-toggle="tab" href="#completed" role="tab">Completed</a>
                        </li>
                    </ul>

                    {{-- Tab content --}}
                    <div class="tab-content mt-3">
                        <div class="tab-pane fade show active" id="pending" role="tabpanel">
                            <div class="table-responsive" id="PendingTravelTbl"></div>
                        </div>
                        <div class="tab-pane fade" id="completed" role="tabpanel">
                            <div class="table-responsive" id="CompletedTravelTbl"></div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('assets/js/jquery.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {

    let travelData = []; // store original data

    function formatDate(dateStr){
        if(!dateStr) return '';
        const d = new Date(dateStr);
        const monthNames = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];
        return `${monthNames[d.getMonth()]} ${String(d.getDate()).padStart(2,'0')}, ${d.getFullYear()}`;
    }

    function renderTravelTable(containerId, tableId, data){
        let html = `<table id="${tableId}" class="table table-bordered table-striped table-hover text-center">
            <thead>
                <tr>
                    <th>TRF No</th>
                    <th>Request Type</th>
                    <th>Requested By</th>
                    <th>Destination</th>
                    <th>Travel Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>`;

        data.forEach(row => {
            let statusClass = row.status.toLowerCase() === 'pending' ? 'badge-warning' : 'badge-success';
            html += `<tr>
                <td>${row.trf_no}</td>
                <td><span class="badge ${statusClass}">${row.request_type}</span></td>
                <td>${row.user?.name ?? 'Unknown'}</td>
                <td><div class="scrollable-cell">${row.destination ?? 'N/A'}</div></td>
                <td>${formatDate(row.travel_date_from)} – ${formatDate(row.travel_date_to)}</td>
                <td><span class="badge ${statusClass}">${row.status}</span></td>
                <td><a href="/TravelRequest/ApprovalForm/${row.trf_no}" class="btn btn-sm btn-primary">View</a></td>
            </tr>`;
        });

        html += `</tbody></table>`;
        document.getElementById(containerId).innerHTML = html;
        $(`#${tableId}`).DataTable({
            pageLength: 10,
            lengthMenu: [10,25,50,100],
            responsive: true
        });
    }
function filterByDate(data, from, to) {
    if (!from && !to) return data;

    // Parse dates as YYYY-MM-DD to avoid timezone issues
    const fromDate = from ? new Date(from) : null;
    const toDate = to ? new Date(to) : null;

    return data.filter(row => {
        const start = new Date(row.travel_date_from);
        const end = new Date(row.travel_date_to);

        // Compare using only year/month/day
        const startYMD = new Date(start.getFullYear(), start.getMonth(), start.getDate());
        const endYMD = new Date(end.getFullYear(), end.getMonth(), end.getDate());

        const fromYMD = fromDate ? new Date(fromDate.getFullYear(), fromDate.getMonth(), fromDate.getDate()) : null;
        const toYMD = toDate ? new Date(toDate.getFullYear(), toDate.getMonth(), toDate.getDate()) : null;

        // Overlap check: start <= to AND end >= from
        if (fromYMD && toYMD) {
            return startYMD <= toYMD && endYMD >= fromYMD;
        }
        if (fromYMD) return endYMD >= fromYMD;
        if (toYMD) return startYMD <= toYMD;
        return true;
    });
}



    // Fetch Travel Request Data
    fetch("{{ route('travel.TravelRequestData') }}")
        .then(res => res.json())
        .then(data => {
            travelData = data; // save original
            updateTables();
        })
        .catch(err => console.error('Error fetching travel data:', err));

function updateTables(){
    const from = document.getElementById('fromDate').value;
    const to   = document.getElementById('toDate').value;
    const type = document.getElementById('requestType').value; // new

    let filtered = filterByDate(travelData, from, to);

    // Filter by request type if selected
    if(type) {
        filtered = filtered.filter(d => d.request_type === type);
    }

    renderTravelTable('PendingTravelTbl','PendingTravelTable', filtered.filter(d => d.status.toLowerCase() === 'pending'));
    renderTravelTable('CompletedTravelTbl','CompletedTravelTable', filtered.filter(d => d.status.toLowerCase() === 'completed'));
}

    // Filter button
    document.getElementById('filterBtn').addEventListener('click', updateTables);
    // Reset button
document.getElementById('resetBtn').addEventListener('click', function(){
    document.getElementById('fromDate').value = '';
    document.getElementById('toDate').value = '';
    document.getElementById('requestType').value = '';
    updateTables();
});


});
</script>
@endsection

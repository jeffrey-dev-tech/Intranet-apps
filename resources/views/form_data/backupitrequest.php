@extends('layouts.app')

@section('title', 'IT Request Data')

@section('content')

<div class="page-content">
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Form Data</a></li>
            <li class="breadcrumb-item active" aria-current="page">IT Request</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="aside-body">
                        <ul class="nav nav-tabs mt-3" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="borrow-tab" data-toggle="tab" href="#borrow" role="tab">
                                    <div class="d-flex flex-row flex-lg-column flex-xl-row align-items-center">
                                        <i data-feather="book" class="icon-sm mr-sm-2 mr-lg-0 mr-xl-2 mb-md-1 mb-xl-0"></i>
                                        <p class="d-none d-sm-block">Borrow Request</p>
                                    </div>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="repair_request-tab" data-toggle="tab" href="#repair_request" role="tab">
                                    <div class="d-flex flex-row flex-lg-column flex-xl-row align-items-center">
                                        <i class="fa-solid fa-wrench mr-sm-2 mr-lg-0 mr-xl-2 mb-md-1 mb-xl-0"></i>
                                        <p class="d-none d-sm-block">Repair Request</p>
                                    </div>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="project_request-tab" data-toggle="tab" href="#project_request" role="tab">
                                    <div class="d-flex flex-row flex-lg-column flex-xl-row align-items-center">
                                        <i class="fas fa-project-diagram mr-sm-2 mr-lg-0 mr-xl-2 mb-md-1 mb-xl-0"></i>
                                        <p class="d-none d-sm-block">Project Request</p>
                                    </div>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="Intranet_request-tab" data-toggle="tab" href="#Intranet_request" role="tab">
                                    <div class="d-flex flex-row flex-lg-column flex-xl-row align-items-center">
                                        <i class="fa-solid fa-globe icon-sm mr-sm-2 mr-lg-0 mr-xl-2 mb-md-1 mb-xl-0"></i>
                                        <p class="d-none d-sm-block">Intranet Request</p>
                                    </div>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="purchase_request-tab" data-toggle="tab" href="#purchase_request" role="tab">
                                    <div class="d-flex flex-row flex-lg-column flex-xl-row align-items-center">
                                        <i class="fa-solid fa-cart-shopping icon-sm mr-sm-2 mr-lg-0 mr-xl-2 mb-md-1 mb-xl-0"></i>
                                        <p class="d-none d-sm-block">Purchase Item</p>
                                    </div>
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content mt-3">
                            <div class="tab-pane fade show active" id="borrow">
                                <div class="table-responsive" id="BorrowRequestTbl"></div>
                            </div>
                            <div class="tab-pane fade" id="repair_request">
                                <div class="table-responsive" id="RepairRequestTbl"></div>
                            </div>
                            <div class="tab-pane fade" id="Intranet_request">
                                <div class="table-responsive" id="IntranetRequestTbl"></div>
                            </div>
                            <div class="tab-pane fade" id="project_request">
                                <div class="table-responsive" id="ProjectRequestTbl"></div>
                            </div>
                            <div class="tab-pane fade" id="purchase_request">
                                <div class="table-responsive" id="PurchaseRequestTbl"></div>
                            </div>
                        </div>

                        <!-- Chart -->
                        <div class="mt-5">
                            <canvas id="avgTimeChart" height="100"></canvas>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.table tbody {
    font-size: 0.65rem; /* extra small */
}
.table tbody td {
    padding: 0.2rem 0.4rem;
}
.table thead {
    font-size: 1rem;
}
</style>

<script src="{{ asset('assets/js/jquery.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    function formatDate(dateString) {
        if (!dateString) return '';
        const date = new Date(dateString);
        const monthNames = ["JAN","FEB","MAR","APR","MAY","JUN","JUL","AUG","SEP","OCT","NOV","DEC"];
        const month = monthNames[date.getMonth()];
        const day = String(date.getDate()).padStart(2,'0');
        const year = String(date.getFullYear()).slice(-2);
        let hours = date.getHours();
        const minutes = String(date.getMinutes()).padStart(2,'0');
        const ampm = hours >= 12 ? 'PM' : 'AM';
        hours = hours % 12 || 12;
        return `${month}-${day}-${year} ${String(hours).padStart(2,'0')}:${minutes} ${ampm}`;
    }

    function getTimeInterval(startDateStr, endDateStr) {
        if (!startDateStr || !endDateStr) return '';
        const start = new Date(startDateStr);
        const end = new Date(endDateStr);
        let diffMs = end - start;
        if (diffMs < 0) return '';
        const minutes = Math.floor(diffMs / 60000);
        const hours = Math.floor(minutes / 60);
        const days = Math.floor(hours / 24);
        const months = Math.floor(days / 30);
        const remDays = days % 30;
        const remHours = hours % 24;
        const remMinutes = minutes % 60;
        let parts = [];
        if (months) parts.push(`${months} month${months>1?'s':''}`);
        if (remDays) parts.push(`${remDays} day${remDays>1?'s':''}`);
        if (remHours) parts.push(`${remHours} hour${remHours>1?'s':''}`);
        if (remMinutes) parts.push(`${remMinutes} min${remMinutes>1?'s':''}`);
        return parts.join(', ') || '0 min';
    }

    function toTitleCase(str){
        if(!str) return '';
        return str.toString().trim().split(' ').map(w=> w.charAt(0).toUpperCase()+w.slice(1).toLowerCase()).join(' ');
    }

    function renderTable(containerId, tableId, tableData, columns){
        let tableHtml = `<table id="${tableId}" class="table table-striped table-hover table-bordered text-center"><thead class="thead-dark"><tr>`;
        tableHtml += `<th>#</th>`;
        const hasCompleted = tableData.some(item => item.status === 'Completed');
        columns.forEach(col=>{
            tableHtml += `<th>${col.replace(/_/g,' ').replace(/\b\w/g,l=>l.toUpperCase())}</th>`;
            if(col==='created_at' && hasCompleted){
                tableHtml += `<th>Updated At</th><th>Time Interval</th>`;
            }
        });
        tableHtml += `<th>Actions</th></tr></thead><tbody>`;

        tableData.forEach((item,index)=>{
            tableHtml += `<tr><td>${index+1}</td>`;
            columns.forEach(col=>{
                if(col==='status'){
                    let statusBadge='';
                    switch(item[col]){
                        case 'Pending': statusBadge="<span class='badge badge-warning'>Pending</span>"; break;
                        case 'Approved': statusBadge="<span class='badge badge-primary'>Approved</span>"; break;
                        case 'Completed': statusBadge="<span class='badge badge-success'>Completed</span>"; break;
                        case 'Rejected': statusBadge="<span class='badge badge-danger'>Rejected</span>"; break;
                        default: statusBadge=`<span class='badge badge-secondary'>${item[col]}</span>`;
                    }
                    tableHtml += `<td>${statusBadge}</td>`;
                } else if(col==='created_at'){
                    tableHtml += `<td>${formatDate(item[col])}</td>`;
                    if(hasCompleted){
                        tableHtml += `<td>${item.status==='Completed'?formatDate(item.updated_at):''}</td>`;
                        tableHtml += `<td>${item.status==='Completed'?getTimeInterval(item.created_at,item.updated_at):''}</td>`;
                    }
                } else {
                    tableHtml += `<td>${toTitleCase(item[col])||''}</td>`;
                }
            });
            tableHtml += `<td>
                <div class="btn-group" role="group">
                    <button class="button-2" type="button" data-toggle="dropdown">•••</button>
                    <div class="dropdown-menu">
                        <a href="/FormData/it-request/approval/${item.reference_no}" target="_blank" class="view_btn btn btn-sm btn-success">
                            <i data-feather="eye"></i>
                        </a>
                    </div>
                </div>
            </td>`;
            tableHtml += `</tr>`;
        });

        tableHtml += `</tbody></table>`;
        document.getElementById(containerId).innerHTML = tableHtml;
        feather.replace();
        $(`#${tableId}`).DataTable({pageLength:10,lengthMenu:[5,10,25,50,100],responsive:true});
    }

    fetch("{{ route('IT.Request.Form.Data') }}")
    .then(res=>res.json())
    .then(data=>{
        renderTable('BorrowRequestTbl','BorrowTable',data.filter(d=>d.type_request==='Borrow_Item'),['reference_no','requestor_name','department','item_name','date_needed','plan_return_date','created_at','status']);
        renderTable('RepairRequestTbl','RepairTable',data.filter(d=>d.type_request==='Repair_Request'),['reference_no','requestor_name','department','issue','created_at','status']);
        renderTable('ProjectRequestTbl','ProjectTable',data.filter(d=>d.type_request==='Project_Request'),['reference_no','requestor_name','type_request','description_of_request','created_at','status']);
        renderTable('PurchaseRequestTbl','PurchaseTable',data.filter(d=>d.type_request==='Purchase_Item'),['reference_no','requestor_name','type_request','description_of_request','created_at','status']);
        renderTable('IntranetRequestTbl','IntranetTable',data.filter(d=>d.type_request==='Intranet_Request'),['reference_no','requestor_name','type_request','description_of_request','created_at','status']);

        // --- Chart ---
    // --- Chart ---
// --- Chart ---
const completedData = data.filter(d => d.status === 'Completed');

// Compute time intervals in days
completedData.forEach(d => {
    d.time_interval_days = 0;
    if(d.created_at && d.updated_at){
        const diffMs = new Date(d.updated_at) - new Date(d.created_at);
        d.time_interval_days = Math.max(diffMs / (1000 * 60 * 60 * 24), 0); // in days
    }
});

const issueMap = {};
completedData.forEach(d => {
    const key = d.issue || d.item_name || d.type_request || 'Unknown';
    if(!issueMap[key]) issueMap[key] = [];
    issueMap[key].push(d.time_interval_days);
});

const avgTimePerIssue = Object.keys(issueMap).map(issue => {
    const avg = issueMap[issue].reduce((a, b) => a + b, 0) / issueMap[issue].length;
    return { issue, avgDays: avg.toFixed(2) }; // 2 decimal days
});

const avgTimeLabels = avgTimePerIssue.map(d => d.issue);
const avgTimeValues = avgTimePerIssue.map(d => d.avgDays);

// Calculate dynamic max for Y-axis in days
const maxY = Math.ceil(Math.max(...avgTimeValues) * 1.2); // 20% buffer

const ctx = document.getElementById('avgTimeChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: avgTimeLabels,
        datasets: [{
            label: 'Avg Completion Time (Days)',
            data: avgTimeValues,
            backgroundColor: 'rgba(54, 162, 235, 0.6)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: true },
            title: { display: true, text: 'Average Completion Time per Issue (Days)' }
        },
        scales: {
            y: {
                beginAtZero: true,
                max: maxY,
                ticks: {
                    // Show 1 decimal place for readability
                    callback: function(value) {
                        return value.toFixed(1) + ' d';
                    }
                }
            }
        }
    }
});


    })
    .catch(err=>console.error('Error fetching data:',err));

});
</script>

@endsection

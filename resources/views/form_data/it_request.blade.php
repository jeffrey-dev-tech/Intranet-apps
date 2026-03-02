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
            <a class="nav-link active" id="borrow-tab" data-toggle="tab" href="#borrow" role="tab" aria-controls="borrow" aria-selected="true">
                <div class="d-flex flex-row flex-lg-column flex-xl-row align-items-center">
                    <i data-feather="book" class="icon-sm mr-sm-2 mr-lg-0 mr-xl-2 mb-md-1 mb-xl-0"></i>
                    <p class="d-none d-sm-block">Borrow Request</p>
                </div>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="repair_request-tab" data-toggle="tab" href="#repair_request" role="tab" aria-controls="repair_request" aria-selected="false">
                <div class="d-flex flex-row flex-lg-column flex-xl-row align-items-center">
               <i class="fa-solid fa-wrench mr-sm-2 mr-lg-0 mr-xl-2 mb-md-1 mb-xl-0"></i>
                    <p class="d-none d-sm-block">Repair Request</p>
                </div>
            </a>
        </li>
          <li class="nav-item">
            <a class="nav-link" id="project_request-tab" data-toggle="tab" href="#project_request" role="tab" aria-controls="project_request" aria-selected="false">
                <div class="d-flex flex-row flex-lg-column flex-xl-row align-items-center">
                 <i class="fas fa-project-diagram mr-sm-2 mr-lg-0 mr-xl-2 mb-md-1 mb-xl-0" ></i>
                    <p class="d-none d-sm-block">Project Request</p>
                </div>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="Intranet_request-tab" data-toggle="tab" href="#Intranet_request" role="tab" aria-controls="Intranet_request" aria-selected="false">
                <div class="d-flex flex-row flex-lg-column flex-xl-row align-items-center">
                    <i class="fa-solid fa-globe icon-sm mr-sm-2 mr-lg-0 mr-xl-2 mb-md-1 mb-xl-0"></i>
                    <p class="d-none d-sm-block">Intranet Request</p>
                </div>
            </a>
        </li>

          <li class="nav-item">
            <a class="nav-link" id="purchase_request-tab" data-toggle="tab" href="#purchase_request" role="tab" aria-controls="purchase_request" aria-selected="false">
                <div class="d-flex flex-row flex-lg-column flex-xl-row align-items-center">
         <i class="fa-solid fa-cart-shopping icon-sm mr-sm-2 mr-lg-0 mr-xl-2 mb-md-1 mb-xl-0"></i>
                    <p class="d-none d-sm-block">Purchase Item</p>
                </div>
            </a>
        </li>
    </ul>

    <div class="tab-content mt-3">
        <div class="tab-pane fade show active" id="borrow" role="tabpanel" aria-labelledby="borrow-tab">
            <div class="table-responsive" id="BorrowRequestTbl"></div>
        </div>
        <div class="tab-pane fade" id="repair_request" role="tabpanel" aria-labelledby="repair_request-tab">
            <div class="table-responsive" id="RepairRequestTbl"></div>
        </div>
        <div class="tab-pane fade" id="Intranet_request" role="tabpanel" aria-labelledby="Intranet_request-tab">
            <div class="table-responsive" id="IntranetRequestTbl"></div>
        </div>
        <div class="tab-pane fade" id="project_request" role="tabpanel" aria-labelledby="project_request-tab">
            <div class="table-responsive" id="ProjectRequestTbl"></div>
        </div>
            <div class="tab-pane fade" id="purchase_request" role="tabpanel" aria-labelledby="purchase_request-tab">
            <div class="table-responsive" id="PurchaseRequestTbl"></div>
        </div>

        
    </div>
</div>

            
              </div>
            </div>
					</div>
				</div> 
                </div>

<style>
.pending {
  color: red;
  font-weight: bold;
  padding: 2px 6px;
  border-radius: 4px;
  background-color: rgba(255, 165, 0, 0.1);
  animation: pulse 1s infinite alternate;
}


.view_btn{

    display         : inline-block;
    outline         : none;
    cursor          : pointer;
    padding         : 0 8px;
    background-color: #fff;
    border-radius   : 0.25rem;
 border          : 1px solid #dddbda;
    color           : #0070d2;
    font-size       : 13px;
    line-height     : 30px;
    font-weight     : 400;
    text-align      : center;


}

.view_btn:hover {
     background-color: #56c000ff; 
} 
.disview_btn{

    display         : inline-block;
    outline         : none;
    cursor          : pointer;
    padding         : 0 8px;
    background-color: #fff;
    border-radius   : 0.25rem;
   border          : 1px solid #dddbda;
    color           : #d20035ff;
    font-size       : 13px;
    line-height     : 30px;
    font-weight     : 400;
    text-align      : center;


}

.disview_btn:hover {
     background-color: #56c000ff; 
} 
.done_btn{

    display         : inline-block;
    outline         : none;
    cursor          : pointer;
    padding         : 0 8px;
    background-color: #fff;
    border-radius   : 0.25rem;
    border          : 1px solid #dddbda;
    color           : #78e000ff;
    font-size       : 13px;
    line-height     : 30px;
    font-weight     : 400;
    text-align      : center;


}

.done_btn:hover {
     background-color: #f4f6f9; 
} 

/* CSS */
.button-2 {
  background-color: rgba(51, 51, 51, 0.05);
  border-radius: 8px;
  border-width: 0;
  color: #333333;
  cursor: pointer;
  display: inline-block;
  font-family: "Haas Grot Text R Web", "Helvetica Neue", Helvetica, Arial, sans-serif;
  font-size: 14px;
  font-weight: 500;
  line-height: 20px;
  list-style: none;
  margin: 0;
  padding: 10px 12px;
  text-align: center;
  transition: all 200ms;
  vertical-align: baseline;
  white-space: nowrap;
  user-select: none;
  -webkit-user-select: none;
  touch-action: manipulation;
}
</style> 
<style>
/* Extra small table body text */
.table tbody {
    font-size: 0.65rem;  /* extra small */
}

/* Optional: reduce row padding for compactness */
.table tbody td {
    padding: 0.2rem 0.4rem;
}

/* Keep header normal size */
.table thead {
    font-size: 1rem;
}
</style>

<!-- Bootstrap 4 Modal -->
<script src="{{ asset('assets/js/jquery.js') }}"></script>
<style>
/* Extra small table body font */
.table tbody {
    font-size: 0.65rem;  /* extra small */
}

/* Reduce cell padding for compactness */
.table tbody td {
    padding: 0.2rem 0.4rem;
}

/* Keep header normal size */
.table thead {
    font-size: 1rem;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {

    let currentRefNo = null;

    // --- Helper: Format date as JAN-01-26 02:30 PM ---
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
        hours = hours % 12;
        hours = hours ? hours : 12; // midnight
        const hoursStr = String(hours).padStart(2,'0');

        return `${month}-${day}-${year} ${hoursStr}:${minutes} ${ampm}`;
    }

    // --- Helper: Calculate time interval (Completed rows only) ---
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
        if (months) parts.push(`${months} month${months > 1 ? 's' : ''}`);
        if (remDays) parts.push(`${remDays} day${remDays > 1 ? 's' : ''}`);
        if (remHours) parts.push(`${remHours} hour${remHours > 1 ? 's' : ''}`);
        if (remMinutes) parts.push(`${remMinutes} min${remMinutes > 1 ? 's' : ''}`);

        return parts.join(', ') || '0 min';
    }

    // --- Helper: Convert text to Title Case ---
    function toTitleCase(str) {
        if (!str) return '';
        return str.toString().trim().split(' ').map(word => {
            return word.charAt(0).toUpperCase() + word.slice(1).toLowerCase();
        }).join(' ');
    }

    // --- Generic function to render tables ---
    function renderTable(containerId, tableId, tableData, columns) {
        let tableHtml = `<table id="${tableId}" class="table table-striped table-hover table-bordered text-center"><thead class="thead-dark"><tr>`;
        tableHtml += `<th>#</th>`;

        const hasCompleted = tableData.some(item => item.status === 'Completed');

        // Render headers
        columns.forEach(col => {
            tableHtml += `<th>${col.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase())}</th>`;
            if (col === 'created_at' && hasCompleted) {
                tableHtml += `<th>Completion Date</th>`;
                tableHtml += `<th>Time Interval</th>`;
            }
        });

        tableHtml += `<th>Actions</th></tr></thead><tbody>`;

        // Render rows
        tableData.forEach((item, index) => {
            tableHtml += `<tr>`;
            tableHtml += `<td>${index + 1}</td>`;

            columns.forEach(col => {
                if (col === 'status') {
                    let statusBadge = '';
                    switch(item[col]) {
                        case 'Pending':
                            statusBadge = "<span class='badge badge-warning'>Pending</span>";
                            break;
                        case 'Approved':
                            statusBadge = "<span class='badge badge-primary'>Approved</span>";
                            break;
                        case 'Completed':
                            statusBadge = "<span class='badge badge-success'>Completed</span>";
                            break;
                        case 'Rejected':
                            statusBadge = "<span class='badge badge-danger'>Rejected</span>";
                            break;
                        default:
                            statusBadge = `<span class='badge badge-secondary'>${item[col]}</span>`;
                    }
                    tableHtml += `<td>${statusBadge}</td>`;
                } else if (col === 'created_at') {
                    tableHtml += `<td>${formatDate(item[col])}</td>`;

                    if (hasCompleted) {
                        tableHtml += `<td>${item.status === 'Completed' ? formatDate(item.updated_at) : ''}</td>`;
                        tableHtml += `<td>${item.status === 'Completed' ? getTimeInterval(item.created_at, item.updated_at) : ''}</td>`;
                    }
                } else {
                    // Apply Title Case to all text columns
                    tableHtml += `<td>${toTitleCase(item[col]) || ''}</td>`;
                }
            });

            tableHtml += `
                <td>
                    <div class="btn-group" role="group">
                        <button class="button-2" role="button" id="btnGroupVerticalDrop1" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            •••
                        </button>
                        <div class="dropdown-menu" aria-labelledby="btnGroupVerticalDrop1">
                            <a href="/FormData/it-request/approval/${item.reference_no}" target="_blank" class="view_btn btn btn-sm btn-success">
                                <i data-feather="eye"></i>
                            </a>
                        </div>
                    </div>
                </td>
            `;

            tableHtml += `</tr>`;
        });

        tableHtml += `</tbody></table>`;
        document.getElementById(containerId).innerHTML = tableHtml;
        feather.replace();

        $(`#${tableId}`).DataTable({
            pageLength: 10,
            lengthMenu: [5, 10, 25, 50, 100],
            responsive: true
        });
    }

    // --- Fetch and render tables ---
    fetch("{{ route('IT.Request.Form.Data') }}")
    .then(response => response.json())
    .then(data => {
        renderTable('BorrowRequestTbl', 'BorrowTable', data.filter(d => d.type_request === 'Borrow_Item'), ['reference_no','requestor_name','department','item_name','date_needed','plan_return_date','created_at','status']);
        renderTable('RepairRequestTbl', 'RepairTable', data.filter(d => d.type_request === 'Repair_Request'), ['reference_no','requestor_name','department','issue','created_at','status']);
        renderTable('ProjectRequestTbl', 'ProjectTable', data.filter(d => d.type_request === 'Project_Request'), ['reference_no','requestor_name','type_request','description_of_request','created_at','status']);
        renderTable('PurchaseRequestTbl', 'PurchaseTable', data.filter(d => d.type_request === 'Purchase_Item'), ['reference_no','requestor_name','type_request','description_of_request','created_at','status']);
        renderTable('IntranetRequestTbl', 'IntranetTable', data.filter(d => d.type_request === 'Intranet_Request'), ['reference_no','requestor_name','type_request','description_of_request','created_at','status']);
    })
    .catch(error => console.error('Error fetching item request data:', error));

});
</script>

@endsection

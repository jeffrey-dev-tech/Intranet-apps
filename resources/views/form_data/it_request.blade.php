@extends('layouts.app')

@section('title', 'IT Request Data')

@section('content')
    	
    
    <div class="page-content">
    	<nav class="page-breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="#">Data</a></li>
						<li class="breadcrumb-item active" aria-current="page">Item Request</li>
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
                    <i data-feather="phone-call" class="icon-sm mr-sm-2 mr-lg-0 mr-xl-2 mb-md-1 mb-xl-0"></i>
                    <p class="d-none d-sm-block">Repair Request</p>
                </div>
            </a>
        </li>
          <li class="nav-item">
            <a class="nav-link" id="project_request-tab" data-toggle="tab" href="#project_request" role="tab" aria-controls="project_request" aria-selected="false">
                <div class="d-flex flex-row flex-lg-column flex-xl-row align-items-center">
                    <i data-feather="phone-call" class="icon-sm mr-sm-2 mr-lg-0 mr-xl-2 mb-md-1 mb-xl-0"></i>
                    <p class="d-none d-sm-block">Project Request</p>
                </div>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="Intranet_request-tab" data-toggle="tab" href="#Intranet_request" role="tab" aria-controls="Intranet_request" aria-selected="false">
                <div class="d-flex flex-row flex-lg-column flex-xl-row align-items-center">
                    <i data-feather="users" class="icon-sm mr-sm-2 mr-lg-0 mr-xl-2 mb-md-1 mb-xl-0"></i>
                    <p class="d-none d-sm-block">Intranet Request</p>
                </div>
            </a>
        </li>

          <li class="nav-item">
            <a class="nav-link" id="purchase_request-tab" data-toggle="tab" href="#purchase_request" role="tab" aria-controls="purchase_request" aria-selected="false">
                <div class="d-flex flex-row flex-lg-column flex-xl-row align-items-center">
                    <i data-feather="users" class="icon-sm mr-sm-2 mr-lg-0 mr-xl-2 mb-md-1 mb-xl-0"></i>
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

@keyframes pulse {
  from {
    opacity: 0.6;
  }
  to {
    opacity: 1;
  }
}

.approved_btn{

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

.approved_btn:hover {
     background-color: #f4f6f9; 
} 
.disapproved_btn{

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

.disapproved_btn:hover {
     background-color: #f4f6f9; 
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
</style> 
<script src="assets/js/jquery.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    // Define which columns to show per tab
    const columnsPerTab = {
        borrow: [
            'reference_no', 'requestor_name', 'department', 'item_name', 'date_needed', 'plan_return_date', 'status'
        ],
        repair: [
             'reference_no','requestor_name', 'department', 'issue', 'status'
        ],
        intranet: [
            'reference_no','requestor_name', 'type_request', 'description_of_request', 'status'
        ],
        project: [
             'reference_no','requestor_name', 'type_request', 'description_of_request', 'status'
        ],
        purchase: [
            'reference_no','requestor_name', 'type_request', 'description_of_request', 'status'
        ]
    };

    // Generic function to render table
 function renderTable(containerId, tableId, tableData, columns) {
    let tableHtml = `<table id="${tableId}" class="table table-striped table-hover table-bordered text-center"><thead class="thead-dark"><tr>`;

    // Add a header for incremental ID
    tableHtml += `<th>#</th>`;

    columns.forEach(col => {
        tableHtml += `<th>${col.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase())}</th>`;
    });
    tableHtml += `<th>Actions</th></tr></thead><tbody>`;

    tableData.forEach((item, index) => {
        tableHtml += `<tr>`;

        // Add incremental ID
        tableHtml += `<td>${index + 1}</td>`; // starts from 1

        columns.forEach(col => {
            if (col === 'status') {
                tableHtml += `<td>${item[col] === "Pending" 
                    ? "<span class='badge badge-warning'>Pending</span>" 
                    : "<span class='badge badge-success'>Done</span>"}</td>`;
            } else {
                tableHtml += `<td>${item[col] || ''}</td>`;
            }
        });

        tableHtml += `
            <td>
                <button class="approved_btn btn btn-sm btn-success"><i data-feather="check-square"></i></button>
                <button class="disapproved_btn btn btn-sm btn-danger"><i data-feather="trash-2"></i></button>
            </td>`;
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


    // Fetch data and populate tables
   fetch("{{ route('IT.Request.Form.Data') }}")
    .then(response => response.json())
    .then(data => {
        // Borrow Request tab
        renderTable(
            'BorrowRequestTbl',
            'BorrowTable',
            data.filter(d => d.type_request === 'Borrow_Item'),
            columnsPerTab.borrow
        );

        // Repair Request tab
        renderTable(
            'RepairRequestTbl',
            'RepairTable',
            data.filter(d => d.type_request === 'Repair_Request'),
            columnsPerTab.repair
        );

          renderTable(
            'ProjectRequestTbl',
            'ProjectTable',
            data.filter(d => d.type_request === 'Project_Request'),
            columnsPerTab.project
        );

            renderTable(
            'PurchaseRequestTbl',
            'PurchaseTable',
            data.filter(d => d.type_request === 'Purchase_Request'),
            columnsPerTab.project
        );

        // Intranet Request tab (combine two intranet types)
        renderTable(
            'IntranetRequestTbl',
            'IntranetTable',
            data.filter(d => d.type_request === 'New_Intranet_Subsystem' || d.type_request === 'Change_Request_Intranet'),
            columnsPerTab.intranet
        );
    })
    .catch(error => console.error('Error fetching item request data:', error));

});
</script>


@endsection

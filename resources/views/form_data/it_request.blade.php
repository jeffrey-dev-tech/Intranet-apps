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
<!-- Bootstrap 4 Modal -->

<div class="modal fade" id="referenceModal" tabindex="-1" role="dialog" aria-labelledby="referenceModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      
      <!-- Modal Header -->
      <div class="modal-header bg-info">
        <h5 class="modal-title text-white" id="referenceModalLabel">IT Request Details</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <!-- Modal Body -->
      <div class="modal-body">
        <!-- Dynamic Table -->
        <table class="table table-bordered">
          <tbody id="referenceTableBody">
            <!-- Populated dynamically -->
          </tbody>
        </table>


      </div>

      <!-- Modal Footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" id="saveStatusBtn">Save</button>
      </div>
    </div>
  </div>
</div>



    <script src="{{ asset('assets/js/jquery.js') }}"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // Define which columns to show per tab
    const columnsPerTab = {
        borrow: ['reference_no', 'requestor_name', 'department', 'item_name', 'date_needed', 'plan_return_date', 'status'],
        repair: ['reference_no','requestor_name', 'department', 'issue', 'status'],
        intranet: ['reference_no','requestor_name', 'type_request', 'description_of_request', 'status'],
        project: ['reference_no','requestor_name', 'type_request', 'description_of_request', 'status'],
        purchase: ['reference_no','requestor_name', 'type_request', 'description_of_request', 'status']
    };

    // Generic function to render table
    function renderTable(containerId, tableId, tableData, columns) {
        let tableHtml = `<table id="${tableId}" class="table table-striped table-hover table-bordered text-center"><thead class="thead-dark"><tr>`;
        tableHtml += `<th>#</th>`;
        columns.forEach(col => {
            tableHtml += `<th>${col.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase())}</th>`;
        });
        tableHtml += `<th>Actions</th></tr></thead><tbody>`;

        tableData.forEach((item, index) => {
            tableHtml += `<tr>`;
            tableHtml += `<td>${index + 1}</td>`; // Incremental ID
            columns.forEach(col => {
                if (col === 'status') {
                    tableHtml += `<td>${item[col] === "Pending" ? "<span class='badge badge-warning'>Pending</span>" : "<span class='badge badge-success'>Done</span>"}</td>`;
                } else {
                    tableHtml += `<td>${item[col] || ''}</td>`;
                }
            });
            tableHtml += `
                <td>
                    <div class="btn-group" role="group">
                        <button class="button-2" role="button" id="btnGroupVerticalDrop1" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            •••
                        </button>
                        <div class="dropdown-menu" aria-labelledby="btnGroupVerticalDrop1">
                            <button 
                                class="view_btn btn btn-sm btn-success" 
                                data-reference="${item.reference_no}" 
                                data-toggle="modal" 
                                data-target="#referenceModal">
                                <i data-feather="eye"></i>
                            </button>
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

    // Fetch data and populate tables
    fetch("{{ route('IT.Request.Form.Data') }}")
    .then(response => response.json())
    .then(data => {
        renderTable('BorrowRequestTbl', 'BorrowTable', data.filter(d => d.type_request === 'Borrow_Item'), columnsPerTab.borrow);
        renderTable('RepairRequestTbl', 'RepairTable', data.filter(d => d.type_request === 'Repair_Request'), columnsPerTab.repair);
        renderTable('ProjectRequestTbl', 'ProjectTable', data.filter(d => d.type_request === 'Project_Request'), columnsPerTab.project);
        renderTable('PurchaseRequestTbl', 'PurchaseTable', data.filter(d => d.type_request === 'Purchase_Request'), columnsPerTab.project);
        renderTable('IntranetRequestTbl', 'IntranetTable', data.filter(d => d.type_request === 'New_Intranet_Subsystem' || d.type_request === 'Change_Request_Intranet'), columnsPerTab.intranet);
    })
    .catch(error => console.error('Error fetching item request data:', error));

    // 🔹 Load IT request details into modal
    document.addEventListener('click', async function(event) {
        const button = event.target.closest('.view_btn');
        if (!button) return;

        const refNo = button.getAttribute('data-reference');

        try {
            const url = "{{ route('it_request.reference_data', ['reference_no' => 'PLACEHOLDER']) }}".replace('PLACEHOLDER', encodeURIComponent(refNo));
            const response = await fetch(url);
            if (!response.ok) throw new Error(`Error: ${response.status}`);
            const data = await response.json();

            let rows = '';
            for (const [key, value] of Object.entries(data)) {
                if (key !== 'approvals') {
                    rows += `
                        <tr>
                            <th style="width: 40%; white-space: normal;">${key.replace(/_/g, ' ').toUpperCase()}</th>
                            <td colspan="2" style="white-space: normal; word-wrap: break-word; text-align: left;">
                                ${value ?? ''}
                            </td>
                        </tr>
                    `;
                }
            }

            if (data.approvals && data.approvals.length > 0) {
                rows += `
                    <tr class="bg-light text-center" style="white-space: normal;">
                        <th>Approver</th>
                        <th>Date Approved</th>
                        <th>Status / Remarks</th>
                    </tr>
                `;
                data.approvals.forEach(app => {
                    rows += `
                        <tr>
                            <td style="white-space: normal;">${app.approved_by}</td>
                            <td style="white-space: normal;">${app.date_accomplished}</td>
                            <td style="white-space: normal; word-wrap: break-word;">${app.status} - ${app.remarks ?? ''}</td>
                        </tr>
                    `;
                });
            }

            // Form fields inside modal body
            rows += `
                <tr>
                    <td colspan="3">
                        <form id="statusRemarksForm">
                            <hr>
                            <div class="form-group">
                                <label for="statusSelect"><strong>Change Status</strong></label>
                                <select id="statusSelect" class="form-control" name="status">
                                    <option selected disabled></option>
                                    <option value="Approved">Approved</option>
                                    <option value="Pending">Pending</option>
                                    <option value="Completed">Completed</option>
                                    <option value="Rejected">Rejected</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="remarksTextarea"><strong>Remarks</strong></label>
                                <textarea id="remarksTextarea" class="form-control" name="remarks" rows="3" placeholder="Enter remarks here..."></textarea>
                            </div>
                        </form>
                    </td>
                </tr>
            `;

            document.getElementById('referenceTableBody').innerHTML = rows;

            $('#referenceModal').modal('show');

        } catch (error) {
            console.error(error);
            document.getElementById('referenceTableBody').innerHTML = `
                <tr><td colspan="3" class="text-danger">Failed to fetch details for ${refNo}</td></tr>
            `;
        }
    });

    // 🔹 Save button inside modal footer
    const saveBtn = document.getElementById('saveStatusBtn');
    if (saveBtn) {
        saveBtn.addEventListener('click', function() {
            const status = document.getElementById('statusSelect').value;
            const remarks = document.getElementById('remarksTextarea').value;

            if (!status) {
                alert('Please select a status.');
                return;
            }
 if (!remarks) {
                alert('Input Remarks.');
                return;
            }
            console.log('Saving status:', status, 'Remarks:', remarks);

            // Example: send data to server
            /*
            fetch('/update-status', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({ status, remarks })
            })
            .then(res => res.json())
            .then(data => console.log(data))
            .catch(err => console.error(err));
            */

            // Close modal after save
        
        });
    }

});
</script>



@endsection

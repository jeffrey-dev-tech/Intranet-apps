@extends('layouts.app')

@section('title', 'Item Request Data')

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
                <h6 class="card-title">Item Request</h6>
                <div class="table-responsive">
        
                    <div id="ItemRequestTbl">

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
    fetch("{{ route('IT.Request.Form.Data') }}")
        .then(response => response.json())
        .then(data => {
            let tableHtml = `
                <table id="dataTableExample" class="table table-hover table-bordered" style="text-align:center;">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Item Name</th>
                            <th>Qty</th>
                            <th>Date Needed</th>
                            <th>Date Plan Return</th>
                            <th>Department</th>
                            <th>Requestor Name</th>
                            <th>Status</th>
                            <th>Action</th>
                           
                        </tr>
                    </thead>
                    <tbody>
            `;

            data.forEach(item => {
                tableHtml += `
                    <tr>
                        <td>${item.id}</td>
                        <td>${item.item_name}</td>
                        <td>${item.qty}</td>
                        <td>${item.date_needed}</td>
                        <td>${item.date_plan_return}</td>
                        <td>${item.department}</td>
                        <td>${item.requestor_name}</td>
                        <td> ${item.status === "pending" ? "<span class='pending'>Pending</span>" : "Done!"}</td>
                        <td><button class="approved_btn"><i data-feather="check-square"></i> </button>
                        <button class="disapproved_btn"><i data-feather="stop-circle"></i> </button>
                        <button class="done_btn"><i data-feather="star"></i> </button>
                        <button class="disapproved_btn"><i data-feather="trash-2"></i> </button></td>
                    </tr>
                `;
            });

            tableHtml += '</tbody></table>';

            // Insert into container
            document.getElementById('ItemRequestTbl').innerHTML = tableHtml;
feather.replace();
            // Initialize DataTable
            $('#dataTableExample').DataTable({
                pageLength: 10,
                lengthMenu: [5, 10, 25, 50, 100],
                responsive: true
            });
        })
        .catch(error => {
            console.error('Error fetching item request data:', error);
        });
});

</script>


@endsection

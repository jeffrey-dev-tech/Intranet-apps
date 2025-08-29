@extends('layouts.app')

@section('title', 'Computer Inventory')

@section('content')
    	
    
    <div class="page-content">
    	<nav class="page-breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="#">Table</a></li>
						<li class="breadcrumb-item active" aria-current="page">Computer Imventory</li>
					</ol>
				</nav>
    <div class="row">
					<div class="col-md-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <h6 class="card-title">Computer Inventory</h6>
                <button class="btn btn-primary"data-toggle="modal"  data-target="#modal_inventory"> <i data-feather="file-plus"></i></button>
                <div class="table-responsive">
        
                    <div id="hardwareTableContainer">

                    </div>
                    
                </div>
              </div>
            </div>
					</div>
				</div> 
                </div>



<!-- Modal -->
<div class="modal fade bd-example-modal-xl" id="modal_inventory" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog  modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Computer Details</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <form id="update_policy_form" enctype="multipart/form-data">
          <div class="row">
            <div class="col-sm-3">
              <div class="form-group">
                <label class="control-label">COMPUTER NAME</label>
                <input type="text" class="form-control" name="computer_name" id="computer_name" required placeholder="Input label name">
              </div>
            </div>
           <div class="col-sm-3">
              <div class="form-group">
                <label class="control-label">FULLNAME</label>
                <input type="text" class="form-control" name="full_name" id="full_name" required placeholder="Input Fullname">
              </div>
            </div>

             <div class="col-sm-3">
              <div class="form-group">
                <label class="control-label">POSITION</label>
                <input type="text" class="form-control" name="position" id="position" required placeholder="Input Position">
              </div>
            </div>

            <div class="col-sm-3">
              <div class="form-group">
                <label class="control-label">DEPARTMENT</label>
                <input type="text" class="form-control" name="dept_region" id="dept_region" required placeholder="Input department">
              </div>
            </div>



            <div class="col-sm-3">
              <div class="form-group">
                <label class="control-label">BRAND / MODEL</label>
                <input type="text" class="form-control" name="brand_model" id="brand_model" required placeholder="Input Brand">
              </div>
            </div>

             <div class="col-sm-3">
              <div class="form-group">
                <label class="control-label">Processor</label>
                <input type="text" class="form-control" name="processor" id="processor" required placeholder="Input Processor">
              </div>
            </div>

          <div class="col-sm-3">
              <div class="form-group">
                <label class="control-label">HDD</label>
                <input type="text" class="form-control" name="hdd" id="ssd" required placeholder="Input hdd">
              </div>
            </div>

                  <div class="col-sm-3">
              <div class="form-group">
                <label class="control-label">SDD</label>
                <input type="text" class="form-control" name="ssd" id="ssd" required placeholder="Input ssd">
              </div>
            </div>

            <div class="col-sm-3">
              <div class="form-group">
                <label class="control-label">RAM</label>
                <input type="text" class="form-control" name="memory" id="memory" required placeholder="Input RAM">
              </div>
            </div>

             <div class="col-sm-3">
              <div class="form-group">
                <label class="control-label">OPERATING SYSTEM</label>
                <input type="text" class="form-control" name="os" id="os" required placeholder="Input OS">
              </div>
            </div>

               <div class="col-sm-3">
              <div class="form-group">
                <label class="control-label">MS OFFICE</label>
                <input type="text" class="form-control" name="ms_office" id="ms_office" required placeholder="Input MS OFFICE">
              </div>
            </div>

        <div class="col-sm-3">
              <div class="form-group">
                <label class="control-label">SERIAL NUMBER</label>
                <input type="text" class="form-control" name="serial_number" id="serial_number" required placeholder="Input Serial Number">
              </div>
            </div>


          </div>
        </form>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary btn_view_modal" id="uploadButton">Save changes</button>
      </div>
    </div>
  </div>
</div>
<style>
  #dataTableExample td, #dataTableExample th {
    white-space: normal;
    word-wrap: break-word;
    word-break: break-word;
  }
</style>
<script src="assets/js/jquery.js"></script>
<script>
    const sendButtonBaseUrl = "{{ url('/send-gmail') }}";

    document.addEventListener('DOMContentLoaded', function () {
        fetch("{{ route('inventory.data') }}")
            .then(response => response.json())
            .then(data => {
                let tableHtml = `
                    <table id="dataTableExample" class="table-bordered" style="width:100%; font-size:12px;">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Computer Name</th>
                                <th>User</th>
                                <th>Department/Region</th>
                                <th>Brand & Model</th>
                                <th>Serial Number</th>
                                <th>OS</th>
                                <th>Processor</th>
                                <th>Memory</th>
                                <th>Remarks</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                `;

                data.forEach(item => {
                    tableHtml += `
                        <tr>
                            <td>${item.id}</td>
                            <td>${item.computer_name}</td>
                            <td>${item.user_name}</td>
                            <td>${item.dept_region}</td>
                            <td>${item.brand_model}</td>
                            <td>${item.serial_number}</td>
                            <td>${item.os}</td>
                            <td>${item.processor}</td>
                            <td>${item.memory}</td>
                            <td>${item.remarks}</td>
                            <td><a href="${sendButtonBaseUrl}/${item.id}" class="btn btn-success">✓</a></td>
                        </tr>
                    `;
                });

                tableHtml += '</tbody></table>';

                document.getElementById('hardwareTableContainer').innerHTML = tableHtml;

                $('#dataTableExample').DataTable({
                    pageLength: 10,
                    lengthMenu: [5, 10, 25, 50, 100],
                    responsive: true
                });
            })
            .catch(error => {
                console.error('Error fetching inventory data:', error);
            });
    });
</script>


@endsection

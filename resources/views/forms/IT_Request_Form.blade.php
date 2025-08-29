@if (auth()->check())
@extends('layouts.app')

@section('title', 'Item & Device Request Form')

@section('content')

  <style>
 .sanden-logo {
  display: flex;
  flex-direction: column;
  align-items: center; /* centers horizontally */
  text-align: center; /* center-aligns text inside h6 */
}

.sanden-logo img{
  width: 700px;
  margin-bottom: 10px;
}

@media (max-width: 766px) {
	.sanden-logo img{
  width: 350px;
  margin-bottom: 10px;
}

}
@media (max-width: 398px) {
	.sanden-logo img{
  width: 325px;
  margin-bottom: 10px;
}
}
.loader {
  border: 6px solid #f3f3f3;
  border-top: 6px solid #3498db;
  border-radius: 50%;
  width: 40px;
  height: 40px;
  animation: spin 1s linear infinite;
  margin: 20px auto;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

    #viewer {
      width: 100%;
      /* background: #78b1e3; */
      height: auto;
      border: 1px solid #ccc;
      margin-top: 20px;
      padding: 10px;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: flex-start;
      overflow: auto;
      user-select: none;           /* 🔒 Prevent text selection */
    }

    img, canvas {
      max-width: 100%;
      border: none;
      margin-bottom: 20px;
      pointer-events: none;       /* 🔒 Prevent drag/save */
    }

    pre {
      white-space: pre-wrap;
      text-align: left;
      padding: 10px;
      width: 100%;
      box-sizing: border-box;
      user-select: none;          /* 🔒 Prevent text selection */
    }

    /* 🔒 Prevent printing of viewer content */
    @media print {
      #viewer, #viewer canvas, #viewer pre {
        display: none !important;
      }
    }
    

input[type="file"] {
  position: relative;
}

input[type="file"]::file-selector-button {
  width: 136px;
  color: transparent;
}

/* Faked label styles and icon */
input[type="file"]::before {
  position: absolute;
  pointer-events: none;
  top: 10px;
  left: 16px;
  height: 20px;
  width: 20px;
  content: "";
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%230964B0'%3E%3Cpath d='M18 15v3H6v-3H4v3c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2v-3h-2zM7 9l1.41 1.41L11 7.83V16h2V7.83l2.59 2.58L17 9l-5-5-5 5z'/%3E%3C/svg%3E");
}

input[type="file"]::after {
  position: absolute;
  pointer-events: none;
  top: 11px;
  left: 40px;
  color: #0964b0;
  content: "Upload File";
}

/* ------- From Step 1 ------- */

/* file upload button */
input[type="file"]::file-selector-button {
  border-radius: 4px;
  padding: 0 16px;
  height: 40px;
  cursor: pointer;
  background-color: white;
  border: 1px solid rgba(0, 0, 0, 0.16);
  box-shadow: 0px 1px 0px rgba(0, 0, 0, 0.05);
  margin-right: 16px;
  transition: background-color 200ms;
}

/* file upload button hover state */
input[type="file"]::file-selector-button:hover {
  background-color: #f3f4f6;
}

/* file upload button active state */
input[type="file"]::file-selector-button:active {
  background-color: #e5e7eb;
}


/* this responsive for table custom */
/* Override Bootstrap behavior */




</style>
<div class="page-content">
    <nav class="page-breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="#">Forms</a></li>
						<li class="breadcrumb-item active" aria-current="page">Deposit Form</li>
					</ol>
				</nav>
		<div class="row">
					<div class="col-md-12 stretch-card">
						<div class="card">
							<div class="card-body">

							   	<div class="sanden-logo">
										<img src="{{ asset('img/Sanden_Logo_SCP2_.png')}}" alt="sanden-logo">
										<div class="title-form">
											<h6 class="card-title" style="font-size:25px;">Item & Device Request Form</h6>
										</div>
                    
									</div>
									<hr>
		    <form id="item_request_form" enctype="multipart/form-data">
          <div class="row">
            <div class="col-sm-3">
              <div class="form-group">
                <label class="control-label">Item*</label>
                <select name="item_name" id="item_name" required>
                  <option selected disabled>Choose Item</option>
                  <option value="Laptop">Laptop</option>
                	<option value="Desktop">Desktop</option>
                   <option value="Wired Mouse">Wired Mouse</option>
				    <option value="Wired Mouse">Wireless Mouse</option>
				   <option value="Wired Keyboard">Wired Keyboard</option>
				   <option value="Wired Keyboard">Wireless Keyboard</option>
				    <option value="Pocket Wifi">Pocket Wifi</option>
					<option value="Flashdrive">Flashdrive</option>
					<option value="External Drive">External Drive</option>
                </select>
              </div>
            </div>
             <div class="col-sm-3">
              <div class="form-group">
                <label class="control-label">Qty*</label>
               <input type="number" id="Qty" name="qty"placeholder="Enter Qty" class="form-control" required>
              </div>
            </div>
                <div class="col-sm-3">
              <div class="form-group">
                <label class="control-label">Date Needed*</label>
               <input type="date" id="date_needed" name="date_needed" class="form-control" required>
              </div>
            </div>

  			<div class="col-sm-3">
              <div class="form-group">
                <label class="control-label">Date Plan Return*</label>
               <input type="date" id="date_plan_return" name="date_plan_return"  class="form-control" required>
              </div>
            </div>

			<div class="col-sm-4">
              <div class="form-group">
                <label class="control-label">Email</label>
               <input type="text" id="email"  class="form-control" name="email" value="{{ Auth::user()->email }}" readonly>
              </div>
            </div>
			<div class="col-sm-4">
              <div class="form-group">
                <label class="control-label">Department</label>
               <input type="text" id="department"  name="department"class="form-control" value="{{ Auth::user()->department }}" readonly>
              </div>
            </div>

			<div class="col-sm-4">
              <div class="form-group">
                <label class="control-label">Requestor Name</label>
               <input type="text" id="requestor_name" name="requestor_name" class="form-control" value="{{ Auth::user()->name }}" readonly>
              </div>
            </div>
								
							
   			<div class="col-sm-12">
              <div class="form-group">
                <label class="control-label">Purpose</label>
               <textarea type="text" id="purpose"  class="form-control"name="purpose" rows="5" placeholder="Input your purpose 255 characters only..." required></textarea>
              </div>
            </div>
     
            <div class="col-sm-12">
              <div class="form-group">
                <input type="button" name="btnSubmit" id="btnSubmit"  class="btn btn-success" value="Submit">
              </div>
            </div>
          </div>
        </form>
                                </div>
             </div>
        </div>
    </div>
</div>


<script>
    // Initialize Choices.js on the select element
    const userSelect = new Choices('#item_name', {
        searchEnabled: true,      // Enable search
        itemSelectText: '',       // Remove "Press to select" text
        shouldSort: true,        // Keep original order
        placeholder: true,        // Enable placeholder
        searchPlaceholderValue: 'Search item...', // Search box placeholder
    });

document.addEventListener('DOMContentLoaded', function() {
    let buttonSubmit = document.getElementById('btnSubmit');

    buttonSubmit.addEventListener('click', async function(e) {
        e.preventDefault();

        // Show confirmation before submit
        const result = await Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to submit this form?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, submit it!',
            cancelButtonText: 'Cancel'
        });

        if (result.isConfirmed) {
            let form = document.getElementById('item_request_form');
            let formData = new FormData(form);

            // Show loading
            Swal.fire({
                title: 'Submitting...',
                text: 'Please wait while we process your request.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

          try {
    const response = await fetch(`{{ route('item.request.insert') }}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    });

    if (response.status === 501) {
        // Custom handling for "No Internet" case
        const errData = await response.json();
        Swal.fire({
            icon: 'warning',
            title: 'Internet Issue',
            text: errData.message || 'SandenIntranet has no Intenet. Please try again later.'
        });
        return; // stop here, don't run success logic
    }

    if (!response.ok) {
        // Other non-200 statuses
        throw new Error(`HTTP error! status: ${response.status}`);
    }

    const data = await response.json();

    Swal.fire({
        icon: 'success',
        title: 'Submitted!',
        text: data.message || 'Your form has been submitted successfully.',
        confirmButtonText: 'OK'
    }).then(() => {
        window.location.reload();
    });

} catch (error) {
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: error.message || 'Something went wrong. Please check all input fields.'
    });
}

        } else {
            Swal.fire('Cancelled', 'Your form was not submitted.', 'info');
        }
    });
});






</script>
@endsection
@endif
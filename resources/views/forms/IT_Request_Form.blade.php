@if (auth()->check())
@extends('layouts.app')

@section('title', 'IT Request Form')

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
						<li class="breadcrumb-item active" aria-current="page">IT Request Form</li>
					</ol>
				</nav>
		<div class="row">
					<div class="col-md-12 stretch-card">
						<div class="card">
							<div class="card-body">

							   	<div class="sanden-logo">
										<img src="{{ asset('img/Sanden_Logo_SCP2_.png')}}" alt="sanden-logo">
										<div class="title-form">
											<h6 class="card-title" style="font-size:25px;">IT Request Form</h6>
										</div>
                    
									</div>
									<hr>
		   <form id="item_request_form" >
  <div class="row">
    <div class="col-sm-3">
      <div class="form-group">
        <label class="control-label">Type of Request*</label>
        <select name="type_request" id="type_request" required>
          <option selected disabled>Type of Request</option>
          <option value="Repair_Request">Repair | Request</option>
          <option value="Borrow_Item">Borrow Item</option>
          <option value="Purchase_Item">Purchase New Item</option>
          <option value="Project_Request">Project Request</option>
          <option value="New_Intranet_Subsystem">New Intranet Subsystem</option>
          <option value="Change_Request_Intranet">Change Request Intranet</option>
        </select>
      </div>
    </div>
<div class="col-sm-3">
      <div class="form-group">
        <label class="control-label">Email</label>
        <input type="text" id="requestor_email" class="form-control" name="requestor_email" value="{{ Auth::user()->email }}" readonly>
      </div>
    </div>

    <div class="col-sm-3">
      <div class="form-group">
        <label class="control-label">Department</label>
        <input type="text" id="department" name="department" class="form-control" value="{{ Auth::user()->department }}" readonly>
      </div>
    </div>

    <div class="col-sm-3">
      <div class="form-group">
        <label class="control-label">Requestor Name</label>
        <input type="text" id="requestor_name" name="requestor_name" class="form-control" value="{{ Auth::user()->name }}" readonly>
      </div>
    </div>
    <!-- Dynamic fields -->
    <div id="dynamicFields" class="col-md-12 row"></div>

    

    

    <div class="col-sm-12">
      <div class="form-group">
        <input type="button" name="btnSubmit" id="btnSubmit" class="btn btn-success" value="Submit">
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
document.addEventListener('DOMContentLoaded', function() {

    const typeRequest = document.getElementById('type_request');
    const dynamicFields = document.getElementById('dynamicFields');
    const buttonSubmit = document.getElementById('btnSubmit');
    let choicesInstances = {}; // Track Choices instances

    // === Handle dynamic fields on type change ===
    typeRequest.addEventListener('change', async function () {
        dynamicFields.innerHTML = ''; // Clear previous fields

        if (!this.value) return;

        try {
            const response = await fetch(`/fields?type=${this.value}`);
            const data = await response.json();
            dynamicFields.innerHTML = data.html;

            setTimeout(() => {
                const selectsToInit = [];

                if(this.value === 'Borrow_Item') selectsToInit.push('item_name');
                if(this.value === 'Repair_Request') selectsToInit.push('issue');
                if(this.value === 'New_Intranet_Subsystem') selectsToInit.push('manager_email');
                if(this.value === 'Change_Request_Intranet') selectsToInit.push('manager_email');

                selectsToInit.forEach(id => {
                    const el = document.getElementById(id);
                    if(el) {
                        if(choicesInstances[id]) choicesInstances[id].destroy();

                        choicesInstances[id] = new Choices(el, {
                            searchEnabled: true,
                            itemSelectText: '',
                            shouldSort: true,
                            placeholder: true,
                            searchPlaceholderValue: 'Search...',
                        });
                    }
                });
            }, 50);

        } catch (error) {
            console.error('Error loading fields:', error);
            dynamicFields.innerHTML = `<div class="col-sm-12 text-danger">Failed to load fields.</div>`;
        }
    });

    // === Handle form submission on button click ===
    buttonSubmit.addEventListener('click', async function (e) {
        e.preventDefault();

        const form = document.getElementById('item_request_form');
        const formData = new FormData(form);

        const confirmResult = await Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to submit this form?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, submit it!',
            cancelButtonText: 'Cancel'
        });

        if (!confirmResult.isConfirmed) {
            Swal.fire('Cancelled', 'Your form was not submitted.', 'info');
            return;
        }

        Swal.fire({
            title: "Submitting...",
            text: "Please wait while we process your request.",
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        try {
            const response = await fetch("{{ route('it.request.insert') }}", {
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            });

            let data;
            try {
                data = await response.json();
            } catch {
                data = { message: await response.text() };
            }

            Swal.close();

            if (response.status === 422) {
                const message = Object.values(data.errors).flat().join(', ');
                throw new Error(message);
            }

            if (response.status === 501) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Internet Issue',
                    text: data.message || 'No internet. Please try again later.'
                });
                return;
            }

            if (!response.ok) {
                Swal.fire({
                    icon: 'error',
                    title: 'Submission Failed',
                    text: data.message || 'An error occurred during submission.',
                    confirmButtonText: 'OK'
                });
                return;
            }

            Swal.fire({
                icon: 'success',
                title: 'Submitted!',
                text: data.message || 'Your form has been submitted successfully.',
                confirmButtonText: 'OK'
            }).then(() => location.reload());

        } catch (error) {
            Swal.fire("Error", error.message || "Something went wrong. Please check all input fields.", "error");
        }
    });

});

</script>

@endsection
@endif

@extends('layouts.app')

@section('title', 'Policies')

@section('content')
<style>
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
 .sanden-logo {
  display: flex;
  flex-direction: column;
  align-items: center; /* centers horizontally */
  text-align: center; /* center-aligns text inside h6 */
}

.sanden-logo img{
  width: 400px;
  margin-bottom: 10px;
}

.title-form img{
  width: 70px;
  margin-bottom: 10px;
}

</style>
<div class="page-content">
    	<div class="row">
					<div class="col-md-12 stretch-card">
						<div class="card">
							<div class="card-body">


							   	<div class="sanden-logo">
										<img src="img/Sanden_Logo_SCP2_.png" alt="sanden-logo">
										<div class="title-form">
                      		<img src="img/policy.png" alt="sanden-logo">
											<h6 class="card-title" style="font-size:25px;">Policies Upload Page</h6>
										</div>
                    
									</div>
                                  
<form id="polices_form" enctype="multipart/form-data">
  <div class="row">
    <div class="col-sm-4">
      <div class="form-group">
        <label class="control-label">Department</label>
        <select name="department" id="department">
          <option selected disabled>Choose Department</option>
          <option value="HR">HR</option>
          <option value="ADM">ADM</option>
          <option value="SCM">SCM</option>
        </select>
      </div>
    </div>



    <div class="col-sm-4">
      <div class="form-group">
        <label class="control-label">Label</label>
        <input type="text" class="form-control" name="label_name" id="label_name" required placeholder="Input label name">
      </div>
    </div>
     <div class="col-sm-4">
      <div class="form-group">
        <label class="control-label">Doc Type</label>
        <select name="doc_type" id="doc_type">
          <option disabled selected></option>
          <option value="POL">POL</option>
          <option value="GDL">GDL</option>
          <option value="FRM">FRM</option>
          <option value="WI">WI</option>
        </select>
      </div>
    </div>
   <div class="col-sm-4">
      <div class="form-group">
        <label class="control-label">Control Type</label>
        <select name="control_type" id="control_type">
          <option disabled selected>Choose Control Type</option>
          <option value="Uncontrolled">Uncontrolled</option>
          <option value="Controlled">Controlled</option>
s
        </select>
      </div>
    </div>
     <div class="col-sm-4">
      <div class="form-group">
        <label class="control-label">File Type</label>
        <select name="file_type" id="file_type">
          <option disabled>Choose File type</option>
          <option value="confidential">Confidential</option>
          <option value="non_confidential">Non Confidential</option>
        </select>
      </div>
    </div>
    <div class="col-sm-4">
      <div class="form-group">
        <label class="control-label">File Name</label>
        <input type="text" class="form-control" name="filename" id="filename" required readonly>
      </div>
    </div>
    
     
    <div class="col-sm-12">
  <div class="form-group">
    <input type="file" name="fileToUpload" id="fileToUpload" accept="application/pdf" required>
  </div>
</div>

  </div>
  <input type="submit" value="Upload" class="btn btn-success">
</form>



</div>
</div>
</div>
</div>
</div>
<script src="assets/js/sweetalert2@11.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
const dept_selected = document.getElementById('department');
const doc_type = document.getElementById('doc_type');
const filenameInput = document.getElementById('filename');

function fetchPolicies() {
    const selectedValue = dept_selected.value.trim();
    const selecteddoctype = doc_type.value.trim();

    // ✅ Only proceed if BOTH have values
    if (selectedValue && selecteddoctype) {
        fetch("{{ route('filename.policies') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ department: selectedValue, doc_type: selecteddoctype })
        })
        .then(response => response.json())
        .then(data => {
            filenameInput.value = data.filename || 'No file found';
        })
        .catch(error => {
            console.error('Fetch error:', error);
        });
    } else {
        console.log('⚠ Please select BOTH Department and Document Type before fetching.');
    }
}

// ✅ Attach event listeners to both selects
if (dept_selected && doc_type) {
    dept_selected.addEventListener('change', fetchPolicies);
    doc_type.addEventListener('change', fetchPolicies);
} else {
    console.warn('❌ Department or Doc Type element not found.');
}


document.getElementById("polices_form").addEventListener("submit", async function (e) {
    e.preventDefault();

    const formData = new FormData(this);
    const file = formData.get("fileToUpload");

    if (!file || file.size === 0) {
        Swal.fire("Error", "Please select a file to upload.", "error");
        return;
    }

    const confirmResult = await Swal.fire({
        title: 'Confirm Upload',
        text: `Upload "${file.name}" to department "${formData.get("department")}"?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, upload it!',
        cancelButtonText: 'Cancel'
    });

    if (!confirmResult.isConfirmed) return;

    Swal.fire({
        title: "Uploading...",
        text: "Please wait while the file is being uploaded.",
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
    });

    try {
        const response = await fetch("{{ route('upload.policies') }}", {
            method: "POST",
            
            headers: {
             'Accept': 'application/json', // ✅ Forces JSON response for 403
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
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

        // ✅ Handle unauthorized
        if (response.status === 403) {
            Swal.fire({
                icon: 'error',
                title: 'Unauthorized',
                text: data.message || 'You are not allowed to perform this action.',
                confirmButtonText: 'OK'
            });
            return;
        }

        if (!response.ok) {
            Swal.fire({
                icon: 'error',
                title: 'Upload Failed',
                text: data.message || 'An error occurred during the upload.',
                confirmButtonText: 'OK'
            });
            return;
        }

        Swal.fire({
            icon: 'success',
            title: 'Uploaded!',
            text: data.message || 'Policy uploaded successfully.',
            confirmButtonText: 'OK'
        }).then(() => location.reload());

    } catch (error) {
        Swal.close();
        console.error("Upload error:", error);
        Swal.fire("Error", "An error occurred during the upload.", "error");
    }
});


});
</script>
@endsection
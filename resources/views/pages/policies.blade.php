
@extends('layouts.app')

@section('title', 'Documents Upload')

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
											<h6 class="card-title" style="font-size:25px;">Documents Upload Page</h6>
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
          <option value="MIS">MIS</option>
          <option value="FIN">FIN</option>
          <option value="PUR">PUR</option>
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
        <label class="access-label">Access Level</label>
        <select name="access_level" id="access_level">
          <option disabled selected>Choose Level</option>
           <option value="1">Staff</option>
          <option value="2">Supervisor</option>
          <option value="3">Manager</option>
          <option value="4">Top Management</option>
        </select>
      </div>
    </div>

    <div class="col-sm-4">
  <div class="form-group">
    <label class="control-label">Category</label>
    <select name="category_id" id="category_id" class="form-control">
      <option disabled selected>Choose Category</option>
      @foreach ($categories as $category)
        <option value="{{ $category->id }}">
          {{ $category->name }}
        </option>
      @endforeach
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
    console.log("FormData contents:");
for (const [key, value] of formData.entries()) {
  console.log(key, value);
}

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
    let errorText = 'An error occurred during the upload.';

    if (response.status === 422 && data.errors) {
        // Combine all error messages
        errorText = Object.values(data.errors)
            .flat()
            .join('\n'); // Join multiple messages with line breaks
    } else if (data.message) {
        errorText = data.message;
    }

    Swal.fire({
        icon: 'error',
        title: 'Upload Failed',
        text: errorText,
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
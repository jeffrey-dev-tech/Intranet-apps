@extends('layouts.app')

@section('title', 'Cold Chain Service Report')

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
  content: "Attachments";
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
</style>
<div class="page-content">
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Forms</a></li>
            <li class="breadcrumb-item active" aria-current="page">Cold Chain Report</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-12 stretch-card">
            <div class="card">
                <div class="card-body">
                      <div class="sanden-logo">
										<img src="{{ asset('img/Sanden_Logo_SCP2_.png') }}" alt="sanden-logo">
                    <h3 style="text-align: center; justify-content:center;">Cold Chain Service Report</h3>
                      </div>
                <form id="pdfForm" method="post">
    @csrf
    <div class="mb-3">
        <label for="pdf_name">Service Report FileName:</label>
        <input type="text" name="pdf_name" id="pdf_name" class="form-control" required placeholder="Enter PDF base name">
    </div>

    <div class="mb-3">
        <label for="content">Content:</label>
        <textarea name="content" id="content" rows="10" class="form-control" placeholder="Enter your text here..." required></textarea>
    </div>
    <div class="mb-3">
        <input type="file" name="images[]" id="images"  multiple accept="image/*">
    </div>
    <button type="submit" class="btn btn-success">Generate PDF & Upload</button>
</form>


                    @if(session('success'))
                        <p style="color: green;">{{ session('success') }}</p>
                    @endif

                    @if($errors->any())
                        <p style="color: red;">{{ implode(', ', $errors->all()) }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<script>
document.getElementById('pdfForm').addEventListener('submit', function(e){
    e.preventDefault(); // prevent normal submit

    const form = this;
    const formData = new FormData(form);

    // ✅ Show confirmation
    Swal.fire({
        title: 'Are you sure?',
        html: 'Do you want to generate and upload this PDF?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, Upload',
        cancelButtonText: 'No, Cancel'
    }).then((result) => {
        if(result.isConfirmed){
            // ✅ User clicked Yes, show loading
            Swal.fire({
                title: 'Uploading PDF...',
                html: 'Please wait while your PDF is being uploaded.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading()
                }
            });

            fetch("{{ url('/ColdChainServiceReport/upload-pdf') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                Swal.close();

                if(data.success){
                    Swal.fire({
                        icon: 'success',
                        title: 'Upload Completed',
                        html: `
                            <b>${data.success}</b><br>
                            <b>Upload Time:</b> ${data.upload_time}<br>
                            <b>File ID:</b> ${data.file_id}
                        `
                    }).then(() => {
                        // ✅ Reload page after user closes success alert
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Upload Failed',
                        html: data.error || 'Unknown error occurred.'
                    });
                }
            })
            .catch(err => {
                Swal.close();
                Swal.fire({
                    icon: 'error',
                    title: 'Upload Failed',
                    html: err.message
                });
            });
        }
    });
});
</script>


@endsection

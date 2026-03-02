@if (auth()->check())
@extends('layouts.app')

@section('title', 'Travel Request Form')

@section('content')

<style>
/* ===== Logo Section ===== */
.sanden-logo {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
}

.sanden-logo img {
    width: 700px;
    margin-bottom: 10px;
}

@media (max-width: 766px) {
    .sanden-logo img { width: 350px; }
}

@media (max-width: 398px) {
    .sanden-logo img { width: 325px; }
}

/* ===== Loader ===== */
.loader {
    border: 6px solid #f3f3f3;
    border-top: 6px solid #3498db;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    animation: spin 1s linear infinite;
    margin: 20px auto;
}

@keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }

/* ===== File Input ===== */
input[type="file"]::file-selector-button {
    border-radius: 4px;
    padding: 0 16px;
    height: 40px;
    cursor: pointer;
    background-color: white;
    border: 1px solid rgba(0, 0, 0, 0.16);
    margin-right: 16px;
    transition: background-color 200ms;
}

input[type="file"]::file-selector-button:hover { background-color: #f3f4f6; }
input[type="file"]::file-selector-button:active { background-color: #e5e7eb; }

/* ===== Viewer Styles ===== */
#viewer {
    width: 100%;
    height: auto;
    border: 1px solid #ccc;
    margin-top: 20px;
    padding: 10px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: flex-start;
    overflow: auto;
    user-select: none;
}

img, canvas { max-width: 100%; border: none; margin-bottom: 20px; pointer-events: none; }
pre { white-space: pre-wrap; text-align: left; padding: 10px; width: 100%; box-sizing: border-box; user-select: none; }

/* Hide viewer content when printing */
@media print {
    #viewer, #viewer canvas, #viewer pre { display: none !important; }
}
</style>

<div class="page-content">
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Forms</a></li>
            <li class="breadcrumb-item active" aria-current="page">Travel Request Form</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-12 stretch-card">
            <div class="card">
                <div class="card-body">

                    <div class="sanden-logo">
                        <img src="{{ asset('img/Sanden_Logo_SCP2_.png')}}" alt="sanden-logo">
                        <h6 class="card-title" style="font-size:25px;">Travel Request Form</h6>
                    </div>

                    <hr>

                    <form id="travel_request_form" enctype="multipart/form-data">
                        @csrf

                        <div class="row">

                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Request Type*</label>
                                    <select name="request_type" id="request_type" class="form-control" required>
                                        <option selected disabled>Type of Request</option>
                                        <option value="Air Travel">Air Travel</option>
                                        <option value="Land Transport">Land Transport</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="text" class="form-control" value="{{ Auth::user()->email }}" readonly>
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Department</label>
                                    <input type="text" class="form-control"name="department" id="department" value="{{ Auth::user()->department }}" readonly>
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Requestor Name</label>
                                    <input type="text" class="form-control" name="requestor_id" id="requestor_id" value="{{ Auth::user()->name }}" readonly>
                                </div>
                            </div>

                            <!-- Dynamic fields -->
                            <div id="dynamicFields" class="col-12 row mt-3"></div>

                            <div class="col-sm-12 mt-3">
                                <button type="button" id="btnSubmit" class="btn btn-success">Submit</button>
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
    const typeRequest = document.getElementById('request_type');
    const dynamicFields = document.getElementById('dynamicFields');
    const buttonSubmit = document.getElementById('btnSubmit');
    const form = document.getElementById('travel_request_form');

    // Clear previous errors
    function clearErrors() {
        form.querySelectorAll('.invalid-feedback').forEach(e => e.remove());
        form.querySelectorAll('.is-invalid').forEach(e => e.classList.remove('is-invalid'));
    }

    // Dynamic fields based on type
    typeRequest.addEventListener('change', async function () {
        dynamicFields.innerHTML = '';
        if (!this.value) return;

        try {
            const res = await fetch(`{{ route('Travelfields') }}?type=${this.value}`);
            const data = await res.json();
            dynamicFields.innerHTML = data.html || '';
        } catch (error) {
            dynamicFields.innerHTML = `<div class="col-sm-12 text-danger">Failed to load fields.</div>`;
        }
    });

    // Submit form
    buttonSubmit.addEventListener('click', async function(e) {
        e.preventDefault();
        clearErrors();

        const maxFileSize = 10 * 1024 * 1024; // 10MB
        const files = form.querySelectorAll('input[type="file"]');
        for (let fileInput of files) {
            for (let file of fileInput.files) {
                if (file.size > maxFileSize) {
                    return Swal.fire({
                        icon: 'error',
                        title: 'File Too Large',
                        text: `File "${file.name}" exceeds 10MB limit.`
                    });
                }
            }
        }

        const confirmResult = await Swal.fire({
            title: 'Are you sure?',
            text: 'Do you want to submit this form?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, submit it!',
            cancelButtonText: 'Cancel'
        });

        if (!confirmResult.isConfirmed) return Swal.fire('Cancelled', 'Your form was not submitted.', 'info');

        Swal.fire({ title: 'Submitting...', text: 'Please wait...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

        try {
            const formData = new FormData(form);
            const res = await fetch("{{ route('travel.Insert') }}", {
                method: "POST",
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                body: formData
            });

            const data = await res.json();
            Swal.close();

            if (res.status === 422) {
                // Display validation errors inline
                const errors = data.errors || {};
               for (let field in errors) {
    let input = form.querySelector(`[name="${field}"]`);
    
    // If it's an array field like attachments.0, attachments.1
    if (!input && field.includes('.')) {
        input = form.querySelector(`[name="${field.split('.')[0]}[]"]`);
    }

    if (input) {
        input.classList.add('is-invalid');
        const errorDiv = document.createElement('div');
        errorDiv.classList.add('invalid-feedback');
        errorDiv.innerText = errors[field].join(', ');
        input.parentNode.appendChild(errorDiv);
    }
}

                return Swal.fire('Validation Error', 'Please fix the errors in the form or you don\'t have attachment.', 'error');
            }

            if (!res.ok) throw new Error(data.message || 'Submission failed.');

            Swal.fire({ icon: 'success', title: 'Submitted!', text: data.message || 'Form submitted successfully.' })
                 .then(() => location.reload());

        } catch (error) {
            Swal.fire('Error', error.message || 'Something went wrong.', 'error');
        }
    });
});
</script>



@endsection
@endif

@extends('layouts.app')

@section('title', 'EDMS '.$department)

@section('content')
<!-- css assets/css/sanden/edms.css -->
</head>
<body>
<style>
 
</style>
  <div class="page-content">
<!-- @php
    echo $department;
@endphp -->
<div class="card">
							<div class="card-body">

	                  <div class="sanden-logo">
										<img src="{{ asset('img/Sanden_Logo_SCP2_.png') }}" alt="sanden-logo">
                    <hr>
										<div class="title-form">
											<h6 class="card-title" style="font-size:25px;">Electronic Document Management System (@php echo $department @endphp)</h6>
										</div>
									</div>
            <div class="table-responsive">
              <div style="margin-bottom: 10px;">
    <label for="docTypeFilter"><strong>Filter by Doc Type:</strong></label>
    <select id="docTypeFilter" class="form-select" style="width: 200px; display:inline-block; margin-left: 10px;">
    </select>
</div>
                <div id="hardwareTableContainer">

                    </div>

							
   </div>

                       
  </div>
  </div>

   
<div class="form-group mt-4">
  <div class="row align-items-center">
   
    <div class="col-auto">
      <button onclick="filterPages()" class="btn btn-success btn-sm">
        <i class="link-icon" data-feather="filter"></i> Filter
      </button>
    </div>
    <div class="col-auto">
      <button onclick="showAllPages()" class="btn btn-primary btn-sm">
        <i class="link-icon" data-feather="list"></i> All
      </button>
    </div>
     <div class="col-auto">
      <input type="text" id="pageInput" class="form-control form-control-sm" placeholder="e.g., 1,3,5 or 2-4" />
    </div>
  </div>
</div>


    <div id="viewer">Content</div>
    <div id="spinner" style="display: none;">
  <div class="container-loader">
  <div class="loader"></div>
</div>
</div>

  </div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Update Policy File</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <form id="update_policy_form" enctype="multipart/form-data">
          <div class="row">
            <div class="col-sm-12">
              <div class="form-group">
                <label class="control-label">Set New Label</label>
                <input type="text" class="form-control" name="new_label" id="new_label" required placeholder="Input label name">
              </div>
            </div>
               <div class="col-sm-12">
              <div class="form-group">
                <label class="control-label">File Type</label>
                <select name="file_type" id="file_type">
                  <option selected disabled>Choose File type</option>
                  <option value="confidential">Confidential</option>
                     <option value="non_confidential">Non Confidential</option>
                </select>
              </div>
            </div>
             <div class="col-sm-12">
              <div class="form-group">
                <label class="control-label">Control Type</label>
                <select name="control_type" id="control_type">
                  <option selected disabled>Choose Control Type</option>
                  <option value="uncontrollled">uncontrollled</option>
                     <option value="controlled">controlled</option>
                </select>
              </div>
            </div>
           <div class="col-sm-12">
              <div class="form-group">
                <label class="control-label">Label Name</label>
                <input type="text" class="form-control" name="label_name" id="label_name" readonly>
                 <input type="hidden" class="form-control" name="id" id="id" readonly>
              </div>
            </div>
            <div class="col-sm-12">
              <div class="form-group">
                <label class="control-label">File Name</label>
                <input type="text" class="form-control" name="filename" id="filename" required readonly>
              </div>
            </div>
             <div class="col-sm-12">
              <div class="form-group">
                <label class="control-label">Department</label>
                <input type="text" class="form-control" name="department" id="department" required readonly>
              </div>
            </div>
          
            <div class="col-sm-12">
              <div class="form-group">
                <input type="file" name="fileToUpload" id="fileToUpload" accept="application/pdf"  required>
              </div>
            </div>
          </div>
        </form>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="modal_confirm_btn">Save changes</button>
      </div>
    </div>
  </div>
</div>


<script>

/* **************************************** Start of policy table************************************ */  
document.addEventListener('DOMContentLoaded', function () {
    let departmentvalue = @json(request('department'));
    let user_role = @json(request('user_role'));

    fetch("{{ route('policy.data') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ department: departmentvalue })
    })
    .then(response => response.json())
    .then(result => {
        console.log('Fetched policy data:', result.data);

        // ✅ Populate dropdown with unique doc_type values
            let docTypes = [...new Set(result.data
              .map(item => item.doc_type)
              .filter(type => type && type.trim() !== '')
          )].sort();

          let docSelect = document.getElementById('docTypeFilter');
          docSelect.innerHTML = '<option value="">All</option>';
          docTypes.forEach(type => {
              let option = document.createElement('option');
              option.value = type;
              option.textContent = type;
              docSelect.appendChild(option);
          });

        // ✅ Build the table
        let tableHtml = `
            <table id="dataTableExample" class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Filename</th>
                        <th>Label Name</th>
                        <th>Department/Region</th>
                        <th>Date of Upload</th>
                        <th>Doc Type</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
        `;

        let row_id = 1;
        result.data.forEach(item => {
            tableHtml += `
                <tr>
                    <td>${row_id}</td>
                    <td>${item.filename}</td>
                    <td>${item.label}</td>
                    <td>${item.department}</td>
                    <td>${item.upload_date}</td>
                    <td>${item.doc_type}</td>
                    <td style='text-align:center;'>
   
                        <button class="approved_btn" type="button"
                            onclick="loadFile('${item.filename}', '${item.department}')"
                            >
                            <i class="fas fa-eye"></i>
                        </button>
                                     
`;

            if (result.userRole === '5' || result.userRole === '6' || result.userRole === '2') {
                tableHtml += `
                        <button class="done_btn modal_update_btn" type="button"
                            data-target="#exampleModal" data-toggle="modal" data-id="${item.id}" data-department="${item.department}"
                            data-filename="${item.filename}" data-labelname="${item.label}"
                           >
                            <i class="fas fa-sync"></i>
                        </button>

                        <button class="disapproved_btn" type="button" onclick="deleteFile('${item.filename}', '${item.department}')">
                            <i class="fas fa-trash"></i>
                        </button>
                        <button class="approved_btn" type="button"
                            onclick="download_file('${item.filename}', '${item.department}')"
                           >
                            <i class="fas fa-download"></i>
                        </button>
                        
                        `;
                        
            }

            tableHtml += `</td></tr>`;
            row_id++;
        });

        tableHtml += '</tbody></table>';
        document.getElementById('hardwareTableContainer').innerHTML = tableHtml;

        // ✅ Initialize DataTable
        let dataTable = $('#dataTableExample').DataTable({
            pageLength: 5,
            lengthMenu: [5, 10, 25, 50, 100],
            responsive: true
        });

        // ✅ Apply filter on dropdown change
       // Doc Type filter (column 6)
docSelect.addEventListener('change', function () {
    dataTable.column(5).search(this.value).draw();
});
    })
    .catch(error => {
        console.error('Error fetching inventory data:', error);
    });
});


/* **************************************** End of policy table************************************ */  

/* **************************************** Start of load file ************************************ */
let currentSignedUrl = '';
let currentPDF = null;
let isLoading = false;

// -------------------------
// Load and Render PDF
// -------------------------
async function loadFile(signedUrl, pageFilter = null) {
    if (isLoading) return;
    isLoading = true;

    currentSignedUrl = signedUrl;

    const viewer = document.getElementById('viewer');
    viewer.innerHTML = '';

    // 🔹 Show loading indicator
    Swal.fire({
        title: 'Loading...',
        text: 'Please wait...',
        toast: true,
        position: 'center',
        background: '#dbeafe',
        color: '#1e3a8a',
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        didOpen: () => Swal.showLoading()
    });

    try {
        const loadingTask = pdfjsLib.getDocument({
            url: signedUrl,
            withCredentials: true // ✅ send Laravel session cookie
        });

        const pdf = await loadingTask.promise;
        currentPDF = pdf;
        const numPages = pdf.numPages;

        let pagesToRender = [];
        if (pageFilter && pageFilter.length > 0) {
            pagesToRender = pageFilter.filter(p => p >= 1 && p <= numPages);
            if (pagesToRender.length === 0) {
                viewer.innerHTML = `<p style="color:red;">No valid page numbers selected.</p>`;
                Swal.close();
                isLoading = false;
                return;
            }
        } else {
            pagesToRender = Array.from({ length: numPages }, (_, i) => i + 1);
        }

        for (const pageNumber of pagesToRender) {
            const page = await pdf.getPage(pageNumber);
            const scale = 1.3;
            const viewport = page.getViewport({ scale });

            const canvas = document.createElement('canvas');
            const context = canvas.getContext('2d');
            canvas.width = viewport.width;
            canvas.height = viewport.height;

            await page.render({ canvasContext: context, viewport }).promise;

            addWatermark(context, canvas.width, canvas.height, 'UNCONTROLLED');
            addPageNumber(context, canvas.width, canvas.height, pageNumber, numPages);

            viewer.appendChild(canvas);
        }

        Swal.close();
        viewer.scrollTo({ top: 0, behavior: 'smooth' });

    } catch (error) {
        viewer.innerHTML = '❌ Failed to load PDF.';
        console.error(error);
        Swal.fire('Error', 'Failed to load PDF.', 'error');
    } finally {
        isLoading = false;
    }
}

// -------------------------
// Parse page input
// -------------------------
function parsePageInput(input) {
    const pages = [];
    const parts = input.split(',');
    for (let part of parts) {
        part = part.trim();
        if (part.includes('-')) {
            const [start, end] = part.split('-').map(Number);
            if (!isNaN(start) && !isNaN(end) && start <= end) {
                for (let i = start; i <= end; i++) pages.push(i);
            }
        } else {
            const page = parseInt(part);
            if (!isNaN(page)) pages.push(page);
        }
    }
    return [...new Set(pages)]; // remove duplicates
}

// -------------------------
// Filter pages
// -------------------------
function filterPages() {
    const input = document.getElementById('pageInput').value;
    const pageArray = parsePageInput(input);

    if (currentPDF && pageArray.length > 0) {
        loadFile(currentSignedUrl, pageArray);
    } else {
        Swal.fire({
            icon: 'warning',
            title: 'Invalid Input',
            text: 'Enter valid page numbers or ranges.',
            confirmButtonText: 'OK',
            confirmButtonColor: '#3085d6'
        });
    }
}

// -------------------------
// Show all pages
// -------------------------
function showAllPages() {
    if (currentPDF) {
        loadFile(currentSignedUrl);
    }
}

// -------------------------
// Watermark
// -------------------------
function addWatermark(ctx, width, height, text) {
    ctx.save();
    ctx.globalAlpha = 0.15;
    ctx.translate(width / 2, height / 2);
    ctx.rotate(-Math.PI / 4);
    ctx.font = 'bold 78px Arial';
    ctx.fillStyle = 'red';
    ctx.textAlign = 'center';
    ctx.fillText(text, 0, 0);
    ctx.restore();
}

// -------------------------
// Page number
// -------------------------
function addPageNumber(ctx, width, height, pageNumber, totalPages) {
    ctx.save();
    ctx.globalAlpha = 0.8;
    ctx.font = 'bold 16px Arial';
    ctx.fillStyle = 'black';
    ctx.textAlign = 'center';
    ctx.fillText(`Page ${pageNumber} of ${totalPages}`, width / 2, height - 20);
    ctx.restore();
}

// -------------------------
// Example usage
// -------------------------
document.addEventListener('DOMContentLoaded', () => {
    // Laravel passes signed URL
    const signedUrl = "{{ $signedUrl }}";

    loadFile(signedUrl);

    // Optional: attach buttons
    document.getElementById('filterBtn')?.addEventListener('click', filterPages);
    document.getElementById('showAllBtn')?.addEventListener('click', showAllPages);
});

/* **************************************** end of load file ************************************ */



/* **************************************** Delete ************************************ */

async function deleteFile(filename, department) {
    const confirmResult = await Swal.fire({
        title: `Delete "${filename}"?`,
        text: "This action cannot be undone.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel',
        reverseButtons: true
    });

    if (!confirmResult.isConfirmed) return;

    Swal.fire({
        title: 'Deleting...',
        text: 'Please wait while we delete the file.',
        allowOutsideClick: false,
        allowEscapeKey: false,
        didOpen: () => Swal.showLoading()
    });

    try {
        const response = await fetch("{{ route('pdf.delete') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ department, filename })
        });

        let data;
        try {
            data = await response.json();
        } catch {
            data = { message: 'Unexpected response format.' };
        }

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
            throw new Error(data.error || 'Server error: ' + response.status);
        }

   await Swal.fire({
    icon: 'success',
    title: 'Deleted!',
    text: data.message || 'Policy deleted successfully.',
    confirmButtonText: 'OK'
});
location.reload();

    } catch (error) {
        console.error('Delete error:', error);
        Swal.fire('Error', 'An error occurred during deletion.', 'error');
    }
}

/* **************************************** Delete ************************************ */


/* **************************************  Update Button File   ********************* */
let selectedId = null;
let selectedDepartment = null;
let selectedFilename = null;
let selectedLabelName = null;

// ✅ Handle click on dynamically generated buttons
document.addEventListener('click', function (event) {
    const clickedBtn = event.target.closest('.modal_update_btn');
    if (clickedBtn) {
        selectedId = clickedBtn.dataset.id;
        selectedDepartment = clickedBtn.dataset.department;
        selectedFilename = clickedBtn.dataset.filename;
   selectedLabelName = clickedBtn.dataset.labelname;

        const filenameInput = document.querySelector('#exampleModal #filename');
        const labelnameInput = document.querySelector('#exampleModal #label_name');
        const deptInput = document.querySelector('#exampleModal #department');
        const idInput = document.querySelector('#exampleModal #id');

        if (filenameInput) {
            filenameInput.value = selectedFilename || '';
            labelnameInput.value = selectedLabelName || '';
            deptInput.value = selectedDepartment || '';
            idInput.value = selectedId || '';
        }
    }
});

// ✅ Async function for update
document.getElementById('modal_confirm_btn').addEventListener('click', async function () {
    if (!selectedId || !selectedDepartment) {
        console.warn('No ID selected!');
        return;
    }

    const result = await Swal.fire({
        title: 'Are you sure?',
        text: "Do you want to update this policy?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, update it!'
    });

    if (!result.isConfirmed) return;

    const form = document.getElementById('update_policy_form');
    const formData = new FormData(form);

    Swal.fire({
        title: 'Updating...',
        text: 'Please wait while we update the policy.',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
    });

    try {
      //
        const response = await fetch("{{ route('policy.update') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: formData
        });

        const data = await response.json().catch(() => ({}));

        // ✅ Handle Unauthorized (403)
        if (response.status === 403) {
            Swal.fire({
                icon: 'error',
                title: 'Unauthorized',
                text: data.message || 'You are not allowed to perform this action.',
                confirmButtonText: 'OK'
            });
            return;
        }

        // ✅ Handle other errors
        if (!response.ok) {
            throw new Error(data.error || 'Server error: ' + response.status);
        }

        // ✅ Success
        Swal.fire({
            icon: 'success',
            title: 'Updated!',
            text: data.message || 'Policy updated successfully.',
            confirmButtonText: 'OK'
        }).then(() => location.reload());

    } catch (error) {
        Swal.fire('Error', error.message || 'Something went wrong.', 'error');
    }
});


/* ****************************************  Update Button File   ************************************* */



function download_file(filename, department) {
    const encodedFile = btoa(filename);
    const encodedDept = btoa(department);
    const url = `/download-file?department=${encodedDept}&file=${encodedFile}`;

    const a = document.createElement('a');
    a.href = url;
    a.setAttribute('download', filename);
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
}

</script>
@endsection
@extends('layouts.app')

@section('title', 'Create Fitness Sports')

@section('content')
<div class="page-content">
  <nav class="page-breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="#"></a></li>
      <li class="breadcrumb-item" aria-current="page">Fitness Challenge</li>
      <li class="breadcrumb-item active" aria-current="page">Create Fitness Sports</li>
    </ol>
  </nav>

  <div class="row">
    <div class="col-md-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <h6 class="card-title">Create Activity Form</h6>

          <form id="activityForm" action="{{ route('activities.store') }}" method="POST">
            @csrf

            <div class="mb-3">
              <label class="form-label">Activity Name:</label>
              <input type="text" class="form-control" name="name" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Description:</label>
              <textarea class="form-control" name="description" rows="3"></textarea>
            </div>

            <div class="mb-3">
              <label class="form-label">Unit (km, miles, etc.):</label>
              <input type="text" class="form-control" name="unit" required>
            </div>

            <h4>Levels</h4>
            <div id="levels-container">
              <div class="level border p-3 mb-3 rounded">
                <div class="mb-3">
                  <label class="form-label">Level Number:</label>
                  <input type="number" class="form-control" name="levels[0][level_number]" value="1" required>
                </div>
                <div class="mb-3">
                  <label class="form-label">Required Value:</label>
                  <input type="number" class="form-control" name="levels[0][required_value]" required>
                </div>
                <div class="mb-3">
                  <label class="form-label">Team Size:</label>
                  <input type="number" class="form-control" name="levels[0][team_size]" required>
                </div>
              </div>
            </div>

            <button type="button" class="btn btn-secondary mb-3" onclick="addLevel()">Add Level</button>
            <br>
            <button type="submit" class="btn btn-primary">Create Activity</button>
          </form>

        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    let levelIndex = 1;

    function addLevel() {
        const container = document.getElementById('levels-container');
        const div = document.createElement('div');
        div.classList.add('level', 'border', 'p-3', 'mb-3', 'rounded');
        div.innerHTML = `
            <div class="mb-3">
                <label class="form-label">Level Number:</label>
                <input type="number" class="form-control" name="levels[${levelIndex}][level_number]" value="${levelIndex+1}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Required Value:</label>
                <input type="number" class="form-control" name="levels[${levelIndex}][required_value]" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Team Size:</label>
                <input type="number" class="form-control" name="levels[${levelIndex}][team_size]" required>
            </div>
        `;
        container.appendChild(div);
        levelIndex++;
    }

    document.getElementById('activityForm').addEventListener('submit', async function(e){
        e.preventDefault();
        const form = this;
        const formData = new FormData(form);

        const confirm = await Swal.fire({
            title: 'Confirm Submission',
            text: "Are you sure you want to create this activity?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, create it!',
            cancelButtonText: 'Cancel',
            allowOutsideClick: false,
            showLoaderOnConfirm: true,
        });

        if (!confirm.isConfirmed) return;

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': form.querySelector('[name="_token"]').value,
                    'Accept': 'application/json' // important!
                },
                body: formData
            });

            const data = await response.json();

            if (response.ok) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: data.message || 'Activity created successfully!',
                }).then(() => {
                    form.reset();
                    document.getElementById('levels-container').innerHTML = '';
                });
            } else if (response.status === 422) {
                // Validation errors
                let messages = [];
                if (data.errors) {
                    for (let field in data.errors) {
                        messages.push(...data.errors[field]);
                    }
                } else if (data.message) {
                    messages.push(data.message);
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    html: messages.join('<br>')
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message || 'Something went wrong!'
                });
            }
        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: error.message || 'Something went wrong!'
            });
        }
    });
</script>
@endsection

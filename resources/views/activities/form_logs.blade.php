@extends('layouts.app')

@section('title', 'Submission Logs')

@section('content')
<style>
.sanden-logo {
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
}

.sanden-logo img {
  width: 150px;
  margin-bottom: 10px;
}

input[type="file"] { position: relative; }
input[type="file"]::file-selector-button { width: 136px; color: transparent; }
input[type="file"]::before { position: absolute; pointer-events: none; top: 10px; left: 16px; content: ""; }
input[type="file"]::after { position: absolute; pointer-events: none; top: 11px; left: 40px; color: #0964b0; content: "Upload File"; }
input[type="file"]::file-selector-button { border-radius: 4px; padding: 0 16px; height: 40px; cursor: pointer; background-color: white; border: 1px solid rgba(0,0,0,.16); box-shadow: 0px 1px 0px rgba(0,0,0,.05); margin-right: 16px; transition: background-color 200ms; }
input[type="file"]::file-selector-button:hover { background-color: #f3f4f6; }
input[type="file"]::file-selector-button:active { background-color: #e5e7eb; }
</style>
<style>

.ranking-heading {
  display: flex;                 /* arrange items side by side */
  align-items: center;           /* vertically center image + text */
  gap: 10px;                     /* space between logo and text */
  color: white;
  font-size: 1.5rem;
  font-weight: bold;
  margin: 0;
  font-family: "Atlanta College", sans-serif;

}



</style>
<div class="page-content">
  <nav class="page-breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="#">Wellness Program</a></li>
      <li class="breadcrumb-item active" aria-current="page">Submission</li>
    </ol>
  </nav>

  <div class="row">
    <div class="col-md-8 grid-margin stretch-card mx-auto">
      <div class="card">
           <img class="ranking-heading" src="{{asset('img/wellness-2.png')}}" alt="">
        <div class="card-body">

                  
          <h6 class="card-title" style="text-align: center; font-size:x-large;">Wellness Program Log Form</h6>

          <form id="formLog" action="{{ route('submission.store') }}" method="POST">
            @csrf

            <!-- Activity select -->
            <div class="mb-3">
              <label class="form-label">Activity</label>
              <select name="activity_id" id="activity_id" class="form-select" required></select>
            </div>

            <!-- Progress Value -->
            <div class="mb-3">
              <label class="form-label">Progress Value</label>
              <div class="input-group">
                <input type="number" id="progress_value" class="form-control" name="progress_value" step="0.01" required>
                <span class="input-group-text" id="progress_unit"></span>
              </div>
            </div>

            <!-- Other Informations -->
            <div class="mb-3">
              <label class="form-label">Other Informations</label>
              <div class="input-group">
                <textarea id="other_informations" class="form-control" rows="5" name="other_informations" placeholder="Input here all other informations" required></textarea>
              </div>
            </div>

            <!-- Team ID -->
            <input type="hidden" name="team_id" id="team_id">

            <!-- Team Name -->
            <div class="mb-3">
              <label class="form-label">Team Name</label>
              <input type="text" id="team_name" class="form-control" readonly>
            </div>

            <!-- Pending Level as level_id -->
            <div class="mb-3">
              <label class="form-label">Pending Level</label>
              <input type="hidden" id="pending_level" name="level_id" class="form-control" readonly>
                 <input type="text" id="display_level" name="display_level" class="form-control" readonly>
            </div>

            <!-- Last Progress Value -->
            <div class="mb-3">
              <label class="form-label">Last Progress Value</label>
              <div class="input-group">
                <input type="number" id="last_progress_value" class="form-control" readonly>
                <span class="input-group-text" id="last_progress_unit"></span>
              </div>
            </div>
<div class="mb-3">
    <label class="form-label">Required Value</label>
    <div class="input-group">
        <input type="number" id="required_value" class="form-control" readonly>
        <span class="input-group-text" id="required_unit"></span>
    </div>
</div>
            <!-- Department -->
            <div class="mb-3">
              <label class="form-label">Department:</label>
              <input type="text" class="form-control" name="department" value="{{ auth()->user()->department }}" required readonly>
            </div>

            <!-- Email -->
            <div class="mb-3">
              <label class="form-label">Email:</label>
              <input type="text" class="form-control" name="email" value="{{ auth()->user()->email }}" required readonly>
            </div>
         
         <div class="col-sm-12">
    <label class="control-label">Attachments</label>
    <div class="form-group">
        <input 
            type="file" 
            name="attachments[]" 
            multiple 
            accept=".jpeg,.jpg,.png,.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx"
        required>
    </div>
</div>
            <button type="submit" class="btn btn-success">Submit</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const activitySelect = document.getElementById('activity_id');
    const teamNameInput = document.getElementById('team_name');
    const pendingLevelInput = document.getElementById('pending_level');
    const displayLevelInput = document.getElementById('display_level');
    const progressInput = document.getElementById('progress_value');
    const progressUnit = document.getElementById('progress_unit');
    const lastProgressInput = document.getElementById('last_progress_value');
    const lastProgressUnit = document.getElementById('last_progress_unit');
    const requiredValueInput = document.getElementById('required_value');
    const requiredUnit = document.getElementById('required_unit');
    const submitButton = document.querySelector('#formLog button[type="submit"]');
    const formLog = document.getElementById('formLog');
    const userId = "{{ auth()->id() }}";
    const submitUrl = "{{ route('submission.store') }}";



    // Load activities
    fetch("{{ route('activities.listActivity') }}")
        .then(res => res.json())
        .then(data => {
            activitySelect.innerHTML = '<option value="">Select Activity</option>';
            data.forEach(a => {
                const opt = document.createElement('option');
                opt.value = a.id;
                opt.textContent = a.name.toUpperCase();
                activitySelect.appendChild(opt);
            });
        })
        .catch(err => console.error('Error loading activities:', err));

    // Reset fields helper
    function resetFields() {
        teamNameInput.value = '';
        pendingLevelInput.value = '';
        displayLevelInput.value = '';
        lastProgressInput.value = '';
        lastProgressUnit.textContent = '';
        progressInput.value = '';
        progressUnit.textContent = '';
        requiredValueInput.value = '';
        requiredUnit.textContent = '';
        document.getElementById('team_id').value = '';
       
    }

    // Check if progress already meets required value
    // function checkAlreadyMet() {
    //     const lastProgress = parseFloat(lastProgressInput.value) || 0;
    //     const requiredValue = parseFloat(requiredValueInput.value) || 0;

    //     if (requiredValue > 0 && lastProgress >= requiredValue) {
    //         submitButton.disabled = true;
    //         Swal.fire({
    //             icon: 'info',
    //             title: 'Already Met!',
    //             text: 'You have already reached the required value for this level.',
    //             confirmButtonText: 'OK'
    //         });
    //     } else {
    //         submitButton.disabled = false;
    //     }
    // }

    // Handle activity change
    activitySelect.addEventListener('change', async function () {
        const activityId = this.value;

        resetFields();
        if (!activityId) return;

        const url = "{{ route('activities.findPendingLevel', ['activity_id' => ':activity_id', 'user_id' => ':user_id']) }}"
            .replace(':activity_id', activityId)
            .replace(':user_id', userId);

        try {
            const res = await fetch(url);
            const data = await res.json();
            if (!data || Object.keys(data).length === 0) return;

            const { team_name, pending_level, display_level, progress_value, unit, team_id, per_member_required } = data;

            // Fill fields
            teamNameInput.value = team_name || '';
            pendingLevelInput.value = pending_level || '';
            displayLevelInput.value = display_level || '';
            lastProgressInput.value = progress_value ?? 0;
            lastProgressUnit.textContent = unit || '';
            progressUnit.textContent = unit || '';
            document.getElementById('team_id').value = team_id || '';

            requiredValueInput.value = per_member_required ?? 0;
            requiredUnit.textContent = unit || '';

            // Check if already met
            // checkAlreadyMet();

        } catch (err) {
            console.error('Error fetching pending level:', err);
            submitButton.disabled = true;
        }
    });

    // Optional: dynamically check if typed progress exceeds required value
    // progressInput.addEventListener('input', checkAlreadyMet);

    // AJAX form submission
    formLog.addEventListener('submit', async function (e) {
        e.preventDefault();
       

        const formData = new FormData(formLog);

        const result = await Swal.fire({
            title: 'Are you sure?',
            text: "Your progress will be submitted!",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, submit it!',
            preConfirm: async () => {
                Swal.showLoading();
                try {
                    const response = await fetch(submitUrl, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: formData
                    });
                    const data = await response.json();
                    if (!response.ok || !data.success) throw new Error(data.message || 'Server error');
                    return data;
                } catch (err) {
                    Swal.showValidationMessage(`Request failed: ${err}`);
                }
            }
        });

        if (result.isConfirmed && result.value) {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: result.value.message,
                confirmButtonText: 'OK'
            });
            formLog.reset();
            resetFields();
        } else {
            submitButton.disabled = false;
        }
    });
});
</script>


@endsection

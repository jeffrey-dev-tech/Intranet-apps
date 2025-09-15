@extends('layouts.app')

@section('title', 'Team Registration')

@section('content')
<style>
     .sanden-logo {
  display: flex;
  flex-direction: column;
  align-items: center; /* centers horizontally */
  text-align: center; /* center-aligns text inside h6 */
}

.sanden-logo img{
  width: 150px;
  margin-bottom: 10px;
}

.title-form img{
  width: 70px;
  margin-bottom: 10px;
}
</style>
<div class="page-content">
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Fitness Challenge</a></li>
            <li class="breadcrumb-item active" aria-current="page">Registration</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-8 grid-margin stretch-card mx-auto">
            <div class="card">
                <div class="card-body">
                    
							   	<div class="sanden-logo">
										<img src="{{asset('img/wellness.png')}}" alt="sanden-logo">
										
                    
									</div>
                    <h6 class="card-title" style="text-align: center; font-size:x-large;">Fitness Challenge Registration</h6>

                    <form id="teamForm" action="{{ route('teams.store') }}" method="POST">
                        @csrf

                        <!-- Role select -->
                        <div class="mb-3">
                            <label class="form-label">Register as:</label>
                            <select name="role_type" id="roleType" class="form-select" required>
                                <option value="">Select...</option>
                                <option value="leader">Team Leader</option>
                                <option value="member">Join a Team</option>
                            </select>
                        </div>

                        <!-- Dynamic fields container -->
                        <div id="inputs_html"></div>

                        <!-- Common inputs -->
                        <div class="mb-3">
                            <label class="form-label">Department:</label>
                            <input type="text" class="form-control" name="department" value="{{ auth()->user()->department }}" required readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email:</label>
                            <input type="text" class="form-control" name="email" value="{{ auth()->user()->email }}" required readonly>
                        </div>

                        <button type="submit" class="btn btn-primary">Register</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const roleType = document.getElementById('roleType');
    const inputsContainer = document.getElementById('inputs_html');
    const teamForm = document.getElementById('teamForm');

    // 🔹 Load activity & level fields
   function loadFields(type) {
    fetch(`{{ route('activities.getFields') }}?type=${type}`)
        .then(res => res.json())
        .then(data => {
            inputsContainer.innerHTML = data.html;

            if (type === 'leader') {
                fetch("{{ route('activities.list') }}")
                    .then(res => res.json())
                    .then(data => {
                        const activitySelect = document.getElementById('activity_id');
                        const levelSelect = document.getElementById('level_id');
                        const levelsHtml = data.levelsHtml;

                        activitySelect.innerHTML = data.html;

                        activitySelect.addEventListener('change', async function () {
                            const activityId = this.value;
                            levelSelect.innerHTML = levelsHtml[activityId] || '<option selected disabled>Choose Level</option>';

                            // 🔥 Check if user has pending status
                            try {
                                const res = await fetch(`{{ route('teams.checkUserPendingStatus') }}`);
                                const status = await res.json();

                                if (status.hasPending) {
                                    Swal.fire({
                                        title: 'Pending Team Found',
                                        html: `You already have a pending status in team <strong>${status.team_name}</strong> (Progress: ${status.progress_value}%).`,
                                        icon: 'warning',
                                        showCancelButton: false,
                                        confirmButtonText: 'Okay',
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            window.location.reload(true); // 🔥 Force reload
                                        }
                                    });
                                }
                            } catch (error) {
                                console.error('Error fetching pending status:', error);
                            }
                        });
                    });
            }
        });
}

    if (roleType.value) loadFields(roleType.value);
    roleType.addEventListener('change', function () {
        loadFields(this.value);
    });

    // 🔹 Handle form submit
    teamForm.addEventListener('submit', async function (e) {
        e.preventDefault();

        // Confirm registration first
        const confirm = await Swal.fire({
            title: 'Confirm Registration',
            text: "Are you sure you want to register?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, Register',
            cancelButtonText: 'Cancel'
        });

        if (!confirm.isConfirmed) return;

        const formData = new FormData(teamForm);

        try {
            // Show loading while waiting for backend
            Swal.fire({
                title: 'Processing...',
                html: 'Please wait while we register your team.',
                didOpen: () => Swal.showLoading(),
                allowOutsideClick: false
            });

            const response = await fetch(teamForm.action, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: formData
            });

            const data = await response.json();
            Swal.close(); // Close loading

            if (data.status === 'success') {
                if (data.invite_code) {
                    await Swal.fire({
                        title: 'Team Created!',
                        html: `Invite Code: <strong>${data.invite_code}</strong>`,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    });
                } else {
                    await Swal.fire({
                        title: 'Success',
                        text: data.message,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    });
                }
                if (data.redirect) window.location.href = data.redirect;
            } else {
                Swal.fire({
                    title: 'Error',
                    text: data.message,
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        } catch (error) {
            Swal.close();
            console.error('Error submitting form:', error);
            Swal.fire({
                title: 'Error',
                text: 'An unexpected error occurred. Please try again.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    });
});
</script>





@endsection

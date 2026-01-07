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
.ranking-heading h3 {
  text-align: center;                 /* keep aspect ratio */
}


</style>
<div class="page-content">
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Wellness Program</a></li>
            <li class="breadcrumb-item active" aria-current="page">Registration</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-8 grid-margin stretch-card mx-auto">

            @if($today->lt($registrationStart))
                <!-- Before registration starts -->
                <div class="card">
                      <img class="ranking-heading" src="{{asset('img/Q1 - Lets Win Together1.jpg')}}" alt="">
                    <div class="card-body text-center">
                     <h6 class="card-title text-center" style="font-size:x-large;">Wellness Program Registration</h6>
                        <p class="text-warning">
                            Registrations will open on {{ $registrationStart->format('F j, Y') }}.
                        </p>
                    </div>
                </div>
            @elseif($today->gt($registrationEnd))
                <!-- After registration ends -->
                <div class="card">
                       <img class="ranking-heading" src="{{asset('img/Q1 - Lets Win Together1.jpg')}}" alt="">
                    <div class="card-body text-center">
                       <h6 class="card-title text-center" style="font-size:x-large;">Wellness Program Registration</h6>
                        <p class="text-danger">
                            Registrations have closed on {{ $registrationEnd->format('F j, Y') }}.
                        </p>
                    </div>
                </div>
            @else
                <!-- Registration is open: show form -->
                <div class="card">
      <img class="ranking-heading" src="{{asset('img/Q1 - Lets Win Together1.jpg')}}" alt="">
                    <div class="card-body">
                        <h6 class="card-title text-center" style="font-size:x-large;">Wellness Program Registration</h6>

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

                        <p class="text-success mt-3 text-center">
                            Registrations are open until {{ $registrationEnd->format('F j, Y') }}. Hurry up!
                        </p>
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const roleType = document.getElementById('roleType');
    const inputsContainer = document.getElementById('inputs_html');
    const teamForm = document.getElementById('teamForm');

    // 🔹 Create a placeholder for error messages
    let warningMessage = document.createElement('div');
    warningMessage.id = "pendingWarning";
    warningMessage.style.color = "red";
    warningMessage.style.marginTop = "10px";
    warningMessage.style.fontWeight = "bold";
    teamForm.appendChild(warningMessage);

    // 🔹 Utility: check if user is in a pending team
    async function checkPendingStatus(showAlert = true) {
        try {
            const res = await fetch(`{{ route('teams.checkUserPendingStatus') }}`);
            if (res.status === 403) {
                const status = await res.json();

                if (status.hasPending && status.pending_teams.length > 0) {
                    let teamsList = status.pending_teams.map(t =>
                        `<strong>${t.team_name}</strong>`
                    ).join('');

                    if (showAlert) {
                        Swal.fire({
                            title: 'Registration Blocked',
                            html: `You cannot register while you are already in pending teams:<br>${teamsList}`,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }

                    // 🚨 Disable submit button
                    const submitButton = teamForm.querySelector('button[type="submit"]');
                    if (submitButton) submitButton.disabled = true;

                    // 🚨 Show warning below form
                    warningMessage.innerHTML =
                        `⚠️ You cannot register or join a team until your pending team(s) are resolved:<br><ul>${teamsList}</ul>`;

                    return true; // means blocked
                }
            }
        } catch (error) {
            console.error('Error fetching pending status:', error);
        }

        // ✅ Clear warning if no block
        warningMessage.innerHTML = "";
        const submitButton = teamForm.querySelector('button[type="submit"]');
        if (submitButton) submitButton.disabled = false;

        return false; // not blocked
    }

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

                            activitySelect.addEventListener('change', function () {
                                const activityId = this.value;
                                levelSelect.innerHTML = levelsHtml[activityId] || '<option selected disabled>Choose Level</option>';

                                // 🔥 Always check pending status after activity selection
                                checkPendingStatus();
                            });
                        });
                }

                // 🔥 Run pending check once fields are loaded
                checkPendingStatus();
            });
    }

    if (roleType.value) loadFields(roleType.value);
    roleType.addEventListener('change', function () {
        loadFields(this.value);
    });

    // 🔹 Handle form submit
    teamForm.addEventListener('submit', async function (e) {
        e.preventDefault();

        // ✅ Double-check pending status before submitting
        const blocked = await checkPendingStatus(false);
        if (blocked) return; // ⛔ stop submission

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
            Swal.close();

            if (data.status === 'success') {
                const messageHtml = data.invite_code
                    ? `Invite Code: <strong>${data.invite_code}</strong>`
                    : data.message;

                await Swal.fire({
                    title: 'Success',
                    html: messageHtml,
                    icon: 'success',
                    confirmButtonText: 'OK'
                });

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

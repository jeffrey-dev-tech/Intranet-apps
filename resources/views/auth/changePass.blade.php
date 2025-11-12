
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Update Password</title>
	<!-- core:css -->
	<link rel="stylesheet" href="{{asset('assets/vendors/core/core.css')}}">
	<!-- endinject -->
  <!-- plugin css for this page -->
	<!-- end plugin css for this page -->
	<!-- inject:css -->
	<link rel="stylesheet" href="{{asset('assets/fonts/feather-font/css/iconfont.css')}}">
	<link rel="stylesheet" href="{{asset('assets/vendors/flag-icon-css/css/flag-icon.min.css')}}">
	<!-- endinject -->
  <!-- Layout styles -->  
	<link rel="stylesheet" href="{{asset('assets/css/sanden/style.css')}}">
  <!-- End layout styles -->
  <link rel="shortcut icon" href="{{asset('img/sm-logo.png')}}" />
</head>
<body style="background: linear-gradient(45deg, #007bff, #7ee5e5);">
    <style>
      
    .sm-logo-sanden{
        padding-top:100px;
        margin-left: 50px;
        justify-content: center;
    }
    </style>
<style>
    .alert-message {
        color: red;
        animation: shake 1s;
    }

    @keyframes shake {
        0% { transform: translateX(0); }
        10% { transform: translateX(-5px); }
        20% { transform: translateX(5px); }
        30% { transform: translateX(-5px); }
        40% { transform: translateX(5px); }
        50% { transform: translateX(-5px); }
        60% { transform: translateX(5px); }
        70% { transform: translateX(-5px); }
        80% { transform: translateX(5px); }
        90% { transform: translateX(-5px); }
        100% { transform: translateX(0); }
    }
</style>
	<div class="main-wrapper">
		<div class="page-wrapper full-page">
			<div class="page-content d-flex align-items-center justify-content-center">

				<div class="row w-100 mx-0 auth-page">
					<div class="col-md-8 col-xl-6 mx-auto">
						<div class="card">
							<div class="row">
                <div class="col-md-4 pr-md-0">
                  <div class="auth-left-wrapper">
                    <div class="sm-logo-sanden">
                 <img src="{{ asset('img/sm-logo.jpg') }}" alt="">

                    </div>
                       
                  </div>
                </div>
                <div class="col-md-8 pl-md-0">
                  <div class="auth-form-wrapper px-4 py-5">
                    <a href="#" class="noble-ui-logo d-block mb-2">Sanden Cold Chain System Philippines Inc.<span></span></a>
                    <h5 class="text-muted font-weight-normal mb-4">Update your password!</h5>
                  @if(session('message'))
    <div class="alert alert-info">
        {{ session('message') }}
    </div>
@endif
                  <h5 class="alert-message">
                  @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
                  </h5>
<form id="changePasswordForm">
    @csrf
    <div class="form-group mt-3">
        <label for="password">New Password</label>
        <input 
            type="password" 
            class="form-control" 
            id="password" 
            name="password" 
            placeholder="Enter new password" 
            required
        >
    </div>

    <div class="form-group mt-3">
        <label for="password_confirmation">Confirm New Password</label>
        <input 
            type="password" 
            class="form-control" 
            id="password_confirmation" 
            name="password_confirmation" 
            placeholder="Confirm new password" 
            required
        >
    </div>

    <div class="mt-4">
        <button class="btn btn-primary text-white" id="updatePass" type="submit">
            Update Password
        </button>
    </div>
</form>


                  </div>
                </div>
              </div>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
    	<script src="{{ asset('assets/js/sweetalert2@11.js') }}"></script>
<script>
document.getElementById('changePasswordForm').addEventListener('submit', async function (e) {
    e.preventDefault();

    const password = document.getElementById('password').value;
    const password_confirmation = document.getElementById('password_confirmation').value;

    try {
        const response = await fetch('{{ route("password.custom.update") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                password,
                password_confirmation,
            }),
        });

        const data = await response.json();

        if (response.ok && data.success) {
            // ✅ SweetAlert success
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: data.message || 'Password updated successfully.',
                confirmButtonText: 'Go to Dashboard',
                confirmButtonColor: '#3085d6',
            }).then(() => {
                window.location.href = '/dashboard';
            });
        } else {
            // ❌ Validation errors
            let errorMessage = 'Something went wrong. Please try again.';

            if (data.errors) {
                errorMessage = Object.values(data.errors).flat().join('\n');
            } else if (data.message) {
                errorMessage = data.message;
            }

            Swal.fire({
                icon: 'error',
                title: 'Validation Failed',
                text: errorMessage,
                confirmButtonColor: '#d33',
            });
        }
    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'An unexpected error occurred. Please try again later.',
        });
        console.error(error);
    }
});
</script>
    	<!-- core:js -->
	<script src="{{asset('assets/vendors/core/core.js')}}"></script>
	<!-- endinject -->
  <!-- plugin js for this page -->
	<!-- end plugin js for this page -->
	<!-- inject:js -->
	<script src="{{asset('assets/vendors/feather-icons/feather.min.js')}}"></script>
	<script src="{{asset('assets/js/template.js')}}"></script>
	<!-- endinject -->
  <!-- custom js for this page -->
	<!-- end custom js for this page -->
</body>
</html>


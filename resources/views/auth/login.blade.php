
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Intranet Login</title>
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
  <link rel="stylesheet" href="{{asset('assets/css/sanden/snowEffect.css')}}">
  <!-- End layout styles -->
  <link rel="shortcut icon" href="{{asset('img/sm-logo.png')}}" />
</head>
<body style="background: linear-gradient(45deg, #007bff, #7ee5e5);">
    <style>
      
 .sm-logo-sanden{
padding-top: 100px;
margin-left: 50px;
justify-content: center;
text-align: center;
}
    </style>
<!-- <style>
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
 
<script>
    window.addEventListener("load", function() {
      document.querySelector(".main").classList.add("loaded");
    });
  </script>
<div class="main">
	<div class="initial-snow">
<div class="snow" style="color: skyblue;">&#10052;</div>
<div class="snow" style="color: skyblue;">&#10052;</div>
<div class="snow" style="color: skyblue;">&#10052;</div>
<div class="snow" style="color: skyblue;">&#10052;</div>
<div class="snow" style="color: skyblue;">&#10052;</div>
<div class="snow" style="color: skyblue;">&#10052;</div>
<div class="snow" style="color: skyblue;">&#10052;</div>
<div class="snow" style="color: skyblue;">&#10052;</div>
<div class="snow" style="color: skyblue;">&#10052;</div>

</div>
</div> -->

<!-- @php
    $images = [
        'ornament.gif',
        'gingerbread.gif.gif',
        'tree.gif',
        'candy cane.gif',
        'bell.gif',
        'stocking.gif',
        'gift.gif',
        'reindeer.gif',
        'candle.gif'
    ];
    $randomImage = $images[array_rand($images)];
@endphp -->
	<div class="main-wrapper">
		<div class="page-wrapper full-page">
			<div class="page-content d-flex align-items-center justify-content-center">

				<div class="row w-100 mx-0 auth-page">
					<div class="col-md-8 col-xl-6 mx-auto">
						<div class="card">
   <!-- <div style="display: flex; position: absolute; right: 0;">
  <img id="christmasImg" src="" alt="Christmas Image" style="width: 100px;">
</div> -->
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
                    <h5 class="text-muted font-weight-normal mb-4">Login your Account</h5>
                  @if(session('message'))
    <div class="alert alert-info">
        {{ session('message') }}
    </div>
@endif
                  <h5 class="alert-message">
                  
                  </h5>
  <form method="POST" action="{{ route('login.process') }}">
    @csrf
     @if(session('error'))
        <p style="color: red;">{{ session('error') }}</p>
    @endif
    <div class="form-group">
        <label for="Username">Username</label>
        <input type="text" class="form-control" id="email" placeholder="Email" name="email">
    </div>
    <div class="form-group">
        <label for="password">Password</label>
        <input type="password" class="form-control" id="password" name="password" placeholder="Password">
    </div>
     <div class="mt-3">
        <button class="btn btn-primary text-white" id="loginbtn" type="submit">Login</button>
    </div>
    <!-- <p class="alert-message" style="font-weight: 700;">Under Maintenance</p> -->
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
<!-- <script>
    const imgEl = document.getElementById('christmasImg');

    fetch('/christmas-images')
        .then(res => res.json())
        .then(images => {
            if(images.length === 0) return;
            imgEl.src = images[0]; // initial image

            setInterval(() => {
                const random = images[Math.floor(Math.random() * images.length)];
                imgEl.src = random;
            }, 5000);
        })
        .catch(err => console.error('Failed to load images:', err));
</script> -->
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


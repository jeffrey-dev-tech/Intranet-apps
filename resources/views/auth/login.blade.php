
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

justify-content: center;
text-align: center;
}

.container-valentine {
  position: fixed;
  bottom: 20px;
  right: 20px;
  transform: scale(0.4);          /* Normal size */
  transform-origin: bottom right;
  z-index: 1000;
}

/* MOBILE */
@media screen and (max-width: 1300px) {
  .container-valentine {
    transform: scale(0.3);        /* Smaller on phones */
  }
}

/* Floating animation */
.valentines {
  position: relative; /* relative to container */
  top: 0;
  left: 0;
  cursor: pointer;
  animation: up 3s linear infinite;
}


@keyframes up {
  0%,100% {transform: translateY(0);}
  50% {transform: translateY(-30px);}
}

/* Envelope */
.envelope {
  position: relative;
  width: 300px;
  height:200px;
  background-color: #f08080;
}

.envelope:before {
  background-color: #f08080;
  content:"";
  position: absolute;
  width: 212px;
  height: 212px;
  transform: rotate(45deg);
  top:-105px;
  left:44px;
  border-radius:30px 0 0 0;
}

/* Card */
.card-valentine {
  position: absolute;
  background-color: #eae2b7;
  width: 270px;
  height: 170px;
  top:5px;
  left:15px;
  box-shadow: -5px -5px 100px rgba(0,0,0,0.4);
}

.card-valentine:before {
  content:"";
  position: absolute;
  border:3px dotted #003049;
  width: 240px;
  height: 140px;
  left:12px;
  top:12px;
}

/* Text */
.text {
  position: absolute;
  font-family: 'Brush Script MT', cursive;
  font-size: 28px;
  text-align: center;
  line-height:25px;
  top:20px;
  left:60px;
  color: #003049;
}

/* Big heart */
.heart {
  background-color: #d62828;
  display: inline-block;
  height: 30px;
  width: 30px;
  position: absolute;
  top: 110px;
  left:120px;
  transform: rotate(-45deg);
}

.heart:before,
.heart:after {
  content: "";
  background-color: #d62828;
  border-radius: 50%;
  height: 30px;
  width: 30px;
  position: absolute;
}

.heart:before { top: -15px; left: 0; }
.heart:after { left: 15px; top: 0; }

/* Floating hearts */
.hearts {
  position: absolute;
  left:120px;
  top:40px;
}

.one,.two,.three,.four,.five{
  background-color:red;
  display:inline-block;
  height:10px;
  width:10px;
  margin:0 10px;
  position:relative;
  transform:rotate(-45deg);
}

.one:before,.one:after,
.two:before,.two:after,
.three:before,.three:after,
.four:before,.four:after,
.five:before,.five:after{
  content:"";
  background:red;
  border-radius:50%;
  height:10px;
  width:10px;
  position:absolute;
}

.one:before,.two:before,.three:before,.four:before,.five:before{top:-5px;}
.one:after,.two:after,.three:after,.four:after,.five:after{left:5px;}

.one{animation:heart 1s infinite;}
.two{animation:heart 2s infinite;}
.three{animation:heart 1.5s infinite;}
.four{animation:heart 2.3s infinite;}
.five{animation:heart 1.7s infinite;}

@keyframes heart{
  0%{transform:translateY(0) rotate(-45deg) scale(0.3);opacity:1;}
  100%{transform:translateY(-150px) rotate(-45deg) scale(1.3);opacity:0;}
}

/* Front of envelope */
.front {
  position: absolute;
  border-right: 180px solid #f4978e;
  border-top: 95px solid transparent;
  border-bottom: 100px solid transparent;
  left:120px;
  top:5px;
  z-index:10;
}

.front:before {
  content:"";
  position:absolute;
  border-left: 300px solid #f8ad9d;
  border-top: 195px solid transparent;
  left:-120px;
  top:-95px;
}

/* Shadow */
.shadow {
  position: absolute;
  width: 330px;
  height: 25px;
  border-radius:50%;
  background: rgba(0,0,0,0.3);
  top:265px;
  left:-15px;
  animation: scale 3s linear infinite;
}

@keyframes scale{
  50%{transform:scale(0.9);}
}


@keyframes scale {
  0%, 100% {
    transform: scaleX(1);
  }
  50% {
    transform: scaleX(0.85);
  }
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
        <input type="text" class="form-control" id="email" placeholder="Username" name="email">
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
  <div class="container-valentine">  
  <div class="valentines">
    <div class="envelope"></div>
    <div class="front"></div>
    <div class="card-valentine">
      <div class="text">
        Happy<br>Hearts <br> Month! <br> Sanden Family!
      </div>
      
    </div>
    <div class="hearts">
      <div class="one"></div>
      <div class="two"></div>
      <div class="three"></div>
      <div class="four"></div>
      <div class="five"></div>
    </div>
    <div class="shadow"></div>
  </div>
</div>

              </div>
						</div>
					</div>
				</div>

			</div>
		</div>
    
	</div>
  
  <script src="assets/js/jquery.js"></script>
  <script>
    $(document).ready(function () {
    $('.container-valentine').mouseover(function () {
        $('.card-valentine').stop().animate({
            top: '-90px'
        }, 'slow');
    }).mouseleave(function () {
        $('.card-valentine').stop().animate({
            top: 0
        }, 'slow');
    });
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


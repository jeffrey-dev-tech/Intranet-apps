<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>@yield('title')</title>

	<!-- core:css -->
	<link rel="stylesheet" href="{{ asset('assets/vendors/core/core.css') }}">
	<!-- endinject -->
    <!-- FullCalendar JS -->
    <script src="{{ asset('assets/js/fullcalendar.index.global.min.js') }}"></script>
	<!-- plugin css for this page -->
	<link rel="stylesheet" href="{{ asset('assets/vendors/datatables.net-bs4/dataTables.bootstrap4.css') }}">
	<!-- end plugin css for this page -->

	<!-- inject:css -->
	<!-- endinject -->

	<!-- Layout styles -->  
	   <link rel="stylesheet" href="{{ asset('assets/vendors/owl.carousel/owl.carousel.min.css')}}">
	    <link rel="stylesheet" href="{{ asset('assets/vendors/owl.carousel/owl.theme.default.min.css')}}">
			    <link rel="stylesheet" href="{{ asset('assets/vendors/animate.css/animate.min.css')}}">
	<link rel="stylesheet" href="{{ asset('assets/css/sanden/style.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/css/sanden/edms.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/fonts/feather-font/css/iconfont.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/vendors/flag-icon-css/css/flag-icon.min.css') }}">
	<!-- End layout styles -->

	<link rel="shortcut icon" href="{{ asset('img/sm-logo.png') }}" />
	<link rel="stylesheet" href="{{ asset('assets/css/fontawesome/css/all.min.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/css/choices.min.css') }}">

	<!-- Scripts -->
	<script src="{{ asset('assets/js/sweetalert2@11.js') }}"></script>
	<script src="{{ asset('assets/js/jquery.js') }}"></script>
	<script src="{{ asset('assets/js/choices.min.js') }}"></script>
	
</head>

<body>@if(Auth::check())
	<div class="main-wrapper">

		<!-- partial:../../partials/_sidebar.html -->
		<nav class="sidebar">
      <div class="sidebar-header">
        <a href="#" class="sidebar-brand">
          Sanden <span>Intranet</span>
        </a>
        <div class="sidebar-toggler not-active">
          <span></span>
          <span></span>
          <span></span>
        </div>
      </div>
      <div class="sidebar-body">
        <ul class="nav">
          <li class="nav-item nav-category">Main</li>
          <li class="nav-item">
            <a href="{{ route('dashboard') }}" class="nav-link">
              <i class="link-icon" data-feather="home"></i>
              <span class="link-title">Dashboard</span>
            </a>
          </li>
		         @auth
@if (in_array(auth()->user()->role, ['user_s3', 'users_s1','developer','users_s2','admin']))
          <li class="nav-item nav-category">web forms</li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#forms" role="button" aria-expanded="false" aria-controls="forms">
        <i class="fa-brands fa-wpforms"></i>
              <span class="link-title">Forms</span>
              <i class="link-arrow" data-feather="chevron-down"></i>
            </a>
            <div class="collapse" id="forms">
              <ul class="nav sub-menu">
				<li class="nav-item"><a href="{{ route('IT.Request.Form') }}" class="nav-link">IT Request Form</a> </li>
				<!-- <li class="nav-item"><a href="{{ route('Shuttle_Form') }}" class="nav-link">Shuttle Form</a> </li> -->
				<li class="nav-item"><a href="{{ route('Deposit_Form') }}" class="nav-link">Deposit Form</a> </li>
				<li class="nav-item"><a href="{{ route('LunchPass_Form') }}" class="nav-link">Lunch Pass Form</a> </li>
				<li class="nav-item"><a href="" class="nav-link">Shuttle Request</a> </li>
              </ul>
            </div>
          </li>
	 @endif
@endauth
  
		         {{-- @auth
@if (in_array(auth()->user()->role, ['user_s3', 'users_s1','developer','users_s2','admin']))
          <li class="nav-item nav-category">General</li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#memo" role="button" aria-expanded="false" aria-controls="memo">
              <i class="link-icon"  data-feather="layers"></i>
              <span class="link-title">Memo</span>
              <i class="link-arrow" data-feather="chevron-down"></i>
            </a>
            <div class="collapse" id="memo">
              <ul class="nav sub-menu">
				<li class="nav-item"><a href="" class="nav-link">List</a> </li>
              </ul>
            </div>
          </li>
	 @endif
@endauth --}}

  @auth
@if (in_array(auth()->user()->role, ['user_s3', 'users_s2','developer','users_s1','admin']))
 <li class="nav-item nav-category">Apps</li>
<li class="nav-item">
    <a class="nav-link" data-toggle="collapse" href="#Policy" role="button" aria-expanded="false" aria-controls="Policy">
        <i class="link-icon" data-feather="folder"></i>
        <span class="link-title">EDMS</span>
        <i class="link-arrow" data-feather="chevron-down"></i>
    </a>
    <div class="collapse" id="Policy">
        <ul class="nav sub-menu">

            <li class="nav-item">
                <a href="{{ route('policy.render', ['department' => 'HR']) }}" class="nav-link">HR</a>
            </li>

            <li class="nav-item">
                <a href="{{ route('policy.render', ['department' => 'ADM']) }}" class="nav-link">ADM</a>
            </li>
			 <li class="nav-item">
                <a href="{{ route('policy.render', ['department' => 'SCM']) }}" class="nav-link">SCM</a>
            </li>
				 <li class="nav-item">
                <a href="{{ route('policy.render', ['department' => 'MIS']) }}" class="nav-link">MIS</a>
            </li>
			    </li>
				 <li class="nav-item">
                <a href="{{ route('policy.render', ['department' => 'PUR']) }}" class="nav-link">PUR</a>
            </li>

            <!-- ✅ Add a nested dropdown here -->
            <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#edcc_upload" role="button" aria-expanded="false" aria-controls="edcc_upload">
                    Module
                    <i class="link-arrow" data-feather="chevron-down"></i>
                </a>
                <div class="collapse" id="edcc_upload">
                    <ul class="nav sub-menu">
                        <li class="nav-item">
                	<a href="{{ route('policies.page.upload') }}" class="nav-link">Upload Policies</a>
                </li>
                    </ul>
                </div>
            </li>
            <!-- ✅ End of nested dropdown -->

        </ul>
    </div>
</li>
@endif
@endauth

     		         @auth
@if (in_array(auth()->user()->role, ['user_s3', 'users_s1','developer','users_s2','admin']))
          <!-- <li class="nav-item nav-category">Data</li> -->
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#form_data" role="button" aria-expanded="false" aria-controls="form_data">
              <i class="link-icon"  data-feather="layout"></i>
              <span class="link-title">Form Data</span>
              <i class="link-arrow" data-feather="chevron-down"></i>
            </a>
            <div class="collapse" id="form_data">
              <ul class="nav sub-menu">
				<li class="nav-item"><a href="{{ route('IT.Request.Data.view') }}" class="nav-link">IT Request Data</a> </li>
              </ul>
            </div>
          </li>
	 @endif
@endauth

	             @auth
  @if (in_array(auth()->user()->role, ['developer']))
   <!-- <li class="nav-item nav-category">Admin</li> -->
		      <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#tables" role="button" aria-expanded="false" aria-controls="tables">
              <i class="link-icon" data-feather="user"></i>
              <span class="link-title">Administrator</span>
              <i class="link-arrow" data-feather="chevron-down"></i>
            </a>
		
            <div class="collapse" id="tables">

        <ul class="nav sub-menu">
            <!-- <li class="nav-item">
                <a href="{{ route('computer.inventory') }}" class="nav-link">Computer Inventory</a>
            </li> -->
			 <li class="nav-item">
                	<a href="{{ route('special-access.index') }}" class="nav-link">Special Access</a>
                </li>
        </ul>
            </div>
          </li>
		   @endif
@endauth

	             @auth
@if (in_array(auth()->user()->role, ['user_s3', 'users_s2','developer','users_s1','admin']))
   <!-- <li class="nav-item nav-category">Docs</li> -->
		      <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#docs" role="button" aria-expanded="false" aria-controls="docs">
          <i class="link-icon fa-solid fa-code-commit"></i>
              <span class="link-title">Documentation</span>
              <i class="link-arrow" data-feather="chevron-down"></i>
            </a>
		
            <div class="collapse" id="docs">

        <ul class="nav sub-menu">
			 <li class="nav-item">
                	<a href="{{ route('versions.view') }}" class="nav-link">Version Control</a>
                </li>
        </ul>
            </div>
          </li>

		  
		   @endif
@endauth

  @auth
@if (in_array(auth()->user()->role, ['user_s3', 'users_s2','developer','users_s1','admin']))
   <!-- <li class="nav-item nav-category">•••</li> -->
		      <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#activity_events" role="button" aria-expanded="false" aria-controls="activity_events">
            <i class="link-icon fa-solid fa-person-running"></i>
              <span class="link-title">Fitness Challenge</span>
              <i class="link-arrow" data-feather="chevron-down"></i>
            </a>
		
            <div class="collapse" id="activity_events">

        <ul class="nav sub-menu">
			 <li class="nav-item">
                	<a href="{{ route('activities.create_view') }}" class="nav-link">Create</a>
                </li>
				 <li class="nav-item">
                	<a href="{{ route('activities.team_registration') }}" class="nav-link">Register</a>
                </li>
				 <li class="nav-item">
                	<a href="{{ route('teams.index') }}" class="nav-link">Teams</a>
                </li>
				<li class="nav-item">
                	<a href="{{ route('activities.log-form') }}" class="nav-link">Submission</a>
                </li>
				
        </ul>
            </div>
          </li>

		  
		   @endif
@endauth
        
        </ul>
      </div>
    </nav>

		<!-- partial -->
	
		<div class="page-wrapper">
				
			<!-- partial:../../partials/_navbar.html -->
			<nav class="navbar">
				<a href="#" class="sidebar-toggler">
					<i data-feather="menu"></i>
				</a>
				<div class="navbar-content">
					<!-- <form class="search-form">
						<div class="input-group">
							<div class="input-group-prepend">
								<div class="input-group-text">
									<i data-feather="search"></i>
								</div>
							</div>
							<input type="text" class="form-control" id="navbarForm" placeholder="Search here...">
						</div>
					</form> -->
					<ul class="navbar-nav">
					
						<li class="nav-item dropdown nav-apps">
							<a class="nav-link dropdown-toggle" href="#" id="appsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<i data-feather="grid"></i>
							</a>
							<div class="dropdown-menu" aria-labelledby="appsDropdown">
								<div class="dropdown-header d-flex align-items-center justify-content-between">
									<p class="mb-0 font-weight-medium">Web Apps</p>
									<a href="javascript:;" class="text-muted">Edit</a>
								</div>
								<div class="dropdown-body">
									<div class="d-flex align-items-center apps">
										{{-- <a href="../../pages/apps/chat.html"><i data-feather="message-square" class="icon-lg"></i><p>Chat</p></a>
										<a href="../../pages/apps/calendar.html"><i data-feather="calendar" class="icon-lg"></i><p>Calendar</p></a>
										<a href="../../pages/email/inbox.html"><i data-feather="mail" class="icon-lg"></i><p>Email</p></a>
										<a href="../../pages/general/profile.html"><i data-feather="instagram" class="icon-lg"></i><p>Profile</p></a> --}}
									</div>
								</div>
								<div class="dropdown-footer d-flex align-items-center justify-content-center">
									<a href="javascript:;">View all</a>
								</div>
							</div>
						</li>
						<!-- <li class="nav-item dropdown nav-messages">
							<a class="nav-link dropdown-toggle" href="#" id="messageDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<i data-feather="mail"></i>
							</a>
							<div class="dropdown-menu" aria-labelledby="messageDropdown">
								<div class="dropdown-header d-flex align-items-center justify-content-between">
									<p class="mb-0 font-weight-medium">9 New Messages</p>
									<a href="javascript:;" class="text-muted">Clear all</a>
								</div>
								<div class="dropdown-body">
									<a href="javascript:;" class="dropdown-item">
										<div class="figure">
											<img src="https://via.placeholder.com/30x30" alt="userr">
										</div>
										<div class="content">
											<div class="d-flex justify-content-between align-items-center">
												<p>Leonardo Payne</p>
												<p class="sub-text text-muted">2 min ago</p>
											</div>	
											<p class="sub-text text-muted">Project status</p>
										</div>
									</a>
									<a href="javascript:;" class="dropdown-item">
										<div class="figure">
											<img src="https://via.placeholder.com/30x30" alt="userr">
										</div>
										<div class="content">
											<div class="d-flex justify-content-between align-items-center">
												<p>Carl Henson</p>
												<p class="sub-text text-muted">30 min ago</p>
											</div>	
											<p class="sub-text text-muted">Client meeting</p>
										</div>
									</a>
									<a href="javascript:;" class="dropdown-item">
										<div class="figure">
											<img src="https://via.placeholder.com/30x30" alt="userr">
										</div>
										<div class="content">
											<div class="d-flex justify-content-between align-items-center">
												<p>Jensen Combs</p>												
												<p class="sub-text text-muted">1 hrs ago</p>
											</div>	
											<p class="sub-text text-muted">Project updates</p>
										</div>
									</a>
									<a href="javascript:;" class="dropdown-item">
										<div class="figure">
											<img src="https://via.placeholder.com/30x30" alt="userr">
										</div>
										<div class="content">
											<div class="d-flex justify-content-between align-items-center">
												<p>Amiah Burton</p>
												<p class="sub-text text-muted">2 hrs ago</p>
											</div>
											<p class="sub-text text-muted">Project deadline</p>
										</div>
									</a>
									<a href="javascript:;" class="dropdown-item">
										<div class="figure">
											<img src="https://via.placeholder.com/30x30" alt="userr">
										</div>
										<div class="content">
											<div class="d-flex justify-content-between align-items-center">
												<p>Yaretzi Mayo</p>
												<p class="sub-text text-muted">5 hr ago</p>
											</div>
											<p class="sub-text text-muted">New record</p>
										</div>
									</a>
								</div>
								<div class="dropdown-footer d-flex align-items-center justify-content-center">
									<a href="javascript:;">View all</a>
								</div>
							</div>
						</li> -->
						<!-- <li class="nav-item dropdown nav-notifications">
							<a class="nav-link dropdown-toggle" href="#" id="notificationDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<i data-feather="bell"></i>
								<div class="indicator">
									<div class="circle"></div>
								</div>
							</a>
							<div class="dropdown-menu" aria-labelledby="notificationDropdown">
								<div class="dropdown-header d-flex align-items-center justify-content-between">
									<p class="mb-0 font-weight-medium">6 New Notifications</p>
									<a href="javascript:;" class="text-muted">Clear all</a>
								</div>
								<div class="dropdown-body">
									<a href="javascript:;" class="dropdown-item">
										<div class="icon">
											<i data-feather="user-plus"></i>
										</div>
										<div class="content">
											<p>New customer registered</p>
											<p class="sub-text text-muted">2 sec ago</p>
										</div>
									</a>
									<a href="javascript:;" class="dropdown-item">
										<div class="icon">
											<i data-feather="gift"></i>
										</div>
										<div class="content">
											<p>New Order Recieved</p>
											<p class="sub-text text-muted">30 min ago</p>
										</div>
									</a>
									<a href="javascript:;" class="dropdown-item">
										<div class="icon">
											<i data-feather="alert-circle"></i>
										</div>
										<div class="content">
											<p>Server Limit Reached!</p>
											<p class="sub-text text-muted">1 hrs ago</p>
										</div>
									</a>
									<a href="javascript:;" class="dropdown-item">
										<div class="icon">
											<i data-feather="layers"></i>
										</div>
										<div class="content">
											<p>Apps are ready for update</p>
											<p class="sub-text text-muted">5 hrs ago</p>
										</div>
									</a>
									<a href="javascript:;" class="dropdown-item">
										<div class="icon">
											<i data-feather="download"></i>
										</div>
										<div class="content">
											<p>Download completed</p>
											<p class="sub-text text-muted">6 hrs ago</p>
										</div>
									</a>
								</div>
								<div class="dropdown-footer d-flex align-items-center justify-content-center">
									<a href="javascript:;">View all</a>
								</div>
							</div>
						</li> -->
<li class="nav-item dropdown nav-notifications">
    <a class="nav-link dropdown-toggle" href="#" id="notificationDropdown" role="button"
       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i data-feather="bell"></i>
        <div class="indicator d-none" id="notif-indicator">
            <div class="circle"></div>
        </div>
    </a>
    <div class="dropdown-menu" aria-labelledby="notificationDropdown" style="width: 320px;">
        <div class="dropdown-header d-flex align-items-center justify-content-between">
            <p class="mb-0 font-weight-medium" id="notif-count">Loading...</p>
            <a href="javascript:;" class="text-muted">Clear all</a>
        </div>
        <!-- Scrollable notification list -->
        <div class="dropdown-body" id="notif-list" style="max-height: 300px; overflow-y: auto;">
            <p class="text-center text-muted m-2">Loading...</p>
        </div>
    </div>
</li>



						<li class="nav-item dropdown nav-profile">
							<a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<i class="fas fa-user"></i>
							</a>
							<div class="dropdown-menu" aria-labelledby="profileDropdown">
								<div class="dropdown-header d-flex flex-column align-items-center">
									<div class="figure mb-3">
										<i class="fas fa-user"></i>
							@if(Auth::check())
								Welcome,<br> {{ Auth::user()->name }}!
								<br>
								{{ Auth::user()->email }}!
							@endif
									</div>
									<div class="info text-center">
										
									</div>
								</div>
								
								<div class="dropdown-body">
									<ul class="profile-nav p-0 pt-3">
										<!-- <li class="nav-item">
											<a href="#" class="nav-link">
												<i data-feather="user"></i>
												<span>Profile</span>
											</a>
										</li>
										<li class="nav-item">
											<a href="javascript:;" class="nav-link">
												<i data-feather="edit"></i>
												<span>Edit Profile</span>
											</a>
										</li>
									 -->	<li class="nav-item">
											<a class="nav-link" type="button" class="btn btn-warning" data-toggle="modal" data-target="#changePasswordModal">
												<i data-feather="repeat"></i>
												<span>Change Password</span>
											</a>
										</li>
									<li class="nav-item">
    <form method="POST" action="{{ route('logout') }}" id="logout-form">
        @csrf
        <a href="#" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i data-feather="log-out"></i>
            <span>Log Out</span>
        </a>
    </form>
</li>

									</ul>
								</div>
							</div>
						</li>
					</ul>
				</div>
			</nav> 
<div class="modal fade" id="requestModal" tabindex="-1" role="dialog" aria-labelledby="requestModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="requestModalLabel">IT Request Details</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="requestModalBody">
        <p class="text-center text-muted">Loading...</p>
      </div>
    </div>
  </div>
</div>
<script>
async function loadNotifications() {
    try {
        const response = await fetch("{{ route('notifications.index') }}", {
            credentials: 'same-origin',
            headers: { 'Accept': 'application/json' }
        });

        const data = await response.json();

        const notifCount = document.getElementById("notif-count");
        const indicator = document.getElementById("notif-indicator");
        const list = document.getElementById("notif-list");

        notifCount.textContent = `${data.count || 0} New Notifications`;
        list.innerHTML = "";

        if (data.count > 0) {
            indicator.classList.remove("d-none");

            // 🔹 Config for notification types
            const notificationConfig = {
                "IT Request Approval": {
                    icon: "check-circle",
                    url: id => `/approvals/${id}`,
                    label: "IT Request Approval"
                },
                "IT Request": {
                    icon: "file-text",
                    url: id => `/requests/${id}`,
                    label: "Request Pending"
                },
                "Fitness Challenge": {
                    icon: "activity",
                    url: logId => `{{ route('approvalForm.activity', ':log_id') }}`.replace(':log_id', logId),
                    label: "Fitness Challenge Approval"
                }
            };

            data.notifications.forEach(notif => {
                const item = document.createElement("a");
                item.className = "dropdown-item";
                item.target = "_blank"; // 🔥 Open in new tab
                item.rel = "noopener noreferrer"; // 🔐 Security best practice

                // 🔹 Use config if available, else fallback
                const config = notificationConfig[notif.type] || {
                    icon: "bell",
                    url: "#",
                    label: notif.type || "Notification"
                };

                // 🔹 Use log_id for Fitness Challenge, id for others
                item.href = config.url(notif.log_id || notif.id);

                item.innerHTML = `
                    <div class="icon">
                        <i data-feather="${config.icon}"></i>
                    </div>
                    <div class="content">
                        <p>${notif.title}</p>
                        <p class="sub-text text-muted">${config.label}</p>
                    </div>
                `;

                list.appendChild(item);
            });

            if (window.feather) feather.replace();

        } else {
            indicator.classList.add("d-none");
            list.innerHTML = `<p class="text-center text-muted m-2">No pending notifications</p>`;
        }
    } catch (error) {
        console.error("Error loading notifications:", error);
        document.getElementById("notif-list").innerHTML = `<p class="text-center text-danger m-2">Error loading notifications</p>`;
    }
}

document.addEventListener("DOMContentLoaded", () => {
    loadNotifications();
});
</script>




			
			
			@endif
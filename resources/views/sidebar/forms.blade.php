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
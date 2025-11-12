

  @auth
@if (in_array(auth()->user()->role, ['user_s3','users_s2','admin']))
   <!-- <li class="nav-item nav-category">•••</li> -->
		      <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#activity_events" role="button" aria-expanded="false" aria-controls="activity_events">
            <i class="link-icon fa-solid fa-person-running"></i>
              <span class="link-title">Wellness Program</span>
              <i class="link-arrow" data-feather="chevron-down"></i>
            </a>
		
            <div class="collapse" id="activity_events">

        <ul class="nav sub-menu">
			
				 <li class="nav-item">
                	<a href="{{ route('activities.team_registration') }}" class="nav-link">Register</a>
                </li>
				 <li class="nav-item">
                	<a href="{{ route('teams.index') }}" class="nav-link">Teams</a>
                </li>
				<li class="nav-item">
                	<a href="{{ route('activities.log-form') }}" class="nav-link">Submission</a>
		
				
        </ul>
            </div>
          </li>

		  
@endif
@endauth



  @auth
@if (in_array(auth()->user()->role, ['users_s1','developer']))
   <!-- <li class="nav-item nav-category">•••</li> -->
     <li class="nav-item nav-category">Apps</li>
		      <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#activity_events" role="button" aria-expanded="false" aria-controls="activity_events">
            <i class="link-icon fa-solid fa-person-running"></i>
              <span class="link-title">Wellness Program</span>
              <i class="link-arrow" data-feather="chevron-down"></i>
            </a>
		
            <div class="collapse" id="activity_events">

        <ul class="nav sub-menu">
			
				 <li class="nav-item">
                	<a href="{{ route('activities.team_registration') }}" class="nav-link">Register</a>
                </li>
				 <li class="nav-item">
                	<a href="{{ route('teams.index') }}" class="nav-link">Teams</a>
                </li>
				<li class="nav-item">
                	<a href="{{ route('activities.log-form') }}" class="nav-link">Submission</a>
			<li class="nav-item">
                	<a href="{{ route('activities.create_view') }}" class="nav-link">Create</a>
                </li>
                </li>
					<li class="nav-item">
                	<a href="{{ route('activities.view.list') }}" class="nav-link">Activity List</a>
                </li>

				
        </ul>
            </div>
          </li>

		  
		   @endif
@endauth

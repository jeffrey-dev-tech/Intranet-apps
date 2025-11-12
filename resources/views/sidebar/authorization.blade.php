
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
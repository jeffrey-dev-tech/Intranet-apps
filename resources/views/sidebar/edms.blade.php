@auth
    @if (session('subsystem') === 'edms' && in_array(auth()->user()->role, ['user_s3', 'users_s2', 'developer', 'users_s1', 'admin']))
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
                    <li class="nav-item">
                        <a href="{{ route('policy.render', ['department' => 'PUR']) }}" class="nav-link">PUR</a>
                    </li>

                    <!-- Nested dropdown -->
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
                </ul>
            </div>
        </li>
    @endif
@endauth

@extends('layouts.app')

@section('title', 'Dashboard')
@section('content')
<style>

    .maintenance {
        text-align: center;
        padding: 60px;
    }
   
    .btn {
        display: inline-block;
        padding: 12px 24px;
        margin: 10px;
        border-radius: 6px;
        text-decoration: none;
        font-weight: bold;
        color: #fff;
        transition: 0.3s;
        border: none;
        cursor: pointer; /* <-- Added for all buttons */
    }
    .btn-back {
        background-color: #38a169;
    }
    .btn-back:hover {
        background-color: #2f855a;
    }
    .btn-logout {
        background-color: #e3342f;
    }
    .btn-logout:hover {
        background-color: #cc1f1a;
    }
</style>


<div class="page-content">

   	<div class="row">
					<div class="col-md-12 stretch-card">
						<div class="card">
							<div class="card-body">

    <div class="maintenance">


    <h1>🚧 This page is under Maintenance</h1>
    <p>We're working on improvements. Please check back later.</p>
    <p>*** MIS ***</p>
    <!-- Back Button -->
    <a href="{{ url()->previous() }}" class="btn btn-back">⬅ Back</a>

    <!-- Logout Button -->
    {{-- <form method="POST" action="{{ route('logout') }}" id="logout-form" style="display:inline;">
        @csrf
        <button type="submit" class="btn btn-logout">Logout</button>
    </form> --}}
        </div>
    </div>
     </div>
      </div>
       </div>
</div>
@endsection

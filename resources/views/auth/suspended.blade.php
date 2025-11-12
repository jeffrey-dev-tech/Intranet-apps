@extends('layouts.app')

@section('content')
<div class="container text-center mt-5">
    <h1 class="text-danger">Account Suspended</h1>
    <p>Your account is suspended</p>
 
    <p>If you believe this is a mistake, please contact support.</p>
<p>
  <a href="mailto:mis.scp@sanden-rs.com">mis.scp@sanden-rs.com</a>
</p>
      <a href="{{ route('login') }}" class="nav-link">Back</a>
</div>
@endsection

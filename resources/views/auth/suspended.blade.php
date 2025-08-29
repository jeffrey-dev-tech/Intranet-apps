@extends('layouts.app')

@section('content')
<div class="container text-center mt-5">
    <h1 class="text-danger">Account Suspended</h1>
    <p>Your account is suspended until:</p>
    <h2>{{ $until->format('F j, Y \\a\\t g:i A') }}</h2>
 
    <p>If you believe this is a mistake, please contact support.</p>
      <a href="{{ route('login') }}" class="nav-link">Back</a>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Registration')

@section('content')
<div class="page-content">
  <nav class="page-breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="#"></a></li>
      <li class="breadcrumb-item " aria-current="page">Fitness Challenge</li>
         <li class="breadcrumb-item active" aria-current="page">Registration</li>
    </ol>
  </nav>

  <div class="row">
    <div class="col-md-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <h6 class="card-title">Registration Form</h6>

          <form id="activities-form" action="#" method="post">
            <div id="wizardVertical">

              <!-- Step 1 -->
              <h2>Employee Details</h2>
              <section>
                <div class="form-group">
                  <label for="fullName">Full Name</label>
                  <input type="text" class="form-control" id="fullName" name="fullName" required>
                </div>
                <div class="form-group">
                  <label for="email">Email Address</label>
                  <input type="email" class="form-control" id="email" name="email" value="{{ Auth::user()->email }}" readonly>
                </div>
                <div class="form-group">
                  <label for="phone">Phone</label>
                  <input type="text" class="form-control" id="phone" name="phone">
                </div>
              </section>

              <!-- Step 2 -->
              <h2>Team</h2>
              <section>
                <div class="form-group">
                  <label for="department">Department</label>
                  <select class="form-control" id="department" name="department">
                    <option>Engineering</option>
                    <option>Marketing</option>
                    <option>Sales</option>
                    <option>HR</option>
                  </select>
                </div>
                <div class="form-group">
                  <label for="manager">Manager</label>
                  <input type="text" class="form-control" id="manager" name="manager">
                </div>
              </section>

              <!-- Step 3 -->
              <h2>Level</h2>
              <section>
                <div class="form-group">
                  <label for="role">Job Role</label>
                  <input type="text" class="form-control" id="role" name="role">
                </div>
                <div class="form-group">
                  <label for="level">Job Level</label>
                  <select class="form-control" id="level" name="level">
                    <option>Junior</option>
                    <option>Mid</option>
                    <option>Senior</option>
                    <option>Lead</option>
                  </select>
                </div>

                <!-- 🔥 Logo under the Level section content -->
                <div class="text-center mt-4">
                  <img src="{{ asset('img/Sample Logo.png') }}" 
                       alt="Logo" 
                       class="img-fluid" 
                       style="max-height: 300px;">
                </div>
              </section>

            </div>
          </form>

        </div>
      </div>
    </div>
  </div>
</div>

<script src="{{ asset('assets/js/jquery.js') }}"></script>
<link rel="stylesheet" href="{{ asset('assets/vendors/jquery-steps/jquery.steps.css') }}">

@endsection

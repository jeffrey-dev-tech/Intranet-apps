@extends('layouts.app')

@section('title', 'Registration')

@section('content')
    	
    
    <div class="page-content">
    	<nav class="page-breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="#"></a></li>
						<li class="breadcrumb-item active" aria-current="page">Fitness Challenge</li>
            <li class="breadcrumb-item" aria-current="page">Statistics</li>
					</ol>
				</nav>
    <div class="row">
					<div class="col-md-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <h6 class="card-title">Statistics</h6>
            
                <div class="table-responsive">
        
                    <div id="hardwareTableContainer">

                    </div>
                    
                </div>
              </div>
            </div>
					</div>
				</div> 
                </div>



<style>
  #dataTableExample td, #dataTableExample th {
    white-space: normal;
    word-wrap: break-word;
    word-break: break-word;
  }
</style>
<script src="assets/js/jquery.js"></script>

@endsection

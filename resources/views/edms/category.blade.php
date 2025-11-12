@extends('layouts.app')

@section('title', 'EDMS Category')

@section('content')
<!-- css assets/css/sanden/edms.css -->
</head>
<body>
<style>
 
</style>

  <div class="page-content">
      <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">EDMS</a></li>
            <li class="breadcrumb-item active" aria-current="page">Category</li>
        </ol>
    </nav>

<div class="card">
							<div class="card-body">

	                  <div class="sanden-logo">
										<img src="{{ asset('img/Sanden_Logo_SCP2_.png') }}" alt="sanden-logo">
                    <hr>
										<div class="title-form">
											<h6 class="card-title" style="font-size:25px;">Electronic Document Management System Category</h6>
										</div>
									</div>

              {{-- <table id="categoryTable" class="table table-bordered table-hover align-middle">
    <thead>
        <tr>
            <th style="width: 60px;">ID</th>
            <th>Category Name</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($results as $category)
            <tr>
                <td>{{ $category->id }}</td>
                <td> <a href="{{ route('document.render', ['category_id' => $category->id,'category_name'=> $category->name]) }}" 
                target="_blank" >
              {{ $category->name }}
                </a></td>
                
            </tr>
        @endforeach
    </tbody>
</table> --}}
<hr>
<style>
    .category-link {
        color: rgb(53, 32, 32);
        text-decoration: none;
        transition: color 0.3s ease, text-shadow 0.3s ease;
    }

    .category-link:hover {
        color: #1ab467; /* darker blue */
        text-shadow: 0 0 5px rgb(230, 230, 230);
    
    }
</style>

<div class="row">
    @foreach ($results as $category)
        <div class="col-md-6 mb-3">
            <div class="p-3 border rounded d-flex justify-content-between align-items-center" 
                 style="background: #d2eaff;">
                  R{{ $category->id }}
                <div class="d-flex align-items-center">
                    <div>
                        <a href="{{ route('document.render', ['category_id' => $category->id, 'category_name'=> $category->name]) }}" 
                           target="_blank" class="category-link">
                            {{ $category->name }}
                        </a>
                    </div>
                </div>
  <div style="display: flex; flex-direction: column; align-items: center;">
  <img 
    src="{{ asset('img/folder.png') }}" alt="Folder" style="width: 30px; height: 30px; display: block; margin: 0; padding: 0;">
  <p style="margin: 0; padding: 0; font-weight: 500; color: #333;">
    {{ $category->policies_count ?? 0 }} Docs
  </p>
</div>
</div>
        </div>
    @endforeach
</div>

               
  </div>
  </div>

   





  </div>

 <!-- <script>
        $(document).ready(function() {
            $('#categoryTable').DataTable({
                pageLength: 10,   // Default rows per page
                lengthMenu: [5, 10, 25, 50],
                order: [[0, 'asc']], // Sort by ID column ascending
                paging: false ,
                info: false
            });
        });
    </script> -->
@endsection
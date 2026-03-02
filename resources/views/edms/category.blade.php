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
<div class="col-md-12 mb-3">
    <input type="text" id="categorySearch" class="form-control" placeholder="Search categories...">
</div>
<hr>
<style>
/* Grid container for folders */
.folder-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 20px;
    padding: 15px 0;
}

/* Each folder tile */
.folder-tile {
    background: #f3f4f6;
    border-radius: 15px;
    padding: 15px 10px;
    text-align: center;
    cursor: pointer;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 160px;
    position: relative;
    transition: transform 0.25s ease, box-shadow 0.25s ease, background 0.25s ease;
}

/* Hover effect */
.folder-tile:hover {
    transform: translateY(-8px) scale(1.02);
    box-shadow: 0 16px 28px rgba(0,0,0,0.25), 0 0 18px rgba(26,180,103,0.3);
    background: linear-gradient(135deg, #e0f7ff, #d2eaff);
}

/* Folder icon */
.folder-tile i.closed {
    font-size: 60px;
    color: #87CEEB;
    margin: 5px 0;
    transition: transform 0.3s ease, color 0.3s ease;
}

.folder-tile i.open {
    font-size: 60px;
    color: #87CEEB;
    display: none;
    margin: 5px 0;
    transition: transform 0.3s ease, color 0.3s ease;
}

/* Switch folder icon on hover */
.folder-tile:hover i.closed { display: none; }
.folder-tile:hover i.open { display: inline-block; transform: scale(1.15); }

/* Folder ID badge */
.folder-id {
    font-size: 13px;
    font-weight: 600;
    color: #fff;
    background: #1ab467;
    padding: 2px 8px;
    border-radius: 12px;
    position: absolute;
    top: 8px;
    left: 8px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
}

/* Folder name text */
.folder-name {
    font-size: 16px;
    font-weight: 600;
    color: #494949;
    margin-top: 8px;
    margin-bottom: 4px;
    text-decoration: none;
    word-break: break-word;
    transition: color 0.2s ease;
}

/* Change name color on hover */
.folder-tile:hover .folder-name {
    color: #1ab467;
}

/* Number of docs */
.folder-docs {
    font-size: 13px;
    font-weight: 500;
    color: #555;
    transition: color 0.2s ease;
}

/* Highlight docs count on hover */
.folder-tile:hover .folder-docs {
    color: #1ab467;
}
</style>


<div class="folder-grid">
    @foreach ($results as $category)
        <a href="{{ route('document.render', ['category_id' => $category->id, 'category_name'=> $category->name]) }}" 
           target="_blank" class="folder-tile">
            <div class="folder-id">R{{ $category->id }}</div>
            <i class="fa-solid fa-folder closed"></i>
            <i class="fa-solid fa-folder-open open"></i>
            <span class="folder-name">{{ $category->name }}</span>
            <span class="folder-docs">{{ $category->policies_count ?? 0 }} Docs</span>
        </a>
    @endforeach
</div>           
  </div>
  </div>
  </div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('categorySearch');
        const folderTiles = document.querySelectorAll('.folder-tile');

        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase();

            folderTiles.forEach(tile => {
                const name = tile.querySelector('.folder-name').textContent.toLowerCase();
                if (name.includes(query)) {
                    tile.style.display = 'flex';
                } else {
                    tile.style.display = 'none';
                }
            });
        });
    });
</script>

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
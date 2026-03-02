@extends('layouts.app')
@section('title', 'Cold Chain')
@section('content')

<div class="page-content">
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Forms</a></li>
            <li class="breadcrumb-item active" aria-current="page">Cold Chain Service Report</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-12 stretch-card">
            <div class="card">
                <div class="card-body">

                    <!-- Logo & Title -->
                    <div class="sanden-logo text-center mb-4">
                        <img src="{{ asset('img/Sanden_Logo_SCP2_.png')}}" alt="sanden-logo" class="img-fluid mb-2" style="max-width: 700px;">
                        <hr>
                        <h6 class="card-title" style="font-size:25px;">Cold Chain Service Report</h6>
                    </div>

                    <!-- Folder Dropdown -->
<div class="col-md-4 mb-4">
    <form method="GET" action="{{ route('Gdrive.list') }}">
        <label for="folder_id">Select Month Folder:</label>
        <select name="folder_id" id="folder_id" class="form-control" onchange="this.form.submit()">
            <option value="" disabled {{ is_null($selectedFolderId) ? 'selected' : '' }}>Choose Folder</option>
            @foreach($monthFolders as $folder)
                <option value="{{ $folder['id'] }}" {{ $folder['id'] === $selectedFolderId ? 'selected' : '' }}>
                    {{ $folder['name'] }}
                </option>
            @endforeach
        </select>
    </form>
</div>




                    <!-- Table or Message -->
            @if($selectedFolderId)
    @if(count($files) > 0)
        <table id="filesTable" class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Size</th>
                    <th>Created</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($files as $i => $file)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $file['name'] }}</td>
                    <td>
                        @php
                            $mime = $file['mimeType'] ?? '';
                            $type = 'Unknown';
                            if(str_contains($mime, 'image/')) $type='Image';
                            elseif(str_contains($mime, 'video/')) $type='Video';
                            elseif(str_contains($mime, 'audio/')) $type='Audio';
                            elseif(str_contains($mime, 'pdf')) $type='PDF';
                            elseif(str_contains($mime, 'word')) $type='Word Document';
                            elseif(str_contains($mime, 'excel')) $type='Excel Spreadsheet';
                            elseif(str_contains($mime, 'text/')) $type='Text File';
                            elseif(str_contains($mime, 'zip')) $type='Compressed File';
                        @endphp
                        {{ $type }}
                    </td>
                    <td>
                        @php
                            $size = $file['size'] ?? 0;
                            if($size >= 1024*1024*1024) $displaySize = number_format($size/1024/1024/1024,2).' GB';
                            elseif($size >= 1024*1024) $displaySize = number_format($size/1024/1024,2).' MB';
                            elseif($size >= 1024) $displaySize = number_format($size/1024,2).' KB';
                            else $displaySize = $size.' B';
                        @endphp
                        {{ $displaySize }}
                    </td>
                    <td>{{ $file['createdTime'] }}</td>
                    <td>
                        <a href="{{ route('ColdChainServiceReport.download', [$file['id'], $file['name']]) }}" class="btn btn-sm btn-primary">Download</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p class="text-muted">No files found in this folder.</p>
    @endif
@else
    <p class="text-muted">Please select a folder to view files.</p>
@endif


                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    if ($('#filesTable').length) {
        $('#filesTable').DataTable({
            paging: true,
            searching: true,
            ordering: true,
            info: true,
            responsive: true
        });
    }
});

</script>

@endsection

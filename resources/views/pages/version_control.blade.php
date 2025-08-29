@if (auth()->check())
@extends('layouts.app')

@section('title', 'Version Control')

@section('content')

<div class="page-content">
<div class="card">
<div class="card-body">

<div class="sanden-logo">
<img src="img/Sanden_Logo_SCP2_.png" alt="sanden-logo">
<hr>
<div class="title-form">
<h6 class="card-title" style="font-size:25px;">Sanden Intranet Version Control</h6>
</div>
</div>
<div class="table-responsive">

<div id="versionTable">

<table  id="dataTableExample" class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Version</th>
            <th>Updates / Changes</th>
            <th>Date Release</th>
            <th>Author</th>
        </tr>
    </thead>
    <tbody>
        @foreach($versions as $version)
            <tr>
                <td>{{ $version->id }}</td>
                <td>{{ $version->version }}</td>
                <td>{{ $version->updates }}</td>
                <td>{{ $version->date_release }}</td>
                <td>{{ $version->author }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
</div>


</div>


</div>
</div>





</div>



@endif
@endsection
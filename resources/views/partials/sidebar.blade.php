@php
    $subsystem = session('subsystem');
@endphp

@if ($subsystem === 'edms')
    @include('sidebar.edms')
@endif

@if ($subsystem === 'forms')
    @include('sidebar.forms')
@endif

@if ($subsystem === 'docs')
    @include('sidebar.docs')
@endif
@extends('canvas::admin.layouts.app')

@section('title', __('Project'))
@section('content')

@include('projects.menus')

<div class="card">
    <div class="card-body">
        {!! $html->table() !!}
        {!! $html->scripts() !!}
    </div>
</div>

@endsection

@push('scripts')

@endpush

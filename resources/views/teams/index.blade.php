@extends('canvas::admin.layouts.app')

@section('title', __('Teams'))
@section('content')
<div class="card">
    <div class="card-header">
        <div class="float-left">Teams</div>
        <div class="float-right">
            <a href="{{ route('projects.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> {{ __('Add Team') }}</a>
        </div>
    </div>
    <div class="card-body">
        {!! $html->table() !!}
        {!! $html->scripts() !!}
    </div>
</div>
@endsection

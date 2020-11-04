@extends('canvas::admin.layouts.app')

@section('title', __('Teams'))
@section('content')
<div class="row my-2">
    <div class="col-md">
        <h1>@yield('title')</h1>
    </div>
    <div class="col-md-auto mb-3 mb-md-0">
        <a href="{{ route('teams.create') }}" class="btn btn-primary">{{ __('Create Team') }}</a>
    </div>
</div>
<div class="card">
    <div class="card-body">
        {!! $html->table() !!}
        {!! $html->scripts() !!}
    </div>
</div>
@endsection

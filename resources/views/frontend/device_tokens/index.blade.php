@extends('canvas::frontend.layouts.app')

@section('title', __('Device Tokens'))
@section('content')
<div class="row my-2">
    <div class="col-md">
        <h1>@yield('title')</h1>
    </div>
    <div class="col-md-auto mb-3 mb-md-0">
        <a href="{{ route('device_tokens.create') }}" class="btn btn-primary">{{ __('Create Device Token') }}</a>
    </div>
</div>
<div class="card">
    <div class="card-body">
        {!! $html->table() !!}
        {!! $html->scripts() !!}
    </div>
</div>
@endsection

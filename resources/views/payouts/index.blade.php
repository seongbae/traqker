@extends('canvas::admin.layouts.app')

@section('title', __('Payouts'))
@section('content')
<div class="row my-2">
    <div class="col-md">
        <h1>@yield('title')</h1>
    </div>
    <div class="col-md-auto mb-3 mb-md-0">
        <a href="{{ route('payouts.create') }}" class="btn btn-primary">{{ __('Create Payout') }}</a>
    </div>
</div>
<div class="card">
    <div class="card-body">
        {!! $html->table() !!}
        {!! $html->scripts() !!}
    </div>
</div>
@endsection

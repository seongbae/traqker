@extends('canvas::admin.layouts.app')

@section('title', __('Calendar'))
@section('content')

<div class="card">
    <div class="card-body">
        <div id='calendar' class="h-100 w-100"></div>
    </div>
</div>

@endsection

@push('scripts')

@endpush

@extends('canvas::admin.layouts.app')

@section('content')


<div class="card">
  <div class="card-body">
    {{$dataTable->table()}}
    </div>
</div>

@stop

@push('scripts')
{{$dataTable->scripts()}}
@endpush
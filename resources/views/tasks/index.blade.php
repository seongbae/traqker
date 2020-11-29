@extends('canvas::admin.layouts.app')

@section('title', __('Tasks'))
@section('content')

    <div class="container">
        <div class="row my-2">
            <div class="col-md-auto mb-3 mb-md-0">
                <a href="{{ route('tasks.create') }}" class="btn btn-primary btn-sm">{{ __('Create Task') }}</a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-body">
                        {!! $html->table() !!}
                        {!! $html->scripts() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')

    <script type="text/javascript">

        $(document).ready(function(){


        });
    </script>
@endpush

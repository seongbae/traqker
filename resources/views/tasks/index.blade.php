@extends('canvas::admin.layouts.app')

@section('title', __('Tasks'))
@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-body">
                        {!! $html->table() !!}
                        {!! $html->scripts() !!}
                    </div>
                    <div class="card-footer px-3">
                        <a href="/tasks" class="text-secondary m-1"><i class="far fa-square" title="Active Tasks"></i></a>
                        <a href="/tasks/completed" class="text-secondary m-1"><i class="far fa-check-square" title="Completed Tasks"></i></a>
                        <a href="/tasks/deleted" class="text-secondary m-1"><i class="far fa-trash-alt " title="Deleted Tasks"></i></a>
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

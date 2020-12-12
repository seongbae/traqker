@extends('canvas::admin.layouts.app')

@section('title', __('Project'))
@section('content')

@include('projects.menus')

<div id='gantt'></div>

@endsection

@push('scripts')
    <script>
        var tasks = @json($tasks);

        console.log(tasks);

        var gantt = new Gantt("#gantt", tasks,{
            header_height: 50,
            column_width: 30,
            step: 24,
            view_modes: ['Quarter Day', 'Half Day', 'Day', 'Week', 'Month'],
            bar_height: 20,
            bar_corner_radius: 3,
            arrow_curve: 5,
            padding: 18,
            view_mode: 'Day',
            date_format: 'YYYY-MM-DD',
            custom_popup_html: null
        });
    </script>
@endpush

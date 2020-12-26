@extends('canvas::admin.layouts.app')

@section('title', __('Project'))
@section('content')

@include('projects.menus')

<div id='gantt'></div>

@endsection

@push('scripts')
    <script>
        var tasks = @json($tasks);

        //console.log(tasks);

        var gantt = new Gantt("#gantt", tasks,{
            header_height: 50,
            column_width: 30,
            step: 24,
            view_modes: ['Quarter Day', 'Half Day', 'Day', 'Week', 'Month'],
            bar_height: 24,
            bar_corner_radius: 3,
            arrow_curve: 5,
            padding: 18,
            view_mode: 'Day',
            date_format: 'YYYY-MM-DD',
            custom_popup_html: function(task) {
                    //console.log(task);
                    // the task object will contain the updated
                    // dates and progress value
                    //const end_date = task.end.format('YYYY-MM-DD');
                    return `
                    <div class="title">${task.name}</div>
                    <div class="subtitle">${task.start} - ${task.end}</div>
                    <div class="subtitle">${task.description}</div>
                  `;
                },
            on_click: function (task) {
                //console.log(task);
            },
            on_date_change: function(task, start, end) {
                var start = moment(start).format("YYYY-MM-DD HH:mm:ss");
                var end = moment(end).subtract(1, "minute").format("YYYY-MM-DD HH:mm:ss");
                var csrf = $('meta[name="csrf-token"]'). attr('content');

                $.ajax({
                    url: '/tasks/'+task.id,
                    data: {
                        _token: csrf,
                        _method: "PUT",
                        start_on: start,
                        due_on: end
                    },
                    type: "POST",
                    success: function (response) {

                    }
                });
            },
            on_progress_change: function(task, progress) {
                var csrf = $('meta[name="csrf-token"]'). attr('content');

                $.ajax({
                    url: '/tasks/'+task.id,
                    data: {
                        _token: csrf,
                        _method: "PUT",
                        progress: progress
                    },
                    type: "POST",
                    success: function (response) {

                    }
                });
            },
            on_view_change: function(mode) {
                //console.log(mode);
            }
        });
    </script>
@endpush

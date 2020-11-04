@extends('canvas::admin.layouts.app')

@section('title', __('Project'))
@section('content')

    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="list-tab" data-toggle="tab" href="#list" role="tab" aria-controls="list" aria-selected="true">List</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="calendar-tab" data-toggle="tab" href="#calendar-tab-content" role="tab" aria-controls="calendar" aria-selected="false">Calendar</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="files-tab" data-toggle="tab" href="#files" role="tab" aria-controls="files" aria-selected="false">Files</a>
        </li>
    </ul>
    <div class="tab-content mt-4 d-flex flex-column flex-grow-1 rounded-right rounded-bottom" id="myTabContent" >
        <div class="tab-pane fade show active" id="list" role="tabpanel" aria-labelledby="list-tab">
            <div class="row mb-2">
                <div class="col-md">
                    <!-- Example split danger button -->
                    <div class="btn-group">
                        <a class="btn btn-primary btn-sm" href="/tasks/create?project={{$project->id}}&redirect_to=project">Add task</a>
                        <button type="button" class="btn btn-sm btn-primary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="sr-only">Toggle Dropdown</span>
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="/tasks/create?project={{$project->id}}&redirect_to=project">Add task</a>
                            <a class="dropdown-item" href="/tasks/create?type=milestone">Add milestone</a>
                            <a class="dropdown-item" href="/sections/create/{{$project->id}}">Add section</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-auto mb-3 mb-md-0">


                </div>
            </div>


            <div class="row justify-content-center">
                <div class="col-md-9">
                    <div class="card">
                        <table class="table table-hover" id="project-tasks">
                            <thead>
                            <tr>
                                <th scope="col">Task name</th>
                                <th scope="col">Assignee</th>
                                <th scope="col">Due date</th>
                                <th scope="col">Priority</th>
                                <th scope="col"></th>
                            </tr>
                            </thead>
                            <tbody id="project-tasks-body" class="ui-sortable">
                            @foreach($project->noSectionTasks as $task)
                                <tr data-id="task-{{$task->id}}" data-type="task" data-section-id="{{$task->section_id}}"  class="task-row">
                                    <td><a href="{{ route('tasks.show', ['task'=>$task])}}">{{$task->name}}</a></td>
                                    <td>{{$task->assigned->name}}</td>
                                    <td>{{$task->due_on}}</td>
                                    <td>{{$task->priority}}</td>
                                    <td></td>
                                </tr>
                            @endforeach

                            @foreach($project->sections as $section)
                                <tr data-id="section-{{$section->id}}"  data-type="section"  class="task-row">
                                    <td><strong>{{$section->name}}</strong> <a href="{{route('sections.edit',['section'=>$section->id])}}"><i class="far fa-edit ml-2"></i></a></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                @foreach($section->tasks as $task)
                                    <tr data-id="task-{{$task->id}}" data-type="task" data-section-id="{{$task->section_id}}"  class="task-row">
                                        <td><a href="{{ route('tasks.show', ['task'=>$task])}}">{{$task->name}}</a></td>
                                        <td>{{$task->assigned->name}}</td>
                                        <td>{{$task->due_on}}</td>
                                        <td>{{$task->priority}}</td>
                                        <td></td>
                                    </tr>
                                @endforeach
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="list-group list-group-flush">
                            <div class="list-group-item py-3">
                                <div class="row">
                                    <div class="col-md-2 text-secondary">
                                        Name
                                    </div>
                                    <div class="col-md">
                                        {{ $project->name }}
                                    </div>
                                </div>
                            </div>
                            <div class="list-group-item py-3">
                                <div class="row">
                                    <div class="col-md-2 text-secondary">
                                        Description
                                    </div>
                                    <div class="col-md">
                                        {{ $project->description}}
                                    </div>
                                </div>
                            </div>
                            <div class="list-group-item py-3">
                                <div class="row">
                                    <div class="col-md-3 text-secondary">
                                        Owner
                                    </div>
                                    <div class="col-md">
                                        {{ $project->user->name }}
                                    </div>
                                </div>
                            </div>
                            @if ($project->client_id)
                                <div class="list-group-item py-3">
                                    <div class="row">
                                        <div class="col-md-3 text-secondary">
                                            Client
                                        </div>
                                        <div class="col-md">
                                            {{ $project->client->name }}
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div class="list-group-item py-3">
                                <div class="row">
                                    <div class="col-md-3 text-secondary">
                                        Created
                                    </div>
                                    <div class="col-md">
                                        {{ $project->created_at->format('Y-m-d') }}
                                    </div>
                                </div>
                            </div>
                            <div class="list-group-item py-3">
                                <div class="row">
                                    <div class="col-md-3 text-secondary">
                                        Members
                                    </div>
                                    <div class="col-md">
                                        @foreach($project->members as $member)
                                            {{ $member->name }}<br>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="list-group-item py-3">
                                @include('projects.actions')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="calendar-tab-content" role="tabpanel" aria-labelledby="calendar-tab" style="height:500px;">
            <div class="card">
                <div class="card-body">
            <div id='calendar' class="h-100 w-100"></div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="files" role="tabpanel" aria-labelledby="files-tab">
            <div class="card">
                <div class="card-body">
                {!! $html->table() !!}
                {!! $html->scripts() !!}
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')

    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>

        function displayMessage(message) {
            $(".response").html(""+message+"");
            setInterval(function() { $(".success").fadeOut(); }, 1000);
        }

        $( function() {
            $( "#project-tasks-body" ).sortable({
                stop: function (event, ui) {
                    var data = [];
                    //var data = $(this).sortable('serialize');
                    //var data = $('#project-tasks-body > tr').attr("id");

                    //console.log(ui.item.attr('data-id'));

                    $('.task-row').each(function() {
                        data.push($(this).data('id'));
                    });

                    //console.log(data);

                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            data: data,
                            item: ui.item.attr('data-id')
                        },
                        type: 'POST',
                        url: '/projects/tasks/reposition'
                    });
                }
            });
            $( "#project-tasks-body" ).disableSelection();

             var calendar = $('#calendar').fullCalendar({
                events: "/calendar/{{$project->id}}",
                displayEventTime: false,
                editable: true,
                eventRender: function (event, element, view) {
                    if (event.allDay === 'true') {
                        event.allDay = true;
                    } else {
                        event.allDay = false;
                    }
                },
                beforeOpen: function (event, ui) {
                    ui.menu.zIndex($(event.target).zIndex() + 1);
                },
                selectable: true,
                selectHelper: true,
                select: function (start, end, allDay) {
                    var title = prompt('Title:');
                    if (title) {
                        var start = $.fullCalendar.formatDate(start, "Y-MM-DD HH:mm:ss");
                        var end = $.fullCalendar.formatDate(end, "Y-MM-DD HH:mm:ss");
                        var endPreviousDay = $.fullCalendar.formatDate(moment(end).subtract(1,'day'), "Y-MM-DD HH:mm:ss");

                        $.ajax({
                            url: "/tasks",
                            data: {
                                _token: "{{ csrf_token() }}",
                                name: title,
                                start_on: start,
                                due_on: endPreviousDay,
                                project_id: {{$project->id}}
                            },
                            type: "POST",
                            success: function (data) {
                                displayMessage("Added Successfully");
                            }
                        });
                        calendar.fullCalendar('renderEvent',
                            {
                                title: title,
                                start: start,
                                end: end,
                                allDay: allDay
                            },
                            true
                        );
                    }
                    calendar.fullCalendar('unselect');
                },
                eventDrop: function (event, delta) {
                    var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD HH:mm:ss");
                    var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD HH:mm:ss");

                    $.ajax({
                        url: '/tasks/'+event.id,
                        data: {
                            _token: "{{ csrf_token() }}",
                            _method: "PUT",
                            start_on: start,
                            due_on: end
                        },
                        type: "POST",
                        success: function (response) {
                            displayMessage("Updated Successfully");
                        }
                    });
                },
                eventClick: function (event) {
                    window.location.href = "/tasks/"+event.id;

                {{--var deleteMsg = confirm("Do you really want to delete?");--}}
                    {{--if (deleteMsg) {--}}
                    {{--    $.ajax({--}}
                    {{--        type: "POST",--}}
                    {{--        url: '/tasks/'+event.id,--}}
                    {{--        data: {--}}
                    {{--            _token: "{{ csrf_token() }}",--}}
                    {{--            _method: "DELETE"--}}

                    {{--        },--}}
                    {{--        success: function (response) {--}}
                    {{--            if(response == 'success') {--}}
                    {{--                $('#calendar').fullCalendar('removeEvents', event.id);--}}
                    {{--                displayMessage("Deleted Successfully");--}}
                    {{--            }--}}
                    {{--        }--}}
                    {{--    });--}}
                    {{--}--}}
                }
            });
        } );
    </script>
@endpush

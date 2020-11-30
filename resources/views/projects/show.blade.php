@extends('canvas::admin.layouts.app')

@section('title', __('Project'))
@section('content')

    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="list-tab" data-toggle="tab" href="#list" role="tab" aria-controls="list" aria-selected="true">List</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="board-tab" data-toggle="tab" href="#board-tab-content" role="tab" aria-controls="board" aria-selected="false">Board</a>
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
                        <div class="card-body">
                        @if (count($project->tasks)>0)
                        <table class="table table-hover" id="project-tasks">
                            <thead>
                            <tr>
                                <th scope="col">Task name</th>
                                <th scope="col">Assignee</th>
                                <th scope="col">Due</th>
                                <th scope="col">Priority</th>
                                <th scope="col"></th>
                            </tr>
                            </thead>
                            <tbody id="project-tasks-body" class="ui-sortable">
                            @foreach($project->noSectionTasks as $task)
                                <tr data-id="task-{{$task->id}}" data-type="task" data-section-id="{{$task->section_id}}"  class="task-row">
                                    <td><a href="{{ route('tasks.show', ['task'=>$task])}}">{{$task->name}}</a></td>
                                    <td>@include('partials.task_assigned',['users'=>$task->users])</td>
                                    <td>{{$task->due_on}}</td>
                                    <td>{{$task->priority}}</td>
                                    <td></td>
                                </tr>
                            @endforeach

                            @foreach($project->sections as $section)
                                <tr data-id="section-{{$section->id}}" data-type="section"  class="task-row">
                                    <td><strong>{{$section->name}}</strong> <a href="{{route('sections.edit',['section'=>$section->id])}}"><i class="far fa-edit ml-2"></i></a>
                                        <form action="{{ route('sections.destroy', $section->id) }}" method="POST" style="display:inline;">
                                            {{ csrf_field() }}
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('Are you sure?');"><i class="far fa-trash-alt"></i></button>
                                        </form>
                                    </td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                @foreach($section->tasks as $task)
                                    <tr data-id="task-{{$task->id}}" data-type="task" data-section-id="{{$task->section_id}}"  class="task-row">
                                        <td><a href="{{ route('tasks.show', ['task'=>$task])}}">{{$task->name}}</a></td>
                                        <td>@include('partials.task_assigned',['users'=>$task->users])</td>
                                        <td>{{$task->due_on}}</td>
                                        <td>{{$task->priority}}</td>
                                        <td></td>
                                    </tr>
                                @endforeach
                            @endforeach
                            </tbody>
                        </table>
                        @else
                            Click <a href="{{route('tasks.create')}}">here</a> to create your first task!
                        @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card  no-gutters" id="project-info">
                        <div class="card-header">
                            <div class="float-left">
                                {{ $project->name }}
                            </div>
                            <div class="float-right">
                                <div class="dropdown">
                                    <button class="btn btn-outline btn-xs" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-chevron-down"></i>
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item" href="{{ route('projects.edit', $project->id) }}">Edit</a>
                                        <a class="dropdown-item" href="#" onclick="event.preventDefault(); if (confirm('{{ __('Delete This Project?') }}')) $('#delete_project_{{ $project->id }}_form').submit();">Delete</a>
                                        <form method="post" action="{{ route('projects.destroy', $project->id) }}" id="delete_project_{{ $project->id }}_form" class="d-none">
                                            @csrf
                                            @method('delete')
                                        </form>

                                        @if ($project->archived)

                                            <a class="dropdown-item" href="#" onclick="$('#archived').val('0');$('#archive_project_{{ $project->id }}_form').submit();">Un-archive</a>
                                            <input type="hidden" name="archive" value="0">
                                        @else
                                            <a class="dropdown-item" href="#" onclick="$('#archived').val('1');$('#archive_project_{{ $project->id }}_form').submit();">Archive</a>
                                        @endif
                                        <form method="post" action="{{ route('projects.update', ['project'=>$project]) }}" id="archive_project_{{ $project->id }}_form" class="d-none">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="name" value="{{$project->name}}">
                                            <input type="hidden" name="archived" value="{{$project->archived}}" id="archived">
                                        </form>

                                        @if ($project->quicklink)
                                            <a class="dropdown-item" href="#" onclick="$('#quicklink_project_{{ $project->id }}_form').submit();">Remove Quicklink</a>

                                            <form method="post" action="{{ route('quicklinks.destroy', ['quicklink'=>$project->quicklink]) }}" id="quicklink_project_{{ $project->id }}_form" class="d-none">
                                                @csrf
                                                @method('delete')
                                            </form>
                                        @else
                                            <a class="dropdown-item" href="#" onclick="$('#quicklink_project_{{ $project->id }}_form').submit();">Add to Quicklink</a>

                                            <form method="post" action="{{ route('quicklinks.store') }}" id="quicklink_project_{{ $project->id }}_form" class="d-none">
                                                @csrf
                                                <input type="hidden" name="title" value="{{$project->name}}">
                                                <input type="hidden" name="model_id" value="{{$project->id}}">
                                                <input type="hidden" name="url" value="{{route('projects.show',['project'=>$project])}}">
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body" style="padding:0;">

                        <div class="list-group list-group-flush">
                            <div class="list-group-item">
                                {{ $project->description}}
                            </div>
                            <div class="list-group-item">
                                Created by {{ $project->user->name }} on {{ $project->created_at->format('Y-m-d') }}
                            </div>
                            @if ($project->parent_id )
                                <div class="list-group-item">
                                    In <a href="{{ route('projects.show',['project'=>$project->parent]) }}">{{ $project->parent->name }}</a>
                                </div>
                            @endif
                            <div class="list-group-item">
                                Members<br>
                                <div class="my-2">
                                @foreach($project->members as $member)
                                    <img src="/storage/{{ $member->photo }}" alt="{{ $member->name }}" class="img-circle elevation-2 " style="width:30px;">
                                @endforeach
                                </div>
                            </div>
                        </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="board-tab-content" role="tabpanel" aria-labelledby="board-tab" style="height:500px;">
            <div class="row mb-2 mx-2">
                <div class="col-md">
                    <a class="btn btn-primary btn-sm" href="#" id="addBoard">Add Board</a>
                </div>
                <div class="col-md-auto mb-3 mb-md-0">


                </div>
            </div>

            <div id='board' class="h-100 w-100"></div>
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

        var kanban = new jKanban({
            element          : '#board',                                           // selector of the kanban container
            gutter           : '15px',                                       // gutter of the board
            widthBoard       : '300px',                                      // width of the board
            responsivePercentage: true,                                    // if it is true I use percentage in the width of the boards and it is not necessary gutter and widthBoard
            dragItems        : true,                                         // if false, all items are not draggable
            boards           : @json($boards),                                           // json of boards
            dragBoards       : true,                                         // the boards are draggable, if false only item can be dragged
            addItemButton    : true,                                        // add a button to board for easy item creation
            buttonContent    : '+',                                          // text or html content of the board button
            itemHandleOptions: {
                enabled             : false,                                 // if board item handle is enabled or not
                handleClass         : "item_handle",                         // css class for your custom item handle
                customCssHandler    : "drag_handler",                        // when customHandler is undefined, jKanban will use this property to set main handler class
                customCssIconHandler: "drag_handler_icon",                   // when customHandler is undefined, jKanban will use this property to set main icon handler class. If you want, you can use font icon libraries here
                customHandler       : "<span class='item_handle'>+</span> %s"// your entirely customized handler. Use %s to position item title
            },
            click            : function (el) {
                window.location.href = "/tasks/"+el.getAttribute('data-eid');
            },                             // callback when any board's item are clicked
            dragEl           : function (el, source) {},                     // callback when any board's item are dragged
            dragendEl        : function (el) {},                             // callback when any board's item stop drag
            dropEl           : function (el, target, source, sibling) {

                var taskIds = [].map.call(target.children, function (e) {
                    return e.getAttribute('data-eid')
                })

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        section_id: target.parentElement.getAttribute('data-id'),
                        orders: taskIds
                    },
                    type: 'PUT',
                    url: '/tasks/'+el.getAttribute('data-eid')
                });
            },    // callback when any board's item drop in a board
            dragBoard        : function (el, source) {

            },                     // callback when any board stop drag
            dragendBoard     : function (el) {
                console.log(el.parentNode);

                var boardIds = [].map.call(el.parentNode.children, function (e) {
                    return e.getAttribute('data-id')
                })

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {

                        project_id: {{$project->id}},
                        orders: boardIds
                    },
                    type: 'POST',
                    url: '/sections/orders'
                });
            },                             // callback when any board stop drag
            buttonClick      : function(el, boardId) {
                console.log(boardId);
                var formItem = document.createElement("form");
                formItem.setAttribute("class", "itemform");
                formItem.innerHTML =
                    '<div class="form-group"><textarea class="form-control" rows="2" autofocus></textarea></div><div class="form-group"><button type="submit" class="btn btn-primary btn-xs pull-right">Submit</button><button type="button" id="CancelBtn" class="btn btn-default btn-xs pull-right">Cancel</button></div>';

                kanban.addForm(boardId, formItem);
                formItem.addEventListener("submit", function(e) {
                    e.preventDefault();
                    var text = e.target[0].value;
                    kanban.addElement(boardId, {
                        title: text,
                        class: "traqker-kanban-item"
                    });
                    formItem.parentNode.removeChild(formItem);



                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            project_id: {{$project->id}},
                            section_id: boardId,
                            name: text
                        },
                        type: 'POST',
                        url: '/tasks'
                    });
                });
                document.getElementById("CancelBtn").onclick = function() {
                    formItem.parentNode.removeChild(formItem);
                };
            }                      // callback when the board's button is clicked
        })

        var addBoardDefault = document.getElementById("addBoard");
        addBoardDefault.addEventListener("click", function() {

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    project_id: {{$project->id}},
                    name: "Default"
                },
                type: 'POST',
                url: '/sections',
                success: function(data) {
                    console.log(data);

                    kanban.addBoards([
                        {
                            id: data.id,
                            title: "Default",

                            item: [

                            ]
                        }
                    ]);
                }
            });
        });

        $( function() {
            $( "#project-tasks-body" ).sortable({
                stop: function (event, ui) {
                    var data = [];

                    $('.task-row').each(function() {
                        data.push($(this).data('id'));
                    });

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

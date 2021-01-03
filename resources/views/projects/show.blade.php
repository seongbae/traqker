@extends('canvas::admin.layouts.app')

@section('title', __('Project'))
@section('content')

    @include('projects.menus')


    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card">
                <div class="card-body">
                    <div class="btn-group">
                        <a class="btn btn-primary btn-sm" href="/tasks/create?project={{$project->id}}&redirect_to=project"><i class="fas fa-plus"></i> Add task</a>
                        <button type="button" class="btn btn-sm btn-primary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="sr-only">Toggle Dropdown</span>
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="/tasks/create?project={{$project->id}}&redirect_to=project">Add task</a>
                            <a class="dropdown-item" href="/tasks/create?type=milestone">Add milestone</a>
                            <a class="dropdown-item" href="{{ route('sections.create', ['project'=>$project]) }}">Add section</a>
                        </div>
                    </div>
                    @if (count($project->tasks)>0 || count($project->sections)>0)
                        <table class="table" id="project-tasks">
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
                        @if (count($project->tasks()->withoutGlobalScopes()->get()) == 0)
                            Click <a href="{{route('tasks.create')}}">here</a> to create your first task!
                        @endif
                    @endif
                </div>
                <div class="card-footer px-3">
                    @if (count($project->completedTasks)>0)
                        <a href="/tasks/completed/{{$project->slug}}" class="text-secondary m-1"><i class="far fa-check-square" title="Completed Tasks"></i></a>
                    @endif
                    @if (count($project->deletedTasks)>0)
                        <a href="/tasks/deleted/{{$project->slug}}" class="text-secondary m-1"><i class="far fa-trash-alt " title="Deleted Tasks"></i></a>
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
                        <div class="dropdown ">
                            <button class="btn btn-outline btn-xs" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-chevron-down"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="{{ route('projects.edit', $project) }}">Edit</a>
                                <a class="dropdown-item" href="#" onclick="event.preventDefault(); if (confirm('{{ __('Delete This Project?') }}')) $('#delete_project_{{ $project->id }}_form').submit();">Delete</a>
                                <form method="post" action="{{ route('projects.destroy', $project) }}" id="delete_project_{{ $project->id }}_form" class="d-none">
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
                                        <input type="hidden" name="linkable_id" value="{{$project->id}}">
                                        <input type="hidden" name="linkable_type" value="{{get_class($project)}}">
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body" style="padding:0;">

                    <div class="list-group list-group-flush">
                        <div class="list-group-item">
                            {{ Helper::limitText($project->description, 200) }}
                        </div>
                        <div class="list-group-item">
                            Created by {{ $project->user->name }} on {{ $project->created_at->format('Y-m-d') }}
                            @if ($project->team)
                                in <a href="{{route('teams.show',['team'=>$project->team])}}">{{$project->team->name}}</a>
                                @endif
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
                                    <img src="/storage/{{ $member->photo }}" alt="{{ $member->name }}" title="{{ $member->name }}" class="rounded-circle profile-small mr-1" >
                                @endforeach
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>

@endsection

@push('scripts')

    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>

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


        } );
    </script>
@endpush

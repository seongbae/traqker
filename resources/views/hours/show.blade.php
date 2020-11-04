@extends('canvas::admin.layouts.app')

@section('title', __('Hour'))
@section('content')
    <div class="container">
        <div class="row mb-2">
            <div class="col-md">
                <h1>@yield('title')</h1>
            </div>
            <div class="col-md-auto mb-3 mb-md-0">
                @include('hours.actions')
            </div>
        </div>

        <div class="card">
            <div class="list-group list-group-flush">
                <div class="list-group-item py-3">
                    <div class="row">
                        <div class="col-md-2 text-secondary">
                            Hour
                        </div>
                        <div class="col-md">
                            {{ $hour->hours }}
                        </div>
                    </div>
                </div>
                <div class="list-group-item py-3">
                    <div class="row">
                        <div class="col-md-2 text-secondary">
                            Description
                        </div>
                        <div class="col-md">
                            {{ $hour->description}}
                        </div>
                    </div>
                </div>
                @if ($hour->task_id)
                <div class="list-group-item py-3">
                    <div class="row">
                        <div class="col-md-2 text-secondary">
                            Task
                        </div>
                        <div class="col-md">
                            <a href="{{ route('tasks.show', $hour->task)}}">{{ $hour->task->name }}</a>
                        </div>
                    </div>
                </div>
                @endif
                @if ($hour->project_id)
                <div class="list-group-item py-3">
                    <div class="row">
                        <div class="col-md-2 text-secondary">
                            Project
                        </div>
                        <div class="col-md">
                            <a href="{{ route('projects.show', ['project'=>$hour->project])}}">{{ $hour->project->name }}</a>
                        </div>
                    </div>
                </div>
                @endif
                <div class="list-group-item py-3">
                    <div class="row">
                        <div class="col-md-2 text-secondary">
                            Created
                        </div>
                        <div class="col-md">
                            {{ \Carbon\Carbon::parse($hour->created_at)->format('Y-m-d') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

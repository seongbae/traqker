<?php

?>
@extends('canvas::admin.layouts.app')


@section('content')

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Projects</h3>

            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
                <button type="button" class="btn btn-tool" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                @foreach(Auth::user()->projects()->orderBy('name')->get() as $project)
                    @include('partials.project', ['project'=>$project])
                @endforeach

            </div>
        </div>
    </div>
<div class="row">
{{--    <div class="col-lg-4">--}}
{{--        <div class="card">--}}
{{--            <div class="card-header border-transparent">--}}
{{--                <h3 class="card-title">Projects</h3>--}}

{{--                <div class="card-tools">--}}
{{--                    <button type="button" class="btn btn-tool" data-card-widget="collapse">--}}
{{--                        <i class="fas fa-minus"></i>--}}
{{--                    </button>--}}
{{--                    <button type="button" class="btn btn-tool" data-card-widget="remove">--}}
{{--                        <i class="fas fa-times"></i>--}}
{{--                    </button>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            <!-- /.card-header -->--}}
{{--            <div class="card-body p-0">--}}
{{--                <div class="table-responsive">--}}
{{--                    <table class="table m-0">--}}
{{--                        <tbody>--}}
{{--                        @foreach(Auth::user()->projects()->orderBy('created_at','desc')->limit(5)->get() as $project)--}}
{{--                            <tr>--}}
{{--                                <td><a href="{{ route('projects.show', ['project'=>$project])}}">{{$project->name}}</a></td>--}}
{{--                            </tr>--}}
{{--                        @endforeach--}}
{{--                        </tbody>--}}
{{--                    </table>--}}
{{--                </div>--}}
{{--                <!-- /.table-responsive -->--}}
{{--            </div>--}}
{{--            <!-- /.card-body -->--}}
{{--            <div class="card-footer clearfix">--}}
{{--                <a href="{{ route('projects.create') }}" class="btn btn-sm btn-info float-left">Create a New</a>--}}
{{--                <a href="{{ route('projects.index') }}" class="btn btn-sm btn-secondary float-right">View All</a>--}}
{{--            </div>--}}
{{--            <!-- /.card-footer -->--}}
{{--        </div>--}}
{{--    </div>--}}
	<div class="col-lg-4">
		<div class="card">
		  <div class="card-header border-transparent">
		    <h3 class="card-title">My Tasks</h3>

		    <div class="card-tools">
		      <button type="button" class="btn btn-tool" data-card-widget="collapse">
		        <i class="fas fa-minus"></i>
		      </button>
		      <button type="button" class="btn btn-tool" data-card-widget="remove">
		        <i class="fas fa-times"></i>
		      </button>
		    </div>
		  </div>
		  <!-- /.card-header -->
		  <div class="card-body p-0">
		    <div class="table-responsive">
		      <table class="table m-0">
		        <thead>
		        <tr>
		          <th>Name</th>
		          <th>Status</th>
		          <th class="text-right">Created</th>
		        </tr>
		        </thead>
		        <tbody>
		        @foreach(Auth::user()->activeTasks()->limit(5)->get() as $task)
		        <tr>
		          <td><a href="{{ route('tasks.show', ['task'=>$task])}}">{{$task->name}}</a></td>
		          <td><span class="badge badge-{{ $task->status_badge }}">{{$task->status}}</span></td>
		          <td class="text-right">{{ \Carbon\Carbon::parse($task->created_at)->diffForHumans()}}</td>
		        </tr>
		        @endforeach
		        </tbody>
		      </table>
		    </div>
		    <!-- /.table-responsive -->
		  </div>

		</div>
	</div>
	<div class="col-lg-4">
		<div class="card">
		  <div class="card-header border-transparent">
		    <h3 class="card-title">Upcoming Due Tasks</h3>

		    <div class="card-tools">
		      <button type="button" class="btn btn-tool" data-card-widget="collapse">
		        <i class="fas fa-minus"></i>
		      </button>
		      <button type="button" class="btn btn-tool" data-card-widget="remove">
		        <i class="fas fa-times"></i>
		      </button>
		    </div>
		  </div>
		  <!-- /.card-header -->
		  <div class="card-body p-0">
		    <div class="table-responsive">
		      <table class="table m-0">
		        <thead>
		        <tr>
		          <th>Name</th>
		          <th>Status</th>
		          <th class="text-right">Due</th>
		        </tr>
		        </thead>
		        <tbody>
		        @foreach(Auth::user()->upcomingTasks as $task)
		        <tr>
		          <td><a href="{{ route('tasks.show', ['task'=>$task])}}">{{$task->name}}</a></td>
		          <td><span class="badge badge-{{ $task->status_badge }}">{{$task->status}}</span></td>
		          <td class="text-right">{{ \Carbon\Carbon::parse($task->due_on)->diffForHumans()}}</td>
		        </tr>
		        @endforeach
		        </tbody>
		      </table>
		    </div>
		    <!-- /.table-responsive -->
		  </div>
		  <!-- /.card-body -->
		  <!-- <div class="card-footer clearfix">
		    <a href="{{ route('tasks.create') }}" class="btn btn-sm btn-info float-left">Create a New Task</a>
		    <a href="{{ route('tasks.index') }}" class="btn btn-sm btn-secondary float-right">View All Tasks</a>
		  </div> -->
		  <!-- /.card-footer -->
		</div>
	</div>
	<div class="col-lg-4">
		<div class="card">
		  <div class="card-header border-transparent">
		    <h3 class="card-title">Past Due Tasks</h3>

		    <div class="card-tools">
		      <button type="button" class="btn btn-tool" data-card-widget="collapse">
		        <i class="fas fa-minus"></i>
		      </button>
		      <button type="button" class="btn btn-tool" data-card-widget="remove">
		        <i class="fas fa-times"></i>
		      </button>
		    </div>
		  </div>
		  <!-- /.card-header -->
		  <div class="card-body p-0">
		    <div class="table-responsive">
		      <table class="table m-0">
		        <thead>
		        <tr>
		          <th>Name</th>
		          <th>Status</th>
		          <th class="text-right">Due</th>
		        </tr>
		        </thead>
		        <tbody>
		        @foreach(Auth::user()->pastDueTasks as $task)
		        <tr>
		          <td><a href="{{ route('tasks.show', ['task'=>$task])}}">{{$task->name}}</a></td>
		          <td><span class="badge badge-{{ $task->status_badge }}">{{$task->status}}</span></td>
		          <td class="text-right">{{ \Carbon\Carbon::parse($task->due_on)->diffForHumans()}}</td>
		        </tr>
		        @endforeach
		        </tbody>
		      </table>
		    </div>
		    <!-- /.table-responsive -->
		  </div>
		  <!-- /.card-body -->
		  <!-- <div class="card-footer clearfix">
		    <a href="{{ route('tasks.create') }}" class="btn btn-sm btn-info float-left">Create a New Task</a>
		    <a href="{{ route('tasks.index') }}" class="btn btn-sm btn-secondary float-right">View All Tasks</a>
		  </div> -->
		  <!-- /.card-footer -->
		</div>
	</div>
</div>
@stop

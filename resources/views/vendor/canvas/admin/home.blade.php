<?php

?>
@extends('canvas::admin.layouts.app')
@section('content')
<div class="container">
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
                @foreach(Auth::user()->projects()->limit(8)->orderBy('updated_at','desc')->get() as $project)
                    @include('partials.project', ['project'=>$project])
                @endforeach

            </div>
        </div>
    </div>
    <div class="row row-short">
	<div class="col-lg-4">
        <div class="card">
            <div class="card-header border-transparent">
                <h3 class="card-title">Tasks Due Soon</h3>

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
                        <tbody>
                        @foreach(Auth::user()->upcomingTasks()->limit(10)->get() as $task)
                            <tr>
                                <td><a href="{{ route('tasks.show', ['task'=>$task])}}">{{$task->name}}</a>
                                    @if ($task->project_id)
                                        <div class="text-muted small">in <a href="{{route('projects.show',['project'=>$task->project])}}" class="text-muted">{{$task->project->name}}</a></div>
                                    @endif
                                </td>
                                <td class="text-right text-muted small">{{ \Carbon\Carbon::parse($task->due_on)->diffForHumans()}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
	</div>
	<div class="col-lg-4">
        <div class="card">
            <div class="card-header border-transparent">
                <h3 class="card-title">Recently Created Tasks</h3>

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
                        <tbody>
                        @foreach(Auth::user()->activeTasks()->limit(10)->get() as $task)
                            <tr>
                                <td><a href="{{ route('tasks.show', ['task'=>$task])}}">{{$task->name}}</a>
                                    @if ($task->project_id)
                                        <div class="text-muted small">in <a href="{{route('projects.show',['project'=>$task->project])}}" class="text-muted">{{$task->project->name}}</a></div>
                                    @endif
                                </td>
                                <td class="text-right text-muted small">{{ \Carbon\Carbon::parse($task->created_at)->diffForHumans()}}</td>
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
		        <tbody>
		        @foreach(Auth::user()->pastDueTasks()->limit(10)->get() as $task)
		        <tr>
		          <td><a href="{{ route('tasks.show', ['task'=>$task])}}">{{$task->name}}</a>
                      @if ($task->project_id)
                          <div class="text-muted small">in <a href="{{route('projects.show',['project'=>$task->project])}}" class="text-muted">{{$task->project->name}}</a></div>
                      @endif
                  </td>
		          <td class="text-right text-muted small">{{ \Carbon\Carbon::parse($task->due_on)->diffForHumans()}}</td>
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

</div>
@stop

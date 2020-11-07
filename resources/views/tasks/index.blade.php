@extends('canvas::admin.layouts.app')

@section('title', __('Tasks'))
@section('content')
<div class="row my-2">
    <div class="col-md-auto mb-3 mb-md-0">
        <a href="{{ route('tasks.create') }}" class="btn btn-primary btn-sm">{{ __('Create Task') }}</a>
    </div>
</div>
<div class="row">
    <div class="col-md-2">
           <div class="card">
            <div class="card-header">
              <h3 class="card-title">Status</h3>

              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="card-body p-0">
              <ul class="nav nav-pills flex-column">
                <li class="nav-item active">
                  <a href="/tasks?status=created" class="nav-link">
                    <i class="far fa-square mr-1"></i> Created
                    <span class="badge bg-info float-right">{{ Auth::user()->tasks()->where('status','created')->count()}}</span>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="/tasks?status=active" class="nav-link">
                    <i class="far fa-check-square mr-1"></i> Active
                    <span class="badge bg-primary float-right">{{ Auth::user()->tasks()->where('status','active')->count()}}</span>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="/tasks?status=complete" class="nav-link">
                    <i class="fas fa-check-square mr-1"></i> Complete
                    <span class="badge bg-success float-right">{{ Auth::user()->tasks()->where('status','complete')->count()}}</span>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="/tasks?archived=1" class="nav-link">
                    <i class="fas fa-archive mr-1"></i> Archived
                    <span class="badge bg-dark float-right">{{ Auth::user()->archivedTasks()->count()}}</span>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="/tasks?deleted=1" class="nav-link">
                    <i class="far fa-trash-alt mr-1"></i> Deleted
                    <span class="badge bg-secondary float-right">{{ Auth::user()->deletedTasks()->count()}}</span>
                  </a>
                </li>
              </ul>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Project</h3>

              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="card-body p-0">
              <ul class="nav nav-pills flex-column">
                @foreach(Auth::user()->projects as $project)
                <li class="nav-item">
                  <a href="/tasks?project={{$project->id}}" class="nav-link">
                    <i class="far fa-folder-open mr-1"></i>
                    {{ $project->name }}
                  </a>
                </li>
                @endforeach
              </ul>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
    <div class="col-md-10">
        <div class="card">
            <div class="card-body">
                {!! $html->table() !!}
                {!! $html->scripts() !!}
            </div>
        </div>
    </div>
</div>
@endsection

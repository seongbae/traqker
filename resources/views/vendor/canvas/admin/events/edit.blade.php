@extends('admin.layouts.app')

@section('content')
            <div class="card card-info">
              <div class="card-header">
                <h3 class="card-title">Update Event</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
                {{ Form::model( $event, ['route' => ['events.update', $event->id], 'method' => 'put', 'role' => 'form', 'enctype'=>'multipart/form-data'] ) }}
              	@csrf
                @include('admin.events.form')
                <!-- /.card-footer -->
                {{ Form::close() }}
            </div>
@endsection

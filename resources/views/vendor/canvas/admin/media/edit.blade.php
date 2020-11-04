@extends('canvas::admin.layouts.app')

@section('content')
            <div class="card card-info">
              <div class="card-header">
                <h3 class="card-title">Update Media Item</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
                {{ Form::model( $media, ['route' => ['admin.media.update', $media->id], 'method' => 'put', 'role' => 'form', 'enctype'=>'multipart/form-data'] ) }}
              	@csrf
                @include('canvas::admin.media.form')
                <!-- /.card-footer -->
                {{ Form::close() }}
            </div>
@endsection

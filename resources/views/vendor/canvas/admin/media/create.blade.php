@extends('canvas::admin.layouts.app')

@section('content')
            <div class="card card-info">
              <div class="card-header">
                <h3 class="card-title">Add Media Item</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form class="form-horizontal" method="POST" action="/admin/media" enctype="multipart/form-data">
              	@csrf
               	@include('canvas::admin.media.form')
                <!-- /.card-footer -->
              </form>
            </div>
@endsection

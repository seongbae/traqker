@extends('admin.layouts.app')

@section('content')
            <div class="card card-info">
              <div class="card-header">
                <h3 class="card-title">Add Event</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form class="form-horizontal" method="POST" action="/admin/events" enctype="multipart/form-data">
              	@csrf
               	@include('admin.events.form')
                <!-- /.card-footer -->
              </form>
            </div>
@endsection

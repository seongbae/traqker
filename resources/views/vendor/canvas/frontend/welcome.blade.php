@extends('canvas::frontend.layouts.app')
@section('content')
<div class="jumbotron">
  <div class="container">
    <div class="row">
      <div class="col-6 mx-auto col-md-6 order-md-2">
      <img src="{{ asset('canvas/img/astronaut.png')}}" class="img-fluid">
    </div>
    <div class="col-md-6 order-md-1 text-center text-md-left pr-md-5">
      <h1 class="mb-3 bd-text-purple-bright">Canvas</h1>
      <p class="lead">
        Canvas is an admin panel built with Laravel. It comes with admin dashboard for managing users, roles & permissions, media items, simple pages, and system logs.
      </p>
      <p class="lead mb-4">
        Canvas comes with admin dashboard for managing users, roles & permissions, media items, simple pages, and system logs.  You can use Canvas to build simple websites or build more advanced websites.  It also has support for building modular components.
      </p>
      <div class="row mx-n2">
        <div class="col-md px-2">
          <a href="https://github.com/seongbae/canvas" class="btn btn-lg btn-primary w-100 mb-3" onclick="ga('send', 'event', 'Jumbotron actions', 'Get started', 'Get started');">Check out Github</a>
        </div>
        <div class="col-md px-2">
          <a href="https://www.seongbae.com/blog/canvas-cms-starter-kit" class="btn btn-lg btn-outline-secondary w-100 mb-3" onclick="ga('send', 'event', 'Jumbotron actions', 'Download', 'Download 4.4.1');">Learn More</a>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
<section>
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <div id="section-title" class="mb-4">
          <h3>All Features</h3>
        </div>
        <ul class="fa-ul">
          <li><span class="fa-li"><i class="fas fa-check"></i></span>Admin dashboard</li>
          <li><span class="fa-li"><i class="fas fa-check"></i></span>User management - add, edit, remove users</li>
          <li><span class="fa-li"><i class="fas fa-check"></i></span>User registration - register, login, reset password</li>
          <li><span class="fa-li"><i class="fas fa-check"></i></span>Roles and permissions management</li>
          <li><span class="fa-li"><i class="fas fa-check"></i></span>Page management - add, edit, delete pages</li>
          <li><span class="fa-li"><i class="fas fa-check"></i></span>Media manager - manage media items such as images and videos</li>
          <li><span class="fa-li"><i class="fas fa-check"></i></span>View logs - sytem and user activity logs</li>
        </ul>
      </div>
  </div>
</section>
<section class="bg-light">
  <div class="container">
    <div class="row">
      <div class="col-lg-6 text-center">
        <img src="{{ asset('canvas/img/seongbae-400.jpg')}}">
      </div>
      <div class="col-lg-6">
        Hi there. Thanks for downloading Canvas.  If you have any issues, please feel free to create an issue on the <a href="https://github.com/seongbae/canvas">github page</a>.
      </div>
  </div>
</section>
@endsection
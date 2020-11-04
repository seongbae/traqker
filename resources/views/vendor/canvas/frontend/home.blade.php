@extends('canvas::frontend.layouts.app')
@section('content')
<section>
  <div class="container">
    Welcome, {{ Auth::user()->name }}
  </div>
</section>
@endsection
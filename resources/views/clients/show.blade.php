@extends('canvas::admin.layouts.app')

@section('title', __('Client'))
@section('content')
    <div class="container">
        <div class="row mb-2">
            <div class="col-md">
                <h1>@yield('title')</h1>
            </div>
            <div class="col-md-auto mb-3 mb-md-0">
                @include('clients.actions')
            </div>
        </div>

        <div class="card">
            <div class="list-group list-group-flush">
                <div class="list-group-item py-3">
                    <div class="row">
                        <div class="col-md-2 text-secondary">
                            Name
                        </div>
                        <div class="col-md">
                            {{ $client->name }}
                        </div>
                    </div>
                </div>
               <div class="list-group-item py-3">
                    <div class="row">
                        <div class="col-md-2 text-secondary">
                            Created
                        </div>
                        <div class="col-md">
                            {{ $client->created_at->format('Y-m-d') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

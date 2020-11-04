@extends('canvas::admin.layouts.app')

@section('title', __('Edit Client'))
@section('content')
    <div class="container">
        <div class="row my-2">
            <div class="col-md">
                <h1>@yield('title')</h1>
            </div>
            <div class="col-md-auto mb-3 mb-md-0">
                @include('clients.actions')
            </div>
        </div>

        <form method="post" action="{{ route('clients.update', $client->id) }}">
            @csrf
            @method('patch')

            <div class="card">
                @include('clients.fields')

                <div class="card-footer text-md-right border-top-0">
                    <button type="submit" name="submit" value="reload" class="btn btn-primary">{{ __('Update & Continue Edit') }}</button>
                    <button type="submit" name="submit" value="redirect" class="btn btn-primary">{{ __('Update') }}</button>
                </div>
            </div>
        </form>
    </div>
@endsection

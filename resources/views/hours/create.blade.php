@extends('canvas::admin.layouts.app')

@section('title', __('Create Hour'))
@section('content')
    <div class="container">
        <h1>@yield('title')</h1>

        <form method="post" action="{{ route('hours.store') }}">
            @csrf

            <div class="card">
                @include('hours.fields')

                <div class="card-footer text-md-right border-top-0">
                    <button type="submit" name="submit" value="reload" class="btn btn-primary">{{ __('Create & Add Another') }}</button>
                    <button type="submit" name="submit" value="redirect" class="btn btn-primary">{{ __('Create') }}</button>
                </div>
            </div>
        </form>
    </div>
@endsection
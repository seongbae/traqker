@extends('canvas::admin.layouts.app')

@section('title', __('Create Team'))
@section('content')
    <div class="container">

        <form method="post" action="{{ route('teams.store') }}">
            @csrf

            <div class="card">
                <div class="card-header">
                    Create Team
                </div>

                @include('teams.fields')

                <div class="card-footer text-md-right border-top-0">
                    <button type="submit" name="submit" value="reload" class="btn btn-primary btn-sm">{{ __('Create & Add Another') }}</button>
                    <button type="submit" name="submit" value="redirect" class="btn btn-primary btn-sm">{{ __('Create') }}</button>
                </div>
            </div>
        </form>
    </div>
@endsection

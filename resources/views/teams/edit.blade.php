@extends('canvas::admin.layouts.app')

@section('title', __('Edit Team'))
@section('content')
    <div class="container">

        <form method="post" action="{{ route('teams.update', ['team'=>$team]) }}">
            @csrf
            @method('patch')

            <div class="card">
                <div class="card-header">
                    <div class="float-left mr-2">
                        {{ $team->name }}
                    </div>
                </div>

                @include('teams.fields')

                <div class="card-footer text-md-right border-top-0">
                    <button type="submit" name="submit" value="reload" class="btn btn-primary">{{ __('Update & Continue Edit') }}</button>
                    <button type="submit" name="submit" value="redirect" class="btn btn-primary">{{ __('Update') }}</button>
                </div>
            </div>
        </form>
    </div>
@endsection

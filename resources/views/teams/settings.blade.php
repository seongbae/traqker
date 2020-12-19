@extends('canvas::admin.layouts.app')

@section('title', __('Team'))
@section('content')

    @include('teams.menus')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="post" action="{{ route('teams.update', ['team'=>$team]) }}">
                    @csrf
                    @method('patch')
                <div class="list-group list-group-flush">
                    <div class="list-group-item py-3">
                        <div class="row">
                            <div class="col-md-2 text-secondary">
                                Name
                            </div>
                            <div class="col-md">
                                <input type="text" class="form-control" name="name" value="{{$team->name}}">
                            </div>
                        </div>

                    </div>
                    <div class="list-group-item py-3">
                        <div class="row">
                            <div class="col-md-2 text-secondary">

                            </div>
                            <div class="col-md">
                                <button type="submit" name="submit" value="reload" class="btn btn-primary">{{ __('Save') }}</button>
                                <a class="btn btn-danger" href="#" onclick="event.preventDefault(); if (confirm('{{ __('Delete This Project?') }}')) $('#delete_team_{{ $team->id }}_form').submit();">Delete</a>

                            </div>
                        </div>
                    </div>
                </div>
                </form>
                <form method="post" action="{{ route('teams.destroy', ['team'=>$team]) }}" id="delete_team_{{ $team->id }}_form" class="d-none">
                    @csrf
                    @method('delete')
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')

<script>
    $(document).ready(function(){

    });
</script>
@endpush

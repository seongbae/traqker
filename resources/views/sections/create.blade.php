@extends('canvas::admin.layouts.app')

@section('title', __('Create Section'))
@section('content')
    <div class="container">
        <form method="post" action="{{ route('sections.store') }}">
            @csrf
            <div class="card">
                <div class="card-header">
                    Create Section
                </div>
                <div class="list-group-item py-3">
                    <div class="row">
                        <label for="name" class="col-form-label col-md-2">Name</label>
                        <div class="col-md">
                            <input type="text" name="name" id="name" class="form-control" value="">
                        </div>
                    </div>
                </div>

                <div class="card-footer text-md-right border-top-0">
                    <button type="submit" name="submit" value="reload" class="btn btn-primary">{{ __('Create & Add Another') }}</button>
                    <button type="submit" name="submit" value="redirect" class="btn btn-primary">{{ __('Create') }}</button>
                </div>
            </div>
            <input type="hidden" name="project_id" value="{{$project->id}}">
        </form>
    </div>
@endsection

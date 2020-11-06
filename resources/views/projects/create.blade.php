@extends('canvas::admin.layouts.app')

@section('title', __('Create Project'))
@section('content')
    <div class="container">
        <form method="post" action="{{ route('projects.store') }}">
            @csrf

            <div class="card">
                <div class="card-header">
                    Create Project
                </div>
                @include('projects.fields', ['action'=>'create'])

                <div class="card-footer text-md-right border-top-0">
                    <button type="submit" name="submit" value="reload" class="btn btn-primary">{{ __('Create & Add Another') }}</button>
                    <button type="submit" name="submit" value="redirect" class="btn btn-primary">{{ __('Create') }}</button>
                </div>
            </div>
        </form>
    </div>
@endsection

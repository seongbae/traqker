@extends('canvas::admin.layouts.app')

@section('title', __('Create Task'))
@section('content')
    <div class="container">
        <form method="post" action="{{ route('tasks.store') }}"  enctype="multipart/form-data">
            @csrf

            <div class="card">
                <div class="card-header">
                    Create Task
                </div>
                @include('tasks.fields', ['project_id'=>app('request')->input('project')])

                <div class="card-footer text-md-right border-top-0 p-2">
                    <input type="hidden" name="redirect_to" value="{{app('request')->input('redirect_to')}}">
                    <button type="submit" name="submit" value="reload" class="btn btn-primary btn-sm">{{ __('Create & Add Another') }}</button>
                    <button type="submit" name="submit" value="redirect" class="btn btn-primary btn-sm">{{ __('Create') }}</button>
                </div>
            </div>
        </form>
    </div>
@endsection

@include('tasks.scripts')

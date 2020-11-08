@extends('canvas::admin.layouts.app')

@section('title', __('Create Task'))
@section('content')
        <form method="post" action="{{ route('tasks.store') }}"  enctype="multipart/form-data">
            @csrf

            <div class="card">
                <div class="card-header">
                    Create Task
                </div>
                @include('tasks.fields', ['project_id'=>app('request')->input('project')])

                <div class="card-footer text-md-right border-top-0">
                    <input type="hidden" name="redirect_to" value="{{app('request')->input('redirect_to')}}">
                    <button type="submit" name="submit" value="reload" class="btn btn-primary">{{ __('Create & Add Another') }}</button>
                    <button type="submit" name="submit" value="redirect" class="btn btn-primary">{{ __('Create') }}</button>
                </div>
            </div>
        </form>
@endsection

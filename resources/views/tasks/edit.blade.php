@extends('canvas::admin.layouts.app')

@section('title', __('Edit Task'))
@section('content')
    <div class="container">
        <form method="post" action="{{ route('tasks.update', $task->id) }}" enctype="multipart/form-data">
            @csrf
            @method('patch')

            <div class="card">
                <div class="card-header">
                    Edit Task
                </div>
                @include('tasks.fields')

                <div class="card-footer text-md-right border-top-0">
                    <button type="submit" name="submit" value="reload" class="btn btn-primary">{{ __('Update & Continue Edit') }}</button>
                    <button type="submit" name="submit" value="redirect" class="btn btn-primary">{{ __('Update') }}</button>
                </div>
            </div>
        </form>
    </div>
@endsection


@include('tasks.scripts')

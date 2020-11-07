@extends('canvas::admin.layouts.app')

@section('title', __('Edit Project'))
@section('content')
    <div class="container">
        <form method="post" action="{{ route('projects.update', $project->id) }}">
            @csrf
            @method('patch')

            <div class="card">
                <div class="card-header">
                    Edit Project
                </div>
                @include('projects.fields', ['action'=>'edit'])

                <div class="card-footer text-md-right border-top-0">
                    <a href="{{ route('projects.destroy', $project->id) }}" class="btn {{ !request()->ajax() ? 'btn-danger' : 'btn-link text-secondary p-1' }}" title="{{ __('Delete') }}"
                       onclick="event.preventDefault(); if (confirm('{{ __('Delete This Project?') }}')) $('#delete_project_{{ $project->id }}_form').submit();">
                        <i class="far fa-trash-alt {{ !request()->ajax() ? 'fa-fw' : '' }}"></i>
                    </a>


                    <button type="submit" name="submit" value="reload" class="btn btn-primary">{{ __('Update & Continue Edit') }}</button>
                    <button type="submit" name="submit" value="redirect" class="btn btn-primary">{{ __('Update') }}</button>
                </div>
            </div>
        </form>
        <form method="post" action="{{ route('projects.destroy', $project->id) }}" id="delete_project_{{ $project->id }}_form" class="d-none">
            @csrf
            @method('delete')
        </form>
    </div>
@endsection


@push('scripts')

<script>
    $(document).ready(function(){
        $("#ManyTable").on("click", "#DeleteButton", function() {
           $(this).closest("tr").remove();
        });
    });
</script>

@endpush

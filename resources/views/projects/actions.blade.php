<div class="text-nowrap text-md-right">
    @if ($project->archived)
    <a href="#" onclick="$('#archive_project_{{ $project->id }}_form').submit();" class="btn {{ !request()->ajax() ? 'btn-primary' : 'btn-link text-secondary p-1' }}" title="{{ __('Un-archive') }}">
        <i class="fas fa-undo {{ !request()->ajax() ? 'fa-fw' : '' }}"></i>
    </a>
    <form method="post" action="{{ route('projects.update', ['project'=>$project]) }}" id="archive_project_{{ $project->id }}_form" class="d-none">
        @csrf
        @method('PUT')
        <input type="hidden" name="name" value="{{$project->name}}">
        <input type="hidden" name="archived" value="0">
    </form>
    @else
    <a href="{{ route('projects.edit', $project) }}" class="btn {{ !request()->ajax() ? 'btn-primary' : 'btn-link text-secondary p-1' }}" title="{{ __('Edit') }}">
        <i class="far fa-edit {{ !request()->ajax() ? 'fa-fw' : '' }}"></i>
    </a>
    @endif

    <a href="{{ route('projects.destroy', $project) }}" class="btn {{ !request()->ajax() ? 'btn-primary' : 'btn-link text-secondary p-1' }}" title="{{ __('Delete') }}"
       onclick="event.preventDefault(); if (confirm('{{ __('Delete This Project?') }}')) $('#delete_project_{{ $project->id }}_form').submit();">
        <i class="far fa-trash-alt {{ !request()->ajax() ? 'fa-fw' : '' }}"></i>
    </a>

    <form method="post" action="{{ route('projects.destroy', $project) }}" id="delete_project_{{ $project->id }}_form" class="d-none">
        @csrf
        @method('delete')
    </form>
</div>

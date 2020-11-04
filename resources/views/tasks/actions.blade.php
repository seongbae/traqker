<div class="text-nowrap text-md-right">
    <a href="#" data-toggle="modal" data-target="#updateStatusModal" title="Update Status"><i class="far fa-check-square fa-lg fa-fw"></i></a>

    <a href="#" data-toggle="modal" data-target="#addTimeModal" title="Add Time"><i class="far fa-clock fa-lg fa-fw"></i></a>

    @if ($task->user_id == Auth::id())
    <a href="{{ route('tasks.edit', $task->id) }}" title="{{ __('Edit') }}">
        <i class="far fa-edit {{ !request()->ajax() ? 'fa-fw' : '' }} fa-lg"></i>
    </a>
    @endif

    @if ($task->archived)
    <a href="{{ route('tasks.unarchive', $task) }}" class="" title="{{ __('Unarchive') }}"
       onclick="return confirm('{{ __('Unarchive This Task?') }}')">
        <i class="fas fa-box-open {{ !request()->ajax() ? 'fa-fw' : '' }}"></i>
    </a>
    @else
    <a href="{{ route('tasks.archive', $task) }}" class="" title="{{ __('Archive') }}"
       onclick="return confirm('{{ __('Archive This Task?') }}')">
        <i class="fas fa-archive {{ !request()->ajax() ? 'fa-fw' : '' }}"></i>
    </a>
    @endif

    @if ($task->user_id == Auth::id())
        <a href="{{ route('tasks.destroy', $task->id) }}" class="" title="{{ __('Delete') }}"
           onclick="event.preventDefault(); if (confirm('{{ __('Delete This Task?') }}')) $('#delete_task_{{ $task->id }}_form').submit();">
            <i class="far fa-trash-alt {{ !request()->ajax() ? 'fa-fw' : '' }} fa-lg"></i>
        </a>

        <form method="post" action="{{ route('tasks.destroy', $task->id) }}" id="delete_task_{{ $task->id }}_form" class="d-none">
            @csrf
            @method('delete')
        </form>
    @endif


</div>

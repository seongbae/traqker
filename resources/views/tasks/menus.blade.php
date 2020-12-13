<div class="dropdown float-right ml-2">
    <button class="btn btn-outline btn-xs" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-chevron-down"></i>
    </button>
    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
        <a href="#" class="dropdown-item" data-toggle="modal" data-target="#updateStatusModal" title="Update Status">Update Status</a>

        <a href="#" class="dropdown-item" data-toggle="modal" data-target="#addTimeModal" title="Add Time">Add Time</a>

        @if ($task->user_id == Auth::id())
        <a class="dropdown-item" href="{{route('tasks.edit',['task'=>$task])}}">Edit</a>
        <a href="{{ route('tasks.destroy', $task->id) }}" title="{{ __('Delete') }}" class="dropdown-item"
           onclick="event.preventDefault(); if (confirm('{{ __('Delete This Task?') }}')) $('#delete_task_{{ $task->id }}_form').submit();">
            Delete
        </a>
        @endif

        <a href="{{ route('tasks.archive', $task) }}" class="dropdown-item" title="{{ __('Archive') }}"
           onclick="return confirm('{{ __('Archive This Task?') }}')">
            Archive
        </a>

        <form method="post" action="{{ route('tasks.destroy', $task->id) }}" id="delete_task_{{ $task->id }}_form" class="d-none">
            @csrf
            @method('delete')
        </form>
    </div>
</div>

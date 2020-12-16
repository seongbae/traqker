@component('mail::message')

The following task has been marked as complete.

**Name**: {{ $task->name }}<br>
@if ($task->description)
**Description**: {{ $task->description }}<br>
@endif
@if ($task->project_id)
**Project**: {{ $task->project->name}}<br>
@endif
**Status**: {{ $task->status }}<br>
**Created by**: {{ $task->owner->name }}<br>
**Assigned to**: {{ $task->assignees_name }}<br>
@if ($task->start_on)
**Start on**: {{ $task->start_on }}<br>
@endif
@if ($task->due_on)
**Due on**: {{ $task->due_on }}<br>
@endif
@if ($task->completed_on)
**Completed on**: {{ $task->completed_on }}<br>
@endif

@component('mail::button', ['url' => route('tasks.show',$task)])
Go to Task
@endcomponent

Thank you.<br>
{{ config('app.name') }}
@endcomponent

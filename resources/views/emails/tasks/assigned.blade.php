@component('mail::message')

You have been assigned a new task:

**Name**: {{ $task->name }}<br>
@if ($task->description)
**Description**: {{ $task->description }}<br>
@endif
@if ($task->project_id)
**Project**: {{ $task->project->name}}<br>
@endif
**Created by**: {{ $task->owner->name }}<br>
@if ($task->start_on)
**Start on**: {{ \Carbon\Carbon::parse($task->start_on)->format('Y-m-d')}}<br>
@endif
@if ($task->due_on)
**Due on**: {{ \Carbon\Carbon::parse($task->due_on)->format('Y-m-d') }}<br>
@endif

@component('mail::button', ['url' => route('tasks.show',$task)])
Go to Task
@endcomponent

Thank you.<br>
{{ config('app.name') }}
@endcomponent

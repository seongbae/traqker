@component('mail::message')
# New Task

You have been assigned a new task:

**Name**: {{ $task->name }}  
@if ($task->description)
**Description**: {{ $task->description }}  
@endif
@if ($task->project_id)
**Project**: {{ $task->project->name}}  
@endif
**Status**: {{ $task->status }}  
**Created by**: {{ $task->owner->name }}  
**Assigned to**: {{ $task->assigned->name }}  
@if ($task->start_on)
**Start on**: {{ \Carbon\Carbon::parse($task->start_on)->format('Y-m-d')}}  
@endif
@if ($task->due_on)
**Due on**: {{ \Carbon\Carbon::parse($task->due_on)->format('Y-m-d') }}  
@endif

@component('mail::button', ['url' => route('tasks.show',$task)])
Go to Task
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent

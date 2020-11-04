@component('mail::message')
# Task Complete

The following task has been marked as complete:

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
**Start on**: {{ $task->start_on }}  
@endif
@if ($task->due_on)
**Due on**: {{ $task->due_on }}  
@endif
@if ($task->completed_on)
**Completed on**: {{ $task->completed_on }}  
@endif

@component('mail::button', ['url' => route('tasks.show',$task)])
Go to Task
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent

@component('mail::message')

New comment: "{{$comment->comment}}""

on task: **{{ $task->name }}**

@component('mail::button', ['url' => route('tasks.show',$task)])
Go to {{ config('app.name') }}
@endcomponent

Thank you.<br>
{{ config('app.name') }}
@endcomponent

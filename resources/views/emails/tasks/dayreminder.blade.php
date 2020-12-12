@component('mail::message')

You have tasks due today:

@foreach($tasks as $task)
    - <a href="{{ route('tasks.show', ['task'=>$task]) }}">{{ $task->name }}</a><br>
@endforeach

Thanks,<br>
{{ config('app.name') }}
@endcomponent

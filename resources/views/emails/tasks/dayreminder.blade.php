@component('mail::message')

You have tasks due today:

<ul>
@foreach($tasks as $task)
        <li><a href="{{ route('tasks.show', ['task'=>$task]) }}">{{ $task->name }}</a></li>
@endforeach
</ul>

Thank you.<br>
{{ config('app.name') }}
@endcomponent

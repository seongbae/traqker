@component('mail::message')

A new discussion started on a channel you are subscribed to: <strong>{{$thread->channel->name}}</strong>

Title: <strong>{{$thread->title}}</strong>

{{$thread->user->name }} wrote: "{{$thread->body}}".

@component('mail::button', ['url' => $url])
    Go to discussion
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent

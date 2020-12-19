@component('mail::message')

On discussion titled <strong>{{$reply->thread->title}}</strong>

{{$reply->user->name }} wrote: "{{$reply->body}}".

@component('mail::button', ['url' => $url])
    Go to discussion
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent

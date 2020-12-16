@component('mail::message')

You have been added to the following team: **{{$team->name}}**

@component('mail::button', ['url' => $url])
Go to {{$team->name}}'s page
@endcomponent

Thank you.<br>
{{ config('app.name') }}
@endcomponent

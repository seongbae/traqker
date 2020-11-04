@component('mail::message')

New user registered on {{ config('app.name') }}.

**Name**: {{$user->name}}  
**E-mail**: {{$user->email}}

@component('mail::button', ['url' => config("app.url")])
Go to {{ config('app.name') }}
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent

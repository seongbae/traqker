@component('mail::message')

**{{$invite->toUser->name}}** has accepted your invitation to join {{$invite->team->name}}.

@component('mail::button', ['url' => config("app.url")])
Go to {{ config('app.name') }}
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
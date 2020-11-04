@component('mail::message')

**{{$user->name}}** has accepted your invitation.

@component('mail::button', ['url' => config("app.url")])
Go to {{ config('app.name') }}
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent

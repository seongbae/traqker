@component('mail::message')

You have been invited to join on <strong>{{ $team->name }}</strong> on {{ config('app.name') }} by {{ $invitation->user->name }}.

@component('mail::button', ['url' => $invitation->getLink() ])
    Accept Invite
@endcomponent

traqker is a project management tool

Thanks,<br>
{{ config('app.name') }}
@endcomponent

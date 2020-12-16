@component('mail::message')

You have been invited to join on <strong>{{ $invitation->team->name }}</strong> on {{ config('app.name') }} by {{ $invitation->fromUser->name }}.

@component('mail::button', ['url' => $invitation->getLink() ])
    Accept Invite
@endcomponent

Thank you.<br>
{{ config('app.name') }}
@endcomponent

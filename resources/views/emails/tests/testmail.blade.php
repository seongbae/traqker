@component('mail::message')

    {{ $body }}

    Thank you.<br>
    {{ config('app.name') }}
@endcomponent

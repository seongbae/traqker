@extends('canvas::admin.layouts.app')
@section('title', __('Payouts'))
@section('content')
<div class="content">
    <div class="title">Something went wrong.</div>

    @if(app()->bound('sentry') && !empty(Sentry::getLastEventID()))
        <div class="subtitle">Error ID: {{ Sentry::getLastEventID() }}</div>

        <!-- Sentry JS SDK 2.1.+ required -->
        

        <script>
            Raven.showReportDialog({
                eventId: '{{ Sentry::getLastEventID() }}',
                // use the public DSN (dont include your secret!)
                dsn: '{{ config("sentry.dsn")}}',
                user: {
                    'name': '{{Auth::user()->name }}',
                    'email': '{{Auth::user()->email }}',
                }
            });
        </script>
    @endif
</div>
@endsection

@push('scripts')

@endpush
@component('mail::message')
# New Payment

You received a payment from {{ $payment->payer->name }}  in the amount of ${{ $payment->amount }}.

**From**: {{ $payment->payer->name }}
**Amount**: ${{ $payment->amount }}
**Method**: {{ $payment->method }}
**Transaction ID**: {{ $payment->external_transaction_id }}
**Date**: {{ $payment->created_at->format('Y-m-d') }}

@component('mail::button', ['url' => $url ])
Go to Transactions
@endcomponent

Thank you.<br>
{{ config('app.name') }}
@endcomponent

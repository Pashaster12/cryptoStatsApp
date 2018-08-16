@component('mail::message')

Dear {{ $receiver['name'] }}, your cryptocurrency wallet balances were changed!

@component('mail::table')
| Wallet address | New balance   | Delta    |
|:--------------:|:-------------:|:--------:|
@foreach($wallets as $wallet)
| {{ $wallet['address'] }} | {{ $wallet['balance'] }} | {{ $wallet['delta'] }} |
@endforeach

@endcomponent

@endcomponent

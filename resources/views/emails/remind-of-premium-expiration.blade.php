@component('mail::message')
**Hi {{ $username }}!**

{!! $message !!}. If you want to maintain your Premium subscription, click the button below:<br>

@component('mail::button', ["url" => config("app.url") . "/payment/premium/1"])
    Renew SelfAccounting Premium
@endcomponent

Thank you and keep saving,<br>
**The {{ config("app.name") }} Team**
@endcomponent

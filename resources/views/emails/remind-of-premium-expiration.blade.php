@component('mail::message')
**Hi {{ $username }}!**

You have **{{ $daysLeft }}** day{{ $daysLeft > 1 ? "s" : "" }} of SelfAccounting Premium remaining. If you want to maintain your Premium subscription, click the button below:<br>

@component('mail::button', ["url" => "https://selfaccounting.rcralph.me/payment"])
    Renew SelfAccounting Premium
@endcomponent

Thank you and keep saving,<br>
**The {{ config("app.name") }} Team**
@endcomponent

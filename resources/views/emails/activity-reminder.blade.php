@component('mail::message')
**Hi {{ $username }}!**

It's been {{ $daysSince }} days since your last activity. Is everything alright?<br>

Remember to visit SelfAccounting from time to time!

@component('mail::button', ["url" => config("app.url") . "/summary"])
    Visit SelfAccouting
@endcomponent

Thank you and keep saving,<br>
**The {{ config("app.name") }} Team**
@endcomponent

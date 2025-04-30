@component('mail::message')
# Verify Your Email Address

<div style="text-align: center; margin-bottom: 20px;">
    <img src="{{ $logoUrl }}" alt="{{ config('app.name') }} Logo" style="width:120px;">
</div>

Please click the button below to verify your email address.

@component('mail::button', ['url' => $url])
    Verify Email
@endcomponent

Thank you for using our application!

@component('mail::footer')
    If you did not create an account, no further action is required.
@endcomponent
@endcomponent

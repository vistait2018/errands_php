@component('mail::message')
    {{-- Logo at the top --}}
    ![{{ config('app.name') }} Logo]({{ $logoUrl }})

    # Hello {{ $user->name }},

    Someone logged into your account.

    **Location of request:** {{ $loginLocation }}

    If you didn't log in, please change your password immediately.

    Thanks,
    {{ config('app.name') }}

    @component('mail::button', ['url' => $loginUrl])
        Visit your account
    @endcomponent
@endcomponent

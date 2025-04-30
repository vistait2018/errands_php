@component('mail::message')
{{-- Logo at the top --}}
<div style="text-align: center; margin-bottom: 20px;">
    <img src="{{ $logoUrl }}" alt="{{ config('app.name') }} Logo" style="width:120px;">
</div>

# Hello {{ $user->email }},

New Account Registration.

**Location of request:** {{ $loginLocation }}

If you didn't login in, please change your password immediately.

Thanks,<br>
{{ config('app.name') }}

@component('mail::button', ['url' => {{ $loginUrl }}])
Visit your account
@endcomponent
@endcomponent

<x-mail::message>
# New sign-in detected

Hi {{ $user->name }},

Your Arivexa account was accessed at **{{ $loginTime }}**.

@if ($ipAddress)
**IP address:** {{ $ipAddress }}
@endif

If this was you, no action is needed. If not, change your password immediately.

<x-mail::button :url="route('login')">
Go to Arivexa
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>

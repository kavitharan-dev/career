<x-mail::message>
# Welcome, {{ $user->name }}!

Your Arivexa account has been created successfully.

**Registered email:** {{ $user->email }}  
**Registered phone:** {{ $user->phone }}

Next step: complete your student profile and skill test to get your personalized career path.

<x-mail::button :url="route('onboarding.index')">
Complete profile
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>

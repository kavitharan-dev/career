<x-mail::message>
# Welcome, {{ $user->name }}!

Your personalized career guidance is ready.

**Recommended career:** {{ $careerName }}  
**Match score:** {{ $matchScore }}%

Log in to view your roadmap, daily tasks, and analytics dashboard.

<x-mail::button :url="route('dashboard')">
Open Dashboard
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>

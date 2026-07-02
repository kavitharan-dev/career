<x-mail::message>
# Hi {{ $user->name }},

You have **{{ $pendingCount }}** pending task(s) on your learning roadmap.

Complete them today to unlock the next level and improve your progress score.

<x-mail::button :url="route('roadmap')">
View Roadmap
</x-mail::button>

Keep learning!<br>
{{ config('app.name') }}
</x-mail::message>

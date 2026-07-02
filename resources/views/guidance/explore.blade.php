@extends('layouts.app')
@section('title', 'Explore Careers')

@section('page')
<div class="mb-6">
    <a href="{{ route('guidance.index') }}" class="text-sm font-medium text-violet-600">← Career Guidance</a>
    <h1 class="mt-2 text-2xl font-bold">Explore careers</h1>
    <p class="mt-1 text-slate-600">Research salary, education, jobs in Sri Lanka, and your fit score.</p>
</div>

<div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
    @foreach ($domains as $domain)
        @php $row = $scores[$domain->id] ?? null; @endphp
        <article class="card-lift flex flex-col rounded-2xl border border-slate-200 bg-white p-5">
            <div class="flex items-start justify-between gap-2">
                <h2 class="text-lg font-semibold">{{ $domain->name }}</h2>
                @if ($row)
                    <span class="shrink-0 rounded-full bg-violet-100 px-2.5 py-0.5 text-xs font-bold text-violet-700">{{ $row['score'] }}% fit</span>
                @endif
            </div>
            <p class="mt-2 flex-1 text-sm text-slate-600 line-clamp-3">{{ $domain->description }}</p>
            @if ($domain->salary_range)
                <p class="mt-3 text-xs font-medium text-slate-500">💰 {{ $domain->salary_range }}</p>
            @endif
            @if ($domain->job_outlook)
                <p class="mt-1 text-xs text-slate-500">📈 {{ Str::limit($domain->job_outlook, 60) }}</p>
            @endif
            <a href="{{ route('guidance.show', $domain) }}" class="mt-4 text-sm font-semibold text-violet-600">Career guidance details →</a>
        </article>
    @endforeach
</div>
@endsection

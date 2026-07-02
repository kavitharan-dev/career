@extends('layouts.app')
@section('title', 'Career Assessment Report')

@section('page')
@if (session('success'))
    <div class="mb-4 rounded-xl border border-teal-200 bg-teal-50 px-4 py-3 text-sm text-teal-800">{{ session('success') }}</div>
@endif
<div class="mb-6">
    <a href="{{ route('guidance.index') }}" class="text-sm font-medium text-violet-600">← Career Guidance</a>
    <h1 class="mt-2 text-2xl font-bold">Career assessment report</h1>
    <p class="mt-1 text-slate-600">Scores use your profile (25%), career-specific fit quiz where completed (55%), and ML similarity (20%). No fixed 55% floor — percentages are calculated from real points.</p>
</div>

@if ($summary['primary'])
    <div class="rounded-2xl border-2 border-violet-400 bg-violet-600 p-6 text-white">
        <p class="text-sm font-medium text-violet-200">Best career match for you</p>
        <h2 class="mt-1 text-3xl font-bold">{{ $summary['primary']->careerDomain->name }}</h2>
        <p class="mt-2 text-5xl font-extrabold">{{ $summary['primary']->match_score }}%</p>
        <p class="mt-4 text-violet-100">{{ $summary['primary']->reasoning }}</p>
        <a href="{{ route('guidance.show', $summary['primary']->careerDomain) }}" class="mt-5 inline-block rounded-full bg-white px-5 py-2.5 text-sm font-semibold text-violet-700">View career guidance details</a>
    </div>
@endif

<div class="mt-8 grid gap-6 lg:grid-cols-2">
    <div class="rounded-2xl border border-slate-200 bg-white p-6">
        <h3 class="font-semibold">Your inputs</h3>
        <dl class="mt-4 space-y-2 text-sm">
            <div><dt class="text-slate-500">Education</dt><dd class="font-medium capitalize">{{ $summary['education_level'] ?? '—' }}</dd></div>
            <div><dt class="text-slate-500">Interests</dt><dd class="font-medium">{{ implode(', ', $summary['interests'] ?? []) ?: '—' }}</dd></div>
            <div><dt class="text-slate-500">Logical thinking test</dt><dd class="font-medium">{{ $summary['logical_score'] }}%</dd></div>
            <div><dt class="text-slate-500">Problem solving test</dt><dd class="font-medium">{{ $summary['problem_solving_score'] }}%</dd></div>
        </dl>
        @if (!empty($summary['academic_marks']))
            <h4 class="mt-4 text-sm font-medium text-slate-500">Academic marks</h4>
            <ul class="mt-2 text-sm">
                @foreach ($summary['academic_marks'] as $subject => $mark)
                    @if ($mark !== null && $mark !== '')
                        <li>{{ ucfirst(str_replace('_', ' ', $subject)) }}: {{ $mark }}%</li>
                    @endif
                @endforeach
            </ul>
        @endif
    </div>
    <div class="rounded-2xl border border-slate-200 bg-white p-6">
        <h3 class="font-semibold">Guidance recommendations</h3>
        <ul class="mt-4 space-y-2 text-sm text-slate-700">
            @foreach ($tips as $tip)
                <li class="rounded-lg bg-slate-50 px-3 py-2">{{ $tip }}</li>
            @endforeach
        </ul>
    </div>
</div>

<div class="mt-8 rounded-2xl border border-slate-200 bg-white p-6">
    <h3 class="font-semibold">All careers ranked for you</h3>
    <p class="mt-1 text-sm text-slate-500">Take each career’s separate fit quiz from its detail page to sharpen scores. Click for Sri Lanka jobs, salary, and skill gaps.</p>
    <div class="mt-4 space-y-2">
        @foreach ($allScores as $row)
            <a href="{{ route('guidance.show', $row['domain']) }}" class="flex flex-wrap items-center gap-4 rounded-xl border border-slate-100 px-4 py-3 hover:bg-violet-50">
                <span class="w-8 text-lg font-bold text-slate-400">{{ $loop->iteration }}</span>
                <span class="min-w-0 flex-1 font-medium">{{ $row['domain']->name }}</span>
                <div class="h-2 w-32 overflow-hidden rounded-full bg-slate-100">
                    <div class="h-full rounded-full bg-violet-600" style="width: {{ $row['score'] }}%"></div>
                </div>
                <span class="w-12 text-right font-bold text-violet-600">{{ $row['score'] }}%</span>
                @php $qz = $quizzesByCareer[$row['domain']->id] ?? null; @endphp
                @if ($qz)
                    <span class="text-xs text-teal-700">Quiz {{ $qz->fit_score }}%</span>
                @else
                    <span class="text-xs text-amber-600">Quiz pending</span>
                @endif
            </a>
        @endforeach
    </div>
</div>
@endsection

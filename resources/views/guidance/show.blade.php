@extends('layouts.app')
@section('title', $career->name)

@section('page')
<div class="mb-6">
    <a href="{{ route('guidance.explore') }}" class="text-sm font-medium text-violet-600">← Explore careers</a>
    <h1 class="mt-2 text-2xl font-bold">{{ $career->name }}</h1>
    <p class="mt-2 text-slate-600">{{ $career->description }}</p>
    <div class="mt-3 flex flex-wrap items-center gap-3">
        @if ($fitScore !== null)
            <p class="inline-flex rounded-full bg-violet-100 px-4 py-1.5 text-sm font-bold text-violet-800">Combined match: {{ $fitScore }}%</p>
        @endif
        @if ($careerQuiz)
            <p class="inline-flex rounded-full bg-teal-100 px-4 py-1.5 text-sm font-semibold text-teal-900">Quiz: {{ $careerQuiz->fit_score }}% — {{ $careerQuiz->verdictLabel() }}</p>
        @else
            <a href="{{ route('guidance.fit-quiz', $career) }}" class="text-sm font-semibold text-violet-600 underline">Take career-specific fit quiz →</a>
        @endif
        <a href="{{ route('guidance.interview', $career) }}" class="text-sm font-semibold text-violet-600 underline" style="margin-left: 12px;">Interview prep →</a>
        <a href="{{ route('guidance.resources') }}#{{ $career->slug }}" class="text-sm font-semibold text-violet-600 underline" style="margin-left: 12px;">Resources →</a>
    </div>
</div>

@if ($careerQuiz?->verdict_summary)
    <div class="mb-6 rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800">{{ $careerQuiz->verdict_summary }}</div>
@endif

@if ($recommendation)
    <div class="mb-6 rounded-xl border border-violet-200 bg-violet-50 px-4 py-3 text-sm text-violet-900">
        <strong>Why this matches you:</strong> {{ $recommendation->reasoning }}
    </div>
@endif

<div class="grid gap-6 lg:grid-cols-2">
    <div class="space-y-4">
        @if ($career->salary_range)
            <section class="rounded-2xl border border-slate-200 bg-white p-5">
                <h3 class="font-semibold text-slate-800">Salary (Sri Lanka)</h3>
                <p class="mt-2 text-sm text-slate-600">{{ $career->salary_range }}</p>
            </section>
        @endif
        @if ($career->job_outlook)
            <section class="rounded-2xl border border-slate-200 bg-white p-5">
                <h3 class="font-semibold">Job outlook</h3>
                <p class="mt-2 text-sm text-slate-600">{{ $career->job_outlook }}</p>
            </section>
        @endif
        @if ($career->education_path)
            <section class="rounded-2xl border border-slate-200 bg-white p-5">
                <h3 class="font-semibold">Education path</h3>
                <p class="mt-2 text-sm text-slate-600">{{ $career->education_path }}</p>
            </section>
        @endif
        @if ($career->typical_roles)
            <section class="rounded-2xl border border-slate-200 bg-white p-5">
                <h3 class="font-semibold">Typical job roles</h3>
                <ul class="mt-2 list-inside list-disc text-sm text-slate-600">
                    @foreach ($career->typical_roles as $role)
                        <li>{{ $role }}</li>
                    @endforeach
                </ul>
            </section>
        @endif
    </div>
    <div class="space-y-4">
        @if ($career->day_in_life)
            <section class="rounded-2xl border border-slate-200 bg-white p-5">
                <h3 class="font-semibold">A day in this career</h3>
                <p class="mt-2 text-sm text-slate-600">{{ $career->day_in_life }}</p>
            </section>
        @endif
        @if ($career->sri_lanka_context)
            <section class="rounded-2xl border border-teal-200 bg-teal-50 p-5">
                <h3 class="font-semibold text-teal-900">Sri Lanka context</h3>
                <p class="mt-2 text-sm text-teal-800">{{ $career->sri_lanka_context }}</p>
            </section>
        @endif
        @if ($activeJobs->isNotEmpty())
            <section class="rounded-2xl border border-emerald-200 bg-emerald-50 p-5">
                <h3 class="font-semibold text-emerald-900">Live job openings (Sri Lanka)</h3>
                <p class="mt-1 text-xs text-emerald-800">Admin-curated — hidden automatically after expiry.</p>
                <ul class="mt-3 space-y-3 text-sm text-emerald-900">
                    @foreach ($activeJobs as $job)
                        <li class="rounded-lg bg-white/80 p-3">
                            <p class="font-semibold">{{ $job->title }}</p>
                            <p class="mt-1 text-emerald-800"><span class="font-medium">Company:</span> {{ $job->company }}</p>
                            @if ($job->location)
                                <p class="text-emerald-800"><span class="font-medium">Location:</span> {{ $job->location }}</p>
                            @endif
                            <p class="text-emerald-700"><span class="font-medium">Valid until:</span> {{ $job->expires_at->format('d M Y') }}</p>
                            @if ($job->apply_url)
                                <a href="{{ $job->apply_url }}" target="_blank" rel="noopener" class="mt-2 inline-block font-medium text-emerald-900 underline">Apply now →</a>
                            @elseif ($job->apply_note)
                                <p class="text-emerald-700"><span class="font-medium">How to apply:</span> {{ $job->apply_note }}</p>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </section>
        @elseif (!empty($career->sri_lanka_jobs))
            <section class="rounded-2xl border border-emerald-200 bg-emerald-50 p-5">
                <h3 class="font-semibold text-emerald-900">Job suggestions (Sri Lanka)</h3>
                <ul class="mt-3 space-y-3 text-sm text-emerald-900">
                    @foreach ($career->sri_lanka_jobs as $job)
                        <li class="rounded-lg bg-white/80 p-3">
                            <p class="font-semibold">{{ $job['title'] }}</p>
                            <p class="mt-1 text-emerald-800"><span class="font-medium">Employers:</span> {{ $job['employers'] }}</p>
                            <p class="text-emerald-800"><span class="font-medium">Location:</span> {{ $job['location'] }}</p>
                            <p class="text-emerald-700"><span class="font-medium">How to apply:</span> {{ $job['how_to_apply'] }}</p>
                        </li>
                    @endforeach
                </ul>
            </section>
        @endif
        @if ($career->who_should_choose)
            <section class="rounded-2xl border border-slate-200 bg-white p-5">
                <h3 class="font-semibold">Who should choose this?</h3>
                <p class="mt-2 text-sm text-slate-600">{{ $career->who_should_choose }}</p>
            </section>
        @endif
        <section class="rounded-2xl border border-amber-200 bg-amber-50 p-5">
            <h3 class="font-semibold text-amber-900">Skill gap analysis</h3>
            <p class="mt-2 text-sm text-amber-800">{{ $skillGap['advice'] }}</p>
            <div style="max-width: 360px; margin: 16px auto;">
                <canvas id="skillGapChart" height="200"></canvas>
            </div>
            @if (count($skillGap['matched']))
                <p class="mt-3 text-xs font-semibold uppercase text-amber-700">Skills you have</p>
                <ul class="mt-1 flex flex-wrap gap-2">
                    @foreach ($skillGap['matched'] as $s)
                        <li class="rounded-full bg-white px-2 py-1 text-xs text-teal-700">{{ $s }}</li>
                    @endforeach
                </ul>
            @endif
            @if (count($skillGap['gaps']))
                <p class="mt-3 text-xs font-semibold uppercase text-amber-700">Skills to develop</p>
                <ul class="mt-1 flex flex-wrap gap-2">
                    @foreach ($skillGap['gaps'] as $s)
                        <li class="rounded-full bg-white px-2 py-1 text-xs text-amber-800">{{ $s }}</li>
                    @endforeach
                </ul>
            @endif
            <a href="{{ route('roadmap') }}" class="mt-4 inline-block text-sm font-semibold text-teal-700">Start learning roadmap →</a>
        </section>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
(() => {
    const matched = {{ count($skillGap['matched']) }};
    const gaps = {{ count($skillGap['gaps']) }};
    const el = document.getElementById('skillGapChart');
    if (!el || (matched === 0 && gaps === 0)) return;
    new Chart(el, {
        type: 'bar',
        data: {
            labels: ['Skills you have', 'Skills to develop'],
            datasets: [{
                label: 'Skill count',
                data: [matched, gaps],
                backgroundColor: ['#059669', '#d97706'],
            }],
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } },
        },
    });
})();
</script>
@endpush
@endsection

@extends('layouts.app')
@section('title', 'Career Guidance')

@section('page')
@include('partials.dash-header', [
    'title' => 'Career Guidance',
    'subtitle' => 'Explore careers, take fit quizzes, and see Sri Lanka job ideas.',
])

<div class="dash-tile-grid">
    <a href="{{ route('guidance.assessment') }}" class="dash-tile dash-tile-purple">
        <span class="dash-tile-icon">▣</span>
        <strong>My assessment</strong>
        <p>Scores, strengths, ranked careers</p>
    </a>
    <a href="{{ route('guidance.explore') }}" class="dash-tile dash-tile-blue">
        <span class="dash-tile-icon">◈</span>
        <strong>Explore careers</strong>
        <p>9 paths with salary and jobs</p>
    </a>
    <a href="{{ route('guidance.compare') }}" class="dash-tile dash-tile-violet">
        <span class="dash-tile-icon">⇄</span>
        <strong>Compare careers</strong>
        <p>Pick 2–3 and compare fit</p>
    </a>
    <a href="{{ route('roadmap') }}" class="dash-tile dash-tile-teal">
        <span class="dash-tile-icon">▸</span>
        <strong>Learning roadmap</strong>
        <p>Daily lessons after you choose</p>
    </a>
</div>

@if ($summary['primary'])
<section class="dash-hero-card">
    <div class="dash-hero-card-inner">
        <p class="dash-hero-kicker">Recommended for you</p>
        <h2 class="dash-hero-title">{{ $summary['primary']->careerDomain->name }}</h2>
        <p class="dash-hero-score">{{ $summary['primary']->match_score }}% fit</p>
        <p class="dash-hero-text">{{ $summary['primary']->reasoning }}</p>
        <div class="dash-hero-links">
            <a href="{{ route('guidance.show', $summary['primary']->careerDomain) }}" class="dash-action-btn dash-action-white">Career details</a>
            <a href="{{ route('guidance.assessment') }}" class="dash-action-btn dash-action-ghost-light">Full report</a>
        </div>
    </div>
</section>
@else
<div class="msg msg-warning">
    <a href="{{ route('onboarding.index') }}">Complete onboarding</a> to unlock career matching.
</div>
@endif

<div class="dash-split">
    <section class="dash-card">
        <h2 class="dash-card-title">Your strengths</h2>
        <ul class="dash-bullet-list">
            @forelse ($summary['strengths'] as $item)
                <li>{{ $item }}</li>
            @empty
                <li class="dash-list-empty">Add skills and complete tests.</li>
            @endforelse
        </ul>
        <p class="text-muted">Logical: {{ $summary['logical_score'] }}% · Problem solving: {{ $summary['problem_solving_score'] }}%</p>
    </section>
    <section class="dash-card">
        <h2 class="dash-card-title">Counselor tips</h2>
        <ul class="dash-bullet-list">
            @foreach ($tips as $tip)
                <li>{{ $tip }}</li>
            @endforeach
        </ul>
    </section>
</div>

<section class="dash-card">
    <h2 class="dash-card-title">How we track completed steps</h2>
    <ol class="dash-bullet-list">
        <li>Each lesson has subtasks — tick all three to complete the day.</li>
        <li>Database saves completion when done (you can undo).</li>
        <li>Roadmap % = finished tasks ÷ total tasks.</li>
        <li>Each career has its own 6-question fit quiz for match %.</li>
    </ol>
    <a href="{{ route('roadmap') }}" class="dash-action-btn dash-action-blue">Open roadmap</a>
</section>

@if ($summary['recommendations']->isNotEmpty())
<section class="dash-card">
    <h2 class="dash-card-title">Top matches</h2>
    <ul class="dash-list">
        @foreach ($summary['recommendations'] as $rec)
            <li>
                <a href="{{ route('guidance.show', $rec->careerDomain) }}" class="dash-list-row">
                    <span>{{ $rec->careerDomain->name }}</span>
                    <strong>{{ $rec->match_score }}%</strong>
                </a>
            </li>
        @endforeach
    </ul>
</section>
@endif
@endsection

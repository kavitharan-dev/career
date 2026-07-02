@extends('layouts.admin')
@section('admin_title', $user->name)
@section('admin_subtitle', 'Student profile, recommendations, and roadmap progress.')

@section('admin')
<p class="adm-back"><a href="{{ route('admin.users.index') }}" class="adm-link">← All students</a></p>

@error('roadmap')
    <div class="adm-flash adm-flash-error">{{ $message }}</div>
@enderror

<div class="adm-split">
    <section class="adm-card">
        <h2 class="adm-card-title">Profile</h2>
        <dl class="adm-dl">
            <div><dt>Email</dt><dd>{{ $user->email }}</dd></div>
            <div><dt>Phone</dt><dd>{{ $user->phone ?? '—' }}</dd></div>
            <div><dt>Education</dt><dd>{{ $user->studentProfile?->education_level ?? '—' }}</dd></div>
            <div><dt>Logical score</dt><dd>{{ $user->studentProfile?->logical_score ?? 0 }}%</dd></div>
            <div><dt>Problem solving</dt><dd>{{ $user->studentProfile?->problem_solving_score ?? 0 }}%</dd></div>
            <div><dt>Onboarding</dt><dd>{{ $user->hasCompletedOnboarding() ? 'Complete' : 'Pending' }}</dd></div>
        </dl>
        <p class="adm-muted" style="margin-top: 12px;"><strong>Skills:</strong> {{ $user->skills->pluck('name')->join(', ') ?: '—' }}</p>
    </section>

    <section class="adm-card">
        <h2 class="adm-card-title">Career recommendations</h2>
        <ul class="adm-list">
            @forelse ($user->careerRecommendations as $rec)
                <li class="adm-list-item">
                    <span>{{ $rec->careerDomain->name }} @if($rec->is_primary)<span class="adm-badge adm-badge-ok">primary</span>@endif</span>
                    <strong>{{ $rec->match_score }}%</strong>
                </li>
            @empty
                <li class="adm-muted">No recommendations yet.</li>
            @endforelse
        </ul>
        @if ($user->hasCompletedOnboarding())
            <form method="POST" action="{{ route('admin.users.regenerate-roadmap', $user) }}" class="adm-form-inline" onsubmit="return confirm('Rebuild this student\'s roadmap? Task progress will be reset.');">
                @csrf
                <button type="submit" class="adm-btn adm-btn-amber">Regenerate roadmap</button>
            </form>
        @endif
    </section>
</div>

@if ($user->activeRoadmap)
    <section class="adm-card">
        <div class="adm-card-head">
            <h2 class="adm-card-title">{{ $user->activeRoadmap->title }}</h2>
            <span class="adm-pill adm-pill-lg">{{ $user->activeRoadmap->completion_percentage }}%</span>
        </div>
        @foreach ($user->activeRoadmap->steps as $step)
            @php
                $done = $step->dailyTasks->where('status', 'completed')->count();
                $total = $step->dailyTasks->count();
            @endphp
            <div class="adm-roadmap-step">
                <p class="adm-roadmap-step-title">{{ $step->title }} <span class="adm-muted">({{ $done }}/{{ $total }} lessons)</span></p>
                <ul class="adm-task-list">
                    @foreach ($step->dailyTasks as $task)
                        <li class="{{ $task->status === 'completed' ? 'is-done' : '' }}">
                            {{ $task->status === 'completed' ? '✓' : '○' }} Day {{ $task->day_number }}: {{ $task->title }}
                        </li>
                    @endforeach
                </ul>
            </div>
        @endforeach
    </section>
@else
    <section class="adm-card adm-muted">No active roadmap yet.</section>
@endif
@endsection

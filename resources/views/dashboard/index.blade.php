@extends('layouts.app')
@section('title', 'Dashboard')

@section('page')
@include('partials.dash-header', [
    'title' => 'Hello, '.auth()->user()->name,
    'subtitle' => 'Your career command center — progress, matches, and next steps in one place.',
])

<div class="dash-actions">
    <a href="{{ route('guidance.index') }}" class="dash-action-btn dash-action-purple">Career guidance</a>
    <a href="{{ route('chatbot') }}" class="dash-action-btn dash-action-blue">AI tutor</a>
    <a href="{{ route('roadmap') }}" class="dash-action-btn dash-action-teal">Roadmap</a>
    <a href="{{ route('report.pdf') }}" class="dash-action-btn dash-action-ghost">Export PDF</a>
    @if ($canDownloadCertificate)
        <a href="{{ route('report.certificate') }}" class="dash-action-btn dash-action-gold">Certificate</a>
    @endif
</div>

@if ($badges->isNotEmpty())
<section class="dash-card dash-card-glow">
    <h2 class="dash-card-title">Achievement badges</h2>
    <div class="dash-badge-grid">
        @foreach ($badges as $badge)
            <div class="dash-badge-item">
                <span class="dash-badge-icon">{{ $badge->icon }}</span>
                <strong>{{ $badge->title }}</strong>
                <p>{{ $badge->description }}</p>
            </div>
        @endforeach
    </div>
</section>
@endif

<div class="dash-stat-grid">
    <div class="dash-stat-card dash-stat-indigo">
        <span class="dash-stat-icon">%</span>
        <p class="dash-stat-label">Roadmap progress</p>
        <p class="dash-stat-value">{{ $stats['completion_percentage'] }}%</p>
    </div>
    <div class="dash-stat-card dash-stat-green">
        <span class="dash-stat-icon">✓</span>
        <p class="dash-stat-label">Tasks done</p>
        <p class="dash-stat-value">{{ $stats['completed_tasks'] }}</p>
    </div>
    <div class="dash-stat-card dash-stat-amber">
        <span class="dash-stat-icon">!</span>
        <p class="dash-stat-label">Pending tasks</p>
        <p class="dash-stat-value">{{ $stats['pending_tasks'] }}</p>
    </div>
    <div class="dash-stat-card dash-stat-violet">
        <span class="dash-stat-icon">◎</span>
        <p class="dash-stat-label">Aptitude scores</p>
        <p class="dash-stat-value dash-stat-small">{{ $stats['logical_score'] }}% / {{ $stats['problem_solving_score'] }}%</p>
    </div>
</div>

@if ($stats['recommendation'])
<section class="dash-hero-card">
    <div class="dash-hero-card-inner">
        <p class="dash-hero-kicker">Top career match</p>
        <h2 class="dash-hero-title">{{ $stats['recommendation']->careerDomain->name }}</h2>
        <p class="dash-hero-score">{{ $stats['recommendation']->match_score }}% fit</p>
        <p class="dash-hero-text">{{ $stats['recommendation']->reasoning }}</p>
        <div class="dash-hero-links">
            <a href="{{ route('guidance.show', $stats['recommendation']->careerDomain) }}" class="dash-action-btn dash-action-white">Career details</a>
            <a href="{{ route('guidance.assessment') }}" class="dash-action-btn dash-action-ghost-light">Full assessment</a>
        </div>
    </div>
</section>
@endif

@if ($jobListings->isNotEmpty())
<section class="dash-card dash-card-jobs">
    <div class="dash-card-head-row">
        <h2 class="dash-card-title">Latest job openings (Sri Lanka)</h2>
        <a href="{{ route('guidance.jobs') }}" class="dash-link-more">View all →</a>
    </div>
    <p class="dash-card-sub">Admin-curated listings — updated daily. Expired jobs hide automatically.</p>
    <div class="dash-job-grid">
        @foreach ($jobListings as $job)
            <article class="dash-job-card">
                <div class="dash-job-top">
                    <h3>{{ $job->title }}</h3>
                    <span class="dash-job-expiry">Until {{ $job->expires_at->format('d M Y') }}</span>
                </div>
                <p class="dash-job-company">{{ $job->company }}</p>
                @if ($job->location)
                    <p class="dash-job-meta">{{ $job->location }}</p>
                @endif
                @if ($job->careerDomain)
                    <p class="dash-job-tag">{{ $job->careerDomain->name }}</p>
                @endif
                @if ($job->apply_url)
                    <a href="{{ $job->apply_url }}" target="_blank" rel="noopener" class="dash-job-apply">Apply now →</a>
                @elseif ($job->apply_note)
                    <p class="dash-job-note">{{ $job->apply_note }}</p>
                @endif
            </article>
        @endforeach
    </div>
</section>
@endif

<div class="dash-split">
    <section class="dash-card">
        <h2 class="dash-card-title">Weekly activity</h2>
        <div class="dash-chart-wrap"><canvas id="weeklyChart"></canvas></div>
    </section>
    <section class="dash-card">
        <h2 class="dash-card-title">Skill levels</h2>
        <div class="dash-chart-wrap"><canvas id="skillChart"></canvas></div>
    </section>
</div>

<div class="dash-split">
    <section class="dash-card">
        <h2 class="dash-card-title">Top career matches</h2>
        <ul class="dash-list">
            @forelse ($recommendations as $rec)
                <li>
                    <a href="{{ route('guidance.show', $rec->careerDomain) }}" class="dash-list-row">
                        <span>{{ $rec->careerDomain->name }}</span>
                        <strong>{{ $rec->match_score }}%</strong>
                    </a>
                </li>
            @empty
                <li class="dash-list-empty">Complete onboarding first.</li>
            @endforelse
        </ul>
        <a href="{{ route('guidance.compare') }}" class="dash-link-more">Compare careers →</a>
    </section>
    <section class="dash-card">
        <h2 class="dash-card-title">Recent alerts <a href="{{ route('notifications') }}" class="dash-link-inline">View all</a></h2>
        <ul class="dash-note-list">
            @forelse ($notifications as $note)
                <li class="dash-note {{ $note->read_at ? 'is-read' : '' }}">
                    <strong>{{ $note->title }}</strong>
                    <p>{{ $note->message }}</p>
                </li>
            @empty
                <li class="dash-list-empty">No notifications yet.</li>
            @endforelse
        </ul>
    </section>
</div>

<form method="POST" action="{{ route('skills.custom') }}" class="dash-card dash-card-form">
    @csrf
    <h2 class="dash-card-title">Add custom skill</h2>
    <div class="dash-form-row">
        <div class="form-group" style="flex: 1; margin: 0;">
            <label class="form-label" for="skill_name">Skill name</label>
            <input type="text" name="name" id="skill_name" required class="form-control" placeholder="e.g. Video editing">
        </div>
        <div class="form-group" style="width: 120px; margin: 0;">
            <label class="form-label" for="proficiency">Level</label>
            <select name="proficiency" id="proficiency" class="form-control">
                @for ($p = 1; $p <= 5; $p++)
                    <option value="{{ $p }}">{{ $p }}</option>
                @endfor
            </select>
        </div>
        <button type="submit" class="btn btn-blue">Add skill</button>
    </div>
</form>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const chartFont = "'Plus Jakarta Sans', sans-serif";
    Chart.defaults.font.family = chartFont;
    new Chart(document.getElementById('weeklyChart'), {
        type: 'line',
        data: {
            labels: @json($stats['weekly_progress']['labels']),
            datasets: [{
                label: 'Tasks done',
                data: @json($stats['weekly_progress']['values']),
                borderColor: '#6366f1',
                backgroundColor: 'rgba(99, 102, 241, 0.12)',
                fill: true,
                tension: 0.35,
            }],
        },
        options: { responsive: true, plugins: { legend: { display: false } } },
    });
    new Chart(document.getElementById('skillChart'), {
        type: 'bar',
        data: {
            labels: @json(array_keys($stats['skill_chart'])),
            datasets: [{
                data: @json(array_values($stats['skill_chart'])),
                backgroundColor: ['#059669', '#0d9488', '#6366f1', '#7c3aed', '#d97706'],
                borderRadius: 8,
            }],
        },
        options: { responsive: true, scales: { y: { min: 0, max: 5 } }, plugins: { legend: { display: false } } },
    });
});
</script>
@endpush
@endsection

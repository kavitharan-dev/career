@extends('layouts.admin')
@section('admin_title', 'Overview')
@section('admin_subtitle', 'Platform stats, recent students, and quick curriculum access.')

@section('admin')
<div class="adm-stat-grid">
    @foreach ([
        ['Students', $stats['students'], 'adm-stat-rose'],
        ['Career domains', $stats['domains'], 'adm-stat-amber'],
        ['Active jobs', $stats['active_jobs'], 'adm-stat-coral'],
        ['Tasks completed', $stats['completed_tasks'], 'adm-stat-violet'],
    ] as [$label, $value, $tone])
        <div class="adm-stat-card {{ $tone }}">
            <p class="adm-stat-label">{{ $label }}</p>
            <p class="adm-stat-value">{{ $value }}</p>
        </div>
    @endforeach
</div>

<section class="adm-card adm-card-highlight">
    <div class="adm-card-head">
        <h2 class="adm-card-title">Job listings</h2>
        <a href="{{ route('admin.jobs.index') }}" class="adm-link">Manage jobs →</a>
    </div>
    <p class="adm-muted">Post Sri Lanka job openings with expiry dates. Students see active listings on their dashboard until the date passes.</p>
</section>

<section class="adm-card adm-card-highlight">
    <div class="adm-card-head">
        <h2 class="adm-card-title">Curriculum &amp; roadmaps</h2>
        <a href="{{ route('admin.curriculum.tools') }}" class="adm-link">Open curriculum tools →</a>
    </div>
    <p class="adm-muted">View real learning paths, reseed templates, or rebuild student roadmaps after curriculum updates.</p>
</section>

<section class="adm-card">
    <h2 class="adm-card-title">Recent students</h2>
    <div class="adm-table-wrap">
        <table class="adm-table">
            <thead>
                <tr><th>Name</th><th>Email</th><th>Career</th><th>Progress</th></tr>
            </thead>
            <tbody>
                @foreach ($recentStudents as $student)
                    <tr>
                        <td><a href="{{ route('admin.users.show', $student) }}" class="adm-link">{{ $student->name }}</a></td>
                        <td>{{ $student->email }}</td>
                        <td>{{ $student->primaryRecommendation?->careerDomain?->name ?? '—' }}</td>
                        <td><span class="adm-pill">{{ $student->activeRoadmap?->completion_percentage ?? 0 }}%</span></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>
@endsection

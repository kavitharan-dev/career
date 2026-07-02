@extends('layouts.admin')
@section('admin_title', 'Curriculum tools')
@section('admin_subtitle', 'Reseed templates and regenerate student roadmaps after curriculum changes.')

@section('admin')
<div class="adm-stat-grid">
    @foreach ([
        ['Career paths', $stats['domains'], 'adm-stat-rose'],
        ['Roadmap steps', $stats['steps'], 'adm-stat-amber'],
        ['Lesson tasks', $stats['tasks'], 'adm-stat-coral'],
        ['Subtasks', $stats['subtasks'], 'adm-stat-violet'],
    ] as [$label, $value, $tone])
        <div class="adm-stat-card {{ $tone }}">
            <p class="adm-stat-label">{{ $label }}</p>
            <p class="adm-stat-value">{{ $value }}</p>
        </div>
    @endforeach
</div>

<p class="adm-note">
    <strong>{{ $stats['students_with_roadmaps'] }}</strong> student(s) currently have an active roadmap.
    Use the tools below after updating curriculum in <code>RoadmapCurriculumData.php</code>.
</p>

<div class="adm-tool-grid">
    <section class="adm-tool-card adm-tool-card-rose">
        <h2 class="adm-tool-title">1. Update curriculum templates</h2>
        <p class="adm-tool-text">
            Loads the latest learning paths into the database. Does <strong>not</strong> change student progress until you regenerate roadmaps.
        </p>
        <form method="POST" action="{{ route('admin.curriculum.reseed') }}" onsubmit="return confirm('Update all career curriculum templates from the seeder?');">
            @csrf
            <input type="hidden" name="confirm" value="yes">
            <button type="submit" class="adm-btn adm-btn-rose">Reseed curriculum</button>
        </form>
    </section>

    <section class="adm-tool-card adm-tool-card-amber">
        <h2 class="adm-tool-title">2. Regenerate all student roadmaps</h2>
        <p class="adm-tool-text">
            Rebuilds every student’s roadmap from their career match. <strong>Resets task progress</strong> for those students.
        </p>
        <form method="POST" action="{{ route('admin.roadmaps.regenerate-all') }}" onsubmit="return confirm('Regenerate roadmaps for ALL students? This resets their task progress.');">
            @csrf
            <input type="hidden" name="confirm" value="yes">
            <button type="submit" class="adm-btn adm-btn-amber">Regenerate all roadmaps</button>
        </form>
    </section>
</div>

<section class="adm-card">
    <h2 class="adm-card-title">Preview curricula</h2>
    <p class="adm-muted">Browse the full lesson plan for each career before students see it.</p>
    <a href="{{ route('admin.domains.index') }}" class="adm-link adm-link-block">View all career domains →</a>
</section>
@endsection

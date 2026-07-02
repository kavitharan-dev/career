@extends('layouts.admin')
@section('admin_title', 'Career domains')
@section('admin_subtitle', 'Browse curricula, lesson counts, and student matches per career path.')

@section('admin')
<div class="adm-domain-grid">
    @foreach ($domains as $domain)
        <article class="adm-domain-card">
            <h3 class="adm-domain-name">{{ $domain->name }}</h3>
            <p class="adm-muted adm-domain-desc">{{ $domain->description }}</p>
            <p class="adm-domain-meta">
                {{ $domain->step_templates_count }} steps ·
                {{ $domain->tasks_count }} lessons ·
                {{ $domain->subtasks_count }} subtasks ·
                {{ $domain->recommendations_count }} students
            </p>
            <div class="adm-domain-actions">
                <a href="{{ route('admin.domains.show', $domain) }}" class="adm-btn adm-btn-primary">View curriculum</a>
                <a href="{{ route('admin.domains.edit', $domain) }}" class="adm-btn adm-btn-ghost">Edit</a>
            </div>
        </article>
    @endforeach
</div>
@endsection

@extends('layouts.admin')
@section('admin_title', $domain->name.' curriculum')
@section('admin_subtitle', $domain->description)

@section('admin')
<div class="adm-card-head adm-back-row">
    <div>
        <a href="{{ route('admin.domains.index') }}" class="adm-link">← All domains</a>
        <p class="adm-domain-meta" style="margin-top: 8px;">
            {{ $stats['steps'] }} steps · {{ $stats['tasks'] }} lessons · {{ $stats['subtasks'] }} subtasks · {{ $domain->recommendations_count }} student matches
        </p>
    </div>
    <a href="{{ route('admin.domains.edit', $domain) }}" class="adm-btn adm-btn-ghost">Edit name &amp; description</a>
</div>

<div class="adm-curriculum-stack">
    @foreach ($domain->stepTemplates as $step)
        <section class="adm-card">
            <div class="adm-step-head">
                <span class="adm-badge">{{ $step->level }}</span>
                <h2 class="adm-card-title" style="margin: 0;">{{ $step->title }}</h2>
            </div>
            @if ($step->description)
                <p class="adm-muted">{{ $step->description }}</p>
            @endif

            <ol class="adm-lesson-list">
                @foreach ($step->taskTemplates as $task)
                    <li class="adm-lesson-item">
                        <p class="adm-lesson-title">Day {{ $task->day_number }}: {{ $task->title }}</p>
                        @if ($task->description)
                            <p class="adm-muted">{{ $task->description }}</p>
                        @endif
                        @if ($task->subtaskTemplates->isNotEmpty())
                            <ul class="adm-subtask-list">
                                @foreach ($task->subtaskTemplates as $sub)
                                    <li>{{ $sub->title }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endforeach
            </ol>
        </section>
    @endforeach
</div>
@endsection

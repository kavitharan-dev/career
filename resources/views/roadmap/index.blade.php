@extends('layouts.app')
@section('title', 'Roadmap')

@section('page')
@include('partials.dash-header', [
    'title' => 'Your learning roadmap',
    'subtitle' => 'Beginner to job-ready — complete each lesson’s subtasks in order (read → practice → review).',
])

@if (! $roadmap)
    <section class="dash-card dash-card-empty">
        <p>Complete onboarding to generate your personalized roadmap.</p>
        <a href="{{ route('onboarding.index') }}" class="dash-action-btn dash-action-blue">Continue setup</a>
    </section>
@else
    <section class="dash-roadmap-head">
        <div>
            <h2 class="dash-roadmap-title">{{ $roadmap->title }}</h2>
            <p class="dash-roadmap-career">{{ $roadmap->careerDomain->name }}</p>
        </div>
        <div class="dash-roadmap-pct">
            <strong>{{ $roadmap->completion_percentage }}%</strong>
            <span>complete</span>
        </div>
        <div class="dash-progress-bar">
            <div class="dash-progress-fill" style="width: {{ $roadmap->completion_percentage }}%"></div>
        </div>
    </section>

    <div class="dash-roadmap-steps">
        @foreach ($roadmap->steps as $step)
            <article class="dash-step-card {{ ! $step->is_unlocked ? 'is-locked' : '' }}">
                <header class="dash-step-head">
                    <span class="dash-step-level">{{ $step->level }}</span>
                    <h3>{{ $step->title }}</h3>
                    @unless ($step->is_unlocked)
                        <span class="dash-step-lock">Locked — finish previous step</span>
                    @endunless
                </header>
                @if ($step->description)
                    <p class="dash-step-desc">{{ $step->description }}</p>
                @endif
                <ul class="dash-task-list">
                    @foreach ($step->dailyTasks as $task)
                        @php $progress = $task->subtaskProgress(); @endphp
                        <li class="dash-task {{ $task->isCompleted() ? 'is-done' : '' }}">
                            <div class="dash-task-top">
                                <div>
                                    <p class="dash-task-title">
                                        Day {{ $task->day_number }}: {{ $task->title }}
                                        @if ($task->isCompleted())
                                            <span class="dash-task-done">Done</span>
                                        @elseif ($progress['total'] > 0)
                                            <span class="dash-task-progress">({{ $progress['done'] }}/{{ $progress['total'] }} subtasks)</span>
                                        @endif
                                    </p>
                                    @if ($task->description)
                                        <p class="dash-task-desc">{{ $task->description }}</p>
                                    @endif
                                </div>
                                @if ($step->is_unlocked && $task->isCompleted())
                                    <form method="POST" action="{{ route('tasks.uncomplete', $task) }}">
                                        @csrf
                                        <button type="submit" class="dash-btn-ghost">Undo</button>
                                    </form>
                                @endif
                            </div>
                            @if ($task->subtasks->isNotEmpty())
                                <ul class="dash-subtask-list">
                                    @foreach ($task->subtasks as $subtask)
                                        <li class="dash-subtask {{ $subtask->is_completed ? 'is-done' : '' }}">
                                            @if ($step->is_unlocked && ! $task->isCompleted())
                                                <form method="POST" action="{{ route('tasks.subtasks.toggle', [$task, $subtask]) }}" class="dash-subtask-form">
                                                    @csrf
                                                    <input type="checkbox" onchange="this.form.submit()" @checked($subtask->is_completed)>
                                                    <span>{{ $subtask->title }}</span>
                                                </form>
                                            @else
                                                <span class="dash-subtask-check">{{ $subtask->is_completed ? '☑' : '☐' }}</span>
                                                <span>{{ $subtask->title }}</span>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                                @if ($step->is_unlocked && ! $task->isCompleted())
                                    <p class="dash-subtask-hint">Complete all subtasks to finish this lesson.</p>
                                @endif
                            @endif
                        </li>
                    @endforeach
                </ul>
            </article>
        @endforeach
    </div>
@endif
@endsection

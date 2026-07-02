<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Arivexa Student Report</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #1e293b; }
        h1 { color: #4f46e5; font-size: 22px; margin-bottom: 4px; }
        h2 { font-size: 14px; margin-top: 20px; border-bottom: 1px solid #e2e8f0; padding-bottom: 4px; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th, td { border: 1px solid #e2e8f0; padding: 6px 8px; text-align: left; }
        th { background: #f8fafc; }
        .meta { color: #64748b; font-size: 11px; }
    </style>
</head>
<body>
    <h1>Arivexa Career & Learning Report</h1>
    <p class="meta">Generated {{ $generatedAt->format('d M Y, H:i') }}</p>

    <h2>Student</h2>
    <p><strong>{{ $user->name }}</strong> — {{ $user->email }}</p>
    <p>Education: {{ $user->studentProfile?->education_level ?? 'N/A' }}</p>
    <p>Logical thinking: {{ $stats['logical_score'] }}% · Problem solving: {{ $stats['problem_solving_score'] }}%</p>

    <h2>Career recommendation</h2>
    @if ($user->primaryRecommendation)
        <p><strong>{{ $user->primaryRecommendation->careerDomain->name }}</strong> — {{ $user->primaryRecommendation->match_score }}% match</p>
        <p>{{ $user->primaryRecommendation->reasoning }}</p>
    @else
        <p>No recommendation on file.</p>
    @endif

    <h2>All matches</h2>
    <table>
        <tr><th>Career domain</th><th>Match %</th></tr>
        @foreach ($user->careerRecommendations as $rec)
            <tr><td>{{ $rec->careerDomain->name }}</td><td>{{ $rec->match_score }}%</td></tr>
        @endforeach
    </table>

    <h2>Learning progress</h2>
    <p>Roadmap completion: <strong>{{ $stats['completion_percentage'] }}%</strong></p>
    <p>Tasks completed: {{ $stats['completed_tasks'] }} · Pending: {{ $stats['pending_tasks'] }}</p>

    @if ($user->activeRoadmap)
        <h2>Roadmap: {{ $user->activeRoadmap->title }}</h2>
        @foreach ($user->activeRoadmap->steps as $step)
            <p><strong>{{ $step->title }}</strong> ({{ $step->level }})</p>
            <ul>
                @foreach ($step->dailyTasks as $task)
                    <li>{{ $task->title }} — {{ $task->isCompleted() ? 'Completed' : 'Pending' }}</li>
                @endforeach
            </ul>
        @endforeach
    @endif

    <h2>Skills</h2>
    <table>
        <tr><th>Skill</th><th>Proficiency (1-5)</th></tr>
        @foreach ($user->skills as $skill)
            <tr><td>{{ $skill->name }}</td><td>{{ $skill->pivot->proficiency }}</td></tr>
        @endforeach
    </table>
</body>
</html>

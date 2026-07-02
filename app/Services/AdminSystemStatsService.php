<?php

namespace App\Services;

use App\Models\CareerDomain;
use App\Models\CareerRecommendation;
use App\Models\DailyTask;
use App\Models\DailyTaskSubtaskTemplate;
use App\Models\DailyTaskTemplate;
use App\Models\RoadmapStepTemplate;
use App\Models\User;

class AdminSystemStatsService
{
  /**
   * @return array<string, int|string>
   */
  public function snapshot(): array
  {
    $studentQuery = User::query()->where('is_admin', false);

    $onboardingDone = (clone $studentQuery)->whereHas('studentProfile', function ($q) {
      $q->where('onboarding_completed', true);
    })->count();

    $totalStudents = (clone $studentQuery)->count();
    $pendingOnboarding = max(0, $totalStudents - $onboardingDone);

    $topCareers = CareerDomain::query()
      ->withCount('recommendations')
      ->orderByDesc('recommendations_count')
      ->limit(3)
      ->get()
      ->map(fn ($d) => "{$d->name} ({$d->recommendations_count})")
      ->implode(', ');

    $recentStudents = User::query()
      ->where('is_admin', false)
      ->latest()
      ->limit(5)
      ->pluck('name')
      ->implode(', ');

    return [
      'total_students' => $totalStudents,
      'onboarding_complete' => $onboardingDone,
      'onboarding_pending' => $pendingOnboarding,
      'students_with_roadmaps' => (clone $studentQuery)->whereHas('roadmaps')->count(),
      'career_domains' => CareerDomain::count(),
      'recommendations' => CareerRecommendation::count(),
      'completed_tasks' => DailyTask::where('status', DailyTask::STATUS_COMPLETED)->count(),
      'pending_tasks' => DailyTask::where('status', '!=', DailyTask::STATUS_COMPLETED)->count(),
      'roadmap_steps' => RoadmapStepTemplate::count(),
      'lesson_tasks' => DailyTaskTemplate::count(),
      'subtasks' => DailyTaskSubtaskTemplate::count(),
      'top_careers' => $topCareers ?: 'none yet',
      'recent_students' => $recentStudents ?: 'none yet',
      'generated_at' => now()->format('Y-m-d H:i'),
    ];
  }

  public function contextBlock(): string
  {
    $s = $this->snapshot();

    return <<<CTX
Live Arivexa platform data (use these exact numbers when answering admin questions):
- Total registered students (non-admin users): {$s['total_students']}
- Onboarding completed: {$s['onboarding_complete']}
- Onboarding still pending: {$s['onboarding_pending']}
- Students with an active roadmap: {$s['students_with_roadmaps']}
- Career domains in system: {$s['career_domains']}
- Total career recommendations generated: {$s['recommendations']}
- Student tasks completed: {$s['completed_tasks']}
- Student tasks still pending: {$s['pending_tasks']}
- Curriculum: {$s['roadmap_steps']} roadmap steps, {$s['lesson_tasks']} lessons, {$s['subtasks']} subtasks
- Top matched careers: {$s['top_careers']}
- Recently registered students: {$s['recent_students']}
- Data snapshot time: {$s['generated_at']}
CTX;
  }
}

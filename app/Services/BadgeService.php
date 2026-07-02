<?php

namespace App\Services;

use App\Models\DailyTask;
use App\Models\User;
use App\Models\UserBadge;
use App\Models\UserCareerFitQuiz;

class BadgeService
{
    /** @return array<string, array{title: string, icon: string, description: string}> */
    public function definitions(): array
    {
        return [
            'onboarding_complete' => [
                'title' => 'Profile Pioneer',
                'icon' => '🎯',
                'description' => 'Completed onboarding and career assessment.',
            ],
            'first_task' => [
                'title' => 'First Step',
                'icon' => '✅',
                'description' => 'Completed your first learning task.',
            ],
            'quiz_master' => [
                'title' => 'Quiz Master',
                'icon' => '🧠',
                'description' => 'Finished a career-specific fit quiz.',
            ],
            'roadmap_half' => [
                'title' => 'Halfway Hero',
                'icon' => '📈',
                'description' => 'Reached 50% roadmap completion.',
            ],
            'roadmap_complete' => [
                'title' => 'Roadmap Champion',
                'icon' => '🏆',
                'description' => 'Completed 100% of your learning roadmap.',
            ],
            'task_milestone_5' => [
                'title' => 'Task Achiever',
                'icon' => '⭐',
                'description' => 'Completed 5 learning tasks.',
            ],
        ];
    }

    public function syncFor(User $user): void
    {
        $definitions = $this->definitions();

        if ($user->hasCompletedOnboarding()) {
            $this->award($user, 'onboarding_complete', $definitions['onboarding_complete']);
        }

        $completedTasks = DailyTask::query()
            ->where('status', DailyTask::STATUS_COMPLETED)
            ->whereHas('roadmapStep.roadmap', fn ($q) => $q->where('user_id', $user->id))
            ->count();

        if ($completedTasks >= 1) {
            $this->award($user, 'first_task', $definitions['first_task']);
        }

        if ($completedTasks >= 5) {
            $this->award($user, 'task_milestone_5', $definitions['task_milestone_5']);
        }

        if (UserCareerFitQuiz::where('user_id', $user->id)->exists()) {
            $this->award($user, 'quiz_master', $definitions['quiz_master']);
        }

        $roadmap = $user->activeRoadmap;

        if ($roadmap) {
            if ($roadmap->completion_percentage >= 50) {
                $this->award($user, 'roadmap_half', $definitions['roadmap_half']);
            }

            if ($roadmap->completion_percentage >= 100) {
                $this->award($user, 'roadmap_complete', $definitions['roadmap_complete']);
            }
        }
    }

    protected function award(User $user, string $slug, array $meta): void
    {
        UserBadge::firstOrCreate(
            ['user_id' => $user->id, 'badge_slug' => $slug],
            [
                'title' => $meta['title'],
                'icon' => $meta['icon'],
                'description' => $meta['description'],
                'earned_at' => now(),
            ]
        );
    }
}

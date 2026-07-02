<?php

namespace App\Services;

use App\Models\DailyTask;
use App\Models\DailyTaskSubtask;
use App\Models\Roadmap;
use App\Models\RoadmapStep;
use App\Models\User;

class ProgressTrackingService
{
    /** @return array<int, string> */
    public function defaultSubtaskTitles(string $taskTitle = ''): array
    {
        $topic = $taskTitle !== '' ? "for \"{$taskTitle}\"" : 'for this lesson';

        return [
            "Study the official lesson material {$topic}",
            'Complete every hands-on exercise in the task description',
            'Self-check: summarize what you learned before moving on',
        ];
    }

    public function ensureTaskSubtasks(DailyTask $task): void
    {
        if ($task->subtasks()->exists()) {
            return;
        }

        foreach ($this->defaultSubtaskTitles($task->title ?? '') as $index => $title) {
            $task->subtasks()->create([
                'title' => $title,
                'sort_order' => $index + 1,
            ]);
        }
    }

    public function completeTask(DailyTask $task, User $user): void
    {
        $this->authorizeTask($task, $user);
        $this->ensureTaskSubtasks($task);

        if (! $task->allSubtasksCompleted()) {
            abort(422, 'Complete all subtasks before marking this task done.');
        }

        $task->update([
            'status' => DailyTask::STATUS_COMPLETED,
            'completed_at' => now(),
        ]);

        $this->afterTaskChange($task);
    }

    public function uncompleteTask(DailyTask $task, User $user): void
    {
        $this->authorizeTask($task, $user);

        $task->update([
            'status' => DailyTask::STATUS_PENDING,
            'completed_at' => null,
        ]);

        $task->subtasks()->update([
            'is_completed' => false,
            'completed_at' => null,
        ]);

        $this->afterTaskChange($task);
    }

    public function toggleSubtask(DailyTaskSubtask $subtask, User $user): void
    {
        $task = $subtask->dailyTask;
        $this->authorizeTask($task, $user);

        if ($subtask->is_completed) {
            $subtask->update([
                'is_completed' => false,
                'completed_at' => null,
            ]);

            if ($task->isCompleted()) {
                $task->update([
                    'status' => DailyTask::STATUS_PENDING,
                    'completed_at' => null,
                ]);
            }
        } else {
            $subtask->update([
                'is_completed' => true,
                'completed_at' => now(),
            ]);

            $task->refresh();

            if ($task->allSubtasksCompleted()) {
                $task->update([
                    'status' => DailyTask::STATUS_COMPLETED,
                    'completed_at' => now(),
                ]);
            }
        }

        $this->afterTaskChange($task);
    }

    protected function authorizeTask(DailyTask $task, User $user): void
    {
        if ($task->roadmapStep->roadmap->user_id !== $user->id) {
            abort(403);
        }
    }

    protected function afterTaskChange(DailyTask $task): void
    {
        $roadmap = $task->roadmapStep->roadmap;
        $this->unlockNextSteps($roadmap);
        $this->syncRoadmapCompletion($roadmap->fresh());
    }

    public function syncRoadmapCompletion(Roadmap $roadmap): void
    {
        $tasks = DailyTask::query()
            ->whereHas('roadmapStep', fn ($q) => $q->where('roadmap_id', $roadmap->id))
            ->get();

        $total = $tasks->count();
        $completed = $tasks->where('status', DailyTask::STATUS_COMPLETED)->count();

        $roadmap->update([
            'completion_percentage' => $total > 0 ? (int) round(($completed / $total) * 100) : 0,
        ]);
    }

    protected function unlockNextSteps(Roadmap $roadmap): void
    {
        $steps = $roadmap->steps()->orderBy('sort_order')->get();

        foreach ($steps as $index => $step) {
            $allDone = $step->dailyTasks()->where('status', '!=', DailyTask::STATUS_COMPLETED)->doesntExist();

            if ($allDone && isset($steps[$index + 1])) {
                $steps[$index + 1]->update(['is_unlocked' => true]);
            }
        }
    }

    public function dashboardStats(User $user): array
    {
        $roadmap = $user->activeRoadmap?->load('steps.dailyTasks');
        $profile = $user->studentProfile;
        $recommendation = $user->primaryRecommendation?->load('careerDomain');

        $pendingTasks = 0;
        $completedTasks = 0;

        if ($roadmap) {
            foreach ($roadmap->steps as $step) {
                foreach ($step->dailyTasks as $task) {
                    if ($task->isCompleted()) {
                        $completedTasks++;
                    } else {
                        $pendingTasks++;
                    }
                }
            }
        }

        return [
            'roadmap' => $roadmap,
            'recommendation' => $recommendation,
            'completion_percentage' => $roadmap?->completion_percentage ?? 0,
            'pending_tasks' => $pendingTasks,
            'completed_tasks' => $completedTasks,
            'logical_score' => $profile?->logical_score ?? 0,
            'problem_solving_score' => $profile?->problem_solving_score ?? 0,
            'skill_chart' => $user->skills->mapWithKeys(fn ($skill) => [$skill->name => $skill->pivot->proficiency])->all(),
            'weekly_progress' => $this->weeklyProgress($user),
        ];
    }

    protected function weeklyProgress(User $user): array
    {
        $countsByDate = DailyTask::query()
            ->where('status', DailyTask::STATUS_COMPLETED)
            ->where('completed_at', '>=', now()->subDays(6)->startOfDay())
            ->whereHas('roadmapStep.roadmap', fn ($q) => $q->where('user_id', $user->id))
            ->selectRaw('DATE(completed_at) as completed_day, COUNT(*) as total')
            ->groupBy('completed_day')
            ->pluck('total', 'completed_day');

        $labels = [];
        $values = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $labels[] = $date->format('D');
            $values[] = (int) ($countsByDate[$date->toDateString()] ?? 0);
        }

        return ['labels' => $labels, 'values' => $values];
    }
}

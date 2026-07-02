<?php

namespace App\Services;

use App\Models\CareerDomain;
use App\Models\CareerRecommendation;
use App\Models\DailyTask;
use App\Models\Roadmap;
use App\Models\RoadmapStep;
use App\Models\User;

class RoadmapGeneratorService
{
    public function __construct(
        protected ProgressTrackingService $progress
    ) {}

    public function generateFor(User $user, CareerRecommendation $recommendation): Roadmap
    {
        $domain = $recommendation->careerDomain;

        $user->roadmaps()->delete();

        $roadmap = Roadmap::create([
            'user_id' => $user->id,
            'career_domain_id' => $domain->id,
            'title' => "{$domain->name} Learning Roadmap",
            'completion_percentage' => 0,
        ]);

        $templates = $domain->stepTemplates()->with('taskTemplates.subtaskTemplates')->get();

        foreach ($templates as $index => $template) {
            $step = RoadmapStep::create([
                'roadmap_id' => $roadmap->id,
                'title' => $template->title,
                'level' => $template->level,
                'sort_order' => $template->sort_order,
                'description' => $template->description,
                'is_unlocked' => $index === 0,
            ]);

            foreach ($template->taskTemplates as $taskTemplate) {
                $task = DailyTask::create([
                    'roadmap_step_id' => $step->id,
                    'title' => $taskTemplate->title,
                    'description' => $taskTemplate->description,
                    'day_number' => $taskTemplate->day_number,
                    'status' => DailyTask::STATUS_PENDING,
                ]);

                $subtaskTemplates = $taskTemplate->subtaskTemplates;

                if ($subtaskTemplates->isNotEmpty()) {
                    foreach ($subtaskTemplates as $subTemplate) {
                        $task->subtasks()->create([
                            'title' => $subTemplate->title,
                            'sort_order' => $subTemplate->sort_order,
                        ]);
                    }
                } else {
                    $this->progress->ensureTaskSubtasks($task);
                }
            }
        }

        $this->progress->syncRoadmapCompletion($roadmap->fresh());

        return $roadmap->load('steps.dailyTasks');
    }
}

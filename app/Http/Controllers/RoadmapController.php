<?php

namespace App\Http\Controllers;

use App\Services\ProgressTrackingService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RoadmapController extends Controller
{
    public function __construct(
        protected ProgressTrackingService $progress,
    ) {}

    public function index(Request $request): View
    {
        $roadmap = $request->user()->activeRoadmap?->load([
            'careerDomain',
            'steps.dailyTasks.subtasks',
        ]);

        if ($roadmap) {
            foreach ($roadmap->steps as $step) {
                foreach ($step->dailyTasks as $task) {
                    $this->progress->ensureTaskSubtasks($task);
                }
            }

            $roadmap->load('steps.dailyTasks.subtasks');
        }

        return view('roadmap.index', compact('roadmap'));
    }
}

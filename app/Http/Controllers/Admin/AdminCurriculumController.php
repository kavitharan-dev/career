<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CareerDomain;
use App\Models\DailyTaskSubtaskTemplate;
use App\Models\DailyTaskTemplate;
use App\Models\User;
use App\Services\CareerRecommendationService;
use App\Services\RoadmapGeneratorService;
use Database\Seeders\CareerGuidanceSeeder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminCurriculumController extends Controller
{
    public function tools(): View
    {
        $stats = [
            'domains' => CareerDomain::count(),
            'steps' => CareerDomain::withCount('stepTemplates')->get()->sum('step_templates_count'),
            'tasks' => DailyTaskTemplate::count(),
            'subtasks' => DailyTaskSubtaskTemplate::count(),
            'students_with_roadmaps' => User::where('is_admin', false)->whereHas('roadmaps')->count(),
        ];

        return view('admin.curriculum.tools', compact('stats'));
    }

    public function reseed(Request $request): RedirectResponse
    {
        $request->validate(['confirm' => ['required', 'in:yes']]);

        (new CareerGuidanceSeeder)->run();

        return redirect()
            ->route('admin.curriculum.tools')
            ->with('success', 'Curriculum updated from the latest Arivexa learning paths (all 9 careers). Existing student roadmaps are unchanged until you regenerate them.');
    }

    public function regenerateAll(
        Request $request,
        CareerRecommendationService $careerService,
        RoadmapGeneratorService $roadmapService,
    ): RedirectResponse {
        $request->validate(['confirm' => ['required', 'in:yes']]);

        $count = 0;

        User::where('is_admin', false)
            ->whereHas('careerRecommendations')
            ->each(function (User $user) use ($careerService, $roadmapService, &$count) {
                $recommendation = $user->primaryRecommendation;

                if (! $recommendation) {
                    $recommendation = $careerService->generateFor($user);
                }

                $roadmapService->generateFor($user, $recommendation);
                $count++;
            });

        return redirect()
            ->route('admin.curriculum.tools')
            ->with('success', "Regenerated real roadmaps for {$count} student(s). They should refresh their Roadmap page.");
    }
}

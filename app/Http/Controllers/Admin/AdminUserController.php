<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\CareerRecommendationService;
use App\Services\RoadmapGeneratorService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    public function index(): View
    {
        $users = User::where('is_admin', false)
            ->with(['studentProfile', 'primaryRecommendation.careerDomain', 'activeRoadmap'])
            ->latest()
            ->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user): View
    {
        abort_if($user->is_admin, 404);

        $user->load([
            'studentProfile',
            'skills',
            'careerRecommendations.careerDomain',
            'activeRoadmap.steps.dailyTasks',
            'appNotifications' => fn ($q) => $q->latest()->limit(10),
        ]);

        return view('admin.users.show', compact('user'));
    }

    public function regenerateRoadmap(
        User $user,
        CareerRecommendationService $careerService,
        RoadmapGeneratorService $roadmapService,
    ): RedirectResponse {
        abort_if($user->is_admin, 404);

        if (! $user->hasCompletedOnboarding()) {
            return back()->withErrors(['roadmap' => 'Student has not finished onboarding yet.']);
        }

        $recommendation = $user->primaryRecommendation ?? $careerService->generateFor($user);
        $roadmap = $roadmapService->generateFor($user, $recommendation);

        return back()->with('success', "Roadmap rebuilt: {$roadmap->title} ({$roadmap->careerDomain->name}).");
    }
}

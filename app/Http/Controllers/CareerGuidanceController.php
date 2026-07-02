<?php

namespace App\Http\Controllers;

use App\Models\CareerDomain;
use App\Models\JobListing;
use App\Services\CareerGuidanceService;
use App\Services\CareerRecommendationService;
use Database\Seeders\CareerLearningExtrasData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CareerGuidanceController extends Controller
{
    public function __construct(
        protected CareerGuidanceService $guidance,
        protected CareerRecommendationService $recommendations,
    ) {}

    public function index(Request $request): View
    {
        $user = $request->user()->load(['skills', 'studentProfile', 'primaryRecommendation.careerDomain']);
        $summary = $this->guidance->profileSummary($user);
        $tips = $this->guidance->counselingTips($user);

        return view('guidance.index', compact('summary', 'tips'));
    }

    public function assessment(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        if (! $user->hasCompletedOnboarding()) {
            return redirect()->route('onboarding.index')
                ->with('error', 'Complete your profile and skill test first to see your career assessment.');
        }

        $summary = $this->guidance->profileSummary($user);
        $allScores = $this->recommendations->scoreAllDomainsFor($user);
        $tips = $this->guidance->counselingTips($user);
        $quizzesByCareer = $user->careerFitQuizzes()->get()->keyBy('career_domain_id');

        return view('guidance.assessment', compact('summary', 'allScores', 'tips', 'quizzesByCareer'));
    }

    public function explore(Request $request): View
    {
        $user = $request->user();
        $domains = CareerDomain::orderBy('name')->get();
        $scores = $user->hasCompletedOnboarding()
            ? $this->recommendations->scoreAllDomainsFor($user)->keyBy(fn ($row) => $row['domain']->id)
            : collect();

        return view('guidance.explore', compact('domains', 'scores'));
    }

    public function show(Request $request, CareerDomain $career): View
    {
        $user = $request->user()->load(['skills', 'careerFitQuizzes']);
        $fitScore = $this->guidance->fitScoreFor($user, $career);
        $skillGap = $this->guidance->skillGapAnalysis($user, $career);
        $careerQuiz = $user->careerFitQuizzes->firstWhere('career_domain_id', $career->id);

        $recommendation = $user->careerRecommendations()
            ->where('career_domain_id', $career->id)
            ->first();

        $activeJobs = JobListing::active()
            ->where(function ($q) use ($career) {
                $q->where('career_domain_id', $career->id)
                    ->orWhereNull('career_domain_id');
            })
            ->orderBy('expires_at')
            ->get();

        return view('guidance.show', compact('career', 'fitScore', 'skillGap', 'recommendation', 'careerQuiz', 'activeJobs'));
    }

    public function compare(Request $request): View
    {
        $user = $request->user();
        $domains = CareerDomain::orderBy('name')->get();
        $selected = collect();
        $comparison = collect();

        if ($request->isMethod('post')) {
            $validated = $request->validate([
                'career_ids' => ['required', 'array', 'min:2', 'max:3'],
                'career_ids.*' => ['exists:career_domains,id'],
            ]);

            $comparison = $this->guidance->compareCareers($user, $validated['career_ids']);
            $selected = CareerDomain::whereIn('id', $validated['career_ids'])->get();
        }

        return view('guidance.compare', compact('domains', 'selected', 'comparison'));
    }

    public function jobs(Request $request): View
    {
        $jobs = JobListing::active()
            ->with('careerDomain')
            ->orderBy('expires_at')
            ->orderByDesc('created_at')
            ->get();

        return view('guidance.jobs', compact('jobs'));
    }

    public function resources(Request $request): View
    {
        $careers = CareerDomain::orderBy('name')->get();
        $resourcesBySlug = $careers->mapWithKeys(fn ($career) => [
            $career->slug => CareerLearningExtrasData::resources($career->slug),
        ]);

        return view('guidance.resources', compact('careers', 'resourcesBySlug'));
    }

    public function interview(Request $request, CareerDomain $career): View
    {
        $questions = CareerLearningExtrasData::interviewQuestions($career->slug);

        return view('guidance.interview', compact('career', 'questions'));
    }
}

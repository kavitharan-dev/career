<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use App\Services\CareerFitScoringService;
use App\Services\CareerRecommendationService;
use App\Services\NotificationService;
use App\Services\RoadmapGeneratorService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class OnboardingController extends Controller
{
    public function __construct(
        protected CareerRecommendationService $careerService,
        protected CareerFitScoringService $fitScoring,
        protected RoadmapGeneratorService $roadmapService,
        protected NotificationService $notificationService,
    ) {}

    public function index(Request $request): View|RedirectResponse
    {
        $step = (int) $request->get('step', 1);
        $step = max(1, min(4, $step));

        $user = $request->user();
        $profile = $user->ensureProfile();

        if ($step >= 2 && empty($profile->education_level)) {
            return redirect()->route('onboarding.index', ['step' => 1])
                ->with('error', 'Please complete your academic info and interests first.');
        }

        if ($step >= 3 && $step < 4 && empty($profile->education_level)) {
            return redirect()->route('onboarding.index', ['step' => 1]);
        }

        if ($step === 4) {
            if (($profile->logical_score ?? 0) < 1 && ($profile->problem_solving_score ?? 0) < 1) {
                return redirect()->route('onboarding.index', ['step' => 3])
                    ->with('error', 'Complete the general aptitude test first.');
            }

            $top = $this->careerService->previewRankedDomains($user, 1)->first();
            $targetCareer = $top['domain'] ?? null;
            $fitQuestions = $targetCareer
                ? $this->fitScoring->questionsFor($targetCareer)
                : collect();

            return view('onboarding.index', [
                'step' => 4,
                'profile' => $profile,
                'targetCareer' => $targetCareer,
                'fitQuestions' => $fitQuestions,
                'predefinedSkills' => collect(),
                'selectedSkillIds' => [],
            ]);
        }

        $predefinedSkills = Skill::whereNull('user_id')->orderBy('name')->get();
        $selectedSkillIds = $user->skills()->pluck('skills.id')->all();

        return view('onboarding.index', compact('step', 'profile', 'predefinedSkills', 'selectedSkillIds'));
    }

    public function storeProfile(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'education_level' => ['required', 'string', 'in:school,college,graduate,other'],
            'math' => ['nullable', 'integer', 'min:0', 'max:100'],
            'science' => ['nullable', 'integer', 'min:0', 'max:100'],
            'english' => ['nullable', 'integer', 'min:0', 'max:100'],
            'computer_science' => ['nullable', 'integer', 'min:0', 'max:100'],
            'interests' => ['required', 'array', 'min:1'],
            'interests.*' => ['string', 'max:100'],
        ]);

        $profile = $request->user()->ensureProfile();
        $profile->update([
            'education_level' => $validated['education_level'],
            'academic_marks' => array_filter([
                'math' => $validated['math'] ?? null,
                'science' => $validated['science'] ?? null,
                'english' => $validated['english'] ?? null,
                'computer_science' => $validated['computer_science'] ?? null,
            ], fn ($v) => $v !== null),
            'interests' => $validated['interests'],
        ]);

        return redirect()->route('onboarding.index', ['step' => 2]);
    }

    public function storeSkills(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'skill_ids' => ['nullable', 'array'],
            'skill_ids.*' => ['exists:skills,id'],
            'custom_skill' => ['nullable', 'string', 'max:80'],
            'proficiency' => ['nullable', 'array'],
        ]);

        $user = $request->user();
        $sync = [];

        foreach ($validated['skill_ids'] ?? [] as $skillId) {
            $sync[$skillId] = ['proficiency' => (int) ($validated['proficiency'][$skillId] ?? 3)];
        }

        if (! empty($validated['custom_skill'])) {
            $skill = Skill::create([
                'name' => $validated['custom_skill'],
                'slug' => Skill::slugFromName($validated['custom_skill'], $user->id),
                'user_id' => $user->id,
            ]);
            $sync[$skill->id] = ['proficiency' => 3];
        }

        $user->skills()->sync($sync);

        return redirect()->route('onboarding.index', ['step' => 3]);
    }

    public function storeSkillTest(Request $request): RedirectResponse
    {
        $answers = $request->validate([
            'q1' => ['required', 'in:a,b,c,d'],
            'q2' => ['required', 'in:a,b,c,d'],
            'q3' => ['required', 'in:a,b,c,d'],
            'q4' => ['required', 'in:a,b,c,d'],
            'q5' => ['required', 'in:a,b,c,d'],
        ]);

        $logicalKeys = ['q1' => ['a' => 25, 'b' => 15, 'c' => 20, 'd' => 10], 'q3' => ['a' => 10, 'b' => 25, 'c' => 15, 'd' => 20], 'q5' => ['a' => 15, 'b' => 20, 'c' => 25, 'd' => 10]];
        $problemKeys = ['q2' => ['a' => 15, 'b' => 25, 'c' => 10, 'd' => 20], 'q4' => ['a' => 20, 'b' => 10, 'c' => 25, 'd' => 15]];

        $logical = 0;
        $problem = 0;

        foreach ($logicalKeys as $q => $map) {
            $logical += $map[$answers[$q]] ?? 0;
        }

        foreach ($problemKeys as $q => $map) {
            $problem += $map[$answers[$q]] ?? 0;
        }

        $user = $request->user();
        $profile = $user->ensureProfile();
        $profile->update([
            'logical_score' => min(100, $logical),
            'problem_solving_score' => min(100, $problem),
        ]);

        return redirect()
            ->route('onboarding.index', ['step' => 4])
            ->with('success', 'Aptitude saved. Answer 6 career-specific questions next — these are different for each job path.');
    }

    public function storeCareerFit(Request $request, CareerFitQuizController $fitQuiz): RedirectResponse
    {
        $top = $this->careerService->previewRankedDomains($request->user(), 1)->first();
        $career = $top['domain'] ?? null;

        if (! $career) {
            return redirect()->route('onboarding.index', ['step' => 3])
                ->withErrors(['career_fit' => 'No career available. Run CareerGuidanceSeeder.']);
        }

        $request->merge(['finish_onboarding' => true]);

        return $fitQuiz->store($request, $career);
    }

    public static function skillTestQuestions(): array
    {
        return [
            'q1' => [
                'question' => 'Which sequence comes next? 2, 4, 8, 16, ?',
                'options' => ['a' => '24', 'b' => '32', 'c' => '20', 'd' => '18'],
            ],
            'q2' => [
                'question' => 'A project deadline moved up by 2 days. Best first action?',
                'options' => ['a' => 'Panic and restart', 'b' => 'Reprioritize tasks', 'c' => 'Ignore the change', 'd' => 'Blame the team'],
            ],
            'q3' => [
                'question' => 'If all Bloops are Razzies, and some Razzies are Lazzies, which is logically safest?',
                'options' => ['a' => 'All Lazzies are Bloops', 'b' => 'Some Bloops may be Lazzies', 'c' => 'No Bloops are Lazzies', 'd' => 'All Lazzies are Bloops'],
            ],
            'q4' => [
                'question' => 'You have 3 hours and 5 small bugs. What do you do?',
                'options' => ['a' => 'Fix easiest first', 'b' => 'Triage by impact', 'c' => 'Fix randomly', 'd' => 'Wait for help'],
            ],
            'q5' => [
                'question' => 'A pattern: A=1, B=2, C=3. What is D?',
                'options' => ['a' => '3', 'b' => '4', 'c' => '5', 'd' => '2'],
            ],
        ];
    }
}

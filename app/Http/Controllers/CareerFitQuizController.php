<?php

namespace App\Http\Controllers;

use App\Models\CareerDomain;
use App\Models\CareerFitQuestion;
use App\Services\CareerFitScoringService;
use App\Services\CareerRecommendationService;
use App\Services\NotificationService;
use App\Services\RoadmapGeneratorService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CareerFitQuizController extends Controller
{
    public function __construct(
        protected CareerFitScoringService $fitScoring,
        protected CareerRecommendationService $careerService,
        protected RoadmapGeneratorService $roadmapService,
        protected NotificationService $notificationService,
    ) {}

    public function show(Request $request, CareerDomain $career): View|RedirectResponse
    {
        $questions = $this->fitScoring->questionsFor($career);

        if ($questions->isEmpty()) {
            return redirect()->route('guidance.show', $career)
                ->with('error', 'Fit questionnaire not available yet. Ask admin to run CareerFitQuestionSeeder.');
        }

        $existing = $this->fitScoring->quizFor($request->user(), $career);

        return view('guidance.fit-quiz', [
            'career' => $career,
            'questions' => $questions,
            'existingQuiz' => $existing,
        ]);
    }

    public function store(Request $request, CareerDomain $career): RedirectResponse
    {
        $questions = CareerFitQuestion::where('career_domain_id', $career->id)->get();
        $rules = [];

        foreach ($questions as $question) {
            $rules[$question->question_key] = ['required', 'in:a,b,c,d'];
        }

        $answers = $request->validate($rules);
        $user = $request->user();
        $quiz = $this->fitScoring->storeQuiz($user, $career, $answers);

        if ($request->boolean('finish_onboarding')) {
            $profile = $user->ensureProfile();
            $profile->update(['onboarding_completed' => true]);

            try {
                $recommendation = $this->careerService->generateFor($user);
                $this->roadmapService->generateFor($user, $recommendation);
                $this->notificationService->notifyImprovement(
                    $user,
                    'Welcome to Arivexa!',
                    'Your personalized roadmap is ready. Start with your first daily task.'
                );
                $this->notificationService->sendWelcomeEmail($user, $recommendation);
                $this->notificationService->syncReminders($user);
            } catch (\Throwable $e) {
                report($e);

                return redirect()
                    ->route('onboarding.index', ['step' => 4])
                    ->withErrors(['career_fit' => 'Could not finalize your career path. Please submit again.']);
            }

            return redirect()
                ->route('guidance.assessment')
                ->with('success', "Career fit: {$quiz->fit_score}% — {$quiz->verdictLabel()}. Your assessment report is ready.");
        }

        $this->careerService->generateFor($user);

        return redirect()
            ->route('guidance.show', $career)
            ->with('success', "Quiz saved: {$quiz->fit_score}% — {$quiz->verdictLabel()}. Match scores updated.");
    }
}

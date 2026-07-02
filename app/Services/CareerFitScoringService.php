<?php

namespace App\Services;

use App\Models\CareerDomain;
use App\Models\CareerFitQuestion;
use App\Models\User;
use App\Models\UserCareerFitQuiz;
use Illuminate\Support\Collection;

class CareerFitScoringService
{
    /**
     * @param  array<string, string>  $answers  question_key => option key (a,b,c,d)
     * @return array{fit_score: int, verdict: string, summary: string, raw_points: int, max_points: int}
     */
    public function scoreAnswers(CareerDomain $domain, array $answers): array
    {
        $questions = CareerFitQuestion::where('career_domain_id', $domain->id)->orderBy('sort_order')->get();
        $rawPoints = 0;
        $maxPoints = 0;

        foreach ($questions as $question) {
            $options = $question->options ?? [];
            $maxForQuestion = 0;
            foreach ($options as $opt) {
                $maxForQuestion = max($maxForQuestion, (int) ($opt['points'] ?? 0));
            }
            $maxPoints += $maxForQuestion;

            $chosen = $answers[$question->question_key] ?? null;
            if ($chosen && isset($options[$chosen])) {
                $rawPoints += (int) ($options[$chosen]['points'] ?? 0);
            }
        }

        $fitScore = $maxPoints > 0 ? (int) round(($rawPoints / $maxPoints) * 100) : 0;

        return [
            'fit_score' => $fitScore,
            'verdict' => $this->verdictFromScore($fitScore),
            'summary' => $this->summaryFromScore($domain, $fitScore),
            'raw_points' => $rawPoints,
            'max_points' => $maxPoints,
        ];
    }

    public function storeQuiz(User $user, CareerDomain $domain, array $answers): UserCareerFitQuiz
    {
        $result = $this->scoreAnswers($domain, $answers);

        return UserCareerFitQuiz::updateOrCreate(
            [
                'user_id' => $user->id,
                'career_domain_id' => $domain->id,
            ],
            [
                'answers' => $answers,
                'fit_score' => $result['fit_score'],
                'verdict' => $result['verdict'],
                'verdict_summary' => $result['summary'],
                'completed_at' => now(),
            ]
        );
    }

    public function quizFor(User $user, CareerDomain $domain): ?UserCareerFitQuiz
    {
        return UserCareerFitQuiz::where('user_id', $user->id)
            ->where('career_domain_id', $domain->id)
            ->first();
    }

    public function verdictFromScore(int $score): string
    {
        if ($score >= 75) {
            return UserCareerFitQuiz::VERDICT_STRONG;
        }

        if ($score >= 50) {
            return UserCareerFitQuiz::VERDICT_MODERATE;
        }

        return UserCareerFitQuiz::VERDICT_LOW;
    }

    public function summaryFromScore(CareerDomain $domain, int $score): string
    {
        if ($score >= 75) {
            return "Your answers show a strong alignment with {$domain->name}. Skills and mindset match what employers expect in Sri Lanka.";
        }

        if ($score >= 50) {
            return "You can pursue {$domain->name} with focused learning. Some areas need improvement — use the skill gap report and roadmap.";
        }

        return "This career fit quiz suggests {$domain->name} may not be your best first choice. Explore higher-scoring careers or retake after building skills.";
    }

    /**
     * @return Collection<int, CareerFitQuestion>
     */
    public function questionsFor(CareerDomain $domain): Collection
    {
        return CareerFitQuestion::where('career_domain_id', $domain->id)->orderBy('sort_order')->get();
    }
}

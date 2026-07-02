<?php

namespace App\Services;

use App\Models\CareerDomain;
use App\Models\CareerRecommendation;
use App\Models\User;
use App\Models\UserCareerFitQuiz;
use Database\Seeders\CareerGuidanceSeeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use RuntimeException;

class CareerRecommendationService
{
    public function __construct(
        protected CareerMlRecommendationService $mlService,
        protected CareerFitScoringService $fitScoring,
    ) {}

    public function generateFor(User $user): CareerRecommendation
    {
        $profile = $user->studentProfile;

        if (! $profile) {
            throw new RuntimeException('Student profile is missing. Complete onboarding step 1 first.');
        }

        $domains = CareerDomain::all();

        if ($domains->isEmpty()) {
            (new CareerGuidanceSeeder)->run();
            $domains = CareerDomain::all();
        }

        if ($domains->isEmpty()) {
            throw new RuntimeException('No career domains configured. Run: php artisan db:seed --class=CareerGuidanceSeeder');
        }

        $scored = $this->scoreAllDomainsFor($user);

        $user->careerRecommendations()->delete();

        foreach ($scored->take(3) as $index => $item) {
            CareerRecommendation::create([
                'user_id' => $user->id,
                'career_domain_id' => $item['domain']->id,
                'match_score' => $item['score'],
                'reasoning' => $item['reason'],
                'is_primary' => $index === 0,
            ]);
        }

        $user->unsetRelation('careerRecommendations');
        $user->unsetRelation('primaryRecommendation');

        $recommendation = CareerRecommendation::query()
            ->where('user_id', $user->id)
            ->where('is_primary', true)
            ->first();

        if (! $recommendation) {
            $recommendation = CareerRecommendation::query()
                ->where('user_id', $user->id)
                ->orderByDesc('match_score')
                ->first();
        }

        if (! $recommendation) {
            throw new RuntimeException('Could not create a career recommendation. Please try again or contact support.');
        }

        return $recommendation;
    }

    /**
     * Top careers from profile + aptitude only (before career-specific quiz).
     *
     * @return Collection<int, array{domain: CareerDomain, score: int}>
     */
    public function previewRankedDomains(User $user, int $limit = 1): Collection
    {
        return $this->scoreDomainsWithoutCareerQuiz($user)->take($limit);
    }

    /**
     * @return Collection<int, array{domain: CareerDomain, score: int, reason: string, quiz: ?UserCareerFitQuiz, raw_score: int}>
     */
    public function scoreAllDomainsFor(User $user): Collection
    {
        $profile = $user->studentProfile;

        if (! $profile) {
            return collect();
        }

        $mlScores = $this->mlService->predictScores($user);
        $quizzes = $user->careerFitQuizzes()->get()->keyBy('career_domain_id');

        $rows = CareerDomain::all()->map(function (CareerDomain $domain) use ($user, $mlScores, $quizzes) {
            $quiz = $quizzes->get($domain->id);
            $profileScore = $this->profileMatchScore($domain, $user);
            $mlScore = $mlScores[$domain->slug] ?? 50;

            if ($quiz) {
                $raw = (int) round(
                    ($profileScore * 0.25) + ($quiz->fit_score * 0.55) + ($mlScore * 0.20)
                );
            } else {
                $raw = (int) round(($profileScore * 0.45) + ($mlScore * 0.55));
            }

            return [
                'domain' => $domain,
                'raw_score' => min(100, max(0, $raw)),
                'score' => 0,
                'reason' => $this->buildReason($domain, $user, $quiz, $mlScore, $profileScore),
                'quiz' => $quiz,
            ];
        });

        return $this->applyRankSpread($rows);
    }

    /**
     * @return Collection<int, array{domain: CareerDomain, score: int}>
     */
    protected function scoreDomainsWithoutCareerQuiz(User $user): Collection
    {
        $mlScores = $this->mlService->predictScores($user);

        $rows = CareerDomain::all()->map(function (CareerDomain $domain) use ($user, $mlScores) {
            $profileScore = $this->profileMatchScore($domain, $user);
            $mlScore = $mlScores[$domain->slug] ?? 50;
            $raw = (int) round(($profileScore * 0.45) + ($mlScore * 0.55));

            return [
                'domain' => $domain,
                'raw_score' => min(100, max(0, $raw)),
                'score' => 0,
            ];
        });

        return $this->applyRankSpread($rows)->map(fn ($row) => [
            'domain' => $row['domain'],
            'score' => $row['score'],
        ]);
    }

    /**
     * Ensures ranked scores are not artificially clustered (no 55% floor).
     *
     * @param  Collection<int, array{domain: CareerDomain, raw_score: int, score: int, ...}>  $rows
     */
    protected function applyRankSpread(Collection $rows): Collection
    {
        $sorted = $rows->sortByDesc('raw_score')->values();
        $previous = 101;

        return $sorted->map(function (array $row, int $index) use (&$previous) {
            $score = $row['raw_score'];

            if ($index > 0 && ($previous - $score) < 3) {
                $score = max(0, $previous - 3);
            }

            $row['score'] = $score;
            $previous = $score;

            return $row;
        })->sortByDesc('score')->values();
    }

    public function previewMatchScore(User $user, CareerDomain $domain): ?int
    {
        if (! $user->studentProfile) {
            return null;
        }

        $item = $this->scoreAllDomainsFor($user)->first(fn ($row) => $row['domain']->id === $domain->id);

        return $item['score'] ?? null;
    }

    protected function profileMatchScore(CareerDomain $domain, User $user): int
    {
        $profile = $user->studentProfile;
        $skillSlugs = $user->skills()->pluck('slug')->all();
        $interests = collect($profile->interests ?? [])->map(fn ($i) => Str::lower($i));
        $marks = collect($profile->academic_marks ?? []);
        $avgMark = $marks->isEmpty() ? null : (int) round($marks->avg());
        $logical = (int) ($profile->logical_score ?? 0);
        $problem = (int) ($profile->problem_solving_score ?? 0);
        $weights = $domain->trait_weights ?? [];

        $score = 0;
        $required = $domain->required_skill_slugs ?? [];
        $skillOverlap = count(array_intersect($required, $skillSlugs));
        $score += min(30, $skillOverlap * 10);

        foreach ($interests as $interest) {
            foreach ($weights['interest_keywords'] ?? [] as $keyword) {
                if (Str::contains($interest, Str::lower($keyword))) {
                    $score += 6;
                }
            }
        }
        $score = min(35, $score);

        $score += (int) (($logical / 100) * min(22, $weights['logical'] ?? 18));
        $score += (int) (($problem / 100) * min(22, $weights['problem_solving'] ?? 18));

        if ($avgMark !== null) {
            $score += (int) (($avgMark / 100) * min(15, $weights['academics'] ?? 12));
        } else {
            $score += 5;
        }

        return min(100, max(0, $score));
    }

    protected function buildReason(
        CareerDomain $domain,
        User $user,
        ?UserCareerFitQuiz $quiz,
        int $mlScore,
        int $profileScore,
    ): string {
        $parts = [];

        if ($quiz) {
            $parts[] = "Career fit questionnaire: {$quiz->fit_score}% ({$quiz->verdictLabel()}).";
        } else {
            $parts[] = 'Complete the career-specific fit quiz for a more accurate match score.';
        }

        $parts[] = "Profile alignment {$profileScore}%, ML similarity {$mlScore}%.";

        $skillOverlap = count(array_intersect(
            $domain->required_skill_slugs ?? [],
            $user->skills()->pluck('slug')->all()
        ));

        if ($skillOverlap > 0) {
            $parts[] = 'Your selected skills overlap with this field.';
        }

        $logical = (int) ($user->studentProfile->logical_score ?? 0);
        $problem = (int) ($user->studentProfile->problem_solving_score ?? 0);

        if ($logical >= 65) {
            $parts[] = 'Logical thinking test supports analytical work.';
        }

        if ($problem >= 65) {
            $parts[] = 'Problem-solving score fits structured learning.';
        }

        return implode(' ', $parts);
    }

    public function topDomainsFor(User $user, int $limit = 3): Collection
    {
        return $user->careerRecommendations()
            ->with('careerDomain')
            ->orderByDesc('match_score')
            ->limit($limit)
            ->get();
    }
}

<?php

namespace App\Services;

use App\Models\CareerDomain;
use App\Models\User;
use Illuminate\Support\Str;
use Phpml\Classification\KNearestNeighbors;
use Throwable;

class CareerMlRecommendationService
{
    /** Must match centroid length (5 scores + 5 interests + 8 skills). */
    private const FEATURE_SIZE = 18;

    /** @var array<string, array<float>> */
    protected array $domainCentroids = [
        'programming' => [0.85, 0.85, 0.75, 0.8, 0.85, 1, 0, 0, 0, 0, 1, 1, 1, 0, 0, 0, 0, 0],
        'data-science' => [0.9, 0.8, 0.85, 0.9, 0.85, 0, 1, 0, 0, 0, 1, 1, 0, 1, 0, 0, 0, 0],
        'web-development' => [0.7, 0.75, 0.7, 0.65, 0.7, 0, 0, 1, 0, 0, 1, 0, 1, 0, 1, 0, 0, 0],
        'design' => [0.5, 0.6, 0.65, 0.55, 0.6, 0, 0, 0, 1, 0, 0, 0, 1, 0, 1, 1, 0, 0],
        'cybersecurity' => [0.88, 0.82, 0.8, 0.75, 0.8, 0, 0, 0, 0, 1, 1, 1, 0, 0, 0, 0, 1, 0],
        'mobile-development' => [0.75, 0.78, 0.72, 0.7, 0.75, 0, 0, 1, 0, 0, 1, 1, 0, 0, 0, 0, 0, 1],
        'digital-marketing' => [0.55, 0.65, 0.7, 0.6, 0.65, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, 0, 1],
        'cloud-devops' => [0.8, 0.85, 0.78, 0.82, 0.8, 0, 0, 0, 0, 0, 1, 1, 1, 0, 0, 0, 1, 0],
        'ai-engineering' => [0.92, 0.88, 0.88, 0.9, 0.88, 1, 1, 0, 0, 0, 1, 1, 1, 1, 0, 0, 0, 0],
    ];

    /**
     * @return array<string, int> domain slug => ML score 0-100
     */
    public function predictScores(User $user): array
    {
        $domains = CareerDomain::all();
        $userVector = $this->extractFeatures($user);
        $scores = [];

        foreach ($domains as $domain) {
            $centroid = $this->normalizeVector(
                $this->domainCentroids[$domain->slug] ?? $this->defaultCentroid()
            );
            $distance = $this->euclideanDistance($userVector, $centroid);
            $scores[$domain->slug] = (int) max(50, min(98, 100 - ($distance * 35)));
        }

        $knnBoost = $this->knnConfidence($userVector, $domains);

        foreach ($scores as $slug => $score) {
            $boost = $knnBoost[$slug] ?? 0;
            $scores[$slug] = (int) min(98, $score + $boost);
        }

        return $scores;
    }

    /**
     * @return array<float>
     */
    public function extractFeatures(User $user): array
    {
        $profile = $user->studentProfile;
        $marks = $profile?->academic_marks ?? [];
        $interests = collect($profile?->interests ?? [])->map(fn ($i) => Str::lower($i))->all();
        $skillSlugs = $user->skills()->pluck('slug')->map(fn ($s) => Str::before($s, '-u'))->all();

        $interestFlags = [
            $this->interestMatch($interests, ['program', 'code', 'software']),
            $this->interestMatch($interests, ['data', 'analytics', 'ai']),
            $this->interestMatch($interests, ['web', 'frontend']),
            $this->interestMatch($interests, ['design', 'creative', 'ui']),
            $this->interestMatch($interests, ['security', 'cyber', 'business', 'market', 'health', 'teach', 'research', 'entrepreneur']),
        ];

        $skillFlags = [
            in_array('programming', $skillSlugs, true) ? 1.0 : 0.0,
            in_array('data-analysis', $skillSlugs, true) ? 1.0 : 0.0,
            in_array('ui-design', $skillSlugs, true) ? 1.0 : 0.0,
            in_array('logical-thinking', $skillSlugs, true) ? 1.0 : 0.0,
            in_array('creativity', $skillSlugs, true) ? 1.0 : 0.0,
            in_array('communication', $skillSlugs, true) ? 1.0 : 0.0,
            in_array('problem-solving', $skillSlugs, true) ? 1.0 : 0.0,
            in_array('mathematics', $skillSlugs, true) ? 1.0 : 0.0,
        ];

        $vector = array_merge([
            ($profile?->logical_score ?? 0) / 100,
            ($profile?->problem_solving_score ?? 0) / 100,
            $this->mark($marks, 'math') / 100,
            $this->mark($marks, 'science') / 100,
            $this->mark($marks, 'computer_science') / 100,
        ], $interestFlags, $skillFlags);

        return $this->normalizeVector($vector);
    }

    /**
     * @param  \Illuminate\Support\Collection<int, CareerDomain>  $domains
     * @return array<string, int>
     */
    protected function knnConfidence(array $userVector, $domains): array
    {
        $userVector = $this->normalizeVector($userVector);

        try {
            $samples = [];
            $labels = [];

            foreach ($domains as $domain) {
                $centroid = $this->normalizeVector(
                    $this->domainCentroids[$domain->slug] ?? $this->defaultCentroid()
                );

                for ($i = 0; $i < 12; $i++) {
                    $samples[] = $this->perturb($centroid);
                    $labels[] = $domain->slug;
                }
            }

            if (count($samples) < 5) {
                return [];
            }

            $classifier = new KNearestNeighbors(7);
            $classifier->train($samples, $labels);

            $predicted = $classifier->predict([$userVector])[0];
            $boost = array_fill_keys($domains->pluck('slug')->all(), 0);
            $boost[$predicted] = 8;

            return $boost;
        } catch (Throwable) {
            return [];
        }
    }

    /**
     * @param  array<float>  $vector
     * @return array<float>
     */
    protected function normalizeVector(array $vector): array
    {
        $normalized = array_pad(array_values($vector), self::FEATURE_SIZE, 0.0);

        return array_map(
            fn ($v) => max(0.0, min(1.0, (float) $v)),
            array_slice($normalized, 0, self::FEATURE_SIZE)
        );
    }

    /**
     * @param  array<float>  $vector
     * @return array<float>
     */
    protected function perturb(array $vector): array
    {
        return array_map(
            fn ($v) => max(0, min(1, $v + (mt_rand(-12, 12) / 100))),
            $this->normalizeVector($vector)
        );
    }

    /**
     * @param  array<float>  $a
     * @param  array<float>  $b
     */
    protected function euclideanDistance(array $a, array $b): float
    {
        $a = $this->normalizeVector($a);
        $b = $this->normalizeVector($b);

        $sum = 0.0;
        for ($i = 0; $i < self::FEATURE_SIZE; $i++) {
            $sum += ($a[$i] - $b[$i]) ** 2;
        }

        return sqrt($sum);
    }

    /**
     * @return array<float>
     */
    protected function defaultCentroid(): array
    {
        return array_fill(0, self::FEATURE_SIZE, 0.5);
    }

    /**
     * @param  array<string, mixed>  $marks
     */
    protected function mark(array $marks, string $key): float
    {
        return (float) ($marks[$key] ?? 70);
    }

    /**
     * @param  array<string>  $interests
     * @param  array<string>  $keywords
     */
    protected function interestMatch(array $interests, array $keywords): float
    {
        foreach ($interests as $interest) {
            foreach ($keywords as $keyword) {
                if (Str::contains($interest, $keyword)) {
                    return 1.0;
                }
            }
        }

        return 0.0;
    }
}

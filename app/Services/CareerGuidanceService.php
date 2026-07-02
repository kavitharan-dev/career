<?php

namespace App\Services;

use App\Models\CareerDomain;
use App\Models\CareerRecommendation;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class CareerGuidanceService
{
    public function __construct(
        protected CareerRecommendationService $recommendationService,
    ) {}

    public function profileSummary(User $user): array
    {
        $profile = $user->studentProfile;
        $recommendations = $user->careerRecommendations()
            ->with('careerDomain')
            ->orderByDesc('match_score')
            ->get();

        $primary = $recommendations->firstWhere('is_primary', true) ?? $recommendations->first();

        return [
            'education_level' => $profile?->education_level,
            'interests' => $profile?->interests ?? [],
            'academic_marks' => $profile?->academic_marks ?? [],
            'logical_score' => $profile?->logical_score ?? 0,
            'problem_solving_score' => $profile?->problem_solving_score ?? 0,
            'strengths' => $this->deriveStrengths($user),
            'recommendations' => $recommendations,
            'primary' => $primary,
        ];
    }

    public function fitScoreFor(User $user, CareerDomain $domain): ?int
    {
        $rec = $user->careerRecommendations()
            ->where('career_domain_id', $domain->id)
            ->first();

        if ($rec) {
            return $rec->match_score;
        }

        return $this->recommendationService->previewMatchScore($user, $domain);
    }

    /**
     * @return array{matched: array<int, string>, gaps: array<int, string>, advice: string}
     */
    public function skillGapAnalysis(User $user, CareerDomain $domain): array
    {
        $requiredSlugs = $domain->required_skill_slugs ?? [];
        $userSkillSlugs = $user->skills()->pluck('slug')->map(fn ($s) => Str::before($s, '-u'))->all();

        $allSkills = Skill::whereNull('user_id')->get()->keyBy('slug');

        $matched = [];
        $gaps = [];

        foreach ($requiredSlugs as $slug) {
            $name = $allSkills->get($slug)?->name ?? Str::headline(str_replace('-', ' ', $slug));
            if (in_array($slug, $userSkillSlugs, true)) {
                $matched[] = $name;
            } else {
                $gaps[] = $name;
            }
        }

        foreach ($domain->key_skills ?? [] as $skillName) {
            $normalized = Str::slug($skillName);
            if (! in_array($normalized, array_map(fn ($s) => Str::slug($s), $matched), true)
                && ! in_array($skillName, $gaps, true)
                && ! in_array($skillName, $matched, true)) {
                if (! $this->userHasSkillNamed($user, $skillName)) {
                    $gaps[] = $skillName;
                }
            }
        }

        $gaps = array_values(array_unique($gaps));
        $matched = array_values(array_unique($matched));

        $advice = count($gaps) === 0
            ? 'Your current skills align well with this career. Focus on your learning roadmap to go deeper.'
            : 'Close these skill gaps through your Arivexa learning roadmap and short online courses (W3Schools, Coursera, YouTube).';

        return compact('matched', 'gaps', 'advice');
    }

    /**
     * @param  array<int, int>  $domainIds
     * @return Collection<int, array{domain: CareerDomain, score: ?int, gaps_count: int}>
     */
    public function compareCareers(User $user, array $domainIds): Collection
    {
        $domains = CareerDomain::whereIn('id', $domainIds)->get();

        return $domains->map(function (CareerDomain $domain) use ($user) {
            $gaps = $this->skillGapAnalysis($user, $domain);

            return [
                'domain' => $domain,
                'score' => $this->fitScoreFor($user, $domain),
                'gaps_count' => count($gaps['gaps']),
                'matched_count' => count($gaps['matched']),
            ];
        })->sortByDesc('score')->values();
    }

    public function counselingTips(User $user): array
    {
        $summary = $this->profileSummary($user);
        $tips = [];

        if (($summary['logical_score'] ?? 0) >= 70) {
            $tips[] = 'Your logical thinking score is strong — analytical careers (Programming, Data Science, AI, Cybersecurity) suit you.';
        }

        if (($summary['problem_solving_score'] ?? 0) >= 70) {
            $tips[] = 'Your problem-solving ability supports engineering and technical roles where you fix real-world issues.';
        }

        $interests = collect($summary['interests'] ?? [])->map(fn ($i) => Str::lower($i));

        if ($interests->contains(fn ($i) => Str::contains($i, 'program') || Str::contains($i, 'data'))) {
            $tips[] = 'Your interests point toward technology — explore Programming, Web Development, or AI Engineering in Career Explorer.';
        }

        if ($interests->contains(fn ($i) => Str::contains($i, 'design') || Str::contains($i, 'business'))) {
            $tips[] = 'Creative or business interests — consider Design or Digital Marketing alongside technical options.';
        }

        if ($summary['primary']) {
            $tips[] = "Primary recommendation: {$summary['primary']->careerDomain->name} ({$summary['primary']->match_score}% fit). Review skill gaps before committing.";
        } else {
            $tips[] = 'Complete your profile and skill test to unlock personalized match scores for every career.';
        }

        $tips[] = 'Use Compare Careers to see two paths side by side before you choose your learning roadmap.';

        return $tips;
    }

    protected function deriveStrengths(User $user): array
    {
        $strengths = [];
        $profile = $user->studentProfile;

        if (($profile?->logical_score ?? 0) >= 65) {
            $strengths[] = 'Logical thinking';
        }

        if (($profile?->problem_solving_score ?? 0) >= 65) {
            $strengths[] = 'Problem solving';
        }

        foreach ($user->skills as $skill) {
            if ($skill->pivot->proficiency >= 4) {
                $strengths[] = $skill->name.' (strong)';
            } elseif ($skill->pivot->proficiency >= 3) {
                $strengths[] = $skill->name;
            }
        }

        foreach ($profile?->interests ?? [] as $interest) {
            $strengths[] = 'Interest: '.$interest;
        }

        return array_slice(array_unique($strengths), 0, 8);
    }

    protected function userHasSkillNamed(User $user, string $skillName): bool
    {
        return $user->skills->contains(fn ($s) => Str::lower($s->name) === Str::lower($skillName));
    }
}

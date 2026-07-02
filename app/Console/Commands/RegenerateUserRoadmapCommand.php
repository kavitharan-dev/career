<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\CareerRecommendationService;
use App\Services\RoadmapGeneratorService;
use Illuminate\Console\Command;

class RegenerateUserRoadmapCommand extends Command
{
    protected $signature = 'arivexa:regenerate-roadmap {email? : User email (default: all students with a career match)}';

    protected $description = 'Rebuild a student roadmap from the latest real curriculum templates';

    public function handle(
        CareerRecommendationService $careerService,
        RoadmapGeneratorService $roadmapService,
    ): int {
        $email = $this->argument('email');

        $users = $email
            ? User::where('email', $email)->where('is_admin', false)->get()
            : User::where('is_admin', false)->whereHas('careerRecommendations')->get();

        if ($users->isEmpty()) {
            $this->error('No matching students found.');

            return self::FAILURE;
        }

        foreach ($users as $user) {
            $recommendation = $user->primaryRecommendation;

            if (! $recommendation) {
                $recommendation = $careerService->generateFor($user);
            }

            $roadmap = $roadmapService->generateFor($user, $recommendation);

            $this->line("Regenerated for {$user->email}: {$roadmap->title} ({$roadmap->careerDomain->name})");
        }

        $this->info('Done. Students should refresh their Roadmap page.');

        return self::SUCCESS;
    }
}

<?php

namespace App\Services;

use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;

class StudentReportService
{
    public function __construct(
        protected ProgressTrackingService $progress
    ) {}

    public function pdfFor(User $user)
    {
        $user->load([
            'studentProfile',
            'skills',
            'careerRecommendations.careerDomain',
            'primaryRecommendation.careerDomain',
            'activeRoadmap.steps.dailyTasks',
        ]);

        $stats = $this->progress->dashboardStats($user);

        return Pdf::loadView('reports.student-pdf', [
            'user' => $user,
            'stats' => $stats,
            'generatedAt' => now(),
        ])->setPaper('a4');
    }
}

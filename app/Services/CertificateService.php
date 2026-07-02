<?php

namespace App\Services;

use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CertificateService
{
    public function pdfFor(User $user)
    {
        $user->load(['primaryRecommendation.careerDomain', 'activeRoadmap']);

        $roadmap = $user->activeRoadmap;

        if (! $roadmap || $roadmap->completion_percentage < 100) {
            throw new HttpException(403, 'Complete your full learning roadmap (100%) to download your certificate.');
        }

        return Pdf::loadView('certificates.completion', [
            'user' => $user,
            'roadmap' => $roadmap,
            'career' => $user->primaryRecommendation?->careerDomain,
            'issuedAt' => now(),
        ])->setPaper('a4', 'landscape');
    }
}

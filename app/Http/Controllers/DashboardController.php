<?php

namespace App\Http\Controllers;

use App\Models\JobListing;
use App\Services\BadgeService;
use App\Services\NotificationService;
use App\Services\ProgressTrackingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        protected ProgressTrackingService $progress,
        protected NotificationService $notifications,
        protected BadgeService $badges,
    ) {}

    public function index(Request $request): View|RedirectResponse
    {
        if ($request->user()->is_admin) {
            return redirect()->route('admin.dashboard');
        }

        $user = $request->user()->load([
            'skills',
            'studentProfile',
            'primaryRecommendation.careerDomain',
            'activeRoadmap',
            'badges',
        ]);
        $this->notifications->syncReminders($user);
        $this->badges->syncFor($user);
        $user->load('badges');

        $stats = $this->progress->dashboardStats($user);
        $notifications = $user->appNotifications()->latest()->limit(5)->get();
        $recommendations = $user->careerRecommendations()->with('careerDomain')->orderByDesc('match_score')->get();
        $badges = $user->badges;
        $canDownloadCertificate = ($stats['completion_percentage'] ?? 0) >= 100;

        $primaryCareerId = $user->primaryRecommendation?->career_domain_id;
        $jobListings = JobListing::active()
            ->with('careerDomain')
            ->when($primaryCareerId, function ($query) use ($primaryCareerId) {
                $query->orderByRaw('CASE WHEN career_domain_id = ? OR career_domain_id IS NULL THEN 0 ELSE 1 END', [$primaryCareerId]);
            })
            ->orderBy('expires_at')
            ->orderByDesc('created_at')
            ->limit(8)
            ->get();

        return view('dashboard.index', compact('stats', 'notifications', 'recommendations', 'badges', 'canDownloadCertificate', 'jobListings'));
    }
}

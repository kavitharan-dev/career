<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CareerDomain;
use App\Models\CareerRecommendation;
use App\Models\DailyTask;
use App\Models\JobListing;
use App\Models\User;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'students' => User::where('is_admin', false)->count(),
            'domains' => CareerDomain::count(),
            'recommendations' => CareerRecommendation::count(),
            'completed_tasks' => DailyTask::where('status', DailyTask::STATUS_COMPLETED)->count(),
            'active_jobs' => JobListing::active()->count(),
        ];

        $recentStudents = User::where('is_admin', false)
            ->with('studentProfile', 'primaryRecommendation.careerDomain', 'activeRoadmap')
            ->latest()
            ->limit(8)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentStudents'));
    }
}

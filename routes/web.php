<?php

use App\Http\Controllers\Admin\AdminCareerDomainController;
use App\Http\Controllers\Admin\AdminCurriculumController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminJobListingController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CareerFitQuizController;
use App\Http\Controllers\CareerGuidanceController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RoadmapController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('home'))->name('home');

Route::middleware(['guest', 'no.cache'])->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware(['auth', 'no.cache'])->name('logout');

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/curriculum', [AdminCurriculumController::class, 'tools'])->name('curriculum.tools');
    Route::post('/curriculum/reseed', [AdminCurriculumController::class, 'reseed'])->name('curriculum.reseed');
    Route::post('/roadmaps/regenerate-all', [AdminCurriculumController::class, 'regenerateAll'])->name('roadmaps.regenerate-all');
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [AdminUserController::class, 'show'])->name('users.show');
    Route::post('/users/{user}/regenerate-roadmap', [AdminUserController::class, 'regenerateRoadmap'])->name('users.regenerate-roadmap');
    Route::get('/domains', [AdminCareerDomainController::class, 'index'])->name('domains.index');
    Route::get('/domains/{domain}', [AdminCareerDomainController::class, 'show'])->name('domains.show');
    Route::get('/domains/{domain}/edit', [AdminCareerDomainController::class, 'edit'])->name('domains.edit');
    Route::put('/domains/{domain}', [AdminCareerDomainController::class, 'update'])->name('domains.update');
    Route::get('/jobs', [AdminJobListingController::class, 'index'])->name('jobs.index');
    Route::post('/jobs', [AdminJobListingController::class, 'store'])->name('jobs.store');
    Route::get('/jobs/{job}/edit', [AdminJobListingController::class, 'edit'])->name('jobs.edit');
    Route::put('/jobs/{job}', [AdminJobListingController::class, 'update'])->name('jobs.update');
    Route::delete('/jobs/{job}', [AdminJobListingController::class, 'destroy'])->name('jobs.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/onboarding', [OnboardingController::class, 'index'])->name('onboarding.index');
    Route::post('/onboarding/profile', [OnboardingController::class, 'storeProfile'])->name('onboarding.profile');
    Route::post('/onboarding/skills', [OnboardingController::class, 'storeSkills'])->name('onboarding.skills');
    Route::post('/onboarding/skill-test', [OnboardingController::class, 'storeSkillTest'])->name('onboarding.skill-test');
    Route::post('/onboarding/career-fit', [OnboardingController::class, 'storeCareerFit'])->name('onboarding.career-fit');

    Route::get('/chatbot', [ChatbotController::class, 'index'])->name('chatbot');
    Route::post('/chatbot/send', [ChatbotController::class, 'send'])->name('chatbot.send');

    Route::middleware('onboarding.complete')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/career-guidance', [CareerGuidanceController::class, 'index'])->name('guidance.index');
        Route::get('/career-guidance/assessment', [CareerGuidanceController::class, 'assessment'])->name('guidance.assessment');
        Route::get('/career-guidance/explore', [CareerGuidanceController::class, 'explore'])->name('guidance.explore');
        Route::get('/career-guidance/compare', [CareerGuidanceController::class, 'compare'])->name('guidance.compare');
        Route::post('/career-guidance/compare', [CareerGuidanceController::class, 'compare']);
        Route::get('/career-guidance/jobs', [CareerGuidanceController::class, 'jobs'])->name('guidance.jobs');
        Route::get('/career-guidance/resources', [CareerGuidanceController::class, 'resources'])->name('guidance.resources');
        Route::get('/career-guidance/careers/{career}/interview', [CareerGuidanceController::class, 'interview'])->name('guidance.interview');
        Route::get('/career-guidance/careers/{career}', [CareerGuidanceController::class, 'show'])->name('guidance.show');
        Route::get('/career-guidance/careers/{career}/fit-quiz', [CareerFitQuizController::class, 'show'])->name('guidance.fit-quiz');
        Route::post('/career-guidance/careers/{career}/fit-quiz', [CareerFitQuizController::class, 'store'])->name('guidance.fit-quiz.store');
        Route::get('/roadmap', [RoadmapController::class, 'index'])->name('roadmap');
        Route::get('/report/pdf', [ReportController::class, 'download'])->name('report.pdf');
        Route::get('/report/certificate', [ReportController::class, 'certificate'])->name('report.certificate');
        Route::post('/tasks/{task}/complete', [TaskController::class, 'complete'])->name('tasks.complete');
        Route::post('/tasks/{task}/uncomplete', [TaskController::class, 'uncomplete'])->name('tasks.uncomplete');
        Route::post('/tasks/{task}/subtasks/{subtask}/toggle', [TaskController::class, 'toggleSubtask'])->name('tasks.subtasks.toggle');
        Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications');
        Route::patch('/notifications/{notification}/read', [NotificationController::class, 'markRead'])->name('notifications.read');
        Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.read-all');
        Route::post('/skills/custom', [SkillController::class, 'store'])->name('skills.custom');
    });
});

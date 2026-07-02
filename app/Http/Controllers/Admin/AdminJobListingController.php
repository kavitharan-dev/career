<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CareerDomain;
use App\Models\JobListing;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminJobListingController extends Controller
{
    public function index(): View
    {
        $domains = CareerDomain::orderBy('name')->get(['id', 'name']);
        $activeJobs = JobListing::with('careerDomain')
            ->active()
            ->orderBy('expires_at')
            ->orderByDesc('created_at')
            ->get();
        $expiredJobs = JobListing::with('careerDomain')
            ->expired()
            ->orderByDesc('expires_at')
            ->limit(20)
            ->get();

        return view('admin.jobs.index', compact('domains', 'activeJobs', 'expiredJobs'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'career_domain_id' => ['nullable', 'exists:career_domains,id'],
            'title' => ['required', 'string', 'max:200'],
            'company' => ['required', 'string', 'max:200'],
            'location' => ['nullable', 'string', 'max:120'],
            'apply_url' => ['nullable', 'url', 'max:500'],
            'apply_note' => ['nullable', 'string', 'max:500'],
            'expires_at' => ['required', 'date', 'after_or_equal:today'],
        ]);

        JobListing::create([
            ...$validated,
            'career_domain_id' => $validated['career_domain_id'] ?: null,
            'is_active' => true,
            'created_by' => $request->user()->id,
        ]);

        return back()->with('success', 'Job listing published. It will hide automatically after the expiry date.');
    }

    public function edit(JobListing $job): View
    {
        $domains = CareerDomain::orderBy('name')->get(['id', 'name']);

        return view('admin.jobs.edit', compact('job', 'domains'));
    }

    public function update(Request $request, JobListing $job): RedirectResponse
    {
        $validated = $request->validate([
            'career_domain_id' => ['nullable', 'exists:career_domains,id'],
            'title' => ['required', 'string', 'max:200'],
            'company' => ['required', 'string', 'max:200'],
            'location' => ['nullable', 'string', 'max:120'],
            'apply_url' => ['nullable', 'url', 'max:500'],
            'apply_note' => ['nullable', 'string', 'max:500'],
            'expires_at' => ['required', 'date'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $job->update([
            ...$validated,
            'career_domain_id' => $validated['career_domain_id'] ?: null,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('admin.jobs.index')
            ->with('success', 'Job listing updated.');
    }

    public function destroy(JobListing $job): RedirectResponse
    {
        $job->delete();

        return back()->with('success', 'Job listing removed.');
    }
}

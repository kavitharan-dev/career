<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CareerDomain;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminCareerDomainController extends Controller
{
    public function index(): View
    {
        $domains = CareerDomain::withCount('recommendations', 'stepTemplates')
            ->with(['stepTemplates.taskTemplates.subtaskTemplates'])
            ->orderBy('name')
            ->get()
            ->map(function (CareerDomain $domain) {
                $domain->tasks_count = $domain->stepTemplates->sum(fn ($s) => $s->taskTemplates->count());
                $domain->subtasks_count = $domain->stepTemplates->sum(
                    fn ($s) => $s->taskTemplates->sum(fn ($t) => $t->subtaskTemplates->count())
                );

                return $domain;
            });

        return view('admin.domains.index', compact('domains'));
    }

    public function show(CareerDomain $domain): View
    {
        $domain->loadCount('recommendations');
        $domain->load([
            'stepTemplates.taskTemplates.subtaskTemplates',
        ]);

        $stats = [
            'steps' => $domain->stepTemplates->count(),
            'tasks' => $domain->stepTemplates->sum(fn ($s) => $s->taskTemplates->count()),
            'subtasks' => $domain->stepTemplates->sum(
                fn ($s) => $s->taskTemplates->sum(fn ($t) => $t->subtaskTemplates->count())
            ),
        ];

        return view('admin.domains.show', compact('domain', 'stats'));
    }

    public function edit(CareerDomain $domain): View
    {
        return view('admin.domains.edit', compact('domain'));
    }

    public function update(Request $request, CareerDomain $domain): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string'],
        ]);

        $domain->update($validated);

        return redirect()->route('admin.domains.index')->with('success', 'Career domain updated.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:80'],
            'proficiency' => ['nullable', 'integer', 'min:1', 'max:5'],
        ]);

        $user = $request->user();

        $skill = Skill::create([
            'name' => $validated['name'],
            'slug' => Skill::slugFromName($validated['name'], $user->id),
            'user_id' => $user->id,
        ]);

        $user->skills()->syncWithoutDetaching([
            $skill->id => ['proficiency' => $validated['proficiency'] ?? 3],
        ]);

        return redirect()->route('dashboard')->with('success', 'Custom skill added.');
    }
}

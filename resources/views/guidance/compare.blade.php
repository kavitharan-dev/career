@extends('layouts.app')
@section('title', 'Compare Careers')

@section('page')
<div class="mb-6">
    <a href="{{ route('guidance.index') }}" class="text-sm font-medium text-violet-600">← Career Guidance</a>
    <h1 class="mt-2 text-2xl font-bold">Compare careers</h1>
    <p class="mt-1 text-slate-600">Pick 2 or 3 careers and compare fit % and skill alignment.</p>
</div>

<form method="POST" action="{{ route('guidance.compare') }}" class="rounded-2xl border border-slate-200 bg-white p-6">
    @csrf
    <h3 class="font-semibold">Select careers to compare</h3>
    <div class="mt-4 grid gap-2 sm:grid-cols-2">
        @foreach ($domains as $domain)
            <label class="flex items-center gap-2 rounded-lg border border-slate-100 px-3 py-2 text-sm">
                <input type="checkbox" name="career_ids[]" value="{{ $domain->id }}" class="rounded text-violet-600"
                    @checked($selected->contains('id', $domain->id))>
                {{ $domain->name }}
            </label>
        @endforeach
    </div>
    @error('career_ids')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
    <button type="submit" class="mt-4 rounded-full bg-violet-600 px-6 py-2.5 text-sm font-semibold text-white">Compare</button>
</form>

@if ($comparison->isNotEmpty())
    <div class="mt-8 overflow-x-auto rounded-2xl border border-slate-200 bg-white">
        <table class="w-full min-w-[600px] text-left text-sm">
            <thead class="border-b bg-slate-50 text-slate-600">
                <tr>
                    <th class="px-4 py-3">Career</th>
                    <th class="px-4 py-3">Fit %</th>
                    <th class="px-4 py-3">Skills matched</th>
                    <th class="px-4 py-3">Skill gaps</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($comparison as $row)
                    <tr class="border-b border-slate-100">
                        <td class="px-4 py-3 font-medium">{{ $row['domain']->name }}</td>
                        <td class="px-4 py-3 font-bold text-violet-600">{{ $row['score'] ?? '—' }}@if($row['score'])%@endif</td>
                        <td class="px-4 py-3">{{ $row['matched_count'] }}</td>
                        <td class="px-4 py-3">{{ $row['gaps_count'] }}</td>
                        <td class="px-4 py-3"><a href="{{ route('guidance.show', $row['domain']) }}" class="text-violet-600 font-semibold">Details</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6 grid gap-4 md:grid-cols-2 lg:grid-cols-3">
        @foreach ($comparison as $row)
            <div class="rounded-2xl border border-slate-200 p-4">
                <h4 class="font-semibold">{{ $row['domain']->name }}</h4>
                <p class="mt-2 text-xs text-slate-500">{{ $row['domain']->salary_range }}</p>
                <p class="mt-2 text-sm text-slate-600">{{ Str::limit($row['domain']->job_outlook, 100) }}</p>
            </div>
        @endforeach
    </div>
@endif
@endsection

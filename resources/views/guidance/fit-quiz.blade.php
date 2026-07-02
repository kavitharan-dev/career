@extends('layouts.app')
@section('title', 'Career Fit Quiz — ' . $career->name)

@section('page')
<div class="mb-6">
    <a href="{{ route('guidance.show', $career) }}" class="text-sm font-medium text-violet-600">← {{ $career->name }}</a>
    <h1 class="mt-2 text-2xl font-bold">Career fit questionnaire</h1>
    <p class="mt-1 text-slate-600">Six questions written only for <strong>{{ $career->name }}</strong>. Your answers are scored as a real percentage — not the same test as other careers.</p>
</div>

@if ($existingQuiz)
    <div class="mb-6 rounded-xl border border-teal-200 bg-teal-50 px-4 py-3 text-sm text-teal-900">
        Last result: <strong>{{ $existingQuiz->fit_score }}%</strong> — {{ $existingQuiz->verdictLabel() }}.
        Submit again to update your match scores.
    </div>
@endif

<form method="POST" action="{{ route('guidance.fit-quiz.store', $career) }}" class="space-y-5">
    @csrf
    @foreach ($questions as $question)
        <fieldset class="rounded-2xl border border-slate-200 bg-white p-5">
            <legend class="font-medium">{{ $loop->iteration }}. {{ $question->question_text }}</legend>
            <div class="mt-3 space-y-2">
                @foreach ($question->options as $key => $opt)
                    <label class="flex items-start gap-2 text-sm">
                        <input type="radio" name="{{ $question->question_key }}" value="{{ $key }}" required class="mt-1 text-violet-600"
                            @checked(old($question->question_key) === $key)>
                        <span><span class="font-semibold uppercase">{{ $key }}</span>. {{ $opt['label'] }}</span>
                    </label>
                @endforeach
            </div>
            @error($question->question_key)<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
        </fieldset>
    @endforeach
    <button type="submit" class="rounded-full bg-violet-600 px-6 py-2.5 text-sm font-semibold text-white">Calculate my fit for {{ $career->name }}</button>
</form>
@endsection

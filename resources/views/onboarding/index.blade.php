@extends('layouts.arivexa')
@section('title', 'Setup Profile')
@section('content')
<section class="center-page">
    <div style="text-align: center; margin-bottom: 20px;">
        <p class="text-muted">Step {{ $step }} of 4</p>
        <h1 class="page-title">Student profile setup</h1>
        <div class="step-bar">
            @for ($i = 1; $i <= 4; $i++)
                <span class="{{ $i <= $step ? 'done' : '' }}"></span>
            @endfor
        </div>
        <p style="margin-top: 12px;">
            <a href="{{ route('chatbot') }}" class="btn btn-blue">Open Learning Assistant</a>
        </p>
    </div>

    @if (session('error'))
        <div class="msg msg-warning">{{ session('error') }}</div>
    @endif
    @if (session('success'))
        <div class="msg msg-success">{{ session('success') }}</div>
    @endif

    <div class="box">
        @if ($step === 1)
            <form method="POST" action="{{ route('onboarding.profile') }}">
                @csrf
                <h2 class="section-title">Academic info and interests</h2>
                <div class="form-group">
                    <label class="form-label" for="education_level">Education level</label>
                    <select name="education_level" id="education_level" required class="form-control">
                        <option value="">Select level</option>
                        <option value="school" @selected(old('education_level', $profile->education_level) === 'school')>School</option>
                        <option value="college" @selected(old('education_level', $profile->education_level) === 'college')>College</option>
                        <option value="graduate" @selected(old('education_level', $profile->education_level) === 'graduate')>Graduate</option>
                        <option value="other" @selected(old('education_level', $profile->education_level) === 'other')>Other</option>
                    </select>
                    @error('education_level')<p class="form-error">{{ $message }}</p>@enderror
                </div>
                <div class="row col-2">
                    @foreach (['math' => 'Mathematics', 'science' => 'Science', 'english' => 'English', 'computer_science' => 'Computer Science'] as $key => $label)
                        <div class="form-group">
                            <label class="form-label" for="{{ $key }}">{{ $label }} % (optional)</label>
                            <input type="number" name="{{ $key }}" id="{{ $key }}" min="0" max="100" value="{{ old($key, $profile->academic_marks[$key] ?? '') }}" class="form-control">
                        </div>
                    @endforeach
                </div>
                <div class="form-group">
                    <label class="form-label">Interests (pick at least one)</label>
                    @foreach (['Programming', 'Data', 'Design', 'Business', 'Healthcare', 'Teaching', 'Research', 'Entrepreneurship'] as $interest)
                        <label style="display: inline-block; margin: 4px 8px 4px 0;">
                            <input type="checkbox" name="interests[]" value="{{ $interest }}" @checked(in_array($interest, old('interests', $profile->interests ?? [])))>
                            {{ $interest }}
                        </label>
                    @endforeach
                    @error('interests')<p class="form-error">{{ $message }}</p>@enderror
                </div>
                <button type="submit" class="btn btn-blue">Continue to step 2</button>
            </form>
        @elseif ($step === 4)
            @error('career_fit')<div class="msg msg-error">{{ $message }}</div>@enderror
            @if ($targetCareer && $fitQuestions->isNotEmpty())
                <form method="POST" action="{{ route('onboarding.career-fit') }}">
                    @csrf
                    <h2 class="section-title">Career fit quiz — {{ $targetCareer->name }}</h2>
                    <p class="text-muted">Six questions only for this career. Other careers have different quizzes later.</p>
                    @foreach ($fitQuestions as $question)
                        <fieldset class="box" style="margin-top: 12px;">
                            <legend><strong>{{ $loop->iteration }}.</strong> {{ $question->question_text }}</legend>
                            @foreach ($question->options as $key => $opt)
                                <label style="display: block; margin-top: 8px;">
                                    <input type="radio" name="{{ $question->question_key }}" value="{{ $key }}" required @checked(old($question->question_key) === $key)>
                                    {{ strtoupper($key) }}. {{ $opt['label'] }}
                                </label>
                            @endforeach
                            @error($question->question_key)<p class="form-error">{{ $message }}</p>@enderror
                        </fieldset>
                    @endforeach
                    <p style="margin-top: 16px;">
                        <a href="{{ route('onboarding.index', ['step' => 3]) }}" class="btn btn-outline">Back</a>
                        <button type="submit" class="btn btn-blue">Finish and get my career path</button>
                    </p>
                </form>
            @else
                <p class="form-error">Run: php artisan db:seed --class=CareerGuidanceSeeder</p>
            @endif
        @elseif ($step === 2)
            <form method="POST" action="{{ route('onboarding.skills') }}">
                @csrf
                <h2 class="section-title">Skills (optional)</h2>
                <p class="text-muted">Select what you know, or skip to the aptitude test.</p>
                @foreach ($predefinedSkills as $skill)
                    <div class="box" style="padding: 12px;">
                        <label>
                            <input type="checkbox" name="skill_ids[]" value="{{ $skill->id }}" @checked(in_array($skill->id, old('skill_ids', $selectedSkillIds)))>
                            <strong>{{ $skill->name }}</strong>
                        </label>
                        <select name="proficiency[{{ $skill->id }}]" class="form-control" style="margin-top: 8px; max-width: 200px;">
                            @for ($p = 1; $p <= 5; $p++)
                                <option value="{{ $p }}">Level {{ $p }}</option>
                            @endfor
                        </select>
                    </div>
                @endforeach
                <div class="form-group">
                    <label class="form-label" for="custom_skill">Custom skill (optional)</label>
                    <input type="text" name="custom_skill" id="custom_skill" class="form-control" placeholder="e.g. Video editing">
                </div>
                <a href="{{ route('onboarding.index', ['step' => 1]) }}" class="btn btn-outline">Back</a>
                <button type="submit" class="btn btn-blue">Step 3</button>
                <a href="{{ route('onboarding.index', ['step' => 3]) }}" class="btn btn-outline">Skip skills</a>
            </form>
        @else
            @php $questions = \App\Http\Controllers\OnboardingController::skillTestQuestions(); @endphp
            @error('skill_test')<div class="msg msg-error">{{ $message }}</div>@enderror
            <form method="POST" action="{{ route('onboarding.skill-test') }}">
                @csrf
                <h2 class="section-title">General aptitude test</h2>
                <p class="text-muted">5 questions — logical thinking and problem solving.</p>
                @foreach ($questions as $key => $q)
                    <fieldset class="box" style="margin-top: 12px;">
                        <legend><strong>{{ $loop->iteration }}.</strong> {{ $q['question'] }}</legend>
                        @foreach ($q['options'] as $optKey => $optLabel)
                            <label style="display: block; margin-top: 8px;">
                                <input type="radio" name="{{ $key }}" value="{{ $optKey }}" required @checked(old($key) === $optKey)>
                                {{ strtoupper($optKey) }}. {{ $optLabel }}
                            </label>
                        @endforeach
                        @error($key)<p class="form-error">{{ $message }}</p>@enderror
                    </fieldset>
                @endforeach
                <a href="{{ route('onboarding.index', ['step' => 2]) }}" class="btn btn-outline">Back</a>
                <button type="submit" class="btn btn-blue">Continue to career fit quiz</button>
            </form>
        @endif
    </div>
</section>
@endsection

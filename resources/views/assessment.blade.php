@extends('layouts.arivexa')

@section('title', 'Career Assessment')

@section('content')
    <section class="py-10 sm:py-16">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
            <div class="anim-up text-center">
                <p class="text-sm font-semibold uppercase tracking-wider text-indigo-600">Career assessment</p>
                <h1 class="mt-2 text-3xl font-bold tracking-tight sm:text-4xl">Discover your career profile</h1>
                <p class="mt-3 text-slate-600">Answer a few questions—there are no wrong choices, only insights.</p>
            </div>

            <div data-assessment class="mt-10 rounded-3xl border border-slate-200 bg-white p-6 shadow-xl shadow-slate-200/50 sm:p-10">
                <div class="mb-8">
                    <div class="mb-2 flex items-center justify-between text-sm">
                        <span data-progress-label class="font-medium text-slate-600">Step 1 of 4</span>
                        <span class="text-slate-400">~3 min left</span>
                    </div>
                    <div class="h-2 overflow-hidden rounded-full bg-slate-100">
                        <div
                            data-progress-bar
                            class="h-full rounded-full bg-linear-to-r from-indigo-600 to-teal-500 transition-all duration-500 ease-out"
                            style="width: 25%"
                        ></div>
                    </div>
                </div>

                <div class="relative min-h-[320px]">
                    {{-- Step 1 --}}
                    <div data-step class="quiz-panel is-active">
                        <h2 class="text-xl font-semibold sm:text-2xl">What kind of work energizes you most?</h2>
                        <p class="mt-2 text-sm text-slate-500">Pick the option that resonates strongest right now.</p>
                        <div class="mt-6 grid gap-3 sm:grid-cols-2">
                            <button type="button" class="opt-card" data-track="analytical">
                                <span class="relative font-semibold">Solving complex problems</span>
                                <span class="relative mt-1 block text-sm text-slate-500">Data, logic, research</span>
                            </button>
                            <button type="button" class="opt-card" data-track="creative">
                                <span class="relative font-semibold">Creating something new</span>
                                <span class="relative mt-1 block text-sm text-slate-500">Design, media, ideas</span>
                            </button>
                            <button type="button" class="opt-card" data-track="people">
                                <span class="relative font-semibold">Helping people grow</span>
                                <span class="relative mt-1 block text-sm text-slate-500">Teaching, coaching, care</span>
                            </button>
                            <button type="button" class="opt-card" data-track="builder">
                                <span class="relative font-semibold">Building tangible results</span>
                                <span class="relative mt-1 block text-sm text-slate-500">Products, operations, craft</span>
                            </button>
                        </div>
                    </div>

                    {{-- Step 2 --}}
                    <div data-step class="quiz-panel hidden">
                        <h2 class="text-xl font-semibold sm:text-2xl">In a team project, you naturally…</h2>
                        <p class="mt-2 text-sm text-slate-500">Think about your last group experience.</p>
                        <div class="mt-6 grid gap-3 sm:grid-cols-2">
                            <button type="button" class="opt-card" data-track="analytical">
                                <span class="relative font-semibold">Analyze the plan</span>
                                <span class="relative mt-1 block text-sm text-slate-500">Metrics, risks, structure</span>
                            </button>
                            <button type="button" class="opt-card" data-track="creative">
                                <span class="relative font-semibold">Shape the vision</span>
                                <span class="relative mt-1 block text-sm text-slate-500">Story, design, pitch</span>
                            </button>
                            <button type="button" class="opt-card" data-track="people">
                                <span class="relative font-semibold">Keep everyone aligned</span>
                                <span class="relative mt-1 block text-sm text-slate-500">Communication, morale</span>
                            </button>
                            <button type="button" class="opt-card" data-track="builder">
                                <span class="relative font-semibold">Ship the deliverable</span>
                                <span class="relative mt-1 block text-sm text-slate-500">Execution, deadlines</span>
                            </button>
                        </div>
                    </div>

                    {{-- Step 3 --}}
                    <div data-step class="quiz-panel hidden">
                        <h2 class="text-xl font-semibold sm:text-2xl">Which environment sounds ideal?</h2>
                        <p class="mt-2 text-sm text-slate-500">Where you’d thrive day to day.</p>
                        <div class="mt-6 grid gap-3 sm:grid-cols-2">
                            <button type="button" class="opt-card" data-track="analytical">
                                <span class="relative font-semibold">Quiet focus time</span>
                                <span class="relative mt-1 block text-sm text-slate-500">Lab, library, remote desk</span>
                            </button>
                            <button type="button" class="opt-card" data-track="creative">
                                <span class="relative font-semibold">Studio or creative space</span>
                                <span class="relative mt-1 block text-sm text-slate-500">Flexible, visual, collaborative</span>
                            </button>
                            <button type="button" class="opt-card" data-track="people">
                                <span class="relative font-semibold">Face-to-face interaction</span>
                                <span class="relative mt-1 block text-sm text-slate-500">Classroom, clinic, office</span>
                            </button>
                            <button type="button" class="opt-card" data-track="builder">
                                <span class="relative font-semibold">Hands-on workshop</span>
                                <span class="relative mt-1 block text-sm text-slate-500">Field, site, maker space</span>
                            </button>
                        </div>
                    </div>

                    {{-- Step 4 --}}
                    <div data-step class="quiz-panel hidden">
                        <h2 class="text-xl font-semibold sm:text-2xl">What matters most in your next role?</h2>
                        <p class="mt-2 text-sm text-slate-500">Your top priority for the next 2–3 years.</p>
                        <div class="mt-6 grid gap-3 sm:grid-cols-2">
                            <button type="button" class="opt-card" data-track="analytical">
                                <span class="relative font-semibold">Expertise & mastery</span>
                                <span class="relative mt-1 block text-sm text-slate-500">Deep technical skill</span>
                            </button>
                            <button type="button" class="opt-card" data-track="creative">
                                <span class="relative font-semibold">Creative freedom</span>
                                <span class="relative mt-1 block text-sm text-slate-500">Expression & innovation</span>
                            </button>
                            <button type="button" class="opt-card" data-track="people">
                                <span class="relative font-semibold">Meaningful impact</span>
                                <span class="relative mt-1 block text-sm text-slate-500">Lives changed, communities</span>
                            </button>
                            <button type="button" class="opt-card" data-track="builder">
                                <span class="relative font-semibold">Ownership & growth</span>
                                <span class="relative mt-1 block text-sm text-slate-500">Build something lasting</span>
                            </button>
                        </div>
                    </div>

                    {{-- Results --}}
                    <div data-result class="hidden text-center">
                        <div class="mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-full bg-linear-to-br from-indigo-100 to-teal-100">
                            <svg class="h-10 w-10 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <p class="text-sm font-semibold uppercase tracking-wider text-teal-600">Your career profile</p>
                        <h2 data-result-title class="mt-2 text-2xl font-bold sm:text-3xl"></h2>
                        <p data-result-summary class="mx-auto mt-4 max-w-lg text-slate-600"></p>
                        <div class="mt-8 flex flex-wrap justify-center gap-4">
                            <a href="{{ route('assessment') }}" class="rounded-full border border-slate-300 px-6 py-2.5 text-sm font-semibold text-slate-700 transition hover:border-indigo-300 hover:text-indigo-700">
                                Retake assessment
                            </a>
                            <a href="{{ route('home') }}" class="rounded-full bg-indigo-600 px-6 py-2.5 text-sm font-semibold text-white shadow-lg shadow-indigo-500/25 transition hover:bg-indigo-700">
                                Back to home
                            </a>
                        </div>
                    </div>
                </div>

                <div data-assessment-controls class="mt-8 flex items-center justify-between border-t border-slate-100 pt-6">
                    <button
                        type="button"
                        data-back
                        disabled
                        class="rounded-full px-5 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-100 disabled:cursor-not-allowed"
                    >
                        Back
                    </button>
                    <button
                        type="button"
                        data-next
                        disabled
                        class="rounded-full bg-indigo-600 px-7 py-2.5 text-sm font-semibold text-white shadow-lg shadow-indigo-500/25 transition hover:bg-indigo-700 disabled:cursor-not-allowed"
                    >
                        Continue
                    </button>
                </div>
            </div>
        </div>
    </section>
@endsection

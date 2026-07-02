@extends('layouts.arivexa')

@section('title', 'Home')

@section('content')
<div class="home-page">

    {{-- Hero: gradient + text left, graphic card right --}}
    <section class="home-hero">
        <div class="home-hero-glow home-hero-glow-1"></div>
        <div class="home-hero-glow home-hero-glow-2"></div>

        <div class="home-hero-inner">
            <div class="home-hero-text">
                <p class="home-badge home-anim-1">
                    <span class="home-badge-dot"></span>
                    Career guidance &amp; learning — Sri Lanka
                </p>

                <h1 class="home-title home-anim-2">
                    Your career path,
                    <span class="home-title-highlight">personalized for you</span>
                </h1>

                <p class="home-lead home-anim-3">
                    Take real fit quizzes, explore 9 careers with local job ideas, then follow a daily learning roadmap — all in one place.
                </p>

                <div class="home-buttons home-anim-4">
                    <a href="{{ route('register') }}" class="home-btn-primary">Register free →</a>
                    <a href="#how-it-works" class="home-btn-secondary">See how it works</a>
                </div>

                <div class="home-trust-row home-anim-4">
                    <span class="home-trust-pill">✓ Real match %</span>
                    <span class="home-trust-pill">✓ Sri Lanka jobs</span>
                    <span class="home-trust-pill">✓ Daily roadmap</span>
                </div>

                <div class="home-stats home-anim-4">
                    <div class="home-stats-grid">
                        <div class="home-stat">
                            <strong class="home-stat-value">4</strong>
                            <span class="home-stat-label">Steps onboarding</span>
                        </div>
                        <div class="home-stat">
                            <strong class="home-stat-value">9</strong>
                            <span class="home-stat-label">Career paths</span>
                        </div>
                        <div class="home-stat">
                            <strong class="home-stat-value">100%</strong>
                            <span class="home-stat-label">Free to start</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="home-hero-visual home-anim-4">
                <div class="home-showcase">
                    <svg viewBox="0 0 360 220" aria-hidden="true">
                        <defs>
                            <linearGradient id="pathLine" x1="0%" y1="0%" x2="100%" y2="0%">
                                <stop offset="0%" stop-color="#2563eb"/>
                                <stop offset="100%" stop-color="#059669"/>
                            </linearGradient>
                        </defs>
                        <path d="M20 180 C70 140, 110 200, 160 150 S250 80, 320 100 S350 40, 340 30" fill="none" stroke="url(#pathLine)" stroke-width="4" stroke-linecap="round"/>
                        <circle cx="20" cy="180" r="10" fill="#fff" stroke="#2563eb" stroke-width="3"/>
                        <circle cx="160" cy="150" r="10" fill="#fff" stroke="#2563eb" stroke-width="3"/>
                        <circle cx="320" cy="100" r="10" fill="#fff" stroke="#2563eb" stroke-width="3"/>
                        <circle cx="340" cy="30" r="10" fill="#fff" stroke="#059669" stroke-width="3"/>
                        <text x="20" y="205" text-anchor="middle" fill="#64748b" font-size="11" font-family="Segoe UI, sans-serif">Start</text>
                        <text x="160" y="175" text-anchor="middle" fill="#64748b" font-size="11" font-family="Segoe UI, sans-serif">Learn</text>
                        <text x="320" y="125" text-anchor="middle" fill="#64748b" font-size="11" font-family="Segoe UI, sans-serif">Match</text>
                        <text x="340" y="55" text-anchor="middle" fill="#64748b" font-size="11" font-family="Segoe UI, sans-serif">Grow</text>
                    </svg>

                    <div class="home-float-card home-float-top">
                        <span style="color:#64748b;">Career fit</span>
                        <strong>87%</strong>
                    </div>
                    <div class="home-float-card home-float-bottom">
                        <span style="color:#64748b;">Top match</span>
                        <strong>Software Engineer</strong>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Features --}}
    <section id="features" class="home-features">
        <div class="home-features-inner">
            <div class="home-section-head">
                <p class="home-section-label">Why Arivexa</p>
                <h2>Everything you need to choose wisely</h2>
                <p>Career guidance first, then structured learning — built for students in Sri Lanka.</p>
            </div>

            <div class="home-feature-grid">
                <article class="home-feature-card">
                    <div class="home-feature-icon">📊</div>
                    <h3>Real match scores</h3>
                    <p>Profile, aptitude test, and a separate fit quiz for each career — no fake 55% for everyone.</p>
                </article>
                <article class="home-feature-card">
                    <div class="home-feature-icon">🇱🇰</div>
                    <h3>Sri Lanka jobs</h3>
                    <p>Salary ranges, employers, locations, and how to apply on every career page.</p>
                </article>
                <article class="home-feature-card">
                    <div class="home-feature-icon">📚</div>
                    <h3>Daily roadmap</h3>
                    <p>Lessons with subtasks; your progress is saved when you complete each step.</p>
                </article>
                <article class="home-feature-card">
                    <div class="home-feature-icon">🎯</div>
                    <h3>Skill gap report</h3>
                    <p>See what you already have and what to learn before you apply for jobs.</p>
                </article>
                <article class="home-feature-card">
                    <div class="home-feature-icon">⚖️</div>
                    <h3>Compare careers</h3>
                    <p>Put two or three paths side by side and pick what fits you best.</p>
                </article>
                <article class="home-feature-card">
                    <div class="home-feature-icon">🤖</div>
                    <h3>Learning assistant</h3>
                    <p>Chat help while you follow your roadmap — stay on track every day.</p>
                </article>
            </div>
        </div>
    </section>

    {{-- Steps --}}
    <section id="how-it-works" class="home-steps">
        <div class="home-steps-inner">
            <div class="home-section-head">
                <h2>Four steps to your career plan</h2>
                <p>Simple flow from sign-up to your personalized report and roadmap.</p>
            </div>

            <div class="home-step-grid">
                <div class="home-step-card">
                    <div class="home-step-num">01</div>
                    <h3>Profile &amp; skills</h3>
                    <p>Education, interests, and optional skills — skip what you do not have yet.</p>
                </div>
                <div class="home-step-card">
                    <div class="home-step-num">02</div>
                    <h3>Aptitude test</h3>
                    <p>5 questions on logical thinking and problem solving.</p>
                </div>
                <div class="home-step-card">
                    <div class="home-step-num">03</div>
                    <h3>Career fit quiz</h3>
                    <p>6 questions unique to your top career — other jobs have their own quiz later.</p>
                </div>
                <div class="home-step-card">
                    <div class="home-step-num">04</div>
                    <h3>Guidance + learn</h3>
                    <p>Assessment report, explore careers, then daily lessons on your roadmap.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="home-cta">
        <div class="home-cta-box">
            <h2>Ready to find your path?</h2>
            <p>Join Arivexa — career guidance and learning made for Sri Lankan students.</p>
            <a href="{{ route('register') }}" class="home-cta-btn">Start your journey →</a>
        </div>
    </section>

</div>
@endsection

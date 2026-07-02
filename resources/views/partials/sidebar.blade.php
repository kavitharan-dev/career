@php
    $user = auth()->user();
    $unread = $user->appNotifications()->whereNull('read_at')->count();
    $initial = strtoupper(substr($user->name, 0, 1));
    $career = $user->primaryRecommendation?->careerDomain?->name;
@endphp

<aside class="dash-sidebar" id="dash-sidebar" aria-label="Student navigation">
    <div class="dash-sidebar-accent" aria-hidden="true"></div>

    <div class="dash-sidebar-top">
        <a href="{{ route('dashboard') }}" class="dash-sidebar-brand">
            <span class="dash-sidebar-logo" aria-hidden="true">
                @include('partials.dash-icon', ['name' => 'logo'])
            </span>
            <span>
                <strong>Arivexa</strong>
                <small>Student workspace</small>
            </span>
        </a>
        <button type="button" class="dash-sidebar-close" data-dash-close aria-label="Close menu">
            @include('partials.dash-icon', ['name' => 'close', 'class' => 'dash-ico dash-ico-sm'])
        </button>
    </div>

    <div class="dash-user-card">
        <span class="dash-user-avatar">{{ $initial }}</span>
        <div class="dash-user-meta">
            <strong>{{ $user->name }}</strong>
            <span>{{ $career ?? 'Complete onboarding' }}</span>
        </div>
    </div>

    <nav class="dash-nav">
        <p class="dash-nav-label">Home</p>
        <a href="{{ route('dashboard') }}" class="dash-nav-link {{ request()->routeIs('dashboard') ? 'is-active' : '' }}">
            <span class="dash-nav-icon-wrap dash-nav-icon-indigo">@include('partials.dash-icon', ['name' => 'dashboard'])</span>
            <span class="dash-nav-text">Dashboard</span>
        </a>
        <a href="{{ route('roadmap') }}" class="dash-nav-link {{ request()->routeIs('roadmap') ? 'is-active' : '' }}">
            <span class="dash-nav-icon-wrap dash-nav-icon-teal">@include('partials.dash-icon', ['name' => 'roadmap'])</span>
            <span class="dash-nav-text">Learning roadmap</span>
        </a>
        <a href="{{ route('chatbot') }}" class="dash-nav-link {{ request()->routeIs('chatbot*') ? 'is-active' : '' }}">
            <span class="dash-nav-icon-wrap dash-nav-icon-blue">@include('partials.dash-icon', ['name' => 'tutor'])</span>
            <span class="dash-nav-text">AI tutor</span>
        </a>
        <a href="{{ route('notifications') }}" class="dash-nav-link {{ request()->routeIs('notifications*') ? 'is-active' : '' }}">
            <span class="dash-nav-icon-wrap dash-nav-icon-rose">@include('partials.dash-icon', ['name' => 'bell'])</span>
            <span class="dash-nav-text">Notifications</span>
            @if ($unread > 0)<span class="dash-nav-badge">{{ $unread }}</span>@endif
        </a>

        <p class="dash-nav-label">Career</p>
        <a href="{{ route('guidance.index') }}" class="dash-nav-link {{ request()->routeIs('guidance.index') ? 'is-active' : '' }}">
            <span class="dash-nav-icon-wrap dash-nav-icon-violet">@include('partials.dash-icon', ['name' => 'overview'])</span>
            <span class="dash-nav-text">Overview</span>
        </a>
        <a href="{{ route('guidance.assessment') }}" class="dash-nav-link {{ request()->routeIs('guidance.assessment') ? 'is-active' : '' }}">
            <span class="dash-nav-icon-wrap dash-nav-icon-amber">@include('partials.dash-icon', ['name' => 'assessment'])</span>
            <span class="dash-nav-text">Assessment</span>
        </a>
        <a href="{{ route('guidance.explore') }}" class="dash-nav-link {{ request()->routeIs('guidance.explore', 'guidance.show', 'guidance.fit-quiz*', 'guidance.interview') ? 'is-active' : '' }}">
            <span class="dash-nav-icon-wrap dash-nav-icon-indigo">@include('partials.dash-icon', ['name' => 'explore'])</span>
            <span class="dash-nav-text">Explore careers</span>
        </a>
        <a href="{{ route('guidance.jobs') }}" class="dash-nav-link {{ request()->routeIs('guidance.jobs') ? 'is-active' : '' }}">
            <span class="dash-nav-icon-wrap dash-nav-icon-green">@include('partials.dash-icon', ['name' => 'jobs'])</span>
            <span class="dash-nav-text">Sri Lanka jobs</span>
        </a>
        <a href="{{ route('guidance.resources') }}" class="dash-nav-link {{ request()->routeIs('guidance.resources') ? 'is-active' : '' }}">
            <span class="dash-nav-icon-wrap dash-nav-icon-teal">@include('partials.dash-icon', ['name' => 'resources'])</span>
            <span class="dash-nav-text">Resources</span>
        </a>
        <a href="{{ route('guidance.compare') }}" class="dash-nav-link {{ request()->routeIs('guidance.compare') ? 'is-active' : '' }}">
            <span class="dash-nav-icon-wrap dash-nav-icon-violet">@include('partials.dash-icon', ['name' => 'compare'])</span>
            <span class="dash-nav-text">Compare</span>
        </a>
    </nav>

    <div class="dash-sidebar-foot">
        <a href="{{ route('report.pdf') }}" class="dash-foot-btn">
            <span class="dash-nav-icon-wrap dash-nav-icon-slate">@include('partials.dash-icon', ['name' => 'pdf'])</span>
            <span>Export PDF report</span>
        </a>
        <form method="POST" action="{{ route('logout') }}" class="dash-sidebar-logout">
            @csrf
            <button type="submit" class="dash-foot-btn dash-foot-btn-danger">
                <span class="dash-nav-icon-wrap dash-nav-icon-rose">@include('partials.dash-icon', ['name' => 'logout'])</span>
                <span>Logout</span>
            </button>
        </form>
    </div>
</aside>

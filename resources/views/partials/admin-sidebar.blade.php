@php
    $user = auth()->user();
    $initial = strtoupper(substr($user->name, 0, 1));
@endphp

<aside class="adm-sidebar" id="adm-sidebar" aria-label="Admin navigation">
    <div class="adm-sidebar-accent" aria-hidden="true"></div>

    <div class="adm-sidebar-top">
        <a href="{{ route('admin.dashboard') }}" class="adm-sidebar-brand">
            <span class="adm-sidebar-logo" aria-hidden="true">
                @include('partials.admin-icon', ['name' => 'shield'])
            </span>
            <span>
                <strong>Arivexa</strong>
                <small>Admin control</small>
            </span>
        </a>
        <button type="button" class="adm-sidebar-close" data-adm-close aria-label="Close menu">
            @include('partials.admin-icon', ['name' => 'close'])
        </button>
    </div>

    <div class="adm-user-card">
        <span class="adm-user-avatar">{{ $initial }}</span>
        <div class="adm-user-meta">
            <strong>{{ $user->name }}</strong>
            <span>Administrator</span>
        </div>
    </div>

    <nav class="adm-nav">
        <p class="adm-nav-label">Manage</p>
        <a href="{{ route('admin.dashboard') }}" class="adm-nav-link {{ request()->routeIs('admin.dashboard') ? 'is-active' : '' }}">
            <span class="adm-nav-icon-wrap adm-nav-icon-rose">@include('partials.admin-icon', ['name' => 'overview'])</span>
            <span>Overview</span>
        </a>
        <a href="{{ route('admin.users.index') }}" class="adm-nav-link {{ request()->routeIs('admin.users*') ? 'is-active' : '' }}">
            <span class="adm-nav-icon-wrap adm-nav-icon-amber">@include('partials.admin-icon', ['name' => 'students'])</span>
            <span>Students</span>
        </a>
        <a href="{{ route('admin.curriculum.tools') }}" class="adm-nav-link {{ request()->routeIs('admin.curriculum*') || request()->routeIs('admin.roadmaps*') ? 'is-active' : '' }}">
            <span class="adm-nav-icon-wrap adm-nav-icon-coral">@include('partials.admin-icon', ['name' => 'curriculum'])</span>
            <span>Curriculum tools</span>
        </a>
        <a href="{{ route('admin.domains.index') }}" class="adm-nav-link {{ request()->routeIs('admin.domains*') ? 'is-active' : '' }}">
            <span class="adm-nav-icon-wrap adm-nav-icon-violet">@include('partials.admin-icon', ['name' => 'domains'])</span>
            <span>Career domains</span>
        </a>
        <a href="{{ route('admin.jobs.index') }}" class="adm-nav-link {{ request()->routeIs('admin.jobs*') ? 'is-active' : '' }}">
            <span class="adm-nav-icon-wrap adm-nav-icon-emerald">@include('partials.admin-icon', ['name' => 'jobs'])</span>
            <span>Job listings</span>
        </a>

        <p class="adm-nav-label">System</p>
        <a href="{{ route('chatbot') }}" class="adm-nav-link {{ request()->routeIs('chatbot*') ? 'is-active' : '' }}">
            <span class="adm-nav-icon-wrap adm-nav-icon-blue">@include('partials.admin-icon', ['name' => 'tutor'])</span>
            <span>System assistant</span>
        </a>
        <a href="{{ route('home') }}" class="adm-nav-link">
            <span class="adm-nav-icon-wrap adm-nav-icon-slate">@include('partials.admin-icon', ['name' => 'home'])</span>
            <span>Public site</span>
        </a>
    </nav>

    <div class="adm-sidebar-foot">
        <form method="POST" action="{{ route('logout') }}" class="adm-sidebar-logout">
            @csrf
            <button type="submit" class="adm-foot-btn adm-foot-btn-danger">
                <span class="adm-nav-icon-wrap adm-nav-icon-rose">@include('partials.admin-icon', ['name' => 'logout'])</span>
                <span>Logout</span>
            </button>
        </form>
    </div>
</aside>

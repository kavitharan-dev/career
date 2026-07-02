@php
    $user = auth()->user();
    $unread = $user->appNotifications()->whereNull('read_at')->count();
@endphp
<header class="dash-topbar">
    <div class="dash-topbar-accent" aria-hidden="true"></div>

    <div class="dash-topbar-left">
        <button type="button" class="dash-menu-btn" data-dash-toggle aria-expanded="false" aria-label="Open navigation menu">
            @include('partials.dash-icon', ['name' => 'menu', 'class' => 'dash-ico'])
            <span>Menu</span>
        </button>
        <a href="{{ route('dashboard') }}" class="dash-topbar-brand">
            <span class="dash-topbar-logo" aria-hidden="true">
                @include('partials.dash-icon', ['name' => 'logo'])
            </span>
            <span>
                <strong>Arivexa</strong>
                <small>Student workspace</small>
            </span>
        </a>

        <nav class="dash-topbar-quick" aria-label="Quick actions">
            <a href="{{ route('dashboard') }}" class="dash-quick-btn {{ request()->routeIs('dashboard') ? 'is-active' : '' }}" title="Dashboard">
                <span class="dash-quick-icon dash-quick-indigo">@include('partials.dash-icon', ['name' => 'dashboard'])</span>
                <span class="dash-quick-label">Dashboard</span>
            </a>
            <a href="{{ route('guidance.index') }}" class="dash-quick-btn {{ request()->routeIs('guidance.*') ? 'is-active' : '' }}" title="Career guidance">
                <span class="dash-quick-icon dash-quick-violet">@include('partials.dash-icon', ['name' => 'explore'])</span>
                <span class="dash-quick-label">Guidance</span>
            </a>
            <a href="{{ route('roadmap') }}" class="dash-quick-btn {{ request()->routeIs('roadmap') ? 'is-active' : '' }}" title="Roadmap">
                <span class="dash-quick-icon dash-quick-teal">@include('partials.dash-icon', ['name' => 'roadmap'])</span>
                <span class="dash-quick-label">Roadmap</span>
            </a>
            <a href="{{ route('chatbot') }}" class="dash-quick-btn {{ request()->routeIs('chatbot*') ? 'is-active' : '' }}" title="AI tutor">
                <span class="dash-quick-icon dash-quick-blue">@include('partials.dash-icon', ['name' => 'tutor'])</span>
                <span class="dash-quick-label">AI tutor</span>
            </a>
        </nav>
    </div>

    <div class="dash-topbar-right">
        <a href="{{ route('notifications') }}" class="dash-top-chip {{ request()->routeIs('notifications*') ? 'is-active' : '' }}" title="Notifications">
            <span class="dash-top-chip-icon dash-top-chip-alert">@include('partials.dash-icon', ['name' => 'bell'])</span>
            <span class="dash-top-chip-text">Alerts</span>
            @if ($unread > 0)<span class="dash-top-badge">{{ $unread }}</span>@endif
        </a>
        <a href="{{ route('home') }}" class="dash-top-chip" title="Public site">
            <span class="dash-top-chip-icon dash-top-chip-home">@include('partials.dash-icon', ['name' => 'home'])</span>
            <span class="dash-top-chip-text">Home</span>
        </a>
        <form method="POST" action="{{ route('logout') }}" class="dash-topbar-logout">
            @csrf
            <button type="submit" class="dash-top-chip dash-top-chip-logout" title="Logout">
                <span class="dash-top-chip-icon dash-top-chip-out">@include('partials.dash-icon', ['name' => 'logout'])</span>
                <span class="dash-top-chip-text">Logout</span>
            </button>
        </form>
    </div>
</header>

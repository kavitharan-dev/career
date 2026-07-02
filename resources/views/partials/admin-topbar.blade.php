<header class="adm-topbar">
    <div class="adm-topbar-accent" aria-hidden="true"></div>

    <div class="adm-topbar-left">
        <button type="button" class="adm-menu-btn" data-adm-toggle aria-expanded="false" aria-label="Open navigation menu">
            @include('partials.admin-icon', ['name' => 'menu'])
            <span>Menu</span>
        </button>
        <a href="{{ route('admin.dashboard') }}" class="adm-topbar-brand">
            <span class="adm-topbar-logo" aria-hidden="true">@include('partials.admin-icon', ['name' => 'shield'])</span>
            <span>
                <strong>Admin</strong>
                <small>Control center</small>
            </span>
        </a>

        <nav class="adm-topbar-quick" aria-label="Quick admin navigation">
            <a href="{{ route('admin.dashboard') }}" class="adm-quick-btn {{ request()->routeIs('admin.dashboard') ? 'is-active' : '' }}" title="Overview">
                <span class="adm-quick-icon adm-quick-rose">@include('partials.admin-icon', ['name' => 'overview'])</span>
                <span class="adm-quick-label">Overview</span>
            </a>
            <a href="{{ route('admin.users.index') }}" class="adm-quick-btn {{ request()->routeIs('admin.users*') ? 'is-active' : '' }}" title="Students">
                <span class="adm-quick-icon adm-quick-amber">@include('partials.admin-icon', ['name' => 'students'])</span>
                <span class="adm-quick-label">Students</span>
            </a>
            <a href="{{ route('admin.curriculum.tools') }}" class="adm-quick-btn {{ request()->routeIs('admin.curriculum*') || request()->routeIs('admin.roadmaps*') ? 'is-active' : '' }}" title="Curriculum">
                <span class="adm-quick-icon adm-quick-coral">@include('partials.admin-icon', ['name' => 'curriculum'])</span>
                <span class="adm-quick-label">Curriculum</span>
            </a>
            <a href="{{ route('admin.domains.index') }}" class="adm-quick-btn {{ request()->routeIs('admin.domains*') ? 'is-active' : '' }}" title="Domains">
                <span class="adm-quick-icon adm-quick-violet">@include('partials.admin-icon', ['name' => 'domains'])</span>
                <span class="adm-quick-label">Domains</span>
            </a>
        </nav>
    </div>

    <div class="adm-topbar-right">
        <a href="{{ route('chatbot') }}" class="adm-top-chip" title="System assistant">
            <span class="adm-top-chip-icon adm-top-chip-blue">@include('partials.admin-icon', ['name' => 'tutor'])</span>
            <span class="adm-top-chip-text">Assistant</span>
        </a>
        <a href="{{ route('home') }}" class="adm-top-chip" title="Public site">
            <span class="adm-top-chip-icon adm-top-chip-home">@include('partials.admin-icon', ['name' => 'home'])</span>
            <span class="adm-top-chip-text">Site</span>
        </a>
        <form method="POST" action="{{ route('logout') }}" class="adm-topbar-logout">
            @csrf
            <button type="submit" class="adm-top-chip adm-top-chip-logout" title="Logout">
                <span class="adm-top-chip-icon adm-top-chip-out">@include('partials.admin-icon', ['name' => 'logout'])</span>
                <span class="adm-top-chip-text">Logout</span>
            </button>
        </form>
    </div>
</header>

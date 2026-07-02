<header class="site-header" data-navbar>
    <div class="site-header-bar"></div>
    <div class="site-header-inner">
        <a href="{{ route('home') }}" class="site-logo">
            <span class="site-logo-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" width="26" height="26">
                    <path d="M4 18L12 6L20 18" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </span>
            <span class="site-logo-text">
                <span class="site-logo-name">Arivexa</span>
                <span class="site-logo-tagline">Career Guidance &amp; Learning</span>
            </span>
        </a>

        <div class="site-header-actions">
            <nav class="site-nav" aria-label="Main navigation">
                <div class="site-nav-panel site-nav-panel-separated">
                    @auth
                        @if (auth()->user()->is_admin)
                            <a href="{{ route('admin.dashboard') }}" class="site-nav-link site-nav-pill {{ request()->routeIs('admin.*') ? 'is-active' : '' }}">Admin</a>
                            <a href="{{ route('chatbot') }}" class="site-nav-link site-nav-pill site-nav-pill-blue {{ request()->routeIs('chatbot*') ? 'is-active' : '' }}">Assistant</a>
                        @else
                            <a href="{{ route('dashboard') }}" class="site-nav-link site-nav-pill site-nav-pill-blue {{ request()->routeIs('dashboard') ? 'is-active' : '' }}">Dashboard</a>
                            <a href="{{ route('guidance.index') }}" class="site-nav-link site-nav-pill site-nav-pill-purple {{ request()->routeIs('guidance.*') ? 'is-active' : '' }}">Career Guidance</a>
                            <a href="{{ route('roadmap') }}" class="site-nav-link site-nav-pill site-nav-pill-teal {{ request()->routeIs('roadmap') ? 'is-active' : '' }}">Roadmap</a>
                            <a href="{{ route('chatbot') }}" class="site-nav-link site-nav-pill site-nav-pill-blue {{ request()->routeIs('chatbot*') ? 'is-active' : '' }}">Assistant</a>
                        @endif
                    @else
                        <a href="{{ route('home') }}#features" class="site-nav-link site-nav-pill site-nav-pill-blue">Features</a>
                        <a href="{{ route('home') }}#how-it-works" class="site-nav-link site-nav-pill site-nav-pill-teal">How it works</a>
                        <a href="{{ route('login') }}" class="site-nav-link site-nav-pill site-nav-pill-login {{ request()->routeIs('login') ? 'is-active' : '' }}">Login</a>
                    @endauth
                </div>

                <div class="site-nav-buttons">
                    @auth
                        <form method="POST" action="{{ route('logout') }}" class="site-nav-form">
                            @csrf
                            <button type="submit" class="site-nav-logout">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('register') }}" class="site-nav-cta">
                            Get started
                            <svg width="18" height="18" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M3 10a.75.75 0 01.75-.75h10.638L10.23 5.29a.75.75 0 111.06-1.06l5.5 5.5a.75.75 0 010 1.06l-5.5 5.5a.75.75 0 11-1.06-1.06l4.158-3.96H3.75A.75.75 0 013 10z" clip-rule="evenodd"/>
                            </svg>
                        </a>
                    @endauth
                </div>
            </nav>

            <button type="button" class="menu-btn" data-nav-toggle aria-expanded="false" aria-label="Open menu">
                <span class="menu-btn-label">Menu</span>
                <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>
    </div>

    <div class="mobile-nav" data-nav-menu>
        <p class="mobile-nav-title">Menu</p>
        @auth
            @if (auth()->user()->is_admin)
                <a href="{{ route('admin.dashboard') }}">Admin panel</a>
            @else
                <a href="{{ route('dashboard') }}">Dashboard</a>
                <a href="{{ route('guidance.index') }}">Career Guidance</a>
                <a href="{{ route('roadmap') }}">Learning Roadmap</a>
                <a href="{{ route('chatbot') }}">Learning Assistant</a>
            @endif
            <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="mobile-nav-logout">Logout</button></form>
        @else
            <a href="{{ route('home') }}#features" class="mobile-nav-pill mobile-nav-pill-blue">Features</a>
            <a href="{{ route('home') }}#how-it-works" class="mobile-nav-pill mobile-nav-pill-teal">How it works</a>
            <a href="{{ route('login') }}" class="mobile-nav-pill mobile-nav-pill-login">Login</a>
            <a href="{{ route('register') }}" class="mobile-nav-cta">Get started free</a>
        @endauth
    </div>
</header>

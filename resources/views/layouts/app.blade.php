@extends('layouts.workspace')

@section('content')
<div class="app-shell">
    <div class="app-shell-mesh" aria-hidden="true"></div>
    <div class="app-shell-orb app-shell-orb-1" aria-hidden="true"></div>
    <div class="app-shell-orb app-shell-orb-2" aria-hidden="true"></div>

    @include('partials.sidebar')

    <div class="dash-overlay" data-dash-overlay></div>

    <div class="dash-col">
        @include('partials.dash-topbar')

        <main class="dash-main">
            <div class="dash-main-inner">
                @include('partials.flash-messages')
                @yield('page')
            </div>
        </main>
    </div>
</div>
@endsection

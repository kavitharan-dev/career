@php
    $title = $title ?? 'Page';
    $subtitle = $subtitle ?? null;
@endphp
<header class="dash-page-head">
    <div class="dash-page-head-text">
        <p class="dash-page-kicker">Arivexa workspace</p>
        <h1 class="dash-page-title">{{ $title }}</h1>
        @if ($subtitle)
            <p class="dash-page-sub">{{ $subtitle }}</p>
        @endif
    </div>
    <div class="dash-page-head-deco" aria-hidden="true">
        <span></span><span></span><span></span>
    </div>
</header>

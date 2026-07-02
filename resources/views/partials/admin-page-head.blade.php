@php
    $title = $title ?? 'Admin';
    $subtitle = $subtitle ?? null;
@endphp
<header class="adm-page-head">
    <div class="adm-page-head-text">
        <p class="adm-page-kicker">Arivexa control center</p>
        <h1 class="adm-page-title">{{ $title }}</h1>
        @if ($subtitle)
            <p class="adm-page-sub">{{ $subtitle }}</p>
        @endif
    </div>
    <div class="adm-page-head-deco" aria-hidden="true">
        <span></span><span></span><span></span>
    </div>
</header>

@extends('layouts.admin-workspace')

@section('content')
<div class="adm-shell">
    <div class="adm-shell-mesh" aria-hidden="true"></div>
    <div class="adm-shell-orb adm-shell-orb-1" aria-hidden="true"></div>
    <div class="adm-shell-orb adm-shell-orb-2" aria-hidden="true"></div>

    @include('partials.admin-sidebar')

    <div class="adm-overlay" data-adm-overlay></div>

    <div class="adm-col">
        @include('partials.admin-topbar')

        <main class="adm-main">
            <div class="adm-main-inner">
                @if (session('success'))
                    <div class="adm-flash adm-flash-success">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="adm-flash adm-flash-error">{{ session('error') }}</div>
                @endif

                @include('partials.admin-page-head', [
                    'title' => trim($__env->yieldContent('admin_title')) ?: 'Management',
                    'subtitle' => trim($__env->yieldContent('admin_subtitle')) ?: null,
                ])

                @yield('admin')
            </div>
        </main>
    </div>
</div>
@endsection

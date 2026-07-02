<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="@yield('meta_description', 'Arivexa Admin')">

    <title>@yield('title', 'Admin — '.config('app.name'))</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800" rel="stylesheet">

    @php($viteReady = file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))

    @if ($viteReady)
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <style>
            body { font-family: Arial, sans-serif; margin: 40px; background: #f0f4f8; }
            .box { background: #fff; padding: 20px; border: 1px solid #ddd; max-width: 400px; }
        </style>
    @endif
</head>
<body class="site-body site-body-workspace site-body-admin">
    @if ($viteReady)
        @yield('content')
    @else
        <div class="box" style="margin: 40px auto;">
            <h1>Build frontend first</h1>
            <pre>npm install && npm run build</pre>
        </div>
    @endif

    @stack('scripts')
</body>
</html>

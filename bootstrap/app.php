<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'onboarding.complete' => \App\Http\Middleware\EnsureOnboardingComplete::class,
            'admin' => \App\Http\Middleware\EnsureUserIsAdmin::class,
            'no.cache' => \App\Http\Middleware\PreventBrowserCache::class,
        ]);

        $middleware->redirectGuestsTo(fn () => route('login'));

        $middleware->redirectUsersTo(function () {
            $user = auth()->user();

            if ($user?->is_admin) {
                return route('admin.dashboard');
            }

            if ($user && ! $user->hasCompletedOnboarding()) {
                return route('onboarding.index');
            }

            return route('dashboard');
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (TokenMismatchException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Session expired. Please refresh and try again.'], 419);
            }

            return redirect()->route('login')
                ->with('error', 'Your session expired. Please log in again.');
        });
    })->create();

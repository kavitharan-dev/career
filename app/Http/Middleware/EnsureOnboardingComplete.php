<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureOnboardingComplete
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user?->is_admin) {
            return $next($request);
        }

        if ($user && ! $user->hasCompletedOnboarding()) {
            if (! $request->routeIs('onboarding.*')) {
                return redirect()->route('onboarding.index');
            }
        }

        return $next($request);
    }
}

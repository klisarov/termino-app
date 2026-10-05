<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Guards every /dashboard/* route. Being logged in is not enough - the
 * account must be a tenant owner AND actually have a tenant record.
 */
class EnsureUserIsTenant
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || ! $user->isTenant() || ! $user->tenant) {
            abort(403, 'This area is only available to business accounts.');
        }

        return $next($request);
    }
}

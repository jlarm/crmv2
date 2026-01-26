<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class EnsureOrganizationSelected
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return $next($request);
        }

        $currentOrganizationId = session('current_organization_id');

        if ($currentOrganizationId && $user->belongsToOrganization($currentOrganizationId)) {
            return $next($request);
        }

        $firstOrganization = $user->organizations()->first();

        if ($firstOrganization) {
            session(['current_organization_id' => $firstOrganization->id]);
        }

        return $next($request);
    }
}

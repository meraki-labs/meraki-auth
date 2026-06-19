<?php

namespace Meraki\Packages\Auth\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Meraki\Packages\Auth\Services\AuthDriverManager;
use Symfony\Component\HttpFoundation\Response;

class EnsureAuthDriverActive
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!app(AuthDriverManager::class)->isActive()) {
            abort(503, 'Auth driver is not active.');
        }

        return $next($request);
    }
}

<?php

namespace Siyaludeio\IPAccessGuard\Middleware;

use Closure;

class IPAccessRouteMiddleware
{
    public function handle($request, Closure $next)
    {
        
        $ipAddresses = IPAddressManager::getIPAddresses();
        $clientIP = $request->ip();

        if (!in_array($clientIP, $ipAddresses)) {
            return response('Not Found', 403);
        }

        return $next($request);
    }
}

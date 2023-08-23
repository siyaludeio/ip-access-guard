<?php

namespace Siyaludeio\IPAccessGuard\Http\Middleware;

use Closure;
use Siyaludeio\IPAccessGuard\Services\IPAddressManager;

class IPAccessRouteMiddleware
{
    public function handle($request, Closure $next)
    {

        $isActive = config('ip-access-guard.active');
        if (!$isActive) {
            return $next($request);
        }

        $IpAddressManager = new IPAddressManager();

        if (!$IpAddressManager->verifyIp()) {
            return abort(403);
        }

        return $next($request);
    }
}

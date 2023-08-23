<?php

namespace Siyaludeio\IPAccessGuard\Middleware;

use Closure;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Str;

class IPAccessMiddleware extends Middleware
{
    public function handle($request, Closure $next): mixed
    {
        $isGlobal = config('ip-access-guard.global');

        if (!$isGlobal) {
            return $next($request);
        }

        $protectedPaths = config('ip-access-guard.protected_paths');
        $path = $request->getPathInfo();

        if (Str::contains($path, $protectedPaths)) {
            $ipAddresses = IPAddressManager::getIPAddresses();
            $clientIP = $request->ip();

            if (!in_array($clientIP, $ipAddresses)) {
                return response('Not Found', 403);
            }
        }

        return $next($request);
    }
}

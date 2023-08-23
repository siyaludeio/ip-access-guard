<?php

namespace Siyaludeio\IPAccessGuard\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Siyaludeio\IPAccessGuard\Services\IPAddressManager;
use Symfony\Component\HttpFoundation\Response;

class IPAccessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $isActive = config('ip-access-guard.active');
        if (!$isActive) {
            return $next($request);
        }

        $isGlobal = config('ip-access-guard.global');

        if (!$isGlobal) {
            return $next($request);
        }

        $protectedPaths = config('ip-access-guard.protected_paths');
        $path = $request->getPathInfo();

        if (Str::contains($path, $protectedPaths)) {
            $IpAddressManager = new IPAddressManager();
            if (!$IpAddressManager->verifyIp()) {
                return abort(403);
            }
        }

        return $next($request);
    }
}

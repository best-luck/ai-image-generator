<?php

namespace Vironeer\License\App\Http\Middleware;

use Closure;

class NotInstalledMiddleware
{
    public function handle($request, Closure $next)
    {
        if (!env('VR_SYSTEMSTATUS')) {return redirect()->route('install.index');}
        return $next($request);
    }
}

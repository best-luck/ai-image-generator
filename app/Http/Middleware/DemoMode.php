<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class DemoMode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (demoMode()) {
            if ($request->isMethod('POST') || $request->isMethod('PUT') || $request->isMethod('DELETE')) {
                toastr()->error('Some features are disabled in the demo version');
                return back();
            }
        }
        return $next($request);
    }
}

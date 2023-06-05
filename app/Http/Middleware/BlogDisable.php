<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class BlogDisable
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
        abort_if(!settings('actions')->blog_status, 404);
        return $next($request);
    }
}
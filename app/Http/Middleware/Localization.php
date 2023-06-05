<?php

namespace App\Http\Middleware;

use App;
use Closure;
use Illuminate\Http\Request;

class Localization
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
        if (!settings('actions')->language_type && session()->has('locale')) {
            App::setLocale(session('locale'));
            App::setLocale(config('app.locale'));
        }
        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsSubscribed
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
        if (auth()->user() && (!auth()->user()->isSubscribed() || !auth()->user()->subscription->isActive() || auth()->user()->subscription->plan_id == 3)) {
            toastr()->info(lang('You are not subscribed or your subscribtion is expired, please subscribe or upgrade your subscription', 'account'));
            return redirect()->route('pricing');
        }
        return $next($request);
    }
}

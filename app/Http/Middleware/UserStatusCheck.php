<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;

class UserStatusCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::user() && userAuthInfo()->status == 0) {
            Auth::logout();
            toastr()->error(lang('Your account has been blocked', 'auth'));
            return redirect()->route('login');
        }
        return $next($request);
    }
}

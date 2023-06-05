<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RegistrationStatusCheck
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
        if (!settings('actions')->registration_status) {
            toastr()->error(lang('Registration is currently disabled.', 'auth'));
            return back();
        }
        return $next($request);
    }
}

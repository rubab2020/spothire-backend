<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            if(Auth::guard($guard)->user()->is_approved)
                return redirect('/admin/dashboard');
            else{
                Auth::guard($guard)->logout();
                return redirect('/admin/login');
            }
        }

        return $next($request);
    }
}

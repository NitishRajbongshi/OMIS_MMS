<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
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
        if (Auth::User()) {
            if ((Auth::User()->user_role_id == 2) || (Auth::User()->user_role_id == 1) || (Auth::User()->user_role_id == 0)) {
                return $next($request);
            } else {
                return redirect('/login');
            }
        } else {
            return redirect('/login');
        }
    }
}

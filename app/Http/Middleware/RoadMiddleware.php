<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoadMiddleware
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
        // Check if the user is authenticated
        if (Auth::check()) {
            $user = Auth::user();

            // Check if the user is either from the road department (department_id = 14) or is a superadmin (role_id = 1)
            if ($user->department == 14 || $user->department == 3 || $user->user_role_id == 1) {
                return $next($request);
            }
        }

        // If the user is not authorized, redirect them or return a 403 response
        return redirect('/unauthorized')->with('error', 'Access Denied'); // or return a 403 response
    }
}

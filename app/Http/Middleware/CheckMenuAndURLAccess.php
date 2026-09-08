<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class CheckMenuAndURLAccess
{
    public function handle(Request $request, Closure $next, $requiredMenuId)
    {
        // Authentication check
        if (!Auth::check()) {
            return redirect()
                ->route('login')
                ->with('error', 'Please log in to continue.');
        }

        // Convert assigned menu IDs to integers
        $userMenuIds = array_map(
            'intval',
            (array) session('menu', [])
        );

        if (!in_array((int) $requiredMenuId, $userMenuIds, true)) {
            abort(403, 'You are not authorised to access this page.');
        }

        return $next($request);
    }
}

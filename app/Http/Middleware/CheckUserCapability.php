<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckUserCapability
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$capabilities)
    {
        $allowedCapabilities = [
            'finalised',
            'uploadFile',
            'can_req_modify',
            'can_aprv_modify_req',
            'inserted',
            'updated',
            'deleted',
            'approveRoadSubAsset',
        ];

        foreach ($capabilities as $capability) {
            if (!in_array($capability, $allowedCapabilities, true)) {
                abort(403, 'Invalid access requirement.');
            }

            if ((int) session($capability, 0) !== 1) {
                abort(403, 'You are not authorised to perform this operation.');
            }
        }
        return $next($request);
    }
}

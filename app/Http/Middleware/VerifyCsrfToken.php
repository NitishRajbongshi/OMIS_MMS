<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Exception;
class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        //
    ];

    public function handle($request, \Closure $next)
    {
        try {
            return parent::handle($request, $next);
        } catch (Exception $e) {
            // return redirect()->route('login');
            return redirect()->route('session-expired');
        }
    }
}

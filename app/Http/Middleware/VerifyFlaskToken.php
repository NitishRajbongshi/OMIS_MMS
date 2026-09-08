<?php

namespace App\Http\Middleware;


use Closure;
use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;
use Illuminate\Support\Facades\Log;

class VerifyFlaskToken
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
        $token = $request->header('x-access-token');

        if (!$token) {
            return response()->json([
                'status_code' => "401",
                "status" => false,
                'message' => 'Token missing'
            ], 401);
        }

        try {
            $decoded = JWT::decode(
                $token,
                new Key(config('app.flask_jwt_secret'), 'HS256')
            );

            // Validate issuer
            if (!isset($decoded->iss) || $decoded->iss !== 'nl-pwd-auth-service') {
                return response()->json([
                    'status_code' => "498",
                    "status" => false,
                    'message' => 'Invalid issuer'
                ], 401);
            }

            // Attach user data to request
            $request->attributes->add([
                'auth_user' => $decoded
            ]);

        } catch (ExpiredException $e) {

            return response()->json([
                'status_code' => "498",
                'message' => 'Token expired'
            ], 401);

        } catch (\Exception $e) {
            return response()->json([
                'status_code' => "498",
                "status" => false,
                'message' => 'Invalid token'
            ], 401);
        }

        return $next($request);
    }
}

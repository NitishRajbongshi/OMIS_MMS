<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            // Log all exceptions in one place
            Log::error($e->getMessage(), ['exception' => $e]);
        });

        //Saiful -- 18-04-2026 -- Start
        $this->reportable(function (NotFoundHttpException $e) {
            if (request()->is('.well-known/*')) {
                return false; // ignore logging
            }
        });
        //Saiful -- 18-04-2026 -- End

        $this->renderable(function (MethodNotAllowedHttpException $e, $request) {
            Log::error("message: Method Not Allowed", [
                'url' => request()->fullUrl(),
                'method' => request()->method(),
                'info' => 'Method Not Allowed',
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return response()->view('errors.405', [], Response::HTTP_METHOD_NOT_ALLOWED);
        });

        $this->renderable(function (NotFoundHttpException $e, $request) {
            Log::error("message: URL Not Found", [
                'url' => request()->fullUrl(),
                'method' => request()->method(),
                'info' => 'URL Not Found',
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return response()->view('errors.404', [], Response::HTTP_NOT_FOUND);
        });

        $this->renderable(function (TokenMismatchException $e, $request) {
            Log::error("message: Session Expired", [
                'url' => request()->fullUrl(),
                'method' => request()->method(),
                'info' => 'Session Expired',
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return response()->view('errors.419', [], 419);
        });

        $this->renderable(function (AuthenticationException $e, $request) {
            Log::error("message: Not Authenticated", [
                'url' => request()->fullUrl(),
                'method' => request()->method(),
                'info' => 'Unauthenticated Access',
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], Response::HTTP_UNAUTHORIZED);
            }
            return redirect()->route('login')->with('error', 'You need to login to access this page.');
        });

        $this->renderable(function (ValidationException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'The given data was invalid.',
                    'errors' => $e->errors(),
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            return redirect()->back()->withErrors($e->errors())->withInput();
        });

        $this->renderable(function (Throwable $e, $request) {
            if ($e instanceof HttpExceptionInterface) {
                return null;
            }
            if ($e instanceof Exception) {
                return response()->view('errors.generic', [], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        });
    }
}

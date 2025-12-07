<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\RoleChecker;
use App\Http\Middleware\Authenticate;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // --- KUNCI PERBAIKAN: DAFTARKAN ROLE CHECKER DI SINI ---
        $middleware->alias([
            'auth' => Authenticate::class,
            'check.role' => RoleChecker::class,
            'api/xendit/*', // Izinkan Xendit mengakses route ini
        ]);

        // Opsional: Jika Anda ingin proteksi default untuk semua route web
        $middleware->web(append: [
            // \App\Http\Middleware\TrustProxies::class,
        ]);

        // Opsional: Jika Anda punya middleware group 'api'
        $middleware->api(prepend: [
            // \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (AuthenticationException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }

            // Perilaku yang Anda inginkan: Ganti redirect ke 404 View
            return response()->view('errors.404', [], 404);
        });
    })->create();

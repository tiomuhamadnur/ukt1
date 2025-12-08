<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Spatie\Permission\Exceptions\UnauthorizedException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // 🔐 MIDDLEWARE SPATIE PERMISSION
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            'CheckBanned' => \App\Http\Middleware\CheckBanned::class,
            'CheckKonfigurasiPJLP' => \App\Http\Middleware\CheckKonfigurasiPJLP::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // ❶ — Unauthorized (belum login)
        $exceptions->render(function (Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException $e, $request) {
            return response()->view('components.errors.401', [], 401);
        });

        // ❷ — Forbidden (sudah login tapi tidak punya izin)
        $exceptions->render(function (Spatie\Permission\Exceptions\UnauthorizedException $e, $request) {
            return response()->view('components.errors.403', [], 403);
        });

        // ❸ — Not Found
        $exceptions->render(function (Symfony\Component\HttpKernel\Exception\NotFoundHttpException $e, $request) {
            return response()->view('components.errors.404', [], 404);
        });

        // ❹ — Method not allowed (optional)
        $exceptions->render(function (Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException $e, $request) {
            return response()->view('components.errors.404', [], 404);
        });

        // 419 Page Expired → CSRF token mismatch
        $exceptions->render(function (\Illuminate\Session\TokenMismatchException $e, $request) {
            return response()->view('components.errors.419', [], 419);
        });

        // // ❺ — Server Error (fallback)
        // $exceptions->render(function (Throwable $e, $request) {
        //     return response()->view('components.errors.500', [], 500);
        // });
    })->create();

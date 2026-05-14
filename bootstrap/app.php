<?php

use App\Http\Middleware\EnsureUserIsAdmin;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => EnsureUserIsAdmin::class,
        ]);
        // /api/* usa Bearer tokens (Sanctum). NO statefulApi para evitar CSRF en SPA con tokens.
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Estructura unificada para excepciones en rutas /api
        $exceptions->render(function (\Throwable $e, Request $request) {
            if (!$request->is('api/*') && !$request->expectsJson()) {
                return null;
            }

            if ($e instanceof ValidationException) {
                return response()->json([
                    'success' => false,
                    'message' => 'Datos inválidos',
                    'data' => null,
                    'errors' => $e->errors(),
                ], 422);
            }

            $status = $e instanceof HttpException ? $e->getStatusCode() : 500;
            $message = $e->getMessage() ?: match ($status) {
                401 => 'No autenticado',
                403 => 'No autorizado',
                404 => 'Recurso no encontrado',
                405 => 'Método no permitido',
                default => 'Error interno',
            };

            return response()->json([
                'success' => false,
                'message' => $message,
                'data' => null,
                'errors' => app()->hasDebugModeEnabled() && $status === 500
                    ? ['debug' => $e->getMessage()]
                    : null,
            ], $status);
        });
    })->create();

<?php

use Illuminate\Http\Request;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Token inválido o no enviado
        $exceptions->render(function (AuthenticationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'No autenticado. Token inválido o no enviado.',
            ], 401);
        });

        // Validación fallida
        $exceptions->render(function (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación.',
                'errors'  => $e->errors(),
            ], 422);
        });

        // Modelo no encontrado — ej: /tickets/9999
        $exceptions->render(function (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Recurso no encontrado.',
            ], 404);
        });

        // Ruta no encontrada
        $exceptions->render(function (NotFoundHttpException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ruta no encontrada.',
            ], 404);
        });

        // Rate limit excedido — notifica a Discord
        $exceptions->render(function (TooManyRequestsHttpException $e, Request $request) {
            app(\App\Services\DiscordService::class)->sendRateLimit(
                $request->path(),
                $request->ip()
            );

            return response()->json([
                'success' => false,
                'message' => 'Demasiadas peticiones. Intenta de nuevo en un minuto.',
            ], 429);
        });

        // Error 500 — notifica a Discord
        $exceptions->render(function (Throwable $e, Request $request) {
            app(\App\Services\DiscordService::class)->sendError(
                $request->path(),
                $request->method(),
                $e->getMessage(),
                $request->ip()
            );

            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor.',
                'error'   => app()->isLocal() ? $e->getMessage() : null,
            ], 500);
        });
    })->create();

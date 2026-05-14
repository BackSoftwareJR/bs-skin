<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'customer.auth' => \App\Http\Middleware\CustomerAuth::class,
        ]);
        // Escludi webhook dal CSRF
        $middleware->validateCsrfTokens(except: [
            'webhooks/*',
            'api/*',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (\App\Exceptions\OtpRateLimitException $e, $request) {
            if ($request->expectsJson() || $request->header('X-Livewire')) {
                return response()->json(['message' => $e->getMessage()], 429);
            }
            return back()->withErrors(['otp' => $e->getMessage()]);
        });
        
        // Exception mapping
        $exceptionMap = [
            \App\Exceptions\OtpInvalidException::class => 422,
            \App\Exceptions\OutOfStockException::class => 422,
            \App\Exceptions\InsufficientStockException::class => 422,
            \App\Exceptions\CouponNotFoundException::class => 422,
            \App\Exceptions\CouponNotApplicableException::class => 422,
            \App\Exceptions\InvalidOrderTransitionException::class => 422,
        ];
        foreach ($exceptionMap as $exceptionClass => $statusCode) {
            $exceptions->render(function ($e, $request) use ($statusCode) {
                if ($request->expectsJson() || $request->header('X-Livewire')) {
                    return response()->json(['message' => $e->getMessage()], $statusCode);
                }
                return back()->withErrors(['error' => $e->getMessage()]);
            }, $exceptionClass);
        }
    })->create();

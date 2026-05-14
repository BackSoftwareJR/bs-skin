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
        $exceptions->render(function (\App\Exceptions\OtpRateLimitException $e, \Illuminate\Http\Request $request) {
            if ($request->expectsJson() || $request->header('X-Livewire')) {
                return response()->json(['message' => $e->getMessage()], 429);
            }
            return back()->withErrors(['otp' => $e->getMessage()]);
        });
        
        $exceptions->render(function (\App\Exceptions\OtpInvalidException $e, \Illuminate\Http\Request $request) {
            if ($request->expectsJson() || $request->header('X-Livewire')) {
                return response()->json(['message' => $e->getMessage()], 422);
            }
            return back()->withErrors(['otp' => $e->getMessage()]);
        });
        
        $exceptions->render(function (\App\Exceptions\OutOfStockException $e, \Illuminate\Http\Request $request) {
            if ($request->expectsJson() || $request->header('X-Livewire')) {
                return response()->json(['message' => $e->getMessage()], 422);
            }
            return back()->withErrors(['cart' => $e->getMessage()]);
        });
        
        $exceptions->render(function (\App\Exceptions\InsufficientStockException $e, \Illuminate\Http\Request $request) {
            if ($request->expectsJson() || $request->header('X-Livewire')) {
                return response()->json(['message' => $e->getMessage()], 422);
            }
            return back()->withErrors(['cart' => $e->getMessage()]);
        });
        
        $exceptions->render(function (\App\Exceptions\CouponNotFoundException $e, \Illuminate\Http\Request $request) {
            if ($request->expectsJson() || $request->header('X-Livewire')) {
                return response()->json(['message' => $e->getMessage()], 404);
            }
            return back()->withErrors(['coupon' => $e->getMessage()]);
        });
        
        $exceptions->render(function (\App\Exceptions\CouponNotApplicableException $e, \Illuminate\Http\Request $request) {
            if ($request->expectsJson() || $request->header('X-Livewire')) {
                return response()->json(['message' => $e->getMessage()], 422);
            }
            return back()->withErrors(['coupon' => $e->getMessage()]);
        });
        
        $exceptions->render(function (\App\Exceptions\InvalidOrderTransitionException $e, \Illuminate\Http\Request $request) {
            if ($request->expectsJson() || $request->header('X-Livewire')) {
                return response()->json(['message' => $e->getMessage()], 422);
            }
            return back()->withErrors(['order' => $e->getMessage()]);
        });
    })->create();

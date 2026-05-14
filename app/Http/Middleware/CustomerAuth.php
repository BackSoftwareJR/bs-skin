<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as BaseResponse;

class CustomerAuth
{
    public function handle(Request $request, Closure $next): BaseResponse
    {
        $customerId = session('skintemple_customer_id');
        
        if (!$customerId) {
            if ($request->expectsJson() || $request->hasHeader('X-Livewire')) {
                return response()->json([
                    'error' => true,
                    'message' => 'Accesso richiesto',
                    'redirect' => route('public.auth.login'),
                ], 401);
            }
            
            return redirect()
                ->guest(route('public.auth.login'))
                ->with('intended', $request->fullUrl());
        }
        
        return $next($request);
    }
}
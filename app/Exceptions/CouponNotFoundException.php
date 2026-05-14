<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CouponNotFoundException extends Exception
{
    public function __construct()
    {
        parent::__construct('Coupon non trovato.', 422);
    }

    public function render(Request $request): JsonResponse|Response
    {
        if ($request->expectsJson() || $request->hasHeader('X-Livewire')) {
            return response()->json([
                'error' => true,
                'message' => $this->getMessage(),
            ], 422);
        }

        return back()->withErrors(['code' => $this->getMessage()])->withInput();
    }
}
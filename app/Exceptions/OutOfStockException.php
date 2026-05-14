<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OutOfStockException extends Exception
{
    public function __construct(string $productName = null)
    {
        $message = $productName 
            ? "Prodotto '{$productName}' non disponibile."
            : 'Prodotto non disponibile.';
        
        parent::__construct($message, 422);
    }

    public function render(Request $request): JsonResponse|Response
    {
        if ($request->expectsJson() || $request->hasHeader('X-Livewire')) {
            return response()->json([
                'error' => true,
                'message' => $this->getMessage(),
            ], 422);
        }

        return back()->withErrors(['variant_id' => $this->getMessage()])->withInput();
    }
}
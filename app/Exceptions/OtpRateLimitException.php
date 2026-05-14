<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OtpRateLimitException extends Exception
{
    public function __construct(int $remainingMinutes = 15)
    {
        $message = "Troppi tentativi. Riprova tra {$remainingMinutes} minuti.";
        parent::__construct($message, 429);
    }

    public function render(Request $request): JsonResponse|Response
    {
        if ($request->expectsJson() || $request->hasHeader('X-Livewire')) {
            return response()->json([
                'error' => true,
                'message' => $this->getMessage(),
            ], 429);
        }

        return back()->withErrors(['email' => $this->getMessage()])->withInput();
    }
}
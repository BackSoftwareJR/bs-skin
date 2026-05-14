<?php

declare(strict_types=1);

namespace App\Http\Controllers\Webhooks;

use App\Contracts\PaymentGateway;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PayPalWebhookController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        // Verifica feature flag
        if (!config('skintemple.payments.paypal_enabled', false)) {
            return response()->json(['error' => 'PayPal non abilitato'], 503);
        }
        
        // Delega al gateway PayPal
        $gateway = app(PaymentGateway::class, ['driver' => 'paypal']);
        
        $result = $gateway->handleWebhook(
            $request->all(),
            $request->headers->all()
        );
        
        return response()->json($result);
    }
}
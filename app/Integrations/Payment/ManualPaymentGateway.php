<?php

declare(strict_types=1);

namespace App\Integrations\Payment;

use App\Contracts\PaymentGateway;
use App\Models\Order;
use App\Models\Payment;

class ManualPaymentGateway implements PaymentGateway
{
    public function createIntent(Order $order, array $context = []): array
    {
        return [
            'status' => 'pending',
            'instructions' => config('skintemple.payment.manual_instructions',
                "Effettuare il bonifico a:\nIBAN: [da configurare]\nCausale: Ordine {$order->order_number}"),
        ];
    }

    public function capture(Payment $payment): array
    {
        $payment->update(['status' => 'captured', 'captured_at' => now()]);

        return ['status' => 'captured'];
    }

    public function refund(Payment $payment, ?int $amountCents = null): array
    {
        // TODO: F6 — implementare logica refund manuale
        return ['status' => 'pending', 'note' => 'Rimborso manuale da processare'];
    }

    public function handleWebhook(array $payload, array $headers): array
    {
        return ['status' => 'ignored', 'reason' => 'Manual gateway does not process webhooks'];
    }

    public function getStatus(Payment $payment): string
    {
        return $payment->status;
    }

    public function isEnabled(): bool
    {
        return true;
    }

    public function driverName(): string
    {
        return 'manual';
    }
}

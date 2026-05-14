<?php

declare(strict_types=1);

namespace App\Integrations\Payment;

use App\Contracts\PaymentGateway;
use App\Exceptions\IntegrationNotEnabledException;
use App\Models\Order;
use App\Models\Payment;

class PayPalPaymentGateway implements PaymentGateway
{
    public function createIntent(Order $order, array $context = []): array
    {
        throw IntegrationNotEnabledException::forDriver('paypal');
    }

    public function capture(Payment $payment): array
    {
        throw IntegrationNotEnabledException::forDriver('paypal');
    }

    public function refund(Payment $payment, ?int $amountCents = null): array
    {
        throw IntegrationNotEnabledException::forDriver('paypal');
    }

    public function handleWebhook(array $payload, array $headers): array
    {
        throw IntegrationNotEnabledException::forDriver('paypal');
    }

    public function getStatus(Payment $payment): string
    {
        throw IntegrationNotEnabledException::forDriver('paypal');
    }

    public function isEnabled(): bool
    {
        return (bool) config('services.paypal.enabled', false);
    }

    public function driverName(): string
    {
        return 'paypal';
    }
}

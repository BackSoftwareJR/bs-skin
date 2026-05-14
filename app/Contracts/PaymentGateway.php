<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Models\Order;
use App\Models\Payment;

interface PaymentGateway
{
    public function createIntent(Order $order, array $context = []): array;

    public function capture(Payment $payment): array;

    public function refund(Payment $payment, ?int $amountCents = null): array;

    public function handleWebhook(array $payload, array $headers): array;

    public function getStatus(Payment $payment): string;

    public function isEnabled(): bool;

    public function driverName(): string;
}

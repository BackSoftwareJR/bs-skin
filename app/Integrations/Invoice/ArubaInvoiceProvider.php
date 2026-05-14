<?php

declare(strict_types=1);

namespace App\Integrations\Invoice;

use App\Contracts\InvoiceProvider;
use App\Exceptions\IntegrationNotEnabledException;
use App\Models\Invoice;
use App\Models\Order;

class ArubaInvoiceProvider implements InvoiceProvider
{
    public function createInvoice(Order $order): array
    {
        throw IntegrationNotEnabledException::forDriver('aruba');
    }

    public function sendInvoice(Invoice $invoice): bool
    {
        throw IntegrationNotEnabledException::forDriver('aruba');
    }

    public function getStatus(Invoice $invoice): string
    {
        throw IntegrationNotEnabledException::forDriver('aruba');
    }

    public function downloadPdf(Invoice $invoice): ?string
    {
        throw IntegrationNotEnabledException::forDriver('aruba');
    }

    public function isEnabled(): bool
    {
        return (bool) config('services.aruba.enabled', false);
    }

    public function driverName(): string
    {
        return 'aruba';
    }
}

<?php

declare(strict_types=1);

namespace App\Integrations\Invoice;

use App\Contracts\InvoiceProvider;
use App\Exceptions\IntegrationNotEnabledException;
use App\Models\Invoice;
use App\Models\Order;

class FattureInCloudInvoiceProvider implements InvoiceProvider
{
    public function createInvoice(Order $order): array
    {
        throw IntegrationNotEnabledException::forDriver('fatture_in_cloud');
    }

    public function sendInvoice(Invoice $invoice): bool
    {
        throw IntegrationNotEnabledException::forDriver('fatture_in_cloud');
    }

    public function getStatus(Invoice $invoice): string
    {
        throw IntegrationNotEnabledException::forDriver('fatture_in_cloud');
    }

    public function downloadPdf(Invoice $invoice): ?string
    {
        throw IntegrationNotEnabledException::forDriver('fatture_in_cloud');
    }

    public function isEnabled(): bool
    {
        return (bool) config('services.fatture_in_cloud.enabled', false);
    }

    public function driverName(): string
    {
        return 'fatture_in_cloud';
    }
}

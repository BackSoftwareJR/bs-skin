<?php

declare(strict_types=1);

namespace App\Integrations\Invoice;

use App\Contracts\InvoiceProvider;
use App\Models\Invoice;
use App\Models\Order;

class NoneInvoiceProvider implements InvoiceProvider
{
    public function createInvoice(Order $order): array
    {
        return ['status' => 'skipped', 'reason' => 'Fatturazione elettronica non attiva'];
    }

    public function sendInvoice(Invoice $invoice): bool
    {
        return false;
    }

    public function getStatus(Invoice $invoice): string
    {
        return 'not_applicable';
    }

    public function downloadPdf(Invoice $invoice): ?string
    {
        return null;
    }

    public function isEnabled(): bool
    {
        return false;
    }

    public function driverName(): string
    {
        return 'none';
    }
}

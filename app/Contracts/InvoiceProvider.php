<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Models\Invoice;
use App\Models\Order;

interface InvoiceProvider
{
    public function createInvoice(Order $order): array;

    public function sendInvoice(Invoice $invoice): bool;

    public function getStatus(Invoice $invoice): string;

    public function downloadPdf(Invoice $invoice): ?string;

    public function isEnabled(): bool;

    public function driverName(): string;
}

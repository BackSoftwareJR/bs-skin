<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Models\Shipment;

interface ShippingProvider
{
    public function rates(array $address, array $items): array;

    public function createShipment(Shipment $shipment): array;

    public function tracking(string $trackingNumber): array;

    public function isEnabled(): bool;

    public function driverName(): string;
}

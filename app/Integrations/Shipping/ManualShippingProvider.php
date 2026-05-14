<?php

declare(strict_types=1);

namespace App\Integrations\Shipping;

use App\Contracts\ShippingProvider;
use App\Models\Shipment;

class ManualShippingProvider implements ShippingProvider
{
    public function rates(array $address, array $items): array
    {
        $flatRate = (float) config('skintemple.shipping.flat_rate', 7.90);
        $freeThreshold = (float) config('skintemple.shipping.free_threshold', 99.00);
        $subtotal = collect($items)->sum(fn ($i) => $i['price'] * $i['quantity']);

        return [
            [
                'code' => 'standard',
                'name' => 'Spedizione Standard',
                'cost' => $subtotal >= $freeThreshold ? 0.0 : $flatRate,
                'estimated_days' => '3-5 giorni lavorativi',
            ],
        ];
    }

    public function createShipment(Shipment $shipment): array
    {
        return ['status' => 'pending', 'note' => 'Tracking da inserire manualmente in admin'];
    }

    public function tracking(string $trackingNumber): array
    {
        return ['status' => 'unknown', 'note' => 'Tracking manuale — verificare sul sito del corriere'];
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

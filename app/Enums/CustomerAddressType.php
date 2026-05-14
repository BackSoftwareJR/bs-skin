<?php

namespace App\Enums;

enum CustomerAddressType: string
{
    case SHIPPING = 'shipping';
    case BILLING = 'billing';
    case BOTH = 'both';

    public function label(): string
    {
        return match ($this) {
            self::SHIPPING => 'Spedizione',
            self::BILLING => 'Fatturazione',
            self::BOTH => 'Spedizione e Fatturazione',
        };
    }
}
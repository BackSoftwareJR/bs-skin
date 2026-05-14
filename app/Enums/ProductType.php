<?php

namespace App\Enums;

enum ProductType: string
{
    case COSMETIC = 'cosmetic';
    case DEVICE = 'device';
    case ACCESSORY = 'accessory';

    public function label(): string
    {
        return match ($this) {
            self::COSMETIC => 'Cosmetico',
            self::DEVICE => 'Dispositivo',
            self::ACCESSORY => 'Accessorio',
        };
    }
}
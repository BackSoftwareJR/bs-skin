<?php

namespace App\Enums;

enum StockMovementType: string
{
    case IN = 'in';
    case OUT = 'out';
    case ADJUSTMENT = 'adjustment';
    case RETURN = 'return';
    case SALE = 'sale';
    case RESTOCK = 'restock';

    public function label(): string
    {
        return match ($this) {
            self::IN => 'Entrata',
            self::OUT => 'Uscita',
            self::ADJUSTMENT => 'Rettifica',
            self::RETURN => 'Reso',
            self::SALE => 'Vendita',
            self::RESTOCK => 'Rifornimento',
        };
    }
}
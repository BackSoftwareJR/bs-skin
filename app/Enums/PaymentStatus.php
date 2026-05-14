<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case PENDING = 'pending';
    case AUTHORIZED = 'authorized';
    case CAPTURED = 'captured';
    case FAILED = 'failed';
    case REFUNDED = 'refunded';
    case PARTIALLY_REFUNDED = 'partially_refunded';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'In attesa',
            self::AUTHORIZED => 'Autorizzato',
            self::CAPTURED => 'Pagato',
            self::FAILED => 'Fallito',
            self::REFUNDED => 'Rimborsato',
            self::PARTIALLY_REFUNDED => 'Rimborsato parzialmente',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::PENDING => 'warning',
            self::AUTHORIZED => 'info',
            self::CAPTURED => 'success',
            self::FAILED => 'danger',
            self::REFUNDED => 'secondary',
            self::PARTIALLY_REFUNDED => 'secondary',
        };
    }
}
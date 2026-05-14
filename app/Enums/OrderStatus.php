<?php

declare(strict_types=1);

namespace App\Enums;

enum OrderStatus: string
{
    case PENDING = 'pending';
    case CONFIRMED = 'confirmed';
    case PROCESSING = 'processing';
    case SHIPPED = 'shipped';
    case DELIVERED = 'delivered';
    case CANCELLED = 'cancelled';
    case REFUNDED = 'refunded';
    case PAID = 'paid';
    
    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'In attesa',
            self::CONFIRMED => 'Confermato',
            self::PROCESSING => 'In lavorazione',
            self::SHIPPED => 'Spedito',
            self::DELIVERED => 'Consegnato',
            self::CANCELLED => 'Annullato',
            self::REFUNDED => 'Rimborsato',
            self::PAID => 'Pagato',
        };
    }
    
    public function canTransitionTo(self $newStatus): bool
    {
        return match ($this) {
            self::PENDING => in_array($newStatus, [self::CONFIRMED, self::PAID, self::CANCELLED]),
            self::CONFIRMED => in_array($newStatus, [self::PROCESSING, self::CANCELLED]),
            self::PAID => in_array($newStatus, [self::PROCESSING, self::CANCELLED]),
            self::PROCESSING => in_array($newStatus, [self::SHIPPED, self::CANCELLED]),
            self::SHIPPED => in_array($newStatus, [self::DELIVERED]),
            self::DELIVERED => in_array($newStatus, [self::REFUNDED]),
            self::CANCELLED => false,
            self::REFUNDED => false,
        };
    }
    
    public function color(): string
    {
        return match ($this) {
            self::PENDING => 'warning',
            self::CONFIRMED => 'info',
            self::PAID => 'success',
            self::PROCESSING => 'primary',
            self::SHIPPED => 'primary',
            self::DELIVERED => 'success',
            self::CANCELLED => 'danger',
            self::REFUNDED => 'secondary',
        };
    }
}
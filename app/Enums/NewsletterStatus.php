<?php

namespace App\Enums;

enum NewsletterStatus: string
{
    case PENDING = 'pending';
    case SUBSCRIBED = 'subscribed';
    case UNSUBSCRIBED = 'unsubscribed';
    case BOUNCED = 'bounced';
    case COMPLAINED = 'complained';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'In attesa di conferma',
            self::SUBSCRIBED => 'Iscritto',
            self::UNSUBSCRIBED => 'Disiscritto',
            self::BOUNCED => 'Email non valida',
            self::COMPLAINED => 'Segnalato come spam',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::PENDING => 'warning',
            self::SUBSCRIBED => 'success',
            self::UNSUBSCRIBED => 'secondary',
            self::BOUNCED => 'danger',
            self::COMPLAINED => 'danger',
        };
    }
}
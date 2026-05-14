<?php

namespace App\Enums;

enum CategoryType: string
{
    case MACROAREA = 'macroarea';
    case MICROAREA = 'microarea';

    public function label(): string
    {
        return match ($this) {
            self::MACROAREA => 'Macroarea',
            self::MICROAREA => 'Microarea',
        };
    }
}
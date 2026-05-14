<?php

declare(strict_types=1);

namespace App\Exceptions;

use RuntimeException;

class IntegrationNotEnabledException extends RuntimeException
{
    public static function forDriver(string $driver): self
    {
        return new self("L'integrazione [{$driver}] non è attiva. Attivala dalle Impostazioni > Integrazioni.");
    }
}

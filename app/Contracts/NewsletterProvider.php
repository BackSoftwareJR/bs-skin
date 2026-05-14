<?php

declare(strict_types=1);

namespace App\Contracts;

interface NewsletterProvider
{
    public function subscribe(string $email, array $meta = []): void;

    public function unsubscribe(string $email): void;

    public function sync(string $email, array $tags = []): void;

    public function isEnabled(): bool;

    public function driverName(): string;
}

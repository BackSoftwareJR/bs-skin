<?php

declare(strict_types=1);

namespace App\Integrations\Newsletter;

use App\Contracts\NewsletterProvider;
use Illuminate\Support\Facades\DB;

class DatabaseNewsletterProvider implements NewsletterProvider
{
    public function subscribe(string $email, array $meta = []): void
    {
        DB::table('newsletter_subscribers')->updateOrInsert(
            ['email' => $email],
            [
                'first_name' => $meta['first_name'] ?? null,
                'locale' => $meta['locale'] ?? 'it',
                'status' => 'pending',
                'source' => $meta['source'] ?? 'manual',
                'confirmation_token' => bin2hex(random_bytes(32)),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }

    public function unsubscribe(string $email): void
    {
        DB::table('newsletter_subscribers')
            ->where('email', $email)
            ->update(['status' => 'unsubscribed', 'unsubscribed_at' => now()]);
    }

    public function sync(string $email, array $tags = []): void
    {
        DB::table('newsletter_subscribers')
            ->where('email', $email)
            ->update(['tags' => json_encode($tags), 'updated_at' => now()]);
    }

    public function isEnabled(): bool
    {
        return true;
    }

    public function driverName(): string
    {
        return 'database';
    }
}

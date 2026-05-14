<?php

declare(strict_types=1);

namespace App\Integrations\Newsletter;

use App\Contracts\NewsletterProvider;
use App\Exceptions\IntegrationNotEnabledException;

class MailchimpNewsletterProvider implements NewsletterProvider
{
    public function subscribe(string $email, array $meta = []): void
    {
        throw IntegrationNotEnabledException::forDriver('mailchimp');
    }

    public function unsubscribe(string $email): void
    {
        throw IntegrationNotEnabledException::forDriver('mailchimp');
    }

    public function sync(string $email, array $tags = []): void
    {
        throw IntegrationNotEnabledException::forDriver('mailchimp');
    }

    public function isEnabled(): bool
    {
        return (bool) config('services.mailchimp.enabled', false);
    }

    public function driverName(): string
    {
        return 'mailchimp';
    }
}

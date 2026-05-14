<?php

declare(strict_types=1);

namespace App\Actions\Newsletter;

use App\Contracts\NewsletterProvider;
use App\Models\NewsletterSubscriber;

class ConfirmDoubleOptInAction
{
    public function __construct(
        private NewsletterProvider $newsletterProvider
    ) {}
    
    public function execute(string $email, string $token): NewsletterSubscriber
    {
        $subscriber = NewsletterSubscriber::where('email', $email)
            ->where('double_opt_in_token', $token)
            ->where('double_opt_in_expires_at', '>', now())
            ->firstOrFail();
            
        $subscriber->status = 'subscribed';
        $subscriber->subscribed_at = now();
        $subscriber->double_opt_in_token = null;
        $subscriber->double_opt_in_expires_at = null;
        $subscriber->save();
        
        // Sync con provider esterno (Mailchimp, etc.)
        if ($this->newsletterProvider->isEnabled()) {
            $this->newsletterProvider->subscribe($subscriber->email, [
                'name' => $subscriber->name,
                'locale' => $subscriber->locale,
                'source' => $subscriber->source,
            ]);
        }
        
        return $subscriber;
    }
}
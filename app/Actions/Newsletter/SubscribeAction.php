<?php

declare(strict_types=1);

namespace App\Actions\Newsletter;

use App\Events\NewsletterSubscribed;
use App\Models\NewsletterSubscriber;
use Illuminate\Support\Str;

class SubscribeAction
{
    public function execute(string $email, ?string $name = null, string $source = 'website', string $locale = 'it'): NewsletterSubscriber
    {
        $subscriber = NewsletterSubscriber::where('email', $email)->first();
        
        if ($subscriber) {
            // Se già subscribed, ritorna senza errori (idempotente)
            if ($subscriber->status === 'subscribed') {
                return $subscriber;
            }
            
            // Se pending o unsubscribed, rigenera token per double opt-in
            if (in_array($subscriber->status, ['pending', 'unsubscribed'])) {
                $subscriber->double_opt_in_token = Str::random(64);
                $subscriber->double_opt_in_expires_at = now()->addHours(48);
                $subscriber->status = 'pending';
                $subscriber->source = $source;
                
                if ($name) {
                    $subscriber->name = $name;
                }
                
                $subscriber->save();
            }
        } else {
            // Nuovo subscriber
            $subscriber = NewsletterSubscriber::create([
                'email' => $email,
                'name' => $name,
                'locale' => $locale,
                'status' => 'pending',
                'source' => $source,
                'double_opt_in_token' => Str::random(64),
                'double_opt_in_expires_at' => now()->addHours(48),
            ]);
        }
        
        event(new NewsletterSubscribed($subscriber));
        
        return $subscriber;
    }
}
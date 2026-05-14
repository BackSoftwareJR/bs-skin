<?php

namespace App\Livewire\Public\Newsletter;

use Livewire\Component;
use App\Models\NewsletterSubscriber;

class SubscribeForm extends Component
{
    public string $email = '';
    public string $name = '';
    public bool $submitted = false;
    public string $state = 'idle'; // idle, loading, success, error
    public ?string $message = null;

    protected $rules = [
        'email' => 'required|email:rfc',
        'name' => 'nullable|string|max:100',
    ];

    protected $messages = [
        'email.required' => 'L\'email è obbligatoria.',
        'email.email' => 'Inserisci un indirizzo email valido.',
    ];

    public function subscribe(): void
    {
        $this->validate();
        $this->state = 'loading';

        try {
            // Controlla se già iscritto
            $existing = NewsletterSubscriber::where('email', $this->email)->first();

            if ($existing) {
                if ($existing->subscribed) {
                    $this->state = 'success';
                    $this->message = 'Sei già iscritto alla nostra newsletter!';
                } else {
                    // Riattiva iscrizione
                    $existing->update([
                        'subscribed' => true,
                        'subscribed_at' => now(),
                    ]);
                    $this->state = 'success';
                    $this->message = 'Iscrizione riattivata con successo!';
                }
            } else {
                // Nuova iscrizione
                NewsletterSubscriber::create([
                    'email' => $this->email,
                    'name' => $this->name ?: null,
                    'subscribed' => true,
                    'subscribed_at' => now(),
                    'source' => 'website',
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);

                $this->state = 'success';
                $this->message = 'Grazie! Ti abbiamo iscritto alla newsletter.';

                // TODO: inviare email di double opt-in in background
                // dispatch(new SendDoubleOptInEmail($this->email));
            }

            $this->submitted = true;

            $this->dispatch('toast', [
                'type' => 'success',
                'message' => 'Iscrizione completata!'
            ]);

        } catch (\Exception $e) {
            $this->state = 'error';
            $this->message = 'Si è verificato un errore. Riprova più tardi.';
            
            logger('Newsletter subscription error: ' . $e->getMessage());
            
            $this->dispatch('toast', [
                'type' => 'error',
                'message' => 'Errore durante l\'iscrizione'
            ]);
        }
    }

    public function reset(): void
    {
        $this->email = '';
        $this->name = '';
        $this->submitted = false;
        $this->state = 'idle';
        $this->message = null;
    }

    public function render()
    {
        return view('livewire.public.newsletter.subscribe-form');
    }
}
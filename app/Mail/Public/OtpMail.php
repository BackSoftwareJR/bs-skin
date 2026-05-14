<?php

namespace App\Mail\Public;

use App\Models\OtpCode;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $email,
        public OtpCode $otp
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Il tuo codice di accesso SkinTemple',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.public.otp',
            with: [
                'email' => $this->email,
                'otp' => $this->otp,
                'expiresMinutes' => $this->otp->expires_at->diffInMinutes(now()),
            ],
        );
    }
}
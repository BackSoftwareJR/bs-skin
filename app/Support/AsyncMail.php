<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Invio email asincrono senza queue worker.
 *
 * Usa Bus::dispatchAfterResponse() per inviare la mail DOPO che la response
 * HTTP è stata flushed al client. PHP-FPM chiama fastcgi_finish_request()
 * e il processo continua l'invio in background senza bloccare l'utente.
 *
 * Pattern valido per Hostinger Premium Web Hosting dove non esistono
 * queue worker, cron, o Redis. L'invio avviene nello stesso processo PHP
 * che ha servito la request, ma dopo il flush della response.
 *
 * ATTENZIONE: se il processo PHP viene killato prima del completamento
 * (es. timeout FPM), la mail non verrà inviata. Per mail critiche (OTP),
 * valutare l'invio sincrono diretto con Mail::to()->send().
 */
class AsyncMail
{
    public static function send(Mailable $mailable, string|array $to): void
    {
        Bus::dispatchAfterResponse(function () use ($mailable, $to) {
            try {
                Mail::to($to)->send($mailable);
            } catch (\Throwable $e) {
                Log::error('AsyncMail: invio fallito', [
                    'mailable' => $mailable::class,
                    'to' => $to,
                    'error' => $e->getMessage(),
                ]);

                if (class_exists(\Spatie\Activitylog\Models\Activity::class)) {
                    activity('mail_error')
                        ->withProperties([
                            'mailable' => $mailable::class,
                            'to' => $to,
                            'error' => $e->getMessage(),
                        ])
                        ->log('Invio email fallito');
                }
            }
        });
    }

    /**
     * Invio sincrono (bloccante). Usare per OTP e mail dove
     * la conferma di invio è critica per il flusso utente.
     */
    public static function sendSync(Mailable $mailable, string|array $to): bool
    {
        try {
            Mail::to($to)->send($mailable);

            return true;
        } catch (\Throwable $e) {
            Log::error('AsyncMail::sendSync fallito', [
                'mailable' => $mailable::class,
                'to' => $to,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }
}

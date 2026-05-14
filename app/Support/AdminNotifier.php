<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Log;

/**
 * Invia notifiche agli indirizzi email admin configurati in .env.
 * Usa AsyncMail per l'invio post-response.
 */
class AdminNotifier
{
    public static function notify(Mailable $mailable): void
    {
        $emails = self::getAdminEmails();

        foreach ($emails as $email) {
            AsyncMail::send($mailable, $email);
        }
    }

    public static function notifySync(Mailable $mailable): void
    {
        $emails = self::getAdminEmails();

        foreach ($emails as $email) {
            AsyncMail::sendSync($mailable, $email);
        }
    }

    /**
     * @return string[]
     */
    public static function getAdminEmails(): array
    {
        $emails = array_filter([
            config('skintemple.admin.email_primary'),
            config('skintemple.admin.email_secondary'),
        ]);

        if (empty($emails)) {
            Log::warning('AdminNotifier: nessun indirizzo email admin configurato.');
        }

        return $emails;
    }
}

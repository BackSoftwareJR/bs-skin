# Pattern Email Asincrona senza Queue Worker

## Problema
Hostinger Premium Web Hosting non supporta queue worker persistenti né Redis. Le email devono essere inviate senza bloccare la response HTTP ma senza infrastruttura di code.

## Soluzione: `Bus::dispatchAfterResponse()`

Laravel supporta `Bus::dispatchAfterResponse()` che registra una closure da eseguire **dopo** che la response HTTP è stata inviata al client. Su PHP-FPM, questo sfrutta `fastcgi_finish_request()` per flushare la response e continuare l'esecuzione nel processo corrente.

## Flusso

```
1. Client invia request (es. POST /checkout)
2. Controller/Action processa la logica
3. AsyncMail::send($mailable, $to) registra closure via dispatchAfterResponse
4. Response 200 inviata al client (HTTP connection chiusa)
5. PHP-FPM chiama fastcgi_finish_request()
6. Closure esegue Mail::to($to)->send($mailable)
7. Processo PHP termina
```

## Utilizzo

```php
use App\Support\AsyncMail;
use App\Mail\Public\OrderConfirmationMail;

// Non-bloccante: email inviata dopo la response
AsyncMail::send(new OrderConfirmationMail($order), $customer->email);

// Bloccante: per OTP dove serve conferma immediata
$sent = AsyncMail::sendSync(new OtpMail($code), $email);
```

## Notifiche Admin

```php
use App\Support\AdminNotifier;
use App\Mail\Admin\LowStockAlertMail;

// Invia a tutti gli admin configurati
AdminNotifier::notify(new LowStockAlertMail($product));
```

## Quando usare sync vs async

| Caso | Metodo |
|---|---|
| Conferma ordine | `AsyncMail::send()` |
| Notifica admin | `AdminNotifier::notify()` (usa async) |
| Codice OTP | `AsyncMail::sendSync()` |
| Email di test (Filament) | `AsyncMail::sendSync()` |

## Gestione errori

Ogni invio è wrappato in try/catch. In caso di errore:
1. Log in `laravel.log` con dettagli
2. Record in `activity_log` (Spatie) per visibilità in Filament
3. L'utente non vede errore (la response è già stata inviata)
4. L'admin può vedere i fallimenti in Filament > Activity Log

## Limiti

- Se il processo PHP viene killato prima del completamento (timeout FPM), la mail non parte
- Non c'è retry automatico: se fallisce, fallisce
- Per mail critiche, usare `sendSync()` e gestire il fallimento nel flusso utente

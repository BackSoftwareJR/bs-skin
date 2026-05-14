# SkinTemple — Strategia Lazy Maintenance

> Architettura sincrona: ZERO Redis, ZERO queue, ZERO cron, ZERO worker.
> Ogni operazione di manutenzione viene eseguita in modo **lazy** (pigro, on-demand) o **sincrono** (inline alla request HTTP).

## Principio guida

Su Hostinger Premium Web Hosting non disponiamo di cron job affidabili, worker di background o Redis. Ogni operazione che tradizionalmente richiederebbe un job schedulato viene invece attivata da un **trigger applicativo** (middleware, observer, controller action) con una strategia **probabilistica** o **event-driven**.

---

## Tabella operazioni

| # | Operazione | Trigger | Implementazione | Rischi residui |
|---|---|---|---|---|
| 1 | **Cleanup OTP scaduti** | Ogni richiesta OTP (login) | Prima di generare un nuovo OTP, `OtpCode::where('expires_at', '<', now())->where('used_at', null)->delete()`. Eseguito nel metodo `generateOtp()` del service. Probabilistico: 1 su 5 richieste esegue il cleanup completo, le altre solo per l'email corrente. | Accumulo lento se nessuno fa login per settimane. Mitigazione: il cleanup per email singola avviene sempre; il bulk è probabilistico. |
| 2 | **Cleanup carrelli abbandonati** | Middleware `CleanExpiredCarts` su rotte carrello/checkout | Il middleware verifica `carts.updated_at < now() - 7 days` e cancella con limit 50 per request (batch piccoli per non rallentare). `Cart::where('updated_at', '<', now()->subDays(7))->limit(50)->delete()`. | Carrelli molto vecchi restano se nessuno visita /cart per un periodo. Volume irrisorio: una riga carrello pesa pochi KB. |
| 3 | **Invio email transazionali** | Sincrono nel controller/observer | `Mail::send()` sincrono nella request HTTP. Ordine confermato → email inline in `OrderObserver::updated()`. OTP → inline in `OtpService::send()`. Utilizzo SMTP transazionale (Postmark/Resend) con timeout 5s per evitare blocchi. | Latenza +200-500ms sulla response. Se SMTP è down, l'utente vede un flash error ma l'ordine resta valido. Retry manuale da admin. |
| 4 | **Alert stock basso** | `StockMovementObserver::created()` | Dopo ogni movimento negativo, il model controlla `inventory.quantity <= threshold_low`. Se vero, invia mail admin inline. Flag `last_low_stock_alert_at` sull'inventory per evitare spam (max 1 alert ogni 24h per variante). | Se il volume di ordini è alto (>50/min), le email si accumulano. Mitigazione: throttle 1 alert/24h per SKU + digest possibile in futuro. |
| 5 | **Generazione sitemap** | On-demand da pannello admin + lazy rebuild | Bottone "Rigenera Sitemap" in Filament Settings. La sitemap viene generata come file statico `public/sitemap.xml` da `SitemapService::generate()`. Opzionale: rigenera automaticamente quando un prodotto viene pubblicato (debounced con flag `settings.sitemap_stale = true`, rigenerata alla prossima visita admin dashboard). | Sitemap potrebbe essere stale per qualche ora. Per un e-commerce piccolo-medio, Google tollera aggiornamenti non real-time. |
| 6 | **Pulizia activity_log** | Middleware probabilistico su rotte admin | 1% delle richieste admin esegue `ActivityLog::where('created_at', '<', now()->subMonths(6))->limit(100)->delete()`. Configurabile: retention period in settings. | Log molto vecchi si accumulano se gli admin non usano il pannello per mesi. Volume gestibile: ~100 byte/record. |
| 7 | **Cleanup sessioni scadute** | Middleware probabilistico globale | Laravel con driver `database` gestisce già il garbage collection via `Illuminate\Session\Middleware\StartSession` con probabilità configurabile in `session.lottery` (default `[2, 100]` = 2% delle request). Nessun intervento custom necessario. | Standard Laravel, nessun rischio aggiuntivo. |
| 8 | **Webhook pagamenti (Stripe/PayPal)** | Endpoint webhook sincrono | `POST /webhook/stripe` e `POST /webhook/paypal` processano il payload sincrono in un controller dedicato. Aggiornano `payments.status`, `orders.payment_status`. Il webhook risponde 200 immediatamente e processa. Se il processing fallisce, i provider ritentano (Stripe: fino a 3 giorni, PayPal: fino a 3 giorni). | Se il server è down durante un webhook, il provider riprova automaticamente. Nessun rischio di perdita dati grazie ai retry nativi dei provider. |
| 9 | **Sync newsletter Mailchimp** | Sincrono in `NewsletterSubscriberObserver` | Al create/update di un subscriber, se `settings.mailchimp_enabled = true`, chiama API Mailchimp inline con timeout 3s. Salva `synced_at` se successo, lascia null se fallisce. Dashboard admin mostra "non sincronizzati" per retry manuale. | Latenza +1-3s sulla subscribe. Se Mailchimp è down, il subscriber resta locale e va sincronizzato dopo. Bottone "Sync tutti" in admin. |
| 10 | **Conferma double opt-in newsletter** | Click utente su link email | L'utente clicca il link con token → controller verifica `double_opt_in_token` e `double_opt_in_expires_at`, aggiorna status a `subscribed`. Token scaduti (>48h) vengono puliti lazy: prima di validare, `NewsletterSubscriber::where('double_opt_in_expires_at', '<', now()->subDays(7))->update(['status' => 'expired'])` con limit 20. | Subscriber con token scaduto restano in stato `pending` fino a cleanup. Impatto nullo sull'esperienza utente. |
| 11 | **Cleanup token reset password** | Middleware probabilistico su login | 5% delle richieste alla pagina login esegue `PasswordResetToken::where('created_at', '<', now()->subHours(2))->delete()`. Conforme al default Laravel di 60 minuti, con margine di sicurezza. | Accumulo minimo, token pesano pochi byte. |
| 12 | **Ricalcolo totali customer** | `OrderObserver::updated()` quando status diventa `delivered` | Ricalcola `customers.total_orders` e `customers.total_spent` con query aggregata. Alternativa: incrementale nel service. | Se un ordine viene modificato retroattivamente, il totale potrebbe sfasarsi. Mitigazione: bottone "Ricalcola" in scheda cliente admin. |
| 13 | **Incremento contatori (view_count)** | Middleware o controller `ProductController::show()` | `Product::where('id', $id)->increment('view_count')` inline. Per evitare race condition su traffico alto: `DB::statement('UPDATE products SET view_count = view_count + 1 WHERE id = ?', [$id])`. | Nessun rischio significativo. Su traffico molto alto, le write incrementali sono efficienti su InnoDB. |
| 14 | **Pulizia media orfane** | On-demand da pannello admin | Bottone "Pulisci media orfane" in Filament. `Media::whereDoesntHave('model')->where('created_at', '<', now()->subDays(30))->delete()`. Elimina anche i file fisici dal disco. | Media recenti (< 30 giorni) non vengono toccate per evitare cancellazione di upload in corso. |

---

## Schema decisionale per nuove operazioni

```
L'operazione è critica per l'utente?
├── SÌ → Esegui SINCRONA nella request (es. email conferma ordine)
│         Timeout: max 5 secondi per servizio esterno
│         Fallback: log errore + flash message + retry manuale admin
│
└── NO → È legata a un evento specifico?
         ├── SÌ → Observer/Event sincrono (es. alert stock dopo movimento)
         │         Throttle se necessario
         │
         └── NO → Cleanup/manutenzione periodica
                   → Middleware probabilistico (1-5% delle request)
                   → Batch piccoli (LIMIT 20-100) per non impattare latenza
                   → Alternative: bottone manuale admin
```

## Configurazione consigliata `config/session.php`

```php
'lottery' => [2, 100], // 2% probabilità di garbage collection sessioni
```

## Note importanti

1. **Timeout SMTP**: configurare `config/mail.php` con `'timeout' => 5` per evitare che un SMTP lento blocchi la request.
2. **Monitoraggio**: l'`activity_log` registra tutte le operazioni critiche. In caso di anomalie, l'admin può verificare dal pannello.
3. **Scalabilità futura**: quando il volume ordini supererà ~100/giorno, considerare l'upgrade a un piano con cron job o l'introduzione di un servizio queue esterno (es. Amazon SQS via HTTP polling).
4. **Idempotenza**: tutte le operazioni lazy sono progettate per essere idempotenti — eseguirle più volte non causa effetti collaterali.

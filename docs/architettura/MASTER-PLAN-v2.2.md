# Master Plan v2.2 — Monolite Laravel su prr.skintemple.it

> Aggiornamento: maggio 2026. Versione definitiva post-verifica Hostinger Premium.

## 1. Sintesi v2 → v2.2

Passaggio da headless a **monolite Laravel 11 LTS** puro. Architettura completamente sincrona: ZERO cron, ZERO queue worker, ZERO Redis. Email inviate con pattern `dispatchAfterResponse`. Immagini processate con GD-only. Deploy su sottodominio `prr.skintemple.it`. Schema DB consolidato in `schema.sql` (gestito da agente DB separato). Design system gestito da agente separato.

## 2. Architettura sincrona + async-after-response

### Pattern Email (AsyncMail)
```
Request HTTP → Controller/Action → response flushed al client
                                  → fastcgi_finish_request()
                                  → Bus::dispatchAfterResponse() esegue Mail::send()
```

- `app/Support/AsyncMail::send()` per email non-bloccanti (conferme ordine, notifiche admin)
- `AsyncMail::sendSync()` per email critiche (OTP) dove serve conferma di invio
- Try/catch con log in `activity_log` per failure tracking
- Niente `Mail::queue()`, niente jobs persistenti

### Operazioni senza cron
| Feature | Strategia |
|---|---|
| Email transazionali | `dispatchAfterResponse` |
| Stock | Decremento diretto `SELECT ... FOR UPDATE` |
| Cleanup carrelli/OTP | Lazy on-demand + bottone admin |
| Sitemap XML | On-demand con cache file, invalidation via Observer |
| Backup | Backup nativo Hostinger |
| Admin notifications | `AdminNotifier` → `AsyncMail` |
| MediaLibrary conversions | Sincrone (GD) |

## 3. Compatibilità Hostinger Premium Web Hosting

| Funzionalità | Stato | Note |
|---|---|---|
| SSH | ✅ | Disponibile su Premium+ |
| Composer 2 | ✅ | Pre-installato, usare `composer2` |
| php artisan | ✅ | Via SSH |
| PHP 8.2/8.3 | ✅ | Default 8.3 nuovi siti |
| MariaDB | ✅ | Gestito da hPanel |
| Cron jobs | ✅ | Illimitati su Premium (non usati) |
| PHP mail() | ⚠️ | 10/min, 100/giorno — usiamo SMTP esterno |
| Document root custom | ✅ | Configurabile per sottodomini |
| GD | ✅ | Disponibile di default |
| Imagick | ❌ | Non garantito — usiamo GD |
| Estensioni PHP | ✅ | gd, mbstring, openssl, pdo_mysql, intl, exif, zip, curl, fileinfo, bcmath |
| Node.js runtime | ❌ | Build Vite in locale, public/build/ committato |

## 4. Strategia deploy prr.skintemple.it

1. **Struttura**: `~/domains/prr.skintemple.it/laravel/`
2. **Document root**: `~/domains/prr.skintemple.it/laravel/public` (da hPanel)
3. **Asset Vite**: `npm run build` locale → `public/build/` committato
4. **Deploy**: `deploy.sh` via SSH (down → pull → composer install → migrate → cache → up)
5. **SSL**: Attivato da hPanel (Let's Encrypt)
6. **Fallback senza SSH**: upload zip via File Manager, estrai, config manuale

## 5. Roadmap Fasi

### F0 — Setup iniziale (QUESTA FASE)
- [x] Scaffold Laravel 11 + pacchetti
- [x] Filament configurato
- [x] Struttura cartelle dominio
- [x] Interfacce e stub integrazioni
- [x] AsyncMail + AdminNotifier
- [x] .env.example completo
- [x] Documentazione architettura
- [ ] Import schema.sql (agente DB)
- [ ] Design tokens (agente Design)

### F1 — Auth + Modelli base
- Email OTP sincrona con AsyncMail
- Guard `customer` separato da `web` (admin)
- Modelli Eloquent da schema.sql

### F2 — Catalogo
- Prodotti, varianti, categorie, brand
- MediaLibrary con conversioni sincrone GD
- Upload validato: WEBP/JPG/PNG, max 800KB, max 2400×2400

### F3 — CMS + SEO
- Pagine con Filament Builder (blocchi JSON)
- Sitemap on-demand con cache
- Schema.org via spatie/schema-org

### F4 — Filament Admin completo
- Resources per tutti i modelli
- Widget dashboard (ordini, revenue, low stock)
- Pagina manutenzione (cleanup lazy)

### F5 — Carrello + Coupon
- Carrello session/DB
- Promozioni e coupon

### F6 — Checkout + Ordini
- Stock con `SELECT ... FOR UPDATE`
- Email conferma ordine via AsyncMail
- Pagamenti manuali (bonifico) attivi, Stripe/PayPal predisposti

### F7 — Admin operativo
- Gestione ordini, spedizioni, resi
- "Reinvia email" on-demand

### F8 — Frontend Blade
- Layout pubblico responsive
- Componenti Livewire per carrello, checkout, ricerca
- Alpine.js per micro-interazioni

### F9 — Integrazioni scaffold
- Attivazione Stripe/PayPal/Mailchimp quando il cliente fornisce credenziali
- Webhook handler sincrono

### F10 — Hardening
- Rate limiting, CSRF, XSS sanitization
- AdminNotifier per errori critici
- Backup via snapshot Hostinger

### F11 — QA + Go-Live
- Test e2e con deploy.sh
- Redirect `skintemple.it` → `prr.skintemple.it`

### F12 — Post-launch
- Analytics, performance tuning
- Fatturazione elettronica (se richiesta)

## 6. Rischi e mitigazioni

| Rischio | Mitigazione |
|---|---|
| Email async fallisce silenziosamente | Try/catch + activity_log + AdminNotifier |
| Webhook Stripe timeout | Processamento sincrono, Stripe riprova fino a 3 giorni |
| Cleanup lazy non eseguito | Dimensione dati piccola, bottone Manutenzione in Filament |
| Stock race condition | `SELECT ... FOR UPDATE` in transazione |
| Build Vite impossibile su Hostinger | public/build/ committato |
| Conversioni immagini lente | Sincrone GD, accettabile per admin |

## 7. Domande aperte

1. Provider email scelto dal cliente (Resend? Brevo? Altro SMTP)?
2. Logo SVG definitivo disponibile?
3. Fatturazione elettronica SDI al lancio o post-launch?
4. Dominio definitivo o `prr.skintemple.it` permanente?

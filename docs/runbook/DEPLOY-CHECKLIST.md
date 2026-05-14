# Checklist Deploy SkinTemple su Hostinger Premium

## PRE-REQUISITI (una tantum)
- [ ] Account Hostinger Premium attivo con SSH abilitato
- [ ] Dominio `skintemple.it` puntato a Hostinger
- [ ] Sottodominio `prr.skintemple.it` creato in hPanel → Domains → Subdomains
- [ ] PHP 8.3 abilitato per `prr.skintemple.it` in hPanel → PHP Configuration
- [ ] Database MariaDB creato in hPanel → Databases → MySQL Databases
  - Nome DB: `skintemple_prod` (o simile)
  - Utente dedicato con tutti i privilegi sul DB
  - Annotare: DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD
- [ ] Connessione SSH verificata: `ssh u123456789@prr.skintemple.it`

## STEP 1 — Import Database
1. Vai su hPanel → phpMyAdmin
2. Seleziona il database `skintemple_prod`
3. Clicca "Importa"
4. Seleziona il file `docs/database/schema.sql`
5. Clicca "Esegui"
6. Verifica: dovresti vedere ~39 tabelle create

## STEP 2 — Clone Repository su Hostinger
```bash
ssh u123456789@prr.skintemple.it
cd ~/domains/prr.skintemple.it/
git clone https://github.com/TUO-ACCOUNT/skintemple-v2.git laravel
cd laravel
```

## STEP 3 — Configura Document Root
In hPanel → Domains → prr.skintemple.it → Manage → Document Root:
- Cambia da `public_html` a `laravel/public`
- Salva

## STEP 4 — Configura Ambiente
```bash
cd ~/domains/prr.skintemple.it/laravel
cp .env.example .env
nano .env  # o vim .env
```

**Valori da compilare nel .env**:
```
APP_KEY=          # verrà generato al passo successivo
APP_URL=https://prr.skintemple.it
APP_ENV=production
APP_DEBUG=false

DB_HOST=127.0.0.1  # o l'host fornito da Hostinger
DB_DATABASE=skintemple_prod
DB_USERNAME=TUO_UTENTE_DB
DB_PASSWORD=TUA_PASSWORD_DB

MAIL_MAILER=smtp
MAIL_HOST=smtp.resend.com      # o il tuo provider SMTP
MAIL_PORT=465
MAIL_USERNAME=resend            # o le tue credenziali
MAIL_PASSWORD=re_xxxxxxxxxxxx   # API key Resend
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS=noreply@skintemple.it
MAIL_FROM_NAME="SkinTemple"

SKINTEMPLE_ADMIN_EMAIL_PRIMARY=jrovera05@gmail.com
SKINTEMPLE_ADMIN_EMAIL_SECONDARY=backsoftware.crm@gmail.com
```

## STEP 5 — Installa Dipendenze e Configura App
```bash
cd ~/domains/prr.skintemple.it/laravel
composer install --no-dev --optimize-autoloader --prefer-dist
php artisan key:generate
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
php artisan filament:cache-components
```

## STEP 6 — Seed Post-Install
```bash
php artisan db:seed --class=PostInstallSeeder
```
Output atteso: "Super admin creato: jrovera05@gmail.com" + avviso cambio password.

## STEP 7 — Permessi file
```bash
chmod -R 755 storage
chmod -R 755 bootstrap/cache
```

## STEP 8 — Verifica SSL
In hPanel → SSL → Verifica che SSL sia attivo per `prr.skintemple.it`.
Se non attivo: hPanel → SSL → Installa (Let's Encrypt).

## STEP 9 — Test Go-Live
- [ ] `https://prr.skintemple.it/` — Mostra homepage SkinTemple (non pagina Hostinger)
- [ ] `https://prr.skintemple.it/healthcheck` — Risponde `{"status":"ok"}`
- [ ] `https://prr.skintemple.it/admin` — Reindirizza a `/admin/login`
- [ ] Login admin con `jrovera05@gmail.com` + password `changeme123!` funziona
- [ ] **CAMBIA SUBITO LA PASSWORD dal profilo Filament**
- [ ] `https://prr.skintemple.it/sitemap.xml` — XML generato

## STEP 10 — Post-Deploy
- [ ] Cambia password super_admin da `/admin/profile`
- [ ] Configura email SMTP reale nelle impostazioni (o nel .env)
- [ ] Carica logo definitivo: `public/img/brand/logo.svg` (o `.png`)
- [ ] Verifica invio email OTP: `https://prr.skintemple.it/account/login` → inserisci email → controlla ricezione
- [ ] Inserisci prodotti reali da `/admin/products`
- [ ] Configura metodi pagamento da `/admin/payment-methods`
- [ ] Verifica Google Search Console: submit sitemap
- [ ] Abilita Cloudflare se decidi di usarlo (opzionale)

## AGGIORNAMENTI SUCCESSIVI
```bash
# Script deploy.sh disponibile nella root del progetto
chmod +x deploy.sh
./deploy.sh
```

## ROLLBACK
In caso di problemi:
```bash
cd ~/domains/prr.skintemple.it/laravel
git log --oneline -5   # trova il commit precedente
git checkout <commit-hash>
php artisan config:cache && route:cache && view:cache
```
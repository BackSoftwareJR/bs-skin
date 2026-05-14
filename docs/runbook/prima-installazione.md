# Prima Installazione — Checklist

## Prerequisiti
- [ ] Piano Hostinger Premium Web Hosting attivo
- [ ] Sottodominio `prr.skintemple.it` creato in hPanel
- [ ] SSH abilitato

## Passo 1: Database
- [ ] hPanel > Database > Crea nuovo database MariaDB
- [ ] Annotare: nome database, username, password
- [ ] Aprire phpMyAdmin
- [ ] Importare `docs/database/schema.sql` (tab Importa)

## Passo 2: Repository
- [ ] SSH nel server
- [ ] `cd ~/domains/prr.skintemple.it`
- [ ] `git clone <URL_REPO> laravel`
- [ ] `cd laravel`

## Passo 3: Configurazione
- [ ] `cp .env.example .env`
- [ ] Editare `.env` con:
  - `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` (dal passo 1)
  - `MAIL_HOST`, `MAIL_USERNAME`, `MAIL_PASSWORD` (credenziali SMTP)
  - `APP_KEY` (generare al passo 4)

## Passo 4: Laravel Setup
- [ ] `composer2 install --no-dev --optimize-autoloader`
- [ ] `php artisan key:generate`
- [ ] `php artisan storage:link`
- [ ] `php artisan config:cache`
- [ ] `php artisan route:cache`
- [ ] `php artisan view:cache`

## Passo 5: hPanel
- [ ] Configurare PHP 8.3 per il sito
- [ ] Document root → `domains/prr.skintemple.it/laravel/public`
- [ ] Attivare SSL (Let's Encrypt)

## Passo 6: Verifica
- [ ] Aprire `https://prr.skintemple.it/` → deve mostrare homepage
- [ ] Aprire `https://prr.skintemple.it/admin/login` → pagina login Filament
- [ ] Login con `admin@skintemple.it` / `password`
- [ ] **CAMBIARE IMMEDIATAMENTE LA PASSWORD**

## Passo 7: Post-installazione
- [ ] Verificare invio email test da Filament
- [ ] Controllare `storage/logs/laravel.log` sia scrivibile
- [ ] Rendere eseguibile deploy script: `chmod +x deploy.sh`

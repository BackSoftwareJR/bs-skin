# Hostinger Bridge Setup — SkinTemple

## Il problema
Hostinger Premium Web Hosting ha il document root FISSO in `public_html/`.
Laravel invece richiede che il web server punti a `public/`.

## La soluzione: Bridge Pattern

### Struttura finale sul server
```
~/domains/prr.skintemple.it/
├── laravel/              ← git clone qui
│   ├── app/
│   ├── bootstrap/
│   ├── config/
│   ├── public/
│   │   ├── build/        ← assets compilati (nel repo)
│   │   └── index.php     ← NON usato direttamente
│   ├── storage/
│   └── vendor/           ← generato da composer install
└── public_html/          ← document root Hostinger (WEB ESPOSTO)
    ├── index.php         ← bridge → punta a ../laravel/
    ├── .htaccess         ← rewrite rules Laravel
    ├── robots.txt
    └── storage → symlink → ../laravel/storage/app/public
```

## STEP 1 — Clone repository

```bash
ssh u951446261@prr.skintemple.it

cd ~/domains/prr.skintemple.it/
git clone https://github.com/BackSoftwareJR/bs-skin.git laravel
```

## STEP 2 — Carica i file bridge in public_html

```bash
cd ~/domains/prr.skintemple.it/public_html/

# Scarica/copia il bridge index.php (o usa nano per crearlo)
nano index.php
# (incolla il contenuto del bridge index.php)

nano .htaccess
# (incolla il contenuto .htaccess)
```

## STEP 3 — Crea il symlink storage

```bash
cd ~/domains/prr.skintemple.it/public_html/
ln -s ../laravel/storage/app/public storage
```

## STEP 4 — Aggiungi link build assets (se non symlink)

I file in `laravel/public/build/` sono già nel repo, quindi sono già accessibili tramite il bridge.
Verifica che esistano:
```bash
ls ~/domains/prr.skintemple.it/laravel/public/build/
```

## STEP 5 — Configura .env

```bash
cd ~/domains/prr.skintemple.it/laravel/
cp .env.example .env
nano .env
```

Modifica:
- `APP_KEY=` → vuoto (artisan lo genera)
- `DB_HOST=` → verifica in hPanel → Databases → il valore corretto (spesso `127.0.0.1`)

## STEP 6 — Installa dipendenze e ottimizza

```bash
cd ~/domains/prr.skintemple.it/laravel/
composer install --no-dev --optimize-autoloader --prefer-dist
php artisan key:generate
php artisan storage:link   # crea anche public/storage symlink
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
php artisan filament:cache-components
chmod -R 755 storage bootstrap/cache
```

## STEP 7 — Import Database

In hPanel → phpMyAdmin:
1. Seleziona database `u951446261_sktdb`
2. Tab Importa → seleziona `docs/database/schema.sql`
3. Esegui

Poi via SSH:
```bash
# Crea tabella sessioni (SESSION_DRIVER=database)
php artisan session:table
php artisan migrate --force
```

## STEP 8 — Seed super admin

```bash
php artisan db:seed --class=PostInstallSeeder
```

## STEP 9 — Test

- https://prr.skintemple.it/ → homepage
- https://prr.skintemple.it/admin → Filament login

## AGGIORNAMENTI FUTURI

```bash
cd ~/domains/prr.skintemple.it/laravel/
git pull origin main
composer install --no-dev --optimize-autoloader
php artisan config:cache && php artisan route:cache && php artisan view:cache && php artisan filament:cache-components
```

## NOTA SICUREZZA

I file sensibili dell'app (config/, app/, routes/, .env) si trovano in `laravel/`
che è FUORI dal document root `public_html/`. Il web server non può mai servirli
direttamente. Solo `public_html/` è esposto al web.
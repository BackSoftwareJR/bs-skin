# Deploy su Hostinger Premium Web Hosting

## Percorso A: Deploy con SSH (consigliato)

### Setup iniziale

```bash
# 1. Abilita SSH da hPanel > Avanzato > Accesso SSH
# 2. Connetti
ssh u123456789@xxx.hostinger.com -p 65002

# 3. Clona repository
cd ~/domains/prr.skintemple.it
git clone https://github.com/OWNER/REPO.git laravel

# 4. Setup .env
cd laravel
cp .env.example .env
nano .env  # inserisci credenziali reali

# 5. Installa dipendenze
composer2 install --no-dev --optimize-autoloader

# 6. Setup Laravel
php artisan key:generate
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

### Configurazione hPanel

1. **Siti web** > `prr.skintemple.it` > **Avanzato** > **Configurazione PHP** → PHP 8.3
2. **Domini** > `prr.skintemple.it` > **Document root** → `domains/prr.skintemple.it/laravel/public`
3. **SSL** → attiva Let's Encrypt per `prr.skintemple.it`
4. **Database** → crea database MariaDB, importa `schema.sql` via phpMyAdmin

### Deploy aggiornamenti

```bash
ssh u123456789@xxx.hostinger.com -p 65002
cd ~/domains/prr.skintemple.it/laravel
bash deploy.sh
```

## Percorso B: Deploy senza SSH (fallback FTP)

Se SSH non è disponibile o limitato:

1. **Locale**: esegui `composer install --no-dev` e `npm run build`
2. **Zip**: comprimi l'intero progetto (escluso `node_modules/`)
3. **hPanel** > File Manager: upload zip in `~/domains/prr.skintemple.it/`
4. **Estrai** zip nel file manager
5. **Rinomina** cartella estratta in `laravel`
6. **Configura** document root da hPanel a `laravel/public`
7. Crea `.env` manualmente via File Manager
8. Accedi a `https://prr.skintemple.it/` per verificare

### Aggiornamenti via FTP
1. Locale: `composer install --no-dev`, `npm run build`
2. Connetti via FTP (FileZilla, credenziali da hPanel > Account FTP)
3. Upload file modificati sovrascrivendo
4. Accedi via browser a `/artisan-web` (se implementato) o phpMyAdmin per migrazioni

## Comandi utili SSH Hostinger

```bash
# Composer con versione PHP specifica
/opt/alt/php83/usr/bin/php /usr/local/bin/composer2 install

# Artisan con PHP specifico
/opt/alt/php83/usr/bin/php artisan migrate --force

# Controllare versione PHP
php -v

# Spazio disco
du -sh ~/domains/prr.skintemple.it/laravel/
```

## Troubleshooting

| Problema | Soluzione |
|---|---|
| 500 Internal Server Error | Controlla `storage/logs/laravel.log`, verifica permessi `storage/` e `bootstrap/cache/` |
| Composer memory error | `php -d memory_limit=-1 /usr/local/bin/composer2 install` |
| PHP version mismatch | Usa path esplicito: `/opt/alt/php83/usr/bin/php` |
| Storage link non funziona | `php artisan storage:link` oppure crea symlink manuale |
| CSS/JS non caricati | Verifica `public/build/` esista e sia committato |

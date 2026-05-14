#!/bin/bash
set -e

# SkinTemple — Deploy script per Hostinger Premium Web Hosting
# Uso: ssh user@host "cd ~/domains/prr.skintemple.it/laravel && bash deploy.sh"

echo "=== SkinTemple Deploy ==="
echo "$(date '+%Y-%m-%d %H:%M:%S')"

echo "[1/7] Maintenance mode ON..."
php artisan down --retry=60 || true

echo "[2/7] Git pull..."
git pull origin main

echo "[3/7] Composer install (no-dev)..."
composer2 install --no-dev --no-interaction --prefer-dist --optimize-autoloader

echo "[4/7] Migrations..."
php artisan migrate --force

echo "[5/7] Storage link..."
php artisan storage:link 2>/dev/null || true

echo "[6/7] Cache ottimizzazione..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
php artisan filament:cache-components 2>/dev/null || true
php artisan icons:cache 2>/dev/null || true

echo "[7/7] Maintenance mode OFF..."
php artisan up

echo "=== Deploy completato ==="

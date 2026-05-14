# SkinTemple — E-commerce Monolite Laravel

E-commerce B2B/B2C per SkinTemple (tecnologie multifunzione per centri estetici + cosmetici skincare Made in Italy).

**Stack**: Laravel 11 LTS · Filament 3 · Livewire 3 · Tailwind CSS · Alpine.js · MariaDB
**Hosting**: Hostinger Premium Web Hosting su `prr.skintemple.it`

## Requisiti

- PHP 8.2+ (target 8.3)
- Composer 2
- Node.js 18+ (solo per build locale)
- MariaDB 10.5+

## Quickstart locale

```bash
# Clona e installa
git clone <URL> && cd new-ecommerce
composer install
npm install

# Configura ambiente
cp .env.example .env
php artisan key:generate

# Database (configura .env con credenziali DB, poi importa schema.sql)
# php artisan storage:link

# Build assets
npm run build    # produzione
npm run dev      # sviluppo con hot reload

# Avvia
php artisan serve
# Apri http://localhost:8000 e http://localhost:8000/admin
```

## Logo

Il logo attuale è un placeholder SVG inline in `resources/views/components/brand-logo.blade.php`.

Per usare il logo definitivo:
1. Salva il file come `public/img/brand/logo.svg`
2. Il componente `brand-logo` lo rileverà automaticamente via `file_exists()`
3. Il file PNG originale è disponibile in `public/img/brand/logo_skinTemple.png`

## Documentazione

| Documento | Percorso |
|---|---|
| Master Plan v2.2 | `docs/architettura/MASTER-PLAN-v2.2.md` |
| Convenzioni codice | `docs/architettura/CONVENZIONI.md` |
| Struttura progetto | `docs/architettura/STRUTTURA-PROGETTO.md` |
| Pattern email async | `docs/architettura/EMAIL-ASYNC-PATTERN.md` |
| Integrazioni future | `docs/architettura/INTEGRAZIONI-PREDISPOSTE.md` |
| Deploy Hostinger | `docs/runbook/deploy.md` |
| Prima installazione | `docs/runbook/prima-installazione.md` |
| Schema DB | `docs/database/` |
| Design System | `docs/design-system/` |
| Specifiche originali | `docs/specs/` |

## Deploy

```bash
# Via SSH su Hostinger
ssh user@host "cd ~/domains/prr.skintemple.it/laravel && bash deploy.sh"
```

Vedi `docs/runbook/deploy.md` per la procedura completa.

## Contatti

- **SkinTemple** — info@skintemple.it
- Strada Santa Vittoria 11, 10024 Moncalieri (TO)
- P.IVA: 11863510019

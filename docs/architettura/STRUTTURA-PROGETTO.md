# Struttura Progetto SkinTemple

```
new-ecommerce/
├── app/
│   ├── Actions/                    # Azioni che scrivono stato (single responsibility)
│   │   ├── Cart/
│   │   ├── Checkout/
│   │   ├── Order/
│   │   ├── Stock/
│   │   ├── Otp/
│   │   └── Newsletter/
│   ├── Contracts/                  # Interfacce per integrazioni esterne
│   │   ├── PaymentGateway.php
│   │   ├── NewsletterProvider.php
│   │   ├── ShippingProvider.php
│   │   ├── InvoiceProvider.php
│   │   └── SearchEngine.php
│   ├── Enums/                      # PHP 8.1+ enums
│   ├── Exceptions/
│   │   └── IntegrationNotEnabledException.php
│   ├── Filament/                   # Admin panel (Filament 3)
│   │   ├── Resources/
│   │   ├── Pages/
│   │   ├── Widgets/
│   │   └── Forms/Blocks/           # Block builder per CMS
│   ├── Http/Controllers/
│   ├── Integrations/               # Implementazioni concrete dei Contracts
│   │   ├── Payment/                # Manual, Stripe stub, PayPal stub
│   │   ├── Newsletter/             # Database, Mailchimp stub
│   │   ├── Invoice/                # None, Aruba stub, FattureInCloud stub
│   │   └── Shipping/               # Manual
│   ├── Livewire/Public/            # Componenti Livewire 3 frontend
│   │   ├── Cart/
│   │   ├── Checkout/
│   │   ├── Catalog/
│   │   ├── Account/
│   │   └── Search/
│   ├── Mail/
│   │   ├── Public/                 # Email ai clienti
│   │   └── Admin/                  # Email agli admin
│   ├── Models/                     # Eloquent models
│   ├── Notifications/Admin/
│   ├── Providers/
│   │   ├── AppServiceProvider.php
│   │   ├── IntegrationsServiceProvider.php
│   │   └── Filament/AdminPanelProvider.php
│   ├── Services/                   # Logica pura, query, calcoli
│   │   ├── Pricing/
│   │   ├── Search/
│   │   ├── Sitemap/
│   │   └── Notifications/
│   ├── Support/                    # Helper generici
│   │   ├── AsyncMail.php           # Email post-response senza queue
│   │   └── AdminNotifier.php       # Notifiche admin configurabili
│   └── View/Components/Public/
├── bootstrap/
│   └── providers.php               # Service providers registrati
├── config/
│   ├── app.php                     # Locale it, timezone Europe/Rome
│   ├── skintemple.php              # Config specifiche progetto
│   └── ...
├── database/
│   └── seeders/Initial/
├── docs/
│   ├── architettura/               # Piano, convenzioni, pattern
│   ├── database/                   # Schema (gestito da agente DB)
│   ├── design-system/              # Tokens (gestito da agente Design)
│   ├── runbook/                    # Deploy, installazione
│   └── specs/                      # Specifiche progetto originali
├── lang/
│   └── it.json                     # Traduzioni custom italiane
├── public/
│   ├── build/                      # Asset Vite compilati (committato)
│   └── img/brand/                  # Logo e brand assets
├── resources/
│   ├── css/app.css                 # Tailwind + Google Fonts
│   ├── js/app.js                   # Alpine.js
│   └── views/
│       ├── components/
│       │   ├── brand-logo.blade.php
│       │   ├── public/
│       │   ├── admin/
│       │   └── emails/
│       ├── layouts/
│       ├── public/                 # Pagine frontend
│       ├── blocks/                 # Blade partials per blocchi CMS
│       └── emails/
├── routes/
│   └── web.php
├── deploy.sh                       # Script deploy Hostinger SSH
├── .env.example                    # Template completo variabili ambiente
└── tailwind.config.js              # Placeholder (design tokens TODO)
```

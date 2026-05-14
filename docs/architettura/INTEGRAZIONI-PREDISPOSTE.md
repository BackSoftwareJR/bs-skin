# Integrazioni Predisposte

Tutte le integrazioni esterne sono dietro interfacce (`app/Contracts/`) con implementazioni intercambiabili. Il binding è gestito da `IntegrationsServiceProvider` basato su config/env.

## Stripe (Pagamenti)

**Interfaccia**: `App\Contracts\PaymentGateway`
**Implementazione**: `App\Integrations\Payment\StripePaymentGateway`
**Stato**: Stub (lancia `IntegrationNotEnabledException`)

### Attivazione
1. Crea account su [dashboard.stripe.com](https://dashboard.stripe.com)
2. Ottieni Public Key, Secret Key, Webhook Secret
3. Configura `.env`:
   ```
   STRIPE_ENABLED=true
   STRIPE_PUBLIC_KEY=pk_live_xxx
   STRIPE_SECRET_KEY=sk_live_xxx
   STRIPE_WEBHOOK_SECRET=whsec_xxx
   ```
4. Installa SDK: `composer require stripe/stripe-php`
5. Implementa i metodi nello stub
6. Configura webhook URL: `https://prr.skintemple.it/webhooks/stripe`

## PayPal (Pagamenti)

**Implementazione**: `App\Integrations\Payment\PayPalPaymentGateway`
**Stato**: Stub

### Attivazione
1. Account business su [developer.paypal.com](https://developer.paypal.com)
2. Configura `.env`:
   ```
   PAYPAL_ENABLED=true
   PAYPAL_CLIENT_ID=xxx
   PAYPAL_CLIENT_SECRET=xxx
   PAYPAL_MODE=live
   ```
3. Installa SDK: `composer require paypal/rest-api-sdk-php` (o checkout SDK v2)
4. Implementa stub

## Mailchimp (Newsletter)

**Interfaccia**: `App\Contracts\NewsletterProvider`
**Implementazione**: `App\Integrations\Newsletter\MailchimpNewsletterProvider`

### Attivazione
1. Crea account [mailchimp.com](https://mailchimp.com)
2. Genera API key da Account > API keys
3. Configura `.env`:
   ```
   MAILCHIMP_ENABLED=true
   MAILCHIMP_API_KEY=xxx-us21
   MAILCHIMP_AUDIENCE_ID=xxx
   MAILCHIMP_SERVER_PREFIX=us21
   ```
4. Installa: `composer require mailchimp/marketing`
5. Implementa sync bidirezionale

## Aruba (Fatturazione Elettronica)

**Interfaccia**: `App\Contracts\InvoiceProvider`
**Implementazione**: `App\Integrations\Invoice\ArubaInvoiceProvider`

### Attivazione
1. Contratto Aruba Fatturazione Elettronica
2. Credenziali API da pannello Aruba
3. `.env`:
   ```
   INVOICE_PROVIDER=aruba
   ARUBA_ENABLED=true
   ARUBA_USERNAME=xxx
   ARUBA_PASSWORD=xxx
   ```
4. Implementa chiamate SOAP/REST Aruba

## Fatture in Cloud (Fatturazione Elettronica)

**Implementazione**: `App\Integrations\Invoice\FattureInCloudInvoiceProvider`

### Attivazione
1. Account [fattureincloud.it](https://fattureincloud.it)
2. API key da Impostazioni > API
3. `.env`:
   ```
   INVOICE_PROVIDER=fatture_in_cloud
   FIC_ENABLED=true
   FIC_API_KEY=xxx
   FIC_COMPANY_ID=xxx
   ```
4. Installa: `composer require fattureincloud/fattureincloud-php-sdk`

# 13 — Checklist di Implementazione

Guida step-by-step per l'agente Frontend che implementera i componenti del design system.

---

## Fase 0: Preparazione

### 0.1 Copia Tailwind Config
```bash
cp docs/design-system/06-tailwind.config.js tailwind.config.js
```
Verifica che il file sia compatibile con la versione Tailwind installata da Foundation agent.

### 0.2 Installa plugin Tailwind
```bash
npm install -D @tailwindcss/forms @tailwindcss/typography @tailwindcss/aspect-ratio
```

### 0.3 Installa plugin Alpine.js
```bash
npm install @alpinejs/focus @alpinejs/collapse
```

---

## Fase 1: CSS e JS base

### 1.1 Aggiorna `resources/css/app.css`

```css
@import 'tailwindcss/base';
@import 'tailwindcss/components';
@import 'tailwindcss/utilities';

@layer base {
    html {
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }

    body {
        @apply font-sans text-ink-soft bg-surface;
    }

    ::selection {
        @apply bg-brand-primary-soft text-brand-primary-900;
    }

    /* Scrollbar sottile */
    ::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }
    ::-webkit-scrollbar-thumb {
        @apply bg-neutral-300 rounded-full;
    }
    ::-webkit-scrollbar-track {
        @apply bg-transparent;
    }
}

@layer utilities {
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }
    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
}

/* Riduzione movimento */
@media (prefers-reduced-motion: reduce) {
    *, *::before, *::after {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
        scroll-behavior: auto !important;
    }
}
```

### 1.2 Aggiorna `resources/js/app.js`

```javascript
import Alpine from 'alpinejs';
import focus from '@alpinejs/focus';
import collapse from '@alpinejs/collapse';

Alpine.plugin(focus);
Alpine.plugin(collapse);

Alpine.store('cart', {
    count: 0,
    total: 0,
    open: false,
    toggle() { this.open = !this.open; },
});

window.Alpine = Alpine;
Alpine.start();
```

---

## Fase 2: Layout principale

### 2.1 Crea `resources/views/layouts/app.blade.php`
Seguire la specifica in `07-components-spec.md` sezione 1.1 `app-layout`.

Includere:
- [x] Google Fonts preconnect + stylesheet
- [x] Vite assets
- [x] Livewire styles/scripts
- [x] Skip-to-content link
- [x] Announcement bar
- [x] Header
- [x] Main con `id="main-content"`
- [x] Footer
- [x] Bottom tab bar
- [x] Toast container
- [x] Cart drawer Livewire
- [x] Search overlay
- [x] Stack scripts

---

## Fase 3: Componenti in ordine di dipendenza

Creare i componenti Blade seguendo l'ordine: **atomi → molecole → organismi → template → pagine**.

### 3.1 Atomi (nessuna dipendenza)
Ordine di creazione:
1. `spinner`
2. `icon`
3. `divider`
4. `kbd`
5. `skeleton`
6. `badge`
7. `avatar`
8. `button` (dipende da `spinner`)
9. `link`
10. `chip`
11. `tooltip`

### 3.2 Form (dipendono da atomi)
1. `input`
2. `textarea`
3. `select`
4. `checkbox`
5. `radio`
6. `switch`
7. `field-wrapper` (dipende da `icon`)
8. `form-section`
9. `form-actions`

### 3.3 Layout (dipendono da atomi)
1. `container`
2. `section`
3. `announcement-bar`
4. `public-footer` (con sotto-componenti `footer-column`, `footer-link`)
5. `public-header` (dipende da mega-menu, icon)
6. `bottom-tab-bar`
7. Layout `app.blade.php`

### 3.4 Navigazione (dipendono da layout + atomi)
1. `breadcrumb`
2. `pagination`
3. `mega-menu-column`
4. `mega-menu`
5. `mobile-nav-drawer`

### 3.5 Card e Listing (dipendono da atomi + badge)
1. `product-card`
2. `product-grid`
3. `product-list-item`

### 3.6 Filtri (dipendono da atomi + form)
1. `filter-checkbox`
2. `filter-color-swatch`
3. `filter-price-range`
4. `filter-group`
5. `filter-sidebar`
6. `filter-active-pills`

### 3.7 Dettaglio prodotto (dipendono da atomi + form)
1. `quantity-stepper`
2. `variant-selector`
3. `product-gallery`
4. `add-to-cart-button`
5. `wishlist-toggle`
6. `share-buttons`
7. `product-tabs`
8. `product-info`
9. `related-products`
10. `recently-viewed`

### 3.8 Carrello (dipendono da atomi + form)
1. `empty-cart-state`
2. `cart-item`
3. `coupon-input`
4. `cart-summary`
5. `cart-drawer`

### 3.9 Checkout (dipendono da form + atomi)
1. `checkout-stepper`
2. `checkout-step`
3. `address-form`
4. `shipping-method-card`
5. `payment-method-card`
6. `terms-checkbox`
7. `order-summary-sticky`

### 3.10 Account (dipendono da form + card)
1. `order-status-badge`
2. `order-card`
3. `address-card`
4. `account-sidebar-nav`
5. `wishlist-grid`
6. `profile-form`

### 3.11 Feedback (dipendono da atomi)
1. `alert`
2. `inline-message`
3. `toast`
4. `confirm-modal`

### 3.12 Trust signals
1. `feature-bar`
2. `made-in-italy-badge`
3. `secure-payment-icons`
4. `einvoice-badge`

### 3.13 Glass effects
1. `glass-card`
2. `glass-overlay`

### 3.14 Search (dipendono da atomi + form)
1. `search-input`
2. `search-result-product`
3. `search-result-page`
4. `search-empty-state`
5. `search-recent`
6. `search-overlay`

### 3.15 CMS Blocks (dipendono da layout + atomi + card)
1. `block-spacer`
2. `block-text`
3. `block-image`
4. `block-text-quote`
5. `block-video`
6. `block-html`
7. `block-cta`
8. `block-hero`
9. `block-features`
10. `block-features-list`
11. `block-slider`
12. `block-newsletter`
13. `block-testimonial`
14. `block-brand-grid`
15. `block-contact-form`
16. `block-product-grid`
17. `block-renderer`

### 3.16 Blog
1. `blog-card`
2. `blog-author-bio`
3. `blog-toc`

### 3.17 Mobile
1. `mobile-search-button`

---

## Fase 4: Componenti Livewire

Seguire `08-livewire-components-spec.md`. Ordine:

1. `Newsletter\SubscribeForm` (indipendente, semplice)
2. `Cart\WishlistToggle` (indipendente)
3. `Cart\AddToCartButton` (emette eventi)
4. `Cart\CartDrawer` (ascolta eventi, complesso)
5. `Catalog\ProductSearch` (indipendente)
6. `Catalog\ProductFilters` (URL sync)
7. `Catalog\ProductGrid` (ascolta filtri)
8. `Catalog\QuickView` (ascolta eventi)
9. `Account\OtpLoginForm`
10. `Account\OtpVerifyForm`
11. `Account\ProfileEditor`
12. `Account\AddressManager`
13. `Checkout\AddressForm`
14. `Checkout\ShippingMethodSelector`
15. `Checkout\PaymentMethodSelector`
16. `Checkout\CheckoutFlow` (orchestra tutti i sub-componenti checkout)

---

## Fase 5: Block Renderer CMS

1. Implementare `block-renderer` con switch su tutti i tipi blocco
2. Testare ogni blocco individualmente con dati fittizi
3. Verificare che la pagina CMS generica (`pages/{slug}`) renderizzi correttamente

---

## Fase 6: Pagine

Creare le route e le view per ogni pagina:

1. `resources/views/pages/home.blade.php` — Homepage
2. `resources/views/pages/shop/index.blade.php` — Shop catalogo
3. `resources/views/pages/shop/show.blade.php` — Dettaglio prodotto
4. `resources/views/pages/shop/category.blade.php` — Categoria/Macroarea
5. `resources/views/pages/cart.blade.php` — Carrello (pagina completa)
6. `resources/views/pages/checkout.blade.php` — Checkout
7. `resources/views/pages/account/index.blade.php` — Dashboard account
8. `resources/views/pages/account/orders.blade.php` — Ordini
9. `resources/views/pages/account/addresses.blade.php` — Indirizzi
10. `resources/views/pages/account/wishlist.blade.php` — Lista desideri
11. `resources/views/pages/account/profile.blade.php` — Profilo
12. `resources/views/pages/account/login.blade.php` — Login OTP
13. `resources/views/pages/blog/index.blade.php` — Blog index
14. `resources/views/pages/blog/show.blade.php` — Blog dettaglio
15. `resources/views/pages/cms.blade.php` — Pagina statica CMS (`/pages/{slug}`)
16. `resources/views/errors/404.blade.php` — 404
17. `resources/views/errors/500.blade.php` — 500

---

## Fase 7: Testing responsive

Testare ogni pagina ai seguenti breakpoint:

| Viewport | Larghezza | Cosa verificare |
|----------|-----------|-----------------|
| Mobile piccolo | 320px | Nessun overflow orizzontale, testo leggibile, CTA raggiungibili |
| Mobile standard | 375px | Layout principale, bottom tab bar, drawer |
| Tablet portrait | 768px | Griglia 2-3 colonne, sidebar nascosta/visibile |
| Tablet landscape / Desktop piccolo | 1024px | Transizione mobile→desktop, mega menu, sidebar filtri |
| Desktop | 1280px | Layout completo, tutte le colonne |
| Desktop largo | 1536px | Contenuto centrato, nessun stretching eccessivo |

---

## Fase 8: Audit Lighthouse

Target per ogni pagina:

| Metrica | Target |
|---------|--------|
| Performance | >= 90 |
| Accessibility | >= 90 |
| Best Practices | >= 90 |
| SEO | >= 90 |

### Checklist performance
- [ ] Immagini con `loading="lazy"` e `decoding="async"`
- [ ] Font con `display=swap`
- [ ] CSS e JS minificati (Vite)
- [ ] Nessun layout shift (CLS) — specificare `width` e `height` su immagini
- [ ] Nessun JS bloccante nel `<head>`

### Checklist accessibilita
- [ ] Tutti i contrasti verificati (vedi `12-accessibility.md`)
- [ ] Focus visible su ogni elemento interattivo
- [ ] Skip-to-content funzionante
- [ ] Heading sequenziali (h1 → h2 → h3)
- [ ] Alt text su tutte le immagini
- [ ] Form label su tutti i campi
- [ ] `aria-label` su icon-only buttons
- [ ] Navigazione completa da tastiera

---

## Riepilogo fasi

| Fase | Descrizione | Dipendenze |
|------|-------------|------------|
| 0 | Preparazione config e deps | Foundation agent completato |
| 1 | CSS e JS base | Fase 0 |
| 2 | Layout principale | Fase 1 |
| 3 | Componenti Blade (97 totali) | Fase 2 |
| 4 | Componenti Livewire (16 totali) | Fase 3 + Database Architect |
| 5 | Block Renderer CMS | Fase 3 |
| 6 | Pagine (17 totali) | Fase 3, 4, 5 |
| 7 | Testing responsive | Fase 6 |
| 8 | Audit Lighthouse | Fase 7 |

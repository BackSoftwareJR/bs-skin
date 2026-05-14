# 11 — Mobile e PWA

## Approccio mobile-first

Ogni componente e layout nasce per viewport **320px** e viene progressivamente arricchito con i breakpoint Tailwind (`sm`, `md`, `lg`, `xl`, `2xl`). La versione mobile non e un adattamento reattivo: e la versione principale.

### Principi chiave

1. **Touch target minimo 44x44px** — ogni elemento interattivo (button, link, icona, checkbox) ha area di tocco minima 44x44 pixel. Se l'elemento visivo e piu piccolo, il padding/area cliccabile compensa.

2. **Thumb zone** — gli elementi interattivi primari (CTA, navigazione) sono nella zona raggiungibile dal pollice (meta inferiore dello schermo). Per questo la navigazione principale su mobile e una bottom tab bar fissa.

3. **Gesti nativi** — swipe orizzontale su gallery prodotto, pull-to-refresh dove appropriato (gestito dal browser), drawer che si aprono dal basso con gesture di chiusura verso il basso.

4. **Contenuto first** — su mobile nessun elemento decorativo deve rubare spazio al contenuto. Le immagini si ridimensionano, i testi si riducono con la scala tipografica, le sidebar diventano drawer.

---

## Bottom tab bar

Barra di navigazione fissa in basso, visibile solo su viewport < `lg` (1024px). Su desktop la navigazione e nell'header.

### Struttura

```
┌────────────────────────────────────────────┐
│  🏠 Home  │  🏪 Shop  │  🛒 Cart  │  👤 Account  │  ≡ Menu  │
│           │           │  (badge)  │             │          │
└────────────────────────────────────────────┘
```

### Specifiche

| Voce | Icona outline | Icona solid (attiva) | Rotta |
|------|---------------|---------------------|-------|
| Home | `home` | `home` (solid) | `/` |
| Shop | `building-storefront` | `building-storefront` (solid) | `/shop` |
| Cart | `shopping-bag` | `shopping-bag` (solid) | Apre drawer carrello |
| Account | `user` | `user` (solid) | `/account` o login |
| Menu | `bars-3` | `x-mark` (quando aperto) | Apre overlay menu completo |

### Implementazione

```html
<nav class="fixed inset-x-0 bottom-0 z-50 border-t border-neutral-200 bg-brand-surface/95 backdrop-blur-lg lg:hidden"
     style="padding-bottom: env(safe-area-inset-bottom)">
    <div class="flex items-center justify-around h-16">
        <a href="/" class="flex flex-col items-center justify-center gap-0.5 min-w-[64px] min-h-[44px]"
           :class="currentTab === 'home' ? 'text-brand-accent' : 'text-neutral-500'">
            <x-heroicon-o-home class="h-6 w-6" x-show="currentTab !== 'home'" />
            <x-heroicon-s-home class="h-6 w-6" x-show="currentTab === 'home'" />
            <span class="text-2xs font-medium">Home</span>
        </a>
        <!-- ripetere per ogni voce -->
        <button @click="$dispatch('toggle-cart')"
                class="relative flex flex-col items-center justify-center gap-0.5 min-w-[64px] min-h-[44px] text-neutral-500">
            <x-heroicon-o-shopping-bag class="h-6 w-6" />
            <span class="text-2xs font-medium">Carrello</span>
            <!-- Badge contatore -->
            <span x-show="cartCount > 0" x-text="cartCount"
                  class="absolute -top-1 right-2 flex h-4 min-w-[16px] items-center justify-center rounded-full bg-brand-accent text-2xs font-semibold text-brand-surface px-1">
            </span>
        </button>
    </div>
</nav>
```

### Note tecniche
- `padding-bottom: env(safe-area-inset-bottom)` per iPhone con notch/barra gesture
- `bg-brand-surface/95 backdrop-blur-lg` per effetto glass sottile
- Il body della pagina deve avere `pb-20 lg:pb-0` per compensare lo spazio della tab bar
- Transizione tra stato outline e solid gestita con Alpine.js `x-show`

---

## Drawer carrello mobile

Su mobile il carrello si apre come **bottom sheet** (dal basso verso l'alto), non come sidebar laterale.

### Comportamento
- Apertura: slide-up con animazione `ease-apple` 300ms
- Altezza: `max-h-[85vh]` — non copre mai completamente lo schermo
- Chiusura: tap su backdrop, swipe verso il basso, pulsante X
- Angoli: `rounded-t-3xl` (24px solo in alto)
- Contenuto scrollabile internamente

```html
<div x-show="cartOpen"
     x-transition:enter="transition ease-apple duration-300"
     x-transition:enter-start="translate-y-full"
     x-transition:enter-end="translate-y-0"
     x-transition:leave="transition ease-apple duration-200"
     x-transition:leave-start="translate-y-0"
     x-transition:leave-end="translate-y-full"
     class="fixed inset-x-0 bottom-0 z-40 max-h-[85vh] rounded-t-3xl bg-brand-surface shadow-soft-xl overflow-hidden lg:hidden">
    <!-- Handle di trascinamento -->
    <div class="flex justify-center py-3">
        <div class="h-1 w-10 rounded-full bg-neutral-300"></div>
    </div>
    <!-- Contenuto carrello scrollabile -->
    <div class="overflow-y-auto px-4 pb-safe" style="max-height: calc(85vh - 48px)">
        <!-- items carrello -->
    </div>
</div>
```

Su **desktop** (lg+) il carrello usa un drawer laterale destro (`right-0`, `w-[420px]`, `rounded-l-3xl`).

---

## Gallery prodotto — gesture

### Swipe orizzontale
La gallery prodotto su mobile supporta swipe nativo tramite CSS scroll-snap:

```html
<div class="flex snap-x snap-mandatory overflow-x-auto scrollbar-hide gap-2 -mx-4 px-4">
    @foreach($images as $image)
        <div class="snap-center shrink-0 w-full">
            <img src="{{ $image->url }}" alt="{{ $image->alt }}"
                 class="w-full aspect-square object-cover rounded-2xl" loading="lazy">
        </div>
    @endforeach
</div>
<!-- Indicatori pallini -->
<div class="flex justify-center gap-2 mt-3">
    @foreach($images as $i => $image)
        <span class="h-1.5 w-1.5 rounded-full transition-colors"
              :class="currentSlide === {{ $i }} ? 'bg-brand-accent' : 'bg-neutral-300'"></span>
    @endforeach
</div>
```

Su desktop la gallery mostra thumbnails laterali + immagine principale grande.

---

## Menu overlay mobile

Attivato dalla voce "Menu" nella bottom tab bar. Copre tutto lo schermo con elenco completo delle voci di navigazione.

### Struttura
```
┌──────────────────────────────────────┐
│  [X Chiudi]                          │
│                                      │
│  Prodotti Viso        >              │
│  Prodotti Corpo       >              │
│  Tecnologie           >              │
│  Chi Siamo                           │
│  Blog                                │
│  Supporto                            │
│  Contatti                            │
│                                      │
│  ─────────────────────               │
│  Il mio account                      │
│  I miei ordini                       │
│  Lista desideri                      │
│                                      │
│  🇮🇹 Made in Italy                   │
│  📧 info@skintemple.it               │
└──────────────────────────────────────┘
```

- Sfondo: `bg-brand-surface` pieno (no glass qui, serve leggibilita piena)
- Animazione: fade-in + slide-up leggero
- Le voci con sottocategorie (Prodotti Viso, Corpo, Tecnologie) aprono un sotto-livello con animazione slide-left
- Pulsante indietro in alto a sinistra nel sotto-livello

---

## PWA — Progressive Web App

### Manifest (predisposto, non attivo day-1)

File `public/manifest.json`:

```json
{
    "name": "SkinTemple",
    "short_name": "SkinTemple",
    "description": "Tecnologie e cosmetici Made in Italy per la cura della pelle",
    "start_url": "/",
    "display": "standalone",
    "background_color": "#FFFFFF",
    "theme_color": "#0A0A0A",
    "orientation": "portrait-primary",
    "icons": [
        {
            "src": "/images/pwa/icon-192.png",
            "sizes": "192x192",
            "type": "image/png"
        },
        {
            "src": "/images/pwa/icon-512.png",
            "sizes": "512x512",
            "type": "image/png"
        },
        {
            "src": "/images/pwa/icon-maskable-512.png",
            "sizes": "512x512",
            "type": "image/png",
            "purpose": "maskable"
        }
    ]
}
```

### Service Worker base

Solo offline shell — mostra una pagina "Sei offline" quando la connessione non e disponibile. Niente push notifications nella fase di lancio.

```javascript
// public/sw.js
const CACHE_NAME = 'skintemple-shell-v1';
const SHELL_ASSETS = [
    '/offline',
    '/css/app.css',
    '/js/app.js',
    '/images/logo.svg',
];

self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => cache.addAll(SHELL_ASSETS))
    );
});

self.addEventListener('fetch', (event) => {
    if (event.request.mode === 'navigate') {
        event.respondWith(
            fetch(event.request).catch(() => caches.match('/offline'))
        );
    }
});
```

### Meta tags nel layout

```html
<meta name="theme-color" content="#0A0A0A">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<link rel="manifest" href="/manifest.json">
<link rel="apple-touch-icon" href="/images/pwa/icon-192.png">
```

---

## Adattamenti specifici mobile

| Componente | Comportamento desktop | Comportamento mobile |
|------------|----------------------|---------------------|
| Header | Logo + nav orizzontale + mega menu + icone | Logo centrato + icone essenziali |
| Navigazione | Mega menu dropdown | Bottom tab bar + overlay menu |
| Filtri catalogo | Sidebar fissa a sinistra | Bottom sheet / drawer con button "Filtra" |
| Griglia prodotti | 4 colonne | 2 colonne |
| Card prodotto hover | CTA "Aggiungi" appare su hover | CTA sempre visibile o tap per dettaglio |
| Carrello | Drawer laterale destro | Bottom sheet |
| Checkout | 2 colonne (form + riepilogo) | Stack verticale, riepilogo collassabile in alto |
| Product gallery | Thumbnails laterali + main image | Swipe orizzontale full-width |
| Search | Overlay con campo largo | Full-screen overlay |
| Footer | 4 colonne | Accordion collassabile per sezioni |
| Announcement bar | Testo scorrevole con chiudi | Testo piu corto, chiudi |

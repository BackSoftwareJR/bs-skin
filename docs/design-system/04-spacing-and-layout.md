# 04 — Spacing e Layout

## Scala spacing

Base: **4px** (Tailwind default). Ogni valore e un multiplo o sottomultiplo.

| Token Tailwind | Pixel | Rem | Uso tipico |
|----------------|-------|-----|-----------|
| `0` | 0 | 0 | Reset |
| `px` | 1px | — | Bordi, separatori sottili |
| `0.5` | 2px | 0.125rem | Micro-gap tra icona e badge numerico |
| `1` | 4px | 0.25rem | Padding badge piccolo, gap minimo |
| `1.5` | 6px | 0.375rem | Padding badge, piccoli gap |
| `2` | 8px | 0.5rem | Padding interno input piccolo, gap chip |
| `2.5` | 10px | 0.625rem | Padding verticale button sm |
| `3` | 12px | 0.75rem | Padding card interno, gap tra label e input |
| `4` | 16px | 1rem | Padding standard, gap griglia mobile |
| `5` | 20px | 1.25rem | Padding button lg, margine tra elementi card |
| `6` | 24px | 1.5rem | Gap griglia tablet, padding sezione mobile |
| `8` | 32px | 2rem | Gap griglia desktop, margine tra blocchi |
| `10` | 40px | 2.5rem | Margine tra sezioni piccole |
| `12` | 48px | 3rem | Padding sezione verticale mobile |
| `14` | 56px | 3.5rem | Padding intermedio |
| `16` | 64px | 4rem | Padding sezione verticale tablet |
| `20` | 80px | 5rem | Padding sezione verticale desktop |
| `24` | 96px | 6rem | Separazione sezioni principali |
| `28` | 112px | 7rem | Uso raro |
| `32` | 128px | 8rem | Padding hero verticale desktop |
| `40` | 160px | 10rem | Uso raro, spacing hero gigante |
| `48` | 192px | 12rem | Uso raro |
| `56` | 224px | 14rem | Uso raro |
| `64` | 256px | 16rem | Uso raro |
| `80` | 320px | 20rem | Altezza minima hero |
| `96` | 384px | 24rem | Altezza sezioni |

## Container

### Container principale del sito
```html
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
    <!-- contenuto -->
</div>
```
- `max-w-7xl` = **1280px** — larghezza massima contenuto
- Padding orizzontale responsive: 16px mobile, 24px tablet, 32px desktop
- Centrato con `mx-auto`

### Varianti container

| Classe | Max width | Uso |
|--------|-----------|-----|
| `max-w-sm` | 640px | Modale piccola, form login |
| `max-w-md` | 768px | Modale media, form checkout step |
| `max-w-lg` | 1024px | Contenuto blog, pagine testo lungo |
| `max-w-xl` | 1280px | Non usato (alias del 7xl) |
| `max-w-7xl` | 1280px | Container standard sito |
| `max-w-screen-2xl` | 1536px | Layout admin o pagine con sidebar ampia |
| Nessun max-w | 100% | Hero full-width, announcement bar |

### Container hero
```html
<section class="relative w-full">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-16 sm:py-20 lg:py-32">
        <!-- contenuto hero -->
    </div>
</section>
```
Il `<section>` esterno e full-width per sfondi/immagini. Il `<div>` interno contiene il testo con padding.

## Grid system

Griglia a **12 colonne** con gap responsive.

```html
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 lg:gap-8">
    <!-- items -->
</div>
```

### Layout comuni

| Layout | Mobile | Tablet (sm/md) | Desktop (lg+) |
|--------|--------|----------------|---------------|
| Griglia prodotti shop | 2 colonne `grid-cols-2` | 3 colonne `sm:grid-cols-3` | 4 colonne `lg:grid-cols-4` |
| Griglia prodotti home (carousel) | scroll orizzontale | scroll orizzontale | 4-5 visibili |
| Sidebar + contenuto | stack verticale | stack verticale | `grid-cols-12` → sidebar `col-span-3` + content `col-span-9` |
| Hero 50/50 | stack verticale (immagine sotto testo) | stack verticale | `grid-cols-2` |
| Footer | stack verticale | 2 colonne | 4 colonne |
| Checkout | stack verticale | stack verticale | `grid-cols-12` → form `col-span-7` + summary `col-span-5` |

### Esempio sidebar + griglia shop
```html
<div class="lg:grid lg:grid-cols-12 lg:gap-8">
    <!-- Sidebar filtri: nascosta su mobile, visibile su desktop -->
    <aside class="hidden lg:block lg:col-span-3">
        <!-- filtri -->
    </aside>
    <!-- Griglia prodotti -->
    <main class="lg:col-span-9">
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6">
            <!-- product cards -->
        </div>
    </main>
</div>
```

## Z-index scale

Scala fissa per evitare conflitti. Mai usare valori arbitrari.

| Token | Valore | Uso |
|-------|--------|-----|
| `z-0` | 0 | Contenuto base |
| `z-10` | 10 | Header sticky, elementi sticky in scroll |
| `z-20` | 20 | Dropdown, tooltip, popover |
| `z-30` | 30 | Overlay/backdrop modale, backdrop drawer |
| `z-40` | 40 | Modale, drawer (sopra il backdrop) |
| `z-50` | 50 | Toast notification, alert flottante |
| `z-60` | 60 | Mega menu (sopra tutto tranne toast) |

Nota: `z-60` non esiste di default in Tailwind, va aggiunto nella config:
```js
zIndex: { 60: '60' }
```

## Breakpoints

Breakpoint Tailwind standard. Design mobile-first: le classi senza prefisso si applicano a tutti i viewport, i prefissi si applicano dal breakpoint in su.

| Prefisso | Min-width | Dispositivo tipico |
|----------|-----------|-------------------|
| _(default)_ | 0px | Mobile portrait (320-639px) |
| `sm` | 640px | Mobile landscape, tablet piccolo |
| `md` | 768px | Tablet portrait |
| `lg` | 1024px | Tablet landscape, desktop piccolo |
| `xl` | 1280px | Desktop standard |
| `2xl` | 1536px | Desktop largo |

### Comportamento per breakpoint

| Breakpoint | Header | Navigazione | Prodotti | Sidebar filtri |
|------------|--------|-------------|----------|----------------|
| < sm | Logo centrato, hamburger, icone essenziali | Bottom tab bar + drawer overlay | 2 col | Drawer bottom sheet |
| sm-md | Logo sx, nav ridotta, icone | Bottom tab bar + drawer overlay | 2-3 col | Drawer bottom sheet |
| md-lg | Logo sx, nav visibile, icone | Nav orizzontale in header | 3 col | Drawer laterale opzionale |
| lg+ | Logo sx, nav completa con mega menu, icone dx | Mega menu dropdown | 3-4 col | Sidebar fissa a sinistra |
| xl+ | Come lg, piu spazio | Come lg | 4 col | Sidebar fissa |

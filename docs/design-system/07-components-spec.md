# 07 — Specifica Componenti Blade

Ogni componente e un Blade anonymous component in `resources/views/components/public/`.
Sintassi d'uso: `<x-public.nome-componente />`.

---

## 1. Layout

### 1.1 `app-layout`

**Path**: `resources/views/layouts/app.blade.php`

Layout principale pubblico. Wrappa tutte le pagine del frontend.

**Slot**:
- `$slot` — contenuto principale della pagina
- `$title` — titolo pagina (per `<title>`)
- `$meta` — meta tag aggiuntivi opzionali
- `$head` — script/style aggiuntivi nel `<head>`

**Struttura**:
```html
<!DOCTYPE html>
<html lang="it" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'SkinTemple' }} — SkinTemple</title>
    {{ $meta ?? '' }}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,400;1,500&family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    {{ $head ?? '' }}
</head>
<body class="min-h-screen bg-brand-surface font-sans text-neutral-700 antialiased">
    <a href="#main-content"
       class="sr-only focus:not-sr-only focus:fixed focus:top-4 focus:left-4 focus:z-[100] focus:rounded-xl focus:bg-brand-accent focus:px-4 focus:py-2 focus:text-sm focus:font-semibold focus:text-brand-surface focus:shadow-soft-lg">
        Vai al contenuto principale
    </a>

    <x-public.announcement-bar />
    <x-public.public-header />

    <main id="main-content" tabindex="-1" class="pb-20 lg:pb-0">
        {{ $slot }}
    </main>

    <x-public.public-footer />
    <x-public.bottom-tab-bar />

    @livewireScripts
    @stack('scripts')
</body>
</html>
```

---

### 1.2 `announcement-bar`

**Path**: `resources/views/components/public/announcement-bar.blade.php`

Barra superiore con messaggio promozionale. Chiudibile. Persiste stato chiusura in `localStorage`.

**Props**:
- Nessuna prop: il contenuto viene da configurazione DB/cache (gestito da Livewire o Blade con dati passati dal controller/middleware).

**Esempio**:
```html
<div x-data="{ dismissed: localStorage.getItem('announcement-dismissed') === 'true' }"
     x-show="!dismissed"
     x-transition:leave="transition ease-apple duration-200"
     x-transition:leave-start="opacity-100 max-h-10"
     x-transition:leave-end="opacity-0 max-h-0"
     class="relative bg-brand-primary text-brand-surface overflow-hidden">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-center py-2 text-xs sm:text-sm font-medium tracking-wide">
            <span>Spedizione gratuita per ordini superiori a €59</span>
            <button @click="dismissed = true; localStorage.setItem('announcement-dismissed', 'true')"
                    aria-label="Chiudi avviso"
                    class="absolute right-4 p-1 text-neutral-400 hover:text-brand-surface transition-colors">
                <x-heroicon-o-x-mark class="h-4 w-4" />
            </button>
        </div>
    </div>
</div>
```

---

### 1.3 `public-header`

**Path**: `resources/views/components/public/public-header.blade.php`

Header sticky con glass effect. Contiene logo, navigazione desktop, icone azione.

**Struttura desktop** (`lg+`):
```
┌─────────────────────────────────────────────────────────────┐
│  [Logo]     Home  Prodotti▾  Tecnologie  Chi Siamo  Blog   │  🔍  👤  ♡  🛒(2)
└─────────────────────────────────────────────────────────────┘
```

**Struttura mobile** (`< lg`):
```
┌─────────────────────────────────────────┐
│  [≡]        [Logo SkinTemple]      🔍 🛒│
└─────────────────────────────────────────┘
```

**Implementazione**:
```html
<header class="sticky top-0 z-50 border-b border-neutral-200/50 bg-brand-surface/90 backdrop-blur-lg"
        x-data="{ megaMenuOpen: null }">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between lg:h-20">
            <!-- Logo -->
            <a href="/" class="shrink-0">
                <img src="/images/logo.svg" alt="SkinTemple" class="h-8 lg:h-10 w-auto">
            </a>

            <!-- Nav desktop -->
            <nav aria-label="Navigazione principale" class="hidden lg:flex lg:items-center lg:gap-8">
                <a href="/" class="text-sm font-medium text-neutral-700 hover:text-brand-accent transition-colors">Home</a>
                <div @mouseenter="megaMenuOpen = 'prodotti'" @mouseleave="megaMenuOpen = null" class="relative">
                    <button class="flex items-center gap-1 text-sm font-medium text-neutral-700 hover:text-brand-accent transition-colors">
                        Prodotti
                        <x-heroicon-m-chevron-down class="h-4 w-4" />
                    </button>
                    <x-public.mega-menu x-show="megaMenuOpen === 'prodotti'" />
                </div>
                <!-- altre voci -->
            </nav>

            <!-- Icone azione -->
            <div class="flex items-center gap-3">
                <button aria-label="Cerca" @click="$dispatch('open-search')"
                        class="p-2 text-neutral-700 hover:text-brand-accent transition-colors">
                    <x-heroicon-o-magnifying-glass class="h-5 w-5" />
                </button>
                <a href="/account" aria-label="Account" class="hidden lg:block p-2 text-neutral-700 hover:text-brand-accent transition-colors">
                    <x-heroicon-o-user class="h-5 w-5" />
                </a>
                <button aria-label="Lista desideri" class="hidden lg:block p-2 text-neutral-700 hover:text-brand-accent transition-colors">
                    <x-heroicon-o-heart class="h-5 w-5" />
                </button>
                <button aria-label="Carrello" @click="$dispatch('toggle-cart')"
                        class="relative p-2 text-neutral-700 hover:text-brand-accent transition-colors">
                    <x-heroicon-o-shopping-bag class="h-5 w-5" />
                    <span x-show="$store.cart.count > 0" x-text="$store.cart.count"
                          class="absolute -top-0.5 -right-0.5 flex h-4 min-w-[16px] items-center justify-center rounded-full bg-brand-accent text-2xs font-semibold text-brand-surface px-1">
                    </span>
                </button>
            </div>
        </div>
    </div>
</header>
```

---

### 1.4 `public-footer`

**Path**: `resources/views/components/public/public-footer.blade.php`

Footer con 4 colonne desktop, accordion mobile. Sfondo scuro.

**Sezioni**: Esplora (link pagine), Prodotti (link categorie), Legale (Privacy, Cookie, Termini), Contatti (email, indirizzo, social).

```html
<footer class="bg-neutral-950 text-neutral-400">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
        <!-- Logo e descrizione -->
        <div class="mb-10">
            <img src="/images/logo-white.svg" alt="SkinTemple" class="h-8 w-auto mb-4">
            <p class="max-w-sm text-sm leading-relaxed">
                Prodotti per la cura della pelle selezionati con rigore, tutti Made in Italy.
            </p>
        </div>

        <!-- Colonne link -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-12">
            <x-public.footer-column title="Esplora">
                <x-public.footer-link href="/">Home</x-public.footer-link>
                <x-public.footer-link href="/shop">Prodotti</x-public.footer-link>
                <x-public.footer-link href="/tecnologie">Tecnologie</x-public.footer-link>
                <x-public.footer-link href="/chi-siamo">Chi Siamo</x-public.footer-link>
                <x-public.footer-link href="/supporto">Supporto</x-public.footer-link>
            </x-public.footer-column>
            <!-- ripetere per altre colonne -->
        </div>

        <!-- Bottom bar -->
        <div class="mt-12 border-t border-neutral-800 pt-8 flex flex-col sm:flex-row items-center justify-between gap-4">
            <p class="text-xs text-neutral-500">
                &copy; {{ date('Y') }} SkinTemple — P.IVA 11863510019
            </p>
            <div class="flex items-center gap-4">
                <x-public.secure-payment-icons />
            </div>
        </div>
    </div>
</footer>
```

---

### 1.5 `container`

**Path**: `resources/views/components/public/container.blade.php`

Wrapper centrato con max-width e padding responsive.

**Props**:
| Prop | Tipo | Default | Descrizione |
|------|------|---------|-------------|
| `max` | string | `7xl` | Max width: `sm`, `md`, `lg`, `xl`, `7xl`, `full` |

```html
@props(['max' => '7xl'])

@php
$maxClasses = [
    'sm' => 'max-w-sm',
    'md' => 'max-w-md',
    'lg' => 'max-w-lg',
    'xl' => 'max-w-xl',
    '7xl' => 'max-w-7xl',
    'full' => 'max-w-full',
];
@endphp

<div {{ $attributes->merge(['class' => 'mx-auto px-4 sm:px-6 lg:px-8 ' . ($maxClasses[$max] ?? 'max-w-7xl')]) }}>
    {{ $slot }}
</div>
```

---

### 1.6 `section`

**Path**: `resources/views/components/public/section.blade.php`

Sezione con padding verticale responsive e sfondo opzionale.

**Props**:
| Prop | Tipo | Default | Descrizione |
|------|------|---------|-------------|
| `bg` | string | `white` | `white`, `neutral`, `dark` |
| `padding` | string | `default` | `none`, `sm`, `default`, `lg` |

```html
@props(['bg' => 'white', 'padding' => 'default'])

@php
$bgClasses = [
    'white' => 'bg-brand-surface',
    'neutral' => 'bg-neutral-50',
    'dark' => 'bg-neutral-950 text-neutral-200',
];
$paddingClasses = [
    'none' => '',
    'sm' => 'py-8 lg:py-12',
    'default' => 'py-12 sm:py-16 lg:py-20',
    'lg' => 'py-16 sm:py-20 lg:py-32',
];
@endphp

<section {{ $attributes->merge(['class' => ($bgClasses[$bg] ?? '') . ' ' . ($paddingClasses[$padding] ?? '')]) }}>
    {{ $slot }}
</section>
```

---

## 2. Navigazione

### 2.1 `mega-menu`

**Path**: `resources/views/components/public/mega-menu.blade.php`

Menu a tendina a 2+ colonne + immagini promozionali. Glass effect. Ispirato a Top Beauty ma con estetica SkinTemple.

**Struttura**: 2 colonne di link (Tipo prodotto / Per obiettivo) + 2 immagini CTA a destra.

```html
<div x-show="megaMenuOpen === 'prodotti'"
     x-transition:enter="transition ease-apple duration-200"
     x-transition:enter-start="opacity-0 -translate-y-2"
     x-transition:enter-end="opacity-100 translate-y-0"
     x-transition:leave="transition ease-apple duration-150"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     @click.outside="megaMenuOpen = null"
     class="absolute left-1/2 -translate-x-1/2 top-full mt-2 w-[900px] rounded-2xl bg-glass-light backdrop-blur-xl border border-neutral-200/50 shadow-glass z-60 p-8">
    <div class="grid grid-cols-12 gap-8">
        <!-- Colonna 1: Tipo prodotto -->
        <div class="col-span-3">
            <h3 class="text-xs font-semibold uppercase tracking-widest text-neutral-500 mb-4">Tipo di Prodotto</h3>
            <ul class="space-y-2">
                <li><a href="#" class="text-sm text-neutral-700 hover:text-brand-accent transition-colors">Creme Viso</a></li>
                <li><a href="#" class="text-sm text-neutral-700 hover:text-brand-accent transition-colors">Sieri e Concentrati</a></li>
                <!-- ... -->
            </ul>
        </div>
        <!-- Colonna 2: Per obiettivo -->
        <div class="col-span-3">
            <h3 class="text-xs font-semibold uppercase tracking-widest text-neutral-500 mb-4">Per Obiettivo</h3>
            <ul class="space-y-2">
                <li><a href="#" class="text-sm text-neutral-700 hover:text-brand-accent transition-colors">Anti-eta</a></li>
                <!-- ... -->
            </ul>
        </div>
        <!-- Immagini CTA -->
        <div class="col-span-6 grid grid-cols-2 gap-4">
            <a href="#" class="group relative overflow-hidden rounded-xl aspect-[3/4]">
                <img src="..." alt="..." class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105" loading="lazy">
                <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                <div class="absolute bottom-4 left-4 text-brand-surface">
                    <p class="text-sm font-semibold">Scopri i prodotti Viso</p>
                    <span class="text-xs underline">Esplora</span>
                </div>
            </a>
            <!-- seconda immagine CTA -->
        </div>
    </div>
</div>
```

---

### 2.2 `mega-menu-column`

**Path**: `resources/views/components/public/mega-menu-column.blade.php`

Colonna singola del mega menu.

**Props**: `title` (string).

```html
@props(['title'])

<div>
    <h3 class="text-xs font-semibold uppercase tracking-widest text-neutral-500 mb-4">{{ $title }}</h3>
    <ul class="space-y-2">
        {{ $slot }}
    </ul>
</div>
```

---

### 2.3 `mobile-nav-drawer`

**Path**: `resources/views/components/public/mobile-nav-drawer.blade.php`

Overlay full-screen navigazione mobile. Si attiva dalla voce "Menu" nella bottom tab bar.

```html
<div x-show="mobileMenuOpen"
     x-transition:enter="transition ease-apple duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-apple duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     x-trap.noscroll="mobileMenuOpen"
     class="fixed inset-0 z-60 bg-brand-surface lg:hidden overflow-y-auto"
     role="dialog" aria-modal="true" aria-label="Menu navigazione">
    <!-- Header con chiudi -->
    <div class="flex items-center justify-between px-4 h-16 border-b border-neutral-100">
        <img src="/images/logo.svg" alt="SkinTemple" class="h-8 w-auto">
        <button @click="mobileMenuOpen = false" aria-label="Chiudi menu" class="p-2 text-neutral-700">
            <x-heroicon-o-x-mark class="h-6 w-6" />
        </button>
    </div>
    <!-- Voci -->
    <nav class="px-4 py-6 space-y-1">
        <!-- Ogni voce con sotto-livello usa x-data per toggle -->
    </nav>
</div>
```

---

### 2.4 `breadcrumb`

**Path**: `resources/views/components/public/breadcrumb.blade.php`

**Props**:
| Prop | Tipo | Default | Descrizione |
|------|------|---------|-------------|
| `items` | array | `[]` | Array di `['label' => '...', 'url' => '...' o null]` |

```html
@props(['items' => []])

<nav aria-label="Breadcrumb" class="mb-6">
    <ol class="flex items-center gap-1.5 text-sm text-neutral-500">
        <li><a href="/" class="hover:text-brand-accent transition-colors">Home</a></li>
        @foreach($items as $item)
            <li class="flex items-center gap-1.5">
                <x-heroicon-m-chevron-right class="h-3 w-3 text-neutral-400" />
                @if($item['url'] ?? null)
                    <a href="{{ $item['url'] }}" class="hover:text-brand-accent transition-colors">{{ $item['label'] }}</a>
                @else
                    <span class="text-neutral-900 font-medium">{{ $item['label'] }}</span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
```

---

### 2.5 `pagination`

**Path**: `resources/views/components/public/pagination.blade.php`

Wrapper per la paginazione Laravel con stile SkinTemple. Sovrascrive il template di default.

```html
@if ($paginator->hasPages())
<nav aria-label="Paginazione" class="flex items-center justify-center gap-1 mt-8">
    {{-- Precedente --}}
    @if ($paginator->onFirstPage())
        <span class="p-2 text-neutral-300 cursor-not-allowed"><x-heroicon-m-chevron-left class="h-5 w-5" /></span>
    @else
        <a href="{{ $paginator->previousPageUrl() }}" class="p-2 text-neutral-700 hover:text-brand-accent transition-colors" aria-label="Pagina precedente">
            <x-heroicon-m-chevron-left class="h-5 w-5" />
        </a>
    @endif

    {{-- Numeri --}}
    @foreach ($elements as $element)
        @if (is_string($element))
            <span class="px-3 py-1.5 text-sm text-neutral-400">{{ $element }}</span>
        @endif
        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                    <span class="flex h-9 w-9 items-center justify-center rounded-full bg-brand-primary text-sm font-semibold text-brand-surface">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" class="flex h-9 w-9 items-center justify-center rounded-full text-sm text-neutral-700 hover:bg-neutral-100 transition-colors">{{ $page }}</a>
                @endif
            @endforeach
        @endif
    @endforeach

    {{-- Successiva --}}
    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" class="p-2 text-neutral-700 hover:text-brand-accent transition-colors" aria-label="Pagina successiva">
            <x-heroicon-m-chevron-right class="h-5 w-5" />
        </a>
    @else
        <span class="p-2 text-neutral-300 cursor-not-allowed"><x-heroicon-m-chevron-right class="h-5 w-5" /></span>
    @endif
</nav>
@endif
```

---

## 3. Atomi

### 3.1 `button`

**Path**: `resources/views/components/public/button.blade.php`

**Props**:
| Prop | Tipo | Default | Descrizione |
|------|------|---------|-------------|
| `variant` | string | `primary` | `primary`, `secondary`, `ghost`, `destructive` |
| `size` | string | `md` | `sm`, `md`, `lg` |
| `href` | string/null | `null` | Se presente, renderizza `<a>` |
| `type` | string | `button` | `button`, `submit`, `reset` |
| `disabled` | bool | `false` | Stato disabilitato |
| `loading` | bool | `false` | Stato caricamento (spinner) |
| `icon` | string/null | `null` | Nome icona Heroicon prima del testo |

**Varianti**:

| Variante | Default | Hover | Active | Disabled |
|----------|---------|-------|--------|----------|
| `primary` | `bg-brand-accent text-brand-surface` | `bg-brand-accent-deep` | `bg-brand-accent-deep scale-[0.98]` | `opacity-50 cursor-not-allowed` |
| `secondary` | `bg-brand-surface text-neutral-900 border border-neutral-300` | `border-neutral-400 bg-neutral-50` | `bg-neutral-100` | `opacity-50` |
| `ghost` | `text-neutral-700` | `text-brand-accent bg-neutral-50` | `bg-neutral-100` | `opacity-50` |
| `destructive` | `bg-danger text-brand-surface` | `bg-red-600` | `bg-red-700` | `opacity-50` |

**Dimensioni**:

| Size | Padding | Font | Raggio |
|------|---------|------|--------|
| `sm` | `px-3.5 py-2` | `text-sm` | `rounded-full` (primary) / `rounded-xl` (altri) |
| `md` | `px-5 py-2.5` | `text-sm` | `rounded-full` / `rounded-xl` |
| `lg` | `px-6 py-3` | `text-base` | `rounded-full` / `rounded-xl` |

```html
@props([
    'variant' => 'primary',
    'size' => 'md',
    'href' => null,
    'type' => 'button',
    'disabled' => false,
    'loading' => false,
    'icon' => null,
])

@php
$base = 'inline-flex items-center justify-center gap-2 font-semibold transition-all duration-200 ease-apple focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-accent focus-visible:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed';

$variants = [
    'primary' => 'bg-brand-accent text-brand-surface hover:bg-brand-accent-deep active:scale-[0.98] rounded-full',
    'secondary' => 'bg-brand-surface text-neutral-900 border border-neutral-300 hover:border-neutral-400 hover:bg-neutral-50 active:bg-neutral-100 rounded-xl',
    'ghost' => 'text-neutral-700 hover:text-brand-accent hover:bg-neutral-50 active:bg-neutral-100 rounded-xl',
    'destructive' => 'bg-danger text-brand-surface hover:bg-red-600 active:bg-red-700 rounded-full',
];

$sizes = [
    'sm' => 'px-3.5 py-2 text-sm',
    'md' => 'px-5 py-2.5 text-sm',
    'lg' => 'px-6 py-3 text-base',
];

$classes = $base . ' ' . ($variants[$variant] ?? $variants['primary']) . ' ' . ($sizes[$size] ?? $sizes['md']);
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes, 'disabled' => $disabled || $loading]) }}>
        @if($loading)
            <x-public.spinner size="sm" />
        @endif
        {{ $slot }}
    </button>
@endif
```

---

### 3.2 `link`

**Path**: `resources/views/components/public/link.blade.php`

```html
@props(['href' => '#'])

<a href="{{ $href }}"
   {{ $attributes->merge(['class' => 'text-brand-accent-deep hover:text-brand-accent underline-offset-2 hover:underline transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-accent focus-visible:ring-offset-2 rounded-sm']) }}>
    {{ $slot }}
</a>
```

---

### 3.3 `badge`

**Path**: `resources/views/components/public/badge.blade.php`

**Props**:
| Prop | Tipo | Default | Descrizione |
|------|------|---------|-------------|
| `variant` | string | `default` | `default`, `accent`, `success`, `warning`, `danger` |
| `size` | string | `md` | `sm`, `md` |

```html
@props(['variant' => 'default', 'size' => 'md'])

@php
$variants = [
    'default' => 'bg-neutral-100 text-neutral-700',
    'accent' => 'bg-brand-accent text-brand-surface',
    'success' => 'bg-success-bg text-success',
    'warning' => 'bg-warning-bg text-warning',
    'danger' => 'bg-danger-bg text-danger',
];
$sizes = [
    'sm' => 'px-1.5 py-0.5 text-2xs',
    'md' => 'px-2 py-0.5 text-xs',
];
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center rounded-full font-medium ' . ($variants[$variant] ?? $variants['default']) . ' ' . ($sizes[$size] ?? $sizes['md'])]) }}>
    {{ $slot }}
</span>
```

---

### 3.4 `chip`

**Path**: `resources/views/components/public/chip.blade.php`

Elemento rimuovibile (usato per filtri attivi).

**Props**: `removable` (bool, default true), emette evento `remove` al click sulla X.

```html
@props(['removable' => true])

<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-1 rounded-full bg-neutral-100 px-3 py-1 text-sm text-neutral-700']) }}>
    {{ $slot }}
    @if($removable)
        <button type="button" aria-label="Rimuovi filtro" class="ml-0.5 p-0.5 rounded-full hover:bg-neutral-200 transition-colors">
            <x-heroicon-m-x-mark class="h-3 w-3" />
        </button>
    @endif
</span>
```

---

### 3.5 `divider`

**Path**: `resources/views/components/public/divider.blade.php`

```html
@props(['label' => null])

@if($label)
    <div {{ $attributes->merge(['class' => 'flex items-center gap-4']) }}>
        <div class="flex-1 border-t border-neutral-200"></div>
        <span class="text-xs text-neutral-500">{{ $label }}</span>
        <div class="flex-1 border-t border-neutral-200"></div>
    </div>
@else
    <hr {{ $attributes->merge(['class' => 'border-t border-neutral-200']) }}>
@endif
```

---

### 3.6 `icon`

**Path**: `resources/views/components/public/icon.blade.php`

Wrapper per Heroicons con size preset.

**Props**: `name` (string), `size` (string: `xs`, `sm`, `md`, `lg`, `xl`), `variant` (string: `outline`, `solid`, `mini`).

```html
@props(['name', 'size' => 'md', 'variant' => 'outline'])

@php
$sizes = ['xs' => 'h-3 w-3', 'sm' => 'h-4 w-4', 'md' => 'h-5 w-5', 'lg' => 'h-6 w-6', 'xl' => 'h-8 w-8'];
$prefix = match($variant) { 'solid' => 'heroicon-s', 'mini' => 'heroicon-m', default => 'heroicon-o' };
$componentName = "x-{$prefix}-{$name}";
@endphp

<x-dynamic-component :component="$prefix . '-' . $name" {{ $attributes->merge(['class' => $sizes[$size] ?? $sizes['md']]) }} />
```

---

### 3.7 `spinner`

**Path**: `resources/views/components/public/spinner.blade.php`

**Props**: `size` (string: `sm`, `md`, `lg`).

```html
@props(['size' => 'md'])

@php
$sizes = ['sm' => 'h-4 w-4', 'md' => 'h-5 w-5', 'lg' => 'h-8 w-8'];
@endphp

<svg {{ $attributes->merge(['class' => 'animate-spin ' . ($sizes[$size] ?? $sizes['md'])]) }} xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
</svg>
```

---

### 3.8 `skeleton`

**Path**: `resources/views/components/public/skeleton.blade.php`

Placeholder shimmer per loading state.

**Props**: `class` (per dimensioni via `h-*`, `w-*`, `rounded-*`).

```html
<div {{ $attributes->merge(['class' => 'animate-shimmer rounded-xl bg-gradient-to-r from-neutral-200 via-neutral-100 to-neutral-200 bg-[length:400%_100%]']) }}>
</div>
```

---

### 3.9 `avatar`

**Path**: `resources/views/components/public/avatar.blade.php`

**Props**: `src` (string/null), `alt` (string), `size` (string: `sm`, `md`, `lg`), `initials` (string/null).

```html
@props(['src' => null, 'alt' => '', 'size' => 'md', 'initials' => null])

@php
$sizes = ['sm' => 'h-8 w-8 text-xs', 'md' => 'h-10 w-10 text-sm', 'lg' => 'h-14 w-14 text-base'];
@endphp

@if($src)
    <img src="{{ $src }}" alt="{{ $alt }}" {{ $attributes->merge(['class' => 'rounded-full object-cover ' . ($sizes[$size] ?? $sizes['md'])]) }}>
@else
    <span {{ $attributes->merge(['class' => 'inline-flex items-center justify-center rounded-full bg-neutral-100 font-medium text-neutral-600 ' . ($sizes[$size] ?? $sizes['md'])]) }}>
        {{ $initials ?? '?' }}
    </span>
@endif
```

---

### 3.10 `tooltip`

**Path**: `resources/views/components/public/tooltip.blade.php`

**Props**: `text` (string), `position` (string: `top`, `bottom`).

```html
@props(['text', 'position' => 'top'])

<span x-data="{ show: false }" @mouseenter="show = true" @mouseleave="show = false" class="relative inline-flex">
    {{ $slot }}
    <span x-show="show" x-transition:enter="transition ease-apple duration-150" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
          class="absolute {{ $position === 'top' ? 'bottom-full mb-2' : 'top-full mt-2' }} left-1/2 -translate-x-1/2 whitespace-nowrap rounded-lg bg-neutral-900 px-2.5 py-1.5 text-xs text-brand-surface shadow-soft-md z-20 pointer-events-none"
          role="tooltip">
        {{ $text }}
    </span>
</span>
```

---

### 3.11 `kbd`

**Path**: `resources/views/components/public/kbd.blade.php`

```html
<kbd {{ $attributes->merge(['class' => 'inline-flex items-center rounded-md border border-neutral-300 bg-neutral-50 px-1.5 py-0.5 font-mono text-xs text-neutral-600 shadow-inner-soft']) }}>
    {{ $slot }}
</kbd>
```

---

## 4. Form

### 4.1 `input`

**Path**: `resources/views/components/public/input.blade.php`

**Props**: `type`, `error` (string/null), `icon` (string/null, icona prefix).

**Stati**: default, focus, error, disabled.

```html
@props(['type' => 'text', 'error' => null, 'icon' => null])

<div class="relative">
    @if($icon)
        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-neutral-400">
            <x-public.icon :name="$icon" size="sm" />
        </div>
    @endif
    <input type="{{ $type }}"
           {{ $attributes->merge([
               'class' => 'block w-full rounded-xl border bg-brand-surface px-4 py-2.5 text-sm text-neutral-900 placeholder:text-neutral-400 shadow-inner-soft transition-colors duration-200 ease-apple focus:outline-none focus:ring-2 focus:ring-brand-accent/20 focus:border-brand-accent disabled:bg-neutral-100 disabled:text-neutral-500 disabled:cursor-not-allowed'
                   . ($icon ? ' pl-10' : '')
                   . ($error ? ' border-danger focus:ring-danger/20 focus:border-danger' : ' border-neutral-300'),
           ]) }}
           @if($error) aria-invalid="true" @endif>
</div>
```

---

### 4.2 `textarea`

**Path**: `resources/views/components/public/textarea.blade.php`

Come input ma con `<textarea>`. Props: `error`, `rows` (default 4).

```html
@props(['error' => null, 'rows' => 4])

<textarea rows="{{ $rows }}"
          {{ $attributes->merge([
              'class' => 'block w-full rounded-xl border bg-brand-surface px-4 py-2.5 text-sm text-neutral-900 placeholder:text-neutral-400 shadow-inner-soft transition-colors duration-200 ease-apple focus:outline-none focus:ring-2 focus:ring-brand-accent/20 focus:border-brand-accent resize-y disabled:bg-neutral-100 disabled:cursor-not-allowed'
                  . ($error ? ' border-danger focus:ring-danger/20 focus:border-danger' : ' border-neutral-300'),
          ]) }}
          @if($error) aria-invalid="true" @endif>{{ $slot }}</textarea>
```

---

### 4.3 `select`

**Path**: `resources/views/components/public/select.blade.php`

```html
@props(['error' => null])

<div class="relative">
    <select {{ $attributes->merge([
        'class' => 'block w-full appearance-none rounded-xl border bg-brand-surface px-4 py-2.5 pr-10 text-sm text-neutral-900 shadow-inner-soft transition-colors duration-200 ease-apple focus:outline-none focus:ring-2 focus:ring-brand-accent/20 focus:border-brand-accent'
            . ($error ? ' border-danger' : ' border-neutral-300'),
    ]) }}>
        {{ $slot }}
    </select>
    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-neutral-400">
        <x-heroicon-m-chevron-down class="h-4 w-4" />
    </div>
</div>
```

---

### 4.4 `checkbox`

**Path**: `resources/views/components/public/checkbox.blade.php`

```html
@props(['label' => null])

<label class="inline-flex items-start gap-2.5 cursor-pointer">
    <input type="checkbox"
           {{ $attributes->merge(['class' => 'mt-0.5 h-4 w-4 rounded-md border-neutral-300 text-brand-accent shadow-inner-soft focus:ring-2 focus:ring-brand-accent/20 transition-colors']) }}>
    @if($label)
        <span class="text-sm text-neutral-700">{{ $label }}</span>
    @else
        {{ $slot }}
    @endif
</label>
```

---

### 4.5 `radio`

**Path**: `resources/views/components/public/radio.blade.php`

Come checkbox ma con `type="radio"` e `rounded-full`.

---

### 4.6 `switch`

**Path**: `resources/views/components/public/switch.blade.php`

Toggle switch stile iOS.

```html
@props(['checked' => false, 'label' => null])

<label class="inline-flex items-center gap-3 cursor-pointer">
    <button type="button" role="switch"
            :aria-checked="on.toString()"
            x-data="{ on: @js($checked) }"
            @click="on = !on; $dispatch('input', on)"
            :class="on ? 'bg-brand-accent' : 'bg-neutral-300'"
            class="relative inline-flex h-6 w-11 shrink-0 rounded-full transition-colors duration-200 ease-apple focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-accent focus-visible:ring-offset-2">
        <span :class="on ? 'translate-x-5' : 'translate-x-0.5'"
              class="pointer-events-none mt-0.5 h-5 w-5 rounded-full bg-brand-surface shadow-soft-sm transition-transform duration-200 ease-apple"></span>
    </button>
    @if($label)
        <span class="text-sm text-neutral-700">{{ $label }}</span>
    @endif
</label>
```

---

### 4.7 `field-wrapper`

**Path**: `resources/views/components/public/field-wrapper.blade.php`

Wrapper standard per un campo form: label + help + input (slot) + error.

**Props**: `label`, `for`, `help` (string/null), `error` (string/null), `required` (bool).

```html
@props(['label', 'for', 'help' => null, 'error' => null, 'required' => false])

<div {{ $attributes->merge(['class' => 'space-y-1.5']) }}>
    <label for="{{ $for }}" class="block text-sm font-medium text-neutral-700">
        {{ $label }}
        @if($required)
            <span class="text-danger" aria-hidden="true">*</span>
        @endif
    </label>
    @if($help)
        <p id="{{ $for }}-help" class="text-xs text-neutral-500">{{ $help }}</p>
    @endif
    {{ $slot }}
    @if($error)
        <p id="{{ $for }}-error" role="alert" class="text-xs text-danger flex items-center gap-1">
            <x-heroicon-m-x-circle class="h-3.5 w-3.5 shrink-0" />
            {{ $error }}
        </p>
    @endif
</div>
```

---

### 4.8 `form-section`

**Path**: `resources/views/components/public/form-section.blade.php`

Raggruppamento logico di campi con titolo e descrizione opzionale.

```html
@props(['title', 'description' => null])

<fieldset {{ $attributes->merge(['class' => 'space-y-6']) }}>
    <legend class="text-lg font-semibold text-neutral-900">{{ $title }}</legend>
    @if($description)
        <p class="text-sm text-neutral-600">{{ $description }}</p>
    @endif
    <div class="space-y-4">
        {{ $slot }}
    </div>
</fieldset>
```

---

### 4.9 `form-actions`

**Path**: `resources/views/components/public/form-actions.blade.php`

Barra di azioni in fondo al form (submit, cancella).

```html
<div {{ $attributes->merge(['class' => 'flex items-center justify-end gap-3 pt-6 border-t border-neutral-200']) }}>
    {{ $slot }}
</div>
```

---

## 5. Card prodotto

### 5.1 `product-card`

**Path**: `resources/views/components/public/product-card.blade.php`

La card e il componente centrale dell'e-commerce. Ispirata al pattern Top Beauty (brand label uppercase + nome + prezzo + hover CTA) con estetica Apple SkinTemple.

**Props**:
| Prop | Tipo | Default | Descrizione |
|------|------|---------|-------------|
| `product` | object | required | Oggetto prodotto con: `name`, `slug`, `brand`, `price`, `compare_price`, `image_url`, `image_alt`, `is_new`, `is_promo`, `promo_label` |

**Stati**:
- Default: immagine + brand + nome + prezzo
- Hover (desktop): appare CTA "Aggiungi al carrello" overlay sull'immagine + icona cuore
- Badge: "Novita" (accent), "Promo" (danger), custom label

```html
@props(['product'])

<article class="group relative flex flex-col rounded-2xl bg-brand-surface shadow-soft-sm hover:shadow-soft-md transition-shadow duration-300 ease-apple overflow-hidden">
    <!-- Immagine -->
    <a href="/prodotti/{{ $product->slug }}" class="relative aspect-square overflow-hidden">
        <img src="{{ $product->image_url }}" alt="{{ $product->image_alt ?? $product->name }}"
             class="h-full w-full object-cover transition-transform duration-500 ease-apple group-hover:scale-105"
             loading="lazy" decoding="async">

        <!-- Badge -->
        @if($product->is_new)
            <span class="absolute top-3 left-3">
                <x-public.badge variant="accent" size="sm">Novita</x-public.badge>
            </span>
        @elseif($product->is_promo)
            <span class="absolute top-3 left-3">
                <x-public.badge variant="danger" size="sm">{{ $product->promo_label ?? 'Promo' }}</x-public.badge>
            </span>
        @endif

        <!-- Wishlist -->
        <button aria-label="Aggiungi alla lista desideri"
                class="absolute top-3 right-3 flex h-9 w-9 items-center justify-center rounded-full bg-brand-surface/80 backdrop-blur-sm text-neutral-500 opacity-0 group-hover:opacity-100 hover:text-danger transition-all duration-200 ease-apple shadow-soft-sm">
            <x-heroicon-o-heart class="h-5 w-5" />
        </button>

        <!-- CTA hover overlay (desktop) -->
        <div class="absolute inset-x-3 bottom-3 opacity-0 translate-y-2 group-hover:opacity-100 group-hover:translate-y-0 transition-all duration-200 ease-apple hidden lg:block">
            <button class="w-full rounded-full bg-brand-accent py-2.5 text-sm font-semibold text-brand-surface hover:bg-brand-accent-deep transition-colors shadow-soft-md">
                Aggiungi al carrello
            </button>
        </div>
    </a>

    <!-- Info -->
    <div class="flex flex-col gap-1 p-3 sm:p-4">
        <span class="text-2xs font-medium uppercase tracking-widest text-neutral-500">
            {{ $product->brand }}
        </span>
        <h3 class="text-sm font-medium leading-snug text-neutral-900 line-clamp-2">
            <a href="/prodotti/{{ $product->slug }}" class="hover:text-brand-accent transition-colors">
                {{ $product->name }}
            </a>
        </h3>
        <div class="flex items-center gap-2 mt-1">
            @if($product->compare_price)
                <span class="text-sm text-neutral-400 line-through">€{{ number_format($product->compare_price, 2, ',', '.') }}</span>
                <span class="text-base font-semibold text-brand-accent">€{{ number_format($product->price, 2, ',', '.') }}</span>
            @else
                <span class="text-base font-semibold text-neutral-900">€{{ number_format($product->price, 2, ',', '.') }}</span>
            @endif
        </div>
    </div>
</article>
```

**Accessibilita**:
- L'intera card e navigabile: il link principale e sull'immagine e sul nome
- Wishlist button ha `aria-label`
- Badge usa colore + testo (non solo colore)
- `line-clamp-2` con testo completo accessibile agli screen reader

---

## 6. Listing

### 6.1 `product-grid`

**Path**: `resources/views/components/public/product-grid.blade.php`

```html
@props(['products', 'cols' => 4])

<div {{ $attributes->merge(['class' => 'grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-' . $cols . ' gap-4 sm:gap-6']) }}>
    @foreach($products as $product)
        <x-public.product-card :product="$product" />
    @endforeach
</div>
```

---

### 6.2 `product-list-item`

**Path**: `resources/views/components/public/product-list-item.blade.php`

Variante lista orizzontale per vista alternativa nello shop.

```html
@props(['product'])

<article class="flex gap-4 rounded-2xl bg-brand-surface p-4 shadow-soft-sm">
    <a href="/prodotti/{{ $product->slug }}" class="shrink-0 w-24 h-24 sm:w-32 sm:h-32 rounded-xl overflow-hidden">
        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="h-full w-full object-cover" loading="lazy">
    </a>
    <div class="flex flex-col justify-between flex-1 min-w-0">
        <div>
            <span class="text-2xs font-medium uppercase tracking-widest text-neutral-500">{{ $product->brand }}</span>
            <h3 class="text-sm font-medium text-neutral-900 mt-0.5">
                <a href="/prodotti/{{ $product->slug }}" class="hover:text-brand-accent transition-colors">{{ $product->name }}</a>
            </h3>
        </div>
        <div class="flex items-center justify-between mt-2">
            <span class="text-base font-semibold text-neutral-900">€{{ number_format($product->price, 2, ',', '.') }}</span>
            <x-public.button size="sm">Aggiungi</x-public.button>
        </div>
    </div>
</article>
```

---

### 6.3 `product-quick-view-modal`

**Path**: `resources/views/components/public/product-quick-view-modal.blade.php`

Modale con anteprima rapida del prodotto (immagine, nome, prezzo, varianti, CTA). Gestita da componente Livewire `Catalog\QuickView`.

---

## 7. Filtri

### 7.1 `filter-sidebar`

**Path**: `resources/views/components/public/filter-sidebar.blade.php`

Container per tutti i gruppi filtro. Su desktop e sidebar fissa, su mobile e drawer bottom sheet.

```html
<aside {{ $attributes->merge(['class' => 'space-y-6']) }} aria-label="Filtri prodotto">
    {{ $slot }}
</aside>
```

---

### 7.2 `filter-group`

**Path**: `resources/views/components/public/filter-group.blade.php`

Gruppo filtro collassabile con Alpine.js.

**Props**: `title` (string), `open` (bool, default true).

```html
@props(['title', 'open' => true])

<div x-data="{ expanded: @js($open) }" class="border-b border-neutral-200 pb-4">
    <button @click="expanded = !expanded" class="flex w-full items-center justify-between py-2 text-sm font-semibold text-neutral-900">
        {{ $title }}
        <x-heroicon-m-chevron-down class="h-4 w-4 text-neutral-500 transition-transform duration-200" ::class="expanded && 'rotate-180'" />
    </button>
    <div x-show="expanded" x-collapse class="mt-2 space-y-2">
        {{ $slot }}
    </div>
</div>
```

---

### 7.3 `filter-checkbox`

**Path**: `resources/views/components/public/filter-checkbox.blade.php`

**Props**: `label`, `count` (int/null), `value`, `name`.

```html
@props(['label', 'count' => null, 'value', 'name'])

<label class="flex items-center justify-between cursor-pointer group">
    <span class="flex items-center gap-2">
        <input type="checkbox" name="{{ $name }}" value="{{ $value }}"
               class="h-4 w-4 rounded-md border-neutral-300 text-brand-accent focus:ring-2 focus:ring-brand-accent/20">
        <span class="text-sm text-neutral-700 group-hover:text-neutral-900 transition-colors">{{ $label }}</span>
    </span>
    @if($count !== null)
        <span class="text-xs text-neutral-400">{{ $count }}</span>
    @endif
</label>
```

---

### 7.4 `filter-price-range`

**Path**: `resources/views/components/public/filter-price-range.blade.php`

Slider doppio per range prezzo. Gestito con Alpine.js.

---

### 7.5 `filter-color-swatch`

**Path**: `resources/views/components/public/filter-color-swatch.blade.php`

**Props**: `colors` (array di `['name' => '...', 'hex' => '...', 'value' => '...']`).

```html
@props(['colors' => []])

<div class="flex flex-wrap gap-2">
    @foreach($colors as $color)
        <label class="relative cursor-pointer" title="{{ $color['name'] }}">
            <input type="checkbox" name="color[]" value="{{ $color['value'] }}" class="peer sr-only">
            <span class="flex h-7 w-7 items-center justify-center rounded-full border-2 border-transparent peer-checked:border-brand-accent transition-colors"
                  style="background-color: {{ $color['hex'] }}">
                <x-heroicon-m-check class="h-3.5 w-3.5 text-white opacity-0 peer-checked:opacity-100 transition-opacity" />
            </span>
        </label>
    @endforeach
</div>
```

---

### 7.6 `filter-active-pills`

**Path**: `resources/views/components/public/filter-active-pills.blade.php`

Mostra i filtri attivi come chip rimuovibili + button "Rimuovi tutti".

```html
@props(['filters' => []])

@if(count($filters) > 0)
<div class="flex flex-wrap items-center gap-2 mb-4">
    @foreach($filters as $filter)
        <x-public.chip wire:click="removeFilter('{{ $filter['key'] }}', '{{ $filter['value'] }}')">
            {{ $filter['label'] }}
        </x-public.chip>
    @endforeach
    <button wire:click="clearAllFilters" class="text-xs text-brand-accent hover:underline">
        Rimuovi tutti
    </button>
</div>
@endif
```

---

## 8. Dettaglio prodotto

### 8.1 `product-gallery`

**Path**: `resources/views/components/public/product-gallery.blade.php`

**Props**: `images` (collection di oggetti con `url`, `alt`).

**Desktop**: thumbnails verticali a sinistra (col-span-1) + immagine principale grande (col-span-5). Click su thumbnail cambia immagine con transizione fade.

**Mobile**: carousel swipe orizzontale con CSS scroll-snap + pallini indicatori.

---

### 8.2 `product-info`

**Path**: `resources/views/components/public/product-info.blade.php`

Blocco informazioni prodotto: brand label, nome (H1), prezzo, descrizione breve, varianti, quantita, CTA.

---

### 8.3 `variant-selector`

**Path**: `resources/views/components/public/variant-selector.blade.php`

**Props**: `type` (`color`, `size`, `generic`), `options`, `selected`.

Per colore: cerchi colorati con bordo selezionato. Per taglia/generico: pill selezionabili.

---

### 8.4 `quantity-stepper`

**Path**: `resources/views/components/public/quantity-stepper.blade.php`

**Props**: `value` (int), `min` (int, 1), `max` (int).

```html
@props(['value' => 1, 'min' => 1, 'max' => 99])

<div x-data="{ qty: @js($value) }" class="inline-flex items-center rounded-xl border border-neutral-300">
    <button @click="qty > {{ $min }} && qty--" :disabled="qty <= {{ $min }}"
            class="flex h-10 w-10 items-center justify-center text-neutral-600 hover:text-neutral-900 disabled:opacity-30 disabled:cursor-not-allowed transition-colors"
            aria-label="Diminuisci quantita">
        <x-heroicon-m-minus class="h-4 w-4" />
    </button>
    <input type="number" x-model="qty" :min="{{ $min }}" :max="{{ $max }}"
           class="h-10 w-12 border-0 bg-transparent text-center text-sm font-semibold text-neutral-900 focus:ring-0 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
           aria-label="Quantita">
    <button @click="qty < {{ $max }} && qty++" :disabled="qty >= {{ $max }}"
            class="flex h-10 w-10 items-center justify-center text-neutral-600 hover:text-neutral-900 disabled:opacity-30 disabled:cursor-not-allowed transition-colors"
            aria-label="Aumenta quantita">
        <x-heroicon-m-plus class="h-4 w-4" />
    </button>
</div>
```

---

### 8.5 `add-to-cart-button`

**Path**: `resources/views/components/public/add-to-cart-button.blade.php`

Pulsante con stati multipli gestiti da Livewire.

**Stati**: idle ("Aggiungi al carrello"), loading (spinner), success ("Aggiunto!" con check, torna a idle dopo 2s), error ("Errore, riprova").

---

### 8.6 `wishlist-toggle`

**Path**: `resources/views/components/public/wishlist-toggle.blade.php`

Icona cuore che alterna outline/solid. Gestita da Livewire.

---

### 8.7 `share-buttons`

**Path**: `resources/views/components/public/share-buttons.blade.php`

Bottoni condivisione (copia link, WhatsApp, email). No dipendenze social SDK.

---

### 8.8 `product-tabs`

**Path**: `resources/views/components/public/product-tabs.blade.php`

Tab Descrizione / Ingredienti / Modo d'uso / Recensioni. Gestito con Alpine.js `x-data`.

---

### 8.9 `related-products`

**Path**: `resources/views/components/public/related-products.blade.php`

Sezione con titolo + carousel/griglia prodotti correlati.

---

### 8.10 `recently-viewed`

**Path**: `resources/views/components/public/recently-viewed.blade.php`

Carousel prodotti visti di recente. Dati da `localStorage` o sessione.

---

## 9. Carrello

### 9.1 `cart-drawer`

Componente Blade wrapper per il drawer carrello. Il contenuto e gestito dal Livewire `Cart\CartDrawer`. Su mobile: bottom sheet. Su desktop: drawer laterale destro.

---

### 9.2 `cart-item`

**Path**: `resources/views/components/public/cart-item.blade.php`

Singolo articolo nel carrello: immagine mini, nome, variante, prezzo, quantita (stepper), rimuovi.

---

### 9.3 `cart-summary`

**Path**: `resources/views/components/public/cart-summary.blade.php`

Riepilogo: subtotale, spedizione, sconto coupon, totale. Con CTA "Vai al checkout".

---

### 9.4 `coupon-input`

**Path**: `resources/views/components/public/coupon-input.blade.php`

Campo testo + button "Applica". Gestito da Livewire.

---

### 9.5 `empty-cart-state`

**Path**: `resources/views/components/public/empty-cart-state.blade.php`

Stato vuoto con icona grande, messaggio, CTA verso lo shop.

```html
<div class="flex flex-col items-center justify-center py-12 text-center">
    <x-heroicon-o-shopping-bag class="h-12 w-12 text-neutral-300 mb-4" />
    <h3 class="text-lg font-semibold text-neutral-900 mb-1">Il tuo carrello e vuoto</h3>
    <p class="text-sm text-neutral-500 mb-6">Scopri i nostri prodotti e aggiungi qualcosa al carrello.</p>
    <x-public.button href="/shop">Esplora lo shop</x-public.button>
</div>
```

---

## 10. Checkout

### 10.1 `checkout-stepper`

**Path**: `resources/views/components/public/checkout-stepper.blade.php`

Indicatore step orizzontale: Indirizzo → Spedizione → Pagamento → Conferma. Step corrente evidenziato, completati con check.

---

### 10.2 `checkout-step`

**Path**: `resources/views/components/public/checkout-step.blade.php`

Wrapper per singolo step di checkout. Props: `title`, `active` (bool).

---

### 10.3 `address-form`

**Path**: `resources/views/components/public/address-form.blade.php`

Form indirizzo completo: nome, cognome, indirizzo, citta, CAP, provincia, telefono. Con validazione live Livewire.

---

### 10.4 `shipping-method-card`

**Path**: `resources/views/components/public/shipping-method-card.blade.php`

Card selezionabile per metodo spedizione: nome, tempi, prezzo. Radio button integrato.

```html
@props(['method', 'selected' => false])

<label class="flex items-center gap-4 cursor-pointer rounded-xl border p-4 transition-colors duration-200
              {{ $selected ? 'border-brand-accent bg-brand-accent/5' : 'border-neutral-300 hover:border-neutral-400' }}">
    <input type="radio" name="shipping_method" value="{{ $method->id }}"
           @checked($selected)
           class="h-4 w-4 border-neutral-300 text-brand-accent focus:ring-brand-accent/20">
    <div class="flex-1">
        <p class="text-sm font-medium text-neutral-900">{{ $method->name }}</p>
        <p class="text-xs text-neutral-500">{{ $method->delivery_time }}</p>
    </div>
    <span class="text-sm font-semibold text-neutral-900">
        {{ $method->price > 0 ? '€' . number_format($method->price, 2, ',', '.') : 'Gratuita' }}
    </span>
</label>
```

---

### 10.5 `payment-method-card`

Analogo a `shipping-method-card`, con icona metodo pagamento.

---

### 10.6 `order-summary-sticky`

**Path**: `resources/views/components/public/order-summary-sticky.blade.php`

Riepilogo ordine che su desktop sta in una colonna sticky a destra (`sticky top-24`). Su mobile e collassabile in alto.

---

### 10.7 `terms-checkbox`

**Path**: `resources/views/components/public/terms-checkbox.blade.php`

Checkbox con testo legale: "Ho letto e accetto i Termini di servizio e la Privacy Policy" con link.

---

## 11. Account

### 11.1 `account-sidebar-nav`

Navigazione laterale account: Dashboard, Ordini, Indirizzi, Lista desideri, Profilo, Disconnetti.

---

### 11.2 `order-card`

Card ordine con: numero, data, stato (badge), totale, link dettaglio.

---

### 11.3 `order-status-badge`

Badge colorato per stato ordine: In elaborazione (info), Spedito (warning), Consegnato (success), Annullato (danger).

---

### 11.4 `address-card`

Card indirizzo con: nome completo, indirizzo formattato, badge "Predefinito", azioni Modifica/Elimina.

---

### 11.5 `wishlist-grid`

Griglia prodotti wishlist, riutilizza `product-card` con button "Rimuovi" al posto di "Aggiungi al carrello".

---

### 11.6 `profile-form`

Form profilo utente: nome, cognome, email (readonly), telefono. Con validazione Livewire.

---

## 12. CMS — Block Renderer

### 12.1 `block-renderer`

**Path**: `resources/views/components/public/block-renderer.blade.php`

Renderizza un array di blocchi CMS iterando e facendo switch sul tipo.

```html
@props(['blocks' => []])

@foreach($blocks as $block)
    @switch($block['type'])
        @case('hero')
            <x-public.block-hero :data="$block['data']" />
            @break
        @case('text')
            <x-public.block-text :data="$block['data']" />
            @break
        @case('image')
            <x-public.block-image :data="$block['data']" />
            @break
        @case('slider')
            <x-public.block-slider :data="$block['data']" />
            @break
        @case('product-grid')
            <x-public.block-product-grid :data="$block['data']" />
            @break
        @case('cta')
            <x-public.block-cta :data="$block['data']" />
            @break
        @case('features')
            <x-public.block-features :data="$block['data']" />
            @break
        @case('features-list')
            <x-public.block-features-list :data="$block['data']" />
            @break
        @case('text-quote')
            <x-public.block-text-quote :data="$block['data']" />
            @break
        @case('video')
            <x-public.block-video :data="$block['data']" />
            @break
        @case('html')
            <x-public.block-html :data="$block['data']" />
            @break
        @case('spacer')
            <x-public.block-spacer :data="$block['data']" />
            @break
        @case('newsletter')
            <x-public.block-newsletter :data="$block['data']" />
            @break
        @case('testimonial')
            <x-public.block-testimonial :data="$block['data']" />
            @break
        @case('brand-grid')
            <x-public.block-brand-grid :data="$block['data']" />
            @break
        @case('contact-form')
            <x-public.block-contact-form :data="$block['data']" />
            @break
    @endswitch
@endforeach
```

### 12.2-12.17 Blocchi CMS

Ogni blocco CMS segue lo stesso pattern:
- Riceve un array `$data` con i campi specifici del blocco
- Wrappa in `<x-public.section>` con padding/bg appropriato
- Usa `<x-public.container>` per centrare

Blocchi specificati:

| Componente | Props `$data` | Descrizione |
|------------|--------------|-------------|
| `block-hero` | `title`, `subtitle`, `cta_text`, `cta_url`, `image_url`, `layout` (`center`/`split`) | Hero section full-width |
| `block-text` | `content` (HTML/markdown) | Blocco testo ricco con prose typography |
| `block-image` | `url`, `alt`, `caption`, `full_width` (bool) | Immagine singola con caption opzionale |
| `block-slider` | `slides` (array di `{image_url, title, cta_text, cta_url}`) | Carousel immagini hero |
| `block-product-grid` | `title`, `product_ids` o `category_slug`, `limit` | Griglia prodotti dinamica |
| `block-cta` | `title`, `text`, `cta_text`, `cta_url`, `bg` | Banner call-to-action |
| `block-features` | `items` (array di `{icon, title, description}`) | Griglia 3-4 colonne feature |
| `block-features-list` | `title`, `items` (array di `{icon, title, description}`) | Lista feature verticale |
| `block-text-quote` | `quote`, `author`, `role` | Citazione con stile display |
| `block-video` | `url` (YouTube/Vimeo), `title`, `poster` | Video embed responsive |
| `block-html` | `content` | HTML libero (sanitizzato lato admin) |
| `block-spacer` | `size` (`sm`/`md`/`lg`) | Spazio verticale vuoto |
| `block-newsletter` | `title`, `subtitle` | Form iscrizione newsletter |
| `block-testimonial` | `testimonials` (array di `{text, author, role, avatar}`) | Carousel/griglia testimonial |
| `block-brand-grid` | `brands` (array di `{name, logo_url, url}`) | Griglia loghi brand |
| `block-contact-form` | `title`, `email_to` | Form contatto con validazione |

---

## 13. Blog

### 13.1 `blog-card`

Card articolo blog: immagine, data, titolo, excerpt, autore. Stile coerente con product-card.

---

### 13.2 `blog-author-bio`

Box autore in fondo all'articolo: avatar, nome, bio breve.

---

### 13.3 `blog-toc`

Table of contents generata dai heading dell'articolo. Sidebar sticky su desktop, collapsible su mobile.

---

## 14. Search

### 14.1 `search-overlay`

**Path**: `resources/views/components/public/search-overlay.blade.php`

Overlay full-screen (o quasi) con campo ricerca prominente. Gestito da Livewire `Catalog\ProductSearch`.

```html
<div x-show="searchOpen"
     x-transition:enter="transition ease-apple duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-trap.noscroll="searchOpen"
     @keydown.escape.window="searchOpen = false"
     class="fixed inset-0 z-60 bg-brand-surface/95 backdrop-blur-xl"
     role="dialog" aria-modal="true" aria-label="Ricerca">
    <div class="mx-auto max-w-2xl px-4 pt-20 sm:pt-32">
        <livewire:public.catalog.product-search />
    </div>
    <button @click="searchOpen = false" aria-label="Chiudi ricerca"
            class="absolute top-4 right-4 p-2 text-neutral-500 hover:text-neutral-900 transition-colors">
        <x-heroicon-o-x-mark class="h-6 w-6" />
    </button>
</div>
```

---

### 14.2 `search-input`

Campo ricerca grande con icona. Props: `placeholder`.

---

### 14.3 `search-result-product`

Singolo risultato prodotto nella ricerca: immagine mini, nome, brand, prezzo.

---

### 14.4 `search-result-page`

Risultato pagina/blog nella ricerca: titolo, tipo (pagina/articolo), excerpt.

---

### 14.5 `search-empty-state`

Stato nessun risultato con suggerimenti.

---

### 14.6 `search-recent`

Ricerche recenti (da `localStorage`). Lista con testi cliccabili + "Cancella cronologia".

---

## 15. Feedback

### 15.1 `toast`

**Path**: `resources/views/components/public/toast.blade.php`

Notifiche toast in alto a destra. Varianti: success, error, warning, info. Auto-dismiss dopo 5s.

```html
<div x-data="{ toasts: [] }"
     @toast.window="toasts.push({...$event.detail, id: Date.now()}); setTimeout(() => toasts.shift(), 5000)"
     class="fixed top-4 right-4 z-50 space-y-2 pointer-events-none">
    <template x-for="toast in toasts" :key="toast.id">
        <div x-transition:enter="transition ease-apple duration-300"
             x-transition:enter-start="opacity-0 translate-x-4"
             x-transition:enter-end="opacity-100 translate-x-0"
             x-transition:leave="transition ease-apple duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0 translate-x-4"
             class="pointer-events-auto flex items-center gap-3 rounded-2xl bg-brand-surface px-4 py-3 shadow-soft-lg border border-neutral-200/50 max-w-sm">
            <!-- icona variant-dependent -->
            <p class="text-sm text-neutral-900" x-text="toast.message"></p>
            <button @click="toasts = toasts.filter(t => t.id !== toast.id)" aria-label="Chiudi" class="ml-auto shrink-0 p-1 text-neutral-400 hover:text-neutral-700">
                <x-heroicon-m-x-mark class="h-4 w-4" />
            </button>
        </div>
    </template>
</div>
```

---

### 15.2 `alert`

**Path**: `resources/views/components/public/alert.blade.php`

Alert inline. Props: `variant` (success/warning/danger/info), `dismissible` (bool).

```html
@props(['variant' => 'info', 'dismissible' => false])

@php
$styles = [
    'info' => 'bg-info-bg border-info/20 text-info',
    'success' => 'bg-success-bg border-success/20 text-success',
    'warning' => 'bg-warning-bg border-warning/20 text-warning',
    'danger' => 'bg-danger-bg border-danger/20 text-danger',
];
$icons = [
    'info' => 'information-circle',
    'success' => 'check-circle',
    'warning' => 'exclamation-triangle',
    'danger' => 'x-circle',
];
@endphp

<div x-data="{ show: true }" x-show="show" role="alert"
     {{ $attributes->merge(['class' => 'flex items-start gap-3 rounded-2xl border p-4 ' . ($styles[$variant] ?? $styles['info'])]) }}>
    <x-public.icon :name="$icons[$variant] ?? 'information-circle'" size="md" class="shrink-0 mt-0.5" />
    <div class="flex-1 text-sm">{{ $slot }}</div>
    @if($dismissible)
        <button @click="show = false" aria-label="Chiudi" class="shrink-0 p-0.5 opacity-60 hover:opacity-100 transition-opacity">
            <x-heroicon-m-x-mark class="h-4 w-4" />
        </button>
    @endif
</div>
```

---

### 15.3 `inline-message`

Messaggio inline sotto un campo form o in una sezione. Piu leggero dell'alert.

---

### 15.4 `confirm-modal`

Modale di conferma azione distruttiva. Props: `title`, `message`, `confirmText`, `cancelText`, `variant` (destructive/default).

---

## 16. Trust signals

### 16.1 `feature-bar`

**Path**: `resources/views/components/public/feature-bar.blade.php`

Barra orizzontale con 3-4 trust signals. Tipicamente sotto l'hero o sopra il footer.

```html
<div class="border-y border-neutral-100 bg-neutral-50">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 divide-y sm:divide-y-0 sm:divide-x divide-neutral-200">
            <div class="flex items-center gap-3 py-4 sm:px-6 lg:justify-center">
                <x-heroicon-o-truck class="h-6 w-6 text-brand-accent shrink-0" />
                <div>
                    <p class="text-sm font-medium text-neutral-900">Spedizione gratuita</p>
                    <p class="text-xs text-neutral-500">Per ordini superiori a €59</p>
                </div>
            </div>
            <div class="flex items-center gap-3 py-4 sm:px-6 lg:justify-center">
                <x-heroicon-o-flag class="h-6 w-6 text-brand-accent shrink-0" />
                <div>
                    <p class="text-sm font-medium text-neutral-900">100% Made in Italy</p>
                    <p class="text-xs text-neutral-500">Qualita e affidabilita italiana</p>
                </div>
            </div>
            <div class="flex items-center gap-3 py-4 sm:px-6 lg:justify-center">
                <x-heroicon-o-lock-closed class="h-6 w-6 text-brand-accent shrink-0" />
                <div>
                    <p class="text-sm font-medium text-neutral-900">Pagamento sicuro</p>
                    <p class="text-xs text-neutral-500">Transazioni protette e criptate</p>
                </div>
            </div>
            <div class="flex items-center gap-3 py-4 sm:px-6 lg:justify-center">
                <x-heroicon-o-chat-bubble-left-right class="h-6 w-6 text-brand-accent shrink-0" />
                <div>
                    <p class="text-sm font-medium text-neutral-900">Assistenza dedicata</p>
                    <p class="text-xs text-neutral-500">Supporto rapido e competente</p>
                </div>
            </div>
        </div>
    </div>
</div>
```

---

### 16.2 `made-in-italy-badge`

Badge compatto con bandierina italiana e testo "Made in Italy".

---

### 16.3 `secure-payment-icons`

Fila di loghi pagamento: Visa, Mastercard, PayPal, ecc. SVG.

---

### 16.4 `einvoice-badge`

Badge "Fatturazione elettronica disponibile" — placeholder configurabile via admin.

---

## 17. Glass effects

### 17.1 `glass-card`

**Path**: `resources/views/components/public/glass-card.blade.php`

```html
<div {{ $attributes->merge(['class' => 'rounded-2xl bg-glass-light backdrop-blur-xl border border-white/20 shadow-glass']) }}>
    {{ $slot }}
</div>
```

---

### 17.2 `glass-overlay`

**Path**: `resources/views/components/public/glass-overlay.blade.php`

Overlay glass per backdrop modali/drawer.

```html
<div {{ $attributes->merge(['class' => 'fixed inset-0 bg-glass-dark backdrop-blur-md']) }}
     aria-hidden="true">
</div>
```

---

## 18. Mobile-specific

### 18.1 `bottom-tab-bar`

**Path**: `resources/views/components/public/bottom-tab-bar.blade.php`

Descritto in dettaglio in `11-mobile-and-pwa.md`. Visibile solo `< lg`.

---

### 18.2 `mobile-search-button`

Button "Cerca" nella top bar mobile. Emette evento per aprire `search-overlay`.

```html
<button @click="$dispatch('open-search')" aria-label="Cerca"
        class="flex items-center gap-2 rounded-full bg-neutral-100 px-4 py-2 text-sm text-neutral-500 lg:hidden w-full">
    <x-heroicon-o-magnifying-glass class="h-4 w-4" />
    <span>Cerca prodotti...</span>
</button>
```

---

## Riepilogo componenti Blade

| Categoria | Numero componenti |
|-----------|-------------------|
| Layout | 6 |
| Navigazione | 5 |
| Atomi | 11 |
| Form | 9 |
| Card prodotto | 1 |
| Listing | 3 |
| Filtri | 6 |
| Dettaglio prodotto | 10 |
| Carrello | 5 |
| Checkout | 7 |
| Account | 6 |
| CMS blocks | 17 |
| Blog | 3 |
| Search | 6 |
| Feedback | 4 |
| Trust signals | 4 |
| Glass effects | 2 |
| Mobile-specific | 2 |
| **Totale** | **97** |

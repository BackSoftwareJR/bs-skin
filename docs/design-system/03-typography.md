# 03 — Tipografia

## Font families

| Ruolo | Font | Source | Fallback | Uso |
|-------|------|--------|----------|-----|
| Display | Cormorant Garamond | Google Fonts | Georgia, serif | H1, H2 hero, claim, titoli sezione principali |
| Sans (UI) | Inter | Google Fonts | system-ui, -apple-system, BlinkMacSystemFont, Segoe UI, sans-serif | Body, navigazione, UI, form, card, button |
| Mono | JetBrains Mono | Google Fonts | monospace | Codici SKU, codici sconto, dati tecnici (raro) |

### Perche Cormorant Garamond e non Playfair Display
Cormorant ha proporzioni piu raffinate e un contrasto asta/filetto piu elegante a dimensioni display. Playfair e piu "editoriale-moda", Cormorant e piu "lusso-sobrio" — coerente con l'identita Apple-minimal di SkinTemple. Inoltre Cormorant ha un peso ottico migliore a 48-80px, dove Playfair tende a sembrare troppo pesante.

### Perche Inter e non SF Pro
SF Pro e il font di sistema Apple, ottimo ma non disponibile come web font su Google Fonts per licensing. Inter e stato progettato specificamente per schermi, ha metriche simili a SF Pro, ed e open source. Con il fallback system-ui, su macOS/iOS viene comunque mostrato SF Pro quando Inter non e ancora caricato.

## Font weights utilizzati

| Font | Weight | Nome | Uso |
|------|--------|------|-----|
| Inter | 300 | Light | Sottotitoli grandi, quote |
| Inter | 400 | Regular | Body text, placeholder |
| Inter | 500 | Medium | Label, navigazione, nome prodotto card |
| Inter | 600 | SemiBold | Button, prezzo, titoli piccoli |
| Inter | 700 | Bold | Enfasi forte (raro nel body, usato per importi totali) |
| Cormorant | 400 | Regular | Citazioni, testi decorativi |
| Cormorant | 500 | Medium | Titoli sezione |
| Cormorant | 600 | SemiBold | Hero H1, claim principali |

## Scala tipografica

Rapporto base: **1.250** (minor third) per body, con salti maggiori per i livelli display.

| Token | Size | Line-height | Uso tipico |
|-------|------|-------------|-----------|
| `text-2xs` | 11px (0.6875rem) | 14px (0.875rem) | Label brand uppercase su card prodotto, badge piccoli |
| `text-xs` | 12px (0.75rem) | 16px (1rem) | Caption, meta date, note a pie |
| `text-sm` | 14px (0.875rem) | 20px (1.25rem) | Testo secondario, nome prodotto su card, filtri sidebar |
| `text-base` | 16px (1rem) | 24px (1.5rem) | Body principale, prezzo, input text, button |
| `text-lg` | 18px (1.125rem) | 28px (1.75rem) | Lead paragraph, intro sezione |
| `text-xl` | 20px (1.25rem) | 28px (1.75rem) | H4, titoli card, titoli widget |
| `text-2xl` | 24px (1.5rem) | 32px (2rem) | H3, titoli sezione secondari |
| `text-3xl` | 30px (1.875rem) | 36px (2.25rem) | H2 interno pagina |
| `text-4xl` | 36px (2.25rem) | 40px (2.5rem) | H2 sezione con Cormorant |
| `text-5xl` | 48px (3rem) | 52px (3.25rem) | H1 pagina, display |
| `text-6xl` | 64px (4rem) | 64px (4rem) | Hero claim desktop |
| `text-7xl` | 80px (5rem) | 80px (5rem) | Hero gigante (raro, solo homepage desktop) |

## Letter-spacing presets

| Token | Valore | Uso |
|-------|--------|-----|
| `tracking-tighter` | -0.04em | Titoli display Cormorant (text-4xl e superiori) |
| `tracking-tight` | -0.02em | Titoli medi (text-2xl, text-3xl) |
| `tracking-normal` | 0em | Body text, input, navigazione |
| `tracking-wide` | 0.05em | Label piccole, testo button uppercase |
| `tracking-widest` | 0.15em | Brand name uppercase su card prodotto, badge |

## Line-height presets

| Token | Valore | Uso |
|-------|--------|-----|
| `leading-none` | 1.0 | Display gigante (text-7xl) dove il testo e una sola riga |
| `leading-tight` | 1.1 | Titoli display (text-5xl, text-6xl) |
| `leading-snug` | 1.2 | Titoli medi, nome prodotto multilinea |
| `leading-normal` | 1.5 | Body text standard |
| `leading-relaxed` | 1.6 | Paragrafi lunghi, articoli blog, descrizioni prodotto |

---

## Combinazioni consigliate

### Hero claim (homepage, landing)
```
Font: Cormorant Garamond
Weight: 600 (SemiBold)
Size: text-6xl (64px) desktop, text-4xl (36px) mobile
Tracking: tracking-tighter (-0.04em)
Leading: leading-tight (1.1)
Color: neutral.900 su sfondo chiaro, brand.surface su sfondo scuro
```
```html
<h1 class="font-display text-4xl lg:text-6xl font-semibold tracking-tighter leading-tight text-neutral-900">
    La cura della pelle, ridefinita.
</h1>
```

### Titolo sezione
```
Font: Cormorant Garamond
Weight: 500 (Medium)
Size: text-4xl (36px) desktop, text-3xl (30px) mobile
Tracking: tracking-tight (-0.02em)
Leading: leading-tight (1.1)
Color: neutral.900
```
```html
<h2 class="font-display text-3xl lg:text-4xl font-medium tracking-tight leading-tight text-neutral-900">
    Nuovi Arrivi
</h2>
```

### Label brand su card prodotto
```
Font: Inter
Weight: 500 (Medium)
Size: text-2xs (11px)
Tracking: tracking-widest (0.15em)
Transform: uppercase
Color: neutral.500
```
```html
<span class="font-sans text-2xs font-medium tracking-widest uppercase text-neutral-500">
    SkinTemple
</span>
```

### Nome prodotto su card
```
Font: Inter
Weight: 500 (Medium)
Size: text-sm (14px)
Tracking: tracking-normal
Leading: leading-snug (1.2)
Color: neutral.900
```
```html
<h3 class="font-sans text-sm font-medium tracking-normal leading-snug text-neutral-900">
    Crema Viso Idratante Acido Ialuronico
</h3>
```

### Prezzo
```
Font: Inter
Weight: 600 (SemiBold)
Size: text-base (16px)
Color: neutral.900 (prezzo corrente), neutral.400 line-through (prezzo originale), brand.accent (prezzo scontato)
```
```html
<div class="flex items-center gap-2">
    <span class="text-sm text-neutral-400 line-through">€49,00</span>
    <span class="text-base font-semibold text-brand-accent">€39,00</span>
</div>
```

### Body paragraph
```
Font: Inter
Weight: 400 (Regular)
Size: text-base (16px)
Leading: leading-relaxed (1.6)
Color: neutral.700
```
```html
<p class="font-sans text-base font-normal leading-relaxed text-neutral-700">
    Selezionati con rigore, formulati in Italia.
    Prodotti che rispettano la tua pelle e le tue aspettative.
</p>
```

### Button text
```
Font: Inter
Weight: 600 (SemiBold)
Size: text-sm (14px) per sm, text-base (16px) per md/lg
Tracking: tracking-normal o tracking-wide per variante uppercase
```
```html
<button class="font-sans text-sm font-semibold tracking-normal">
    Aggiungi al carrello
</button>
```

### Navigazione header
```
Font: Inter
Weight: 500 (Medium)
Size: text-sm (14px)
Tracking: tracking-normal
Color: neutral.700, hover brand.accent
```
```html
<a class="font-sans text-sm font-medium tracking-normal text-neutral-700 hover:text-brand-accent transition-colors">
    Prodotti
</a>
```

---

## Import Google Fonts

Snippet da includere in `resources/css/app.css` o nel `<head>`:

```css
@import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,400;1,500&family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap');
```

Per performance, preferire il caricamento via `<link rel="preconnect">` + `<link rel="stylesheet">` nel layout Blade:

```html
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,400;1,500&family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
```

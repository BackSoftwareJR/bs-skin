# 03 — Tipografia

## Font families

| Ruolo | Font | Source | Fallback | Uso |
|-------|------|--------|----------|-----|
| Display | Cormorant Garamond | Google Fonts | Georgia, serif | H1/H2 hero, claim, quote signature ("La cura della pelle, ridefinita.") |
| Sans (UI) | Inter | Google Fonts | HuaweiSans, system-ui, -apple-system, BlinkMacSystemFont, Segoe UI, sans-serif | Body, navigazione, UI, form, card, button |
| Mono | JetBrains Mono | Google Fonts | monospace | Codici SKU (es. `LENZ100X200TNT`), codici sconto, dati tecnici |

### Stack sans completo
```
Inter, "HuaweiSans", system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif
```

`HuaweiSans` come secondo fallback rispecchia lo stack del sito attualmente online (`skintemple.it/frontend/dist/`) e garantisce coerenza percepita se il cliente apre il sito su device dove HuaweiSans risulta installato. Su tutti gli altri device la rendering autorevole resta a Inter (caricato da Google Fonts) o al system font Apple/Windows.

### Perche Cormorant Garamond
Lo screenshot del vecchio sito mostra esattamente il pattern: titolo display serif + quote italica brevissima sotto. Cormorant ha proporzioni raffinate e un contrasto asta/filetto elegante a dimensioni display. Il pairing con Inter sans e collaudato e ad alta leggibilita.

### Perche Inter come primario
Pensato per schermi, metriche simili a SF Pro, open source. Con il fallback `system-ui` su macOS/iOS viene mostrato SF Pro durante il caricamento. La cosmesi finale e raffinata.

## Font weights utilizzati

| Font | Weight | Nome | Uso |
|------|--------|------|-----|
| Inter | 300 | Light | Sottotitoli grandi (raro) |
| Inter | 400 | Regular | Body text, placeholder, descrizioni card |
| Inter | 500 | Medium | Label nav, nome prodotto card, voci sidebar inattive |
| Inter | 600 | SemiBold | Button, prezzo, titoli piccoli, voce nav attiva |
| Inter | 700 | Bold | Enfasi forte (raro), totali importi importanti |
| Cormorant | 400 | Regular | Quote italica signature |
| Cormorant | 500 | Medium | Titoli sezione "Tratta tutti gli inestetismi" stile |
| Cormorant | 600 | SemiBold | Hero H1 ("La cura della pelle, ridefinita.") |
| Cormorant | 400 italic | Italic | Quote ("La qualita di ogni prodotto e il riflesso...") |

## Scala tipografica

Rapporto base **1.250** (minor third) per body, salti maggiori per i livelli display.

| Token | Size | Line-height | Uso tipico |
|-------|------|-------------|-----------|
| `text-2xs` | 11px | 14px | Label brand uppercase su card prodotto, badge piccoli |
| `text-xs` | 12px | 16px | Caption, meta date, sku |
| `text-sm` | 14px | 20px | Testo secondario, nome prodotto card, filtri sidebar, link "Scopri di piu >" |
| `text-base` | 16px | 24px | Body principale, prezzo, input text, button |
| `text-lg` | 18px | 28px | Lead paragraph, intro sezione, button CTA pieno |
| `text-xl` | 20px | 28px | H4, titoli card, titoli widget |
| `text-2xl` | 24px | 32px | H3, titoli sezione secondari |
| `text-3xl` | 30px | 36px | H2 interno pagina, titolo sezione "TRATTA TUTTI GLI INESTETISMI" |
| `text-4xl` | 36px | 40px | H2 sezione con Cormorant |
| `text-5xl` | 48px | 52px | H1 pagina, display ("La cura della pelle, ridefinita.") |
| `text-6xl` | 64px | 64px | Hero claim desktop |
| `text-7xl` | 80px | 80px | Hero gigante (raro) |

## Letter-spacing presets

| Token | Valore | Uso |
|-------|--------|-----|
| `tracking-tighter` | -0.04em | Titoli display Cormorant (text-4xl+) |
| `tracking-tight` | -0.02em | Titoli medi |
| `tracking-normal` | 0em | Body text, input, navigazione |
| `tracking-wide` | 0.05em | Label piccole, testo button uppercase ("SCOPRI") |
| `tracking-widest` | 0.15em | Brand name uppercase su card prodotto |

## Line-height presets

| Token | Valore | Uso |
|-------|--------|-----|
| `leading-none` | 1.0 | Display gigante una riga |
| `leading-tight` | 1.1 | Titoli display |
| `leading-snug` | 1.2 | Titoli medi, nome prodotto multilinea |
| `leading-normal` | 1.5 | Body standard |
| `leading-relaxed` | 1.6 | Paragrafi lunghi, articoli blog, descrizioni prodotto |

---

## Combinazioni consigliate

### Hero claim Cormorant centrato (homepage, "La cura della pelle, ridefinita.")
```
Font: Cormorant Garamond
Weight: 600 (SemiBold)
Size: text-5xl (48px) mobile, text-6xl (64px) desktop
Tracking: tracking-tighter (-0.04em)
Leading: leading-tight (1.1)
Color: ink (#0F172A) — mai brand teal su display gigante
Allineamento: centrato
```
```html
<h1 class="font-display text-5xl lg:text-6xl font-semibold tracking-tighter leading-tight text-ink text-center">
    La cura della pelle, ridefinita.
</h1>
```

### Quote italica signature (sotto hero)
```
Font: Cormorant Garamond
Weight: 400 italic
Size: text-base (16px) o text-lg (18px)
Color: ink-soft
Allineamento: centrato
```
```html
<p class="font-display italic text-base text-ink-soft text-center max-w-xl mx-auto">
    "La qualita di ogni prodotto e il riflesso della cura che mettiamo nel selezionarlo per te."
</p>
```

### Titolo sezione uppercase tipo "TRATTA TUTTI GLI INESTETISMI"
```
Font: Inter
Weight: 600 (SemiBold)
Size: text-2xl (24px) mobile, text-3xl (30px) desktop
Tracking: tracking-wide (0.05em)
Leading: leading-tight
Transform: uppercase
Color: ink
```
```html
<h2 class="font-sans text-2xl lg:text-3xl font-semibold tracking-wide uppercase leading-tight text-ink">
    Tratta tutti gli inestetismi
</h2>
```

### Label brand uppercase su card prodotto
```
Font: Inter
Weight: 500 (Medium)
Size: text-2xs (11px)
Tracking: tracking-widest (0.15em)
Transform: uppercase
Color: muted (#64748B)
```
```html
<span class="font-sans text-2xs font-medium tracking-widest uppercase text-muted">
    SkinTemple
</span>
```

### Nome prodotto su card (es. "PRESS FLOW", "LASER DIODO D-WAVE 2000 w")
```
Font: Inter
Weight: 600 (SemiBold)
Size: text-sm (14px)
Tracking: tracking-wide
Transform: uppercase per device, normale per skincare/monouso
Color: ink
```
```html
<h3 class="font-sans text-sm font-semibold tracking-wide uppercase leading-snug text-ink">
    Press Flow
</h3>
```

### SKU/etichetta sotto nome
```
Font: Inter
Weight: 400
Size: text-xs (12px)
Color: muted
```
```html
<p class="font-sans text-xs text-muted">press flow</p>
```

### Link "Scopri di piu >"
```
Font: Inter
Weight: 500
Size: text-sm
Color: brand.primary, hover brand.primary-hover
```
```html
<a href="..." class="inline-flex items-center gap-1 font-sans text-sm font-medium text-brand-primary hover:text-brand-primary-hover transition-colors">
    Scopri di piu
    <x-heroicon-m-chevron-right class="h-3.5 w-3.5" />
</a>
```

### Prezzo
```
Font: Inter
Weight: 600 (SemiBold)
Size: text-base (16px) standard, text-lg (18px) su pagina dettaglio
Color: ink (prezzo corrente), muted line-through (prezzo originale), brand.primary-700 (prezzo scontato — usare 700 per contrasto AA su tutti gli sfondi)
```
```html
<div class="flex items-center gap-2">
    <span class="text-sm text-muted line-through">€49,00</span>
    <span class="text-base font-semibold text-brand-primary-700">€39,00</span>
</div>
```

### Body paragraph
```
Font: Inter
Weight: 400
Size: text-base (16px)
Leading: leading-relaxed (1.6)
Color: ink-soft (#334155)
```
```html
<p class="font-sans text-base font-normal leading-relaxed text-ink-soft">
    Con la tecnologia multifunzione puoi lavorare contemporaneamente
    sia il viso che il corpo ottimizzando la seduta...
</p>
```

### Button CTA pieno (es. "SCOPRI")
```
Font: Inter
Weight: 500 (Medium)
Size: text-sm (14px) o text-base (16px) per size lg
Tracking: tracking-wide
Transform: uppercase
Color: surface (#FFFFFF) su bg brand.primary
```
```html
<button class="rounded-full bg-brand-primary px-9 py-3.5 font-sans text-sm font-medium tracking-wide uppercase text-surface hover:bg-brand-primary-hover transition-colors">
    Scopri
</button>
```

### Voce navigazione attiva con underline animato
```
Font: Inter
Weight: 500 (attiva 600)
Size: text-sm
Color: ink-soft (default), brand.primary (attiva)
```
```html
<a href="/" class="relative font-sans text-sm font-medium text-ink-soft hover:text-brand-primary transition-colors aria-[current=page]:text-brand-primary aria-[current=page]:font-semibold">
    Home
    <span class="absolute -bottom-1 left-0 h-0.5 w-full bg-brand-primary scale-x-0 origin-left transition-transform duration-200 ease-apple aria-[current=page]:scale-x-100" aria-hidden="true"></span>
</a>
```

### Sidebar filtro voce attiva
```
Font: Inter
Weight: 500
Size: text-sm
Color: brand.primary (attiva con underline), ink-soft (inattive)
```

---

## Import Google Fonts

Includere in `<head>` del layout Blade:

```html
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,400;1,500&family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
```

Per `HuaweiSans` non e necessario il caricamento via CDN: e fallback locale; se l'utente lo ha installato (alcuni device Huawei) verra usato automaticamente, altrimenti si passa al successivo nello stack.

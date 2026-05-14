# 12 — Accessibilita

## Obiettivo

**WCAG 2.1 livello AA** come target minimo. L'accessibilita non e una feature opzionale: e un requisito di qualita del prodotto e un obbligo normativo (Direttiva Europea sull'accessibilita 2025).

> **Nota importante**: il vecchio sito SkinTemple online usava `#1A9696` come colore CTA con contrasto 3.59:1 — FAIL WCAG AA per testo normale. Nel nuovo design system il brand primary e stato modernizzato in `#0F8A8A` che raggiunge ~4.7:1 su bianco (PASS AA testo normale, PASS AA testo grande, FAIL AAA). Per AAA usare `brand.primary-700` (#086060, 7.5:1).

---

## Contrasto colori

### Verifiche per combinazioni principali

| Primo piano | Sfondo | Rapporto | AA normale | AA large | AAA normale |
|-------------|--------|----------|-----------|----------|-------------|
| `ink` (#0F172A) | `surface` (#FFFFFF) | 17.9 : 1 | PASS | PASS | PASS |
| `ink-soft` (#334155) | `surface` (#FFFFFF) | 10.6 : 1 | PASS | PASS | PASS |
| `muted` (#64748B) | `surface` (#FFFFFF) | 4.7 : 1 | PASS | PASS | FAIL |
| `brand.primary` (#0F8A8A) | `surface` (#FFFFFF) | 4.7 : 1 | PASS | PASS | FAIL |
| `brand.primary-hover` (#0B7575) | `surface` (#FFFFFF) | 5.9 : 1 | PASS | PASS | FAIL |
| `brand.primary-700` (#086060) | `surface` (#FFFFFF) | 7.5 : 1 | PASS | PASS | PASS |
| `surface` (#FFFFFF) | `brand.primary` (#0F8A8A) | 4.7 : 1 | PASS | PASS | FAIL |
| `surface` (#FFFFFF) | `brand.primary-hover` (#0B7575) | 5.9 : 1 | PASS | PASS | FAIL |
| `brand.primary` (#0F8A8A) | `brand.primary-soft` (#E6F4F4) | 4.0 : 1 | FAIL | PASS | FAIL |
| `brand.primary-700` (#086060) | `brand.primary-soft` (#E6F4F4) | 6.4 : 1 | PASS | PASS | FAIL |
| `ink` (#0F172A) | `brand.primary-soft` (#E6F4F4) | 15.6 : 1 | PASS | PASS | PASS |
| `ink` (#0F172A) | `surface-soft` (#F8FAFC) | 17.0 : 1 | PASS | PASS | PASS |
| `surface` (#FFFFFF) | `neutral.900` (#0F172A) | 17.9 : 1 | PASS | PASS | PASS |
| `neutral.400` (#94A3B8) | `neutral.900` (#0F172A) | 6.5 : 1 | PASS | PASS | FAIL |
| `danger` (#DC2626) | `surface` (#FFFFFF) | 4.8 : 1 | PASS | PASS | FAIL |
| `success` (#059669) | `surface` (#FFFFFF) | 3.7 : 1 | FAIL | PASS | FAIL |
| `warning` (#D97706) | `surface` (#FFFFFF) | 3.6 : 1 | FAIL | PASS | FAIL |
| `info` (#0284C7) | `surface` (#FFFFFF) | 4.5 : 1 | PASS | PASS | FAIL |

### Azioni correttive obbligatorie

- **Testo brand su sfondo soft**: usare sempre `brand.primary-700` su `brand.primary-soft` (PASS AA), mai `brand.primary` (FAIL AA).
- **Prezzi scontati**: usare `brand.primary-700` (#086060) come colore prezzo scontato per garantire PASS AAA su tutti gli sfondi card.
- **Stati semantici `success`/`warning`** su bianco: rapporto < 4.5 → testo deve sempre essere accompagnato da icona; preferire scrivere il testo su sfondo `*-bg` semantico per migliorare il contrasto percepito.
- **`muted` su bianco** (4.7:1): PASS AA per testo normale, ma evitare per testo body lungo — usare per label, caption, meta date.
- **CTA primaria** (surface su brand.primary): 4.7:1 → PASS AA, per AAA passare a `brand.primary-hover` come bg.

---

## Focus visible

### Ring standard

```css
/* Stile focus globale applicato via Tailwind */
:focus-visible {
    outline: none;
    box-shadow: 0 0 0 2px #FFFFFF, 0 0 0 4px #0F8A8A;
}
```

Tradotto in classi Tailwind:
```html
<button class="focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-primary focus-visible:ring-offset-2">
```

- `ring-2`: spessore 2px
- `ring-brand-primary`: colore teal
- `ring-offset-2`: offset 2px (crea spazio bianco tra bordo elemento e ring per visibilita)
- Solo su `:focus-visible`, non su `:focus` — cosi il ring appare con tastiera, non con click del mouse

### Personalizzazioni per contesto

| Contesto | Ring | Note |
|----------|------|------|
| Su sfondo chiaro | `ring-brand-primary ring-offset-white` | Default |
| Su sfondo scuro (footer, hero dark) | `ring-brand-primary-300 ring-offset-neutral-900` | Offset scuro + ring tint chiaro per contrasto |
| Su sfondo soft teal | `ring-brand-primary-700 ring-offset-brand-primary-soft` | Ring scuro su sfondo soft |
| Input field | `ring-2 ring-brand-primary/20 border-brand-primary` | Ring semi-trasparente + bordo pieno |
| Link inline nel testo | `ring-brand-primary rounded-sm` | Raggio piccolo per adattarsi al testo |

---

## Skip-to-content

Primo elemento nel `<body>`, visibile solo su focus da tastiera:

```html
<a href="#main-content"
   class="sr-only focus:not-sr-only focus:fixed focus:top-4 focus:left-4 focus:z-[100] focus:rounded-xl focus:bg-brand-primary focus:px-4 focus:py-2 focus:text-sm focus:font-semibold focus:text-surface focus:shadow-soft-lg">
    Vai al contenuto principale
</a>
```

E il landmark `#main-content`:
```html
<main id="main-content" tabindex="-1">
    <!-- contenuto pagina -->
</main>
```

---

## Aria labels e ruoli

### Icone-only button
Ogni button con solo icona (senza testo visibile) deve avere `aria-label`:

```html
<button aria-label="Cerca" class="...">
    <x-heroicon-o-magnifying-glass class="h-5 w-5" />
</button>

<button aria-label="Aggiungi alla lista desideri" class="...">
    <x-heroicon-o-heart class="h-5 w-5" />
</button>

<button aria-label="Chiudi menu" class="...">
    <x-heroicon-o-x-mark class="h-5 w-5" />
</button>
```

### Navigazione
```html
<nav aria-label="Navigazione principale">...</nav>
<nav aria-label="Breadcrumb">...</nav>
<nav aria-label="Paginazione">...</nav>
<nav aria-label="Navigazione mobile" class="lg:hidden">...</nav>
```

### Voce navigazione attiva
Usare `aria-current="page"` invece di classi alternative:
```html
<a href="/" aria-current="page" class="text-brand-primary font-semibold">Home</a>
```

### Regioni landmark
```html
<header role="banner">...</header>
<main id="main-content" role="main">...</main>
<aside aria-label="Filtri prodotto">...</aside>
<footer role="contentinfo">...</footer>
```

---

## Form accessibili

### Struttura obbligatoria

Ogni campo form deve avere:
1. `<label>` visibile collegato con `for`/`id`
2. Testo di aiuto con `aria-describedby`
3. Messaggio di errore con `aria-describedby` e `aria-invalid`

```html
<div>
    <label for="email" class="block text-sm font-medium text-ink-soft">
        Email
    </label>
    <p id="email-help" class="mt-1 text-xs text-muted">
        Riceverai un codice OTP a questo indirizzo.
    </p>
    <input type="email" id="email" name="email"
           aria-describedby="email-help email-error"
           aria-invalid="false"
           class="mt-1.5 block w-full rounded-xl border-border bg-surface-sunken shadow-inner-soft
                  focus:border-brand-primary focus:ring-2 focus:ring-brand-primary/20">
    <p id="email-error" role="alert" class="mt-1 text-xs text-danger hidden">
        Inserisci un indirizzo email valido.
    </p>
</div>
```

### Validazione live (Livewire)
Quando Livewire aggiorna lo stato di validazione:
- Impostare `aria-invalid="true"` sul campo
- Rendere visibile il messaggio di errore
- L'errore deve essere in un elemento con `role="alert"` per annuncio immediato agli screen reader

---

## Modale e drawer

### Focus trap
Quando una modale o un drawer e aperto:
1. Il focus viene spostato al primo elemento focusable all'interno
2. Tab e Shift+Tab ciclano solo tra gli elementi interni (focus trap)
3. Esc chiude la modale/drawer
4. Alla chiusura, il focus torna all'elemento che aveva aperto la modale

Alpine.js gestisce il focus trap con il plugin `@alpinejs/focus`:

```html
<div x-show="open" x-trap.noscroll="open" role="dialog" aria-modal="true" aria-labelledby="modal-title">
    <h2 id="modal-title">Titolo modale</h2>
    <!-- contenuto -->
    <button @click="open = false">Chiudi</button>
</div>
```

- `x-trap`: attiva focus trap (richiede `@alpinejs/focus`)
- `x-trap.noscroll`: previene lo scroll del body dietro la modale
- `role="dialog"` e `aria-modal="true"`: semantica corretta
- `aria-labelledby`: collega il titolo della modale

### Backdrop
```html
<div x-show="open" @click="open = false" aria-hidden="true"
     class="fixed inset-0 z-30 bg-glass-dark">
</div>
```
Il backdrop ha `aria-hidden="true"` perche non e contenuto significativo.

---

## Riduzione del movimento

Rispettare `prefers-reduced-motion` per utenti sensibili alle animazioni:

```css
@media (prefers-reduced-motion: reduce) {
    *, *::before, *::after {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
        scroll-behavior: auto !important;
    }
}
```

In Tailwind, usare il modificatore `motion-safe:` per le animazioni:

```html
<div class="motion-safe:animate-fade-up">
    <!-- contenuto che appare con animazione solo se l'utente non ha richiesto riduzione -->
</div>
```

### Cosa si disabilita
- Animazioni di entrata/uscita (fade, slide, scale)
- Transizioni di trasformazione
- Auto-scroll smooth
- Underline animato sotto voce nav attiva
- Parallax

### Cosa resta attivo
- Transizioni di colore e opacita (brevi, < 200ms)
- Indicatori di caricamento (spinner, skeleton — necessari per feedback)

---

## Testing accessibilita

### Strumenti consigliati
1. **axe DevTools** (estensione Chrome) — audit automatizzato WCAG
2. **Lighthouse Accessibility** — punteggio target >= 90
3. **Navigazione tastiera manuale** — verificare ogni pagina con solo Tab, Shift+Tab, Enter, Esc, Frecce
4. **Screen reader** — test con VoiceOver (macOS/iOS) su pagine chiave

### Checklist per pagina

- [ ] Tutti i livelli heading sono sequenziali (h1 → h2 → h3, no salti)
- [ ] Ogni immagine ha `alt` significativo (o `alt=""` se decorativa)
- [ ] Ogni form field ha label visibile
- [ ] Ogni errore di validazione e annunciato (`role="alert"`)
- [ ] Navigazione possibile con sola tastiera
- [ ] Focus visible su tutti gli elementi interattivi
- [ ] Skip-to-content funzionante
- [ ] Contrasto testo >= 4.5:1 (normale) o >= 3:1 (grande)
- [ ] Modale: focus trap attivo, Esc chiude
- [ ] Carousel: controlli accessibili da tastiera
- [ ] Nessun contenuto significativo trasmesso solo tramite colore
- [ ] `aria-current="page"` sulle voci di navigazione attive
- [ ] Underline animato disabilitato sotto `prefers-reduced-motion`

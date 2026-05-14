# 12 — Accessibilita

## Obiettivo

**WCAG 2.1 livello AA** come target minimo. L'accessibilita non e una feature opzionale: e un requisito di qualita del prodotto e un obbligo normativo (Direttiva Europea sull'accessibilita 2025).

---

## Contrasto colori

### Verifiche per combinazioni principali

| Primo piano | Sfondo | Rapporto | Passa AA (testo normale) | Passa AA (testo grande) |
|-------------|--------|----------|--------------------------|------------------------|
| `neutral.900` (#171717) | `brand.surface` (#FFFFFF) | 17.4:1 | Si | Si |
| `neutral.700` (#404040) | `brand.surface` (#FFFFFF) | 9.7:1 | Si | Si |
| `neutral.500` (#737373) | `brand.surface` (#FFFFFF) | 4.6:1 | Si | Si |
| `neutral.400` (#A3A3A3) | `brand.surface` (#FFFFFF) | 2.7:1 | No | Si (solo testo grande) |
| `brand.accent` (#B08D57) | `brand.surface` (#FFFFFF) | 3.5:1 | No | Si |
| `brand.accent` (#B08D57) | `brand.primary` (#0A0A0A) | 5.0:1 | Si | Si |
| `brand.surface` (#FFFFFF) | `brand.primary` (#0A0A0A) | 19.3:1 | Si | Si |
| `brand.surface` (#FFFFFF) | `brand.accent` (#B08D57) | 3.5:1 | No | Si |
| `danger` (#EF4444) | `brand.surface` (#FFFFFF) | 3.9:1 | No | Si |
| `danger` (#EF4444) | `danger-bg` (#FEF2F2) | 3.8:1 | No | Si |
| `success` (#10B981) | `brand.surface` (#FFFFFF) | 3.3:1 | No | Si |
| `neutral.900` (#171717) | `neutral.50` (#FAFAFA) | 16.5:1 | Si | Si |
| `neutral.400` (#A3A3A3) | `neutral.900` (#171717) | 6.3:1 | Si | Si |

### Azioni correttive

- **`brand.accent` su bianco** (3.5:1): non raggiunge AA per testo normale. Usare `brand.accent-deep` (#8B6F3E, rapporto ~5.3:1) per testo su sfondo bianco. L'accent pieno (#B08D57) e ammesso solo per elementi decorativi (icone grandi, bordi, sfondi di button dove il testo e bianco su accent).
- **`neutral.400` su bianco** (2.7:1): usare solo per testo placeholder o elementi decorativi, mai per informazioni essenziali.
- **`danger` su bianco** (3.9:1): per testo di errore, aggiungere sempre un'icona accanto al messaggio per non affidarsi solo al colore.
- **`success` su bianco** (3.3:1): come danger, sempre accompagnare con icona. Per testo, preferire `success` su `success-bg` con icona.

---

## Focus visible

### Ring standard

```css
/* Stile focus globale — applicato via Tailwind plugin o CSS custom */
:focus-visible {
    outline: none;
    box-shadow: 0 0 0 2px #FFFFFF, 0 0 0 4px #B08D57;
}
```

Tradotto in classi Tailwind:
```html
<button class="focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-accent focus-visible:ring-offset-2">
```

- `ring-2`: spessore 2px
- `ring-brand-accent`: colore oro
- `ring-offset-2`: offset 2px (crea spazio bianco tra bordo elemento e ring per visibilita)
- Solo su `:focus-visible`, non su `:focus` — cosi il ring appare con tastiera, non con click del mouse

### Personalizzazioni per contesto

| Contesto | Ring | Note |
|----------|------|------|
| Su sfondo chiaro | `ring-brand-accent ring-offset-white` | Default |
| Su sfondo scuro (footer, hero) | `ring-brand-accent-soft ring-offset-neutral-900` | Offset scuro per contrasto |
| Input field | `ring-brand-accent/20 border-brand-accent` | Ring semi-trasparente + bordo pieno |
| Link inline nel testo | `ring-brand-accent rounded-sm` | Raggio piccolo per adattarsi al testo |

---

## Skip-to-content

Primo elemento nel `<body>`, visibile solo su focus da tastiera:

```html
<a href="#main-content"
   class="sr-only focus:not-sr-only focus:fixed focus:top-4 focus:left-4 focus:z-[100] focus:rounded-xl focus:bg-brand-accent focus:px-4 focus:py-2 focus:text-sm focus:font-semibold focus:text-brand-surface focus:shadow-soft-lg">
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
    <label for="email" class="block text-sm font-medium text-neutral-700">
        Email
    </label>
    <p id="email-help" class="mt-1 text-xs text-neutral-500">
        Riceverai un codice OTP a questo indirizzo.
    </p>
    <input type="email" id="email" name="email"
           aria-describedby="email-help email-error"
           aria-invalid="false"
           class="mt-1.5 block w-full rounded-xl border-neutral-300 shadow-inner-soft
                  focus:border-brand-accent focus:ring-2 focus:ring-brand-accent/20">
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

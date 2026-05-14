# 05 — Raggi e Ombre

## Border radius

| Token | Valore | Uso |
|-------|--------|-----|
| `rounded-none` | 0px | Immagini full-bleed, divider |
| `rounded-sm` | 4px | Badge piccoli, chip |
| `rounded-md` | 6px | Raro, elementi compatti |
| `rounded-lg` | 8px | Input di default, select |
| `rounded-xl` | 12px | Card secondarie, button secondari, dropdown |
| `rounded-2xl` | 16px | **Card prodotto (default)**, card principali, modale desktop |
| `rounded-3xl` | 24px | Drawer mobile (solo lato visibile), card hero |
| `rounded-full` | 9999px | **Button primari (pill)**, avatar, badge circolari, chip |

### Defaults per componente

| Componente | Raggio | Note |
|------------|--------|------|
| Card prodotto | `rounded-2xl` | 16px, coerente con estetica Apple |
| Button primario | `rounded-full` | Pill shape, premium e moderno |
| Button secondario | `rounded-xl` | 12px, leggermente meno arrotondato per gerarchia visiva |
| Button ghost/link | Nessun raggio visibile | Solo testo con padding hover |
| Input / Textarea | `rounded-xl` | 12px |
| Select | `rounded-xl` | 12px |
| Checkbox | `rounded-md` | 6px |
| Toggle/switch | `rounded-full` | Pill |
| Modal desktop | `rounded-2xl` | 16px |
| Drawer mobile (bottom) | `rounded-t-3xl` | 24px solo in alto |
| Drawer mobile (right) | `rounded-l-3xl` | 24px solo a sinistra |
| Mega menu | `rounded-2xl` | 16px |
| Dropdown | `rounded-xl` | 12px |
| Toast | `rounded-2xl` | 16px |
| Tooltip | `rounded-lg` | 8px |
| Avatar | `rounded-full` | Circolare |
| Badge | `rounded-full` | Pill piccolo |
| Immagine prodotto (dentro card) | `rounded-xl` | 12px, o `rounded-t-2xl` se attaccata al bordo card |

### Perche pill button e non rounded-xl
Il button pill (`rounded-full`) e una scelta premium coerente con l'estetica Apple. Crea un contrasto visivo con le card (`rounded-2xl`) che aiuta a distinguere elementi interattivi da contenitori. Il pill button e inoltre piu "touch-friendly" percettivamente — invita al tap su mobile.

---

## Ombre (shadows)

Ombre custom Apple-style: diffuse, multi-layer, con opacita molto basse. Nessuna ombra Tailwind default usata direttamente.

| Token | Valore CSS | Uso |
|-------|-----------|-----|
| `shadow-soft-sm` | `0 1px 2px rgba(10,10,10,0.04), 0 1px 3px rgba(10,10,10,0.04)` | Elevazione minima: card a riposo, input focus leggero |
| `shadow-soft-md` | `0 4px 8px rgba(10,10,10,0.04), 0 2px 4px rgba(10,10,10,0.05)` | Elevazione media: card hover, dropdown, popover |
| `shadow-soft-lg` | `0 12px 24px rgba(10,10,10,0.06), 0 4px 8px rgba(10,10,10,0.06)` | Elevazione alta: modale, drawer, mega menu |
| `shadow-soft-xl` | `0 24px 48px rgba(10,10,10,0.08), 0 12px 24px rgba(10,10,10,0.08)` | Elevazione massima: dialog centrato, elemento hero flottante |
| `shadow-glass` | `0 8px 32px rgba(10,10,10,0.08), inset 0 1px 0 rgba(255,255,255,0.4)` | Elementi glass: include inner glow bianco per effetto vetro |
| `shadow-inner-soft` | `inset 0 1px 2px rgba(10,10,10,0.04)` | Input field a riposo, well container |

### Transizioni ombre
Le ombre cambiano stato con transizione fluida:
```html
<div class="shadow-soft-sm hover:shadow-soft-md transition-shadow duration-300 ease-apple">
```
Durata: **300ms** con easing Apple `cubic-bezier(0.4, 0, 0.2, 1)`.

### Applicazione per componente

| Componente | Stato default | Stato hover | Stato active/focus |
|------------|--------------|-------------|-------------------|
| Card prodotto | `shadow-soft-sm` | `shadow-soft-md` | — |
| Dropdown/popover | `shadow-soft-lg` | — | — |
| Modal | `shadow-soft-xl` | — | — |
| Drawer | `shadow-soft-xl` | — | — |
| Mega menu | `shadow-glass` | — | — |
| Toast | `shadow-soft-lg` | — | — |
| Button primario | Nessuna ombra (colore pieno basta) | Leggero `shadow-soft-sm` opzionale | — |
| Input | `shadow-inner-soft` | — | `ring-2 ring-brand-accent` |
| Glass overlay | `shadow-glass` | — | — |

---

## Bordi

| Token | Valore | Uso |
|-------|--------|-----|
| `border` | 1px solid | Default per divider, bordi input, bordi card leggeri |
| `border-2` | 2px solid | Focus ring spesso, bordo selezionato (es. variante colore attiva) |
| `border-4` | 4px solid | Raro, usato per evidenziazione forte (es. bordo immagine selezionata in gallery) |

### Colori bordo

| Contesto | Classe | Risultato |
|----------|--------|-----------|
| Divider orizzontale | `border-neutral-200` | Linea sottile visibile ma discreta |
| Input default | `border-neutral-300` | Bordo percepibile per definire l'area |
| Input focus | `border-brand-accent ring-2 ring-brand-accent/20` | Bordo oro con ring semi-trasparente |
| Input error | `border-danger` | Bordo rosso per errore validazione |
| Card prodotto | Nessun bordo — solo ombra | L'ombra soft definisce i limiti |
| Separatore sezione | `border-t border-neutral-100` | Quasi invisibile, crea separazione sottile |
| Bordo selezionato (variante) | `border-2 border-brand-accent` | Cerchio colore selezionato |

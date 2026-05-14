# 02 — Token Colore

## Brand

| Token | Hex | HSL | Uso | Dark mode |
|-------|-----|-----|-----|-----------|
| `brand.primary` | `#0A0A0A` | `hsl(0, 0%, 4%)` | Testo principale, sfondi scuri, header | `#FAFAFA` |
| `brand.surface` | `#FFFFFF` | `hsl(0, 0%, 100%)` | Sfondo pagina, card, container | `#0A0A0A` |
| `brand.accent` | `#B08D57` | `hsl(38, 39%, 51%)` | CTA primarie, link attivi, accent decorativo | `#D4B58A` |
| `brand.accent-soft` | `#D4B58A` | `hsl(33, 45%, 68%)` | Hover accent, badge, sfondi accent leggeri | `#B08D57` |
| `brand.accent-deep` | `#8B6F3E` | `hsl(38, 38%, 39%)` | Active state accent, testo su sfondo accent | `#D4B58A` |

## Neutrali (scala Tailwind-style)

| Token | Hex | HSL | Uso |
|-------|-----|-----|-----|
| `neutral.50` | `#FAFAFA` | `hsl(0, 0%, 98%)` | Sfondo sezioni alternate, hover leggero |
| `neutral.100` | `#F5F5F5` | `hsl(0, 0%, 96%)` | Sfondo input, sfondo card secondarie |
| `neutral.200` | `#E5E5E5` | `hsl(0, 0%, 90%)` | Bordi leggeri, divider |
| `neutral.300` | `#D4D4D4` | `hsl(0, 0%, 83%)` | Bordi input default, placeholder icon |
| `neutral.400` | `#A3A3A3` | `hsl(0, 0%, 64%)` | Testo placeholder, icone disabilitate |
| `neutral.500` | `#737373` | `hsl(0, 0%, 45%)` | Testo secondario, label brand su card |
| `neutral.600` | `#525252` | `hsl(0, 0%, 32%)` | Testo body secondario |
| `neutral.700` | `#404040` | `hsl(0, 0%, 25%)` | Testo body principale |
| `neutral.800` | `#262626` | `hsl(0, 0%, 15%)` | Titoli secondari, footer sfondo |
| `neutral.900` | `#171717` | `hsl(0, 0%, 9%)` | Titoli principali |
| `neutral.950` | `#0A0A0A` | `hsl(0, 0%, 4%)` | Nero piu profondo, alias `brand.primary` |

## Stati semantici

| Token | Hex | HSL | Uso |
|-------|-----|-----|-----|
| `success` | `#10B981` | `hsl(160, 84%, 39%)` | Conferma ordine, validazione ok, badge disponibile |
| `success-bg` | `#ECFDF5` | `hsl(152, 81%, 96%)` | Sfondo alert successo |
| `warning` | `#F59E0B` | `hsl(38, 92%, 50%)` | Scorte limitate, attenzione |
| `warning-bg` | `#FFFBEB` | `hsl(48, 100%, 96%)` | Sfondo alert warning |
| `danger` | `#EF4444` | `hsl(0, 84%, 60%)` | Errore validazione, rimuovi, elimina |
| `danger-bg` | `#FEF2F2` | `hsl(0, 86%, 97%)` | Sfondo alert errore |
| `info` | `#3B82F6` | `hsl(217, 91%, 60%)` | Link informativi, badge info |
| `info-bg` | `#EFF6FF` | `hsl(214, 100%, 97%)` | Sfondo alert info |

## Glass overlay

| Token | Valore | Proprieta aggiuntive | Uso |
|-------|--------|----------------------|-----|
| `glass.light` | `rgba(255, 255, 255, 0.72)` | `backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px);` | Mega menu, drawer mobile, overlay navigazione su sfondo chiaro |
| `glass.dark` | `rgba(10, 10, 10, 0.55)` | `backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px);` | Modal backdrop, overlay su immagini hero |

## Gradienti (uso limitato)

| Token | Valore | Uso |
|-------|--------|-----|
| `gradient.accent` | `linear-gradient(135deg, #B08D57 0%, #D4B58A 100%)` | CTA premium, badge esclusivi (raro) |
| `gradient.surface` | `linear-gradient(180deg, #FFFFFF 0%, #FAFAFA 100%)` | Sfondo sezioni per leggera profondita |
| `gradient.dark` | `linear-gradient(180deg, #0A0A0A 0%, #171717 100%)` | Footer, sezioni scure |

---

## Quando usare cosa

### Testo
- **Titoli principali (H1, H2)**: `neutral.900` su sfondo chiaro, `brand.surface` su sfondo scuro
- **Body text**: `neutral.700` — abbastanza scuro per leggibilita, non nero pieno per morbidezza
- **Testo secondario** (date, meta, caption): `neutral.500`
- **Label brand su card prodotto**: `neutral.500` con `uppercase` e `tracking-widest`
- **Link**: `brand.accent` con underline su hover, `brand.accent-deep` su active

### Sfondi
- **Pagina**: `brand.surface` (`#FFFFFF`)
- **Sezioni alternate**: `neutral.50` per creare ritmo senza bordi
- **Card prodotto**: `brand.surface` con `shadow-soft-sm`, hover `shadow-soft-md`
- **Header fisso**: `glass.light` con `border-b border-neutral.200/50`
- **Footer**: `neutral.900` o `neutral.950` con testo `neutral.400`

### Accent dorato
- **CTA primaria**: sfondo `brand.accent`, testo `brand.surface`, hover `brand.accent-deep`
- **Badge "Novita"**: sfondo `brand.accent`, testo `brand.surface`
- **Prezzo in promozione**: `brand.accent` per il prezzo scontato, `neutral.400` barrato per l'originale
- **Focus ring**: `ring-brand-accent` per evidenziare accessibilita
- **Non usare oro come**: colore di sfondo di intere sezioni, colore testo body, bordo di card standard

### Colori semantici
- Usare **solo** per comunicare stati reali: errore di validazione (danger), successo operazione (success), avviso scorte (warning), informazione neutra (info)
- Mai come decorazione o per creare varieta cromatica nella pagina

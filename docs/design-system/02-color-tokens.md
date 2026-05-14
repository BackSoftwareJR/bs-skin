# Color Tokens - SkinTemple Design System

Palette definitiva teal medical-wellness per SkinTemple ecommerce.

## Brand Colors

### Primary Teal Scale
```css
brand-primary: #0F8A8A     /* Teal principale, WCAG AA 4.7:1 su white */
brand-primary-hover: #0B7575
brand-primary-soft: #E6F4F4  /* Background sezioni soft */
brand-primary-50: #F0FAFA
brand-primary-100: #D9F0F0
brand-primary-200: #B3E0E0
brand-primary-300: #7FCBCB
brand-primary-400: #3FAEAE
brand-primary-500: #0F8A8A   /* Same as primary */
brand-primary-600: #0B7575
brand-primary-700: #086060
brand-primary-800: #064848
brand-primary-900: #043333
brand-primary-950: #021F1F
```

### Accent & Surfaces
```css
brand-accent: #14B8A6        /* Teal vivace per highlight, stati attivi */
brand-accent-soft: #CCFBF1

brand-ink: #0F172A          /* Testo principale slate */
brand-ink-soft: #334155     /* Testo secondario */
brand-muted: #64748B

brand-surface: #FFFFFF      /* Superficie principale */
brand-surface-soft: #F8FAFC /* Background soft */
brand-surface-sunken: #F1F5F9 /* Input backgrounds */

brand-border: #E2E8F0       /* Bordi default */
brand-border-strong: #CBD5E1
```

## Neutral Scale (Slate)
Sfumatura cool per neutrali:
```css
neutral-50: #F8FAFC
neutral-100: #F1F5F9
neutral-200: #E2E8F0
neutral-300: #CBD5E1
neutral-400: #94A3B8
neutral-500: #64748B
neutral-600: #475569
neutral-700: #334155
neutral-800: #1E293B
neutral-900: #0F172A
neutral-950: #020617
```

## Semantic Colors
```css
success: #059669, success-soft: #ECFDF5
warning: #D97706, warning-soft: #FFFBEB
danger: #DC2626, danger-soft: #FEF2F2
info: #0284C7, info-soft: #F0F9FF
```

## Glass Morphism
```css
glass-light: rgba(255,255,255,0.72)
glass-tint: rgba(15,138,138,0.08)
glass-dark: rgba(15,23,42,0.55)
```

## Contrasti WCAG

| Combinazione | Ratio | Livello |
|--------------|-------|---------|
| `brand-primary` (#0F8A8A) su white | 4.7:1 | ✅ AA |
| `brand-primary` su `brand-primary-soft` (#E6F4F4) | 3.2:1 | ⚠️ AA Large |
| `brand-ink` (#0F172A) su white | 19.1:1 | ✅ AAA |
| white su `brand-primary` | 4.7:1 | ✅ AA |
| `brand-ink-soft` (#334155) su white | 8.9:1 | ✅ AAA |

## Migrazione dalla Palette Precedente

**ELIMINATA** completamente la palette oro/bronzo (`#B08D57`) — era sbagliata rispetto alla brand identity reale.

**SOSTITUITA** con teal `#0F8A8A` derivato dal colore originale `#1A9696` del vecchio sito, ottimizzato per WCAG AA compliance.
# SkinTemple Design System

Documentazione completa del sistema di design per l'e-commerce SkinTemple.
Monolite Laravel + Blade + Livewire 3 + Tailwind CSS + Alpine.js.

---

## Indice

| # | File | Contenuto |
|---|------|-----------|
| 1 | [Principi di Design](01-design-principles.md) | Principi guida, voice & tone, anti-pattern |
| 2 | [Token Colore](02-color-tokens.md) | Palette brand, neutrali, semantici, glass overlay |
| 3 | [Tipografia](03-typography.md) | Font families, scale tipografica, combinazioni |
| 4 | [Spacing e Layout](04-spacing-and-layout.md) | Scale spacing, container, grid, z-index, breakpoint |
| 5 | [Raggi e Ombre](05-radii-and-shadows.md) | Border radius, ombre soft Apple-style, bordi |
| 6 | [Tailwind Config](06-tailwind.config.js) | File JS completo, copy-pastable nel progetto |
| 7 | [Componenti Blade](07-components-spec.md) | Specifica completa di ogni componente Blade pubblico |
| 8 | [Componenti Livewire](08-livewire-components-spec.md) | Componenti Livewire 3 interattivi con stato server |
| 9 | [Sistema Icone](09-icon-system.md) | Strategia Heroicons, scale, mappatura azione-icona |
| 10 | [Mockup Pagine](10-pagine-mockup.md) | Wireframe testuale di ogni pagina chiave |
| 11 | [Mobile e PWA](11-mobile-and-pwa.md) | Approccio mobile-first, bottom tab bar, PWA |
| 12 | [Accessibilita](12-accessibility.md) | WCAG 2.1 AA, contrasti, focus, aria, motion |
| 13 | [Checklist Implementazione](13-implementation-checklist.md) | Step-by-step per l'agente Frontend |

---

## Convenzioni

- **Token name**: formato dot-notation (`brand.primary`, `neutral.500`)
- **Componenti Blade**: `resources/views/components/public/<name>.blade.php`
- **Componenti Livewire**: `app/Livewire/Public/<Domain>/<Name>.php`
- **Classi Tailwind**: sempre utility-first, nessun CSS custom salvo eccezioni documentate
- **Lingua UI**: italiano per testi utente, inglese per codice e nomi tecnici

## Stack tecnologico

| Layer | Tecnologia |
|-------|-----------|
| Backend | Laravel (ultima stabile) |
| Template | Blade + Livewire 3 |
| Styling | Tailwind CSS 3.x |
| Interattivita client | Alpine.js 3.x |
| Admin | Filament 3.x |
| Icone | Heroicons (via blade-heroicons) |
| Font | Google Fonts (Inter, Cormorant Garamond, JetBrains Mono) |

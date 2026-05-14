# 09 — Sistema Icone

## Strategia

SkinTemple utilizza **Heroicons** come set icone primario, tramite il pacchetto `blade-ui-kit/blade-heroicons` gia incluso come dipendenza di Filament 3.

### Sintassi Blade

```html
{{-- Outline (24x24, stroke 1.5px) — default per UI --}}
<x-heroicon-o-shopping-bag class="h-5 w-5" />

{{-- Solid (24x24, filled) — per stati attivi, bottom tab bar --}}
<x-heroicon-s-shopping-bag class="h-5 w-5" />

{{-- Mini (20x20, filled, ottimizzate per piccole dimensioni) --}}
<x-heroicon-m-shopping-bag class="h-4 w-4" />
```

### Regola d'uso outline vs solid
- **Outline**: stato default, navigazione, toolbar, azioni
- **Solid**: stato attivo/selezionato (es. tab attiva nel bottom bar, cuore wishlist pieno)
- **Mini**: contesti piccoli (badge, chip, inline nel testo)

## Scala dimensioni

| Classe | Pixel | Uso |
|--------|-------|-----|
| `h-3 w-3` | 12px | Icone tiny: freccia in breadcrumb, indicatore inline |
| `h-4 w-4` | 16px | Icone sm: dentro button sm, chip, badge, input prefix |
| `h-5 w-5` | 20px | **Default**: navigazione, toolbar, azioni card |
| `h-6 w-6` | 24px | Icone lg: header principale, bottom tab bar |
| `h-8 w-8` | 32px | Icone xl: feature bar, empty state |
| `h-12 w-12` | 48px | Icone hero: empty state grande, feature section |

## Colori

Le icone ereditano `currentColor`. Impostare il colore sul container o direttamente con classi `text-*`:

```html
{{-- Eredita dal parent --}}
<span class="text-neutral-700">
    <x-heroicon-o-heart class="h-5 w-5" />
</span>

{{-- Diretto --}}
<x-heroicon-o-heart class="h-5 w-5 text-neutral-400 hover:text-danger transition-colors" />
```

## Mappatura azione → icona

| Azione / Contesto | Icona Heroicons | Variante | Note |
|---|---|---|---|
| Carrello | `shopping-bag` | outline / solid | Solid quando contiene articoli |
| Account / Utente | `user` | outline / solid | |
| Ricerca | `magnifying-glass` | outline | |
| Menu hamburger | `bars-3` | outline | Solo mobile |
| Chiudi | `x-mark` | outline | Modal, drawer, overlay |
| Wishlist (vuoto) | `heart` | outline | |
| Wishlist (pieno) | `heart` | solid | Con `text-danger` |
| Stella recensione (vuota) | `star` | outline | |
| Stella recensione (piena) | `star` | solid | Con `text-warning` |
| Conferma / Check | `check` | outline | Checkbox custom, step completato |
| Successo | `check-circle` | solid | Alert, toast |
| Informazione | `information-circle` | outline | Tooltip trigger, alert info |
| Attenzione | `exclamation-triangle` | outline | Alert warning |
| Errore | `x-circle` | solid | Alert errore, validazione |
| Freccia giu (dropdown) | `chevron-down` | outline / mini | Mega menu, select, accordion |
| Freccia destra | `chevron-right` | mini | Breadcrumb, link "vedi tutto" |
| Freccia sinistra | `chevron-left` | mini | Navigazione indietro, pagination |
| Frecce carousel | `arrow-left` / `arrow-right` | outline | Carousel prodotti |
| Filtro | `funnel` | outline | Button "Filtra" mobile |
| Ordina | `arrows-up-down` | outline | Select ordinamento |
| Griglia view | `squares-2x2` | outline | Toggle vista griglia |
| Lista view | `bars-3-bottom-left` | outline | Toggle vista lista |
| Condividi | `share` | outline | Pagina prodotto |
| Home | `home` | outline / solid | Bottom tab bar |
| Negozio | `building-storefront` | outline / solid | Bottom tab bar |
| Spedizione | `truck` | outline | Feature bar, checkout |
| Made in Italy | `flag` | outline | Feature bar (con personalizzazione) |
| Supporto | `chat-bubble-left-right` | outline | Feature bar, link supporto |
| Email | `envelope` | outline | Newsletter, contatti |
| Telefono | `phone` | outline | Contatti |
| Posizione | `map-pin` | outline | Indirizzo, contatti |
| Modifica | `pencil-square` | outline | Account, indirizzi |
| Elimina | `trash` | outline | Carrello, account |
| Aggiungi | `plus` | outline / mini | Quantita, filtri |
| Rimuovi/Sottrai | `minus` | outline / mini | Quantita |
| Occhio (mostra) | `eye` | outline | Password, anteprima |
| Occhio barrato (nascondi) | `eye-slash` | outline | Password |
| Download | `arrow-down-tray` | outline | PDF manuale, fattura |
| Documento | `document-text` | outline | Fattura, manuale |
| Calendario | `calendar` | outline | Date ordine |
| Orologio | `clock` | outline | Tempo di consegna |
| Lucchetto | `lock-closed` | outline | Pagamento sicuro |
| Carta credito | `credit-card` | outline | Metodo pagamento |
| Tag prezzo | `tag` | outline | Coupon, promozione |
| Fotocamera | `camera` | outline | Upload immagine |
| Impostazioni | `cog-6-tooth` | outline | Account settings |
| Logout | `arrow-right-on-rectangle` | outline | Disconnetti |

## Icone custom (non Heroicons)

Per icone non presenti in Heroicons (loghi brand, icone pagamento, bandiera Italia), usare SVG inline o componente Blade dedicato:

```html
{{-- resources/views/components/public/icon-italy-flag.blade.php --}}
<svg {{ $attributes->merge(['class' => 'h-5 w-5']) }} viewBox="0 0 20 20" fill="none">
    <rect x="0" y="2" width="6.67" height="16" rx="1" fill="#009246"/>
    <rect x="6.67" y="2" width="6.67" height="16" fill="#FFFFFF"/>
    <rect x="13.33" y="2" width="6.67" height="16" rx="1" fill="#CE2B37"/>
</svg>
```

Icone pagamento (Visa, Mastercard, PayPal, ecc.) caricate come SVG da `/public/images/payment/`.

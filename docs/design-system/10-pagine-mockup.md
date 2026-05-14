# 10 — Mockup Pagine

Wireframe testuale di ogni pagina chiave. Per ciascuna: struttura gerarchica dei componenti, comportamento responsive, interazioni Livewire coinvolte.

---

## 1. Homepage

### Desktop (lg+)

```
┌─────────────────────────────────────────────────────────────────────────┐
│ [announcement-bar] Spedizione gratuita per ordini superiori a €59  [X] │
├─────────────────────────────────────────────────────────────────────────┤
│ [public-header] Logo | Home Prodotti▾ Tecnologie Chi Siamo Blog | 🔍👤♡🛒│
├─────────────────────────────────────────────────────────────────────────┤
│                                                                         │
│  [block-hero layout="center"]                                           │
│  ┌─────────────────────────────────────────────────────────────────┐   │
│  │        TECNOLOGIE MULTIFUNZIONE AL SERVIZIO                     │   │
│  │              DEL CENTRO ESTETICO                                │   │
│  │  (Cormorant 600, text-6xl, tracking-tighter)                    │   │
│  │                                                                  │   │
│  │  Skintemple e la soluzione Made In Italy che ti affianca         │   │
│  │  (Inter 400, text-lg, neutral.600)                              │   │
│  │                                                                  │   │
│  │              [  Scopri  ] (button primary lg)                    │   │
│  │                                                                  │   │
│  │  (Immagine hero full-width dietro con overlay gradient)          │   │
│  └─────────────────────────────────────────────────────────────────┘   │
│                                                                         │
├─────────────────────────────────────────────────────────────────────────┤
│                                                                         │
│  [feature-bar] 4 colonne                                                │
│  🚚 Spedizione gratuita | 🇮🇹 Made in Italy | 🔒 Pagamento sicuro | 💬 Assistenza│
│                                                                         │
├─────────────────────────────────────────────────────────────────────────┤
│                                                                         │
│  [section bg="neutral"] "Tratta tutti gli inestetismi"                  │
│  [block-hero layout="split"]                                            │
│  ┌──────────────────────────┬──────────────────────────┐               │
│  │                          │  Con la tecnologia        │               │
│  │    [Immagine device]     │  multifunzione puoi       │               │
│  │                          │  lavorare viso e corpo... │               │
│  │                          │  [Scopri] button          │               │
│  └──────────────────────────┴──────────────────────────┘               │
│                                                                         │
├─────────────────────────────────────────────────────────────────────────┤
│                                                                         │
│  [section] "La cura della pelle, ridefinita."                           │
│  [block-hero layout="split" reversed]                                   │
│  ┌──────────────────────────┬──────────────────────────┐               │
│  │  "La qualita di ogni     │                          │               │
│  │  prodotto e il riflesso  │   [Immagine skincare]    │               │
│  │  della cura..."          │                          │               │
│  │  [Esplora] button        │                          │               │
│  └──────────────────────────┴──────────────────────────┘               │
│                                                                         │
├─────────────────────────────────────────────────────────────────────────┤
│                                                                         │
│  [section] "Un solo dispositivo, infiniti risultati"                    │
│  [block-features] 3 colonne                                             │
│  ┌──────────────┬──────────────┬──────────────┐                        │
│  │ Versatilita  │ Personalizza │ Noleggio o   │                        │
│  │ operativa    │ il display   │ vendita      │                        │
│  └──────────────┴──────────────┴──────────────┘                        │
│                                                                         │
├─────────────────────────────────────────────────────────────────────────┤
│                                                                         │
│  [section bg="neutral"] "Nuovi Arrivi"                                  │
│  (Cormorant 500, text-4xl, centrato)                                    │
│  [related-products come carousel orizzontale]                           │
│  ┌────┐ ┌────┐ ┌────┐ ┌────┐ ┌────┐                                   │
│  │card│ │card│ │card│ │card│ │card│  ← →                               │
│  └────┘ └────┘ └────┘ └────┘ └────┘                                   │
│                                                                         │
├─────────────────────────────────────────────────────────────────────────┤
│                                                                         │
│  [section] [block-cta]                                                  │
│  "Scopri come ottimizzare il tuo centro" + [Contattaci] button          │
│                                                                         │
├─────────────────────────────────────────────────────────────────────────┤
│                                                                         │
│  [section bg="neutral"] [block-newsletter]                              │
│  "Iscriviti alla newsletter" + [Newsletter\SubscribeForm]               │
│                                                                         │
├─────────────────────────────────────────────────────────────────────────┤
│                                                                         │
│  [section] [block-brand-grid]                                           │
│  Loghi brand partner (se presenti)                                      │
│                                                                         │
├─────────────────────────────────────────────────────────────────────────┤
│ [public-footer]                                                         │
│  Logo | Esplora | Prodotti | Legale | Contatti                          │
│  © 2026 SkinTemple — P.IVA 11863510019                                 │
└─────────────────────────────────────────────────────────────────────────┘
```

### Mobile (< sm)
- Hero: `text-4xl`, immagine sotto il testo, button full-width
- Feature bar: stack verticale (1 colonna)
- Sezioni split: stack verticale (immagine sopra, testo sotto)
- Carousel prodotti: scroll orizzontale con snap, card piu piccole
- Footer: sezioni in accordion collassabile
- Bottom tab bar fissa in basso

**Livewire coinvolti**: `Newsletter\SubscribeForm`

---

## 2. Shop Catalogo

### Desktop (lg+)

Replica esattamente il pattern del vecchio sito SkinTemple: sidebar verticale con categorie reali a sinistra, search bar prominente in cima, griglia 3 colonne di card prodotto bianche con "Scopri di piu >" in teal.

```
┌──────────────────────────────────────────────────────────────────────────┐
│ [public-header con voce "Prodotti" attiva e underline teal]              │
├──────────────────────────────────────────────────────────────────────────┤
│                  │                                                       │
│  [filter-        │ ┌──────────────────────────────────────────────────┐ │
│   sidebar]       │ │ 🔍 Cerca prodotto, categoria, ingrediente...     │ │
│                  │ └──────────────────────────────────────────────────┘ │
│  Tutti i         │                                                       │
│  prodotti    18  │  Tutti i prodotti (18)                               │
│  ────────        │  ──────────────────────────                          │
│  underline teal  │                                                       │
│                  │  ┌────────────┐ ┌────────────┐ ┌────────────┐       │
│  Corpo        4  │  │            │ │            │ │            │       │
│  Pelle        2  │  │ [device]   │ │ [device]   │ │ [device]   │       │
│  Viso e corpo 3  │  │            │ │            │ │            │       │
│  Monouso      8  │  ├────────────┤ ├────────────┤ ├────────────┤       │
│  Tecnologie   8  │  │ PRESS FLOW │ │ LASER      │ │ IVS INFRA  │       │
│  Epilazione   2  │  │ press flow │ │ DIODO      │ │ VIBE SHAPE │       │
│                  │  │            │ │ D-WAVE 2k  │ │            │       │
│  ─────────────   │  │ Scopri >   │ │ Scopri >   │ │ Scopri >   │       │
│  PREZZO     +    │  └────────────┘ └────────────┘ └────────────┘       │
│  [--|------]     │                                                       │
│                  │  ┌────────────┐ ┌────────────┐ ┌────────────┐       │
│  MATERIALE   +   │  │ Lenzuolino │ │ Pantatuta  │ │ Fascia     │       │
│  (solo se cat=   │  │ TNT 100x200│ │ in cartene │ │ capelli TNT│       │
│   Monouso)       │  │ LENZ100... │ │ TUTACARTE..│ │ FASCIACAP..│       │
│                  │  │ Scopri >   │ │ Scopri >   │ │ Scopri >   │       │
│                  │  └────────────┘ └────────────┘ └────────────┘       │
│                  │                                                       │
│                  │  [pagination] < 1 [2] 3 >                            │
│                  │                                                       │
├──────────────────┴──────────────────────────────────────────────────────┤
│ [public-footer]                                                         │
└─────────────────────────────────────────────────────────────────────────┘
```

### Categorie reali (dal vecchio sito)
- Corpo
- Pelle (skincare cosmetici)
- Viso e corpo
- Monouso (lenzuolini TNT, pantatute cartene, fasce capelli, infradito, veline, dischetti cotone)
- Tecnologie (PRESS FLOW pressoterapia, LASER DIODO, IVS INFRA VIBE SHAPE endermologie, dispositivi multifunzione)
- Epilazione

Lo schema DB resta flessibile (le categorie sono CRUD da admin), questi nomi sono solo riferimento per i mockup.

### Pattern UI sidebar filtri
- Voce attiva: testo `brand.primary`, font-weight 500, underline teal sotto la riga
- Voci inattive: testo `ink-soft`, font-weight 400, nessun decoro
- Counter a destra in `muted` text-xs
- Hover: testo `brand.primary` senza underline
- Su mobile: trasforma in bottom sheet che si apre da button "Filtra" in alto

### Mobile
- Sidebar filtri nascosta, button "Filtra (3)" in alto apre bottom sheet con filtri
- Search bar full-width in alto
- Griglia 2 colonne
- Card stessa struttura, font ridotti
- Pill filtri attivi scrollabili orizzontalmente sopra la griglia

**Livewire coinvolti**: `Catalog\ProductFilters`, `Catalog\ProductGrid`, `Catalog\ProductSearch` (la search bar in pagina shop e una variante inline del componente search)

---

## 3. Pagina Prodotto (Cosmetico)

### Desktop (lg+)

```
┌─────────────────────────────────────────────────────────────────────────┐
│ [public-header]                                                         │
├─────────────────────────────────────────────────────────────────────────┤
│ [breadcrumb] Home > Viso > Creme > Crema Idratante                     │
├─────────────────────────────────────────────────────────────────────────┤
│                                                                         │
│  ┌─────────────────────────────────┬───────────────────────────────┐   │
│  │ [product-gallery]               │ [product-info]                │   │
│  │                                  │                               │   │
│  │  ┌──┐  ┌─────────────────┐     │ SKINTEMPLE (brand label 2xs)  │   │
│  │  │th│  │                 │     │ Crema Viso Idratante Acido    │   │
│  │  │um│  │                 │     │ Ialuronico (H1, text-3xl)     │   │
│  │  │  │  │   Main Image    │     │                               │   │
│  │  ├──┤  │                 │     │ ★★★★☆ 4.2 (23 recensioni)    │   │
│  │  │th│  │                 │     │                               │   │
│  │  │um│  │                 │     │ €39,00  €49,00 (barrato)     │   │
│  │  │  │  └─────────────────┘     │                               │   │
│  │  ├──┤                           │ Descrizione breve (2-3 righe) │   │
│  │  │th│                           │                               │   │
│  │  └──┘                           │ Formato: [50ml] [100ml]       │   │
│  │                                  │ [variant-selector]            │   │
│  │                                  │                               │   │
│  │                                  │ [-] 1 [+] [quantity-stepper] │   │
│  │                                  │                               │   │
│  │                                  │ [████ Aggiungi al carrello ████]│  │
│  │                                  │ [add-to-cart-button]          │   │
│  │                                  │                               │   │
│  │                                  │ ♡ Wishlist | ↗ Condividi     │   │
│  │                                  │                               │   │
│  │                                  │ 🚚 Spedizione gratuita >€59  │   │
│  │                                  │ 🇮🇹 Made in Italy             │   │
│  │                                  │ 🔒 Pagamento sicuro          │   │
│  └─────────────────────────────────┴───────────────────────────────┘   │
│                                                                         │
├─────────────────────────────────────────────────────────────────────────┤
│ [product-tabs]                                                          │
│ [Descrizione] [Ingredienti] [Modo d'uso] [Recensioni (23)]             │
│ ─────────────                                                           │
│ Testo descrizione completa con prose typography...                       │
│                                                                         │
├─────────────────────────────────────────────────────────────────────────┤
│ [section] "Prodotti correlati" [related-products]                       │
│ ┌────┐ ┌────┐ ┌────┐ ┌────┐                                           │
│ │card│ │card│ │card│ │card│                                            │
│ └────┘ └────┘ └────┘ └────┘                                           │
├─────────────────────────────────────────────────────────────────────────┤
│ [section] "Visti di recente" [recently-viewed]                          │
├─────────────────────────────────────────────────────────────────────────┤
│ [public-footer]                                                         │
└─────────────────────────────────────────────────────────────────────────┘
```

### Mobile
- Gallery: carousel swipe full-width con pallini
- Info: sotto la gallery, stack verticale
- Tabs: accordion oppure tab scrollabili orizzontalmente
- CTA "Aggiungi al carrello" sticky in fondo allo schermo
- Correlati: carousel orizzontale

**Livewire coinvolti**: `Cart\AddToCartButton`, `Cart\WishlistToggle`, `Catalog\QuickView` (per correlati)

---

## 4. Pagina Prodotto DEVICE / Categoria Tecnologie

Esempi reali dal vecchio sito: **PRESS FLOW** (pressoterapia), **LASER DIODO D-WAVE 2000 w** (laser diodo doppia onda), **IVS INFRA VIBE SHAPE** (FIR PAD + massaggio vibrazionale + endomassaggio vacuum), dispositivi multifunzione.

### Dettaglio prodotto Device

```
┌──────────────────────────────────────────────────────────────────────────┐
│ [breadcrumb] Home > Prodotti > Tecnologie > Press Flow                   │
├──────────────────────────────────────────────────────────────────────────┤
│                                                                          │
│  ┌────────────────────────────────┬────────────────────────────────┐    │
│  │ [product-gallery]              │ [product-info variante device] │    │
│  │                                 │                                │    │
│  │ ┌────────────────────────────┐ │ [made-in-italy-badge]          │    │
│  │ │                            │ │ SKINTEMPLE (brand label 2xs)   │    │
│  │ │   Main image device         │ │                                │    │
│  │ │   (su sfondo grigio chiaro) │ │ PRESS FLOW                     │    │
│  │ │                            │ │ (H1 uppercase tracking-wide    │    │
│  │ └────────────────────────────┘ │  text-3xl text-ink)            │    │
│  │ ▶ Video demo (click play)      │                                 │    │
│  │ ┌──┐ ┌──┐ ┌──┐ ┌──┐            │ SKU: PRESSFLOW001               │    │
│  │ │ 1│ │ 2│ │ 3│ │ ▶│ thumbnails │ font-mono text-xs text-muted    │    │
│  │ └──┘ └──┘ └──┘ └──┘            │                                 │    │
│  │                                 │ Descrizione breve (2-3 righe)  │    │
│  │                                 │ Pressoterapia professionale...│    │
│  │                                 │                                 │    │
│  │                                 │ MODALITA DI FORNITURA          │    │
│  │                                 │ ┌─────────┐ ┌──────────┐       │    │
│  │                                 │ │Acquisto │ │ Noleggio │       │    │
│  │                                 │ │ €4.200  │ │ €180/mese│       │    │
│  │                                 │ └─────────┘ └──────────┘       │    │
│  │                                 │ (variant-selector toggle teal) │    │
│  │                                 │                                 │    │
│  │                                 │ [▼ Calcolatore noleggio]        │    │
│  │                                 │ Durata: [12 mesi ▾]             │    │
│  │                                 │ Totale: €2.160                  │    │
│  │                                 │                                 │    │
│  │                                 │ [████ Richiedi demo ████]      │    │
│  │                                 │ (button primary uppercase)     │    │
│  │                                 │ [─── Acquista subito ───]      │    │
│  │                                 │ (button secondary outline)     │    │
│  │                                 │                                 │    │
│  │                                 │ ✓ Made in Italy                │    │
│  │                                 │ ✓ Garanzia 24 mesi              │    │
│  │                                 │ ✓ Assistenza tecnica inclusa   │    │
│  │                                 │ ✓ Formazione operatore         │    │
│  └────────────────────────────────┴────────────────────────────────┘    │
│                                                                          │
├──────────────────────────────────────────────────────────────────────────┤
│ [product-tabs]                                                           │
│ [Descrizione] [Specifiche tecniche] [Certificazioni] [Modalita] [Manuale PDF] [Video] │
│  ─────────────                                                           │
│                                                                          │
│ TAB: Specifiche tecniche                                                 │
│ ┌─────────────────────────────────┬──────────────────────────────────┐  │
│ │ Tecnologia                       │ Pressoterapia sequenziale        │  │
│ │ Programmi preimpostati           │ 12                                │  │
│ │ Camere di pressione              │ 24 (gambali) + 8 (addominale)    │  │
│ │ Pressione regolabile             │ 30-180 mmHg                      │  │
│ │ Dimensioni                       │ 45 x 35 x 25 cm                  │  │
│ │ Peso                             │ 8.5 kg                            │  │
│ │ Alimentazione                    │ 220V 50Hz                         │  │
│ │ Garanzia                         │ 24 mesi                           │  │
│ │ Certificazioni                   │ CE Medical Device, ISO 13485     │  │
│ └─────────────────────────────────┴──────────────────────────────────┘  │
│                                                                          │
│ TAB: Modalita acquisto / noleggio (calculator interattivo)               │
│  ACQUISTO   |   NOLEGGIO                                                 │
│  ───────────────                                                         │
│  Quota mensile:  [─────|──────] 12 / 24 / 36 mesi                       │
│  Importo: €180/mese × 24 = €4.320 totale                                │
│  ✓ Manutenzione inclusa                                                  │
│  ✓ Sostituzione device guasto in 48h                                     │
│  [Richiedi contratto] (button primary)                                   │
│                                                                          │
│ TAB: Manuale PDF                                                         │
│ 📄 Manuale operatore PRESS FLOW v3.2.pdf (2.4 MB) [↓ Scarica]           │
│ 📄 Scheda tecnica (450 KB) [↓ Scarica]                                   │
│                                                                          │
│ TAB: Video                                                                │
│ [block-video embed YouTube/Vimeo full-width]                             │
│                                                                          │
├──────────────────────────────────────────────────────────────────────────┤
│ [block-features-list bg=primary-soft] "Perche scegliere Press Flow"      │
│ ✓ Versatilita operativa — tratta gambe, addome, braccia                  │
│ ✓ Economia degli spazi — un solo dispositivo compatto                    │
│ ✓ Display personalizzabile con logo istituto                             │
│ ✓ Assistenza Made in Italy dedicata, intervento in 48h                   │
│ ✓ Formazione operatore inclusa nel contratto                             │
├──────────────────────────────────────────────────────────────────────────┤
│ [block-cta] "Vuoi una demo nel tuo centro?"                              │
│  [Richiedi demo] (button primary) [Contattaci] (button secondary)        │
├──────────────────────────────────────────────────────────────────────────┤
│ [section] "Altre tecnologie SkinTemple" [related-products dei device]    │
├──────────────────────────────────────────────────────────────────────────┤
│ [public-footer]                                                          │
└──────────────────────────────────────────────────────────────────────────┘
```

### Differenze chiave rispetto al cosmetico
- Nome prodotto: uppercase tracking-wide (es. "PRESS FLOW", "LASER DIODO D-WAVE 2000 w")
- CTA primaria: "Richiedi demo" (form contatto) o "Acquista subito"
- CTA secondaria: outline teal
- Toggle Acquisto/Noleggio: variant-selector con pill teal piene/outline
- Calculator noleggio: slider mesi 12/24/36 + display totale aggiornato in tempo reale
- Tab estese: Specifiche tecniche, Certificazioni, Modalita acquisto/noleggio, Manuale PDF, Video
- Badge "Made in Italy" prominente in alto vicino al brand label
- Garanzia 24 mesi sempre visibile come trust signal
- Nessun quantity stepper, niente "Aggiungi al carrello"
- Sezione features list con sfondo `primary-soft` per enfatizzare i benefici

**Livewire coinvolti**: `Catalog\DeviceConfigurator` (variante di `QuickView` per calcolatore noleggio), `Catalog\RequestDemoForm` (form contatto con campo "device interessato" pre-popolato)

---

## 5. Pagina Categoria / Macroarea

```
┌─────────────────────────────────────────────────────────────────────────┐
│ [public-header]                                                         │
├─────────────────────────────────────────────────────────────────────────┤
│                                                                         │
│  [block-hero layout="center" con immagine categoria]                    │
│  "Cura del Viso" (H1 Cormorant text-5xl)                               │
│  Breve descrizione della macroarea (Inter text-lg neutral.600)          │
│                                                                         │
├─────────────────────────────────────────────────────────────────────────┤
│ [breadcrumb] Home > Prodotti > Viso                                     │
├────────────────┬────────────────────────────────────────────────────────┤
│ [filter-       │ Filtri = microaree della macroarea                     │
│  sidebar]      │                                                        │
│                │ [product-grid] + [pagination]                          │
│  MICROAREA  ▾  │                                                        │
│  ☐ Creme viso  │                                                        │
│  ☐ Sieri       │                                                        │
│  ☐ Contorno    │                                                        │
│  ...           │                                                        │
│                │                                                        │
│  PREZZO     +  │                                                        │
│  MARCHIO    +  │                                                        │
├────────────────┴────────────────────────────────────────────────────────┤
│ [public-footer]                                                         │
└─────────────────────────────────────────────────────────────────────────┘
```

**Livewire coinvolti**: `Catalog\ProductFilters`, `Catalog\ProductGrid`

---

## 6. Carrello

### Desktop: Drawer laterale destro
Vedi specifica dettagliata in `Cart\CartDrawer` (08-livewire-components-spec.md).

### Mobile: Bottom sheet
- Slide up dal basso, `max-h-[85vh]`, `rounded-t-3xl`
- Handle di trascinamento in alto
- Lista items scrollabile
- Footer sticky con totale + CTA checkout

### Pagina carrello dedicata (opzionale, /cart)
Per utenti che preferiscono una vista completa:
```
┌─────────────────────────────────────────────────────────────────────────┐
│ [public-header]                                                         │
├─────────────────────────────────────────────────────────────────────────┤
│  "Il tuo carrello" (H1)                                                 │
│                                                                         │
│  ┌─────────────────────────────────────┬─────────────────────────┐     │
│  │ [cart-item] immagine, nome,         │ [cart-summary]          │     │
│  │  variante, qty stepper, prezzo,     │ Subtotale: €XX          │     │
│  │  rimuovi                            │ Spedizione: calcolata   │     │
│  │ ──────────────────────────          │  al checkout            │     │
│  │ [cart-item]                          │ [coupon-input]          │     │
│  │ ──────────────────────────          │ ─────────               │     │
│  │ [cart-item]                          │ Totale: €XX             │     │
│  │                                      │ [Vai al checkout]       │     │
│  │                                      │  (sticky top-24)        │     │
│  └─────────────────────────────────────┴─────────────────────────┘     │
│                                                                         │
├─────────────────────────────────────────────────────────────────────────┤
│ [public-footer]                                                         │
└─────────────────────────────────────────────────────────────────────────┘
```

---

## 7. Checkout (Multi-step)

```
┌─────────────────────────────────────────────────────────────────────────┐
│ [header semplificato: solo logo centrato + "Torna allo shop"]           │
├─────────────────────────────────────────────────────────────────────────┤
│                                                                         │
│  [checkout-stepper]                                                     │
│  (1)──────(2)──────(3)──────(4)                                        │
│  Indirizzo Spedizione Pagamento Conferma                                │
│                                                                         │
│  ┌─────────────────────────────────────┬─────────────────────────┐     │
│  │                                      │ [order-summary-sticky]  │     │
│  │  STEP 1: Indirizzo di spedizione     │                         │     │
│  │  [address-form]                      │ Crema Viso x1   €39    │     │
│  │  oppure: seleziona indirizzo salvato │ Siero Notte x2  €78    │     │
│  │                                      │ ─────────────          │     │
│  │  STEP 2: Metodo di spedizione        │ Subtotale      €117    │     │
│  │  [shipping-method-card] Standard 3-5gg €5,90                   │     │
│  │  [shipping-method-card] Express 1-2gg  €9,90                   │     │
│  │                                      │ Spedizione     €5,90   │     │
│  │  STEP 3: Pagamento                   │ Sconto         -€10    │     │
│  │  [payment-method-card] Carta          │ ─────────────          │     │
│  │  [payment-method-card] PayPal         │ TOTALE        €112,90 │     │
│  │  [terms-checkbox]                     │                         │     │
│  │                                      │                         │     │
│  │  STEP 4: Riepilogo + Conferma        │                         │     │
│  │  [Conferma ordine] button lg          │                         │     │
│  │                                      │                         │     │
│  └─────────────────────────────────────┴─────────────────────────┘     │
│                                                                         │
└─────────────────────────────────────────────────────────────────────────┘
```

### Mobile
- Stepper in alto compatto (numeri + label step corrente)
- Riepilogo ordine: collassabile in alto ("Mostra riepilogo ▾")
- Form full-width
- Button "Continua" / "Conferma ordine" sticky in basso

**Livewire coinvolti**: `Checkout\CheckoutFlow`, `Checkout\AddressForm`, `Checkout\ShippingMethodSelector`, `Checkout\PaymentMethodSelector`

---

## 8. Account (Dashboard Ordini)

```
┌─────────────────────────────────────────────────────────────────────────┐
│ [public-header]                                                         │
├─────────────────────────────────────────────────────────────────────────┤
│ [breadcrumb] Home > Il mio account                                      │
├────────────────┬────────────────────────────────────────────────────────┤
│                │                                                        │
│ [account-      │  "I miei ordini" (H1)                                  │
│  sidebar-nav]  │                                                        │
│                │  [order-card] #ST-2026-001 | 12 Mag 2026               │
│  Dashboard     │  [order-status-badge variant="success"] Consegnato     │
│  Ordini ←      │  2 articoli — €78,00                                   │
│  Indirizzi     │  [Dettaglio >]                                         │
│  Lista desideri│  ────────────────────────────                          │
│  Profilo       │  [order-card] #ST-2026-002 | 10 Mag 2026              │
│  ──────        │  [order-status-badge variant="info"] In elaborazione   │
│  Disconnetti   │  1 articolo — €39,00                                   │
│                │  [Dettaglio >]                                          │
│                │                                                        │
│                │  [pagination]                                           │
│                │                                                        │
├────────────────┴────────────────────────────────────────────────────────┤
│ [public-footer]                                                         │
└─────────────────────────────────────────────────────────────────────────┘
```

### Mobile
- Sidebar nav diventa tab orizzontale scrollabile in alto o dropdown
- Card ordine full-width

**Livewire coinvolti**: `Account\ProfileEditor`, `Account\AddressManager`

---

## 9. Blog Index

```
┌─────────────────────────────────────────────────────────────────────────┐
│ [public-header]                                                         │
├─────────────────────────────────────────────────────────────────────────┤
│  "Blog" (H1 Cormorant text-5xl centrato)                                │
│  "Consigli, novita e approfondimenti sulla cura della pelle"            │
│                                                                         │
│  ┌──────────────────────────────────────────────────────────────────┐   │
│  │ [blog-card featured] — primo articolo grande                     │   │
│  │ Immagine larga + titolo + excerpt + data + autore                │   │
│  └──────────────────────────────────────────────────────────────────┘   │
│                                                                         │
│  ┌────────┐ ┌────────┐ ┌────────┐                                      │
│  │blog-   │ │blog-   │ │blog-   │                                      │
│  │card    │ │card    │ │card    │                                      │
│  └────────┘ └────────┘ └────────┘                                      │
│  ┌────────┐ ┌────────┐ ┌────────┐                                      │
│  │blog-   │ │blog-   │ │blog-   │                                      │
│  │card    │ │card    │ │card    │                                      │
│  └────────┘ └────────┘ └────────┘                                      │
│                                                                         │
│  [pagination]                                                           │
├─────────────────────────────────────────────────────────────────────────┤
│ [public-footer]                                                         │
└─────────────────────────────────────────────────────────────────────────┘
```

## 10. Blog Dettaglio

```
┌─────────────────────────────────────────────────────────────────────────┐
│ [public-header]                                                         │
├─────────────────────────────────────────────────────────────────────────┤
│ [breadcrumb] Home > Blog > Titolo articolo                              │
│                                                                         │
│  ┌──────────────────────────────────────────────────────────────────┐   │
│  │  12 Maggio 2026 | 5 min lettura                                  │   │
│  │  "Titolo Articolo" (H1 Cormorant text-5xl)                       │   │
│  │  [Immagine hero articolo full-width rounded-2xl]                  │   │
│  └──────────────────────────────────────────────────────────────────┘   │
│                                                                         │
│  ┌──────────┬───────────────────────────────────────────────────────┐   │
│  │ [blog-   │ [prose typography]                                     │   │
│  │  toc]    │ Contenuto articolo con heading, paragrafi,             │   │
│  │ sticky   │ immagini, liste...                                     │   │
│  │ top-24   │                                                        │   │
│  │          │ [blog-author-bio] Avatar + nome + bio                  │   │
│  │          │                                                        │   │
│  │          │ [share-buttons]                                        │   │
│  └──────────┴───────────────────────────────────────────────────────┘   │
│                                                                         │
│  [section] "Articoli correlati"                                         │
│  ┌────────┐ ┌────────┐ ┌────────┐                                      │
│  │blog-   │ │blog-   │ │blog-   │                                      │
│  └────────┘ └────────┘ └────────┘                                      │
├─────────────────────────────────────────────────────────────────────────┤
│ [public-footer]                                                         │
└─────────────────────────────────────────────────────────────────────────┘
```

---

## 11. Chi Siamo

```
┌─────────────────────────────────────────────────────────────────────────┐
│ [public-header]                                                         │
├─────────────────────────────────────────────────────────────────────────┤
│                                                                         │
│  [block-hero layout="center"]                                           │
│  "SkinTemple" (H1 Cormorant text-6xl)                                   │
│  "Nasce da una visione concreta, costruita sul campo."                  │
│                                                                         │
├─────────────────────────────────────────────────────────────────────────┤
│                                                                         │
│  [block-text] (prose max-w-3xl centrato)                                │
│  Testo lungo "Chi Siamo" da specifiche...                               │
│  Dall'esperienza diretta del fondatore...                               │
│                                                                         │
├─────────────────────────────────────────────────────────────────────────┤
│                                                                         │
│  [block-image full-width] Foto azienda/team                             │
│                                                                         │
├─────────────────────────────────────────────────────────────────────────┤
│                                                                         │
│  [block-features] 3 colonne                                             │
│  Made in Italy | Assistenza dedicata | Multifunzionalita                │
│                                                                         │
├─────────────────────────────────────────────────────────────────────────┤
│                                                                         │
│  [block-text-quote]                                                     │
│  "SkinTemple e piu di una tecnologia: e un partner di crescita          │
│   per il tuo business."                                                 │
│                                                                         │
├─────────────────────────────────────────────────────────────────────────┤
│                                                                         │
│  [block-cta] "Scopri le nostre tecnologie" + [Esplora] button          │
│                                                                         │
├─────────────────────────────────────────────────────────────────────────┤
│ [public-footer]                                                         │
└─────────────────────────────────────────────────────────────────────────┘
```

---

## 12. Pagina Statica Generica (CMS)

```
┌─────────────────────────────────────────────────────────────────────────┐
│ [public-header]                                                         │
├─────────────────────────────────────────────────────────────────────────┤
│ [breadcrumb] Home > {{ $page->title }}                                  │
│                                                                         │
│  [block-renderer :blocks="$page->blocks"]                               │
│  (renderizza dinamicamente i blocchi CMS configurati nell'admin)        │
│                                                                         │
├─────────────────────────────────────────────────────────────────────────┤
│ [public-footer]                                                         │
└─────────────────────────────────────────────────────────────────────────┘
```

---

## 13. Pagine 404 e 500

### 404
```
┌─────────────────────────────────────────────────────────────────────────┐
│ [public-header]                                                         │
├─────────────────────────────────────────────────────────────────────────┤
│                                                                         │
│               (centrato verticalmente)                                  │
│                                                                         │
│                    404                                                   │
│              (text-7xl Cormorant tracking-tighter neutral.200)          │
│                                                                         │
│         Pagina non trovata                                              │
│  (text-xl font-semibold neutral.900)                                    │
│                                                                         │
│  La pagina che cerchi non esiste o e stata spostata.                    │
│  (text-base neutral.500)                                                │
│                                                                         │
│         [Torna alla home] (button primary)                              │
│         [Vai allo shop] (button secondary)                              │
│                                                                         │
├─────────────────────────────────────────────────────────────────────────┤
│ [public-footer]                                                         │
└─────────────────────────────────────────────────────────────────────────┘
```

### 500
Analogo, con messaggio "Si e verificato un errore. Stiamo lavorando per risolverlo." e button "Riprova" + "Torna alla home".

---

## 14. Search Overlay

```
┌─────────────────────────────────────────────────────────────────────────┐
│  [search-overlay] (fixed inset-0, bg blur)                     [X]     │
│                                                                         │
│     ┌──────────────────────────────────────────────────────┐           │
│     │  🔍 Cerca prodotti, categorie, articoli...           │           │
│     │  [search-input] (text-lg, rounded-2xl, bg-neutral-100)           │
│     └──────────────────────────────────────────────────────┘           │
│                                                                         │
│     RICERCHE RECENTI                                                    │
│     [search-recent]                                                     │
│     crema viso × | siero × | acido ialuronico × | Cancella tutto       │
│                                                                         │
│     ──── oppure dopo digitazione ────                                   │
│                                                                         │
│     PRODOTTI                                                            │
│     [search-result-product] immagine | nome | brand | prezzo            │
│     [search-result-product]                                             │
│     [search-result-product]                                             │
│     Vedi tutti i risultati per "crema" →                                │
│                                                                         │
│     PAGINE                                                              │
│     [search-result-page] Chi Siamo | Pagina                            │
│     [search-result-page] Guida alla skincare | Blog                     │
│                                                                         │
└─────────────────────────────────────────────────────────────────────────┘
```

**Livewire coinvolti**: `Catalog\ProductSearch`

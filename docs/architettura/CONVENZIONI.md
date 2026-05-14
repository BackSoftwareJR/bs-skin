# Convenzioni di Codice — SkinTemple

## Naming

| Contesto | Convenzione | Esempio |
|---|---|---|
| Classi | PascalCase | `PlaceOrderAction` |
| Metodi/variabili | camelCase | `$grandTotal`, `calculateDiscount()` |
| Tabelle/colonne DB | snake_case | `order_items`, `created_at` |
| Route names | kebab-case dot-notation | `public.shop.product` |
| Blade views | kebab-case | `product-card.blade.php` |
| Config keys | snake_case dot-notation | `skintemple.shipping.flat_rate` |

## Architettura

### Action classes (`app/Actions/`)
Responsabilità singola, scrivono stato. Un metodo pubblico `execute()`.
```php
class PlaceOrderAction
{
    public function execute(Cart $cart, Customer $customer): Order { ... }
}
```

### Service classes (`app/Services/`)
Metodi puri, non scrivono stato. Calcoli, trasformazioni, query.

### Contracts (`app/Contracts/`)
Interfacce per integrazioni esterne. Implementazioni in `app/Integrations/`.

### Form Request
Ogni endpoint ha il suo Form Request con regole esplicite. Mai validazione inline nei controller.

### Eager Loading
Sempre dichiarato. Mai N+1. Usare `$with` nei modelli o `->with()` nelle query.

### Eventi e Listener
Side-effect tramite eventi: `OrderPlaced` → `SendOrderConfirmationListener`.
I listener usano `AsyncMail` per email non-bloccanti.

### Switch esaustivi
Ogni `match`/`switch` su enum deve coprire tutti i casi. Nessun `default` generico se l'enum è controllato.

### Import
Sempre top-of-file. Mai `use` inline dentro metodi.

## Frontend

### Blade Components
- `<x-public.xxx>` → componenti pubblici (`resources/views/components/public/`)
- Componenti email in `resources/views/components/emails/`

### Livewire
Componenti in `app/Livewire/Public/`. Namespace: `livewire.public.xxx`.

### CSS/JS
- Tailwind utility-first
- Alpine.js per micro-interazioni
- Nessun jQuery o framework JS pesante

## Database
- Migrazioni solo per evoluzioni post-installazione
- Schema iniziale via `schema.sql` consolidato
- Soft delete solo dove business-critical (prodotti, ordini, clienti)
- JSON columns per dati flessibili (attributi varianti, snapshot ordini)

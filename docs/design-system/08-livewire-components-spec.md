# 08 — Specifica Componenti Livewire 3

Ogni componente Livewire risiede in `app/Livewire/Public/` con il relativo template Blade in `resources/views/livewire/public/`.
Tutti i componenti usano Livewire 3 (class-based con attributi PHP 8).

---

## Cart

### `Cart\CartDrawer`

**Path classe**: `app/Livewire/Public/Cart/CartDrawer.php`
**Path vista**: `resources/views/livewire/public/cart/cart-drawer.blade.php`

Drawer carrello con stato completo. Comunica con lo store Alpine per apertura/chiusura.

**Properties**:
```php
public bool $open = false;
public Collection $items;        // CartItem collection
public float $subtotal = 0;
public float $shippingEstimate = 0;
public float $discount = 0;
public float $total = 0;
public string $couponCode = '';
public ?string $couponError = null;
public ?string $couponSuccess = null;
```

**Methods**:
```php
public function mount(): void;                          // Carica carrello da sessione/DB
public function updateQuantity(int $itemId, int $qty): void;  // Aggiorna quantita
public function removeItem(int $itemId): void;          // Rimuovi articolo
public function applyCoupon(): void;                    // Applica codice coupon
public function removeCoupon(): void;                   // Rimuovi coupon
public function goToCheckout(): void;                   // Redirect a /checkout
protected function recalculate(): void;                 // Ricalcola totali
```

**Events emitted**: `cart-updated` (con `count` e `total`).
**Events listened**: `add-to-cart` (aggiunge item e apre drawer), `toggle-cart` (apre/chiude).

**Validazione**:
- `couponCode`: `required|string|min:3|max:30`
- Quantita: min 1, max stock disponibile

**Esempio Blade** (estratto):
```html
<div x-data="{ open: @entangle('open') }"
     x-show="open"
     x-trap.noscroll="open"
     @keydown.escape.window="open = false"
     class="fixed inset-0 z-40">
    <!-- Backdrop -->
    <div x-show="open" @click="open = false" class="fixed inset-0 bg-glass-dark backdrop-blur-md" aria-hidden="true"></div>

    <!-- Drawer desktop: laterale destro -->
    <div x-show="open"
         x-transition:enter="transition ease-apple duration-300"
         x-transition:enter-start="translate-x-full"
         x-transition:enter-end="translate-x-0"
         class="fixed right-0 inset-y-0 w-full max-w-md bg-surface shadow-soft-xl rounded-l-3xl hidden lg:flex lg:flex-col"
         role="dialog" aria-modal="true" aria-label="Carrello">

        <!-- Header -->
        <div class="flex items-center justify-between px-6 py-4 border-b border-neutral-100">
            <h2 class="text-lg font-semibold text-neutral-900">Carrello ({{ $items->count() }})</h2>
            <button @click="open = false" aria-label="Chiudi carrello" class="p-2 text-neutral-500 hover:text-neutral-900 transition-colors">
                <x-heroicon-o-x-mark class="h-5 w-5" />
            </button>
        </div>

        <!-- Items -->
        <div class="flex-1 overflow-y-auto px-6 py-4 space-y-4">
            @forelse($items as $item)
                <x-public.cart-item :item="$item" />
            @empty
                <x-public.empty-cart-state />
            @endforelse
        </div>

        <!-- Footer con totali -->
        @if($items->isNotEmpty())
        <div class="border-t border-neutral-100 px-6 py-4 space-y-3">
            <x-public.coupon-input />
            <x-public.cart-summary :subtotal="$subtotal" :shipping="$shippingEstimate" :discount="$discount" :total="$total" />
            <x-public.button class="w-full" size="lg" wire:click="goToCheckout">
                Vai al checkout — €{{ number_format($total, 2, ',', '.') }}
            </x-public.button>
        </div>
        @endif
    </div>

    <!-- Drawer mobile: bottom sheet (analogo ma con rounded-t-3xl e posizionamento bottom) -->
</div>
```

---

### `Cart\AddToCartButton`

**Path classe**: `app/Livewire/Public/Cart/AddToCartButton.php`

**Properties**:
```php
public int $productId;
public ?int $variantId = null;
public int $quantity = 1;
public string $state = 'idle'; // idle, loading, success, error
```

**Methods**:
```php
public function addToCart(): void;
```

**Events emitted**: `add-to-cart` (con `productId`, `variantId`, `quantity`).

**Esempio Blade**:
```html
<button wire:click="addToCart"
        wire:loading.attr="disabled"
        class="w-full rounded-full bg-brand-primary py-3 text-sm font-semibold text-surface transition-all duration-200 ease-apple
               hover:bg-brand-primary-hover active:scale-[0.98] disabled:opacity-50">
    <span wire:loading.remove>
        @if($state === 'success')
            <span class="flex items-center justify-center gap-2">
                <x-heroicon-m-check class="h-4 w-4" /> Aggiunto!
            </span>
        @elseif($state === 'error')
            Errore, riprova
        @else
            Aggiungi al carrello
        @endif
    </span>
    <span wire:loading class="flex items-center justify-center">
        <x-public.spinner size="sm" class="text-surface" />
    </span>
</button>
```

---

### `Cart\WishlistToggle`

**Path classe**: `app/Livewire/Public/Cart/WishlistToggle.php`

**Properties**:
```php
public int $productId;
public bool $inWishlist = false;
```

**Methods**:
```php
public function mount(int $productId): void;  // Verifica se gia in wishlist
public function toggle(): void;                // Aggiunge o rimuove
```

**Events emitted**: `wishlist-updated`.

**Esempio Blade**:
```html
<button wire:click="toggle" aria-label="{{ $inWishlist ? 'Rimuovi dalla lista desideri' : 'Aggiungi alla lista desideri' }}"
        class="flex h-9 w-9 items-center justify-center rounded-full transition-colors
               {{ $inWishlist ? 'text-danger' : 'text-neutral-400 hover:text-danger' }}">
    @if($inWishlist)
        <x-heroicon-s-heart class="h-5 w-5" />
    @else
        <x-heroicon-o-heart class="h-5 w-5" />
    @endif
</button>
```

---

## Catalog

### `Catalog\ProductFilters`

**Path classe**: `app/Livewire/Public/Catalog/ProductFilters.php`

Filtri sidebar che aggiornano la griglia prodotti senza reload pagina. Sincronizza stato con URL query string.

**Properties**:
```php
public array $selectedCategories = [];
public array $selectedBrands = [];
public ?float $priceMin = null;
public ?float $priceMax = null;
public array $selectedColors = [];
public bool $onlyPromo = false;
public bool $onlyNew = false;
public string $sortBy = 'newest'; // newest, price_asc, price_desc, name_asc, bestseller
```

**Methods**:
```php
public function updatedSelectedCategories(): void;   // Emette filter-changed
public function updatedSelectedBrands(): void;
public function updatedPriceMin(): void;
public function updatedPriceMax(): void;
public function removeFilter(string $key, string $value): void;
public function clearAllFilters(): void;
```

**URL sync**: usa `#[Url]` attribute di Livewire 3 su ogni property per mantenere i filtri nell'URL:
```php
#[Url(as: 'cat')]
public array $selectedCategories = [];

#[Url(as: 'brand')]
public array $selectedBrands = [];

#[Url(as: 'min')]
public ?float $priceMin = null;

#[Url(as: 'max')]
public ?float $priceMax = null;

#[Url(as: 'sort')]
public string $sortBy = 'newest';
```

**Events emitted**: `filter-changed` (con tutti i parametri filtro correnti).

---

### `Catalog\ProductGrid`

**Path classe**: `app/Livewire/Public/Catalog/ProductGrid.php`

Griglia prodotti paginata. Ascolta i filtri e aggiorna.

**Properties**:
```php
public int $perPage = 12;
public string $viewMode = 'grid'; // grid, list
```

**Methods**:
```php
public function render(): View;  // Query prodotti con filtri applicati
public function loadMore(): void; // Per infinite scroll opzionale
```

**Events listened**: `filter-changed`.

**Esempio Blade**:
```html
<div>
    <!-- Header con conteggio e ordinamento -->
    <div class="flex items-center justify-between mb-6">
        <p class="text-sm text-neutral-500">
            Mostrando <span class="font-medium text-neutral-900">{{ $products->count() }}</span> di {{ $products->total() }} risultati
        </p>
        <div class="flex items-center gap-4">
            <x-public.select wire:model.live="sortBy" class="text-sm">
                <option value="newest">Novita</option>
                <option value="price_asc">Prezzo crescente</option>
                <option value="price_desc">Prezzo decrescente</option>
                <option value="name_asc">Nome A-Z</option>
                <option value="bestseller">Piu venduti</option>
            </x-public.select>
        </div>
    </div>

    <!-- Griglia -->
    <x-public.product-grid :products="$products" />

    <!-- Paginazione -->
    <div class="mt-8">
        {{ $products->links('components.public.pagination') }}
    </div>
</div>
```

---

### `Catalog\ProductSearch`

**Path classe**: `app/Livewire/Public/Catalog/ProductSearch.php`

Ricerca live con debounce. Risultati divisi per tipo (prodotti, pagine).

**Properties**:
```php
#[Url(as: 'q')]
public string $query = '';
public Collection $productResults;
public Collection $pageResults;
public array $recentSearches = [];  // Da sessione
public bool $hasSearched = false;
```

**Methods**:
```php
public function updatedQuery(): void;  // Con debounce 300ms via wire:model.live.debounce.300ms
public function search(): void;
public function clearRecent(): void;
public function selectRecent(string $term): void;
```

**Esempio Blade**:
```html
<div class="space-y-6">
    <!-- Input ricerca -->
    <div class="relative">
        <x-heroicon-o-magnifying-glass class="absolute left-4 top-1/2 -translate-y-1/2 h-5 w-5 text-neutral-400" />
        <input type="search" wire:model.live.debounce.300ms="query"
               placeholder="Cerca prodotti, categorie, articoli..."
               class="w-full rounded-2xl border-0 bg-neutral-100 py-4 pl-12 pr-4 text-lg text-neutral-900 placeholder:text-neutral-400 focus:bg-surface focus:ring-2 focus:ring-brand-primary/20 transition-all"
               autofocus>
    </div>

    <!-- Risultati -->
    @if($hasSearched && $query !== '')
        @if($productResults->isNotEmpty())
            <div>
                <h3 class="text-xs font-semibold uppercase tracking-widest text-neutral-500 mb-3">Prodotti</h3>
                <div class="space-y-2">
                    @foreach($productResults->take(6) as $product)
                        <x-public.search-result-product :product="$product" />
                    @endforeach
                </div>
            </div>
        @endif
        @if($productResults->isEmpty() && $pageResults->isEmpty())
            <x-public.search-empty-state :query="$query" />
        @endif
    @elseif(!$hasSearched && count($recentSearches) > 0)
        <x-public.search-recent :searches="$recentSearches" />
    @endif
</div>
```

---

### `Catalog\QuickView`

**Path classe**: `app/Livewire/Public/Catalog/QuickView.php`

Modale con anteprima rapida prodotto.

**Properties**:
```php
public bool $open = false;
public ?Product $product = null;
public ?int $selectedVariantId = null;
public int $quantity = 1;
```

**Methods**:
```php
public function show(int $productId): void;   // Carica prodotto e apre modale
public function close(): void;
public function addToCart(): void;
```

**Events listened**: `quick-view` (con `productId`).

---

## Checkout

### `Checkout\CheckoutFlow`

**Path classe**: `app/Livewire/Public/Checkout/CheckoutFlow.php`

Multi-step checkout. Gestisce il flusso completo.

**Properties**:
```php
public int $currentStep = 1;     // 1=indirizzo, 2=spedizione, 3=pagamento, 4=conferma
public int $totalSteps = 4;
public ?int $selectedAddressId = null;
public bool $useNewAddress = false;
public ?int $selectedShippingMethodId = null;
public string $selectedPaymentMethod = '';   // stripe, paypal, bank_transfer
public bool $termsAccepted = false;
public ?Order $completedOrder = null;
```

**Methods**:
```php
public function nextStep(): void;
public function previousStep(): void;
public function goToStep(int $step): void;
public function placeOrder(): void;
```

**Validazione per step**:
- Step 1: indirizzo selezionato o nuovo indirizzo valido
- Step 2: metodo spedizione selezionato
- Step 3: metodo pagamento selezionato, termini accettati
- Step 4: conferma riepilogo

---

### `Checkout\AddressForm`

**Path classe**: `app/Livewire/Public/Checkout/AddressForm.php`

**Properties**:
```php
public string $firstName = '';
public string $lastName = '';
public string $address = '';
public string $addressLine2 = '';
public string $city = '';
public string $postalCode = '';
public string $province = '';
public string $phone = '';
public string $country = 'IT';
public bool $isDefault = false;
```

**Validazione**:
```php
protected array $rules = [
    'firstName'  => 'required|string|max:100',
    'lastName'   => 'required|string|max:100',
    'address'    => 'required|string|max:255',
    'city'       => 'required|string|max:100',
    'postalCode' => 'required|string|regex:/^\d{5}$/',
    'province'   => 'required|string|size:2',
    'phone'      => 'required|string|regex:/^(\+39)?\d{9,10}$/',
];
```

**Methods**:
```php
public function save(): void;      // Salva indirizzo
public function updated($prop): void;  // Validazione live su ogni campo
```

---

### `Checkout\ShippingMethodSelector`

**Path classe**: `app/Livewire/Public/Checkout/ShippingMethodSelector.php`

**Properties**:
```php
public Collection $methods;
public ?int $selectedMethodId = null;
public bool $loading = true;
```

**Methods**:
```php
public function mount(int $addressId): void;   // Calcola metodi in base a indirizzo + peso
public function selectMethod(int $methodId): void;
```

**Events emitted**: `shipping-selected` (con `methodId`, `price`).

---

### `Checkout\PaymentMethodSelector`

**Path classe**: `app/Livewire/Public/Checkout/PaymentMethodSelector.php`

**Properties**:
```php
public array $availableMethods = [];   // ['stripe', 'paypal', 'bank_transfer']
public string $selectedMethod = '';
```

**Methods**:
```php
public function mount(): void;
public function selectMethod(string $method): void;
```

Nota: l'integrazione effettiva con Stripe/PayPal avviene lato server al momento del `placeOrder()`. Il componente qui gestisce solo la selezione UI.

---

## Account

### `Account\OtpLoginForm`

**Path classe**: `app/Livewire/Public/Account/OtpLoginForm.php`

Step 1 del login passwordless: inserimento email + accettazione termini.

**Properties**:
```php
public string $email = '';
public bool $termsAccepted = false;
public bool $submitted = false;
public ?string $errorMessage = null;
```

**Validazione**:
```php
protected array $rules = [
    'email' => 'required|email:rfc',
    'termsAccepted' => 'accepted',
];
```

**Methods**:
```php
public function requestOtp(): void;   // Invia OTP, imposta submitted = true
```

**Events emitted**: `otp-requested` (con `email`).

**Esempio Blade**:
```html
<form wire:submit="requestOtp" class="space-y-4">
    <x-public.field-wrapper label="Email" for="login-email" :error="$errors->first('email')" required>
        <x-public.input type="email" id="login-email" wire:model="email"
                        placeholder="il-tuo@email.it" :error="$errors->first('email')" />
    </x-public.field-wrapper>

    <x-public.checkbox wire:model="termsAccepted">
        <span class="text-sm text-neutral-600">
            Ho letto e accetto i <a href="/termini" class="text-brand-primary-hover hover:underline">Termini di servizio</a>
            e la <a href="/privacy" class="text-brand-primary-hover hover:underline">Privacy Policy</a>
        </span>
    </x-public.checkbox>
    @error('termsAccepted')
        <p class="text-xs text-danger">{{ $message }}</p>
    @enderror

    <x-public.button type="submit" class="w-full" size="lg" :loading="false">
        Ricevi codice di accesso
    </x-public.button>
</form>
```

---

### `Account\OtpVerifyForm`

**Path classe**: `app/Livewire/Public/Account/OtpVerifyForm.php`

Step 2: inserimento OTP a 6 cifre.

**Properties**:
```php
public string $email;
public string $otp = '';
public int $resendCooldown = 0;    // Secondi rimanenti prima di poter reinviare
public ?string $errorMessage = null;
public int $attempts = 0;
```

**Methods**:
```php
public function mount(string $email): void;
public function verify(): void;         // Verifica OTP, login, redirect
public function resendOtp(): void;      // Reinvia OTP (con cooldown 60s)
public function updatedOtp(): void;     // Autosubmit quando 6 cifre inserite
```

**Esempio Blade**:
```html
<div class="space-y-6 text-center">
    <div>
        <h2 class="text-xl font-semibold text-neutral-900">Inserisci il codice</h2>
        <p class="text-sm text-neutral-500 mt-1">Abbiamo inviato un codice a <strong>{{ $email }}</strong></p>
    </div>

    <div class="flex justify-center gap-2">
        @for($i = 0; $i < 6; $i++)
            <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]"
                   class="h-12 w-10 rounded-xl border-neutral-300 text-center text-lg font-semibold shadow-inner-soft focus:border-brand-primary focus:ring-2 focus:ring-brand-primary/20"
                   x-on:input="$event.target.value && $event.target.nextElementSibling?.focus()"
                   x-on:keydown.backspace="!$event.target.value && $event.target.previousElementSibling?.focus()">
        @endfor
    </div>

    @if($errorMessage)
        <x-public.alert variant="danger">{{ $errorMessage }}</x-public.alert>
    @endif

    <div class="text-sm text-neutral-500">
        Non hai ricevuto il codice?
        @if($resendCooldown > 0)
            <span>Reinvia tra {{ $resendCooldown }}s</span>
        @else
            <button wire:click="resendOtp" class="text-brand-primary-hover hover:underline">Reinvia codice</button>
        @endif
    </div>
</div>
```

---

### `Account\ProfileEditor`

**Path classe**: `app/Livewire/Public/Account/ProfileEditor.php`

**Properties**:
```php
public string $firstName;
public string $lastName;
public string $email;      // readonly, non modificabile
public string $phone = '';
```

**Methods**:
```php
public function mount(): void;
public function save(): void;
```

---

### `Account\AddressManager`

**Path classe**: `app/Livewire/Public/Account/AddressManager.php`

CRUD indirizzi cliente.

**Properties**:
```php
public Collection $addresses;
public bool $showForm = false;
public ?int $editingAddressId = null;
```

**Methods**:
```php
public function mount(): void;
public function create(): void;           // Mostra form vuoto
public function edit(int $id): void;      // Mostra form con dati
public function delete(int $id): void;    // Elimina (con conferma)
public function setDefault(int $id): void; // Imposta come predefinito
```

---

## Newsletter

### `Newsletter\SubscribeForm`

**Path classe**: `app/Livewire/Public/Newsletter/SubscribeForm.php`

**Properties**:
```php
public string $email = '';
public string $state = 'idle';  // idle, loading, success, error
public ?string $message = null;
```

**Validazione**:
```php
protected array $rules = [
    'email' => 'required|email:rfc',
];
```

**Methods**:
```php
public function subscribe(): void;  // Salva, invia double opt-in, state = success
```

**Esempio Blade**:
```html
<div>
    @if($state === 'success')
        <div class="flex items-center gap-2 text-success">
            <x-heroicon-m-check-circle class="h-5 w-5" />
            <p class="text-sm font-medium">Grazie! Controlla la tua email per confermare l'iscrizione.</p>
        </div>
    @else
        <form wire:submit="subscribe" class="flex gap-2">
            <x-public.input type="email" wire:model="email" placeholder="La tua email"
                            :error="$errors->first('email')" class="flex-1" />
            <x-public.button type="submit" :loading="$state === 'loading'">Iscriviti</x-public.button>
        </form>
        @if($state === 'error' && $message)
            <p class="mt-2 text-xs text-danger">{{ $message }}</p>
        @endif
    @endif
</div>
```

---

## Riepilogo componenti Livewire

| Componente | Dominio | Interazione principale |
|------------|---------|----------------------|
| `Cart\CartDrawer` | Carrello | Gestione completa carrello |
| `Cart\AddToCartButton` | Carrello | Aggiunta al carrello |
| `Cart\WishlistToggle` | Carrello | Toggle wishlist |
| `Catalog\ProductFilters` | Catalogo | Filtri con URL sync |
| `Catalog\ProductGrid` | Catalogo | Griglia paginata |
| `Catalog\ProductSearch` | Catalogo | Ricerca live con debounce |
| `Catalog\QuickView` | Catalogo | Anteprima rapida modale |
| `Checkout\CheckoutFlow` | Checkout | Multi-step orchestrator |
| `Checkout\AddressForm` | Checkout | Form indirizzo con validazione |
| `Checkout\ShippingMethodSelector` | Checkout | Selezione spedizione |
| `Checkout\PaymentMethodSelector` | Checkout | Selezione pagamento |
| `Account\OtpLoginForm` | Account | Login OTP step 1 |
| `Account\OtpVerifyForm` | Account | Login OTP step 2 |
| `Account\ProfileEditor` | Account | Modifica profilo |
| `Account\AddressManager` | Account | CRUD indirizzi |
| `Newsletter\SubscribeForm` | Newsletter | Iscrizione newsletter |
| **Totale** | | **16 componenti** |

# SkinTemple — Mapping Tabelle → Modelli Eloquent Laravel

> Schema v1.0.0 — Ogni tabella è mappata al suo model con trait, relazioni, cast e scope.

---

## Legenda Trait

| Abbreviazione | Trait completo |
|---|---|
| **HF** | `Illuminate\Database\Eloquent\Factories\HasFactory` |
| **SD** | `Illuminate\Database\Eloquent\SoftDeletes` |
| **HM** | `Spatie\MediaLibrary\HasMedia` (interface) + `InteractsWithMedia` (trait) |
| **HR** | `Spatie\Permission\Traits\HasRoles` |
| **LA** | `Spatie\Activitylog\Traits\LogsActivity` |
| **HT** | `Spatie\Translatable\HasTranslations` |
| **HS** | `Spatie\Sluggable\HasSlug` |

---

## Sezione 1 — Laravel Core

### `User` → `users`
- **Namespace**: `App\Models\User`
- **Trait**: HF, HR, LA, `Authenticatable`, `HasApiTokens` (Sanctum), `TwoFactorAuthenticatable` (Fortify)
- **Relazioni**:
  - `sessions()` → hasMany(`Session`)
  - `createdPages()` → hasMany(`Page`, `created_by_user_id`)
  - `updatedPages()` → hasMany(`Page`, `updated_by_user_id`)
  - `blogPosts()` → hasMany(`BlogPost`, `author_user_id`)
  - `performedStockMovements()` → hasMany(`StockMovement`, `performed_by_user_id`)
  - `processedRefunds()` → hasMany(`Refund`, `processed_by_user_id`)
- **Cast**: `email_verified_at` → `datetime`, `is_active` → `boolean`, `last_login_at` → `datetime`, `password` → `hashed`
- **Scope**: `scopeActive($q)` → `where('is_active', true)`
- **Observer**: nessuno (Spatie Activitylog gestisce audit automatico)

### `Session` → `sessions`
- **Namespace**: `App\Models\Session`
- **Trait**: nessuno
- **Relazioni**: `user()` → belongsTo(`User`)
- **Note**: gestito nativamente da Laravel, model opzionale per query admin.

---

## Sezione 2 — RBAC

Le tabelle `permissions`, `roles`, `model_has_permissions`, `model_has_roles`, `role_has_permissions` sono gestite interamente da **Spatie Permission**. Non servono model custom.

---

## Sezione 3 — Customer & Auth

### `Customer` → `customers`
- **Namespace**: `App\Models\Customer`
- **Trait**: HF, SD, LA
- **Relazioni**:
  - `addresses()` → hasMany(`CustomerAddress`)
  - `defaultShippingAddress()` → hasOne(`CustomerAddress`)->where('is_default', true)->where('type', '!=', 'billing')
  - `defaultBillingAddress()` → hasOne(`CustomerAddress`)->where('is_default', true)->where('type', '!=', 'shipping')
  - `termsAcceptances()` → hasMany(`TermsAcceptance`)
  - `orders()` → hasMany(`Order`)
  - `carts()` → hasMany(`Cart`)
  - `coupons()` → hasMany(`Coupon`) (coupon dedicati)
  - `couponRedemptions()` → hasMany(`CouponRedemption`)
- **Cast**: `is_active` → `boolean`, `marketing_consent` → `boolean`, `marketing_consent_at` → `datetime`, `last_login_at` → `datetime`, `total_spent` → `decimal:2`, `deleted_at` → `datetime`
- **Scope**: `scopeActive($q)`, `scopeWithMarketing($q)` → `where('marketing_consent', true)`
- **Observer**: `CustomerObserver` — ricalcolo lazy `total_orders`/`total_spent` se necessario

### `CustomerAddress` → `customer_addresses`
- **Namespace**: `App\Models\CustomerAddress`
- **Trait**: HF
- **Relazioni**: `customer()` → belongsTo(`Customer`)
- **Cast**: `is_default` → `boolean`, `type` → `string` (ENUM)

### `TermsAcceptance` → `terms_acceptances`
- **Namespace**: `App\Models\TermsAcceptance`
- **Trait**: nessuno
- **Relazioni**: `customer()` → belongsTo(`Customer`)
- **Cast**: `accepted_at` → `datetime`, `document_type` → `string` (ENUM)
- **Note**: immutabile, non ha `updated_at`

### `OtpCode` → `otp_codes`
- **Namespace**: `App\Models\OtpCode`
- **Trait**: nessuno
- **Relazioni**: nessuna (collegato a `customers` via email, non FK)
- **Cast**: `expires_at` → `datetime`, `used_at` → `datetime`
- **Scope**: `scopeValid($q)` → `where('expires_at', '>', now())->whereNull('used_at')->where('attempts', '<', 5)`
- **Observer**: `OtpCodeObserver` — lazy cleanup OTP scaduti (probabilistico 20%)

---

## Sezione 4 — Catalogo

### `Brand` → `brands`
- **Namespace**: `App\Models\Brand`
- **Trait**: HF, SD, HT, HS, HM, LA
- **Translatable**: `['name', 'description']`
- **Relazioni**:
  - `products()` → hasMany(`Product`)
  - `seoMeta()` → morphOne(`SeoMeta`, 'seoable')
  - registerMediaCollections: `logo`
- **Cast**: `is_active` → `boolean`
- **Scope**: `scopeActive($q)`

### `Category` → `categories`
- **Namespace**: `App\Models\Category`
- **Trait**: HF, SD, HT, HS, HM, LA
- **Translatable**: `['name', 'description']`
- **Relazioni**:
  - `parent()` → belongsTo(`Category`, `parent_id`)
  - `children()` → hasMany(`Category`, `parent_id`)
  - `products()` → belongsToMany(`Product`, `category_product`)->withPivot('sort_order', 'is_primary')
  - `seoMeta()` → morphOne(`SeoMeta`, 'seoable')
  - registerMediaCollections: `cover`, `icon`
- **Cast**: `is_active` → `boolean`, `type` → `string` (ENUM)
- **Scope**: `scopeActive($q)`, `scopeMacroaree($q)` → `where('type', 'macroarea')`, `scopeMicroaree($q)`, `scopeRoots($q)` → `whereNull('parent_id')`
- **Note**: Categorie: 6 macroaree reali (Corpo, Pelle, Viso e corpo, Monouso, Tecnologie, Epilazione) con relative microaree. Products di tipo `device` nelle macroarea Tecnologie, tipo `cosmetic` per Pelle, tipo `cosmetic` per Monouso.

### `Attribute` → `attributes`
- **Namespace**: `App\Models\Attribute`
- **Trait**: HF, HT
- **Translatable**: `['name']`
- **Relazioni**: `values()` → hasMany(`AttributeValue`)
- **Cast**: `is_filterable` → `boolean`, `is_required` → `boolean`, `type` → `string` (ENUM)

### `AttributeValue` → `attribute_values`
- **Namespace**: `App\Models\AttributeValue`
- **Trait**: HF, HT
- **Translatable**: `['value']`
- **Relazioni**:
  - `attribute()` → belongsTo(`Attribute`)
  - `products()` → belongsToMany(`Product`, `product_attribute_values`)
  - `variants()` → belongsToMany(`ProductVariant`, `variant_attribute_values`, `attribute_value_id`, `variant_id`)

### `Product` → `products`
- **Namespace**: `App\Models\Product`
- **Trait**: HF, SD, HT, HS, HM, LA
- **Translatable**: `['name', 'short_description', 'description']`
- **Relazioni**:
  - `brand()` → belongsTo(`Brand`)
  - `categories()` → belongsToMany(`Category`, `category_product`)->withPivot('sort_order', 'is_primary')
  - `primaryCategory()` → belongsToMany(`Category`, `category_product`)->wherePivot('is_primary', true)
  - `variants()` → hasMany(`ProductVariant`)
  - `attributeValues()` → belongsToMany(`AttributeValue`, `product_attribute_values`)
  - `tags()` → belongsToMany(`ProductTag`, `product_product_tag`)
  - `seoMeta()` → morphOne(`SeoMeta`, 'seoable')
  - `orderItems()` → hasMany(`OrderItem`)
  - registerMediaCollections: `gallery`, `manual_pdf`, `video`
- **Cast**: `price` → `decimal:2`, `compare_at_price` → `decimal:2`, `cost` → `decimal:2`, `tax_rate` → `decimal:2`, `rental_daily_price` → `decimal:2`, `rental_monthly_price` → `decimal:2`, `is_featured` → `boolean`, `is_new` → `boolean`, `is_bestseller` → `boolean`, `is_rentable` → `boolean`, `requires_shipping` → `boolean`, `dimensions_json` → `array`, `technical_specs_json` → `array`, `certifications_json` → `array`, `published_at` → `datetime`, `product_type` → `string`, `status` → `string`
- **Scope**: `scopePublished($q)` → `where('status', 'published')->whereNotNull('published_at')`, `scopeActive($q)` alias published, `scopeFeatured($q)`, `scopeNew($q)`, `scopeBestseller($q)`, `scopeOfType($q, $type)` → `where('product_type', $type)`, `scopeInStock($q)` → join con inventory
- **Observer**: `ProductObserver` — invalidazione cache, ricalcolo `sales_count`

### `ProductVariant` → `product_variants`
- **Namespace**: `App\Models\ProductVariant`
- **Trait**: HF
- **Relazioni**:
  - `product()` → belongsTo(`Product`)
  - `attributeValues()` → belongsToMany(`AttributeValue`, `variant_attribute_values`, `variant_id`)
  - `inventory()` → hasOne(`Inventory`)
  - `cartItems()` → hasMany(`CartItem`)
  - `orderItems()` → hasMany(`OrderItem`)
- **Cast**: `price_override` → `decimal:2`, `is_active` → `boolean`
- **Scope**: `scopeActive($q)`, `scopeInStock($q)` → join con inventory
- **Accessors**: `effectivePrice` → `price_override ?? product->price`

### `ProductTag` → `product_tags`
- **Namespace**: `App\Models\ProductTag`
- **Trait**: HF, HT, HS
- **Translatable**: `['name']`
- **Relazioni**: `products()` → belongsToMany(`Product`, `product_product_tag`)

---

## Sezione 5 — Inventario

### `Inventory` → `inventory`
- **Namespace**: `App\Models\Inventory`
- **Trait**: HF
- **Relazioni**:
  - `productVariant()` → belongsTo(`ProductVariant`)
  - `movements()` → hasMany(`StockMovement`)
- **Cast**: `allow_backorder` → `boolean`, `last_movement_at` → `datetime`
- **Scope**: `scopeLowStock($q)` → `whereColumn('quantity', '<=', 'threshold_low')`, `scopeCritical($q)`, `scopeOutOfStock($q)` → `where('quantity', '<=', 0)->where('allow_backorder', false)`
- **Accessors**: `isLow`, `isCritical`, `isOutOfStock`

### `StockMovement` → `stock_movements`
- **Namespace**: `App\Models\StockMovement`
- **Trait**: nessuno
- **Relazioni**:
  - `inventory()` → belongsTo(`Inventory`)
  - `performedBy()` → belongsTo(`User`, `performed_by_user_id`)
- **Cast**: `type` → `string` (ENUM)
- **Observer**: `StockMovementObserver` — aggiorna `inventory.quantity` e `inventory.last_movement_at`, invia alert stock basso

---

## Sezione 6 — Carrello

### `Cart` → `carts`
- **Namespace**: `App\Models\Cart`
- **Trait**: HF
- **Relazioni**:
  - `customer()` → belongsTo(`Customer`)
  - `items()` → hasMany(`CartItem`)
  - `coupon()` → belongsTo(`Coupon`)
- **Cast**: `subtotal` → `decimal:2`, `discount_total` → `decimal:2`, `tax_total` → `decimal:2`, `total` → `decimal:2`, `expires_at` → `datetime`
- **Scope**: `scopeActive($q)` → `where('updated_at', '>', now()->subDays(7))`, `scopeExpired($q)`, `scopeBySession($q, $token)`, `scopeByCustomer($q, $customerId)`

### `CartItem` → `cart_items`
- **Namespace**: `App\Models\CartItem`
- **Trait**: HF
- **Relazioni**:
  - `cart()` → belongsTo(`Cart`)
  - `productVariant()` → belongsTo(`ProductVariant`)
- **Cast**: `unit_price_snapshot` → `decimal:2`, `subtotal_snapshot` → `decimal:2`

---

## Sezione 7 — Coupon e Promozioni

### `Coupon` → `coupons`
- **Namespace**: `App\Models\Coupon`
- **Trait**: HF, LA
- **Relazioni**:
  - `customer()` → belongsTo(`Customer`) (coupon dedicati)
  - `redemptions()` → hasMany(`CouponRedemption`)
  - `orders()` → hasMany(`Order`)
- **Cast**: `value` → `decimal:2`, `min_order_amount` → `decimal:2`, `max_discount` → `decimal:2`, `applicable_ids` → `array`, `is_active` → `boolean`, `starts_at` → `datetime`, `expires_at` → `datetime`, `type` → `string`, `applies_to` → `string`
- **Scope**: `scopeValid($q)` → attivo + non scaduto + non esaurito, `scopeForCustomer($q, $customerId)`, `scopeByCode($q, $code)`

### `CouponRedemption` → `coupon_redemptions`
- **Namespace**: `App\Models\CouponRedemption`
- **Trait**: nessuno
- **Relazioni**:
  - `coupon()` → belongsTo(`Coupon`)
  - `customer()` → belongsTo(`Customer`)
  - `order()` → belongsTo(`Order`)
- **Cast**: `discount_amount` → `decimal:2`, `redeemed_at` → `datetime`

### `Promotion` → `promotions`
- **Namespace**: `App\Models\Promotion`
- **Trait**: HF
- **Relazioni**: `customer()` → belongsTo(`Customer`)
- **Cast**: `rules_json` → `array`, `discount_value` → `decimal:2`, `is_active` → `boolean`, `starts_at` → `datetime`, `expires_at` → `datetime`
- **Scope**: `scopeActive($q)`, `scopeAutomatic($q)` → `where('type', 'automatic')`

---

## Sezione 8 — Ordini, Pagamenti, Spedizioni

### `Order` → `orders`
- **Namespace**: `App\Models\Order`
- **Trait**: HF, SD, LA
- **Relazioni**:
  - `customer()` → belongsTo(`Customer`)
  - `items()` → hasMany(`OrderItem`)
  - `statusHistory()` → hasMany(`OrderStatusHistory`)
  - `payments()` → hasMany(`Payment`)
  - `refunds()` → hasMany(`Refund`)
  - `shipments()` → hasMany(`Shipment`)
  - `coupon()` → belongsTo(`Coupon`)
  - `couponRedemption()` → hasOne(`CouponRedemption`)
- **Cast**: `subtotal` → `decimal:2`, `discount_total` → `decimal:2`, `tax_total` → `decimal:2`, `shipping_total` → `decimal:2`, `total` → `decimal:2`, `shipping_address_json` → `array`, `billing_address_json` → `array`, `payment_metadata` → `array`, `paid_at` → `datetime`, `shipped_at` → `datetime`, `delivered_at` → `datetime`, `cancelled_at` → `datetime`, `status` → `string`, `payment_status` → `string`, `invoice_status` → `string`
- **Scope**: `scopeByStatus($q, $status)`, `scopePending($q)`, `scopePaid($q)`, `scopeRecentFirst($q)` → `orderBy('created_at', 'desc')`, `scopeNeedsInvoice($q)` → `where('invoice_status', 'none')->where('payment_status', 'captured')`
- **Observer**: `OrderObserver` — crea `OrderStatusHistory`, invia email transazionali, aggiorna `customers.total_orders/total_spent`, decrementa inventario

### `OrderItem` → `order_items`
- **Namespace**: `App\Models\OrderItem`
- **Trait**: HF
- **Relazioni**:
  - `order()` → belongsTo(`Order`)
  - `product()` → belongsTo(`Product`)
  - `productVariant()` → belongsTo(`ProductVariant`)
- **Cast**: `unit_price` → `decimal:2`, `tax_rate` → `decimal:2`, `tax_amount` → `decimal:2`, `discount_amount` → `decimal:2`, `subtotal` → `decimal:2`, `total` → `decimal:2`, `product_snapshot_json` → `array`

### `OrderStatusHistory` → `order_status_history`
- **Namespace**: `App\Models\OrderStatusHistory`
- **Trait**: nessuno
- **Relazioni**:
  - `order()` → belongsTo(`Order`)
  - `performedBy()` → belongsTo(`User`, `performed_by_user_id`)
- **Note**: immutabile, solo `created_at`

### `Payment` → `payments`
- **Namespace**: `App\Models\Payment`
- **Trait**: HF
- **Relazioni**:
  - `order()` → belongsTo(`Order`)
  - `refunds()` → hasMany(`Refund`)
- **Cast**: `amount` → `decimal:2`, `metadata_json` → `array`, `webhook_payload_json` → `array`, `paid_at` → `datetime`, `failed_at` → `datetime`

### `Refund` → `refunds`
- **Namespace**: `App\Models\Refund`
- **Trait**: HF
- **Relazioni**:
  - `order()` → belongsTo(`Order`)
  - `payment()` → belongsTo(`Payment`)
  - `processedBy()` → belongsTo(`User`, `processed_by_user_id`)
- **Cast**: `amount` → `decimal:2`, `processed_at` → `datetime`

### `PaymentMethod` → `payment_methods`
- **Namespace**: `App\Models\PaymentMethod`
- **Trait**: HF
- **Relazioni**: nessuna diretta
- **Cast**: `config_json` → `array`, `is_active` → `boolean`
- **Scope**: `scopeActive($q)`, `scopeByProvider($q, $provider)`

### `Shipment` → `shipments`
- **Namespace**: `App\Models\Shipment`
- **Trait**: HF
- **Relazioni**: `order()` → belongsTo(`Order`)
- **Cast**: `shipped_at` → `datetime`, `delivered_at` → `datetime`

---

## Sezione 9 — CMS

### `Page` → `pages`
- **Namespace**: `App\Models\Page`
- **Trait**: HF, SD, HT, HS, HM, LA
- **Translatable**: `['title']`
- **Relazioni**:
  - `blocks()` → hasMany(`Block`)->orderBy('sort_order')
  - `seoMeta()` → morphOne(`SeoMeta`, 'seoable')
  - `createdBy()` → belongsTo(`User`, `created_by_user_id`)
  - `updatedBy()` → belongsTo(`User`, `updated_by_user_id`)
- **Cast**: `is_published` → `boolean`, `published_at` → `datetime`
- **Scope**: `scopePublished($q)` → `where('is_published', true)`

### `Block` → `blocks`
- **Namespace**: `App\Models\Block`
- **Trait**: HF
- **Relazioni**: `page()` → belongsTo(`Page`)
- **Cast**: `content_json` → `array`, `settings_json` → `array`, `is_active` → `boolean`, `starts_at` → `datetime`, `expires_at` → `datetime`
- **Scope**: `scopeActive($q)` → attivo + non scaduto, `scopeForLocation($q, $location)`, `scopeOrdered($q)` → `orderBy('sort_order')`

### `Menu` → `menus`
- **Namespace**: `App\Models\Menu`
- **Trait**: HF
- **Relazioni**: `items()` → hasMany(`MenuItem`)->whereNull('parent_id')->orderBy('sort_order')
- **Cast**: `is_active` → `boolean`
- **Scope**: `scopeByCode($q, $code)`, `scopeActive($q)`

### `MenuItem` → `menu_items`
- **Namespace**: `App\Models\MenuItem`
- **Trait**: HF, HT
- **Translatable**: `['label']`
- **Relazioni**:
  - `menu()` → belongsTo(`Menu`)
  - `parent()` → belongsTo(`MenuItem`, `parent_id`)
  - `children()` → hasMany(`MenuItem`, `parent_id`)->orderBy('sort_order')
  - `target()` → morphTo() (via `target_type` + `target_id`, opzionale)
- **Cast**: `opens_in_new_tab` → `boolean`, `is_active` → `boolean`, `type` → `string`

### `Media` → `media`
Gestito interamente da **Spatie Media Library**. Model: `Spatie\MediaLibrary\MediaCollections\Models\Media`.

### `SeoMeta` → `seo_meta`
- **Namespace**: `App\Models\SeoMeta`
- **Trait**: HF, HT
- **Translatable**: `['meta_title', 'meta_description', 'og_title', 'og_description']`
- **Relazioni**: `seoable()` → morphTo()
- **Cast**: `schema_markup_json` → `array`

### `Redirect` → `redirects`
- **Namespace**: `App\Models\Redirect`
- **Trait**: HF
- **Cast**: `is_active` → `boolean`
- **Scope**: `scopeActive($q)`

### `EmailTemplate` → `email_templates`
- **Namespace**: `App\Models\EmailTemplate`
- **Trait**: HF
- **Cast**: `available_variables_json` → `array`, `is_active` → `boolean`
- **Scope**: `scopeActive($q)`, `scopeByCode($q, $code)`

---

## Sezione 10 — Blog

### `BlogCategory` → `blog_categories`
- **Namespace**: `App\Models\BlogCategory`
- **Trait**: HF, HT, HS
- **Translatable**: `['name', 'description']`
- **Relazioni**: `posts()` → hasMany(`BlogPost`)
- **Cast**: `is_active` → `boolean`

### `BlogTag` → `blog_tags`
- **Namespace**: `App\Models\BlogTag`
- **Trait**: HF, HT, HS
- **Translatable**: `['name']`
- **Relazioni**: `posts()` → belongsToMany(`BlogPost`, `blog_post_blog_tag`)

### `BlogPost` → `blog_posts`
- **Namespace**: `App\Models\BlogPost`
- **Trait**: HF, SD, HT, HS, HM, LA
- **Translatable**: `['title', 'excerpt', 'body_html']`
- **Relazioni**:
  - `category()` → belongsTo(`BlogCategory`, `blog_category_id`)
  - `tags()` → belongsToMany(`BlogTag`, `blog_post_blog_tag`)
  - `author()` → belongsTo(`User`, `author_user_id`)
  - `seoMeta()` → morphOne(`SeoMeta`, 'seoable')
  - registerMediaCollections: `featured_image`, `content_images`
- **Cast**: `is_published` → `boolean`, `published_at` → `datetime`
- **Scope**: `scopePublished($q)` → `where('is_published', true)->whereNotNull('published_at')->where('published_at', '<=', now())`, `scopeRecentFirst($q)`

---

## Sezione 11 — Newsletter

### `NewsletterSubscriber` → `newsletter_subscribers`
- **Namespace**: `App\Models\NewsletterSubscriber`
- **Trait**: HF
- **Relazioni**: nessuna FK diretta
- **Cast**: `status` → `string` (ENUM), `synced_at` → `datetime`, `subscribed_at` → `datetime`, `unsubscribed_at` → `datetime`, `double_opt_in_expires_at` → `datetime`
- **Scope**: `scopeSubscribed($q)`, `scopePending($q)`, `scopeNeedsSync($q)` → `whereNull('synced_at')->where('status', 'subscribed')`
- **Observer**: `NewsletterSubscriberObserver` — sync lazy Mailchimp se abilitato

---

## Sezione 12 — Settings & Audit

### `Setting` → `settings`
Gestito da **Spatie Laravel Settings**. Non serve model custom; si usano le classi Settings (es. `GeneralSettings`, `CommerceSettings`, `SeoSettings`, ecc.).

### `Activity` → `activity_log`
Gestito da **Spatie Activitylog**. Model: `Spatie\Activitylog\Models\Activity`. Nessun model custom necessario.

---

## Riepilogo Observer

| Observer | Model | Responsabilità |
|---|---|---|
| `ProductObserver` | `Product` | Invalidazione cache catalogo, aggiornamento `sales_count` |
| `OrderObserver` | `Order` | Creazione `OrderStatusHistory`, email transazionali, aggiornamento `customers.total_orders/total_spent`, decremento inventario |
| `StockMovementObserver` | `StockMovement` | Aggiornamento `inventory.quantity` e `last_movement_at`, alert stock basso |
| `CustomerObserver` | `Customer` | Ricalcolo lazy aggregati |
| `OtpCodeObserver` | `OtpCode` | Cleanup lazy OTP scaduti (probabilistico) |
| `NewsletterSubscriberObserver` | `NewsletterSubscriber` | Sync Mailchimp se abilitato |

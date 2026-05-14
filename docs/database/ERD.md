# SkinTemple — Diagramma Entità-Relazione (ERD)

> Schema v1.0.0 — 2026-05-15 — MariaDB 10.6+

## Diagramma Mermaid

```mermaid
erDiagram

    %% ===== SEZIONE 1: LARAVEL CORE =====
    users {
        bigint id PK
        varchar name
        varchar email UK
        timestamp email_verified_at
        varchar password
        varchar locale
        boolean is_active
        timestamp last_login_at
    }

    sessions {
        varchar id PK
        bigint user_id FK
        varchar ip_address
        text user_agent
        longtext payload
        int last_activity
    }

    personal_access_tokens {
        bigint id PK
        varchar tokenable_type
        bigint tokenable_id
        varchar name
        varchar token UK
    }

    %% ===== SEZIONE 2: RBAC =====
    permissions {
        bigint id PK
        varchar name
        varchar guard_name
    }

    roles {
        bigint id PK
        varchar name
        varchar guard_name
    }

    model_has_permissions {
        bigint permission_id FK
        varchar model_type
        bigint model_id
    }

    model_has_roles {
        bigint role_id FK
        varchar model_type
        bigint model_id
    }

    role_has_permissions {
        bigint permission_id FK
        bigint role_id FK
    }

    %% ===== SEZIONE 3: CUSTOMER =====
    customers {
        bigint id PK
        varchar email UK
        varchar name
        varchar surname
        varchar phone
        boolean is_active
        boolean marketing_consent
        int total_orders
        decimal total_spent
        timestamp deleted_at
    }

    customer_addresses {
        bigint id PK
        bigint customer_id FK
        enum type
        boolean is_default
        varchar full_name
        varchar street
        varchar city
        varchar province
        varchar country
    }

    terms_acceptances {
        bigint id PK
        bigint customer_id FK
        varchar document_version
        enum document_type
        timestamp accepted_at
    }

    otp_codes {
        bigint id PK
        varchar email
        varchar code_hash
        int attempts
        timestamp expires_at
        timestamp used_at
    }

    %% ===== SEZIONE 4: CATALOGO =====
    brands {
        bigint id PK
        json name
        varchar slug UK
        json description
        boolean is_active
    }

    categories {
        bigint id PK
        bigint parent_id FK
        json name
        varchar slug UK
        enum type
        boolean is_active
    }

    attributes {
        bigint id PK
        varchar code UK
        json name
        enum type
        boolean is_filterable
    }

    attribute_values {
        bigint id PK
        bigint attribute_id FK
        json value
        varchar slug
        varchar color_hex
    }

    products {
        bigint id PK
        varchar sku UK
        json name
        varchar slug UK
        bigint brand_id FK
        enum product_type
        enum status
        decimal price
        decimal compare_at_price
        boolean is_rentable
        decimal rental_daily_price
        decimal rental_monthly_price
        boolean is_featured
        boolean is_new
        timestamp published_at
    }

    product_variants {
        bigint id PK
        bigint product_id FK
        varchar sku UK
        varchar name
        decimal price_override
        boolean is_active
    }

    category_product {
        bigint category_id FK
        bigint product_id FK
        boolean is_primary
    }

    product_attribute_values {
        bigint product_id FK
        bigint attribute_value_id FK
    }

    variant_attribute_values {
        bigint variant_id FK
        bigint attribute_value_id FK
    }

    product_tags {
        bigint id PK
        json name
        varchar slug UK
    }

    product_product_tag {
        bigint product_id FK
        bigint product_tag_id FK
    }

    %% ===== SEZIONE 5: INVENTARIO =====
    inventory {
        bigint id PK
        bigint product_variant_id FK UK
        int quantity
        int threshold_low
        boolean allow_backorder
    }

    stock_movements {
        bigint id PK
        bigint inventory_id FK
        enum type
        int quantity
        varchar reason
        bigint performed_by_user_id FK
    }

    %% ===== SEZIONE 6: CARRELLO =====
    carts {
        bigint id PK
        bigint customer_id FK
        varchar session_token
        decimal total
        bigint coupon_id FK
        timestamp expires_at
    }

    cart_items {
        bigint id PK
        bigint cart_id FK
        bigint product_variant_id FK
        int quantity
        decimal unit_price_snapshot
    }

    %% ===== SEZIONE 7: COUPON & PROMOZIONI =====
    coupons {
        bigint id PK
        varchar code UK
        enum type
        decimal value
        enum applies_to
        int usage_count
        bigint customer_id FK
        boolean is_active
    }

    coupon_redemptions {
        bigint id PK
        bigint coupon_id FK
        bigint customer_id FK
        bigint order_id FK
        decimal discount_amount
    }

    promotions {
        bigint id PK
        varchar name
        enum type
        json rules_json
        enum discount_type
        decimal discount_value
        boolean is_active
    }

    %% ===== SEZIONE 8: ORDINI =====
    orders {
        bigint id PK
        varchar order_number UK
        bigint customer_id FK
        varchar customer_email
        json shipping_address_json
        json billing_address_json
        decimal total
        enum status
        enum payment_status
        enum invoice_status
        bigint coupon_id FK
        timestamp deleted_at
    }

    order_items {
        bigint id PK
        bigint order_id FK
        bigint product_id FK
        bigint product_variant_id FK
        varchar sku
        int quantity
        decimal unit_price
        json product_snapshot_json
    }

    order_status_history {
        bigint id PK
        bigint order_id FK
        varchar from_status
        varchar to_status
        bigint performed_by_user_id FK
    }

    payments {
        bigint id PK
        bigint order_id FK
        varchar provider
        varchar external_id
        varchar status
        decimal amount
    }

    refunds {
        bigint id PK
        bigint order_id FK
        bigint payment_id FK
        decimal amount
        varchar status
        bigint processed_by_user_id FK
    }

    payment_methods {
        bigint id PK
        varchar code UK
        varchar name
        varchar provider
        boolean is_active
    }

    shipments {
        bigint id PK
        bigint order_id FK
        varchar carrier
        varchar tracking_number
        varchar status
    }

    %% ===== SEZIONE 9: CMS =====
    pages {
        bigint id PK
        varchar slug UK
        json title
        varchar template
        boolean is_published
        bigint created_by_user_id FK
    }

    blocks {
        bigint id PK
        bigint page_id FK
        varchar location
        varchar type
        json content_json
        int sort_order
        boolean is_active
    }

    menus {
        bigint id PK
        varchar code UK
        varchar name
        varchar location
    }

    menu_items {
        bigint id PK
        bigint menu_id FK
        bigint parent_id FK
        json label
        enum type
        varchar url
        int sort_order
    }

    media {
        bigint id PK
        varchar model_type
        bigint model_id
        char uuid UK
        varchar collection_name
        varchar file_name
    }

    seo_meta {
        bigint id PK
        varchar seoable_type
        bigint seoable_id
        json meta_title
        json meta_description
        varchar robots
    }

    redirects {
        bigint id PK
        varchar source_url UK
        varchar destination_url
        smallint status_code
    }

    email_templates {
        bigint id PK
        varchar code UK
        varchar name
        text subject_template
        longtext body_html_template
    }

    %% ===== SEZIONE 10: BLOG =====
    blog_categories {
        bigint id PK
        json name
        varchar slug UK
    }

    blog_tags {
        bigint id PK
        json name
        varchar slug UK
    }

    blog_posts {
        bigint id PK
        bigint blog_category_id FK
        json title
        varchar slug UK
        json body_html
        bigint author_user_id FK
        boolean is_published
    }

    blog_post_blog_tag {
        bigint blog_post_id FK
        bigint blog_tag_id FK
    }

    %% ===== SEZIONE 11: NEWSLETTER =====
    newsletter_subscribers {
        bigint id PK
        varchar email UK
        enum status
        varchar external_provider
    }

    %% ===== SEZIONE 12: SETTINGS & AUDIT =====
    settings {
        bigint id PK
        varchar group_name
        varchar name
        longtext payload
    }

    activity_log {
        bigint id PK
        varchar log_name
        text description
        varchar subject_type
        bigint subject_id
        varchar causer_type
        bigint causer_id
        json properties
    }

    %% ======================================
    %% RELAZIONI
    %% ======================================

    %% Core
    users ||--o{ sessions : "ha sessioni"

    %% RBAC (polimorfiche — le linee mostrano il concetto)
    roles ||--o{ role_has_permissions : "ha permessi"
    permissions ||--o{ role_has_permissions : "assegnato a ruoli"
    roles ||--o{ model_has_roles : "assegnato a model"
    permissions ||--o{ model_has_permissions : "assegnato a model"

    %% Customer
    customers ||--o{ customer_addresses : "ha indirizzi"
    customers ||--o{ terms_acceptances : "accettazioni GDPR"

    %% Catalogo
    categories ||--o{ categories : "parent_id (albero)"
    brands ||--o{ products : "produce"
    products ||--o{ product_variants : "ha varianti"
    attributes ||--o{ attribute_values : "ha valori"
    categories ||--o{ category_product : "contiene prodotti"
    products ||--o{ category_product : "in categorie"
    products ||--o{ product_attribute_values : "ha attributi"
    attribute_values ||--o{ product_attribute_values : "usato da prodotti"
    product_variants ||--o{ variant_attribute_values : "ha attributi"
    attribute_values ||--o{ variant_attribute_values : "usato da varianti"
    products ||--o{ product_product_tag : "ha tag"
    product_tags ||--o{ product_product_tag : "taggato su"

    %% Inventario
    product_variants ||--|| inventory : "giacenza 1:1"
    inventory ||--o{ stock_movements : "movimenti"
    users ||--o{ stock_movements : "effettuato da"

    %% Carrello
    customers ||--o{ carts : "ha carrelli"
    coupons ||--o{ carts : "applicato a"
    carts ||--o{ cart_items : "contiene"
    product_variants ||--o{ cart_items : "nel carrello"

    %% Coupon
    customers ||--o{ coupons : "coupon dedicati"
    coupons ||--o{ coupon_redemptions : "utilizzi"

    %% Ordini
    customers ||--o{ orders : "effettua"
    coupons ||--o{ orders : "usato in"
    orders ||--o{ order_items : "contiene"
    products ||--o{ order_items : "venduto in (snapshot)"
    product_variants ||--o{ order_items : "variante venduta"
    orders ||--o{ order_status_history : "storico stati"
    users ||--o{ order_status_history : "cambiato da"
    orders ||--o{ payments : "pagamenti"
    orders ||--o{ refunds : "rimborsi"
    payments ||--o{ refunds : "rimborsato da"
    users ||--o{ refunds : "processato da"
    orders ||--o{ shipments : "spedizioni"
    orders ||--o{ coupon_redemptions : "redemption"

    %% CMS
    pages ||--o{ blocks : "ha blocchi"
    users ||--o{ pages : "creato/modificato da"
    menus ||--o{ menu_items : "contiene"
    menu_items ||--o{ menu_items : "parent_id (albero)"

    %% Blog
    blog_categories ||--o{ blog_posts : "contiene"
    users ||--o{ blog_posts : "autore"
    blog_posts ||--o{ blog_post_blog_tag : "ha tag"
    blog_tags ||--o{ blog_post_blog_tag : "taggato in"
```

## Note sulle relazioni polimorfiche

### 1. `seo_meta` — Relazione polimorfica `seoable`
La tabella `seo_meta` usa una relazione polimorfica `morphTo` tramite le colonne `seoable_type` e `seoable_id`. Qualsiasi entità può avere meta SEO:
- `App\Models\Product` → meta SEO prodotto
- `App\Models\Category` → meta SEO categoria
- `App\Models\Brand` → meta SEO brand
- `App\Models\Page` → meta SEO pagina CMS
- `App\Models\BlogPost` → meta SEO articolo blog

### 2. `media` — Spatie Media Library (polimorfica `model`)
La tabella `media` usa `model_type` + `model_id` per associare file a qualsiasi entità:
- Prodotti (galleria immagini, PDF manuali)
- Brand (logo)
- Categorie (cover image)
- Pagine CMS (immagini contenuto)
- Blog Posts (featured image, immagini body)

### 3. `activity_log` — Spatie Activitylog (doppia polimorfica)
Doppia relazione polimorfica:
- **subject** (`subject_type` + `subject_id`): l'entità su cui è stata eseguita l'azione
- **causer** (`causer_type` + `causer_id`): chi ha eseguito l'azione (tipicamente `App\Models\User`)

### 4. `model_has_permissions` / `model_has_roles` — Spatie Permission (polimorfica)
Associano permessi e ruoli a qualsiasi model (in questo progetto solo `App\Models\User`), tramite `model_type` + `model_id`.

### 5. `personal_access_tokens` — Laravel Sanctum (polimorfica `tokenable`)
Usa `tokenable_type` + `tokenable_id` per associare token API a qualsiasi model autenticabile.

## Relazioni chiave non ovvie

| Relazione | Tipo | Note |
|---|---|---|
| `categories.parent_id → categories.id` | Self-referencing | Albero a 2 livelli (macroarea → microarea) |
| `menu_items.parent_id → menu_items.id` | Self-referencing | Albero per menu gerarchici |
| `orders.customer_id → customers.id` | FK nullable, SET NULL | L'ordine sopravvive alla cancellazione del customer |
| `order_items.product_id → products.id` | FK nullable, SET NULL | Snapshot in `product_snapshot_json` garantisce integrità storica |
| `inventory ↔ product_variants` | 1:1 | Ogni variante ha esattamente un record inventario |
| `carts.session_token` | Guest cart | Carrelli guest identificati da token sessione |
| `coupons.customer_id` | Coupon dedicato | Se non null, il coupon è riservato a quel singolo cliente |

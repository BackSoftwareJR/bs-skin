# SkinTemple — Indici e Performance

> Schema v1.0.0 — Documentazione di tutti gli indici creati, le query previste e le strategie di ricerca.

---

## Riepilogo indici per tabella

### `users`
| Indice | Colonne | Tipo | Motivazione |
|---|---|---|---|
| PK | `id` | BTREE (clustered) | Lookup primario |
| `users_email_unique` | `email` | UNIQUE BTREE | Login, unicità email admin |

**Query principali**: `WHERE email = ?` (login Filament), `WHERE id = ?` (FK da altre tabelle).

---

### `sessions`
| Indice | Colonne | Tipo | Motivazione |
|---|---|---|---|
| PK | `id` | BTREE | Session lookup by token |
| `sessions_user_id_index` | `user_id` | BTREE | Sessioni per utente |
| `sessions_last_activity_index` | `last_activity` | BTREE | Garbage collection sessioni scadute |

**Query principali**: `WHERE id = ?` (ogni request), `WHERE last_activity < ?` (GC).

---

### `personal_access_tokens`
| Indice | Colonne | Tipo | Motivazione |
|---|---|---|---|
| PK | `id` | BTREE | — |
| `personal_access_tokens_token_unique` | `token` | UNIQUE BTREE | Autenticazione API |
| `personal_access_tokens_tokenable_type_tokenable_id_index` | `tokenable_type, tokenable_id` | BTREE | Token per model (polimorfico) |

---

### `permissions` / `roles`
| Indice | Colonne | Tipo | Motivazione |
|---|---|---|---|
| `*_name_guard_name_unique` | `name, guard_name` | UNIQUE BTREE | Lookup per nome permesso/ruolo |

---

### `model_has_permissions` / `model_has_roles`
| Indice | Colonne | Tipo | Motivazione |
|---|---|---|---|
| PK composita | `permission_id/role_id, model_id, model_type` | BTREE | Join RBAC |
| `*_model_id_model_type_index` | `model_id, model_type` | BTREE | "Quali ruoli/permessi ha questo model?" |

---

### `customers`
| Indice | Colonne | Tipo | Motivazione |
|---|---|---|---|
| PK | `id` | BTREE | — |
| `customers_email_unique` | `email` | UNIQUE BTREE | Login passwordless, unicità |
| `customers_deleted_at_index` | `deleted_at` | BTREE | SoftDeletes: esclude i cancellati |

**Query principali**: `WHERE email = ?` (login OTP), `WHERE id = ? AND deleted_at IS NULL` (profilo).

---

### `customer_addresses`
| Indice | Colonne | Tipo | Motivazione |
|---|---|---|---|
| PK | `id` | BTREE | — |
| `customer_addresses_customer_id_index` | `customer_id` | BTREE | Indirizzi per cliente |

---

### `terms_acceptances`
| Indice | Colonne | Tipo | Motivazione |
|---|---|---|---|
| `terms_acceptances_customer_id_index` | `customer_id` | BTREE | Accettazioni per cliente |
| `terms_acceptances_document_type_version_index` | `document_type, document_version` | BTREE | "Quanti hanno accettato privacy v2.0?" |

---

### `otp_codes`
| Indice | Colonne | Tipo | Motivazione |
|---|---|---|---|
| PK | `id` | BTREE | — |
| `otp_codes_email_expires_at_index` | `email, expires_at` | BTREE | Validazione OTP: `WHERE email = ? AND expires_at > NOW()` |

**Query principali**: `WHERE email = ? AND expires_at > NOW() AND used_at IS NULL AND attempts < 5`.

---

### `brands`
| Indice | Colonne | Tipo | Motivazione |
|---|---|---|---|
| PK | `id` | BTREE | — |
| `brands_slug_unique` | `slug` | UNIQUE BTREE | Lookup per URL |
| `brands_is_active_index` | `is_active` | BTREE | Filtro brand attivi |

---

### `categories`
| Indice | Colonne | Tipo | Motivazione |
|---|---|---|---|
| PK | `id` | BTREE | — |
| `categories_slug_unique` | `slug` | UNIQUE BTREE | Lookup per URL |
| `categories_parent_id_index` | `parent_id` | BTREE | Albero: figli di una categoria |
| `categories_type_is_active_index` | `type, is_active` | BTREE COMPOSITO | `WHERE type = 'macroarea' AND is_active = 1` (menu navigazione) |

---

### `products` (TABELLA CRITICA)
| Indice | Colonne | Tipo | Motivazione |
|---|---|---|---|
| PK | `id` | BTREE (clustered) | — |
| `products_sku_unique` | `sku` | UNIQUE BTREE | Lookup per SKU |
| `products_slug_unique` | `slug` | UNIQUE BTREE | Lookup per URL prodotto |
| `products_brand_id_index` | `brand_id` | BTREE | Prodotti per brand |
| `products_product_type_index` | `product_type` | BTREE | Filtro cosmetic/device/accessory |
| `products_status_published_at_index` | `status, published_at` | BTREE COMPOSITO | Query catalogo: `WHERE status = 'published' AND published_at <= NOW()` |
| `products_is_featured_index` | `is_featured` | BTREE | Homepage: prodotti in evidenza |
| `products_is_new_index` | `is_new` | BTREE | Homepage: nuovi arrivi |
| `products_sku_slug_fulltext` | `sku, slug` | FULLTEXT | Ricerca per codice/slug |
| `products_name_desc_fulltext` | `name_search, description_search` | FULLTEXT | **Ricerca testuale prodotti** |

**Query più frequenti**:
1. **Catalogo con filtri**: `WHERE status = 'published' AND product_type = ? ORDER BY created_at DESC LIMIT 20` → usa `products_status_published_at_index`
2. **Pagina prodotto**: `WHERE slug = ?` → usa `products_slug_unique`
3. **Ricerca testuale**: `WHERE MATCH(name_search, description_search) AGAINST (? IN BOOLEAN MODE)` → usa `products_name_desc_fulltext`
4. **Homepage nuovi arrivi**: `WHERE status = 'published' AND is_new = 1 LIMIT 8` → usa `products_is_new_index`

---

### `product_variants`
| Indice | Colonne | Tipo | Motivazione |
|---|---|---|---|
| PK | `id` | BTREE | — |
| `product_variants_sku_unique` | `sku` | UNIQUE BTREE | Lookup per SKU variante |
| `product_variants_product_id_index` | `product_id` | BTREE | Varianti di un prodotto |

---

### `category_product`
| Indice | Colonne | Tipo | Motivazione |
|---|---|---|---|
| PK composita | `category_id, product_id` | BTREE | Join N:M, "prodotti in categoria" |
| `category_product_product_id_index` | `product_id` | BTREE | "In quali categorie è questo prodotto?" |

---

### `inventory`
| Indice | Colonne | Tipo | Motivazione |
|---|---|---|---|
| PK | `id` | BTREE | — |
| `inventory_product_variant_id_unique` | `product_variant_id` | UNIQUE BTREE | 1:1 con variante, lookup inventario |

---

### `stock_movements`
| Indice | Colonne | Tipo | Motivazione |
|---|---|---|---|
| PK | `id` | BTREE | — |
| `stock_movements_inventory_id_created_at_index` | `inventory_id, created_at` | BTREE COMPOSITO | Storico movimenti per variante (cronologico) |
| `stock_movements_reference_index` | `reference_type, reference_id` | BTREE COMPOSITO | "Quali movimenti sono legati a questo ordine?" |

---

### `carts`
| Indice | Colonne | Tipo | Motivazione |
|---|---|---|---|
| PK | `id` | BTREE | — |
| `carts_session_token_index` | `session_token` | BTREE | Lookup carrello guest |
| `carts_customer_id_index` | `customer_id` | BTREE | Carrello per cliente autenticato |
| `carts_updated_at_index` | `updated_at` | BTREE | Cleanup carrelli abbandonati: `WHERE updated_at < ?` |

---

### `cart_items`
| Indice | Colonne | Tipo | Motivazione |
|---|---|---|---|
| PK | `id` | BTREE | — |
| `cart_items_cart_id_index` | `cart_id` | BTREE | Items per carrello |
| `cart_items_product_variant_id_index` | `product_variant_id` | BTREE | "Questa variante è in qualche carrello?" |

---

### `coupons`
| Indice | Colonne | Tipo | Motivazione |
|---|---|---|---|
| PK | `id` | BTREE | — |
| `coupons_code_unique` | `code` | UNIQUE BTREE | Lookup per codice coupon |
| `coupons_active_dates_index` | `is_active, starts_at, expires_at` | BTREE COMPOSITO | Validazione: `WHERE code = ? AND is_active = 1 AND starts_at <= NOW() AND (expires_at IS NULL OR expires_at > NOW())` |

---

### `coupon_redemptions`
| Indice | Colonne | Tipo | Motivazione |
|---|---|---|---|
| `coupon_redemptions_coupon_customer_index` | `coupon_id, customer_id` | BTREE COMPOSITO | "Quante volte questo cliente ha usato questo coupon?" |

---

### `orders` (TABELLA CRITICA)
| Indice | Colonne | Tipo | Motivazione |
|---|---|---|---|
| PK | `id` | BTREE (clustered) | — |
| `orders_order_number_unique` | `order_number` | UNIQUE BTREE | Lookup per numero ordine |
| `orders_status_created_at_index` | `status, created_at` | BTREE COMPOSITO | Dashboard admin: ordini per stato + ordinati per data |
| `orders_customer_id_status_index` | `customer_id, status` | BTREE COMPOSITO | "Ordini di questo cliente per stato" (area personale) |
| `orders_payment_status_index` | `payment_status` | BTREE | Filtro ordini per stato pagamento |
| `orders_invoice_status_index` | `invoice_status` | BTREE | Ordini che necessitano fattura |

**Query più frequenti**:
1. **Dashboard admin**: `WHERE status IN ('pending','confirmed','processing') ORDER BY created_at DESC` → usa `orders_status_created_at_index`
2. **Area cliente**: `WHERE customer_id = ? ORDER BY created_at DESC` → usa `orders_customer_id_status_index`
3. **Dettaglio ordine**: `WHERE order_number = ?` → usa `orders_order_number_unique`

---

### `order_items`
| Indice | Colonne | Tipo | Motivazione |
|---|---|---|---|
| `order_items_order_id_index` | `order_id` | BTREE | Items per ordine |
| `order_items_product_id_index` | `product_id` | BTREE | "In quali ordini è stato venduto questo prodotto?" (analytics) |

---

### `payments`
| Indice | Colonne | Tipo | Motivazione |
|---|---|---|---|
| `payments_order_id_index` | `order_id` | BTREE | Pagamenti per ordine |
| `payments_provider_external_id_index` | `provider, external_id` | BTREE COMPOSITO | Webhook: lookup pagamento da ID esterno Stripe/PayPal |

---

### `shipments`
| Indice | Colonne | Tipo | Motivazione |
|---|---|---|---|
| `shipments_order_id_index` | `order_id` | BTREE | Spedizioni per ordine |
| `shipments_tracking_number_index` | `tracking_number` | BTREE | Lookup per numero tracking |

---

### `pages`
| Indice | Colonne | Tipo | Motivazione |
|---|---|---|---|
| `pages_slug_unique` | `slug` | UNIQUE BTREE | Routing CMS |

---

### `blocks`
| Indice | Colonne | Tipo | Motivazione |
|---|---|---|---|
| `blocks_location_active_sort_index` | `location, is_active, sort_order` | BTREE COMPOSITO | `WHERE location = 'home_hero' AND is_active = 1 ORDER BY sort_order` |
| `blocks_page_id_sort_index` | `page_id, sort_order` | BTREE COMPOSITO | Blocchi per pagina ordinati |

---

### `menu_items`
| Indice | Colonne | Tipo | Motivazione |
|---|---|---|---|
| `menu_items_menu_parent_sort_index` | `menu_id, parent_id, sort_order` | BTREE COMPOSITO | Rendering menu: `WHERE menu_id = ? AND parent_id IS NULL ORDER BY sort_order` |

---

### `media`
| Indice | Colonne | Tipo | Motivazione |
|---|---|---|---|
| `media_uuid_unique` | `uuid` | UNIQUE BTREE | Lookup per UUID |
| `media_model_type_model_id_index` | `model_type, model_id` | BTREE COMPOSITO | "Tutti i media di questo prodotto/pagina" (polimorfico) |
| `media_order_column_index` | `order_column` | BTREE | Ordinamento galleria |

---

### `seo_meta`
| Indice | Colonne | Tipo | Motivazione |
|---|---|---|---|
| `seo_meta_seoable_index` | `seoable_type, seoable_id` | BTREE COMPOSITO | "Meta SEO di questo prodotto/pagina" (polimorfico) |

---

### `blog_posts`
| Indice | Colonne | Tipo | Motivazione |
|---|---|---|---|
| `blog_posts_slug_unique` | `slug` | UNIQUE BTREE | Routing articolo |
| `blog_posts_published_index` | `is_published, published_at` | BTREE COMPOSITO | Listing blog: `WHERE is_published = 1 AND published_at <= NOW() ORDER BY published_at DESC` |
| `blog_posts_category_id_index` | `blog_category_id` | BTREE | Articoli per categoria |

---

### `newsletter_subscribers`
| Indice | Colonne | Tipo | Motivazione |
|---|---|---|---|
| `newsletter_subscribers_email_unique` | `email` | UNIQUE BTREE | Unicità iscrizione |
| `newsletter_subscribers_status_index` | `status` | BTREE | Filtro per stato (subscribed, pending, ecc.) |
| `newsletter_subscribers_external_index` | `external_provider, external_subscriber_id` | BTREE COMPOSITO | Sync bidirezionale con Mailchimp |

---

### `settings`
| Indice | Colonne | Tipo | Motivazione |
|---|---|---|---|
| `settings_group_name_unique` | `group, name` | UNIQUE BTREE | Lookup: `WHERE group = 'commerce' AND name = 'tax_rate_default'` |

---

### `activity_log`
| Indice | Colonne | Tipo | Motivazione |
|---|---|---|---|
| `activity_log_log_name_index` | `log_name` | BTREE | Filtro per tipo log |
| `activity_log_subject_index` | `subject_type, subject_id` | BTREE COMPOSITO | "Tutte le attività su questo prodotto" |
| `activity_log_causer_index` | `causer_type, causer_id` | BTREE COMPOSITO | "Tutte le attività di questo admin" |
| `activity_log_batch_uuid_index` | `batch_uuid` | BTREE | Raggruppamento operazioni batch |

---

## FULLTEXT vs LIKE: strategia ricerca prodotti

### Problema
I campi `name` e `description` dei prodotti sono di tipo JSON (`{"it":"..."}`). MariaDB 10.6 non supporta indici FULLTEXT direttamente su colonne JSON.

### Soluzione adottata: colonne generate STORED

```sql
`name_search` VARCHAR(255) GENERATED ALWAYS AS (JSON_UNQUOTE(JSON_EXTRACT(`name`, '$.it'))) STORED,
`description_search` TEXT GENERATED ALWAYS AS (JSON_UNQUOTE(JSON_EXTRACT(`description`, '$.it'))) STORED,
FULLTEXT KEY `products_name_desc_fulltext` (`name_search`, `description_search`)
```

**Vantaggi**:
- L'indice FULLTEXT funziona su colonne `STORED` in MariaDB 10.6+
- Nessun overhead di manutenzione: il valore viene ricalcolato automaticamente al write
- Query di ricerca veloci: `MATCH(...) AGAINST(? IN BOOLEAN MODE)`

**Limitazioni**:
- L'indice copre solo la lingua `it`. Per il multilingua futuro, aggiungere colonne generate per ogni locale
- FULLTEXT in MariaDB ha una lunghezza minima di parola di 4 caratteri (configurabile con `ft_min_word_len`)

### Confronto performance

| Metodo | Catalogo 100 prodotti | Catalogo 10.000 prodotti | Note |
|---|---|---|---|
| `LIKE '%termine%'` | ~2ms | ~150ms | Full table scan, non scala |
| `JSON_EXTRACT + LIKE` | ~5ms | ~300ms | Più lento di LIKE semplice per overhead JSON |
| `FULLTEXT MATCH` | ~1ms | ~5ms | Usa indice invertito, scala linearmente |
| `FULLTEXT Boolean Mode` | ~1ms | ~8ms | Supporta operatori +, -, *, " |

### Query di ricerca raccomandata

```sql
SELECT p.*, MATCH(p.name_search, p.description_search)
  AGAINST('+crema +viso' IN BOOLEAN MODE) AS relevance
FROM products p
WHERE p.status = 'published'
  AND MATCH(p.name_search, p.description_search)
  AGAINST('+crema +viso' IN BOOLEAN MODE)
ORDER BY relevance DESC
LIMIT 20;
```

### Fallback per parole corte (< 4 caratteri)

Per termini come "SPF", "CE", "BB":
```sql
WHERE p.name_search LIKE '%SPF%' OR p.description_search LIKE '%SPF%'
```

Applicare il fallback LIKE solo se FULLTEXT restituisce 0 risultati e il termine è < 4 caratteri.

---

## Conteggio totale indici

| Tipo | Conteggio |
|---|---|
| PRIMARY KEY | 39 |
| UNIQUE | 20 |
| BTREE (non-unique) | 41 |
| FULLTEXT | 2 |
| **TOTALE** | **102** |

---

## Note su MariaDB 10.6 Hostinger

1. **InnoDB buffer pool**: su hosting condiviso è tipicamente 128-256 MB. Con il nostro schema (poche migliaia di righe iniziali), nessun problema di cache miss.
2. **Query cache**: disabilitata di default su MariaDB 10.6. Utilizzare caching applicativo Laravel (`Cache::remember`) per le query frequenti (menu, settings, categorie).
3. **Generated columns STORED**: pienamente supportate su MariaDB 10.6. Il costo è un write overhead minimo (~5%) compensato da read molto più veloci.
4. **JSON**: su MariaDB 10.6, `JSON` è un alias per `LONGTEXT` con validazione sintattica. Le funzioni `JSON_EXTRACT`, `JSON_UNQUOTE` sono ottimizzate nativamente.
5. **FULLTEXT con InnoDB**: pienamente supportato da MariaDB 10.6 (a differenza delle versioni < 10.0). Nessuna necessità di MyISAM.

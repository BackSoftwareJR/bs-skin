-- ============================================================================
-- SKINTEMPLE E-COMMERCE — DATABASE SCHEMA
-- ============================================================================
-- Versione: 1.0.0
-- Data: 2026-05-15
-- Engine: MariaDB 10.6+ (Hostinger Premium Managed)
-- Charset: utf8mb4 / utf8mb4_unicode_ci
--
-- ISTRUZIONI PER L'IMPORTAZIONE:
-- 1) Da hPanel Hostinger, crea un nuovo database con collation utf8mb4_unicode_ci
-- 2) In phpMyAdmin, seleziona il database appena creato
-- 3) Vai su "Importa" > seleziona questo file > Esegui
-- 4) Attendi il messaggio di conferma "Import completato con successo"
-- 5) Il super admin di default è jrovera05@gmail.com / changeme123!
--    CAMBIA LA PASSWORD AL PRIMO LOGIN DA FILAMENT.
-- ============================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;
SET time_zone = '+00:00';
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';


-- ===================================================================
-- SEZIONE 1: TABELLE LARAVEL CORE
-- ===================================================================

-- Registro migrazioni Laravel
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` VARCHAR(255) NOT NULL,
  `batch` INT NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Utenti admin (backend Filament)
CREATE TABLE IF NOT EXISTS `users` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `email_verified_at` TIMESTAMP NULL DEFAULT NULL,
  `password` VARCHAR(255) NOT NULL,
  `two_factor_secret` TEXT NULL DEFAULT NULL,
  `two_factor_recovery_codes` TEXT NULL DEFAULT NULL,
  `two_factor_confirmed_at` TIMESTAMP NULL DEFAULT NULL,
  `remember_token` VARCHAR(100) NULL DEFAULT NULL,
  `current_team_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `profile_photo_path` VARCHAR(2048) NULL DEFAULT NULL,
  `locale` VARCHAR(5) NOT NULL DEFAULT 'it',
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `last_login_at` TIMESTAMP NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Token reset password
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` VARCHAR(255) NOT NULL,
  `token` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Sessioni utente (driver database)
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` VARCHAR(255) NOT NULL,
  `user_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `ip_address` VARCHAR(45) NULL DEFAULT NULL,
  `user_agent` TEXT NULL DEFAULT NULL,
  `payload` LONGTEXT NOT NULL,
  `last_activity` INT NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Cache applicativa (driver database)
CREATE TABLE IF NOT EXISTS `cache` (
  `key` VARCHAR(255) NOT NULL,
  `value` MEDIUMTEXT NOT NULL,
  `expiration` INT NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Lock per cache atomica
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` VARCHAR(255) NOT NULL,
  `owner` VARCHAR(255) NOT NULL,
  `expiration` INT NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Token API (Laravel Sanctum)
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `tokenable_type` VARCHAR(255) NOT NULL,
  `tokenable_id` BIGINT UNSIGNED NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `token` VARCHAR(64) NOT NULL,
  `abilities` TEXT NULL DEFAULT NULL,
  `last_used_at` TIMESTAMP NULL DEFAULT NULL,
  `expires_at` TIMESTAMP NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`, `tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ===================================================================
-- SEZIONE 2: RBAC (SPATIE PERMISSION)
-- ===================================================================

-- Permessi granulari
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `guard_name` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`, `guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Ruoli utente
CREATE TABLE IF NOT EXISTS `roles` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `guard_name` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`, `guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Associazione polimorfica model <-> permesso
CREATE TABLE IF NOT EXISTS `model_has_permissions` (
  `permission_id` BIGINT UNSIGNED NOT NULL,
  `model_type` VARCHAR(255) NOT NULL,
  `model_id` BIGINT UNSIGNED NOT NULL,
  PRIMARY KEY (`permission_id`, `model_id`, `model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`, `model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`)
    REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Associazione polimorfica model <-> ruolo
CREATE TABLE IF NOT EXISTS `model_has_roles` (
  `role_id` BIGINT UNSIGNED NOT NULL,
  `model_type` VARCHAR(255) NOT NULL,
  `model_id` BIGINT UNSIGNED NOT NULL,
  PRIMARY KEY (`role_id`, `model_id`, `model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`, `model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`)
    REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Associazione ruolo <-> permesso
CREATE TABLE IF NOT EXISTS `role_has_permissions` (
  `permission_id` BIGINT UNSIGNED NOT NULL,
  `role_id` BIGINT UNSIGNED NOT NULL,
  PRIMARY KEY (`permission_id`, `role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`)
    REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`)
    REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ===================================================================
-- SEZIONE 3: CUSTOMER & AUTH PASSWORDLESS
-- ===================================================================

-- Clienti storefront (separati dagli utenti admin)
CREATE TABLE IF NOT EXISTS `customers` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(255) NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `surname` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(30) NULL DEFAULT NULL,
  `locale` VARCHAR(5) NOT NULL DEFAULT 'it',
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `marketing_consent` TINYINT(1) NOT NULL DEFAULT 0,
  `marketing_consent_at` TIMESTAMP NULL DEFAULT NULL,
  `last_login_at` TIMESTAMP NULL DEFAULT NULL,
  `total_orders` INT UNSIGNED NOT NULL DEFAULT 0,
  `total_spent` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  `deleted_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `customers_email_unique` (`email`),
  KEY `customers_deleted_at_index` (`deleted_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Indirizzi cliente (spedizione / fatturazione)
CREATE TABLE IF NOT EXISTS `customer_addresses` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `customer_id` BIGINT UNSIGNED NOT NULL,
  `type` ENUM('shipping','billing','both') NOT NULL DEFAULT 'both',
  `is_default` TINYINT(1) NOT NULL DEFAULT 0,
  `full_name` VARCHAR(255) NOT NULL,
  `company` VARCHAR(255) NULL DEFAULT NULL,
  `vat_number` VARCHAR(20) NULL DEFAULT NULL,
  `sdi_code` VARCHAR(7) NULL DEFAULT NULL,
  `pec` VARCHAR(255) NULL DEFAULT NULL,
  `street` VARCHAR(255) NOT NULL,
  `civic` VARCHAR(20) NOT NULL,
  `postal_code` VARCHAR(10) NOT NULL,
  `city` VARCHAR(255) NOT NULL,
  `province` VARCHAR(2) NOT NULL,
  `country` VARCHAR(2) NOT NULL DEFAULT 'IT',
  `phone` VARCHAR(30) NULL DEFAULT NULL,
  `notes` TEXT NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `customer_addresses_customer_id_index` (`customer_id`),
  CONSTRAINT `customer_addresses_customer_id_foreign` FOREIGN KEY (`customer_id`)
    REFERENCES `customers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Accettazioni GDPR/termini con versioning
CREATE TABLE IF NOT EXISTS `terms_acceptances` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `customer_id` BIGINT UNSIGNED NOT NULL,
  `document_version` VARCHAR(20) NOT NULL,
  `document_type` ENUM('terms','privacy','cookie','marketing') NOT NULL,
  `accepted_at` TIMESTAMP NOT NULL,
  `ip_address` VARCHAR(45) NOT NULL,
  `user_agent` VARCHAR(500) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `terms_acceptances_customer_id_index` (`customer_id`),
  KEY `terms_acceptances_document_type_version_index` (`document_type`, `document_version`),
  CONSTRAINT `terms_acceptances_customer_id_foreign` FOREIGN KEY (`customer_id`)
    REFERENCES `customers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Codici OTP per login passwordless
CREATE TABLE IF NOT EXISTS `otp_codes` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(255) NOT NULL,
  `code_hash` VARCHAR(255) NOT NULL,
  `attempts` INT UNSIGNED NOT NULL DEFAULT 0,
  `expires_at` TIMESTAMP NOT NULL,
  `used_at` TIMESTAMP NULL DEFAULT NULL,
  `ip_address` VARCHAR(45) NOT NULL,
  `user_agent` VARCHAR(500) NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `otp_codes_email_expires_at_index` (`email`, `expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ===================================================================
-- SEZIONE 4: CATALOGO PRODOTTI
-- ===================================================================

-- Brand / marchi (Spatie Translatable su name, description)
CREATE TABLE IF NOT EXISTS `brands` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` JSON NOT NULL COMMENT 'Translatable: {"it":"..."}',
  `slug` VARCHAR(255) NOT NULL,
  `description` JSON NULL DEFAULT NULL COMMENT 'Translatable',
  `logo_path` VARCHAR(500) NULL DEFAULT NULL,
  `website_url` VARCHAR(500) NULL DEFAULT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `sort_order` INT NOT NULL DEFAULT 0,
  `seo_meta_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  `deleted_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `brands_slug_unique` (`slug`),
  KEY `brands_is_active_index` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Categorie prodotto (albero parent_id, macroarea/microarea)
CREATE TABLE IF NOT EXISTS `categories` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `parent_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `name` JSON NOT NULL COMMENT 'Translatable: {"it":"..."}',
  `slug` VARCHAR(255) NOT NULL,
  `description` JSON NULL DEFAULT NULL COMMENT 'Translatable',
  `type` ENUM('macroarea','microarea') NOT NULL DEFAULT 'microarea',
  `cover_image_path` VARCHAR(500) NULL DEFAULT NULL,
  `icon_path` VARCHAR(500) NULL DEFAULT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `sort_order` INT NOT NULL DEFAULT 0,
  `seo_meta_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  `deleted_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_slug_unique` (`slug`),
  KEY `categories_parent_id_index` (`parent_id`),
  KEY `categories_type_is_active_index` (`type`, `is_active`),
  CONSTRAINT `categories_parent_id_foreign` FOREIGN KEY (`parent_id`)
    REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Attributi prodotto configurabili (taglia, colore, capacità, ecc.)
CREATE TABLE IF NOT EXISTS `attributes` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` VARCHAR(50) NOT NULL,
  `name` JSON NOT NULL COMMENT 'Translatable: {"it":"..."}',
  `type` ENUM('select','multiselect','text','number','boolean','color','range') NOT NULL DEFAULT 'select',
  `is_filterable` TINYINT(1) NOT NULL DEFAULT 1,
  `is_required` TINYINT(1) NOT NULL DEFAULT 0,
  `sort_order` INT NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `attributes_code_unique` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Valori possibili per ogni attributo
CREATE TABLE IF NOT EXISTS `attribute_values` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `attribute_id` BIGINT UNSIGNED NOT NULL,
  `value` JSON NOT NULL COMMENT 'Translatable: {"it":"..."}',
  `slug` VARCHAR(255) NOT NULL,
  `sort_order` INT NOT NULL DEFAULT 0,
  `color_hex` VARCHAR(7) NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `attribute_values_attribute_id_index` (`attribute_id`),
  KEY `attribute_values_slug_index` (`slug`),
  CONSTRAINT `attribute_values_attribute_id_foreign` FOREIGN KEY (`attribute_id`)
    REFERENCES `attributes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Prodotti (cosmetic, device, accessory)
CREATE TABLE IF NOT EXISTS `products` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `sku` VARCHAR(64) NOT NULL,
  `name` JSON NOT NULL COMMENT 'Translatable: {"it":"..."}',
  `slug` VARCHAR(255) NOT NULL,
  `short_description` JSON NULL DEFAULT NULL COMMENT 'Translatable',
  `description` JSON NULL DEFAULT NULL COMMENT 'Translatable',
  `brand_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `product_type` ENUM('cosmetic','device','accessory') NOT NULL DEFAULT 'cosmetic',
  `status` ENUM('draft','published','archived') NOT NULL DEFAULT 'draft',

  -- Prezzi
  `price` DECIMAL(12,2) NOT NULL,
  `compare_at_price` DECIMAL(12,2) NULL DEFAULT NULL,
  `cost` DECIMAL(12,2) NULL DEFAULT NULL,
  `currency` VARCHAR(3) NOT NULL DEFAULT 'EUR',
  `tax_rate` DECIMAL(5,2) NOT NULL DEFAULT 22.00,

  -- Fisico / spedizione
  `weight_grams` INT UNSIGNED NULL DEFAULT NULL,
  `dimensions_json` JSON NULL DEFAULT NULL COMMENT '{"length_cm":0,"width_cm":0,"height_cm":0}',
  `requires_shipping` TINYINT(1) NOT NULL DEFAULT 1,

  -- Noleggio (solo device)
  `is_rentable` TINYINT(1) NOT NULL DEFAULT 0,
  `rental_daily_price` DECIMAL(12,2) NULL DEFAULT NULL,
  `rental_monthly_price` DECIMAL(12,2) NULL DEFAULT NULL,

  -- Cosmetici
  `ingredients_text` TEXT NULL DEFAULT NULL,
  `inci_text` TEXT NULL DEFAULT NULL,
  `usage_instructions` TEXT NULL DEFAULT NULL,

  -- Device
  `technical_specs_json` JSON NULL DEFAULT NULL COMMENT '{"power":"...","frequency":"..."}',
  `certifications_json` JSON NULL DEFAULT NULL COMMENT '["CE","ISO 13485"]',
  `warranty_months` INT UNSIGNED NULL DEFAULT NULL,
  `video_demo_url` VARCHAR(500) NULL DEFAULT NULL,
  `manual_pdf_path` VARCHAR(500) NULL DEFAULT NULL,

  -- Badge e flags
  `badge_label` VARCHAR(50) NULL DEFAULT NULL,
  `badge_color` VARCHAR(20) NULL DEFAULT NULL,
  `is_featured` TINYINT(1) NOT NULL DEFAULT 0,
  `is_new` TINYINT(1) NOT NULL DEFAULT 0,
  `is_bestseller` TINYINT(1) NOT NULL DEFAULT 0,

  -- Contatori
  `view_count` INT UNSIGNED NOT NULL DEFAULT 0,
  `sales_count` INT UNSIGNED NOT NULL DEFAULT 0,

  `published_at` TIMESTAMP NULL DEFAULT NULL,
  `seo_meta_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  `deleted_at` TIMESTAMP NULL DEFAULT NULL,

  -- Colonne generate per fulltext search su JSON tradotto
  `name_search` VARCHAR(255) GENERATED ALWAYS AS (JSON_UNQUOTE(JSON_EXTRACT(`name`, '$.it'))) STORED,
  `description_search` TEXT GENERATED ALWAYS AS (JSON_UNQUOTE(JSON_EXTRACT(`description`, '$.it'))) STORED,

  PRIMARY KEY (`id`),
  UNIQUE KEY `products_sku_unique` (`sku`),
  UNIQUE KEY `products_slug_unique` (`slug`),
  KEY `products_brand_id_index` (`brand_id`),
  KEY `products_product_type_index` (`product_type`),
  KEY `products_status_published_at_index` (`status`, `published_at`),
  KEY `products_is_featured_index` (`is_featured`),
  KEY `products_is_new_index` (`is_new`),
  FULLTEXT KEY `products_sku_slug_fulltext` (`sku`, `slug`),
  FULLTEXT KEY `products_name_desc_fulltext` (`name_search`, `description_search`),
  CONSTRAINT `products_brand_id_foreign` FOREIGN KEY (`brand_id`)
    REFERENCES `brands` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Varianti prodotto (taglia, formato, ecc.)
CREATE TABLE IF NOT EXISTS `product_variants` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` BIGINT UNSIGNED NOT NULL,
  `sku` VARCHAR(64) NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `price_override` DECIMAL(12,2) NULL DEFAULT NULL,
  `weight_grams_override` INT UNSIGNED NULL DEFAULT NULL,
  `barcode` VARCHAR(100) NULL DEFAULT NULL,
  `image_path` VARCHAR(500) NULL DEFAULT NULL,
  `sort_order` INT NOT NULL DEFAULT 0,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_variants_sku_unique` (`sku`),
  KEY `product_variants_product_id_index` (`product_id`),
  CONSTRAINT `product_variants_product_id_foreign` FOREIGN KEY (`product_id`)
    REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Pivot categoria <-> prodotto
CREATE TABLE IF NOT EXISTS `category_product` (
  `category_id` BIGINT UNSIGNED NOT NULL,
  `product_id` BIGINT UNSIGNED NOT NULL,
  `sort_order` INT NOT NULL DEFAULT 0,
  `is_primary` TINYINT(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`category_id`, `product_id`),
  KEY `category_product_product_id_index` (`product_id`),
  CONSTRAINT `category_product_category_id_foreign` FOREIGN KEY (`category_id`)
    REFERENCES `categories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `category_product_product_id_foreign` FOREIGN KEY (`product_id`)
    REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Pivot prodotto <-> valore attributo
CREATE TABLE IF NOT EXISTS `product_attribute_values` (
  `product_id` BIGINT UNSIGNED NOT NULL,
  `attribute_value_id` BIGINT UNSIGNED NOT NULL,
  PRIMARY KEY (`product_id`, `attribute_value_id`),
  KEY `product_attribute_values_attribute_value_id_index` (`attribute_value_id`),
  CONSTRAINT `pav_product_id_foreign` FOREIGN KEY (`product_id`)
    REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pav_attribute_value_id_foreign` FOREIGN KEY (`attribute_value_id`)
    REFERENCES `attribute_values` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Pivot variante <-> valore attributo
CREATE TABLE IF NOT EXISTS `variant_attribute_values` (
  `variant_id` BIGINT UNSIGNED NOT NULL,
  `attribute_value_id` BIGINT UNSIGNED NOT NULL,
  PRIMARY KEY (`variant_id`, `attribute_value_id`),
  KEY `vav_attribute_value_id_index` (`attribute_value_id`),
  CONSTRAINT `vav_variant_id_foreign` FOREIGN KEY (`variant_id`)
    REFERENCES `product_variants` (`id`) ON DELETE CASCADE,
  CONSTRAINT `vav_attribute_value_id_foreign` FOREIGN KEY (`attribute_value_id`)
    REFERENCES `attribute_values` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tag prodotto (Novità, Promo, Best Seller, ecc.)
CREATE TABLE IF NOT EXISTS `product_tags` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` JSON NOT NULL COMMENT 'Translatable: {"it":"..."}',
  `slug` VARCHAR(255) NOT NULL,
  `color` VARCHAR(20) NULL DEFAULT NULL,
  `sort_order` INT NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_tags_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Pivot prodotto <-> tag
CREATE TABLE IF NOT EXISTS `product_product_tag` (
  `product_id` BIGINT UNSIGNED NOT NULL,
  `product_tag_id` BIGINT UNSIGNED NOT NULL,
  PRIMARY KEY (`product_id`, `product_tag_id`),
  KEY `ppt_product_tag_id_index` (`product_tag_id`),
  CONSTRAINT `ppt_product_id_foreign` FOREIGN KEY (`product_id`)
    REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `ppt_product_tag_id_foreign` FOREIGN KEY (`product_tag_id`)
    REFERENCES `product_tags` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ===================================================================
-- SEZIONE 5: MAGAZZINO / INVENTARIO
-- ===================================================================

-- Giacenza per variante prodotto
CREATE TABLE IF NOT EXISTS `inventory` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_variant_id` BIGINT UNSIGNED NOT NULL,
  `quantity` INT NOT NULL DEFAULT 0,
  `threshold_low` INT NOT NULL DEFAULT 5,
  `threshold_critical` INT NOT NULL DEFAULT 1,
  `allow_backorder` TINYINT(1) NOT NULL DEFAULT 0,
  `location` VARCHAR(100) NULL DEFAULT NULL,
  `last_movement_at` TIMESTAMP NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `inventory_product_variant_id_unique` (`product_variant_id`),
  CONSTRAINT `inventory_product_variant_id_foreign` FOREIGN KEY (`product_variant_id`)
    REFERENCES `product_variants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Movimenti di magazzino (audit trail)
CREATE TABLE IF NOT EXISTS `stock_movements` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `inventory_id` BIGINT UNSIGNED NOT NULL,
  `type` ENUM('in','out','adjustment','return','sale','restock') NOT NULL,
  `quantity` INT NOT NULL COMMENT 'Positivo o negativo',
  `reason` VARCHAR(255) NULL DEFAULT NULL,
  `reference_type` VARCHAR(100) NULL DEFAULT NULL COMMENT 'es. order, manual',
  `reference_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `performed_by_user_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `notes` TEXT NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `stock_movements_inventory_id_created_at_index` (`inventory_id`, `created_at`),
  KEY `stock_movements_reference_index` (`reference_type`, `reference_id`),
  CONSTRAINT `stock_movements_inventory_id_foreign` FOREIGN KEY (`inventory_id`)
    REFERENCES `inventory` (`id`) ON DELETE CASCADE,
  CONSTRAINT `stock_movements_performed_by_foreign` FOREIGN KEY (`performed_by_user_id`)
    REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ===================================================================
-- SEZIONE 6: CARRELLO
-- ===================================================================

-- Carrello (guest via session_token, autenticato via customer_id)
CREATE TABLE IF NOT EXISTS `carts` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `customer_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `session_token` VARCHAR(60) NULL DEFAULT NULL,
  `currency` VARCHAR(3) NOT NULL DEFAULT 'EUR',
  `subtotal` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `discount_total` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `tax_total` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `total` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `coupon_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `notes` TEXT NULL DEFAULT NULL,
  `expires_at` TIMESTAMP NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `carts_session_token_index` (`session_token`),
  KEY `carts_customer_id_index` (`customer_id`),
  KEY `carts_updated_at_index` (`updated_at`),
  CONSTRAINT `carts_customer_id_foreign` FOREIGN KEY (`customer_id`)
    REFERENCES `customers` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Righe carrello
CREATE TABLE IF NOT EXISTS `cart_items` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `cart_id` BIGINT UNSIGNED NOT NULL,
  `product_variant_id` BIGINT UNSIGNED NOT NULL,
  `quantity` INT UNSIGNED NOT NULL DEFAULT 1,
  `unit_price_snapshot` DECIMAL(12,2) NOT NULL,
  `subtotal_snapshot` DECIMAL(12,2) NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cart_items_cart_id_index` (`cart_id`),
  KEY `cart_items_product_variant_id_index` (`product_variant_id`),
  CONSTRAINT `cart_items_cart_id_foreign` FOREIGN KEY (`cart_id`)
    REFERENCES `carts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cart_items_product_variant_id_foreign` FOREIGN KEY (`product_variant_id`)
    REFERENCES `product_variants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ===================================================================
-- SEZIONE 7: COUPON E PROMOZIONI
-- ===================================================================

-- Coupon sconto
CREATE TABLE IF NOT EXISTS `coupons` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` VARCHAR(64) NOT NULL,
  `type` ENUM('percentage','fixed') NOT NULL,
  `value` DECIMAL(12,2) NOT NULL,
  `min_order_amount` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `max_discount` DECIMAL(12,2) NULL DEFAULT NULL,
  `applies_to` ENUM('all','category','brand','product','customer','first_order') NOT NULL DEFAULT 'all',
  `applicable_ids` JSON NULL DEFAULT NULL COMMENT 'Array di ID target filtro',
  `usage_limit_global` INT UNSIGNED NULL DEFAULT NULL,
  `usage_limit_per_customer` INT UNSIGNED NOT NULL DEFAULT 1,
  `usage_count` INT UNSIGNED NOT NULL DEFAULT 0,
  `customer_id` BIGINT UNSIGNED NULL DEFAULT NULL COMMENT 'Per coupon dedicati a singolo cliente',
  `starts_at` TIMESTAMP NULL DEFAULT NULL,
  `expires_at` TIMESTAMP NULL DEFAULT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `description` TEXT NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `coupons_code_unique` (`code`),
  KEY `coupons_active_dates_index` (`is_active`, `starts_at`, `expires_at`),
  CONSTRAINT `coupons_customer_id_foreign` FOREIGN KEY (`customer_id`)
    REFERENCES `customers` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Utilizzi coupon (audit)
CREATE TABLE IF NOT EXISTS `coupon_redemptions` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `coupon_id` BIGINT UNSIGNED NOT NULL,
  `customer_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `order_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `discount_amount` DECIMAL(12,2) NOT NULL,
  `redeemed_at` TIMESTAMP NOT NULL,
  PRIMARY KEY (`id`),
  KEY `coupon_redemptions_coupon_customer_index` (`coupon_id`, `customer_id`),
  CONSTRAINT `coupon_redemptions_coupon_id_foreign` FOREIGN KEY (`coupon_id`)
    REFERENCES `coupons` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Promozioni automatiche / manuali
CREATE TABLE IF NOT EXISTS `promotions` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `description` TEXT NULL DEFAULT NULL,
  `type` ENUM('automatic','manual') NOT NULL DEFAULT 'manual',
  `rules_json` JSON NULL DEFAULT NULL COMMENT '{"min_qty":2,"category_id":5}',
  `discount_type` ENUM('percentage','fixed','free_shipping') NOT NULL,
  `discount_value` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `priority` INT NOT NULL DEFAULT 0,
  `customer_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `starts_at` TIMESTAMP NULL DEFAULT NULL,
  `expires_at` TIMESTAMP NULL DEFAULT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `promotions_active_dates_index` (`is_active`, `starts_at`, `expires_at`),
  CONSTRAINT `promotions_customer_id_foreign` FOREIGN KEY (`customer_id`)
    REFERENCES `customers` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ===================================================================
-- SEZIONE 8: ORDINI, PAGAMENTI, SPEDIZIONI
-- ===================================================================

-- Ordini
CREATE TABLE IF NOT EXISTS `orders` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_number` VARCHAR(20) NOT NULL,
  `customer_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `customer_email` VARCHAR(255) NOT NULL,
  `customer_name` VARCHAR(255) NOT NULL,
  `shipping_address_json` JSON NOT NULL,
  `billing_address_json` JSON NOT NULL,
  `currency` VARCHAR(3) NOT NULL DEFAULT 'EUR',
  `subtotal` DECIMAL(12,2) NOT NULL,
  `discount_total` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `tax_total` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `shipping_total` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `total` DECIMAL(12,2) NOT NULL,
  `status` ENUM('pending','confirmed','processing','shipped','delivered','cancelled','refunded','partially_refunded') NOT NULL DEFAULT 'pending',
  `payment_status` ENUM('pending','authorized','captured','failed','refunded','partially_refunded') NOT NULL DEFAULT 'pending',
  `payment_provider` VARCHAR(50) NULL DEFAULT NULL,
  `payment_method` VARCHAR(50) NULL DEFAULT NULL,
  `payment_external_id` VARCHAR(255) NULL DEFAULT NULL,
  `payment_metadata` JSON NULL DEFAULT NULL,
  `shipping_provider` VARCHAR(50) NULL DEFAULT NULL,
  `shipping_method` VARCHAR(100) NULL DEFAULT NULL,
  `coupon_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `notes_customer` TEXT NULL DEFAULT NULL,
  `notes_admin` TEXT NULL DEFAULT NULL,
  `ip_address` VARCHAR(45) NULL DEFAULT NULL,
  `user_agent` VARCHAR(500) NULL DEFAULT NULL,
  `paid_at` TIMESTAMP NULL DEFAULT NULL,
  `shipped_at` TIMESTAMP NULL DEFAULT NULL,
  `delivered_at` TIMESTAMP NULL DEFAULT NULL,
  `cancelled_at` TIMESTAMP NULL DEFAULT NULL,

  -- Fatturazione elettronica
  `invoice_number` VARCHAR(50) NULL DEFAULT NULL,
  `invoice_external_id` VARCHAR(255) NULL DEFAULT NULL,
  `invoice_provider` VARCHAR(50) NULL DEFAULT NULL,
  `invoice_pdf_path` VARCHAR(500) NULL DEFAULT NULL,
  `invoice_xml_path` VARCHAR(500) NULL DEFAULT NULL,
  `invoice_status` ENUM('none','pending','sent','accepted','rejected') NOT NULL DEFAULT 'none',

  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  `deleted_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `orders_order_number_unique` (`order_number`),
  KEY `orders_status_created_at_index` (`status`, `created_at`),
  KEY `orders_customer_id_status_index` (`customer_id`, `status`),
  KEY `orders_payment_status_index` (`payment_status`),
  KEY `orders_invoice_status_index` (`invoice_status`),
  CONSTRAINT `orders_customer_id_foreign` FOREIGN KEY (`customer_id`)
    REFERENCES `customers` (`id`) ON DELETE SET NULL,
  CONSTRAINT `orders_coupon_id_foreign` FOREIGN KEY (`coupon_id`)
    REFERENCES `coupons` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Righe ordine (snapshot completo del prodotto al momento dell'acquisto)
CREATE TABLE IF NOT EXISTS `order_items` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` BIGINT UNSIGNED NOT NULL,
  `product_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `product_variant_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `sku` VARCHAR(64) NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `variant_name` VARCHAR(255) NULL DEFAULT NULL,
  `quantity` INT UNSIGNED NOT NULL,
  `unit_price` DECIMAL(12,2) NOT NULL,
  `tax_rate` DECIMAL(5,2) NOT NULL,
  `tax_amount` DECIMAL(12,2) NOT NULL,
  `discount_amount` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `subtotal` DECIMAL(12,2) NOT NULL,
  `total` DECIMAL(12,2) NOT NULL,
  `product_snapshot_json` JSON NULL DEFAULT NULL COMMENT 'Snapshot completo del prodotto al momento ordine',
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_items_order_id_index` (`order_id`),
  KEY `order_items_product_id_index` (`product_id`),
  CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`)
    REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`)
    REFERENCES `products` (`id`) ON DELETE SET NULL,
  CONSTRAINT `order_items_variant_id_foreign` FOREIGN KEY (`product_variant_id`)
    REFERENCES `product_variants` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Storico transizioni stato ordine
CREATE TABLE IF NOT EXISTS `order_status_history` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` BIGINT UNSIGNED NOT NULL,
  `from_status` VARCHAR(50) NULL DEFAULT NULL,
  `to_status` VARCHAR(50) NOT NULL,
  `reason` TEXT NULL DEFAULT NULL,
  `performed_by_user_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_status_history_order_id_index` (`order_id`),
  CONSTRAINT `osh_order_id_foreign` FOREIGN KEY (`order_id`)
    REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `osh_performed_by_foreign` FOREIGN KEY (`performed_by_user_id`)
    REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Transazioni pagamento (multi-pagamento per ordine)
CREATE TABLE IF NOT EXISTS `payments` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` BIGINT UNSIGNED NOT NULL,
  `provider` VARCHAR(50) NOT NULL,
  `method` VARCHAR(50) NULL DEFAULT NULL,
  `external_id` VARCHAR(255) NULL DEFAULT NULL,
  `status` VARCHAR(50) NOT NULL DEFAULT 'pending',
  `amount` DECIMAL(12,2) NOT NULL,
  `currency` VARCHAR(3) NOT NULL DEFAULT 'EUR',
  `metadata_json` JSON NULL DEFAULT NULL,
  `webhook_payload_json` JSON NULL DEFAULT NULL,
  `paid_at` TIMESTAMP NULL DEFAULT NULL,
  `failed_at` TIMESTAMP NULL DEFAULT NULL,
  `error_message` TEXT NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `payments_order_id_index` (`order_id`),
  KEY `payments_provider_external_id_index` (`provider`, `external_id`),
  CONSTRAINT `payments_order_id_foreign` FOREIGN KEY (`order_id`)
    REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Rimborsi
CREATE TABLE IF NOT EXISTS `refunds` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` BIGINT UNSIGNED NOT NULL,
  `payment_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `amount` DECIMAL(12,2) NOT NULL,
  `reason` TEXT NOT NULL,
  `external_id` VARCHAR(255) NULL DEFAULT NULL,
  `status` VARCHAR(50) NOT NULL DEFAULT 'pending',
  `processed_by_user_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `processed_at` TIMESTAMP NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `refunds_order_id_index` (`order_id`),
  KEY `refunds_payment_id_index` (`payment_id`),
  CONSTRAINT `refunds_order_id_foreign` FOREIGN KEY (`order_id`)
    REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `refunds_payment_id_foreign` FOREIGN KEY (`payment_id`)
    REFERENCES `payments` (`id`) ON DELETE SET NULL,
  CONSTRAINT `refunds_processed_by_foreign` FOREIGN KEY (`processed_by_user_id`)
    REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Metodi di pagamento configurati
CREATE TABLE IF NOT EXISTS `payment_methods` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` VARCHAR(50) NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `provider` VARCHAR(50) NOT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 0,
  `sort_order` INT NOT NULL DEFAULT 0,
  `config_json` JSON NULL DEFAULT NULL,
  `instructions` TEXT NULL DEFAULT NULL,
  `icon_path` VARCHAR(500) NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `payment_methods_code_unique` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Spedizioni
CREATE TABLE IF NOT EXISTS `shipments` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` BIGINT UNSIGNED NOT NULL,
  `carrier` VARCHAR(100) NOT NULL,
  `tracking_number` VARCHAR(100) NULL DEFAULT NULL,
  `tracking_url` VARCHAR(500) NULL DEFAULT NULL,
  `status` VARCHAR(50) NOT NULL DEFAULT 'pending',
  `shipped_at` TIMESTAMP NULL DEFAULT NULL,
  `delivered_at` TIMESTAMP NULL DEFAULT NULL,
  `weight_grams` INT UNSIGNED NULL DEFAULT NULL,
  `label_pdf_path` VARCHAR(500) NULL DEFAULT NULL,
  `notes` TEXT NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `shipments_order_id_index` (`order_id`),
  KEY `shipments_tracking_number_index` (`tracking_number`),
  CONSTRAINT `shipments_order_id_foreign` FOREIGN KEY (`order_id`)
    REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ===================================================================
-- SEZIONE 9: CMS (PAGINE, BLOCCHI, MENU, MEDIA, SEO)
-- ===================================================================

-- Pagine CMS
CREATE TABLE IF NOT EXISTS `pages` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `slug` VARCHAR(255) NOT NULL,
  `title` JSON NOT NULL COMMENT 'Translatable: {"it":"..."}',
  `template` VARCHAR(50) NOT NULL DEFAULT 'default',
  `is_published` TINYINT(1) NOT NULL DEFAULT 0,
  `published_at` TIMESTAMP NULL DEFAULT NULL,
  `seo_meta_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `created_by_user_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `updated_by_user_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  `deleted_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pages_slug_unique` (`slug`),
  CONSTRAINT `pages_created_by_foreign` FOREIGN KEY (`created_by_user_id`)
    REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `pages_updated_by_foreign` FOREIGN KEY (`updated_by_user_id`)
    REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Blocchi CMS (componibili per pagina o posizione globale)
CREATE TABLE IF NOT EXISTS `blocks` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `page_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `location` VARCHAR(100) NULL DEFAULT NULL COMMENT 'Es. home_hero, footer_promo',
  `type` VARCHAR(50) NOT NULL COMMENT 'hero, text, features, product_grid, cta, contact_form...',
  `content_json` JSON NOT NULL,
  `settings_json` JSON NULL DEFAULT NULL,
  `sort_order` INT NOT NULL DEFAULT 0,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `starts_at` TIMESTAMP NULL DEFAULT NULL,
  `expires_at` TIMESTAMP NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `blocks_location_active_sort_index` (`location`, `is_active`, `sort_order`),
  KEY `blocks_page_id_sort_index` (`page_id`, `sort_order`),
  CONSTRAINT `blocks_page_id_foreign` FOREIGN KEY (`page_id`)
    REFERENCES `pages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Menu navigazione
CREATE TABLE IF NOT EXISTS `menus` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` VARCHAR(50) NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `location` VARCHAR(50) NOT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `menus_code_unique` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Voci di menu (albero con parent_id)
CREATE TABLE IF NOT EXISTS `menu_items` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `menu_id` BIGINT UNSIGNED NOT NULL,
  `parent_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `label` JSON NOT NULL COMMENT 'Translatable: {"it":"..."}',
  `type` ENUM('page','category','brand','product','blog','custom') NOT NULL DEFAULT 'custom',
  `target_type` VARCHAR(100) NULL DEFAULT NULL,
  `target_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `url` VARCHAR(500) NULL DEFAULT NULL,
  `icon` VARCHAR(50) NULL DEFAULT NULL,
  `badge_label` VARCHAR(20) NULL DEFAULT NULL,
  `opens_in_new_tab` TINYINT(1) NOT NULL DEFAULT 0,
  `sort_order` INT NOT NULL DEFAULT 0,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `menu_items_menu_parent_sort_index` (`menu_id`, `parent_id`, `sort_order`),
  CONSTRAINT `menu_items_menu_id_foreign` FOREIGN KEY (`menu_id`)
    REFERENCES `menus` (`id`) ON DELETE CASCADE,
  CONSTRAINT `menu_items_parent_id_foreign` FOREIGN KEY (`parent_id`)
    REFERENCES `menu_items` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Media (Spatie Media Library)
CREATE TABLE IF NOT EXISTS `media` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `model_type` VARCHAR(255) NOT NULL,
  `model_id` BIGINT UNSIGNED NOT NULL,
  `uuid` CHAR(36) NULL DEFAULT NULL,
  `collection_name` VARCHAR(255) NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `file_name` VARCHAR(255) NOT NULL,
  `mime_type` VARCHAR(255) NULL DEFAULT NULL,
  `disk` VARCHAR(255) NOT NULL,
  `conversions_disk` VARCHAR(255) NULL DEFAULT NULL,
  `size` BIGINT UNSIGNED NOT NULL,
  `manipulations` JSON NOT NULL,
  `custom_properties` JSON NOT NULL,
  `generated_conversions` JSON NOT NULL,
  `responsive_images` JSON NOT NULL,
  `order_column` INT UNSIGNED NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `media_uuid_unique` (`uuid`),
  KEY `media_model_type_model_id_index` (`model_type`, `model_id`),
  KEY `media_order_column_index` (`order_column`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEO meta (polimorfica: qualsiasi entità può avere meta SEO)
CREATE TABLE IF NOT EXISTS `seo_meta` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `seoable_type` VARCHAR(255) NULL DEFAULT NULL,
  `seoable_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `meta_title` JSON NULL DEFAULT NULL COMMENT 'Translatable',
  `meta_description` JSON NULL DEFAULT NULL COMMENT 'Translatable',
  `og_title` JSON NULL DEFAULT NULL COMMENT 'Translatable',
  `og_description` JSON NULL DEFAULT NULL COMMENT 'Translatable',
  `og_image_path` VARCHAR(500) NULL DEFAULT NULL,
  `canonical_url` VARCHAR(500) NULL DEFAULT NULL,
  `robots` VARCHAR(100) NOT NULL DEFAULT 'index,follow',
  `schema_markup_json` JSON NULL DEFAULT NULL,
  `twitter_card` VARCHAR(50) NOT NULL DEFAULT 'summary_large_image',
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `seo_meta_seoable_index` (`seoable_type`, `seoable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Redirect URL (301/302)
CREATE TABLE IF NOT EXISTS `redirects` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `source_url` VARCHAR(500) NOT NULL,
  `destination_url` VARCHAR(500) NOT NULL,
  `status_code` SMALLINT NOT NULL DEFAULT 301,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `hits` INT UNSIGNED NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `redirects_source_url_unique` (`source_url`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Template email (gestibili da backoffice)
CREATE TABLE IF NOT EXISTS `email_templates` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` VARCHAR(50) NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `subject_template` TEXT NOT NULL,
  `body_html_template` LONGTEXT NOT NULL,
  `body_text_template` LONGTEXT NULL DEFAULT NULL,
  `available_variables_json` JSON NULL DEFAULT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_templates_code_unique` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ===================================================================
-- SEZIONE 10: BLOG
-- ===================================================================

-- Categorie blog
CREATE TABLE IF NOT EXISTS `blog_categories` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` JSON NOT NULL COMMENT 'Translatable: {"it":"..."}',
  `slug` VARCHAR(255) NOT NULL,
  `description` JSON NULL DEFAULT NULL COMMENT 'Translatable',
  `sort_order` INT NOT NULL DEFAULT 0,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `blog_categories_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tag blog
CREATE TABLE IF NOT EXISTS `blog_tags` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` JSON NOT NULL COMMENT 'Translatable: {"it":"..."}',
  `slug` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `blog_tags_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Articoli blog
CREATE TABLE IF NOT EXISTS `blog_posts` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `blog_category_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `title` JSON NOT NULL COMMENT 'Translatable: {"it":"..."}',
  `slug` VARCHAR(255) NOT NULL,
  `excerpt` JSON NULL DEFAULT NULL COMMENT 'Translatable',
  `body_html` JSON NOT NULL COMMENT 'Translatable',
  `featured_image_path` VARCHAR(500) NULL DEFAULT NULL,
  `author_user_id` BIGINT UNSIGNED NOT NULL,
  `is_published` TINYINT(1) NOT NULL DEFAULT 0,
  `published_at` TIMESTAMP NULL DEFAULT NULL,
  `view_count` INT UNSIGNED NOT NULL DEFAULT 0,
  `reading_time_minutes` INT UNSIGNED NULL DEFAULT NULL,
  `seo_meta_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  `deleted_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `blog_posts_slug_unique` (`slug`),
  KEY `blog_posts_published_index` (`is_published`, `published_at`),
  KEY `blog_posts_category_id_index` (`blog_category_id`),
  CONSTRAINT `blog_posts_category_id_foreign` FOREIGN KEY (`blog_category_id`)
    REFERENCES `blog_categories` (`id`) ON DELETE SET NULL,
  CONSTRAINT `blog_posts_author_foreign` FOREIGN KEY (`author_user_id`)
    REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Pivot blog_post <-> blog_tag
CREATE TABLE IF NOT EXISTS `blog_post_blog_tag` (
  `blog_post_id` BIGINT UNSIGNED NOT NULL,
  `blog_tag_id` BIGINT UNSIGNED NOT NULL,
  PRIMARY KEY (`blog_post_id`, `blog_tag_id`),
  KEY `bpbt_blog_tag_id_index` (`blog_tag_id`),
  CONSTRAINT `bpbt_blog_post_id_foreign` FOREIGN KEY (`blog_post_id`)
    REFERENCES `blog_posts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bpbt_blog_tag_id_foreign` FOREIGN KEY (`blog_tag_id`)
    REFERENCES `blog_tags` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ===================================================================
-- SEZIONE 11: NEWSLETTER
-- ===================================================================

-- Iscritti newsletter (con double opt-in e sync provider esterno)
CREATE TABLE IF NOT EXISTS `newsletter_subscribers` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(255) NOT NULL,
  `name` VARCHAR(255) NULL DEFAULT NULL,
  `status` ENUM('pending','subscribed','unsubscribed','bounced','complained') NOT NULL DEFAULT 'pending',
  `source` VARCHAR(50) NULL DEFAULT NULL COMMENT 'footer, popup, checkout, import',
  `locale` VARCHAR(5) NOT NULL DEFAULT 'it',
  `external_provider` VARCHAR(50) NULL DEFAULT NULL,
  `external_subscriber_id` VARCHAR(255) NULL DEFAULT NULL,
  `synced_at` TIMESTAMP NULL DEFAULT NULL,
  `subscribed_at` TIMESTAMP NULL DEFAULT NULL,
  `unsubscribed_at` TIMESTAMP NULL DEFAULT NULL,
  `double_opt_in_token` VARCHAR(60) NULL DEFAULT NULL,
  `double_opt_in_expires_at` TIMESTAMP NULL DEFAULT NULL,
  `ip_address` VARCHAR(45) NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `newsletter_subscribers_email_unique` (`email`),
  KEY `newsletter_subscribers_status_index` (`status`),
  KEY `newsletter_subscribers_external_index` (`external_provider`, `external_subscriber_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ===================================================================
-- SEZIONE 12: SETTINGS & AUDIT LOG
-- ===================================================================

-- Impostazioni globali (Spatie Settings format)
CREATE TABLE IF NOT EXISTS `settings` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `group` VARCHAR(255) NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `payload` LONGTEXT NULL DEFAULT NULL,
  `locked` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_group_name_unique` (`group`, `name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Activity log (Spatie Activitylog)
CREATE TABLE IF NOT EXISTS `activity_log` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `log_name` VARCHAR(255) NULL DEFAULT NULL,
  `description` TEXT NOT NULL,
  `subject_type` VARCHAR(255) NULL DEFAULT NULL,
  `subject_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `causer_type` VARCHAR(255) NULL DEFAULT NULL,
  `causer_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `properties` JSON NULL DEFAULT NULL,
  `batch_uuid` CHAR(36) NULL DEFAULT NULL,
  `event` VARCHAR(255) NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `activity_log_log_name_index` (`log_name`),
  KEY `activity_log_subject_index` (`subject_type`, `subject_id`),
  KEY `activity_log_causer_index` (`causer_type`, `causer_id`),
  KEY `activity_log_batch_uuid_index` (`batch_uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ===================================================================
-- SEZIONE 13: SEED DATA
-- ===================================================================

-- ===== 13.1: PERMESSI SPATIE =====
INSERT IGNORE INTO `permissions` (`name`, `guard_name`, `created_at`, `updated_at`) VALUES
  ('view_dashboard',      'web', NOW(), NOW()),
  ('view_analytics',      'web', NOW(), NOW()),
  ('manage_products',     'web', NOW(), NOW()),
  ('manage_orders',       'web', NOW(), NOW()),
  ('manage_customers',    'web', NOW(), NOW()),
  ('manage_content',      'web', NOW(), NOW()),
  ('manage_settings',     'web', NOW(), NOW()),
  ('manage_integrations', 'web', NOW(), NOW()),
  ('manage_users',        'web', NOW(), NOW()),
  ('manage_promotions',   'web', NOW(), NOW()),
  ('manage_inventory',    'web', NOW(), NOW()),
  ('manage_blog',         'web', NOW(), NOW()),
  ('manage_media',        'web', NOW(), NOW()),
  ('view_audit_logs',     'web', NOW(), NOW());

-- ===== 13.2: RUOLI =====
INSERT IGNORE INTO `roles` (`name`, `guard_name`, `created_at`, `updated_at`) VALUES
  ('super_admin', 'web', NOW(), NOW()),
  ('admin',       'web', NOW(), NOW()),
  ('manager',     'web', NOW(), NOW()),
  ('editor',      'web', NOW(), NOW());

-- ===== 13.3: ASSOCIAZIONE RUOLO -> PERMESSI =====
-- super_admin: tutti i permessi
INSERT IGNORE INTO `role_has_permissions` (`permission_id`, `role_id`)
SELECT p.id, r.id
FROM `permissions` p, `roles` r
WHERE r.name = 'super_admin';

-- admin: tutto tranne manage_users e manage_integrations
INSERT IGNORE INTO `role_has_permissions` (`permission_id`, `role_id`)
SELECT p.id, r.id
FROM `permissions` p, `roles` r
WHERE r.name = 'admin'
  AND p.name NOT IN ('manage_users', 'manage_integrations');

-- manager: dashboard, analytics, ordini, clienti, inventario, promozioni
INSERT IGNORE INTO `role_has_permissions` (`permission_id`, `role_id`)
SELECT p.id, r.id
FROM `permissions` p, `roles` r
WHERE r.name = 'manager'
  AND p.name IN ('view_dashboard', 'view_analytics', 'manage_orders', 'manage_customers', 'manage_inventory', 'manage_promotions');

-- editor: contenuti, blog, media
INSERT IGNORE INTO `role_has_permissions` (`permission_id`, `role_id`)
SELECT p.id, r.id
FROM `permissions` p, `roles` r
WHERE r.name = 'editor'
  AND p.name IN ('view_dashboard', 'manage_content', 'manage_blog', 'manage_media');

-- ===== 13.4: UTENTE SUPER ADMIN =====
-- IMPORTANTE: cambiare password al primo login da Filament profile.
-- Hash bcrypt di 'changeme123!' generato con cost 12
INSERT IGNORE INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `locale`, `is_active`, `created_at`, `updated_at`) VALUES
  (1, 'Super Admin', 'jrovera05@gmail.com', NOW(), '$2y$12$LJ3m4yS6kRe0TbJiYGJ9xOzYQg5GKxFGlxv0F0fN8qBu.DGMW.6eS', 'it', 1, NOW(), NOW());

-- Assegnazione ruolo super_admin all'utente 1
INSERT IGNORE INTO `model_has_roles` (`role_id`, `model_type`, `model_id`)
SELECT r.id, 'App\\Models\\User', 1
FROM `roles` r WHERE r.name = 'super_admin';

-- ===== 13.5: SETTINGS — GENERAL =====
INSERT IGNORE INTO `settings` (`group`, `name`, `payload`, `created_at`, `updated_at`) VALUES
  ('general', 'site_name',                '"SkinTemple"',                                         NOW(), NOW()),
  ('general', 'site_tagline',             '"Tecnologie Made in Italy per Centri Estetici"',       NOW(), NOW()),
  ('general', 'contact_email',            '"info@skintemple.it"',                                 NOW(), NOW()),
  ('general', 'contact_phone',            '""',                                                   NOW(), NOW()),
  ('general', 'company_name',             '"SkinTemple"',                                         NOW(), NOW()),
  ('general', 'company_vat',              '"11863510019"',                                        NOW(), NOW()),
  ('general', 'company_address_street',   '"Strada Santa Vittoria 11"',                           NOW(), NOW()),
  ('general', 'company_address_city',     '"Moncalieri"',                                         NOW(), NOW()),
  ('general', 'company_address_postal',   '"10024"',                                              NOW(), NOW()),
  ('general', 'company_address_province', '"TO"',                                                 NOW(), NOW()),
  ('general', 'company_address_country',  '"IT"',                                                 NOW(), NOW()),
  ('general', 'currency',                 '"EUR"',                                                NOW(), NOW()),
  ('general', 'default_locale',           '"it"',                                                 NOW(), NOW()),
  ('general', 'timezone',                 '"Europe/Rome"',                                        NOW(), NOW());

-- ===== 13.6: SETTINGS — NOTIFICATIONS =====
INSERT IGNORE INTO `settings` (`group`, `name`, `payload`, `created_at`, `updated_at`) VALUES
  ('notifications', 'admin_emails',          '["jrovera05@gmail.com","backsoftware.crm@gmail.com"]', NOW(), NOW()),
  ('notifications', 'notify_new_order',      'true',                                                  NOW(), NOW()),
  ('notifications', 'notify_low_stock',      'true',                                                  NOW(), NOW()),
  ('notifications', 'notify_failed_payment', 'true',                                                  NOW(), NOW()),
  ('notifications', 'low_stock_threshold',   '5',                                                     NOW(), NOW());

-- ===== 13.7: SETTINGS — MAIL =====
INSERT IGNORE INTO `settings` (`group`, `name`, `payload`, `created_at`, `updated_at`) VALUES
  ('mail', 'from_address', '"noreply@skintemple.it"', NOW(), NOW()),
  ('mail', 'from_name',    '"SkinTemple"',            NOW(), NOW()),
  ('mail', 'reply_to',     '"info@skintemple.it"',    NOW(), NOW());

-- ===== 13.8: SETTINGS — INTEGRATIONS =====
INSERT IGNORE INTO `settings` (`group`, `name`, `payload`, `created_at`, `updated_at`) VALUES
  ('integrations', 'payment_provider',    '"manual"',   NOW(), NOW()),
  ('integrations', 'newsletter_provider', '"database"', NOW(), NOW()),
  ('integrations', 'invoice_provider',    '"none"',     NOW(), NOW()),
  ('integrations', 'shipping_provider',   '"manual"',   NOW(), NOW()),
  ('integrations', 'stripe_enabled',      'false',      NOW(), NOW()),
  ('integrations', 'paypal_enabled',      'false',      NOW(), NOW()),
  ('integrations', 'mailchimp_enabled',   'false',      NOW(), NOW()),
  ('integrations', 'aruba_enabled',       'false',      NOW(), NOW()),
  ('integrations', 'fic_enabled',         'false',      NOW(), NOW());

-- ===== 13.9: SETTINGS — COMMERCE =====
INSERT IGNORE INTO `settings` (`group`, `name`, `payload`, `created_at`, `updated_at`) VALUES
  ('commerce', 'tax_rate_default',         '22.00',                NOW(), NOW()),
  ('commerce', 'prices_include_tax',       'true',                 NOW(), NOW()),
  ('commerce', 'allow_guest_checkout',     'true',                 NOW(), NOW()),
  ('commerce', 'min_order_amount',         '0',                    NOW(), NOW()),
  ('commerce', 'free_shipping_threshold',  '99.00',                NOW(), NOW()),
  ('commerce', 'default_shipping_cost',    '7.90',                 NOW(), NOW()),
  ('commerce', 'order_number_prefix',      '"SK-"',                NOW(), NOW()),
  ('commerce', 'invoice_number_prefix',    '"FT-"',                NOW(), NOW()),
  ('commerce', 'invoice_number_year_reset','true',                 NOW(), NOW());

-- ===== 13.10: SETTINGS — SEO =====
INSERT IGNORE INTO `settings` (`group`, `name`, `payload`, `created_at`, `updated_at`) VALUES
  ('seo', 'default_meta_title',       '"SkinTemple — Tecnologie Made in Italy"',                                                                                                  NOW(), NOW()),
  ('seo', 'default_meta_description', '"Soluzioni 100% Made in Italy per centri estetici e studi medici. Tecnologie multifunzione, assistenza dedicata, qualità italiana."',       NOW(), NOW()),
  ('seo', 'default_og_image_path',    '""',              NOW(), NOW()),
  ('seo', 'google_site_verification', '""',              NOW(), NOW()),
  ('seo', 'robots',                   '"index,follow"',  NOW(), NOW()),
  ('seo', 'social_facebook',          '""',              NOW(), NOW()),
  ('seo', 'social_instagram',         '""',              NOW(), NOW()),
  ('seo', 'social_youtube',           '""',              NOW(), NOW()),
  ('seo', 'social_linkedin',          '""',              NOW(), NOW());

-- ===== 13.11: SETTINGS — FEATURES =====
INSERT IGNORE INTO `settings` (`group`, `name`, `payload`, `created_at`, `updated_at`) VALUES
  ('features', 'blog_enabled',            'true',   NOW(), NOW()),
  ('features', 'wishlist_enabled',        'true',   NOW(), NOW()),
  ('features', 'reviews_enabled',         'false',  NOW(), NOW()),
  ('features', 'b2b_pricing_enabled',     'false',  NOW(), NOW()),
  ('features', 'multilingual_enabled',    'false',  NOW(), NOW()),
  ('features', 'dark_mode_enabled',       'false',  NOW(), NOW()),
  ('features', 'einvoice_badge_visible',  'true',   NOW(), NOW()),
  ('features', 'einvoice_badge_label',    '"Fatturazione elettronica disponibile su richiesta"', NOW(), NOW());

-- ===== 13.12: CATEGORIE (MACROAREE + MICROAREE) =====

-- Macroaree
INSERT IGNORE INTO `categories` (`id`, `parent_id`, `name`, `slug`, `description`, `type`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
  (1, NULL, '{"it":"Tecnologie"}',  'tecnologie', '{"it":"Tecnologie multifunzione Made in Italy per centri estetici e studi medici"}', 'macroarea', 1, 1, NOW(), NOW()),
  (2, NULL, '{"it":"Cosmetici"}',   'cosmetici',  '{"it":"Cosmetici skincare Made in Italy per la cura della pelle"}',                  'macroarea', 1, 2, NOW(), NOW()),
  (3, NULL, '{"it":"Accessori"}',   'accessori',  '{"it":"Accessori professionali per trattamenti estetici"}',                          'macroarea', 1, 3, NOW(), NOW());

-- Microaree Tecnologie
INSERT IGNORE INTO `categories` (`id`, `parent_id`, `name`, `slug`, `description`, `type`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
  (4, 1, '{"it":"Viso"}',          'tecnologie-viso',          NULL, 'microarea', 1, 1, NOW(), NOW()),
  (5, 1, '{"it":"Corpo"}',         'tecnologie-corpo',         NULL, 'microarea', 1, 2, NOW(), NOW()),
  (6, 1, '{"it":"Multifunzione"}', 'tecnologie-multifunzione', NULL, 'microarea', 1, 3, NOW(), NOW());

-- Microaree Cosmetici
INSERT IGNORE INTO `categories` (`id`, `parent_id`, `name`, `slug`, `description`, `type`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
  (7,  2, '{"it":"Detergenti"}',  'cosmetici-detergenti',  NULL, 'microarea', 1, 1, NOW(), NOW()),
  (8,  2, '{"it":"Sieri"}',       'cosmetici-sieri',       NULL, 'microarea', 1, 2, NOW(), NOW()),
  (9,  2, '{"it":"Creme viso"}',  'cosmetici-creme-viso',  NULL, 'microarea', 1, 3, NOW(), NOW()),
  (10, 2, '{"it":"Creme corpo"}', 'cosmetici-creme-corpo', NULL, 'microarea', 1, 4, NOW(), NOW()),
  (11, 2, '{"it":"Maschere"}',    'cosmetici-maschere',    NULL, 'microarea', 1, 5, NOW(), NOW());

-- ===== 13.13: BRAND =====
INSERT IGNORE INTO `brands` (`id`, `name`, `slug`, `description`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
  (1, '{"it":"SkinTemple"}', 'skintemple', '{"it":"Tecnologie e cosmetici Made in Italy per professionisti del settore estetico"}', 1, 1, NOW(), NOW());

-- ===== 13.14: PAGINE CMS CON CONTENUTO REALE =====

-- Home
INSERT IGNORE INTO `pages` (`id`, `slug`, `title`, `template`, `is_published`, `published_at`, `created_by_user_id`, `created_at`, `updated_at`) VALUES
  (1, 'home',               '{"it":"Home"}',                  'home',    1, NOW(), 1, NOW(), NOW()),
  (2, 'chi-siamo',          '{"it":"Chi Siamo"}',             'about',   1, NOW(), 1, NOW(), NOW()),
  (3, 'supporto',           '{"it":"Supporto"}',              'default', 1, NOW(), 1, NOW(), NOW()),
  (4, 'contatti',           '{"it":"Contatti"}',              'contact', 1, NOW(), 1, NOW(), NOW()),
  (5, 'privacy',            '{"it":"Privacy Policy"}',        'legal',   0, NULL,  1, NOW(), NOW()),
  (6, 'cookie',             '{"it":"Cookie Policy"}',         'legal',   0, NULL,  1, NOW(), NOW()),
  (7, 'termini-di-servizio','{"it":"Termini di Servizio"}',   'legal',   0, NULL,  1, NOW(), NOW());

-- ===== 13.15: BLOCCHI CMS HOMEPAGE (contenuto reale) =====

-- Hero homepage
INSERT INTO `blocks` (`page_id`, `location`, `type`, `content_json`, `settings_json`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'home_hero', 'hero', JSON_OBJECT(
  'title', 'TECNOLOGIE MULTIFUNZIONE AL SERVIZIO DEL CENTRO ESTETICO',
  'subtitle', 'Skintemple è la soluzione Made In Italy che ti affianca per un risultato garantito',
  'cta_text', 'Scopri',
  'cta_link', '/tecnologie',
  'image_placeholder', true
), NULL, 1, 1, NOW(), NOW());

-- Sezione "Tratta tutti gli inestetismi"
INSERT INTO `blocks` (`page_id`, `location`, `type`, `content_json`, `settings_json`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'home_features', 'features', JSON_OBJECT(
  'title', 'TRATTA TUTTI GLI INESTETISMI',
  'description', 'Con la tecnologia multifunzione puoi lavorare contemporaneamente sia il viso che il corpo ottimizzando la seduta e garantendo alla cliente un risultato visibile fin dal primo trattamento.',
  'image_placeholder', true
), NULL, 2, 1, NOW(), NOW());

-- Citazione / Quote
INSERT INTO `blocks` (`page_id`, `location`, `type`, `content_json`, `settings_json`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'home_quote', 'text_quote', JSON_OBJECT(
  'title', 'La cura della pelle, ridefinita.',
  'body', '"La qualità di ogni prodotto è il riflesso della cura che mettiamo nel selezionarlo per te."',
  'image_placeholder', true
), NULL, 3, 1, NOW(), NOW());

-- Features list "Un solo dispositivo, infiniti risultati"
INSERT INTO `blocks` (`page_id`, `location`, `type`, `content_json`, `settings_json`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'home_features_list', 'features_list', JSON_OBJECT(
  'title', 'UN SOLO DISPOSITIVO, INFINITI RISULTATI',
  'items', JSON_ARRAY(
    JSON_OBJECT('title', 'Massima versatilità', 'description', 'Massima versatilità, efficacia italiana. La rivoluzione multifunzione che fa crescere il tuo centro.'),
    JSON_OBJECT('title', 'Display personalizzabile', 'description', 'Personalizza il display con il logo del tuo Istituto.'),
    JSON_OBJECT('title', 'Noleggio o vendita', 'description', 'Trova la soluzione migliore tra noleggio o vendita.')
  )
), NULL, 4, 1, NOW(), NOW());

-- Product grid nuovi arrivi
INSERT INTO `blocks` (`page_id`, `location`, `type`, `content_json`, `settings_json`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'home_new_arrivals', 'product_grid', JSON_OBJECT(
  'title', 'Nuovi arrivi',
  'filter', 'is_new',
  'limit', 8
), NULL, 5, 1, NOW(), NOW());

-- ===== 13.16: BLOCCHI CMS CHI SIAMO (contenuto reale) =====

-- Hero chi siamo
INSERT INTO `blocks` (`page_id`, `location`, `type`, `content_json`, `settings_json`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(2, 'about_hero', 'hero', JSON_OBJECT(
  'title', 'Skintemple',
  'subtitle', '',
  'image_placeholder', true
), NULL, 1, 1, NOW(), NOW());

-- Testo descrittivo chi siamo (contenuto reale dal cliente)
INSERT INTO `blocks` (`page_id`, `location`, `type`, `content_json`, `settings_json`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(2, 'about_text', 'text', JSON_OBJECT(
  'body', 'Nasce da una visione concreta, costruita sul campo. Dall''esperienza diretta del fondatore, maturata a stretto contatto con centri estetici e studi medici, emerge una consapevolezza chiara: il mercato è sempre più invaso da tecnologie estere, spesso prive di continuità, assistenza e reale valore nel tempo. Da qui prende forma Skintemple: un progetto orientato alla creazione di soluzioni 100% Made in Italy, capaci di unire qualità costruttiva, robustezza, design e affidabilità. Non solo tecnologia, ma un vero strumento di lavoro pensato per i professionisti del settore, con un elemento distintivo fondamentale: un servizio di assistenza presente, competente e duraturo. Il tutto arricchito da un approccio intelligente alla multifunzionalità, che permette di ottimizzare gli investimenti e massimizzare le performance operative, senza compromessi. Skintemple è più di una tecnologia: è un partner di crescita per il tuo business.'
), NULL, 2, 1, NOW(), NOW());

-- CTA chi siamo
INSERT INTO `blocks` (`page_id`, `location`, `type`, `content_json`, `settings_json`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(2, 'about_cta', 'cta', JSON_OBJECT(
  'button', 'Scopri',
  'link', '/tecnologie'
), NULL, 3, 1, NOW(), NOW());

-- Features "Perché scegliere una tecnologia multifunzione"
INSERT INTO `blocks` (`page_id`, `location`, `type`, `content_json`, `settings_json`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(2, 'about_features', 'features', JSON_OBJECT(
  'title', 'Perché scegliere una tecnologia multifunzione',
  'items', JSON_ARRAY(
    JSON_OBJECT('title', 'Versatilità operativa', 'description', 'Puoi utilizzare tutte le tecnologie in un''unica seduta per un trattamento completo, oppure modularle in base alle esigenze della cliente e al suo budget. Massima flessibilità, massima efficacia.'),
    JSON_OBJECT('title', 'Economia degli spazi', 'description', 'Dì addio alle cabine affollate da macchinari ingombranti. La nostra Piattaforma di Bellezza integra più tecnologie in un''unica scocca compatta, elegante e funzionale, ottimizzando lo spazio senza rinunciare alla qualità.')
  )
), NULL, 4, 1, NOW(), NOW());

-- ===== 13.17: BLOCCHI PAGINE SECONDARIE =====

-- Supporto placeholder
INSERT INTO `blocks` (`page_id`, `location`, `type`, `content_json`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(3, 'supporto_content', 'text', '{"body":"Hai bisogno di assistenza? Il nostro team è a tua disposizione. Contattaci per qualsiasi informazione sui nostri prodotti e servizi."}', 1, 1, NOW(), NOW());

-- Contatti — form e info aziendali
INSERT INTO `blocks` (`page_id`, `location`, `type`, `content_json`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(4, 'contatti_form', 'contact_form', JSON_OBJECT(
  'company_name', 'SkinTemple',
  'address', 'Strada Santa Vittoria 11, 10024 Moncalieri (TO)',
  'vat', 'P.IVA 11863510019',
  'email', 'info@skintemple.it',
  'phone', ''
), 1, 1, NOW(), NOW());

-- Privacy placeholder (draft)
INSERT INTO `blocks` (`page_id`, `location`, `type`, `content_json`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(5, 'privacy_content', 'text', '{"body":"[Inserire testo Privacy Policy]"}', 1, 1, NOW(), NOW());

-- Cookie placeholder (draft)
INSERT INTO `blocks` (`page_id`, `location`, `type`, `content_json`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(6, 'cookie_content', 'text', '{"body":"[Inserire testo Cookie Policy]"}', 1, 1, NOW(), NOW());

-- Termini placeholder (draft)
INSERT INTO `blocks` (`page_id`, `location`, `type`, `content_json`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(7, 'termini_content', 'text', '{"body":"[Inserire testo Termini di Servizio]"}', 1, 1, NOW(), NOW());

-- ===== 13.18: MENU NAVIGAZIONE =====

INSERT IGNORE INTO `menus` (`id`, `code`, `name`, `location`, `is_active`, `created_at`, `updated_at`) VALUES
  (1, 'main_nav',   'Navigazione principale', 'header',  1, NOW(), NOW()),
  (2, 'footer_nav', 'Footer',                 'footer',  1, NOW(), NOW()),
  (3, 'mobile_nav', 'Navigazione mobile',     'mobile',  1, NOW(), NOW());

-- Menu principale (header)
INSERT INTO `menu_items` (`menu_id`, `parent_id`, `label`, `type`, `url`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
  (1, NULL, '{"it":"Home"}',        'custom', '/',                     1, 1, NOW(), NOW()),
  (1, NULL, '{"it":"Prodotti"}',    'custom', '/shop',                 2, 1, NOW(), NOW()),
  (1, NULL, '{"it":"Tecnologie"}',  'custom', '/categoria/tecnologie', 3, 1, NOW(), NOW()),
  (1, NULL, '{"it":"Chi Siamo"}',   'custom', '/chi-siamo',            4, 1, NOW(), NOW()),
  (1, NULL, '{"it":"Supporto"}',    'custom', '/supporto',             5, 1, NOW(), NOW()),
  (1, NULL, '{"it":"Contatti"}',    'custom', '/contatti',             6, 1, NOW(), NOW()),
  (1, NULL, '{"it":"Blog"}',        'custom', '/blog',                 7, 1, NOW(), NOW());

-- Footer — Sezione "Naviga"
INSERT INTO `menu_items` (`id`, `menu_id`, `parent_id`, `label`, `type`, `url`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
  (8,  2, NULL, '{"it":"Naviga"}',   'custom', NULL, 1, 1, NOW(), NOW());
INSERT INTO `menu_items` (`menu_id`, `parent_id`, `label`, `type`, `url`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
  (2, 8, '{"it":"Home"}',        'custom', '/',                     1, 1, NOW(), NOW()),
  (2, 8, '{"it":"Prodotti"}',    'custom', '/shop',                 2, 1, NOW(), NOW()),
  (2, 8, '{"it":"Chi Siamo"}',   'custom', '/chi-siamo',            3, 1, NOW(), NOW()),
  (2, 8, '{"it":"Tecnologie"}',  'custom', '/categoria/tecnologie', 4, 1, NOW(), NOW()),
  (2, 8, '{"it":"Supporto"}',    'custom', '/supporto',             5, 1, NOW(), NOW()),
  (2, 8, '{"it":"Contatti"}',    'custom', '/contatti',             6, 1, NOW(), NOW());

-- Footer — Sezione "Legale"
INSERT INTO `menu_items` (`id`, `menu_id`, `parent_id`, `label`, `type`, `url`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
  (15, 2, NULL, '{"it":"Legale"}', 'custom', NULL, 2, 1, NOW(), NOW());
INSERT INTO `menu_items` (`menu_id`, `parent_id`, `label`, `type`, `url`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
  (2, 15, '{"it":"Privacy Policy"}',      'page', '/privacy',              1, 1, NOW(), NOW()),
  (2, 15, '{"it":"Cookie Policy"}',       'page', '/cookie',               2, 1, NOW(), NOW()),
  (2, 15, '{"it":"Termini di servizio"}', 'page', '/termini-di-servizio',  3, 1, NOW(), NOW());

-- Footer — Sezione "Contatti"
INSERT INTO `menu_items` (`id`, `menu_id`, `parent_id`, `label`, `type`, `url`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
  (19, 2, NULL, '{"it":"Contatti"}', 'custom', NULL, 3, 1, NOW(), NOW());
INSERT INTO `menu_items` (`menu_id`, `parent_id`, `label`, `type`, `url`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
  (2, 19, '{"it":"info@skintemple.it"}',                             'custom', 'mailto:info@skintemple.it', 1, 1, NOW(), NOW()),
  (2, 19, '{"it":"Italia"}',                                        'custom', NULL,                        2, 1, NOW(), NOW()),
  (2, 19, '{"it":"P.IVA 11863510019"}',                             'custom', NULL,                        3, 1, NOW(), NOW()),
  (2, 19, '{"it":"Strada Santa Vittoria 11 — 10024 Moncalieri (TO)"}', 'custom', NULL,                    4, 1, NOW(), NOW());

-- Menu mobile
INSERT INTO `menu_items` (`menu_id`, `parent_id`, `label`, `type`, `url`, `icon`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
  (3, NULL, '{"it":"Home"}',     'custom', '/',         'home',          1, 1, NOW(), NOW()),
  (3, NULL, '{"it":"Shop"}',     'custom', '/shop',     'shopping-bag',  2, 1, NOW(), NOW()),
  (3, NULL, '{"it":"Account"}',  'custom', '/account',  'user',          3, 1, NOW(), NOW()),
  (3, NULL, '{"it":"Carrello"}', 'custom', '/carrello', 'shopping-cart', 4, 1, NOW(), NOW()),
  (3, NULL, '{"it":"Menu"}',     'custom', '#menu',     'menu',          5, 1, NOW(), NOW());

-- ===== 13.19: EMAIL TEMPLATES =====
INSERT IGNORE INTO `email_templates` (`code`, `name`, `subject_template`, `body_html_template`, `body_text_template`, `available_variables_json`, `is_active`, `created_at`, `updated_at`) VALUES
('otp_login', 'OTP Login', 'Il tuo codice di accesso SkinTemple',
'<h2>Ciao {{ $customer_name }},</h2><p>Il tuo codice di accesso è:</p><h1 style="text-align:center;font-size:32px;letter-spacing:8px;">{{ $otp_code }}</h1><p>Il codice scade tra {{ $expires_minutes }} minuti.</p><p>Se non hai richiesto questo codice, ignora questa email.</p><p>— SkinTemple</p>',
'Ciao {{ $customer_name }}, il tuo codice di accesso è: {{ $otp_code }}. Scade tra {{ $expires_minutes }} minuti.',
'["customer_name","otp_code","expires_minutes"]',
1, NOW(), NOW()),

('order_confirmation', 'Conferma Ordine', 'Ordine {{ $order_number }} confermato — SkinTemple',
'<h2>Grazie {{ $customer_name }}!</h2><p>Il tuo ordine <strong>{{ $order_number }}</strong> è stato confermato.</p><p><strong>Totale:</strong> € {{ $order_total }}</p><p>Riceverai un aggiornamento quando il pacco verrà spedito.</p><p>— SkinTemple</p>',
'Grazie {{ $customer_name }}! Ordine {{ $order_number }} confermato. Totale: € {{ $order_total }}.',
'["customer_name","order_number","order_total","order_items","shipping_address"]',
1, NOW(), NOW()),

('order_shipped', 'Ordine Spedito', 'Il tuo ordine {{ $order_number }} è in viaggio!',
'<h2>Ciao {{ $customer_name }},</h2><p>Il tuo ordine <strong>{{ $order_number }}</strong> è stato spedito!</p><p><strong>Corriere:</strong> {{ $carrier }}</p><p><strong>Tracking:</strong> <a href="{{ $tracking_url }}">{{ $tracking_number }}</a></p><p>— SkinTemple</p>',
'Ciao {{ $customer_name }}, il tuo ordine {{ $order_number }} è stato spedito. Tracking: {{ $tracking_url }}',
'["customer_name","order_number","carrier","tracking_number","tracking_url"]',
1, NOW(), NOW()),

('order_delivered', 'Ordine Consegnato', 'Il tuo ordine {{ $order_number }} è stato consegnato',
'<h2>Ciao {{ $customer_name }},</h2><p>Il tuo ordine <strong>{{ $order_number }}</strong> risulta consegnato.</p><p>Speriamo che i prodotti siano di tuo gradimento! Per qualsiasi necessità, contattaci.</p><p>— SkinTemple</p>',
'Ciao {{ $customer_name }}, il tuo ordine {{ $order_number }} è stato consegnato.',
'["customer_name","order_number"]',
1, NOW(), NOW()),

('order_cancelled', 'Ordine Annullato', 'Ordine {{ $order_number }} annullato',
'<h2>Ciao {{ $customer_name }},</h2><p>Il tuo ordine <strong>{{ $order_number }}</strong> è stato annullato.</p><p><strong>Motivo:</strong> {{ $reason }}</p><p>Per qualsiasi domanda, contattaci a info@skintemple.it.</p><p>— SkinTemple</p>',
'Ciao {{ $customer_name }}, l''ordine {{ $order_number }} è stato annullato. Motivo: {{ $reason }}.',
'["customer_name","order_number","reason"]',
1, NOW(), NOW()),

('newsletter_double_opt_in', 'Conferma Newsletter', 'Conferma la tua iscrizione alla newsletter SkinTemple',
'<h2>Ciao{{ $name }},</h2><p>Grazie per esserti iscritto alla newsletter SkinTemple!</p><p>Clicca il pulsante per confermare:</p><p><a href="{{ $confirmation_url }}" style="padding:12px 24px;background:#000;color:#fff;text-decoration:none;border-radius:6px;">Conferma iscrizione</a></p><p>Il link scade tra 48 ore.</p><p>— SkinTemple</p>',
'Conferma la tua iscrizione: {{ $confirmation_url }}',
'["name","confirmation_url"]',
1, NOW(), NOW()),

('admin_new_order', 'Admin — Nuovo Ordine', '[SkinTemple Admin] Nuovo ordine {{ $order_number }}',
'<h2>Nuovo ordine ricevuto</h2><p><strong>Ordine:</strong> {{ $order_number }}</p><p><strong>Cliente:</strong> {{ $customer_name }} ({{ $customer_email }})</p><p><strong>Totale:</strong> € {{ $order_total }}</p><p><a href="{{ $admin_url }}">Visualizza nel pannello</a></p>',
'Nuovo ordine {{ $order_number }} da {{ $customer_name }} — € {{ $order_total }}',
'["order_number","customer_name","customer_email","order_total","admin_url"]',
1, NOW(), NOW()),

('admin_low_stock', 'Admin — Stock Basso', '[SkinTemple Admin] Scorte basse: {{ $product_name }}',
'<h2>Allarme scorte basse</h2><p>Il prodotto <strong>{{ $product_name }}</strong> (SKU: {{ $sku }}) ha raggiunto <strong>{{ $quantity }} unità</strong> disponibili.</p><p>Soglia impostata: {{ $threshold }}</p><p><a href="{{ $admin_url }}">Gestisci inventario</a></p>',
'Scorte basse: {{ $product_name }} ({{ $sku }}) — {{ $quantity }} unità rimaste.',
'["product_name","sku","quantity","threshold","admin_url"]',
1, NOW(), NOW()),

('admin_failed_payment', 'Admin — Pagamento Fallito', '[SkinTemple Admin] Pagamento fallito ordine {{ $order_number }}',
'<h2>Pagamento fallito</h2><p><strong>Ordine:</strong> {{ $order_number }}</p><p><strong>Provider:</strong> {{ $provider }}</p><p><strong>Errore:</strong> {{ $error_message }}</p><p><a href="{{ $admin_url }}">Visualizza dettagli</a></p>',
'Pagamento fallito per ordine {{ $order_number }} via {{ $provider }}: {{ $error_message }}',
'["order_number","provider","error_message","admin_url"]',
1, NOW(), NOW()),

('admin_otp_alert', 'Admin — Allarme OTP', '[SkinTemple Admin] Tentativi OTP sospetti da {{ $email }}',
'<h2>Allarme sicurezza OTP</h2><p>L''indirizzo <strong>{{ $email }}</strong> ha superato il numero massimo di tentativi OTP.</p><p><strong>IP:</strong> {{ $ip_address }}</p><p><strong>Tentativi:</strong> {{ $attempts }}</p><p>Valutare eventuale blocco temporaneo.</p>',
'Allarme OTP: {{ $email }} — {{ $attempts }} tentativi da IP {{ $ip_address }}.',
'["email","ip_address","attempts"]',
1, NOW(), NOW());

-- ===== 13.20: PAYMENT METHODS =====
INSERT IGNORE INTO `payment_methods` (`code`, `name`, `provider`, `is_active`, `sort_order`, `instructions`, `created_at`, `updated_at`) VALUES
  ('bank_transfer',   'Bonifico Bancario', 'manual',  1, 1, 'Effettuare il bonifico al seguente IBAN: [CONFIGURARE IBAN DA PANNELLO]. Causale: numero ordine. I prodotti verranno spediti dopo la ricezione del pagamento.', NOW(), NOW()),
  ('cash_on_delivery', 'Contrassegno',     'manual',  0, 2, 'Pagamento alla consegna. Supplemento di € 3,90 applicato automaticamente.', NOW(), NOW()),
  ('stripe_card',      'Carta di Credito', 'stripe',  0, 3, NULL, NOW(), NOW()),
  ('paypal',           'PayPal',           'paypal',  0, 4, NULL, NOW(), NOW());

-- ===== 13.21: COUPON BENVENUTO =====
INSERT IGNORE INTO `coupons` (`code`, `type`, `value`, `min_order_amount`, `applies_to`, `usage_limit_per_customer`, `starts_at`, `expires_at`, `is_active`, `description`, `created_at`, `updated_at`) VALUES
  ('BENVENUTO10', 'percentage', 10.00, 0.00, 'first_order', 1, NOW(), DATE_ADD(NOW(), INTERVAL 1 YEAR), 1, 'Sconto 10% sul primo ordine — Benvenuto su SkinTemple!', NOW(), NOW());


-- ===================================================================
-- FINE SEED DATA — RIATTIVAZIONE FK
-- ===================================================================
SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================================
-- FINE SCHEMA SKINTEMPLE v1.0.0
-- ============================================================================

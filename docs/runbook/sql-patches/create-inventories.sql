-- Eseguire in phpMyAdmin su database u951446261_sktdb
-- Tab: SQL → incolla → Esegui

-- Controllo se la tabella inventory esiste già
-- Se no, la crea con tutte le dipendenze necessarie

-- ===================================================================
-- TABELLA PRODUCT_VARIANTS (prerequisito per inventory)
-- ===================================================================

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

-- ===================================================================
-- TABELLA INVENTORY (giacenze magazzino)
-- ===================================================================

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

-- ===================================================================
-- TABELLA STOCK_MOVEMENTS (movimenti di magazzino - audit trail)
-- ===================================================================

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
-- INSERIMENTO DATI TEST (opzionale)
-- ===================================================================

-- Inserimento inventory per le varianti esistenti (se ci sono)
-- INSERT IGNORE INTO `inventory` (`product_variant_id`, `quantity`, `threshold_low`, `allow_backorder`, `created_at`, `updated_at`)
-- SELECT `id`, 0, 5, 0, NOW(), NOW() FROM `product_variants` WHERE `id` NOT IN (SELECT `product_variant_id` FROM `inventory`);

-- Fine script
# 05 — Trading: Trade-in / Barter Listings

"Trading products" are user-generated **trade-in / barter listings**: customers list their
own used items for trade or swap with other users, separate from the main product catalog.
These are fully database-backed. A listing becomes visible in the trading section
**automatically** once a user posts it.

## `trade_listings`

A single item a user wants to trade.

```sql
CREATE TABLE `trade_listings` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `category_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `title` VARCHAR(191) NOT NULL,
  `slug` VARCHAR(191) NOT NULL,
  `description` LONGTEXT NOT NULL,
  `condition` ENUM('new','like_new','good','fair','poor') NOT NULL DEFAULT 'good',
  `estimated_value` DECIMAL(10,2) NULL DEFAULT NULL,
  `preferred_trade` VARCHAR(255) NULL DEFAULT NULL,
  `status` ENUM('active','pending_trade','completed','cancelled') NOT NULL DEFAULT 'active',
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  `deleted_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `trade_listings_slug_unique` (`slug`),
  KEY `trade_listings_user_id_index` (`user_id`),
  KEY `trade_listings_category_id_index` (`category_id`),
  KEY `trade_listings_status_index` (`status`),
  CONSTRAINT `fk_trade_listings_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_trade_listings_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

| Column | Type | Notes |
| --- | --- | --- |
| `user_id` | BIGINT UNSIGNED | Owner / lister |
| `category_id` | BIGINT UNSIGNED NULL | Optional category for filtering |
| `condition` | ENUM | Item condition |
| `estimated_value` | DECIMAL(10,2) | Owner's valuation |
| `preferred_trade` | VARCHAR(255) | What they want in exchange |
| `status` | ENUM | `active`, `pending_trade`, `completed`, `cancelled` |
| `is_active` | TINYINT(1) | Hide without deleting |

## `trade_images`

Gallery for a trade listing (same upload pattern as product images).

```sql
CREATE TABLE `trade_images` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `trade_listing_id` BIGINT UNSIGNED NOT NULL,
  `path` VARCHAR(255) NOT NULL,
  `sort_order` INT UNSIGNED NOT NULL DEFAULT 0,
  `is_primary` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trade_images_trade_listing_id_index` (`trade_listing_id`),
  CONSTRAINT `fk_trade_images_listing` FOREIGN KEY (`trade_listing_id`) REFERENCES `trade_listings` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

## `trade_offers`

A swap/trade proposal sent by another user in response to a listing.

```sql
CREATE TABLE `trade_offers` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `trade_listing_id` BIGINT UNSIGNED NOT NULL,
  `from_user_id` BIGINT UNSIGNED NOT NULL,
  `message` TEXT NULL,
  `offered_items` TEXT NULL,
  `offered_value` DECIMAL(10,2) NULL DEFAULT NULL,
  `cash_difference` DECIMAL(10,2) NULL DEFAULT NULL,
  `status` ENUM('pending','accepted','rejected','withdrawn') NOT NULL DEFAULT 'pending',
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trade_offers_trade_listing_id_index` (`trade_listing_id`),
  KEY `trade_offers_from_user_id_index` (`from_user_id`),
  KEY `trade_offers_status_index` (`status`),
  CONSTRAINT `fk_trade_offers_listing` FOREIGN KEY (`trade_listing_id`) REFERENCES `trade_listings` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_trade_offers_user` FOREIGN KEY (`from_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

| Column | Type | Notes |
| --- | --- | --- |
| `offered_items` | TEXT | Description of what the buyer offers in exchange |
| `offered_value` | DECIMAL(10,2) | Value of the offered items |
| `cash_difference` | DECIMAL(10,2) | Money to balance an unequal trade |
| `status` | ENUM | Negotiation state |

## Flow

1. A customer posts a `trade_listings` row (with images) — it appears in the trading
   section immediately.
2. Another user sends a `trade_offers` row (`pending`).
3. The owner accepts/rejects. On acceptance the listing becomes `pending_trade`, then
   `completed` when the swap is finalized.
4. Either party can `withdraw` an offer or `cancel` a listing.

## Relationship summary

- `users 1—* trade_listings`
- `trade_listings 1—* trade_images`
- `trade_listings 1—* trade_offers`
- `users 1—* trade_offers` (as the proposing user)

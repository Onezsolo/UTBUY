# 01 — Users, Authentication & Addresses

## `users`

Customers and admins share one table, differentiated by the `role` column. The storefront
role is `customer`; the back office uses `admin`. Trade-in/barter is done by normal
`customer` accounts (no separate "trader" role required).

```sql
CREATE TABLE `users` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(191) NOT NULL,
  `email` VARCHAR(191) NOT NULL,
  `email_verified_at` TIMESTAMP NULL DEFAULT NULL,
  `password` VARCHAR(191) NOT NULL,
  `phone` VARCHAR(30) NULL DEFAULT NULL,
  `avatar` VARCHAR(255) NULL DEFAULT NULL,
  `role` ENUM('customer','admin') NOT NULL DEFAULT 'customer',
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `remember_token` VARCHAR(100) NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  `deleted_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_role_index` (`role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

| Column | Type | Notes |
| --- | --- | --- |
| `id` | BIGINT UNSIGNED | Primary key |
| `name` | VARCHAR(191) | Display name |
| `email` | VARCHAR(191) | Unique login identifier |
| `email_verified_at` | TIMESTAMP NULL | Email verification timestamp |
| `password` | VARCHAR(191) | Bcrypt/argon2 hash |
| `phone` | VARCHAR(30) | Contact number |
| `avatar` | VARCHAR(255) | Profile photo path (nullable) |
| `role` | ENUM | `customer` or `admin` |
| `is_active` | TINYINT(1) | Soft disable login without deleting |
| `deleted_at` | TIMESTAMP NULL | Soft delete |

## `password_reset_tokens`

Laravel's standard password-reset store, keyed by email.

```sql
CREATE TABLE `password_reset_tokens` (
  `email` VARCHAR(191) NOT NULL,
  `token` VARCHAR(191) NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

## `sessions`

Laravel's database session driver.

```sql
CREATE TABLE `sessions` (
  `id` VARCHAR(191) NOT NULL,
  `user_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `ip_address` VARCHAR(45) NULL DEFAULT NULL,
  `user_agent` TEXT NULL,
  `payload` LONGTEXT NOT NULL,
  `last_activity` INT NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

## `addresses`

Saved delivery details per user. This is the store behind the **"add and save delivery
details"** path (`/user/addresses`) and the checkout's shipping form. An order copies the
selected address into `orders.delivery_*` columns so later address edits do not mutate
historical orders.

```sql
CREATE TABLE `addresses` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `label` VARCHAR(50) NOT NULL DEFAULT 'Home',
  `full_name` VARCHAR(191) NOT NULL,
  `phone` VARCHAR(30) NOT NULL,
  `email` VARCHAR(191) NULL DEFAULT NULL,
  `address_line_1` VARCHAR(191) NOT NULL,
  `address_line_2` VARCHAR(191) NULL DEFAULT NULL,
  `city` VARCHAR(100) NOT NULL,
  `state` VARCHAR(100) NOT NULL,
  `postal_code` VARCHAR(20) NULL DEFAULT NULL,
  `country` VARCHAR(100) NOT NULL DEFAULT 'Nigeria',
  `is_default` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `addresses_user_id_index` (`user_id`),
  KEY `addresses_is_default_index` (`is_default`),
  CONSTRAINT `fk_addresses_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

| Column | Type | Notes |
| --- | --- | --- |
| `label` | VARCHAR(50) | e.g. "Home", "Office" |
| `full_name` | VARCHAR(191) | Recipient name |
| `phone` | VARCHAR(30) | Contact for delivery |
| `address_line_1/2` | VARCHAR(191) | Street address |
| `city` / `state` / `postal_code` / `country` | — | Geographic fields |
| `is_default` | TINYINT(1) | Default delivery address (only one per user) |

### Rules

- A user can own many addresses; deleting a user cascades to their addresses.
- Only one address per user should have `is_default = 1` (enforced in the application,
  e.g. on save, set all others to `0`).

## Relationship summary

- `users 1—* addresses`
- `users 1—* product_reviews`, `orders`, `payments`, `trade_listings`, `wishlist_items`

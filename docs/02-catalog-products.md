# 02 — Catalog: Categories, Products, Images & Specifications

Products use **uploaded images instead of icons**. Each product has one main image
(`products.image`) and an optional gallery (`product_images`). When no image has been
uploaded, the storefront falls back to a single default project icon stored in
`settings` under the key `products.default_image`.

## `categories`

A self-referencing category tree (categories can have sub-categories).

```sql
CREATE TABLE `categories` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `parent_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `name` VARCHAR(191) NOT NULL,
  `slug` VARCHAR(191) NOT NULL,
  `description` TEXT NULL,
  `image` VARCHAR(255) NULL DEFAULT NULL,
  `sort_order` INT UNSIGNED NOT NULL DEFAULT 0,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_slug_unique` (`slug`),
  KEY `categories_parent_id_index` (`parent_id`),
  CONSTRAINT `fk_categories_parent` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

## `products`

The central catalog table. `image` holds the main uploaded image path; it is `NULL`
when no image was provided, in which case the application renders the default project
icon from `settings`.

```sql
CREATE TABLE `products` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `category_id` BIGINT UNSIGNED NOT NULL,
  `name` VARCHAR(191) NOT NULL,
  `slug` VARCHAR(191) NOT NULL,
  `sku` VARCHAR(100) NULL DEFAULT NULL,
  `short_description` VARCHAR(255) NULL DEFAULT NULL,
  `description` LONGTEXT NULL,
  `unit` VARCHAR(50) NULL DEFAULT NULL,
  `price` DECIMAL(10,2) NOT NULL,
  `sale_price` DECIMAL(10,2) NULL DEFAULT NULL,
  `cost_price` DECIMAL(10,2) NULL DEFAULT NULL,
  `stock_quantity` INT UNSIGNED NOT NULL DEFAULT 0,
  `max_stock` INT UNSIGNED NOT NULL DEFAULT 0,
  `image` VARCHAR(255) NULL DEFAULT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `is_featured` TINYINT(1) NOT NULL DEFAULT 0,
  `rating_avg` DECIMAL(3,2) NOT NULL DEFAULT 0.00,
  `rating_count` INT UNSIGNED NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  `deleted_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_slug_unique` (`slug`),
  KEY `products_category_id_index` (`category_id`),
  KEY `products_is_active_index` (`is_active`),
  KEY `products_is_featured_index` (`is_featured`),
  KEY `products_name_index` (`name`),
  CONSTRAINT `fk_products_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

| Column | Type | Notes |
| --- | --- | --- |
| `category_id` | BIGINT UNSIGNED | Required category |
| `name` / `slug` | VARCHAR(191) | Product title and URL slug (unique) |
| `sku` | VARCHAR(100) | Stock keeping unit (optional) |
| `short_description` | VARCHAR(255) | One-line summary |
| `description` | LONGTEXT | Full description shown under "Check Description" |
| `unit` | VARCHAR(50) | e.g. "500g pack", "2-2.99 kg" |
| `price` | DECIMAL(10,2) | Selling price |
| `sale_price` | DECIMAL(10,2) | Discounted price (optional) |
| `cost_price` | DECIMAL(10,2) | Internal cost (optional) |
| `stock_quantity` | INT UNSIGNED | Current stock |
| `max_stock` | INT UNSIGNED | Stock capacity, used to draw the stock bar |
| `image` | VARCHAR(255) | Main uploaded image path; NULL → default icon |
| `is_active` | TINYINT(1) | Publish / hide |
| `is_featured` | TINYINT(1) | Featured on the homepage |
| `rating_avg` | DECIMAL(3,2) | Denormalized average rating (auto-updated from reviews) |
| `rating_count` | INT UNSIGNED | Denormalized review count |

> **Stock status** (`In Stock` / `Low Stock` / `Out of Stock`) is derived at read time
> from `stock_quantity` vs `max_stock`, not stored. Example: 0 → Out of Stock;
> `stock_quantity` below a threshold (e.g. 20% of `max_stock`) → Low Stock.

## `product_images`

Uploaded image gallery. One product can have several images; `is_primary` marks the
cover image (synced into `products.image`).

```sql
CREATE TABLE `product_images` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` BIGINT UNSIGNED NOT NULL,
  `path` VARCHAR(255) NOT NULL,
  `alt` VARCHAR(191) NULL DEFAULT NULL,
  `sort_order` INT UNSIGNED NOT NULL DEFAULT 0,
  `is_primary` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_images_product_id_index` (`product_id`),
  KEY `product_images_primary_index` (`product_id`, `is_primary`),
  CONSTRAINT `fk_product_images_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

## `product_specifications`

Key/value specifications (e.g. "Weight — 500g", "Origin — Local farm") rendered on the
**"Check Description"** page alongside the full description.

```sql
CREATE TABLE `product_specifications` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` BIGINT UNSIGNED NOT NULL,
  `label` VARCHAR(191) NOT NULL,
  `value` VARCHAR(255) NOT NULL,
  `sort_order` INT UNSIGNED NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_specifications_product_id_index` (`product_id`),
  CONSTRAINT `fk_product_specifications_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

## Image rules (no icons)

1. The admin "Add Product" form uploads a main image and optionally gallery images
   (PNG / JPG / WEBP, max 2 MB each).
2. `products.image` stores the primary image path (e.g. `products/<uuid>.webp`).
3. If `products.image` is `NULL`, the frontend renders the default project icon path
   from `settings` (`products.default_image`).
4. Deleting a product cascades to `product_images` and `product_specifications`.

## Relationship summary

- `categories 1—* products` (category delete is restricted while products exist)
- `products 1—* product_images`
- `products 1—* product_specifications`
- `products 1—* product_reviews`, `order_items`, `cart_items`

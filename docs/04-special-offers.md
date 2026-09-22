# 04 — Special Offers (Discounts)

Special offers are stored in the database and **automatically applied** to products based
on their `scope`:

- `all` — applies to every product, including products the admin adds later.
- `category` — applies to every product in the chosen categories.
- `product` — applies only to the explicitly selected products.

Because offers live in the database (not hardcoded), a new product automatically inherits
every active `all`-scoped offer and every active offer targeting its category the moment
the admin saves it.

## `special_offers`

```sql
CREATE TABLE `special_offers` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(191) NOT NULL,
  `slug` VARCHAR(191) NOT NULL,
  `description` TEXT NULL,
  `type` ENUM('percentage','fixed_amount','buy_x_get_y','free_shipping') NOT NULL DEFAULT 'percentage',
  `value` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `buy_quantity` INT UNSIGNED NULL DEFAULT NULL,
  `get_quantity` INT UNSIGNED NULL DEFAULT NULL,
  `scope` ENUM('all','category','product') NOT NULL DEFAULT 'all',
  `min_order_amount` DECIMAL(10,2) NULL DEFAULT NULL,
  `start_date` DATETIME NULL DEFAULT NULL,
  `end_date` DATETIME NULL DEFAULT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `special_offers_slug_unique` (`slug`),
  KEY `special_offers_active_window_index` (`is_active`, `start_date`, `end_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

| Column | Type | Notes |
| --- | --- | --- |
| `type` | ENUM | `percentage`, `fixed_amount`, `buy_x_get_y`, `free_shipping` |
| `value` | DECIMAL(10,2) | Discount amount (e.g. `20` = 20% off, or `5.00` off) |
| `buy_quantity` / `get_quantity` | INT | Quantities for `buy_x_get_y` offers |
| `scope` | ENUM | `all`, `category`, or `product` |
| `min_order_amount` | DECIMAL | Minimum basket value to activate the offer |
| `start_date` / `end_date` | DATETIME | Optional validity window |
| `is_active` | TINYINT(1) | Master switch |

## `offer_products` (pivot)

Links a `product`-scoped offer to specific products.

```sql
CREATE TABLE `offer_products` (
  `offer_id` BIGINT UNSIGNED NOT NULL,
  `product_id` BIGINT UNSIGNED NOT NULL,
  PRIMARY KEY (`offer_id`, `product_id`),
  KEY `offer_products_product_id_index` (`product_id`),
  CONSTRAINT `fk_offer_products_offer` FOREIGN KEY (`offer_id`) REFERENCES `special_offers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_offer_products_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

## `offer_categories` (pivot)

Links a `category`-scoped offer to categories (and, via the category tree, their
sub-categories).

```sql
CREATE TABLE `offer_categories` (
  `offer_id` BIGINT UNSIGNED NOT NULL,
  `category_id` BIGINT UNSIGNED NOT NULL,
  PRIMARY KEY (`offer_id`, `category_id`),
  KEY `offer_categories_category_id_index` (`category_id`),
  CONSTRAINT `fk_offer_categories_offer` FOREIGN KEY (`offer_id`) REFERENCES `special_offers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_offer_categories_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

## Auto-apply algorithm

When a product is loaded (or when the admin adds a product), the effective offer is the
highest-priority active offer that matches:

```text
matching offers = active offers where:
  now() is between start_date and end_date (when set)
  AND (
       scope = 'all'
    OR (scope = 'category' AND offer_categories.category_id IN (product's category path))
    OR (scope = 'product'  AND offer_products.product_id = product.id)
  )
```

The resulting discount is applied to `products.sale_price` (display) and to the order
totals (`orders.discount`) at checkout. Multiple offers do not stack by default; the best
single offer wins unless configured otherwise.

## Relationship summary

- `special_offers 1—* offer_products`
- `special_offers 1—* offer_categories`

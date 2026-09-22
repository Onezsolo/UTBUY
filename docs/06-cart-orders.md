# 06 — Cart & Orders

## `carts`

A cart belongs to a logged-in user, or to a guest identified by `session_id`. One of the
two is always set.

```sql
CREATE TABLE `carts` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `session_id` VARCHAR(191) NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `carts_user_id_index` (`user_id`),
  KEY `carts_session_id_index` (`session_id`),
  CONSTRAINT `fk_carts_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

## `cart_items`

Line items, each capturing the price at the time it was added.

```sql
CREATE TABLE `cart_items` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `cart_id` BIGINT UNSIGNED NOT NULL,
  `product_id` BIGINT UNSIGNED NOT NULL,
  `quantity` INT UNSIGNED NOT NULL DEFAULT 1,
  `price_at_add` DECIMAL(10,2) NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cart_items_cart_product_unique` (`cart_id`, `product_id`),
  KEY `cart_items_product_id_index` (`product_id`),
  CONSTRAINT `fk_cart_items_cart` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_cart_items_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

## `orders`

Placed orders. Delivery details are **snapshotted** onto the order (from `addresses` at
checkout) so historical orders are unaffected by later address changes. Totals include the
applied discount, shipping, tax, and the payment gateway fee (2% Paystack fee, see
[07-payments](07-payments.md)).

```sql
CREATE TABLE `orders` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_number` VARCHAR(40) NOT NULL,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `status` ENUM('pending','confirmed','processing','shipped','out_for_delivery','delivered','cancelled') NOT NULL DEFAULT 'pending',
  `payment_method` ENUM('cod','paystack') NOT NULL DEFAULT 'cod',
  `payment_status` ENUM('pending','paid','failed','refunded') NOT NULL DEFAULT 'pending',
  `subtotal` DECIMAL(10,2) NOT NULL,
  `discount` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `shipping_fee` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `tax` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `payment_fee` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `total` DECIMAL(10,2) NOT NULL,
  `currency` CHAR(3) NOT NULL DEFAULT 'NGN',
  `customer_notes` TEXT NULL,
  `delivery_full_name` VARCHAR(191) NOT NULL,
  `delivery_phone` VARCHAR(30) NOT NULL,
  `delivery_email` VARCHAR(191) NULL DEFAULT NULL,
  `delivery_address_line_1` VARCHAR(191) NOT NULL,
  `delivery_address_line_2` VARCHAR(191) NULL DEFAULT NULL,
  `delivery_city` VARCHAR(100) NOT NULL,
  `delivery_state` VARCHAR(100) NOT NULL,
  `delivery_postal_code` VARCHAR(20) NULL DEFAULT NULL,
  `delivery_country` VARCHAR(100) NOT NULL DEFAULT 'Nigeria',
  `placed_at` TIMESTAMP NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `orders_order_number_unique` (`order_number`),
  KEY `orders_user_id_index` (`user_id`),
  KEY `orders_status_index` (`status`),
  KEY `orders_payment_status_index` (`payment_status`),
  CONSTRAINT `fk_orders_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

| Column group | Notes |
| --- | --- |
| `status` | Fulfilment status, matches `order_statuses.code` |
| `payment_method` | `cod` or `paystack` |
| `payment_status` | `pending`, `paid`, `failed`, `refunded` |
| `subtotal` / `discount` / `shipping_fee` / `tax` / `payment_fee` / `total` | Monetary breakdown |
| `delivery_*` | Snapshot of the delivery details at checkout |
| `currency` | ISO 4217, default `NGN` (Naira) |

## `order_items`

Product line items with name/price snapshots so orders remain accurate even if a product
is later edited or deleted (`product_id` is nullable).

```sql
CREATE TABLE `order_items` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` BIGINT UNSIGNED NOT NULL,
  `product_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `name` VARCHAR(191) NOT NULL,
  `sku` VARCHAR(100) NULL DEFAULT NULL,
  `unit` VARCHAR(50) NULL DEFAULT NULL,
  `image` VARCHAR(255) NULL DEFAULT NULL,
  `price` DECIMAL(10,2) NOT NULL,
  `original_price` DECIMAL(10,2) NULL DEFAULT NULL,
  `quantity` INT UNSIGNED NOT NULL DEFAULT 1,
  `subtotal` DECIMAL(10,2) NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_items_order_id_index` (`order_id`),
  KEY `order_items_product_id_index` (`product_id`),
  CONSTRAINT `fk_order_items_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_order_items_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

## `order_shipments`

Carrier and tracking details once an order is shipped.

```sql
CREATE TABLE `order_shipments` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` BIGINT UNSIGNED NOT NULL,
  `shipping_method_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `carrier` VARCHAR(100) NULL DEFAULT NULL,
  `tracking_number` VARCHAR(100) NULL DEFAULT NULL,
  `shipped_at` TIMESTAMP NULL DEFAULT NULL,
  `delivered_at` TIMESTAMP NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_shipments_order_id_index` (`order_id`),
  KEY `order_shipments_shipping_method_id_index` (`shipping_method_id`),
  CONSTRAINT `fk_order_shipments_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_order_shipments_method` FOREIGN KEY (`shipping_method_id`) REFERENCES `shipping_methods` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

## Wishlist

```sql
CREATE TABLE `wishlist_items` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `product_id` BIGINT UNSIGNED NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `wishlist_items_user_product_unique` (`user_id`, `product_id`),
  CONSTRAINT `fk_wishlist_items_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_wishlist_items_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

## Relationship summary

- `carts 1—* cart_items`; `cart_items *—1 products`
- `orders 1—* order_items`; `order_items *—1 products` (nullable)
- `orders 1—* order_shipments`
- `users 1—* wishlist_items`

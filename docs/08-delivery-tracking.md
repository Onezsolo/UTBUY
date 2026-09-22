# 08 — Delivery & Order Tracking

Two related pieces:

- **Delivery details** are saved per user in `addresses` (see
  [01-users-auth-addresses](01-users-auth-addresses.md)) and snapshotted onto `orders`.
- **Order tracking** is driven by a canonical status list (`order_statuses`) plus an
  audit log (`order_status_histories`). The tracking page animates a moving icon along
  this timeline based on the order's current status and step.

## `shipping_methods`

Delivery options offered at checkout.

```sql
CREATE TABLE `shipping_methods` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(191) NOT NULL,
  `price` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `estimated_days_min` INT UNSIGNED NULL DEFAULT NULL,
  `estimated_days_max` INT UNSIGNED NULL DEFAULT NULL,
  `free_threshold` DECIMAL(10,2) NULL DEFAULT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `sort_order` INT UNSIGNED NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

| Column | Notes |
| --- | --- |
| `price` | Shipping cost |
| `estimated_days_min/max` | Delivery window (e.g. 3–5 business days) |
| `free_threshold` | Basket value above which shipping is free (e.g. `50.00`) |

### Seed rows

```sql
INSERT INTO `shipping_methods` (`name`, `price`, `estimated_days_min`, `estimated_days_max`, `free_threshold`, `sort_order`) VALUES
('Standard Delivery', 0.00, 3, 5, 50.00, 1),
('Express Delivery',  9.99, 1, 2, NULL,  2),
('Store Pickup',      0.00, NULL, NULL, NULL, 3);
```

## `order_statuses`

Canonical fulfilment statuses with their **timeline step** and **icon**. This is what the
order-tracking page uses to draw the progress bar and choose the moving icon.

```sql
CREATE TABLE `order_statuses` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` VARCHAR(50) NOT NULL,
  `name` VARCHAR(100) NOT NULL,
  `step` TINYINT UNSIGNED NOT NULL,
  `icon` VARCHAR(100) NULL DEFAULT NULL,
  `color` VARCHAR(50) NULL DEFAULT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_statuses_code_unique` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### Seed rows (tracking timeline)

| `code` | `name` | `step` | `icon` |
| --- | --- | --- | --- |
| `pending` | Order Placed | 1 | `fa-box-open` |
| `confirmed` | Order Confirmed | 2 | `fa-check-circle` |
| `processing` | Processing | 3 | `fa-cogs` |
| `shipped` | Shipped | 4 | `fa-truck` |
| `out_for_delivery` | Out for Delivery | 5 | `fa-shipping-fast` |
| `delivered` | Delivered | 6 | `fa-home` |
| `cancelled` | Cancelled | 0 | `fa-times-circle` |

```sql
INSERT INTO `order_statuses` (`code`, `name`, `step`, `icon`, `color`) VALUES
('pending',          'Order Placed',     1, 'fa-box-open',      'gray'),
('confirmed',        'Order Confirmed',  2, 'fa-check-circle',  'blue'),
('processing',       'Processing',       3, 'fa-cogs',          'indigo'),
('shipped',          'Shipped',          4, 'fa-truck',         'orange'),
('out_for_delivery', 'Out for Delivery', 5, 'fa-shipping-fast', 'amber'),
('delivered',        'Delivered',        6, 'fa-home',          'green'),
('cancelled',        'Cancelled',        0, 'fa-times-circle',  'red');
```

## `order_status_histories`

Audit log: every time an order's status changes, a row is appended with a timestamp. The
tracking page uses these timestamps to show **when** each step was reached.

```sql
CREATE TABLE `order_status_histories` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` BIGINT UNSIGNED NOT NULL,
  `status_code` VARCHAR(50) NOT NULL,
  `note` VARCHAR(255) NULL DEFAULT NULL,
  `changed_by` BIGINT UNSIGNED NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_status_histories_order_id_index` (`order_id`),
  KEY `order_status_histories_status_code_index` (`status_code`),
  KEY `order_status_histories_changed_by_index` (`changed_by`),
  CONSTRAINT `fk_order_status_histories_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_order_status_histories_status` FOREIGN KEY (`status_code`) REFERENCES `order_statuses` (`code`) ON DELETE CASCADE,
  CONSTRAINT `fk_order_status_histories_user` FOREIGN KEY (`changed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

> `status_code` references the string `code` in `order_statuses` (a natural key) rather
> than the numeric id, matching `orders.status`.

## How "moving icons" work

1. The tracking page loads the ordered `order_statuses` rows (by `step`) to render the
   full progress bar.
2. `orders.status` selects the current step; the frontend animates the icon from the
   previous step to the current one.
3. `order_status_histories` supplies the timestamp shown under each completed step.
4. `cancelled` (step 0) short-circuits the bar into a red "Cancelled" state.

## Relationship summary

- `shipping_methods 1—* order_shipments`
- `order_statuses 1—* order_status_histories` (via `code`)
- `orders 1—* order_status_histories`

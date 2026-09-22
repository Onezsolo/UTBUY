# 07 — Payments (COD + Paystack 2% fee)

Two payment options are supported:

1. **Payment on Delivery (COD)** — customer pays cash at the doorstep, no gateway fee.
2. **Paystack** — online card/bank payment with a **2% fee** added to the order total.

Fees and gateway credentials are configurable in the database (not hardcoded), so the 2%
Paystack fee can be adjusted from the admin **Payment Gateway** page.

## `payment_gateways`

Enabled payment methods and their per-method settings. `fee_percent` holds the Paystack
2% fee; COD uses `0.00`.

```sql
CREATE TABLE `payment_gateways` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` VARCHAR(50) NOT NULL,
  `name` VARCHAR(191) NOT NULL,
  `is_enabled` TINYINT(1) NOT NULL DEFAULT 1,
  `fee_percent` DECIMAL(5,2) NOT NULL DEFAULT 0.00,
  `config` JSON NULL,
  `sort_order` INT UNSIGNED NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `payment_gateways_code_unique` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

| Column | Notes |
| --- | --- |
| `code` | `cod` or `paystack` |
| `is_enabled` | Whether the method is offered at checkout |
| `fee_percent` | Fee percentage (Paystack = `2.00`) |
| `config` | JSON: Paystack public/secret keys, mode (`live`/`sandbox`), currency |

### Seed rows

```sql
INSERT INTO `payment_gateways` (`code`, `name`, `is_enabled`, `fee_percent`, `sort_order`) VALUES
('cod',      'Payment on Delivery', 1, 0.00, 1),
('paystack', 'Paystack',            1, 2.00, 2);
```

## `payments`

One row per payment attempt/record for an order. The `fee` column stores the gateway fee
that was charged (e.g. `2%` of the Paystack amount). COD payments are created with
`status = 'pending'` and fee `0.00` until cash is collected.

```sql
CREATE TABLE `payments` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` BIGINT UNSIGNED NOT NULL,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `method` ENUM('cod','paystack') NOT NULL,
  `amount` DECIMAL(10,2) NOT NULL,
  `fee` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `currency` CHAR(3) NOT NULL DEFAULT 'NGN',
  `status` ENUM('pending','authorized','paid','failed','refunded') NOT NULL DEFAULT 'pending',
  `provider_reference` VARCHAR(191) NULL DEFAULT NULL,
  `provider_response` JSON NULL,
  `paid_at` TIMESTAMP NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `payments_order_id_index` (`order_id`),
  KEY `payments_user_id_index` (`user_id`),
  KEY `payments_status_index` (`status`),
  CONSTRAINT `fk_payments_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_payments_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

| Column | Notes |
| --- | --- |
| `amount` | Payment amount (order `total`) |
| `fee` | Gateway fee applied (2% of amount for Paystack) |
| `provider_reference` | Paystack transaction reference |
| `provider_response` | Raw Paystack webhook/verify response |
| `paid_at` | When the payment was confirmed |

> This table powers the admin **Transactions** page and the user's **Transactions** page.

## Fee calculation

At checkout, when `paystack` is selected:

```text
payment_fee = ROUND(total * fee_percent / 100, 2)   -- fee_percent = 2.00
order.total = subtotal - discount + shipping_fee + tax + payment_fee
```

`orders.payment_fee` records the fee for the order, and the corresponding `payments.fee`
records the same value.

## `settings` (configuration)

Key/value store for site-wide configuration that does not warrant its own table. Includes
the default product icon and Paystack keys (also mirrored in `payment_gateways.config`).

```sql
CREATE TABLE `settings` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `key` VARCHAR(191) NOT NULL,
  `value` TEXT NULL,
  `group` VARCHAR(100) NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### Recommended keys

| Key | Value | Purpose |
| --- | --- | --- |
| `products.default_image` | `/images/products/default-icon.png` | Fallback icon when a product has no image |
| `paystack.public_key` | `pk_live_…` | Paystack public key |
| `paystack.secret_key` | `sk_live_…` | Paystack secret key |
| `paystack.mode` | `live` / `sandbox` | Environment |
| `paystack.fee_percent` | `2.00` | Default Paystack fee (mirrors `payment_gateways`) |
| `store.currency` | `NGN` | Default currency |
| `store.name` | `UTBUY` | Store name |

## Relationship summary

- `orders 1—* payments`
- `users 1—* payments`

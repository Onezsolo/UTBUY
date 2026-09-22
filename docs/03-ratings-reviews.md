# 03 — Ratings & Reviews

Ratings are stored per-review and aggregated back onto `products` so the storefront can
show a star rating without recomputing on every page load. A brand-new product starts at
`0.00` rating with `0` reviews, so **ratings "automatically apply" the moment a product is
added** — no hardcoded stars, everything reads from the database.

## `product_reviews`

One row per customer review/rating.

```sql
CREATE TABLE `product_reviews` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` BIGINT UNSIGNED NOT NULL,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `order_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `rating` TINYINT UNSIGNED NOT NULL,
  `title` VARCHAR(191) NULL DEFAULT NULL,
  `comment` TEXT NULL,
  `is_approved` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_reviews_user_product_unique` (`product_id`, `user_id`),
  KEY `product_reviews_product_id_index` (`product_id`),
  KEY `product_reviews_user_id_index` (`user_id`),
  CONSTRAINT `fk_product_reviews_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_product_reviews_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

| Column | Type | Notes |
| --- | --- | --- |
| `order_id` | BIGINT UNSIGNED NULL | Optional link to a purchase (verified review) |
| `rating` | TINYINT UNSIGNED | 1–5 stars |
| `title` / `comment` | — | Optional review text |
| `is_approved` | TINYINT(1) | Moderation flag; only approved reviews count |

> The unique key `(product_id, user_id)` means a user can leave **one** rating per
> product (they may edit it later).

## Auto-applied rating summary

`products.rating_avg` and `products.rating_count` are denormalized aggregates kept in
sync whenever a review is inserted, updated, or deleted:

- `rating_count` = number of **approved** reviews.
- `rating_avg` = `SUM(rating) / COUNT(*)` over approved reviews, rounded to 2 decimals.

```sql
-- Example: recalculate a product's rating after a review changes
UPDATE `products` p
SET p.rating_count = (
        SELECT COUNT(*) FROM `product_reviews` r
        WHERE r.product_id = p.id AND r.is_approved = 1
    ),
    p.rating_avg = COALESCE((
        SELECT ROUND(AVG(r.rating), 2) FROM `product_reviews` r
        WHERE r.product_id = p.id AND r.is_approved = 1
    ), 0.00)
WHERE p.id = :product_id;
```

In the application this runs inside a database transaction after each review write (or via
a `product_reviews` AFTER INSERT/UPDATE/DELETE trigger).

## Relationship summary

- `products 1—* product_reviews`
- `users 1—* product_reviews`
- `product_reviews *—1 orders` (optional, for verified purchases)

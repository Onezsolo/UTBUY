# 09 — Feature Mapping (Requirements → Tables & Paths)

This page maps each storefront requirement to the database tables that back it and the
URL path (route) that exposes it. Paths assume the existing route naming style in
`routes/web.php` (`shop.*`, `user.*`, `admin.*`).

## 1. Uploaded product images (no icons) + default fallback

**Tables:** `products.image`, `product_images`, `settings`.

- Admin uploads a main image + gallery images when adding/editing a product
  (`admin.products` → "Add Product" modal).
- `products.image` stores the main image; `product_images` stores the gallery.
- If `products.image` is `NULL`, the storefront renders `settings` value
  `products.default_image` (the single default project icon).

## 2. Ratings stored & auto-applied

**Tables:** `product_reviews`, `products.rating_avg` / `products.rating_count`.

- Ratings are stored per product/user in `product_reviews`.
- `products.rating_avg` and `rating_count` are auto-recomputed on every review change.
- A newly added product starts at `0.00` / `0` — the UI always reads from the database,
  never from hardcoded stars.

## 3. Special offers stored & auto-applied

**Tables:** `special_offers`, `offer_products`, `offer_categories`.

- Offers are created by the admin and persisted.
- A new product automatically inherits every active `all` offer and any active offer
  targeting its category; `product`-scoped offers attach via `offer_products`.
- The winning offer drives `products.sale_price` and `orders.discount` at checkout.

## 4. Trading products (trade-in / barter) stored & auto-listed

**Tables:** `trade_listings`, `trade_images`, `trade_offers`.

- Users post trade listings; they appear in the trading section immediately (status
  `active`, `is_active = 1`).
- Other users send `trade_offers`; the owner accepts/rejects.

## 5. "Check Description" — descriptions & specifications

**Tables:** `products.description`, `products.short_description`, `product_specifications`.

- **Path (label):** a "Check Description" link/button on the product card or product
  details page.
- **Route:** `GET /products/{id}/description` → `shop.product-description`, named
  `products.description`.
- Renders the full `products.description` plus the ordered `product_specifications`
  key/value list.

## 6. Payment on Delivery + Paystack (2% fee)

**Tables:** `payment_gateways`, `payments`, `orders`, `settings`.

- **Checkout payment options:** `cod` (Payment on Delivery) and `paystack`.
- Paystack fee = `payment_gateways.fee_percent` (`2.00`), added to `orders.payment_fee`
  and reflected in `orders.total`; `payments.fee` records the actual fee.
- Admin **Payment Gateway** page (`admin.payment-gateway`) edits enablement, keys, and
  fee percent.

## 7. Add & save delivery details

**Tables:** `addresses` (saved), `orders.delivery_*` (checkout snapshot).

- **Path:** `GET /user/addresses` → `user.addresses` (manage saved delivery details).
  Add/edit/delete and "Set as Default" operate on `addresses`.
- At checkout the selected `addresses` row is copied into `orders.delivery_*` so the
  order is immutable to later address edits.

## 8. Track orders with moving icons

**Tables:** `order_statuses`, `order_status_histories`, `orders`, `order_shipments`.

- **Path:** from the user's **My Purchases** page, a "Track Order" action opens the
  tracking view.
- **Route:** `GET /user/purchases/{order}/track` → `user.track-order`, named
  `user.trackOrder` (accepts `order_number` or order id).
- The view renders the ordered `order_statuses` timeline, animates a moving icon from
  the previous step to `orders.status`, and shows timestamps from
  `order_status_histories`.

## Route additions (summary)

```php
// Product description & specifications
Route::get('/products/{id}/description', ...)->name('products.description');

// Delivery details (saved addresses)
Route::get('/user/addresses', ...)->name('user.addresses');
Route::post('/user/addresses', ...)->name('user.addresses.store');
Route::put('/user/addresses/{address}', ...)->name('user.addresses.update');
Route::delete('/user/addresses/{address}', ...)->name('user.addresses.destroy');

// Order tracking
Route::get('/user/purchases/{order}/track', ...)->name('user.trackOrder');

// Trading
Route::get('/trade', ...)->name('trade.index');
Route::get('/trade/create', ...)->name('trade.create');
Route::post('/trade', ...)->name('trade.store');
Route::get('/trade/{listing}', ...)->name('trade.show');
Route::post('/trade/{listing}/offers', ...)->name('trade.offers.store');
```

> The existing `routes/web.php` uses `Route::view(...)` placeholders. These additions will
> become real controller-backed routes during implementation.

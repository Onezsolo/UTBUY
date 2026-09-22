<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'category_id', 'name', 'slug', 'sku', 'short_description', 'description',
        'unit', 'price', 'sale_price', 'cost_price', 'stock_quantity', 'max_stock',
        'image', 'is_active', 'is_featured', 'rating_avg', 'rating_count',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'sale_price' => 'decimal:2',
            'cost_price' => 'decimal:2',
            'rating_avg' => 'decimal:2',
            'stock_quantity' => 'integer',
            'max_stock' => 'integer',
            'rating_count' => 'integer',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}

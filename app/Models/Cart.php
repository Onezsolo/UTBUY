<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    protected $fillable = [
        'user_id', 'session_id',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function current(): self
    {
        if (auth()->check()) {
            return static::firstOrCreate(['user_id' => auth()->id()]);
        }

        return static::firstOrCreate(['session_id' => session()->getId()]);
    }

    public static function totalItems(): int
    {
        $cart = auth()->check()
            ? static::where('user_id', auth()->id())->first()
            : static::where('session_id', session()->getId())->first();

        return $cart ? (int) $cart->items()->sum('quantity') : 0;
    }
}

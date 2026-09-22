<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentGateway extends Model
{
    protected $fillable = [
        'code', 'name', 'is_enabled', 'fee_percent', 'config', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_enabled' => 'boolean',
            'fee_percent' => 'decimal:2',
            'config' => 'array',
        ];
    }
}

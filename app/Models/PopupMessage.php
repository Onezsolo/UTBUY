<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PopupMessage extends Model
{
    protected $fillable = [
        'title', 'message', 'is_active', 'display_mode', 'start_date', 'end_date',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'start_date' => 'datetime',
            'end_date' => 'datetime',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function ($query) {
                $query->where('display_mode', 'every_login')
                    ->orWhere(function ($query) {
                        $query->where('display_mode', 'scheduled')
                            ->whereNotNull('start_date')
                            ->whereNotNull('end_date')
                            ->where('start_date', '<=', now())
                            ->where('end_date', '>=', now());
                    });
            })
            ->latest();
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug', 'name', 'tagline',
        'price_cents', 'currency', 'stripe_price_id',
        'vehicle_limit', 'features',
        'is_featured', 'is_active', 'sort_order',
    ];

    protected $casts = [
        'features' => 'array',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function priceFormatted(): string
    {
        return '$'.number_format($this->price_cents / 100, 0);
    }
}

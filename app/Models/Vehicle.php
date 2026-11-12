<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'dealership_id', 'created_by', 'vin', 'stock_number',
        'year', 'make', 'model', 'trim', 'exterior_color',
        'status', 'listed_at',
    ];

    protected $casts = [
        'listed_at' => 'datetime',
        'year' => 'integer',
    ];

    public function dealership(): BelongsTo
    {
        return $this->belongsTo(Dealership::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function photos(): HasMany
    {
        return $this->hasMany(Photo::class);
    }

    public function displayName(): string
    {
        return trim(sprintf('%s %s %s %s', $this->year, $this->make, $this->model, $this->trim));
    }
}

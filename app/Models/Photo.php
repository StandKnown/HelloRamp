<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Photo extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';
    public const STATUS_PROCESSING = 'processing';
    public const STATUS_READY = 'ready';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_FAILED = 'failed';

    protected $fillable = [
        'vehicle_id', 'uploaded_by', 'angle',
        'original_path', 'enhanced_path', 'thumbnail_path',
        'status', 'provider', 'revision',
        'metadata', 'notes', 'reviewed', 'reviewed_by', 'processed_at',
    ];

    protected $casts = [
        'metadata' => 'array',
        'reviewed' => 'boolean',
        'processed_at' => 'datetime',
    ];

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function isReady(): bool
    {
        return in_array($this->status, [self::STATUS_READY, self::STATUS_APPROVED], true);
    }
}

<?php

namespace App\Jobs;

use App\Models\Photo;
use App\Services\PhotoEnhancement\PhotoEnhancer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class ProcessPhotoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 2;

    public function __construct(public Photo $photo)
    {
    }

    public function handle(PhotoEnhancer $enhancer): void
    {
        $this->photo->update(['status' => Photo::STATUS_PROCESSING]);

        try {
            $result = $enhancer->enhance($this->photo);

            $this->photo->update([
                'enhanced_path' => $result->enhancedPath,
                'thumbnail_path' => $result->thumbnailPath,
                'provider' => $enhancer->name(),
                'metadata' => array_merge($this->photo->metadata ?? [], $result->metadata),
                'status' => Photo::STATUS_READY,
                'processed_at' => now(),
            ]);
        } catch (Throwable $e) {
            $this->photo->update([
                'status' => Photo::STATUS_FAILED,
                'notes' => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}

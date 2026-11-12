<?php

namespace App\Services\PhotoEnhancement;

use App\Models\Photo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Deterministic no-op enhancer used in dev, tests, and until a real
 * AI provider (Replicate, Stability, bespoke model) is wired in.
 * Copies the original to enhanced/ and records a fake reflection-cleanup
 * flag in metadata so the UI can render the "revisit" / before-after states.
 */
class StubPhotoEnhancer implements PhotoEnhancer
{
    public function __construct(private string $disk = 'public')
    {
    }

    public function enhance(Photo $photo): EnhancementResult
    {
        $enhancedPath = 'enhanced/'.Str::uuid().'.jpg';

        if (Storage::disk($this->disk)->exists($photo->original_path)) {
            Storage::disk($this->disk)->copy($photo->original_path, $enhancedPath);
        } else {
            Storage::disk($this->disk)->put($enhancedPath, '');
        }

        return new EnhancementResult(
            enhancedPath: $enhancedPath,
            thumbnailPath: null,
            metadata: [
                'provider' => $this->name(),
                'background_removed' => true,
                'reflection_cleanup' => true,
                'enhanced_at' => now()->toIso8601String(),
            ],
        );
    }

    public function name(): string
    {
        return 'stub';
    }
}

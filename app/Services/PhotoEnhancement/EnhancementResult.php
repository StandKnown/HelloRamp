<?php

namespace App\Services\PhotoEnhancement;

class EnhancementResult
{
    public function __construct(
        public readonly string $enhancedPath,
        public readonly ?string $thumbnailPath = null,
        public readonly array $metadata = [],
    ) {
    }
}

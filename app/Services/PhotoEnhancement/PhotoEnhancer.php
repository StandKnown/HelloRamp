<?php

namespace App\Services\PhotoEnhancement;

use App\Models\Photo;

interface PhotoEnhancer
{
    public function enhance(Photo $photo): EnhancementResult;

    public function name(): string;
}

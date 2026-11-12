<?php

namespace App\Providers;

use App\Services\PhotoEnhancement\PhotoEnhancer;
use App\Services\PhotoEnhancement\StubPhotoEnhancer;
use Illuminate\Support\ServiceProvider;

class PhotoEnhancementServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(PhotoEnhancer::class, function () {
            $driver = config('photo.driver', 'stub');

            return match ($driver) {
                'stub' => new StubPhotoEnhancer(config('photo.disk', 'public')),
                default => throw new \RuntimeException("Unknown photo driver: {$driver}"),
            };
        });
    }
}

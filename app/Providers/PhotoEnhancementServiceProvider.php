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
                'local_rembg' => new \App\Services\PhotoEnhancement\LocalRembgPhotoEnhancer(
                    python: config('photo.local_rembg.python'),
                    script: config('photo.local_rembg.script'),
                    backdrop: config('photo.local_rembg.backdrop'),
                    model: config('photo.local_rembg.model'),
                    disk: config('photo.disk', 'public'),
                    timeout: config('photo.local_rembg.timeout'),
                ),
                default => throw new \RuntimeException("Unknown photo driver: {$driver}"),
            };
        });
    }
}

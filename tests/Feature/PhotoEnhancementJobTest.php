<?php

use App\Jobs\ProcessPhotoJob;
use App\Models\Dealership;
use App\Models\Photo;
use App\Models\Vehicle;
use App\Services\PhotoEnhancement\StubPhotoEnhancer;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('public');
});

it('processes a photo via the stub enhancer', function () {
    $dealer = Dealership::factory()->create();
    $vehicle = Vehicle::factory()->for($dealer)->create();

    $file = UploadedFile::fake()->image('o.jpg');
    $path = $file->storeAs('originals', 'o.jpg', 'public');

    $photo = Photo::factory()->for($vehicle)->create(['original_path' => $path, 'status' => Photo::STATUS_PENDING]);

    (new ProcessPhotoJob($photo))->handle(new StubPhotoEnhancer('public'));

    $photo->refresh();
    expect($photo->status)->toBe(Photo::STATUS_READY)
        ->and($photo->enhanced_path)->not->toBeNull()
        ->and($photo->provider)->toBe('stub')
        ->and($photo->metadata['reflection_cleanup'] ?? null)->toBeTrue();

    Storage::disk('public')->assertExists($photo->enhanced_path);
});

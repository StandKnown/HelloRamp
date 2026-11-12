<?php

use App\Jobs\ProcessPhotoJob;
use App\Models\Dealership;
use App\Models\Photo;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Storage;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\post;

beforeEach(function () {
    Storage::fake('public');
    $this->dealership = Dealership::factory()->create();
    $this->user = User::factory()->create(['dealership_id' => $this->dealership->id]);
    $this->vehicle = Vehicle::factory()->for($this->dealership)->create();
});

it('uploads photos to a vehicle and queues processing', function () {
    Bus::fake();
    actingAs($this->user);

    post(route('vehicles.photos.store', $this->vehicle), [
        'angle' => 'front',
        'photos' => [UploadedFile::fake()->image('front.jpg', 1200, 800)],
    ])->assertRedirect(route('vehicles.show', $this->vehicle));

    expect(Photo::count())->toBe(1);
    $photo = Photo::first();
    expect($photo->angle)->toBe('front')->and($photo->status)->toBe(Photo::STATUS_PENDING);

    Bus::assertDispatched(ProcessPhotoJob::class);
});

it('blocks uploads for vehicles the user does not own', function () {
    $otherDealership = Dealership::factory()->create();
    $otherVehicle = Vehicle::factory()->for($otherDealership)->create();
    actingAs($this->user);

    post(route('vehicles.photos.store', $otherVehicle), [
        'photos' => [UploadedFile::fake()->image('x.jpg')],
    ])->assertForbidden();
});

it('bumps revision when revisit is requested', function () {
    Bus::fake();
    actingAs($this->user);
    $photo = Photo::factory()->ready()->for($this->vehicle)->create(['revision' => 2]);

    post(route('photos.revisit', $photo))->assertRedirect();

    expect($photo->fresh()->revision)->toBe(3)
        ->and($photo->fresh()->status)->toBe(Photo::STATUS_PENDING);

    Bus::assertDispatched(ProcessPhotoJob::class);
});

<?php

namespace Database\Factories;

use App\Models\Photo;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Photo> */
class PhotoFactory extends Factory
{
    protected $model = Photo::class;

    public function definition(): array
    {
        return [
            'vehicle_id' => Vehicle::factory(),
            'uploaded_by' => null,
            'angle' => fake()->randomElement(['front', 'rear', 'driver-side', 'passenger-side', 'interior-front', 'interior-rear']),
            'original_path' => 'originals/'.fake()->uuid().'.jpg',
            'enhanced_path' => null,
            'thumbnail_path' => null,
            'status' => Photo::STATUS_PENDING,
            'provider' => null,
            'revision' => 1,
            'metadata' => null,
            'notes' => null,
            'reviewed' => false,
        ];
    }

    public function ready(): static
    {
        return $this->state(fn (array $a) => [
            'status' => Photo::STATUS_READY,
            'enhanced_path' => 'enhanced/'.fake()->uuid().'.jpg',
            'processed_at' => now(),
            'provider' => 'stub',
        ]);
    }
}

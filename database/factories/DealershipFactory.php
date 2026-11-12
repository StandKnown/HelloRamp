<?php

namespace Database\Factories;

use App\Models\Dealership;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/** @extends Factory<Dealership> */
class DealershipFactory extends Factory
{
    protected $model = Dealership::class;

    public function definition(): array
    {
        $name = fake()->company().' Motors';

        return [
            'name' => $name,
            'slug' => Str::slug($name).'-'.Str::random(5),
            'city' => fake()->city(),
            'state' => fake()->stateAbbr(),
            'country' => 'US',
            'website' => fake()->url(),
            'logo_path' => null,
        ];
    }
}

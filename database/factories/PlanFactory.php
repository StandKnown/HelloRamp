<?php

namespace Database\Factories;

use App\Models\Plan;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/** @extends Factory<Plan> */
class PlanFactory extends Factory
{
    protected $model = Plan::class;

    public function definition(): array
    {
        $name = fake()->word();

        return [
            'slug' => Str::slug($name),
            'name' => Str::title($name),
            'tagline' => fake()->sentence(6),
            'price_cents' => fake()->numberBetween(5000, 50000),
            'currency' => 'USD',
            'stripe_price_id' => null,
            'vehicle_limit' => fake()->numberBetween(10, 500),
            'features' => ['Feature A', 'Feature B', 'Feature C'],
            'is_featured' => false,
            'is_active' => true,
            'sort_order' => 0,
        ];
    }
}

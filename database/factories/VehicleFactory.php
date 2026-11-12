<?php

namespace Database\Factories;

use App\Models\Dealership;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Vehicle> */
class VehicleFactory extends Factory
{
    protected $model = Vehicle::class;

    public function definition(): array
    {
        $makes = ['Toyota', 'Honda', 'Ford', 'BMW', 'Mercedes-Benz', 'Audi', 'Tesla', 'Chevrolet'];
        $models = ['Camry', 'Civic', 'F-150', 'X5', 'C-Class', 'A4', 'Model 3', 'Silverado'];
        $colors = ['Black', 'White', 'Silver', 'Red', 'Blue', 'Gray'];

        return [
            'dealership_id' => Dealership::factory(),
            'created_by' => null,
            'vin' => strtoupper(fake()->bothify('?#?#?#?#?#?#?#?#?')),
            'stock_number' => 'SK-'.fake()->numberBetween(1000, 9999),
            'year' => fake()->numberBetween(2018, 2026),
            'make' => fake()->randomElement($makes),
            'model' => fake()->randomElement($models),
            'trim' => fake()->randomElement(['Base', 'Sport', 'Limited', 'Premium']),
            'exterior_color' => fake()->randomElement($colors),
            'status' => 'draft',
        ];
    }
}

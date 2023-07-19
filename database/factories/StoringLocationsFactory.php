<?php

namespace Database\Factories;

use App\Models\Branch;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StoringLocations>
 */
class StoringLocationsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'main_section' => fake()->word(),
            'section'=> fake()->numberBetween(1,200),
            'branch_id'=> Branch::factory()->create()->id,
            'available_quantity'=>fake()->numberBetween(100,1000),
            'unavailable_quantity'=>fake()->numberBetween(100,1000),
        ];
    }
}

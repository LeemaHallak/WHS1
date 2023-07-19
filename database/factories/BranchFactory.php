<?php

namespace Database\Factories;

use App\Models\Address;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Branch>
 */
class BranchFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'address_id' => Address::factory()->create()->id,
            'phone_number' => fake()->phoneNumber(),
            'space' => fake()->numberBetween(1000.0,5000.0),
            'sectionMaxCapacity'=> fake()->numberBetween(100,1000),
            'company_register'=> fake()->numberBetween(10000,90000),
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}

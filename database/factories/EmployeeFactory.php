<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Address;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'employee_name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone_number' =>  fake()->phoneNumber(),
            'address_id' => Address::factory()->create()->id,
            'branch_id'=> fake()->numberBetween(1,5),
            'salary'=>fake()-> numberBetween(1000.0,2000.0),
            'photo'=>fake()->text(),
            'position'=>fake()->word(),
            'is_manager' => fake()->boolean(),
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}

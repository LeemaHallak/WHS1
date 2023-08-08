<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Address;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProducingCompany>
 */
class ProducingCompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'company_code'=>fake()->numberBetween(1000,10000),
            'company_name'=> fake()->name(),
            'address_id'=>fake()->numberBetween(1,10),
            'phone_number'=>fake()->phoneNumber(),
            'email'=>fake()->email(),
            'company_register'=> fake()->numberBetween(10000,90000),
            'industry_register'=> fake()->numberBetween(10000,90000),
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}

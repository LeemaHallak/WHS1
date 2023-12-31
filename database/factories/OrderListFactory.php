<?php

namespace Database\Factories;

use App\Models\Branch;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderList>
 */
class OrderListFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_id'=>fake()->numberBetween(1,10),
            'branch_id'=>fake()->numberBetween(1,10),
            'order_quantity'=>fake()->randomNumber(),
            'order_cost'=> fake()->numberBetween(1000.0,10000.0),
            'order_earnings'=> fake()->numberBetween(1000.0,10000.0),
            'orderd'=>fake()->boolean(),
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}

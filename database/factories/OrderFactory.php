<?php

namespace Database\Factories;

use App\Models\OrderList;
use App\Models\Shipment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    
    public function definition(): array
    {
        return [
            'OrderList_id'=> fake()->numberBetween(1,10),
            'Shipment_id'=>fake()->numberBetween(1,10),
            'order_date'=>fake()->date(),
            'ready'=>fake()->boolean(),
            'arrived'=>fake()->boolean(),
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}

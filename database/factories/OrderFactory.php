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
            'OrderList_id'=> OrderList::factory()->create()->id,
            'Shipment_id'=>Shipment::factory()->create()->id,
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

<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Address;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Shipment>
 */
class ShipmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'I\O'=>fake()->randomElement(['In','Out']),
            'shipping_company'=>fake()->word(),
            'SourceAddress_id'=>Address::factory()->create()->id,
            'DestinationAddress_id'=>Address::factory()->create()->id,
            'shipment_date'=>fake()->date(),
            'shipment_type'=>fake()->randomElement(['air','sea','overland']),
            'max_quantity'=>fake()->randomNumber(),
            'shipment_quantity'=>fake()->randomNumber(),
            'shipment_cost'=>fake()->numberBetween(10000.0,1000000.0),
            'shipProducts_cost'=>fake()->numberBetween(10000.0,1000000.0),
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

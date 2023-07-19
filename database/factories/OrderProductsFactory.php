<?php

namespace Database\Factories;

use App\Models\BranchesProducts;
use App\Models\OrderList;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderProducts>
 */
class OrderProductsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'BranchesProducts_id'=> BranchesProducts::factory()->create()->id,
            'quantity'=>fake()->numberBetween(100,1000),
            'total_price'=>fake()->numberBetween(500.0,9000.0),
            'OrderList_id'=> OrderList::factory()->create()->id,
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}

<?php

namespace Database\Factories;

use App\Models\Branch;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BranchesProducts>
 */
class BranchesProductsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id'=>fake()->numberBetween(1,10),
            'branch_id'=>fake()->numberBetween(1,10),
            'Supplier_id'=>fake()->numberBetween(1,10),
            'in_quantity'=>fake()->numberBetween(100,1000),
            'recent_quantity'=>fake()->numberBetween(100,1000),
            'date_in'=>fake()->date(),
            'prod_date'=>fake()->date(),
            'exp_date'=>fake()->date(),
            'purchase_num'=>fake()->numberBetween(1000,10000),
            'buying_cost'=>fake()->numberBetween(100.0,1000.0),
            'price'=>fake()->numberBetween(100.0,1000.0),
            
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}

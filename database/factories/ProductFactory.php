<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\ProducingCompany;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Products>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'UPC_code'=>fake()->numberBetween(1000,10000),
            'product_code'=>fake()->numberBetween(1000,10000),
            'product_name'=> fake()->word(),
            'Category_id'=> Category::factory()->create()->id,
            'description'=>fake()->text(),
            'ProducingCompany_id'=>ProducingCompany::factory()->create()->id,
            'Supplier_id'=>User::factory()->create()->id,
            'image'=>fake()->text(),
            'weight'=>fake()->numberBetween(10.0,1000.0),
            'WUnit'=>fake()->randomElement(['KG', 'G']),
            'size'=>fake()->numberBetween(10.0,1000.0),
            'SUnit'=>fake()->randomElement(['M', 'CM']),
            'box_quantity'=>fake()->numberBetween(10,1000),
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}

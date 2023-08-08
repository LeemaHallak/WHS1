<?php

namespace Database\Factories;

use App\Models\BranchesProducts;
use App\Models\StoringLocations;
use GuzzleHttp\Promise\Create;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BpSl>
 */
class BpSlFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'BranchesProduct_id'=>fake()->numberBetween(1,10),
            'StoringLocation_id'=>fake()->numberBetween(1,10),
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\Branch;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Financial>
 */
class FinancialFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'month'=>fake()->randomElement([
                'JAN','FEB','MAR','APR','MAY','JUN','JUL','AUG','SEP','OCT','NOV','DEC'
            ]),
            'outgoings'=>fake()->numberBetween(10000.0,1000000.0),
            'incomings'=>fake()->numberBetween(10000.0,1000000.0),
            'total_salaries'=>fake()->numberBetween(10000.0,1000000.0),
            'total_costs'=>fake()->numberBetween(10000.0,1000000.0),
            'earnings'=>fake()->numberBetween(10000.0,1000000.0),
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}

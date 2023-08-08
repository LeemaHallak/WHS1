<?php

namespace Database\Factories;

use App\Models\BranchesEquipments;
use App\Models\Equipment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EquipmentFix>
 */
class EquipmentFixFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'equipment_id'=>fake()->numberBetween(1,10),
            'damage_date'=>fake()->date(),
            'fix_date'=>fake()->date(),
            'fixing_cost'=>fake()->numberBetween(100.0,1000.0),
        ];
    }
}

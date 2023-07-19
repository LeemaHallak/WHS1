<?php

namespace Database\Factories;

use App\Models\Branch;
use App\Models\Employee;
use App\Models\Equipment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BranchesEquipments>
 */
class BranchesEquipmentsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'employee_id'=>Employee::factory()->create()->id,
            'branch_id'=>Branch::factory()->create()->id,
            'equipment_id'=>Equipment::factory()->create()->id,
            'quantity'=>fake()->numberBetween(1,100),
            'cost'=>fake()->numberBetween(100.0,1000.0),
            'date_in'=>fake()->date(),
            'available'=>fake()->numberBetween(100,1000),
        ];
    }
}

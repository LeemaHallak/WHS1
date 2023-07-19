<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {   
        \App\Models\Role::factory()->create(['role' => 'WareHouse Keeper']);
        \App\Models\Role::factory()->create(['role' => 'Assistant']);
        \App\Models\Role::factory()->create(['role' => 'general manager']);
        \App\Models\Role::factory()->create(['role' => 'normal']);
        \App\Models\Unit::factory()->create(['unit_name' => 'KG']);
        \App\Models\Unit::factory()->create(['unit_name' => 'L']);
        \App\Models\Country::factory(10)->create();
        \App\Models\City::factory(10)->create();
        \App\Models\Region::factory(10)->create();
        \App\Models\Address::factory(10)->create();
        \App\Models\User::factory(10)->create();
        \App\Models\Branch::factory(10)->create();
        \App\Models\Employee::factory(10)->create();
        \App\Models\Manager::factory(10)->create();
        \App\Models\Category::factory()->create(['id' => 1,'parent_id' => null, 'category_name' => 'milk' ]);
        \App\Models\Category::factory()->create(['id' => 2,'parent_id' => null, 'category_name' => 'veg' ]);
        \App\Models\Category::factory()->create(['id' => 3,'parent_id' => null, 'category_name' =>'fruits' ]);
        \App\Models\Category::factory()->create(['id' => 4,'parent_id' => null, 'category_name' =>'juice' ]);
        \App\Models\Category::factory()->create(['id' => 5,'parent_id' => null, 'category_name' =>'meat' ]);
        \App\Models\Category::factory(10)->create();
        \App\Models\ProducingCompany::factory(10)->create();
        \App\Models\Product::factory(10)->create();
        \App\Models\BranchesProducts::factory(10)->create();
        \App\Models\Shipment::factory(10)->create();
        \App\Models\OrderList::factory(10)->create();
        \App\Models\Financial::factory(10)->create();  
        \App\Models\BpSl::factory(10)->create();
        \App\Models\Equipment::factory(10)->create();
        \App\Models\BranchesEquipments::factory(10)->create();
        \App\Models\EquipmentFix::factory(10)->create();
        \App\Models\StoringLocations::factory(10)->create();
        \App\Models\OrderList::factory(10)->create();
        \App\Models\OrderProducts::factory(10)->create();
        \App\Models\Order::factory(10)->create();


        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}

<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\BpSl;
use App\Models\City;
use App\Models\Role;
use App\Models\Unit;
use App\Models\User;
use App\Models\Order;
use App\Models\Branch;
use App\Models\Region;
use App\Models\Address;
use App\Models\Country;
use App\Models\Manager;
use App\Models\Product;
use App\Models\Category;
use App\Models\Employee;
use App\Models\Shipment;
use App\Models\Equipment;
use App\Models\Financial;
use App\Models\OrderList;
use App\Models\EquipmentFix;
use App\Models\OrderProducts;
use Illuminate\Database\Seeder;
use App\Models\BranchesProducts;
use App\Models\ProducingCompany;
use App\Models\StoringLocations;
use App\Models\BranchesEquipments;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {   
        Role::factory()->create(['role' => 'Assistant']);
        Role::factory()->create(['role' => 'WareHouse Keeper']);
        Role::factory()->create(['role' => 'general manager']);
        Role::factory()->create(['role' => 'normal']);
        Unit::factory()->create(['unit_name' => 'KG']);
        Unit::factory()->create(['unit_name' => 'L']);
        Unit::factory()->create(['unit_name' => 'G']);
        Unit::factory()->create(['unit_name' => 'M']);
        Unit::factory()->create(['unit_name' => 'CM']);
        Country::factory(10)->create();
        City::factory(10)->create();
        Region::factory(10)->create();
        Address::factory(10)->create();
        User::factory(10)->create();
        Branch::factory(10)->create();
        Employee::factory(10)->create();
        Manager::factory(10)->create();
        Category::factory()->create(['id' => 1,'parent_id' => null, 'category_name' => 'milk' ]);
        Category::factory()->create(['id' => 2,'parent_id' => null, 'category_name' => 'veg' ]);
        Category::factory()->create(['id' => 3,'parent_id' => null, 'category_name' =>'fruits' ]);
        Category::factory()->create(['id' => 4,'parent_id' => null, 'category_name' =>'juice' ]);
        Category::factory()->create(['id' => 5,'parent_id' => null, 'category_name' =>'meat' ]);
        Category::factory(10)->create();
        ProducingCompany::factory(10)->create();
        Product::factory(10)->create();
        BranchesProducts::factory(10)->create();
        Shipment::factory(10)->create();
        OrderList::factory(10)->create();
        Financial::factory(10)->create();  
        Equipment::factory(10)->create();
        BranchesEquipments::factory(10)->create();
        EquipmentFix::factory(10)->create();
        StoringLocations::factory(10)->create();
        OrderList::factory(10)->create();
        BpSl::factory(10)->create();
        OrderProducts::factory(10)->create();
        Order::factory(10)->create();


        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}

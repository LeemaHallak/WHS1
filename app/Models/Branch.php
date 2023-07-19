<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = [
        'address_id',
        'phone_number',
        'space',
        'sectionMaxCapacity',
        'company_register',
    ];

    public function products()
    {
        return $this->hasMany(BranchesProducts::class, 'branch_id');
    }
    public function costs()
    {
        return $this->hasMany(Cost::class, 'branch_id');
    }
    public function BranchesCats()
    {
        return $this->hasMany(BranchesCategories::class, 'branch_id');
    }

    // public function financial()
    // {
    //     return $this->hasOne(Financial::class, 'branch_id');
    // }

    public function employees()
    {
        return $this->hasMany(Employee::class, 'branch_id');
    }
    
    public function branches_equipment()
    {
        return $this->hasMany(BranchesEquipments::class, 'branch_id');
    }

    public function storing_locations()
    {
        return $this->hasMany(StoringLocations::class, 'branch_id');
    }

    public function address()
    {
        return $this->belongsTo(Address::class, 'address_id');
    }

    public function innerTransactions()
    {
        return $this->hasMany(InnerTransaction::class);
    }

    public function OrderLists()
    {
        return $this->hasMany(OrderList::class, 'branch_id');
    }

    public function managers(): HasManyThrough
    {
        return $this->hasManyThrough(
            Manager::class,
            Employee::class,
            'branch_id', // Foreign key on the employees table...
            'employee_id', // Foreign key on the managers table...
            'id', // Local key on the branches table...
            'id' // Local key on the employees table...
        );
    }

    public function equipments_fixing(): HasManyThrough
    {
        return $this->hasManyThrough(
            EquipmentFix::class,
            BranchesEquipments::class,
            'branch_id', // Foreign key on the equipments table...
            'equipment_id', // Foreign key on the equipmentFixes table...
            'id', // Local key on the branches table...
            'id' // Local key on the employees table...
        );
    }

    public function orders(): HasManyThrough
    {
        return $this->hasManyThrough(
            Order::class,
            OrderList::class,
            'branch_id', // Foreign key on the orderlits table...
            'OrderList_id', // Foreign key on the orders table...
            'id', // Local key on the branches table...
            'id' // Local key on the employees table...
        );
    }

    public function equipments()
    {
        return $this->belongsToMany(Equipment::class, 'branch_equipment');
    }
//->withPivot('branch_id')
}

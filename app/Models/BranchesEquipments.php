<?php

namespace App\Models;

use Cjmellor\Approval\Concerns\MustBeApproved;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BranchesEquipments extends Model
{
    use HasFactory;
    protected $guarded = [
        'id',
    ];

    public function branches()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
    public function employees()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
    public function equipment_fixes()
    {
        return $this->hasMany(EquipmentFix::class, 'equipment_id');
    }
    public function equipments()
    {
        return $this->belongsTo(Equipment::class, 'equipment_id');
    }

    protected $table = 'branch_equipment';
}

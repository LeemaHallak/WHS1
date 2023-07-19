<?php

namespace App\Models;

use Cjmellor\Approval\Concerns\MustBeApproved;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    use HasFactory;
    protected $guarded = [
        'id'
        
    ];
    public function branches_equipments()
    {
        return $this->hasMany(BranchesEquipments::class, 'branch_id');
    }
    public function branches()
    {
        return $this->belongsToMany(Branch::class, 'branch_equipment');
    }
    
    //protected $table = 'equipments';
}

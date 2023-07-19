<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class Employee extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];
    

    public function manager()
    {
        return $this->hasOne(Manager::class, 'employee_id');
    }
    public function branches_equipment()
    {
        return $this->hasMany(BranchesEquipments::class, 'employee_id');
    }

    public function branches()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
    public function addresses()
    {
        return $this->belongsTo(Address::class, 'address_id');
    }
    public function scopeManagerWithRoleIds($query, $roleIds)
    {
        return $query->where('is_manager', true)
                    ->whereIn('role_id', $roleIds);
    }
    
    


}

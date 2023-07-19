<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipmentFix extends Model
{
    use HasFactory;
    protected $guarded = [
        'id'
    ];
    public function branches_equipments()
    {
        return $this->belongsTo(BranchesEquipments::class, 'equipment_id');
    }
}

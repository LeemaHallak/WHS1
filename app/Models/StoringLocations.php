<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoringLocations extends Model
{
    use HasFactory;
    protected $guarded = [
        'id'
    ];
    public function BranchProd_StoringLoc()
    {
        return $this->hasMany(BpSl::class, 'StoringLocation_id');
    }
    public function branches()
    {
        return $this->belongsTo(Branch::class,'branch_id');
    }
}

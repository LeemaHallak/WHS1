<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BpSl extends Model
{
    use HasFactory;
    protected $fillable = [
        'BranchesProduct_id',
        'StoringLocation_id'
    ];
    public function storing_locations()
    {
        return $this->belongsTo(StoringLocations::class, 'StoringLocation_id');
    }
    public function branches_products()
    {
        return $this->belongsTo(BranchesProducts::class, 'BranchesProduct_id');
    }
}

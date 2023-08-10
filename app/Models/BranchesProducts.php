<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BranchesProducts extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];

    public function suppliers()
    {
        return $this->belongsTo(User::class, 'Supplier_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function Products()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function bp_sls()
    {
        return $this->hasMany(BP_SL::class, 'branches_product_id');
    }

    public function orderProducts()
    {
        return $this->hasMany(OrderProducts::class, 'BranchesProducts_id');
    }

    public function innerTransictions()
    {
        return $this->hasMany(InnerTransaction::class, 'BranchProduct_id');
    }

    public function scopeWithWhereHas($query, $relation, $constraint)
    {
        return $query->whereHas($relation, $constraint)
        ->with([$relation => $constraint]);
    }
}

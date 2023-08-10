<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];




    public function producing_companies()
    {
        return $this->belongsTo(ProducingCompany::class, 'ProducingCompany_id');
    }

    public function WUnits()
    {
        return $this->belongsTo(Unit::class, 'WUnite_id');
    }

    public function SUnits()
    {
        return $this->belongsTo(Unit::class, 'SUnite_id');
    }

    public function categories()
    {
        return $this->belongsTo(Category::class, 'Category_id');
    }
    
    public function BranchProducts()
    {
        return $this->hasMany(BranchesProducts::class, 'product_id');
    }


}

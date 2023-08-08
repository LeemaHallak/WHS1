<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SelfReferenceTrait;
use Cjmellor\Approval\Concerns\MustBeApproved;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Category extends Model
{
    use HasFactory, SelfReferenceTrait;

    protected $fillable = [
        'parent_id',
        'category_name',
    ];


    public function BranchesCats()
    {
        return $this->hasMany(BranchesCategories::class, 'category_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }

    public function icons()
    {
        return $this->hasMany(CategoryIcon::class, 'category_id');
    }

    public function barnchesproducts(): HasManyThrough
    {
        return $this->hasManyThrough(
            BranchesProducts::class,
            Product::class,
            'Category_id', // Foreign key on the products table...
            'product_id', // Foreign key on the branches_products table...
            'id', // Local key on the branches table...
            'id' // Local key on the employees table...
        );
    }

}

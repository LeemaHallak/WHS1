<?php

namespace App\Models;

use Cjmellor\Approval\Concerns\MustBeApproved;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAssis extends Product
{
    use HasFactory, MustBeApproved;
    
    protected $table = 'branches_products';
}

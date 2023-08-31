<?php

namespace App\Models;

use Cjmellor\Approval\Concerns\MustBeApproved;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductApprove extends Product
{
    use HasFactory, MustBeApproved;
    
    protected $table = 'products';
}

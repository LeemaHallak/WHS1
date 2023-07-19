<?php

namespace App\Models;

use Cjmellor\Approval\Concerns\MustBeApproved;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderClient extends Order
{
    use HasFactory, MustBeApproved;
    
    protected $table = 'orders';
}

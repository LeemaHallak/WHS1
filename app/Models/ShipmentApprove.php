<?php

namespace App\Models;

use Cjmellor\Approval\Concerns\MustBeApproved;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShipmentApprove extends Shipment
{
    use HasFactory, MustBeApproved;
    
    protected $table = 'shipments';
}

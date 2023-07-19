<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];

    public function shipments()
    {
        return $this->belongsTo(Shipment::class, 'shipment_id');
    }

    public function OrderList()
    {
        return $this->belongsTo(OrderList::class, 'OrderList_id');
    }

    public function branches()
    {
        return $this->belongsToMany(Branch::class);
    }

}

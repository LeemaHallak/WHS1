<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderList extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];

    public function orders()
    {
        return $this->hasMany(Order::class, 'OrderList_id');
    }

    public function OrderProducts()
    {
        return $this->hasMany(OrderProducts::class, 'OrderList_id');
    }

    public function customers()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function branches()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}

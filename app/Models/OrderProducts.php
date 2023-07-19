<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderProducts extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];

    public function branchesproducts()
    {
        return $this->belongsTo(BranchesProducts::class, 'BranchesProducts_id');
    }

    public function OrderList()
    {
        return $this->belongsTo(OrderList::class, 'OrderList_id');
    }


}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProducingCompany extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'ProducingCompany_id');
    }
    public function addresses()
    {
        return $this->belongsTo(Address::class, 'address_id');
    }

}

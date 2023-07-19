<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];

    public function orders()
    {
        return $this->hasMany(Order::class, 'shipment_id');
    }
    public function SourceAddresses()
    {
        return $this->belongsTo(Address::class,'SourceAddress_id');
    }
    public function DestinationAddresses()
    {
        return $this->belongsTo(Address::class,'DestinationAddress_id');
    }
}

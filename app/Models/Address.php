<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;
    protected $fillable = [
        'region_id',
        'address'
    ];
    public function regions()
    {
        return $this->belongsTo(Region::class , 'region_id');
    }
    public function employees()
    {
        return $this->hasMany(Employee::class , 'address_id');
    }
    public function branches()
    {
        return $this->hasMany(Branch::class , 'address_id');
    }
    public function customers()
    {
        return $this->hasMany(User::class , 'address_id');
    }
    public function producing_companies()
    {
        return $this->hasMany(ProducingCompany::class , 'address_id');
    }
    public function ShipSourceAddresses()
    {
        return $this->hasMany(Shipment::class , 'SourceAddress_id');
    }
    public function ShipDestinationAddresses()
    {
        return $this->hasMany(Shipment::class , 'DestinationAddress_id');
    }
}

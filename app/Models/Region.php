<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Region extends Model
{
    use HasFactory;
    protected $fillable = [
        'region',
        'city_id'
    ];

    public function addresses()
    {
        return $this->hasMany(Address::class,'region_id');
    }

    public function cities()
    {
        return $this->belongsTo(City::class,'city_id');
    }

    
}

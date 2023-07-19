<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;
    protected $fillable = [
        'country_id',
        'city'
    ];
    public function regions()
    {
        return $this->hasMany(Region::class,'city_id');
    }
    public function countries()
    {
        return $this->belongsTo(Country::class,'country_id');
    }
}

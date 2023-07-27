<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;
    protected $fillable = [
        'unit_name',
    ];
    
    public function weight()
    {
        return $this->hasMany(Product::class, 'WUnit');
    }
    public function size()
    {
        return $this->hasMany(Product::class, 'SUnit');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Financial extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];

    // public function branches()
    // {
    //     return $this->belongsTo(Branch::class, 'branch_id');
    // }

}

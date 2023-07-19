<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cost extends Model
{
    use HasFactory;
    protected $guarded = [
        'id',
    ];

    public function manager()
    {
        return $this->belongsTo(Manager::class, 'manager_id');
    }
}

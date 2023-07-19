<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InnerTransaction extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
    ];

    public function products()
    {
        return $this->belongsTo(BranchesProducts::class, 'BranchProduct_id');
    }

    public function SourceBranch()
    {
        return $this->belongsTo(Branch::class, 'SourceBranch_id');
    }

    public function DestinationBranch()
    {
        return $this->belongsTo(Branch::class, 'DestinationBranch_id');
    }

    
    protected $table = 'inner_transactions';
}

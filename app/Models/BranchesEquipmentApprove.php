<?php

namespace App\Models;

use Cjmellor\Approval\Concerns\MustBeApproved;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BranchesEquipmentApprove extends BranchesEquipment
{
    use HasFactory, MustBeApproved;
    
    protected $table = 'branch_equipment';
}

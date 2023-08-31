<?php

namespace App\Models;

use App\Traits\SelfReferenceTrait;
use Cjmellor\Approval\Concerns\MustBeApproved;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class CategoryApprove extends Category
{
    use HasFactory, SelfReferenceTrait, MustBeApproved;
    
    protected $table = 'categories';
}

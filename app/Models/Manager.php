<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;
use Illuminate\Foundation\Auth\Manager as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Znck\Eloquent\Traits\BelongsToThrough;



class Manager extends Authenticatable
{
    use HasFactory, HasApiTokens, Notifiable, BelongsToThrough;

    protected $guarded = [
        'id'
    ];
        
    protected $hidden = [
        'password',
        'remember_token'
    ];

    public function costs()
    {
        return $this->hasMany(Cost::class, 'manager_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function roles()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function branches()
    {
        return $this->belongsToThrough(Branch::class, Employee::class);
    }

    public function scopeBranch()
    {
        $managerId = Auth::id();
        return $this->find($managerId)->branches()->pluck('branches.id');
    }

    public function scopeRole()
    {
        $managerId = Auth::id();
        return $this->find($managerId)->role_id;
    }

}

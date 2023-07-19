<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;
use Illuminate\Foundation\Auth\Manager as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Manager extends Authenticatable
{
    use HasFactory, HasApiTokens, Notifiable;

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

}

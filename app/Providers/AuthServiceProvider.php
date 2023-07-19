<?php

namespace App\Providers;

use App\Models\Employee;
use App\Models\Manager;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */

    
     //[fn(Manager $manager) => $manager->role_id == 1 ,fn(Manager $manager) => $manager->role_id == 2]
    public function boot()
    {
        $this->registerPolicies();
        Gate::define('add Branch_category', fn(Manager $manager) =>$manager->role_id == 1 || $manager->role_id == 2);
        Gate::define('add Branch_InnerCat',  fn(Manager $manager) => $manager->role_id == 1 || $manager->role_id == 2);
        Gate::define('add Branch_Product', fn(Manager $manager) => $manager->role_id == 1 || $manager->role_id == 2);
        Gate::define('add Order_Product', fn(Manager $manager) => $manager->role_id == 1 || $manager->role_id == 2);
        Gate::define('add Order_List', fn(Manager $manager) => $manager->role_id == 1 || $manager->role_id == 2);
        Gate::define('add Order', fn(Manager $manager) => $manager->role_id == 1 || $manager->role_id == 2);

        Gate::define('addAK', fn(Manager $manager) => $manager->role_id == 1 || $manager->role_id == 3);
        Gate::define('addCustomer', fn(Manager $manager) => $manager->role_id == 1 || $manager->role_id == 3);

        //Passport::routes();

        Passport::tokensCan([
            'user' => 'User Type',
            'manager' => 'Manager User Type',
        ]);
    }
}

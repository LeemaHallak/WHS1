<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Builder;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
  {
//         Builder::macro('withWhereHas', fn($relation, $constraint) =>
//         $this->whereHas($relation, $constraint)->with([$relation => $constraint])
// );
    }

    
}

<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        Gate::define('admin', function ($user) {
            return ($user->role == "1" || $user->role == "2" || $user->role == "3");
        });

        Validator::extend('valid_date', function ($attribute, $value, $parameters, $validator) {
            $year = $parameters[0];
            $month = $parameters[1];
            $day = $parameters[2];

            return checkdate($month, $day, $year);
        });
    }
}

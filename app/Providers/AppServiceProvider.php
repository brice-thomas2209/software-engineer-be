<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Passport\UserRepository;

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
        // Override Laravel Passports UserRepository with our one that can utilise different guards.
        app()->bind(\Laravel\Passport\Bridge\UserRepository::class, function () {
            return app()->make(UserRepository::class);
        });
    }
}

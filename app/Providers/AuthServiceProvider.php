<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // insert custom passport routes
        Passport::routes(null, [
            'prefix' => 'api/users/oauth',
            'middleware' => [
                'oauth-users'
            ]
        ]);

        // reduce for more secure application and production
        Passport::tokensExpireIn(now()->addDay(1));
        Passport::refreshTokensExpireIn(now()->addDay(7));
    }
}

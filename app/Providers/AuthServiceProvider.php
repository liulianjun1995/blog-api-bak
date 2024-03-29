<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;
use Laravel\Passport\RouteRegistrar;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
//         'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::tokensCan([
            'blog-web' => 'blog web api',
            'blog-admin' => 'blog admin api',
        ]);

        Passport::routes(function (RouteRegistrar $router) {
            $router->forAccessTokens();
        }, ['prefix' => 'api/oauth', 'middleware' => 'passport-administrators']);

        Passport::tokensExpireIn(now()->addDays(7));

    }
}

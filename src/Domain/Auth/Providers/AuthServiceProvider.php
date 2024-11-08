<?php

namespace Domain\Auth\Providers;

// use Illuminate\Services\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];


    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Passport::hashClientSecrets();

        Passport::tokensCan([
            'github_id' => 'Access to User avatar',
//            'check-status' => 'Check order status',
        ]);
        //
    }

    public function register()
    {
        $this->app->register(
            ActionsServiceProvider::class
        );
        parent::register();
    }
}

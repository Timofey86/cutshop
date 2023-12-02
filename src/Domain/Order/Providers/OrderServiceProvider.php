<?php

namespace Domain\Order\Providers;

// use Illuminate\Services\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class OrderServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        //
    }

    public function register()
    {
        $this->app->register(
            PaymentServiceProvider::class
        );

        $this->app->register(
            ActionsServiceProvider::class
        );
        parent::register();
    }
}

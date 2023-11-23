<?php

namespace App\Providers;

use Domain\Auth\Providers\AuthServiceProvider;
use Domain\Cart\Providers\CartServiceProvider;
use Domain\Product\Providers\ProductServiceProvider;
use Domain\Catalog\Providers\CatalogServiceProvider;
use Illuminate\Support\ServiceProvider;

class DomainServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->register(
            CartServiceProvider::class
        );

        $this->app->register(
            AuthServiceProvider::class
        );

        $this->app->register(
            CatalogServiceProvider::class
        );

        $this->app->register(
            ProductServiceProvider::class
        );
    }

}

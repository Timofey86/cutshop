<?php

declare(strict_types=1);

namespace App\Providers;

use App\MoonShine\Resources\BrandResource;
use App\MoonShine\Resources\CategoryResource;
use App\MoonShine\Resources\OptionResource;
use App\MoonShine\Resources\ProductResource;
use App\MoonShine\Resources\PropertyResource;
use Domain\Catalog\Models\Brand;
use MoonShine\Providers\MoonShineApplicationServiceProvider;
use MoonShine\MoonShine;
use MoonShine\Menu\MenuGroup;
use MoonShine\Menu\MenuItem;
use MoonShine\Resources\MoonShineUserResource;
use MoonShine\Resources\MoonShineUserRoleResource;

class MoonShineServiceProvider extends MoonShineApplicationServiceProvider
{
    protected function resources(): array
    {
        return [];
    }

    protected function pages(): array
    {
        return [];
    }

    protected function menu(): array
    {
        return [
            MenuGroup::make(static fn() => __('moonshine::ui.resource.system'), [
                MenuItem::make(
                    static fn() => __('moonshine::ui.resource.admins_title'),
                    new MoonShineUserResource()
                ),
                MenuItem::make(
                    static fn() => __('moonshine::ui.resource.role_title'),
                    new MoonShineUserRoleResource()
                ),
            ]),

            MenuGroup::make('Products', [
                MenuItem::make('Products',
                    new ProductResource()
                ),
                MenuItem::make('Category',
                    new CategoryResource()
                ),
                MenuItem::make('Brands',
                    new BrandResource()
                ),
                MenuItem::make('Properties',
                    new PropertyResource()
                ),
                MenuItem::make('Options',
                    new OptionResource()
                ),
            ]),

            MenuItem::make('Documentation', 'https://moonshine-laravel.com')
                ->badge(fn() => 'Check'),
        ];
    }

    /**
     * @return array{css: string, colors: array, darkColors: array}
     */
    protected function theme(): array
    {
        return [];
    }
}

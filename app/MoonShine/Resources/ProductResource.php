<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Domain\Product\Models\Product;
use Illuminate\Database\Eloquent\Model;
use MoonShine\Decorations\Column;
use MoonShine\Decorations\Grid;
use MoonShine\Decorations\Tab;
use MoonShine\Fields\Image;
use MoonShine\Fields\Relationships\BelongsTo;
use MoonShine\Fields\Relationships\BelongsToMany;
use MoonShine\Fields\Text;
use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Fields\ID;

class ProductResource extends ModelResource
{
    protected string $model = Product::class;

    protected string $title = 'Products';

    protected array $with = [
        'brand',
        'categories',
        'properties',
        'optionValues'
    ];

    public function fields(): array
    {
        return [
            Grid::make([
                Column::make([
                    Block::make([
                        ID::make()->sortable(),
                        Text::make('Title'),
                        BelongsTo::make('Brand', resource: new BrandResource()),
                        Text::make('Price'),
                        Image::make('Thumbnail')
                            ->disk('public')
                            ->dir('images/products'),
                    ])
                ]),
                Column::make([
                    Block::make([
                        BelongsToMany::make('Categories', resource: new CategoryResource())
                            ->hideOnIndex(),
                        BelongsToMany::make('Properties', resource: new PropertyResource())
                            ->fields([
                                Text::make('Value')
                            ])->hideOnIndex(),
                        BelongsToMany::make('OptionValues', resource: new OptionResource())
                            ->fields([
                                Text::make('Value')
                            ])->hideOnIndex()
                    ])
                ]),
            ]),

        ];
    }

    public function rules(Model $item): array
    {
        return [];
    }
}
